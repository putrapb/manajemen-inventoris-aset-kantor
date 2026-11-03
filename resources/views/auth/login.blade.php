@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="auth-wrapper">
        <div class="card shadow-sm auth-card">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="display-6 text-primary mb-2"><i class="bi bi-box-seam"></i></div>
                    <h1 class="h3 page-title">Masuk ke Inventaris</h1>
                    <p class="text-muted mb-0">Kelola aset kantor dengan aman.</p>
                </div>

                <form method="POST" action="{{ route('login.store') }}" class="vstack gap-3">
                    @csrf

                    <div>
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror" required autofocus autocomplete="email"
                            placeholder="admin@example.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password"
                            placeholder="Password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                    </button>
                </form>

                <p class="text-center text-muted small mt-4 mb-0">
                    Belum punya akun?
                    <a href="{{ route('register') }}">Buat akun staff</a>
                </p>
            </div>
        </div>
    </div>
@endsection
