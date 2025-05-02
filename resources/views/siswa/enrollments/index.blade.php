@extends('layouts.siswa')

@section('title', 'Survei Minat dan Bakat')

@push('styles')
    <style>
        .interest-category {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0.15rem 0.5rem 0 rgba(33, 40, 50, 0.1);
        }

        .interest-category h4 {
            margin-bottom: 15px;
            color: #0d6efd;
            font-weight: 600;
        }

        .interest-category p {
            color: #6c757d;
            margin-bottom: 15px;
        }

        .range-labels {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
            font-size: 0.85rem;
            color: #6c757d;
        }

        .score-display {
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: #0d6efd;
            margin-top: 10px;
            padding: 5px;
            border-radius: 5px;
            background-color: rgba(13, 110, 253, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Survei Minat dan Bakat</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('siswa.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('siswa.recommendations.show') }}">Rekomendasi</a></li>
            <li class="breadcrumb-item active">Survei Minat</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-poll me-1"></i> Pengisian Survei Minat dan Bakat
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-4" role="alert">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="alert-heading">Petunjuk Pengisian</h5>
                            <p class="mb-0">Survei ini bertujuan untuk mengetahui minat dan bakat Anda. Hasil survei akan
                                digunakan sebagai dasar untuk merekomendasikan kegiatan ekstrakurikuler yang sesuai. Silakan
                                geser slider untuk setiap kategori sesuai dengan tingkat ketertarikan Anda, dengan skala 1
                                (Tidak Tertarik) hingga 5 (Sangat Tertarik).</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('siswa.recommendations.save-survey') }}" method="POST">
                    @csrf

                    <!-- Academic Score -->
                    <div class="mb-4">
                        <h4>Nilai Akademik</h4>
                        <p class="text-muted">Masukkan nilai rata-rata akademik Anda (opsional). Data ini akan membantu
                            memberikan rekomendasi yang lebih akurat.</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                                    <input type="number" class="form-control" name="academic_score"
                                        value="{{ old('academic_score', $student->academic_score) }}" min="0"
                                        max="100" step="0.01" placeholder="Masukkan nilai rata-rata (0-100)">
                                </div>
                                @error('academic_score')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Interest Categories -->
                    @foreach ($categories as $key => $name)
                        <div class="interest-category">
                            <h4>{{ $name }}</h4>
                            <p>Seberapa besar minat Anda terhadap bidang {{ $name }}?</p>

                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <input type="range" class="form-range interest-slider"
                                        id="interest-{{ $key }}" name="interests[{{ $key }}]"
                                        min="1" max="5" step="1"
                                        value="{{ old('interests.' . $key, $interests[$key] ?? 3) }}"
                                        data-display="score-{{ $key }}">
                                    <div class="range-labels">
                                        <span>Tidak Tertarik</span>
                                        <span>Sedikit Tertarik</span>
                                        <span>Cukup Tertarik</span>
                                        <span>Tertarik</span>
                                        <span>Sangat Tertarik</span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="score-display" id="score-{{ $key }}">
                                        {{ old('interests.' . $key, $interests[$key] ?? 3) }}</div>
                                </div>
                            </div>

                            @error('interests.' . $key)
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan dan Lihat Rekomendasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sliders = document.querySelectorAll('.interest-slider');

            sliders.forEach(slider => {
                const displayId = slider.dataset.display;
                const display = document.getElementById(displayId);

                // Set initial value
                display.textContent = slider.value;

                // Update when slider changes
                slider.addEventListener('input', function() {
                    display.textContent = this.value;
                });
            });
        });
    </script>
@endpush
