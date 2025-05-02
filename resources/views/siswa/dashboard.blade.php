@extends('layouts.siswa')

@section('title', 'Dashboard Siswa')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard Siswa</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>

        <!-- Welcome Alert -->
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            <h4 class="alert-heading">Selamat Datang, {{ Auth::user()->name }}!</h4>
            <p>Selamat datang di sistem pengelolaan ekstrakurikuler MA Modern Miftahussa'adah Cimahi. Gunakan panel ini
                untuk mengakses informasi ekstrakurikuler, rekomendasi, dan kegiatan Anda.</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <!-- Survey Alert (if needed) -->
        @if (!$hasInterests)
            <div class="alert alert-warning mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-3 fs-3"></i>
                    <div>
                        <h5 class="alert-heading">Isi Survei Minat & Bakat!</h5>
                        <p class="mb-0">Untuk mendapatkan rekomendasi ekstrakurikuler yang sesuai, silakan isi survei
                            minat dan bakat terlebih dahulu.</p>
                    </div>
                    <a href="{{ route('siswa.recommendations.survey') }}" class="btn btn-warning ms-auto">Isi Survei
                        Sekarang</a>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-0">{{ $enrollments->count() }}</h5>
                                <div class="small">Ekstrakurikuler Diikuti</div>
                            </div>
                            <div class="fs-1 text-white-50">
                                <i class="fas fa-users-class"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('siswa.enrollments.index') }}">Lihat
                            Detail</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-0">{{ $upcomingMeetings->count() }}</h5>
                                <div class="small">Pertemuan Mendatang</div>
                            </div>
                            <div class="fs-1 text-white-50">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('siswa.schedule') }}">Lihat Jadwal</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-info text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-0">{{ $recommendations->count() }}</h5>
                                <div class="small">Rekomendasi Tersedia</div>
                            </div>
                            <div class="fs-1 text-white-50">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('siswa.recommendations.show') }}">Lihat
                            Rekomendasi</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-0">{{ $achievements->count() }}</h5>
                                <div class="small">Prestasi Diraih</div>
                            </div>
                            <div class="fs-1 text-white-50">
                                <i class="fas fa-trophy"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('siswa.achievements') }}">Lihat
                            Prestasi</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Enrolled Extracurriculars -->
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-users-class me-1"></i>
                        Ekstrakurikuler Diikuti
                    </div>
                    <div class="card-body">
                        @if ($enrollments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Jadwal</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($enrollments as $enrollment)
                                            <tr>
                                                <td>{{ $enrollment->extracurricular->name }}</td>
                                                <td>{{ $enrollment->extracurricular->schedule }}</td>
                                                <td>
                                                    @if ($enrollment->status == 'approved')
                                                        <span class="badge bg-success">Disetujui</span>
                                                    @elseif($enrollment->status == 'pending')
                                                        <span class="badge bg-warning">Menunggu</span>
                                                    @elseif($enrollment->status == 'completed')
                                                        <span class="badge bg-primary">Selesai</span>
                                                    @else
                                                        <span class="badge bg-danger">Ditolak</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('siswa.enrollments.show', $enrollment->id) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <img src="/img/empty-data.svg" alt="Tidak ada data" class="img-fluid mb-3"
                                    style="max-height: 150px;">
                                <h5>Belum Ada Ekstrakurikuler</h5>
                                <p class="text-muted">Anda belum terdaftar dalam ekstrakurikuler apapun.</p>
                                <a href="{{ route('siswa.recommendations.show') }}" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i> Lihat Rekomendasi
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Upcoming Meetings -->
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-calendar-alt me-1"></i>
                        Jadwal Pertemuan Mendatang
                    </div>
                    <div class="card-body">
                        @if ($upcomingMeetings->count() > 0)
                            <div class="list-group">
                                @foreach ($upcomingMeetings as $meeting)
                                    <div class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">{{ $meeting->title }}</h5>
                                            <small class="text-muted">{{ $meeting->date->format('d M Y, H:i') }}</small>
                                        </div>
                                        <p class="mb-1">
                                            {{ Str::limit($recommendation->extracurricular->description, 100) }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-users me-1"></i>
                                            {{ $recommendation->extracurricular->availableSlots() }} slot tersedia
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $recommendation->extracurricular->schedule }}
                                        </small>
                                    </div>
                                @endforeach
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('siswa.recommendations.show') }}" class="btn btn-primary">
                                    <i class="fas fa-list me-1"></i> Lihat Semua Rekomendasi
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <img src="/img/empty-recommendations.svg" alt="Tidak ada rekomendasi"
                                    class="img-fluid mb-3" style="max-height: 150px;">
                                <h5>Belum Ada Rekomendasi</h5>
                                <p class="text-muted">Isi survei minat dan bakat untuk mendapatkan rekomendasi.</p>
                                <a href="{{ route('siswa.recommendations.survey') }}" class="btn btn-primary">
                                    <i class="fas fa-poll me-1"></i> Isi Survei Minat
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Achievements -->
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-trophy me-1"></i>
                        Prestasi Terbaru
                    </div>
                    <div class="card-body">
                        @if ($achievements->count() > 0)
                            <div class="list-group">
                                @foreach ($achievements as $achievement)
                                    <div class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">{{ $achievement->title }}</h5>
                                            <small class="text-muted">{{ $achievement->date->format('d M Y') }}</small>
                                        </div>
                                        <p class="mb-1">{{ $achievement->extracurricular->name }}</p>
                                        <small class="text-muted">
                                            <span
                                                class="badge bg-{{ $achievement->level == 'internasional' ? 'danger' : ($achievement->level == 'nasional' ? 'warning' : ($achievement->level == 'provinsi' ? 'info' : 'success')) }}">
                                                Tingkat {{ ucfirst($achievement->level) }}
                                            </span>
                                        </small>
                                    </div>
                                @endforeach
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('siswa.achievements') }}" class="btn btn-primary">
                                    <i class="fas fa-list me-1"></i> Lihat Semua Prestasi
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <img src="/img/empty-trophy.svg" alt="Tidak ada prestasi" class="img-fluid mb-3"
                                    style="max-height: 150px;">
                                <h5>Belum Ada Prestasi</h5>
                                <p class="text-muted">Anda belum memiliki prestasi yang tercatat dalam sistem.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
