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
        $filters = $request->only(['status', 'kategori', 'jenis', 'search']);
        $pjuPoints = $this->pjuService->getAll($filters);
        $stats = $this->pjuService->getStats();

        return view('admin.pju.index', compact('pjuPoints', 'stats', 'filters'));
    }

    public function create()
    {
        return view('admin.pju.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|in:pju,rambu,rppj,cermin',
            'jenis' => 'required|in:sonte,led,kalipucang',
            'daya' => 'nullable|string|max:50',
            'letak' => 'required|in:kiri,kanan',
            'type' => 'nullable|string|max:100',
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
        return view('admin.pju.edit', compact('pju'));
    }

    public function update(Request $request, PjuPoint $pju)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|in:pju,rambu,rppj,cermin',
            'jenis' => 'required|in:sonte,led,kalipucang',
            'daya' => 'nullable|string|max:50',
            'letak' => 'required|in:kiri,kanan',
            'type' => 'nullable|string|max:100',
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
