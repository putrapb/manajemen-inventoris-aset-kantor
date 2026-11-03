@php
    $asset = $asset ?? null;
    $isEdit = $asset !== null;
    $draftKey = $isEdit
        ? 'asset-draft:edit:'.$asset->id.':'.auth()->id()
        : 'asset-draft:create:'.auth()->id();
@endphp

<form method="POST"
    action="{{ $isEdit ? route('assets.update', $asset) : route('assets.store') }}"
    data-draft-form
    data-draft-key="{{ $draftKey }}"
    autocomplete="off">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="row g-3">
        <div class="col-12 col-md-6">
            <label for="nama" class="form-label">Nama Aset <span class="text-danger">*</span></label>
            <input type="text" id="nama" name="nama" value="{{ old('nama', $asset?->nama ?? '') }}"
                class="form-control @error('nama') is-invalid @enderror" maxlength="150" required
                placeholder="Contoh: Laptop Dell Latitude">
            @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-6">
            <label for="kode" class="form-label">Kode Unik <span class="text-danger">*</span></label>
            <input type="text" id="kode" name="kode" value="{{ old('kode', $asset?->kode ?? '') }}"
                class="form-control text-uppercase @error('kode') is-invalid @enderror" maxlength="50" required
                placeholder="Contoh: AST-001">
            @error('kode')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-6">
            <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
            <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                <option value="">— Pilih kategori —</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) old('category_id', $asset?->category_id ?? '') === (string) $category->id)>
                        {{ $category->nama }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-6">
            <label for="lokasi" class="form-label">Lokasi <span class="text-danger">*</span></label>
            <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $asset?->lokasi ?? '') }}"
                class="form-control @error('lokasi') is-invalid @enderror" maxlength="150" required
                placeholder="Contoh: Lantai 2 / Gudang Utama">
            @error('lokasi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-6">
            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                @foreach (\App\Models\Asset::STATUSES as $status)
                    <option value="{{ $status }}" @selected(old('status', $asset?->status ?? 'tersedia') === $status)>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-6">
            <label for="tanggal_beli" class="form-label">Tanggal Beli <span class="text-danger">*</span></label>
            <input type="date" id="tanggal_beli" name="tanggal_beli"
                value="{{ old('tanggal_beli', $asset?->tanggal_beli?->format('Y-m-d') ?? '') }}"
                class="form-control @error('tanggal_beli') is-invalid @enderror" required>
            @error('tanggal_beli')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-6">
            <label for="nilai" class="form-label">Nilai Aset (Rp) <span class="text-danger">*</span></label>
            <input type="number" id="nilai" name="nilai" step="0.01" min="0"
                value="{{ old('nilai', $isEdit ? number_format((float) $asset->nilai, 2, '.', '') : '') }}"
                class="form-control @error('nilai') is-invalid @enderror" required placeholder="0.00">
            @error('nilai')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-4">
        <div class="d-flex align-items-center gap-2">
            <small class="text-muted draft-indicator" data-draft-status>
                Isian form disimpan otomatis sebagai draft di perangkat ini.
            </small>
            <button type="button" class="btn btn-link btn-sm p-0 d-none" data-draft-clear-button>
                Hapus draft
            </button>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> {{ $isEdit ? 'Perbarui Aset' : 'Simpan Aset' }}
            </button>
        </div>
    </div>
</form>
