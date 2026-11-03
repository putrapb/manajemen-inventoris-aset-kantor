<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Models\Asset;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AssetController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Asset::class);

        return view('assets.create', [
            'categories' => Category::query()->orderBy('nama')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssetRequest $request): RedirectResponse
    {
        $asset = Asset::create($request->validated());

        return redirect()->route('assets.create')
            ->with('success', 'Aset "'.$asset->nama.'" berhasil disimpan.')
            ->with('draft_key', $this->draftKey('create', null, $request->user()->id));
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset): View
    {
        $this->authorize('view', $asset);

        $asset->load('category');

        return view('assets.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset): View
    {
        $this->authorize('update', $asset);

        return view('assets.edit', [
            'asset' => $asset,
            'categories' => Category::query()->orderBy('nama')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssetRequest $request, Asset $asset): RedirectResponse
    {
        $asset->update($request->validated());

        return redirect()->route('assets.edit', $asset)
            ->with('success', 'Aset "'.$asset->nama.'" berhasil diperbarui.')
            ->with('draft_key', $this->draftKey('edit', $asset->id, $request->user()->id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset): RedirectResponse
    {
        $this->authorize('delete', $asset);

        $nama = $asset->nama;
        $asset->delete();

        return redirect()->route('assets.create')
            ->with('success', 'Aset "'.$nama.'" berhasil dihapus.')
            ->with('draft_key', $this->draftKey('edit', $asset->id, request()->user()->id));
    }

    private function draftKey(string $mode, ?int $assetId, int $userId): string
    {
        return $mode === 'edit'
            ? 'asset-draft:edit:'.$assetId.':'.$userId
            : 'asset-draft:create:'.$userId;
    }
}
