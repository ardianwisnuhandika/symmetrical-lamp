<?php

namespace App\Http\Controllers;

use App\Models\PjuType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PjuTypeController extends Controller
{
    public function index()
    {
        $pjuTypes = PjuType::withCount('pjuPoints')->latest()->paginate(15);
        return view('admin.master.pju-types.index', compact('pjuTypes'));
    }

    public function create()
    {
        return view('admin.master.pju-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:pju_types,name',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        PjuType::create($validated);

        return redirect()->route('admin.master.pju-types.index')
            ->with('success', 'Jenis PJU berhasil ditambahkan.');
    }

    public function edit(PjuType $pjuType)
    {
        return view('admin.master.pju-types.edit', compact('pjuType'));
    }

    public function update(Request $request, PjuType $pjuType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:pju_types,name,' . $pjuType->id,
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $pjuType->update($validated);

        return redirect()->route('admin.master.pju-types.index')
            ->with('success', 'Jenis PJU berhasil diperbarui.');
    }

    public function destroy(PjuType $pjuType)
    {
        if ($pjuType->pjuPoints()->count() > 0) {
            return back()->with('error', 'Jenis PJU tidak dapat dihapus karena masih digunakan.');
        }

        $pjuType->delete();

        return redirect()->route('admin.master.pju-types.index')
            ->with('success', 'Jenis PJU berhasil dihapus.');
    }
}
