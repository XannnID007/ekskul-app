@extends('layouts.siswa')

@section('title', 'Dashboard Siswa')

@push('styles')
    <style>
        .card {
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .list-group-item {
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .list-group-item:hover {
            background-color: rgba(0, 123, 255, 0.05);
            border-left-color: #007bff;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-home"></i>
            </span> Dashboard Siswa
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Welcome Alert -->
    <div class="alert alert-gradient-primary alert-dismissible fade show" role="alert">
        <h4 class="alert-heading text-white">Selamat Datang, {{ Auth::user()->name }}!</h4>
        <p class="text-white mb-0">Selamat datang di sistem pengelolaan ekstrakurikuler MA Modern Miftahussa'adah Cimahi.
            Gunakan panel ini
            untuk mengakses informasi ekstrakurikuler, rekomendasi, dan kegiatan Anda.</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- Survey Alert (if needed) -->
    @if (!$hasInterests)
        <div class="alert alert-warning mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="mdi mdi-alert-circle me-3" style="font-size: 2rem;"></i>
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
    <div class="row">
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-primary card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/purpleadmin/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Ekstrakurikuler Diikuti <i
                            class="mdi mdi-school mdi-24px float-end"></i>
                    </h4>
                    <h2 class="mb-5">{{ $enrollments->count() }}</h2>
                    <h6 class="card-text">
                        <a href="{{ route('siswa.enrollments.index') }}" class="text-white">
                            Lihat Detail <i class="mdi mdi-chevron-right"></i>
                        </a>
                    </h6>
                </div>
            </div>
        </div>
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/purpleadmin/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Pertemuan Mendatang <i
                            class="mdi mdi-calendar mdi-24px float-end"></i>
                    </h4>
                    <h2 class="mb-5">{{ $upcomingMeetings->count() }}</h2>
                    <h6 class="card-text">
                        <a href="{{ route('siswa.schedule') }}" class="text-white">
                            Lihat Jadwal <i class="mdi mdi-chevron-right"></i>
                        </a>
                    </h6>
                </div>
            </div>
        </div>
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/purpleadmin/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Rekomendasi Tersedia <i
                            class="mdi mdi-lightbulb-on mdi-24px float-end"></i>
                    </h4>
                    <h2 class="mb-5">{{ $recommendations->count() }}</h2>
                    <h6 class="card-text">
                        <a href="{{ route('siswa.recommendations.show') }}" class="text-white">
                            Lihat Rekomendasi <i class="mdi mdi-chevron-right"></i>
                        </a>
                    </h6>
                </div>
            </div>
        </div>
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-warning card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/purpleadmin/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Prestasi Diraih <i class="mdi mdi-trophy mdi-24px float-end"></i>
                    </h4>
                    <h2 class="mb-5">{{ $achievements->count() }}</h2>
                    <h6 class="card-text">
                        <a href="{{ route('siswa.achievements') }}" class="text-white">
                            Lihat Prestasi <i class="mdi mdi-chevron-right"></i>
                        </a>
                    </h6>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Enrolled Extracurriculars -->
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        <i class="mdi mdi-school me-1"></i>
                        Ekstrakurikuler Diikuti
                    </h4>
                    @if ($enrollments->count() > 0)
                        <div class="table-responsive mt-3">
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
                                                    <span class="badge badge-gradient-success">Disetujui</span>
                                                @elseif($enrollment->status == 'pending')
                                                    <span class="badge badge-gradient-warning">Menunggu</span>
                                                @elseif($enrollment->status == 'completed')
                                                    <span class="badge badge-gradient-primary">Selesai</span>
                                                @else
                                                    <span class="badge badge-gradient-danger">Ditolak</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('siswa.enrollments.show', $enrollment->id) }}"
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
                            <i class="mdi mdi-school-outline text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Belum Ada Ekstrakurikuler</h5>
                            <p class="text-muted">Anda belum terdaftar dalam ekstrakurikuler apapun.</p>
                            <a href="{{ route('siswa.recommendations.show') }}" class="btn btn-gradient-primary">
                                <i class="mdi mdi-magnify me-1"></i> Lihat Rekomendasi
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recommendations -->
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        <i class="mdi mdi-lightbulb-on me-1"></i>
                        Rekomendasi Ekstrakurikuler
                    </h4>
                    @if ($recommendations->count() > 0)
                        <div class="list-group mt-3">
                            @foreach ($recommendations as $recommendation)
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $recommendation->extracurricular->name }}</h5>
                                        <small>
                                            <span class="badge badge-gradient-success">
                                                {{ number_format($recommendation->score, 1) }}% Match
                                            </span>
                                        </small>
                                    </div>
                                    <p class="mb-1">
                                        {{ Str::limit($recommendation->extracurricular->description, 100) }}
                                    </p>
                                    <small class="text-muted">
                                        <i class="mdi mdi-account-multiple me-1"></i>
                                        {{ $recommendation->extracurricular->availableSlots() }} slot tersedia
                                        <span class="mx-2">•</span>
                                        <i class="mdi mdi-clock-outline me-1"></i>
                                        {{ $recommendation->extracurricular->schedule }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('siswa.recommendations.show') }}" class="btn btn-gradient-primary">
                                <i class="mdi mdi-view-list me-1"></i> Lihat Semua Rekomendasi
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="mdi mdi-lightbulb-outline text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Belum Ada Rekomendasi</h5>
                            <p class="text-muted">Isi survei minat dan bakat untuk mendapatkan rekomendasi.</p>
                            <a href="{{ route('siswa.recommendations.survey') }}" class="btn btn-gradient-primary">
                                <i class="mdi mdi-clipboard-text me-1"></i> Isi Survei Minat
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Upcoming Meetings -->
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        <i class="mdi mdi-calendar me-1"></i>
                        Jadwal Pertemuan Mendatang
                    </h4>
                    @if ($upcomingMeetings->count() > 0)
                        <div class="list-group mt-3">
                            @foreach ($upcomingMeetings as $meeting)
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $meeting->title }}</h5>
                                        <small class="text-muted">{{ $meeting->date->format('d M Y, H:i') }}</small>
                                    </div>
                                    <p class="mb-1">{{ $meeting->extracurricular->name }}</p>
                                    <small class="text-muted">
                                        <i class="mdi mdi-map-marker me-1"></i>
                                        {{ $meeting->extracurricular->location ?? 'Lokasi tidak ditentukan' }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('siswa.schedule') }}" class="btn btn-gradient-primary">
                                <i class="mdi mdi-calendar-clock me-1"></i> Lihat Semua Jadwal
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="mdi mdi-calendar-blank text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Tidak Ada Pertemuan</h5>
                            <p class="text-muted">Belum ada pertemuan yang dijadwalkan dalam waktu dekat.</p>
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
                    @if ($achievements->count() > 0)
                        <div class="list-group mt-3">
                            @foreach ($achievements as $achievement)
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $achievement->title }}</h5>
                                        <small class="text-muted">{{ $achievement->date->format('d M Y') }}</small>
                                    </div>
                                    <p class="mb-1">{{ $achievement->extracurricular->name }}</p>
                                    <small class="text-muted">
                                        <span
                                            class="badge badge-{{ $achievement->level == 'internasional' ? 'gradient-danger' : ($achievement->level == 'nasional' ? 'gradient-warning' : ($achievement->level == 'provinsi' ? 'gradient-info' : 'gradient-success')) }}">
                                            Tingkat {{ ucfirst($achievement->level) }}
                                        </span>
                                    </small>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('siswa.achievements') }}" class="btn btn-gradient-primary">
                                <i class="mdi mdi-trophy-variant me-1"></i> Lihat Semua Prestasi
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="mdi mdi-trophy-outline text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Belum Ada Prestasi</h5>
                            <p class="text-muted">Anda belum memiliki prestasi yang tercatat dalam sistem.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
