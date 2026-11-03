@extends('layouts.app')

@section('title', 'Daftar Aset')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 page-title mb-1">Daftar Aset Kantor</h1>
            <p class="text-muted mb-0">Cari, filter, dan kelola seluruh aset kantor.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('assets.export', request()->query()) }}" class="btn btn-outline-success">
                <i class="bi bi-filetype-csv me-1"></i> Export CSV
            </a>
            @can('create', App\Models\Asset::class)
                <a href="{{ route('assets.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Aset
                </a>
            @endcan
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('assets.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-lg-5">
                    <label for="search" class="form-label">Cari Aset</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" id="search" name="search" value="{{ $filters['search'] }}"
                            class="form-control" placeholder="Nama atau kode aset">
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <label for="category_id" class="form-label">Kategori</label>
                    <select id="category_id" name="category_id" class="form-select">
                        <option value="">Semua kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) $filters['category_id'] === (string) $category->id)>
                                {{ $category->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-6 col-lg-2">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="">Semua status</option>
                        @foreach (App\Models\Asset::STATUSES as $status)
                            <option value="{{ $status }}" @selected($filters['status'] === $status)>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary" title="Reset filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2 py-3">
            <span>Data Aset</span>
            <span class="text-muted small">
                @if ($assets->total() > 0)
                    Menampilkan {{ $assets->firstItem() }}–{{ $assets->lastItem() }} dari {{ $assets->total() }} aset
                @else
                    Tidak ada data
                @endif
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Kode</th>
                        <th scope="col">Nama Aset</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Lokasi</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end">Nilai</th>
                        <th scope="col">Tanggal Beli</th>
                        <th scope="col" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assets as $asset)
                        @php
                            $badge = [
                                'tersedia' => 'success',
                                'dipinjam' => 'warning',
                                'rusak' => 'danger',
                            ][$asset->status] ?? 'secondary';
                        @endphp
                        <tr>
                            <td><span class="badge text-bg-dark">{{ $asset->kode }}</span></td>
                            <td class="fw-semibold">{{ $asset->nama }}</td>
                            <td>{{ $asset->category?->nama ?? '—' }}</td>
                            <td>{{ $asset->lokasi }}</td>
                            <td><span class="badge text-bg-{{ $badge }}">{{ ucfirst($asset->status) }}</span></td>
                            <td class="text-end text-nowrap">Rp {{ number_format((float) $asset->nilai, 0, ',', '.') }}</td>
                            <td class="text-nowrap">{{ $asset->tanggal_beli?->format('d/m/Y') }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('assets.show', $asset) }}" class="btn btn-sm btn-outline-secondary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('update', $asset)
                                    <a href="{{ route('assets.edit', $asset) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                @endcan
                                @can('delete', $asset)
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus"
                                        data-bs-toggle="modal" data-bs-target="#deleteAssetModal"
                                        data-delete-url="{{ route('assets.destroy', $asset) }}"
                                        data-asset-name="{{ $asset->nama }} ({{ $asset->kode }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="fs-1 mb-2"><i class="bi bi-inbox"></i></div>
                                    <p class="mb-1 fw-semibold">Tidak ada aset yang cocok.</p>
                                    <p class="mb-0 small">Coba ubah kata kunci atau filter yang digunakan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($assets->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $assets->links() }}
            </div>
        @endif
    </div>

    @can('create', App\Models\Asset::class)
        @push('modals')
            <div class="modal fade" id="deleteAssetModal" tabindex="-1" aria-labelledby="deleteAssetModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteAssetModalLabel">Hapus Aset</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <p class="mb-2">Anda yakin ingin menghapus aset berikut?</p>
                                <p class="fw-semibold mb-0" data-delete-name>—</p>
                                <p class="text-danger small mt-3 mb-0">Tindakan ini tidak dapat dibatalkan.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash me-1"></i> Hapus Aset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endpush
    @endcan
@endsection
