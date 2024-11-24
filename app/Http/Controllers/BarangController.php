<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Tag;
use Exception;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barang = Barang::with('tags')->orderBy('name', 'asc')->get();

        return view('barang.barang', compact('barang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::orderBy('nama', 'asc')->get(); // Ambil semua kategori
        $tags = Tag::orderBy('name', 'asc')->get();
        return view('barang.barang-add', compact('kategoris', 'tags')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data yang diterima dari form
        $validated = $request->validate([
            'name' => 'required|max:100|unique:barangs',
            'category' => 'required|exists:kategoris,nama',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'stock' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'tags' => 'array|exists:tags,id_tag',  // Validasi array tag
            'note' => 'max:1000',
        ]);

        // Ambil semua input dari request
        $input = $request->all();

        // Proses gambar jika ada
        if ($image = $request->file('image')) {
            $destinationPath = 'images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        }

        // Simpan data barang
        $barang = Barang::create($input); // Simpan barang pertama kali

        // Sync tags (memilih banyak tag untuk satu barang)
        if ($request->has('tags')) {
            $barang->tags()->sync($request->tags);  // Menyinkronkan tag yang dipilih dengan barang
        }

        // Menampilkan pesan sukses
        Alert::success('Success', 'Barang has been saved!');
        return redirect('/barang');
    }


    /**
     * Display the specified resource.
     */
    public function show(Barang $barang)
    {
    $barang = Barang::with('tags')->findOrFail($barang -> id_barang); // Mengambil barang beserta tags

    return view('barang.barang-show', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_barang)
    {
        $barang = Barang::findOrFail($id_barang);
        $kategoris = Kategori::orderBy('nama', 'asc')->get();
        $tags = Tag::orderBy('name', 'asc')->get(); // Ambil data tags

        return view('barang.barang-edit', compact('barang', 'kategoris', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_barang)
    {
        $barang = Barang::findOrFail($id_barang);

        // Validasi data
        $validated = $request->validate([
            'name' => 'required|max:100|unique:barangs,name,' . $id_barang . ',id_barang',
            'category' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg', // Tambahkan nullable
            'stock' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'note' => 'nullable|max:1000', // Note juga opsional
        ]);

        // Cek jika ada file gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($barang->image) {
                $oldImagePath = public_path('images/' . $barang->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Upload gambar baru
            $image = $request->file('image');
            $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $validated['image'] = $imageName;
        } else {
            // Tetap gunakan gambar lama jika tidak ada gambar baru
            $validated['image'] = $barang->image;
        }

        // Update data barang
        $barang->update($validated);

        // Tampilkan pesan sukses
        Alert::info('Success', 'Barang has been updated!');
        return redirect('/barang');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_barang)
    {
        try {
            $deletedbarang = Barang::findOrFail($id_barang);
    
            // Hapus file gambar jika ada
            if ($deletedbarang->image) {
                $imagePath = public_path('images/' . $deletedbarang->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
    
            $deletedbarang->delete();
    
            Alert::error('Success', 'Barang has been deleted !');
            return redirect('/barang');
        } catch (Exception $ex) {
            Alert::warning('Error', 'Cant deleted, Barang already used !');
            return redirect('/barang');
        }
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'category', 'nama'); // 'category' di tabel 'barangs', 'nama' di tabel 'kategoris'
    }


}