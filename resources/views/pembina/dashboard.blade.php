@extends('layouts.pembina')

@section('title', 'Dashboard Pembina')

@push('styles')
    <style>
        .card {
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .gradient-card {
            border: none;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        .activity-item {
            padding: 15px;
            border-left: 3px solid transparent;
            margin-bottom: 8px;
            transition: all 0.3s;
        }

        .activity-item:hover {
            background-color: rgba(32, 201, 151, 0.05);
            border-left-color: #20c997;
        }

        .activity-time {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .activity-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .activity-desc {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .extracurricular-card {
            transition: all 0.3s;
            border-radius: 10px;
            overflow: hidden;
            height: 100%;
            margin-bottom: 20px;
        }

        .extracurricular-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .extracurricular-header {
            background: linear-gradient(to right, #198754, #20c997);
            color: white;
            padding: 15px;
            position: relative;
            overflow: hidden;
        }

        .extracurricular-content {
            padding: 15px;
        }

        .extracurricular-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .extracurricular-schedule {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .extracurricular-footer {
            padding: 15px;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }

        .quick-access-item {
            height: 100%;
            transition: all 0.3s;
        }

        .quick-access-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(32, 201, 151, 0.05);
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-success text-white me-2">
                <i class="mdi mdi-home"></i>
            </span> Dashboard Pembina
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-success align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Welcome Alert -->
    <div class="alert alert-gradient-success alert-dismissible fade show" role="alert">
        <h4 class="alert-heading text-white">Selamat Datang, {{ Auth::user()->name }}!</h4>
        <p class="text-white mb-0">Selamat datang di panel pembina sistem pengelolaan dan rekomendasi kegiatan
            ekstrakurikuler MA Modern
            Miftahussa'adah Cimahi. Gunakan panel ini untuk mengelola ekstrakurikuler, pertemuan, kehadiran, dan
            prestasi siswa.</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- Empty State (jika tidak ada ekstrakurikuler yang dikelola) -->
    @if ($extracurriculars->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="mdi mdi-school text-muted" style="font-size: 4rem;"></i>
                <h3 class="mt-3">Belum Ada Ekstrakurikuler yang Dikelola</h3>
                <p class="text-muted mb-4">Anda belum ditugaskan untuk mengelola ekstrakurikuler. Silakan hubungi
                    administrator untuk mendapatkan penugasan ekstrakurikuler.</p>
                <a href="{{ route('pembina.profile') }}" class="btn btn-gradient-success">
                    <i class="mdi mdi-account-cog me-1"></i> Lihat Profil Saya
                </a>
            </div>
        </div>
    @else
        <!-- Stats Cards -->
        <div class="row">
            <div class="col-md-3 stretch-card grid-margin">
                <div class="card bg-gradient-primary card-img-holder text-white">
                    <div class="card-body">
                        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Ekstrakurikuler <i class="mdi mdi-teach mdi-24px float-end"></i>
                        </h4>
                        <h2 class="mb-5">{{ $extracurriculars->count() }}</h2>
                        <h6 class="card-text">
                            <a href="{{ route('pembina.extracurriculars.index') }}" class="text-white">
                                Lihat Detail <i class="mdi mdi-chevron-right"></i>
                            </a>
                        </h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3 stretch-card grid-margin">
                <div class="card bg-gradient-success card-img-holder text-white">
                    <div class="card-body">
                        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Total Siswa <i
                                class="mdi mdi-account-multiple mdi-24px float-end"></i>
                        </h4>
                        <h2 class="mb-5">{{ $totalEnrollments }}</h2>
                        <h6 class="card-text">
                            <a href="{{ route('pembina.enrollments.index') }}" class="text-white">
                                Lihat Detail <i class="mdi mdi-chevron-right"></i>
                            </a>
                        </h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3 stretch-card grid-margin">
                <div class="card bg-gradient-warning card-img-holder text-white">
                    <div class="card-body">
                        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Pertemuan Mendatang <i
                                class="mdi mdi-calendar mdi-24px float-end"></i>
                        </h4>
                        <h2 class="mb-5">{{ $upcomingMeetings->count() }}</h2>
                        <h6 class="card-text">
                            <a href="{{ route('pembina.meetings.index') }}" class="text-white">
                                Lihat Detail <i class="mdi mdi-chevron-right"></i>
                            </a>
                        </h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3 stretch-card grid-margin">
                <div class="card bg-gradient-danger card-img-holder text-white">
                    <div class="card-body">
                        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Prestasi Terbaru <i
                                class="mdi mdi-trophy mdi-24px float-end"></i>
                        </h4>
                        <h2 class="mb-5">{{ $recentAchievements->count() }}</h2>
                        <h6 class="card-text">
                            <a href="{{ route('pembina.achievements.index') }}" class="text-white">
                                Lihat Detail <i class="mdi mdi-chevron-right"></i>
                            </a>
                        </h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Extracurriculars -->
            <div class="col-lg-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <i class="mdi mdi-teach me-1"></i>
                            Ekstrakurikuler yang Saya Kelola
                        </h4>
                        <div class="row mt-3">
                            @foreach ($extracurriculars as $extracurricular)
                                <div class="col-md-6">
                                    <div class="card extracurricular-card">
                                        <div class="extracurricular-header">
                                            <h5 class="extracurricular-title mb-0">{{ $extracurricular->name }}</h5>
                                            <div class="extracurricular-schedule">
                                                <i class="mdi mdi-clock-outline me-1"></i> {{ $extracurricular->schedule }}
                                            </div>
                                        </div>
                                        <div class="extracurricular-content">
                                            <div class="mb-3">
                                                <small class="text-muted">Lokasi:</small>
                                                <div>{{ $extracurricular->location ?? 'Belum ditentukan' }}</div>
                                            </div>

                                            <div class="mb-3">
                                                <small class="text-muted">Kapasitas:</small>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    @php
                                                        $enrolledCount = $extracurricular
                                                            ->enrollments()
                                                            ->whereIn('status', ['approved', 'completed'])
                                                            ->count();
                                                        $percentage =
                                                            ($enrolledCount / $extracurricular->capacity) * 100;
                                                        $progressClass =
                                                            $percentage >= 90
                                                                ? 'bg-gradient-danger'
                                                                : ($percentage >= 70
                                                                    ? 'bg-gradient-warning'
                                                                    : 'bg-gradient-success');
                                                    @endphp
                                                    <div>
                                                        <span>{{ $enrolledCount }}/{{ $extracurricular->capacity }}</span>
                                                    </div>
                                                    <span
                                                        class="badge badge-{{ $percentage >= 90 ? 'danger' : ($percentage >= 70 ? 'warning' : 'success') }}">
                                                        {{ number_format($percentage, 0) }}%
                                                    </span>
                                                </div>
                                                <div class="progress mt-2" style="height: 6px;">
                                                    <div class="progress-bar {{ $progressClass }}" role="progressbar"
                                                        style="width: {{ $percentage }}%"
                                                        aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <small class="text-muted">Pendaftaran Menunggu:</small>
                                                <div>
                                                    @php
                                                        $pendingCount = $extracurricular
                                                            ->enrollments()
                                                            ->where('status', 'pending')
                                                            ->count();
                                                    @endphp

                                                    @if ($pendingCount > 0)
                                                        <span class="badge badge-warning">{{ $pendingCount }} pendaftaran
                                                            menunggu</span>
                                                    @else
                                                        <span class="text-muted">Tidak ada pendaftaran menunggu</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-between mt-3">
                                                <a href="{{ route('pembina.extracurriculars.show', $extracurricular->id) }}"
                                                    class="btn btn-sm btn-outline-success">
                                                    <i class="mdi mdi-eye me-1"></i> Lihat Detail
                                                </a>
                                                <a href="{{ route('pembina.meetings.create', ['extracurricular_id' => $extracurricular->id]) }}"
                                                    class="btn btn-sm btn-gradient-success">
                                                    <i class="mdi mdi-plus me-1"></i> Buat Pertemuan
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Meetings -->
            <div class="col-lg-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <i class="mdi mdi-calendar me-1"></i>
                            Pertemuan Mendatang
                        </h4>
                        @if ($upcomingMeetings->isNotEmpty())
                            <div class="mt-3">
                                @foreach ($upcomingMeetings as $meeting)
                                    <div class="activity-item">
                                        <div class="activity-time">
                                            <i class="mdi mdi-clock-outline me-1"></i>
                                            {{ $meeting->date->format('d M Y, H:i') }}
                                        </div>
                                        <div class="activity-title">{{ $meeting->title }}</div>
                                        <div class="activity-desc">
                                            <span class="badge badge-gradient-success">
                                                <span class="badge badge-gradient-success">
                                                    {{ $meeting->extracurricular->name }}
                                                </span>
                                                <span class="ms-2">
                                                    <i class="mdi mdi-map-marker me-1"></i>
                                                    {{ $meeting->extracurricular->location ?? 'Lokasi tidak ditentukan' }}
                                                </span>
                                        </div>
                                        <div class="mt-2">
                                            <a href="{{ route('pembina.attendances.manage', $meeting->id) }}"
                                                class="btn btn-sm btn-gradient-success">
                                                <i class="mdi mdi-clipboard-check me-1"></i> Kelola Kehadiran
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="mdi mdi-calendar-blank text-muted" style="font-size: 3rem;"></i>
                                <h5 class="mt-3">Tidak Ada Pertemuan</h5>
                                <p class="text-muted">Belum ada pertemuan yang dijadwalkan dalam waktu dekat.</p>
                                <a href="{{ route('pembina.meetings.create') }}" class="btn btn-gradient-success">
                                    <i class="mdi mdi-plus me-1"></i> Buat Pertemuan Baru
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Enrollments -->
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <i class="mdi mdi-account-check me-1"></i>
                            Pendaftaran Terbaru
                        </h4>
                        @if (isset($recentEnrollments) && $recentEnrollments->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Siswa</th>
                                            <th>Ekstrakurikuler</th>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentEnrollments as $enrollment)
                                            <tr>
                                                <td>{{ $enrollment->student->user->name }}</td>
                                                <td>{{ $enrollment->extracurricular->name }}</td>
                                                <td>{{ $enrollment->created_at->format('d/m/Y') }}</td>
                                                <td>
                                                    @if ($enrollment->status == 'pending')
                                                        <span class="badge badge-warning">Menunggu</span>
                                                    @elseif($enrollment->status == 'approved')
                                                        <span class="badge badge-success">Disetujui</span>
                                                    @elseif($enrollment->status == 'rejected')
                                                        <span class="badge badge-danger">Ditolak</span>
                                                    @else
                                                        <span class="badge badge-primary">Selesai</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('pembina.enrollments.show', $enrollment->id) }}"
                                                        class="btn btn-sm btn-gradient-info">
                                                        <i class="mdi mdi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="mdi mdi-account-multiple-outline text-muted" style="font-size: 3rem;"></i>
                                <h5 class="mt-3">Belum Ada Pendaftaran</h5>
                                <p class="text-muted">Belum ada pendaftaran siswa untuk ekstrakurikuler yang Anda
                                    kelola.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Achievements -->
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <i class="mdi mdi-trophy me-1"></i>
                            Prestasi Terbaru
                        </h4>
                        @if ($recentAchievements->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Siswa</th>
                                            <th>Ekstrakurikuler</th>
                                            <th>Prestasi</th>
                                            <th>Tingkat</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentAchievements as $achievement)
                                            <tr>
                                                <td>{{ $achievement->student->user->name }}</td>
                                                <td>{{ $achievement->extracurricular->name }}</td>
                                                <td>
                                                    <span class="d-inline-block text-truncate" style="max-width: 150px;">
                                                        {{ $achievement->title }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($achievement->level == 'sekolah')
                                                        <span class="badge badge-gradient-success">Sekolah</span>
                                                    @elseif($achievement->level == 'kecamatan')
                                                        <span class="badge badge-gradient-info">Kecamatan</span>
                                                    @elseif($achievement->level == 'kabupaten')
                                                        <span class="badge badge-gradient-primary">Kabupaten</span>
                                                    @elseif($achievement->level == 'provinsi')
                                                        <span class="badge badge-gradient-warning">Provinsi</span>
                                                    @elseif($achievement->level == 'nasional')
                                                        <span class="badge badge-gradient-danger">Nasional</span>
                                                    @elseif($achievement->level == 'internasional')
                                                        <span class="badge badge-gradient-dark">Internasional</span>
                                                    @endif
                                                </td>
                                                <td>{{ $achievement->date->format('d/m/Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('pembina.achievements.create') }}" class="btn btn-gradient-success">
                                    <i class="mdi mdi-plus me-1"></i> Tambah Prestasi Baru
                                </a>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="mdi mdi-trophy-variant text-muted" style="font-size: 3rem;"></i>
                                <h5 class="mt-3">Belum Ada Prestasi</h5>
                                <p class="text-muted">Belum ada prestasi yang tercatat untuk ekstrakurikuler yang Anda
                                    kelola.</p>
                                <a href="{{ route('pembina.achievements.create') }}" class="btn btn-gradient-success">
                                    <i class="mdi mdi-plus me-1"></i> Tambah Prestasi Baru
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Quick Links -->
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">
                <i class="mdi mdi-flash me-1"></i>
                Akses Cepat
            </h4>
            <div class="row mt-3">
                <div class="col-md-3 col-sm-6 mb-4">
                    <a href="{{ route('pembina.attendances.index') }}"
                        class="btn btn-gradient-success btn-lg btn-block py-3 quick-access-item">
                        <i class="mdi mdi-clipboard-text-outline mdi-24px d-block mb-2"></i>
                        Kelola Kehadiran
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <a href="{{ route('pembina.meetings.create') }}"
                        class="btn btn-gradient-success btn-lg btn-block py-3 quick-access-item">
                        <i class="mdi mdi-calendar-plus mdi-24px d-block mb-2"></i>
                        Buat Pertemuan
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <a href="{{ route('pembina.achievements.create') }}"
                        class="btn btn-gradient-success btn-lg btn-block py-3 quick-access-item">
                        <i class="mdi mdi-trophy-award mdi-24px d-block mb-2"></i>
                        Catat Prestasi
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <a href="{{ route('pembina.reports.attendance') }}"
                        class="btn btn-gradient-success btn-lg btn-block py-3 quick-access-item">
                        <i class="mdi mdi-chart-bar mdi-24px d-block mb-2"></i>
                        Lihat Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
