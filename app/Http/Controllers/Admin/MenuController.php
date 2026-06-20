<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MenuController extends Controller
{
    /**
     * Display the main catalog table along with unique categories.
     * Rendered at: GET /admin/menus -> route('admin.menus.index')
     */
    public function index()
    {
        // Retrieve the menus and transform them to include the full asset URL
        $menuItems = Menu::orderBy('category')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'price' => $item->price,
                'category' => $item->category,
                'is_available' => $item->is_available,
                'image_url' => $item->image_path ? asset('storage/' . $item->image_path) : null,
            ];
        });

        // Dynamically extract unique categories directly from your menus table records
        $categories = Menu::select('category', DB::raw('count(*) as total_items'))
            ->groupBy('category')
            ->orderBy('category')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->category,
                    // URL/Key safe slug structure generated from the string name
                    'slug' => strtolower(str_replace(' ', '-', $item->category)),
                    'count' => $item->total_items
                ];
            });

        return Inertia::render('Admin/Menus/Index', [
            'menuItems' => $menuItems,
            'categories' => $categories
        ]);
    }

    /**
     * Show the dedicated form to add a new menu item.
     * Rendered at: GET /admin/menus/create -> route('admin.menus.create')
     */
    public function create() 
    {
        $categories = Menu::select('category', DB::raw('count(*) as total_items'))
            ->groupBy('category')
            ->orderBy('category')
            ->get()
            ->map(fn($item) => [
                'name' => $item->category,
                'slug' => strtolower(str_replace(' ', '-', $item->category)),
                'count' => $item->total_items
            ]);
            
        return Inertia::render('Admin/Menus/Create', ['categories' => $categories]);
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
            'category' => 'required|string',
            'is_available' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        unset($validated['image']);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('menus', 'public');
        }

        Menu::create($validated);

        return redirect()->route('admin.menus.index')->with('success', 'Menu item created successfully!');
    }

    /**
     * Show the dedicated form to edit an existing menu item.
     */
    public function edit(Menu $menu)
    {
        return Inertia::render('Admin/Menus/Edit', [
            'menuItem' => $menu
        ]);
    }

    /**
     * Process the updates made to a menu item.
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'is_available' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($menu->image_path) {
                Storage::disk('public')->delete($menu->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('menus', 'public');
        }

        $menu->update($validated);

        return redirect()->route('admin.menus.index')->with('success', 'Menu item updated successfully!');
    }

    /**
     * Remove an item from the catalog ledger.
     */
    public function destroy(Menu $menu)
    {
        if ($menu->image_path) {
            Storage::disk('public')->delete($menu->image_path);
        }

        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Menu item deleted successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | String-Based Category Management Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Show form to create a new category tracking string.
     * Rendered at: GET /admin/categories/create
     */
    public function createCategory()
    {
        // Fixed: Points to your actual file layout at 'Pages/Admin/Menus/CreateCategory.vue'
        return Inertia::render('Admin/Menus/CreateCategory');
    }

    /**
     * Initialize a new category string by recording a placeholder item.
     * Rendered at: POST /admin/categories
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $categoryName = strtolower(trim($request->name));

        // Optional: Check if the category already exists to avoid redundant strings
        $exists = Menu::where('category', $categoryName)->exists();
        if ($exists) {
            return redirect()->route('admin.menus.index')->with('error', 'Category already exists!');
        }

        // Create a hidden placeholder item so this category immediately populates your tables
        Menu::create([
            'name'         => 'First ' . ucfirst($categoryName) . ' Item (Placeholder)',
            'description'  => 'Temporary placeholder record initializing the ' . $categoryName . ' master group catalog tracking string.',
            'price'        => 0.00,
            'category'     => $categoryName,
            'is_available' => false,
            'image_path'   => null
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'New category tracking string initialized successfully!');
    }


    public function editCategory($categoryName)
    {
        // Pass down the current category string directly as a prop array wrapper
        return Inertia::render('Admin/Menus/EditCategory', [
            'category' => [
                'name' => $categoryName,
                'slug' => strtolower(str_replace(' ', '-', $categoryName))
            ]
        ]);
    }

    /**
     * Update all matching menu items sharing an old category string name to a new one.
     * Rendered at: PUT /admin/categories/{category_name}
     */
    public function updateCategory(Request $request, $categoryName)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Updates the string across all rows currently assigned to it
        Menu::where('category', $categoryName)
            ->update(['category' => strtolower($request->name)]);

        return redirect()->route('admin.menus.index')->with('success', 'Category updated successfully across all items!');
    }

    /**
     * Delete a category by removing or updating items assigned to it.
     * Rendered at: DELETE /admin/categories/{category_name}
     */
    public function destroyCategory($categoryName)
    {
        $items = Menu::where('category', $categoryName)->get();
        
        foreach ($items as $item) {
            if ($item->image_path) {
                Storage::disk('public')->delete($item->image_path);
            }
            $item->delete();
        }

        return redirect()->route('admin.menus.index')->with('success', 'Category and its related items removed successfully!');
    }
}