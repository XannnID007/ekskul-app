@extends('layouts.pembina')

@section('title', 'Profil Saya')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Profil Saya</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('pembina.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Profil Saya</li>
        </ol>

        <div class="row">
            <div class="col-lg-4 mb-4">
                <!-- Profile Picture and Basic Info -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <i class="fas fa-user-circle me-1"></i> Informasi Dasar
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <div class="d-flex justify-content-center">
                                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center"
                                    style="width: 120px; height: 120px; font-size: 3rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            </div>
                        </div>
                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <p class="text-muted">Pembina Ekstrakurikuler</p>
                        <p class="mb-0"><strong>Spesialisasi:</strong> {{ $coach->specialty ?? 'Belum diisi' }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-info-circle me-1"></i> Informasi Tambahan
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Pengalaman</small>
                            <p class="mb-0">{{ $coach->experience ? $coach->experience . ' tahun' : '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Sertifikat</small>
                            <p class="mb-0">{{ $coach->certificates ?? '-' }}</p>
                        </div>
                        <div>
                            <small class="text-muted d-block mb-1">Ekstrakurikuler yang Dibina</small>
                            @if ($coach->extracurriculars->count() > 0)
                                <ul class="list-group list-group-flush">
                                    @foreach ($coach->extracurriculars as $extracurricular)
                                        <li class="list-group-item px-0">
                                            <a
                                                href="{{ route('pembina.extracurriculars.show', $extracurricular->id) }}">{{ $extracurricular->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="mb-0">Belum ada ekstrakurikuler yang dibina</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <!-- Edit Profile -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-edit me-1"></i> Edit Profil
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pembina.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="specialty" class="form-label">Spesialisasi</label>
                                <input type="text" class="form-control @error('specialty') is-invalid @enderror"
                                    id="specialty" name="specialty" value="{{ old('specialty', $coach->specialty) }}">
                                @error('specialty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="experience" class="form-label">Pengalaman (tahun)</label>
                                <input type="number" class="form-control @error('experience') is-invalid @enderror"
                                    id="experience" name="experience" value="{{ old('experience', $coach->experience) }}"
                                    min="0">
                                @error('experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="certificates" class="form-label">Sertifikat</label>
                                <textarea class="form-control @error('certificates') is-invalid @enderror" id="certificates" name="certificates"
                                    rows="3">{{ old('certificates', $coach->certificates) }}</textarea>
                                <small class="text-muted">Pisahkan dengan baris baru untuk setiap sertifikat</small>
                                @error('certificates')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-key me-1"></i> Ubah Password
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pembina.profile.update-password') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="current_password" class="form-label">Password Saat Ini</label>
                                <input type="password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    id="current_password" name="current_password" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-key me-1"></i> Ubah Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
