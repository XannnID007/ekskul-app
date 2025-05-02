@extends('layouts.siswa')

@section('title', 'Rekomendasi Ekstrakurikuler')

@push('styles')
    <style>
        .recommendation-card {
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 30px;
            height: 100%;
        }

        .recommendation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .recommendation-card .card-header {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .score-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #0d6efd;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: bold;
            margin: 0 auto 15px auto;
        }

        .badge-capacity {
            background-color: #6c757d;
        }

        .enrolled-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
        }

        .capacity-progress {
            height: 10px;
            border-radius: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Rekomendasi Ekstrakurikuler</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('siswa.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Rekomendasi Ekstrakurikuler</li>
        </ol>

        <div class="mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="alert alert-info mb-0">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="alert-heading">Rekomendasi Berdasarkan Minat & Bakat</h5>
                                <p class="mb-0">Berdasarkan hasil survei minat dan bakat yang telah Anda isi, berikut
                                    adalah rekomendasi kegiatan ekstrakurikuler yang mungkin sesuai dengan Anda. Rekomendasi
                                    diurutkan berdasarkan tingkat kesesuaian.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('siswa.recommendations.survey') }}" class="btn btn-primary">
                        <i class="fas fa-poll me-1"></i> Perbarui Survei Minat
                    </a>
                </div>
            </div>
        </div>

        <!-- Recommendations -->
        <div class="row">
            @foreach ($recommendations as $recommendation)
                <div class="col-xl-4 col-md-6">
                    <div class="card recommendation-card">
                        @if (in_array($recommendation->extracurricular_id, $enrolledExtracurricularIds))
                            <div class="enrolled-badge">
                                <span class="badge bg-success">Sudah Terdaftar</span>
                            </div>
                        @endif

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>{{ $recommendation->extracurricular->name }}</span>
                            <span class="badge bg-primary">{{ number_format($recommendation->score * 100, 1) }}%
                                Match</span>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="score-circle">
                                    {{ number_format($recommendation->score * 100, 0) }}%
                                </div>
                            </div>

                            <h5 class="card-title">Deskripsi</h5>
                            <p class="card-text">
                                {{ $recommendation->extracurricular->description ?? 'Tidak ada deskripsi' }}</p>

                            <h5 class="card-title">Jadwal</h5>
                            <p class="card-text">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ $recommendation->extracurricular->schedule ?? 'Jadwal belum ditentukan' }}
                            </p>

                            <h5 class="card-title">Kapasitas</h5>
                            <div class="mb-2">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Terisi:
                                        {{ $recommendation->extracurricular->capacity - $recommendation->extracurricular->availableSlots() }}
                                        / {{ $recommendation->extracurricular->capacity }}</span>
                                    <span>{{ $recommendation->extracurricular->availableSlots() }} slot tersedia</span>
                                </div>
                                @php
                                    $percentage =
                                        (($recommendation->extracurricular->capacity -
                                            $recommendation->extracurricular->availableSlots()) /
                                            $recommendation->extracurricular->capacity) *
                                        100;
                                    $progressClass =
                                        $percentage >= 90
                                            ? 'bg-danger'
                                            : ($percentage >= 70
                                                ? 'bg-warning'
                                                : 'bg-success');
                                @endphp
                                <div class="progress capacity-progress">
                                    <div class="progress-bar {{ $progressClass }}" role="progressbar"
                                        style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                @if (in_array($recommendation->extracurricular_id, $enrolledExtracurricularIds))
                                    <button class="btn btn-success" disabled>
                                        <i class="fas fa-check-circle me-1"></i> Sudah Terdaftar
                                    </button>
                                @elseif($recommendation->extracurricular->availableSlots() <= 0)
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-ban me-1"></i> Kapasitas Penuh
                                    </button>
                                @else
                                    <form action="{{ route('siswa.recommendations.enroll') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="extracurricular_id"
                                            value="{{ $recommendation->extracurricular_id }}">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-user-plus me-1"></i> Daftar Sekarang
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer text-muted">
                            <div class="d-flex justify-content-between">
                                <small><i class="fas fa-map-marker-alt me-1"></i>
                                    {{ $recommendation->extracurricular->location ?? 'Lokasi belum ditentukan' }}</small>
                                <small><i class="fas fa-user me-1"></i>
                                    {{ $recommendation->extracurricular->coach->user->name }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($recommendations->isEmpty())
            <div class="text-center py-5">
                <img src="/img/empty-recommendations.svg" alt="Tidak ada rekomendasi" class="img-fluid mb-4"
                    style="max-height: 250px;">
                <h3>Belum Ada Rekomendasi</h3>
                <p class="text-muted mb-4">Anda belum mengisi survei minat dan bakat atau sistem belum menghasilkan
                    rekomendasi.</p>
                <a href="{{ route('siswa.recommendations.survey') }}" class="btn btn-primary">
                    <i class="fas fa-poll me-1"></i> Isi Survei Minat dan Bakat
                </a>
            </div>
        @endif
    </div>
@endsection
