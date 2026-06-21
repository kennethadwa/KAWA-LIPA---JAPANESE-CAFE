<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        return Inertia::render('Admin/Menus/CategoryIndex', [
            'categories' => Category::all()
        ]);
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return Inertia::render('Admin/Menus/CreateCategory');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:categories,name|max:255',
        ]);

        Category::create($validated);

        return redirect()->route('admin.menus.index')->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified category.
     * Uses Implicit Route Model Binding to fetch the record automatically.
     */
    public function edit(Category $category)
    {
        return Inertia::render('Admin/Menus/EditCategory', [
            'category' => $category
        ]);
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id . '|max:255',
        ]);

        $category->update($validated);

        return redirect()->route('admin.menus.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Category deleted successfully.');
    }
}