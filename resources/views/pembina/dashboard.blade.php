@extends('layouts.pembina')

@section('title', 'Dashboard Pembina')

@push('styles')
    <style>
        .stat-card {
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .info-icon {
            font-size: 2rem;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        .activity-list-item {
            padding: 15px;
            border-left: 3px solid transparent;
            margin-bottom: 8px;
        }

        .activity-list-item:hover {
            background-color: #f8f9fa;
            border-left-color: #198754;
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
            border: 1px solid #dee2e6;
            border-radius: 10px;
            overflow: hidden;
            height: 100%;
        }

        .extracurricular-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-color: #198754;
        }

        .extracurricular-header {
            background-color: #198754;
            color: white;
            padding: 15px;
            position: relative;
            overflow: hidden;
        }

        .extracurricular-header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('/img/pattern.svg');
            background-size: cover;
            opacity: 0.1;
        }

        .extracurricular-content {
            position: relative;
            padding: 20px;
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

        .table-hover tbody tr:hover {
            background-color: rgba(25, 135, 84, 0.05);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard Pembina</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>

        <!-- Welcome Alert -->
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <h4 class="alert-heading">Selamat Datang, {{ Auth::user()->name }}!</h4>
            <p>Selamat datang di panel pembina sistem pengelolaan dan rekomendasi kegiatan ekstrakurikuler MA Modern
                Miftahussa'adah Cimahi. Gunakan panel ini untuk mengelola ekstrakurikuler, pertemuan, kehadiran, dan
                prestasi siswa.</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <!-- Empty State (jika tidak ada ekstrakurikuler yang dikelola) -->
        @if ($extracurriculars->isEmpty())
            <div class="card mb-4">
                <div class="card-body text-center py-5">
                    <img src="/img/empty-data.svg" alt="Belum ada ekstrakurikuler" class="img-fluid mb-3"
                        style="max-height: 200px;">
                    <h3>Belum Ada Ekstrakurikuler yang Dikelola</h3>
                    <p class="text-muted mb-4">Anda belum ditugaskan untuk mengelola ekstrakurikuler. Silakan hubungi
                        administrator untuk mendapatkan penugasan ekstrakurikuler.</p>
                    <a href="{{ route('pembina.profile') }}" class="btn btn-success">
                        <i class="fas fa-user-cog me-1"></i> Lihat Profil Saya
                    </a>
                </div>
            </div>
        @else
            <!-- Stats Cards -->
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card bg-primary text-white mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="info-icon bg-white text-primary">
                                    <i class="fas fa-users-class"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $extracurriculars->count() }}</h5>
                                    <div class="small">Ekstrakurikuler</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link"
                                href="{{ route('pembina.extracurriculars.index') }}">Lihat Detail</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card bg-success text-white mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="info-icon bg-white text-success">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $totalEnrollments }}</h5>
                                    <div class="small">Total Siswa</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('pembina.enrollments.index') }}">Lihat
                                Detail</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card bg-warning text-white mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="info-icon bg-white text-warning">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $upcomingMeetings->count() }}</h5>
                                    <div class="small">Pertemuan Mendatang</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('pembina.meetings.index') }}">Lihat
                                Detail</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card bg-danger text-white mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="info-icon bg-white text-danger">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $recentAchievements->count() }}</h5>
                                    <div class="small">Prestasi Terbaru</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link"
                                href="{{ route('pembina.achievements.index') }}">Lihat Detail</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Extracurriculars -->
                <div class="col-xl-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-users-class me-1"></i>
                            Ekstrakurikuler yang Saya Kelola
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($extracurriculars as $extracurricular)
                                    <div class="col-xl-6 mb-4">
                                        <div class="extracurricular-card">
                                            <div class="extracurricular-header">
                                                <div class="extracurricular-title">{{ $extracurricular->name }}</div>
                                                <div class="extracurricular-schedule">
                                                    <i class="fas fa-clock me-1"></i> {{ $extracurricular->schedule }}
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
                                                        <div>
                                                            @php
                                                                $enrolledCount = $extracurricular
                                                                    ->enrollments()
                                                                    ->whereIn('status', ['approved', 'completed'])
                                                                    ->count();
                                                                $percentage =
                                                                    ($enrolledCount / $extracurricular->capacity) * 100;
                                                            @endphp
                                                            <span>{{ $enrolledCount }}/{{ $extracurricular->capacity }}</span>
                                                        </div>
                                                        <span
                                                            class="badge {{ $percentage >= 90 ? 'bg-danger' : ($percentage >= 70 ? 'bg-warning' : 'bg-success') }}">
                                                            {{ number_format($percentage, 0) }}%
                                                        </span>
                                                    </div>
                                                    <div class="progress mt-2" style="height: 5px;">
                                                        <div class="progress-bar {{ $percentage >= 90 ? 'bg-danger' : ($percentage >= 70 ? 'bg-warning' : 'bg-success') }}"
                                                            role="progressbar" style="width: {{ $percentage }}%"
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
                                                            <span class="badge bg-warning">{{ $pendingCount }} pendaftaran
                                                                menunggu</span>
                                                        @else
                                                            <span class="text-muted">Tidak ada pendaftaran menunggu</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="extracurricular-footer">
                                                <div class="d-flex justify-content-between">
                                                    <a href="{{ route('pembina.extracurriculars.show', $extracurricular->id) }}"
                                                        class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-eye me-1"></i> Lihat Detail
                                                    </a>
                                                    <a href="{{ route('pembina.meetings.create', ['extracurricular_id' => $extracurricular->id]) }}"
                                                        class="btn btn-sm btn-success">
                                                        <i class="fas fa-plus me-1"></i> Buat Pertemuan
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
                <div class="col-xl-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Pertemuan Mendatang
                        </div>
                        <div class="card-body">
                            @if ($upcomingMeetings->isNotEmpty())
                                <ul class="list-unstyled mb-0">
                                    @foreach ($upcomingMeetings as $meeting)
                                        <li class="activity-list-item">
                                            <div class="activity-time">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $meeting->date->format('d M Y, H:i') }}
                                            </div>
                                            <div class="activity-title">{{ $meeting->title }}</div>
                                            <div class="activity-desc">
                                                <span
                                                    class="badge bg-success">{{ $meeting->extracurricular->name }}</span>
                                                <span class="ms-2">
                                                    <i class="fas fa-map-marker-alt me-1"></i>
                                                    {{ $meeting->extracurricular->location ?? 'Lokasi tidak ditentukan' }}
                                                </span>
                                            </div>
                                            <div class="mt-2">
                                                <a href="{{ route('pembina.attendances.manage', $meeting->id) }}"
                                                    class="btn btn-sm btn-success">
                                                    <i class="fas fa-clipboard-check me-1"></i> Kelola Kehadiran
                                                </a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-center py-4">
                                    <img src="/img/empty-calendar.svg" alt="Tidak ada pertemuan" class="img-fluid mb-3"
                                        style="max-height: 150px;">
                                    <h5>Tidak Ada Pertemuan</h5>
                                    <p class="text-muted">Belum ada pertemuan yang dijadwalkan dalam waktu dekat.</p>
                                    <a href="{{ route('pembina.meetings.create') }}" class="btn btn-success">
                                        <i class="fas fa-plus me-1"></i> Buat Pertemuan Baru
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Enrollments -->
                <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-user-check me-1"></i>
                            Pendaftaran Terbaru
                        </div>
                        <div class="card-body">
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
                                                            <span class="badge bg-warning">Menunggu</span>
                                                        @elseif($enrollment->status == 'approved')
                                                            <span class="badge bg-success">Disetujui</span>
                                                        @elseif($enrollment->status == 'rejected')
                                                            <span class="badge bg-danger">Ditolak</span>
                                                        @else
                                                            <span class="badge bg-primary">Selesai</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('pembina.enrollments.show', $enrollment->id) }}"
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
                                    <img src="/img/empty-data.svg" alt="Tidak ada pendaftaran" class="img-fluid mb-3"
                                        style="max-height: 150px;">
                                    <h5>Belum Ada Pendaftaran</h5>
                                    <p class="text-muted">Belum ada pendaftaran siswa untuk ekstrakurikuler yang Anda
                                        kelola.</p>
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
                                                        <span class="d-inline-block text-truncate"
                                                            style="max-width: 150px;">
                                                            {{ $achievement->title }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if ($achievement->level == 'sekolah')
                                                            <span class="badge bg-success">Sekolah</span>
                                                        @elseif($achievement->level == 'kecamatan')
                                                            <span class="badge bg-info">Kecamatan</span>
                                                        @elseif($achievement->level == 'kabupaten')
                                                            <span class="badge bg-primary">Kabupaten</span>
                                                        @elseif($achievement->level == 'provinsi')
                                                            <span class="badge bg-purple">Provinsi</span>
                                                        @elseif($achievement->level == 'nasional')
                                                            <span class="badge bg-warning">Nasional</span>
                                                        @elseif($achievement->level == 'internasional')
                                                            <span class="badge bg-danger">Internasional</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $achievement->date->format('d/m/Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="{{ route('pembina.achievements.create') }}" class="btn btn-success">
                                        <i class="fas fa-plus me-1"></i> Tambah Prestasi Baru
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <img src="/img/empty-trophy.svg" alt="Tidak ada prestasi" class="img-fluid mb-3"
                                        style="max-height: 150px;">
                                    <h5>Belum Ada Prestasi</h5>
                                    <p class="text-muted">Belum ada prestasi yang tercatat untuk ekstrakurikuler yang Anda
                                        kelola.</p>
                                    <a href="{{ route('pembina.achievements.create') }}" class="btn btn-success">
                                        <i class="fas fa-plus me-1"></i> Tambah Prestasi Baru
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Quick Links -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-bolt me-1"></i>
                Akses Cepat
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('pembina.attendances.index') }}"
                            class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center py-4">
                            <i class="fas fa-clipboard-check fa-2x mb-2"></i>
                            <span>Kelola Kehadiran</span>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('pembina.meetings.create') }}"
                            class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center py-4">
                            <i class="fas fa-calendar-plus fa-2x mb-2"></i>
                            <span>Buat Pertemuan</span>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('pembina.achievements.create') }}"
                            class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center py-4">
                            <i class="fas fa-trophy fa-2x mb-2"></i>
                            <span>Catat Prestasi</span>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('pembina.reports.attendance') }}"
                            class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center py-4">
                            <i class="fas fa-chart-bar fa-2x mb-2"></i>
                            <span>Lihat Laporan</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
