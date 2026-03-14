<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class DesaController extends Controller
{
    public function index(Request $request)
    {
        $query = Desa::with('kecamatan')->withCount('pjuPoints');

        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        $desas = $query->latest()->paginate(15);
        $kecamatans = Kecamatan::active()->orderBy('name')->get();

        return view('admin.master.desas.index', compact('desas', 'kecamatans'));
    }

    public function create()
    {
        $kecamatans = Kecamatan::active()->orderBy('name')->get();
        return view('admin.master.desas.create', compact('kecamatans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:desas,code',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Desa::create($validated);

        return redirect()->route('admin.master.desas.index')
            ->with('success', 'Desa berhasil ditambahkan.');
    }

    public function edit(Desa $desa)
    {
        $kecamatans = Kecamatan::active()->orderBy('name')->get();
        return view('admin.master.desas.edit', compact('desa', 'kecamatans'));
    }

    public function update(Request $request, Desa $desa)
    {
        $validated = $request->validate([
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:desas,code,' . $desa->id,
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $desa->update($validated);

        return redirect()->route('admin.master.desas.index')
            ->with('success', 'Desa berhasil diperbarui.');
    }

    public function destroy(Desa $desa)
    {
        if ($desa->pjuPoints()->count() > 0) {
            return back()->with('error', 'Desa tidak dapat dihapus karena masih digunakan oleh PJU.');
        }

        $desa->delete();

        return redirect()->route('admin.master.desas.index')
            ->with('success', 'Desa berhasil dihapus.');
    }

    public function getByKecamatan(Kecamatan $kecamatan)
    {
        $desas = $kecamatan->desas()->active()->orderBy('name')->get();
        return response()->json($desas);
    }
}
