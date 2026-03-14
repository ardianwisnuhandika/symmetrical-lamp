<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin']);
    }

    public function index()
    {
        $categories = Category::withCount('pjuPoints')->latest()->paginate(15);
        return view('admin.master.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.master.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        Category::create($validated);

        return redirect()->route('admin.master.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('admin.master.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validated = $request->validated();

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return redirect()->route('admin.master.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->pjuPoints()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan.');
        }

        $category->delete();

        return redirect()->route('admin.master.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
