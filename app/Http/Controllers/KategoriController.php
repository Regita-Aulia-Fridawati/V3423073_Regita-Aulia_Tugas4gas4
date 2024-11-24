<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::orderBy('nama', 'asc')->get();
        return view('kategori.kategori', ['kategoris' => $kategoris]);
    }

    public function create()
    {
        return view('kategori.kategori-add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:100|unique:kategoris,nama',
        ]);

        Kategori::create($request->all());
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('kategori.kategori-edit', ['kategori' => $kategori]);
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama' => 'required|max:100|unique:kategoris,nama,' . $kategori->id_kategori . ',id_kategori',
        ]);

        $kategori->update($request->all());
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');

    }
}

