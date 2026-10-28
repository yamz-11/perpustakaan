<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('category.index', ['categories' => Category::all()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('admin');
        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255'
        ]);
        Category::create([
            'title' => $request->title
        ]);

        return redirect()->route('category.index')->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        Gate::authorize('admin');
        return view('category.edit', compact('category')); {
            // Gate::authorize('admin');

            // Lakukan pengecekan apakah pengguna memiliki hak akses sebagai admin
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        // Practical
        // $buku->title = $request->title;
        // $buku->save();

        // Eloquent Way - Readable
        $category->update([
            'title' => ucfirst($request->title),
        ]);
        return redirect()->route('category.index')->with('success', 'buku category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    { {
            Gate::authorize('admin');
            $category->delete();
            return redirect()->route('category.index')->with('success', 'Category deleted successfully!');
        }
    }
}
