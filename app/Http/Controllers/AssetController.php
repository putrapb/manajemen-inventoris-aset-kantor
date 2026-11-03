<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetIndexRequest;
use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Models\Asset;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AssetIndexRequest $request): View
    {
        $this->authorize('viewAny', Asset::class);

        $filters = $this->filters($request);

        $assets = $this->filteredQuery($filters)
            ->orderBy('kode')
            ->paginate(10)
            ->withQueryString();

        return view('assets.index', [
            'assets' => $assets,
            'categories' => Category::query()->orderBy('nama')->get(),
            'filters' => $filters,
        ]);
    }

    /**
     * Export the filtered asset list as a CSV file.
     */
    public function export(AssetIndexRequest $request): StreamedResponse
    {
        $this->authorize('viewAny', Asset::class);

        $filename = 'aset-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($request) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Kode', 'Nama', 'Kategori', 'Lokasi', 'Status', 'Tanggal Beli', 'Nilai'], ',', '"', '');

            $this->filteredQuery($this->filters($request))
                ->orderBy('kode')
                ->lazy(500)
                ->each(function (Asset $asset) use ($handle) {
                    fputcsv($handle, [
                        $this->csvCell($asset->kode),
                        $this->csvCell($asset->nama),
                        $this->csvCell($asset->category?->nama),
                        $this->csvCell($asset->lokasi),
                        $this->csvCell($asset->status),
                        $asset->tanggal_beli?->format('Y-m-d'),
                        $asset->nilai,
                    ], ',', '"', '');
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

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

        return redirect()->route('assets.index')
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

        return redirect()->route('assets.index')
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

        return redirect()->route('assets.index')
            ->with('success', 'Aset "'.$nama.'" berhasil dihapus.')
            ->with('draft_key', $this->draftKey('edit', $asset->id, request()->user()->id));
    }

    /**
     * @return array{search: string|null, category_id: string|null, status: string|null}
     */
    private function filters(AssetIndexRequest $request): array
    {
        return [
            'search' => $request->validated('search'),
            'category_id' => $request->validated('category_id'),
            'status' => $request->validated('status'),
        ];
    }

    /**
     * @param  array{search: string|null, category_id: string|null, status: string|null}  $filters
     */
    private function filteredQuery(array $filters): Builder
    {
        $search = $filters['search'];
        $categoryId = $filters['category_id'];
        $status = $filters['status'];

        return Asset::query()
            ->with('category')
            ->when(filled($search), function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nama', 'like', '%'.$search.'%')
                        ->orWhere('kode', 'like', '%'.$search.'%');
                });
            })
            ->when(filled($categoryId), fn ($query) => $query->where('category_id', $categoryId))
            ->when(filled($status), fn ($query) => $query->where('status', $status));
    }

    private function csvCell(?string $value): string
    {
        $value = (string) $value;

        return preg_match('/^[=+\-@]/', $value) === 1 ? "'".$value : $value;
    }

    private function draftKey(string $mode, ?int $assetId, int $userId): string
    {
        return $mode === 'edit'
            ? 'asset-draft:edit:'.$assetId.':'.$userId
            : 'asset-draft:create:'.$userId;
    }
}
