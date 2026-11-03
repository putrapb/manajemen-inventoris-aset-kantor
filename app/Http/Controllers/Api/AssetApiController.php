<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssetApiResource;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

class AssetApiController extends Controller
{
    /**
     * Display a paginated listing of assets.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'status' => ['nullable', Rule::in(Asset::STATUSES)],
        ], [
            'status.in' => 'Status tidak valid. Gunakan tersedia, dipinjam, atau rusak.',
        ]);

        $assets = Asset::query()
            ->with('category')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->orderBy('kode')
            ->paginate(10)
            ->withQueryString();

        return AssetApiResource::collection($assets);
    }
}
