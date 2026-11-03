@extends('layouts.app')

@section('title', 'Buat Akun')

@section('content')
    <div class="auth-wrapper">
        <div class="card shadow-sm auth-card">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="display-6 text-primary mb-2"><i class="bi bi-person-plus"></i></div>
                    <h1 class="h3 page-title">Buat Akun Staff</h1>
                    <p class="text-muted mb-0">Akun baru otomatis memiliki akses staff.</p>
                </div>

                <form method="POST" action="{{ route('register.store') }}" class="vstack gap-3">
                    @csrf

                    <div>
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror" required autofocus autocomplete="name"
                            placeholder="Nama lengkap">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror" required autocomplete="email"
                            placeholder="nama@example.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password"
                            placeholder="Minimal 8 karakter">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                            required autocomplete="new-password" placeholder="Ulangi password">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="bi bi-person-check me-1"></i> Daftar
                    </button>
                </form>

                <p class="text-center text-muted small mt-4 mb-0">
                    Sudah punya akun?
                    <a href="{{ route('login') }}">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
@endsection
