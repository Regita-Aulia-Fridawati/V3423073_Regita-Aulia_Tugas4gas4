<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Exception;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barang = Barang::orderBy('name', 'asc')->get();

        return view('barang.barang', [
            'barang' => $barang
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('barang.barang-add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100|unique:barangs',
            'category' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'stock' => 'required',
            'price' => 'required',
            'note' => 'max:1000',
        ]);
        $input = $request->all();

        if ($image = $request->file('image')) {
            $destinationPath = 'images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        }


        Barang::create($input);

        Alert::success('Success', 'Barang has been saved !');
        return redirect('/barang');
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang)
    {
        // Mencari barang berdasarkan ID
        $barang = Barang::find($barang->id_barang);

        // Menampilkan view dan mengirim data barang
        return view('barang.barang-show', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_barang)
    {
        $barang = barang::findOrFail($id_barang);

        return view('barang.barang-edit', [
            'barang' => $barang,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_barang)
    {
        $barang = Barang::findOrFail($id_barang);

        // Validasi data, buat gambar opsional (nullable)
        $validated = $request->validate([
            'name' => 'required|max:100|unique:barangs,name,' . $id_barang . ',id_barang',
            'category' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',  // Nullable, agar gambar opsional
            'stock' => 'required',
            'price' => 'required',
            'note' => 'max:1000',
        ]);

        // Cek jika ada file gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama
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
}