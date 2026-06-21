<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MenuController extends Controller
{
    /**
     * Display the main catalog table.
     */
    public function index()
    {
        // Load the relationship and sort menu items by category name
        $menuItems = Menu::with('category')
            ->select('menus.*') 
            ->join('categories', 'menus.category_id', '=', 'categories.id')
            ->orderBy('categories.name', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'price' => $item->price,
                    'category' => $item->category ? $item->category->name : 'Uncategorized',
                    'category_id' => $item->category_id,
                    'is_available' => $item->is_available,
                    'image_url' => $item->image_path ? asset('storage/' . $item->image_path) : null,
                ];
            });

        // Fetch all categories with total menu item metrics for the side panels/filters
        $categories = Category::withCount('menus')->get()->map(function ($cat) {
            return [
                'id' => $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug,
                'count' => $cat->menus_count
            ];
        });

        return Inertia::render('Admin/Menus/Index', [
            'menuItems' => $menuItems,
            'categories' => $categories
        ]);
    }

    /**
     * Show the dedicated form to add a new menu item.
     */
    public function create() 
    {
        return Inertia::render('Admin/Menus/Create', [
            'categories' => Category::all()
        ]);
    }

    /**
     * Process the creation of a new item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_available' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('menus', 'public');
        }

        Menu::create($validated);

        return redirect()->route('admin.menus.index')->with('success', 'Menu item created successfully!');
    }

    /**
     * Show the edit form for a specific item.
     */
    public function edit(Menu $menu)
    {
        return Inertia::render('Admin/Menus/Edit', [
            'menuItem' => $menu,
            'categories' => Category::all()
        ]);
    }

    /**
     * Update an existing menu item.
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_available' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Drop old asset if replacing it
            if ($menu->image_path) {
                Storage::disk('public')->delete($menu->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('menus', 'public');
        }

        $menu->update($validated);

        return redirect()->route('admin.menus.index')->with('success', 'Menu item updated successfully!');
    }

    /**
     * Delete a menu item and its files.
     */
    public function destroy(Menu $menu)
    {
        if ($menu->image_path) {
            Storage::disk('public')->delete($menu->image_path);
        }
        
        $menu->delete();
        
        return redirect()->route('admin.menus.index')->with('success', 'Menu item deleted successfully!');
    }
}