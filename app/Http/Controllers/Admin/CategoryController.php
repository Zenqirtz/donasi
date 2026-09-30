<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * index
     */
    public function index()
    {
        $categories = Category::paginate(10);

        return view('admin.category.index', compact('categories'));
    }

    /**
     * create
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * store
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $image = $request->file('image');
        $image->storeAs('public/categories', $image->hashName());

        $category = Category::create([
            'image' => $image->hashName(),
            'name' => $validated['name'],
            'slug' => $this->uniqueSlug($validated['name']),
        ]);

        return redirect()->route('admin.category.index')
            ->with($category ? ['success' => 'Data Berhasil Disimpan!'] : ['error' => 'Data Gagal Disimpan!']);
    }

    /**
     * edit
     */
    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    /**
     * update
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2000',
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
        ]);

        $attributes = [
            'name' => $validated['name'],
            'slug' => $this->uniqueSlug($validated['name'], $category->id),
        ];

        if ($request->hasFile('image')) {
            Storage::disk('local')->delete('public/categories/'.basename($category->getRawOriginal('image')));

            $image = $request->file('image');
            $image->storeAs('public/categories', $image->hashName());

            $attributes['image'] = $image->hashName();
        }

        $category->update($attributes);

        return redirect()->route('admin.category.index')
            ->with(['success' => 'Data Berhasil Diupdate!']);
    }

    /**
     * destroy
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        Storage::disk('local')->delete('public/categories/'.basename($category->getRawOriginal('image')));
        $category->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }

    /**
     * Slug unik dari nama, ditambahkan sufiks bila slug sudah dipakai.
     */
    protected function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name, '-') ?: 'category';
        $slug = $base;
        $suffix = 1;

        while (Category::withTrashed()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $base.'-'.(++$suffix);
        }

        return $slug;
    }
}
