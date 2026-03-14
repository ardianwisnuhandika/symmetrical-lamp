<?php

namespace App\Http\Controllers;

use App\Models\PjuPoint;
use App\Services\PjuService;
use Illuminate\Http\Request;

class PjuController extends Controller
{
    public function __construct(protected PjuService $pjuService)
    {
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'category_id', 'pju_type_id', 'kecamatan_id', 'desa_id', 'search']);
        $pjuPoints = $this->pjuService->getAll($filters);
        $stats = $this->pjuService->getStats();

        return view('admin.pju.index', compact('pjuPoints', 'stats', 'filters'));
    }

    public function create()
    {
        $categories = \App\Models\Category::active()->get();
        $pjuTypes = \App\Models\PjuType::active()->get();
        $kecamatans = \App\Models\Kecamatan::active()->get();
        
        return view('admin.pju.create', compact('categories', 'pjuTypes', 'kecamatans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'pju_type_id' => 'required|exists:pju_types,id',
            'daya' => 'nullable|string|max:50',
            'letak' => 'required|in:kiri,kanan',
            'type' => 'nullable|string|max:100',
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
            'desa_id' => 'nullable|exists:desas,id',
            'lat' => 'required|numeric|between:-90,90',
            'long' => 'required|numeric|between:-180,180',
            'status' => 'required|in:normal,mati',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pju-photos', 'public');
        }

        $this->pjuService->create($validated);

        return redirect()->route('admin.pju.index')
            ->with('success', 'Data PJU berhasil ditambahkan.');
    }

    public function edit(PjuPoint $pju)
    {
        $categories = \App\Models\Category::active()->get();
        $pjuTypes = \App\Models\PjuType::active()->get();
        $kecamatans = \App\Models\Kecamatan::active()->get();
        $desas = $pju->kecamatan_id ? \App\Models\Desa::where('kecamatan_id', $pju->kecamatan_id)->active()->get() : collect();
        
        return view('admin.pju.edit', compact('pju', 'categories', 'pjuTypes', 'kecamatans', 'desas'));
    }

    public function update(Request $request, PjuPoint $pju)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'pju_type_id' => 'required|exists:pju_types,id',
            'daya' => 'nullable|string|max:50',
            'letak' => 'required|in:kiri,kanan',
            'type' => 'nullable|string|max:100',
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
            'desa_id' => 'nullable|exists:desas,id',
            'lat' => 'required|numeric|between:-90,90',
            'long' => 'required|numeric|between:-180,180',
            'status' => 'required|in:normal,mati',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pju-photos', 'public');
        }

        $this->pjuService->update($pju, $validated);

        return redirect()->route('admin.pju.index')
            ->with('success', 'Data PJU berhasil diperbarui.');
    }

    public function destroy(PjuPoint $pju)
    {
        $this->pjuService->delete($pju);
        return redirect()->route('admin.pju.index')
            ->with('success', 'Data PJU berhasil dihapus.');
    }

    public function verify(Request $request, PjuPoint $pju)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        $this->pjuService->verify($pju, $validated['action']);

        return back()->with('success', 'Status verifikasi berhasil diperbarui.');
    }
}
