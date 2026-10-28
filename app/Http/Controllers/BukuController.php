<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;


class BukuController extends Controller
{

    public function index(): View
    {
        return view('bukus.index', ['bukus' => Buku::all()]);
    }

    public function create()
    {
        Gate::authorize('admin');
        $categories = Category::all();
        return view('bukus.create', compact('categories'));
    }

    public function store(Request $request, Buku $buku)
    {
        $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'year' => 'required|digits:4|integer',
            'copies_in_circulation' => 'required|integer',
            'category_id' => [
                'nullable',
                Rule::exists('categories', 'id'),

            ]
        ]);

        $buku = Buku::create([
            'title' => ucfirst($request->title),
            'author' => ucfirst($request->author),
            'year' => ($request->year),
            'copies_in_circulation' => ($request->copies_in_circulation),
            'category_id' => $request->category_id
        ]);
        // dd($buku);
        return redirect()->route('bukus.index')->with('success', 'buku created successfully!');
    }

    public function edit(Buku $buku)
    {
        Gate::authorize('admin');
        $categories = Category::all(); {
            // dd($buku);
            return view('bukus.edit', compact('buku', 'categories'));
        }
    }

    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'year' => 'required|digits:4|integer',
            'copies_in_circulation' => 'required|integer',
            'category_id' => [
                'nullable',
                Rule::exists('categories', 'id'),

            ]
        ]);

        $buku->update([
            'title' => ucfirst($request->title),
            'author' => ucfirst($request->author),
            'year' => ($request->year),
            'copies_in_circulation' => ($request->copies_in_circulation),
            'category_id' => $request->category_id
        ]);
        return redirect()->route('bukus.index')->with('success', 'buku updated successfully!');
    }

    public function destroy(Buku $buku)
    { {
            Gate::authorize('admin');
            $buku->delete();
            return redirect()->route('bukus.index')->with('success', 'Buku deleted successfully!');
        }
    }
}
