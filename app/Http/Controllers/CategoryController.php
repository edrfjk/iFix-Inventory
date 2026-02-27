<?php
namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;
 
class CategoryController extends Controller {
 
    public function index() {
        $categories = Category::withCount('products')->latest()->paginate(15);
        return view('categories.index', compact('categories'));
    }
 
    public function store(Request $request) {
        $request->validate(['name' => 'required|string|max:255|unique:categories,name']);
        Category::create($request->only('name', 'description'));
        return redirect()->route('categories.index')
            ->with('success', 'Category created!');
    }
 
    public function update(Request $request, Category $category) {
        $request->validate(['name' => 'required|string|max:255|unique:categories,name,' . $category->id]);
        $category->update($request->only('name', 'description'));
        return redirect()->route('categories.index')
            ->with('success', 'Category updated!');
    }
 
    public function destroy(Category $category) {
        $category->delete();
        return redirect()->route('categories.index')
            ->with('success', 'Category deleted.');
    }
}
