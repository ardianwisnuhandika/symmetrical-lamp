<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    public function index()
    {
        $kecamatans = Kecamatan::withCount(['desas', 'pjuPoints'])->latest()->paginate(15);
        return view('admin.master.kecamatans.index', compact('kecamatans'));
    }

    public function create()
    {
        return view('admin.master.kecamatans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:kecamatans,name',
            'code' => 'required|string|max:50|unique:kecamatans,code',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Kecamatan::create($validated);

        return redirect()->route('admin.master.kecamatans.index')
            ->with('success', 'Kecamatan berhasil ditambahkan.');
    }

    public function edit(Kecamatan $kecamatan)
    {
        return view('admin.master.kecamatans.edit', compact('kecamatan'));
    }

    public function update(Request $request, Kecamatan $kecamatan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:kecamatans,name,' . $kecamatan->id,
            'code' => 'required|string|max:50|unique:kecamatans,code,' . $kecamatan->id,
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $kecamatan->update($validated);

        return redirect()->route('admin.master.kecamatans.index')
            ->with('success', 'Kecamatan berhasil diperbarui.');
    }

    public function destroy(Kecamatan $kecamatan)
    {
        if ($kecamatan->pjuPoints()->count() > 0) {
            return back()->with('error', 'Kecamatan tidak dapat dihapus karena masih digunakan oleh PJU.');
        }

        $kecamatan->delete();

        return redirect()->route('admin.master.kecamatans.index')
            ->with('success', 'Kecamatan berhasil dihapus.');
    }
}
