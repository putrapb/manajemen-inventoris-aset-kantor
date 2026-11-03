@extends('layouts.app')

@section('title', 'Edit Aset')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 page-title mb-1">Edit Aset</h1>
            <p class="text-muted mb-0">Perbarui data aset {{ $asset->kode }}.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('assets.show', $asset) }}" class="btn btn-outline-secondary">
                <i class="bi bi-eye me-1"></i> Detail
            </a>
            <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-3 p-md-4">
            @include('assets._form')
        </div>
    </div>
@endsection
