@extends('layouts.pembina')

@section('title', 'Laporan Kehadiran')

@push('styles')
    <style>
        .report-card {
            transition: transform 0.3s;
        }

        .report-card:hover {
            transform: translateY(-5px);
        }

        .extracurricular-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.5rem;
            margin-right: 15px;
        }

        .card-hover:hover {
            border-color: #198754;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Laporan Kehadiran</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('pembina.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Laporan Kehadiran</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <i class="fas fa-chart-bar me-1"></i> Laporan Kehadiran Ekstrakurikuler
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="alert-heading">Informasi</h5>
                            <p class="mb-0">Pilih salah satu ekstrakurikuler di bawah ini untuk melihat laporan kehadiran
                                siswa.</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @foreach ($extracurriculars as $extracurricular)
                        <div class="col-xl-4 col-md-6 mb-4">
                            <a href="{{ route('pembina.attendances.report', $extracurricular->id) }}"
                                class="text-decoration-none">
                                <div class="card report-card card-hover h-100">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="extracurricular-icon bg-success text-white">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            <div>
                                                <h5 class="card-title">{{ $extracurricular->name }}</h5>
                                                <p class="text-muted mb-0">{{ $extracurricular->schedule }}</p>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="row text-center mt-3">
                                            <div class="col-6">
                                                <div class="mb-1">
                                                    @php
                                                        $enrollmentCount = $extracurricular
                                                            ->enrollments()
                                                            ->where('status', 'approved')
                                                            ->count();
                                                    @endphp
                                                    <h5 class="mb-0">{{ $enrollmentCount }}</h5>
                                                    <small class="text-muted">Total Siswa</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="mb-1">
                                                    @php
                                                        $meetingCount = $extracurricular->meetings()->count();
                                                    @endphp
                                                    <h5 class="mb-0">{{ $meetingCount }}</h5>
                                                    <small class="text-muted">Total Pertemuan</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex justify-content-between align-items-center">
                                        <span>Lihat Laporan</span>
                                        <span class="btn btn-sm btn-outline-success rounded-circle">
                                            <i class="fas fa-arrow-right"></i>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach

                    @if ($extracurriculars->isEmpty())
                        <div class="col-12">
                            <div class="text-center py-5">
                                <img src="/img/empty-data.svg" alt="Tidak ada data" class="img-fluid mb-3"
                                    style="max-height: 200px;">
                                <h3>Belum Ada Ekstrakurikuler</h3>
                                <p class="text-muted">Anda belum memiliki ekstrakurikuler yang dikelola.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-download me-1"></i> Ekspor Laporan
                    </div>
                    <div class="card-body">
                        <p>Anda dapat mengekspor laporan kehadiran untuk semua ekstrakurikuler yang Anda kelola dalam format
                            PDF atau Excel.</p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('pembina.reports.attendance') }}" class="btn btn-outline-success disabled">
                                <i class="fas fa-file-pdf me-1"></i> Ekspor Semua (PDF)
                            </a>
                            <a href="{{ route('pembina.reports.attendance') }}" class="btn btn-outline-success disabled">
                                <i class="fas fa-file-excel me-1"></i> Ekspor Semua (Excel)
                            </a>
                            <small class="d-block w-100 text-muted mt-2">* Anda harus memilih ekstrakurikuler tertentu untuk
                                mengekspor laporan.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-info-circle me-1"></i> Panduan Kehadiran
                    </div>
                    <div class="card-body">
                        <p>Informasi tentang status kehadiran:</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item ps-0">
                                <span class="badge bg-success me-2">Hadir</span> Siswa hadir dalam pertemuan
                            </li>
                            <li class="list-group-item ps-0">
                                <span class="badge bg-primary me-2">Izin</span> Siswa tidak hadir dengan izin
                            </li>
                            <li class="list-group-item ps-0">
                                <span class="badge bg-warning me-2">Sakit</span> Siswa tidak hadir karena sakit
                            </li>
                            <li class="list-group-item ps-0">
                                <span class="badge bg-danger me-2">Alpha</span> Siswa tidak hadir tanpa keterangan
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
