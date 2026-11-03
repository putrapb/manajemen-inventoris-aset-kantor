@extends('layouts.app')

@section('title', 'Detail Aset')

@section('content')
    @php
        $badge = [
            'tersedia' => 'success',
            'dipinjam' => 'warning',
            'rusak' => 'danger',
        ][$asset->status] ?? 'secondary';
    @endphp

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 page-title mb-1">Detail Aset</h1>
            <p class="text-muted mb-0">{{ $asset->kode }}</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            @can('update', $asset)
                <a href="{{ route('assets.edit', $asset) }}" class="btn btn-primary">
                    <i class="bi bi-pencil-square me-1"></i> Edit Aset
                </a>
            @endcan
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <h2 class="h4 mb-1">{{ $asset->nama }}</h2>
                    <span class="badge text-bg-dark">{{ $asset->kode }}</span>
                    <span class="badge text-bg-{{ $badge }}">{{ ucfirst($asset->status) }}</span>
                </div>
                <div class="text-lg-end">
                    <div class="text-muted small">Nilai Aset</div>
                    <div class="fs-4 fw-bold">Rp {{ number_format((float) $asset->nilai, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="border rounded p-3 h-100">
                        <div class="text-muted small">Kategori</div>
                        <div class="fw-semibold">{{ $asset->category?->nama ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="border rounded p-3 h-100">
                        <div class="text-muted small">Lokasi</div>
                        <div class="fw-semibold">{{ $asset->lokasi }}</div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="border rounded p-3 h-100">
                        <div class="text-muted small">Status</div>
                        <div class="fw-semibold">{{ ucfirst($asset->status) }}</div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="border rounded p-3 h-100">
                        <div class="text-muted small">Tanggal Beli</div>
                        <div class="fw-semibold">{{ $asset->tanggal_beli?->translatedFormat('d F Y') }}</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="border rounded p-3">
                        <div class="text-muted small">Terakhir Diperbarui</div>
                        <div class="fw-semibold">{{ $asset->updated_at?->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
