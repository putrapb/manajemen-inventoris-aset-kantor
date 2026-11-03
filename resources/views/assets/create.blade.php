@extends('layouts.app')

@section('title', 'Tambah Aset')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 page-title mb-1">Tambah Aset</h1>
            <p class="text-muted mb-0">Lengkapi data aset kantor yang baru.</p>
        </div>
        <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-3 p-md-4">
            @include('assets._form')
        </div>
    </div>
@endsection
