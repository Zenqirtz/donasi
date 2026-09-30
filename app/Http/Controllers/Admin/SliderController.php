<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    /**
     * index
     */
    public function index()
    {
        $sliders = Slider::orderBy('order')->latest()->paginate(5);

        return view('admin.slider.index', compact('sliders'));
    }

    /**
     * store
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'link' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $image = $request->file('image');
        $image->storeAs('public/sliders', $image->hashName());

        $slider = Slider::create([
            'image' => $image->hashName(),
            'link' => $validated['link'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
            // Nomor urut dibuat manual supaya slider tetap tampil sesuai urutan
            // dan tidak bentrok, Unique constraint di tabel sudah dilepas.
            'order' => (int) Slider::max('order') + 1,
        ]);

        return redirect()->route('admin.slider.index')
            ->with($slider ? ['success' => 'Data Berhasil Disimpan!'] : ['error' => 'Data Gagal Disimpan!']);
    }

    /**
     * destroy
     */
    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);

        Storage::disk('local')->delete('public/sliders/'.basename($slider->getRawOriginal('image')));

        $slider->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
