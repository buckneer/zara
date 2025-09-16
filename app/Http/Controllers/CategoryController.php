<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
   
    public function index()
    {
        $categories = Category::with('parent')->orderBy('position')->paginate(25);
        return request()->wantsJson() ? response()->json($categories) : view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::orderBy('name')->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'slug' => 'required|string|max:191|unique:categories,slug',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'position' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        $category = Category::create($data);

        return $request->wantsJson()
            ? response()->json($category, 201)
            : redirect()->route('categories.index')->with('success', 'Category created.');
    }

    public function show(Category $category)
    {
        return request()->wantsJson() ? response()->json($category) : view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $parents = Category::where('id', '!=', $category->id)->orderBy('name')->get();
        return view('admin.categories.edit', compact('category','parents'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'slug' => ['required','string','max:191', Rule::unique('categories','slug')->ignore($category->id)],
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'position' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        $category->update($data);

        return $request->wantsJson()
            ? response()->json($category)
            : redirect()->route('categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Request $request, Category $category)
    {
        $category->delete();
        return $request->wantsJson()
            ? response()->json(['message'=>'deleted'])
            : redirect()->route('categories.index')->with('success', 'Category deleted.');
    }
}
