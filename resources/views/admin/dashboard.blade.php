@extends('layouts.admin')

@section('title', 'Dashboard Admin')

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
            background-color: rgba(103, 58, 183, 0.05);
            border-left-color: #7b5cfa;
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

        .table-hover tbody tr:hover {
            background-color: rgba(103, 58, 183, 0.05);
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-home"></i>
            </span> Dashboard
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
        <p class="text-white mb-0">Selamat datang di panel admin sistem pengelolaan dan rekomendasi kegiatan ekstrakurikuler
            MA Modern
            Miftahussa'adah Cimahi. Gunakan panel ini untuk mengatur pengguna, ekstrakurikuler, dan parameter
            rekomendasi.</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-primary card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/purpleadmin/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Total Siswa <i
                            class="mdi mdi-account-multiple mdi-24px float-end"></i>
                    </h4>
                    <h2 class="mb-5">{{ $totalStudents }}</h2>
                    <h6 class="card-text">
                        <a href="{{ route('admin.users.index', ['role' => 'siswa']) }}" class="text-white">
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
                    <h4 class="font-weight-normal mb-3">Ekstrakurikuler <i class="mdi mdi-teach mdi-24px float-end"></i>
                    </h4>
                    <h2 class="mb-5">{{ $totalExtracurriculars }}</h2>
                    <h6 class="card-text">
                        <a href="{{ route('admin.extracurriculars.index') }}" class="text-white">
                            Lihat Detail <i class="mdi mdi-chevron-right"></i>
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
                    <h4 class="font-weight-normal mb-3">Pendaftaran <i class="mdi mdi-account-check mdi-24px float-end"></i>
                    </h4>
                    <h2 class="mb-5">{{ $totalEnrollments }}</h2>
                    <h6 class="card-text">
                        <a href="{{ route('admin.reports.enrollments') }}" class="text-white">
                            Lihat Detail <i class="mdi mdi-chevron-right"></i>
                        </a>
                    </h6>
                </div>
            </div>
        </div>
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/purpleadmin/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Ekstrakurikuler Aktif <i
                            class="mdi mdi-school mdi-24px float-end"></i>
                    </h4>
                    <h2 class="mb-5">{{ $activeExtracurriculars }}</h2>
                    <h6 class="card-text">
                        <a href="{{ route('admin.extracurriculars.index', ['status' => 'active']) }}" class="text-white">
                            Lihat Detail <i class="mdi mdi-chevron-right"></i>
                        </a>
                    </h6>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Ekstrakurikuler Chart -->
        <div class="col-lg-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        <i class="mdi mdi-chart-bar me-1"></i>
                        Distribusi Pendaftaran Ekstrakurikuler
                    </h4>
                    <div class="chart-container">
                        <canvas id="enrollmentChart"></canvas>
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
                    @if ($upcomingMeetings > 0)
                        <div class="mt-3">
                            @foreach ($upcomingMeetings as $meeting)
                                <div class="activity-item">
                                    <div class="activity-time">
                                        <i class="mdi mdi-clock-outline me-1"></i>
                                        {{ $meeting->date->format('d M Y, H:i') }}
                                    </div>
                                    <div class="activity-title">{{ $meeting->title }}</div>
                                    <div class="activity-desc">
                                        <span
                                            class="badge badge-gradient-primary">{{ $meeting->extracurricular->name }}</span>
                                        <span class="ms-2"><i class="mdi mdi-map-marker me-1"></i>
                                            {{ $meeting->extracurricular->location ?? 'Lokasi tidak ditentukan' }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="mdi mdi-calendar-remove text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Tidak Ada Pertemuan</h5>
                            <p class="text-muted">Belum ada pertemuan yang dijadwalkan dalam waktu dekat.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Achievements -->
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        <i class="mdi mdi-trophy me-1"></i>
                        Prestasi Terbaru
                    </h4>
                    @if ($recentAchievements->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Siswa</th>
                                        <th>Ekstrakurikuler</th>
                                        <th>Prestasi</th>
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
                                            <td>{{ $achievement->date->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="mdi mdi-trophy-variant text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Belum Ada Prestasi</h5>
                            <p class="text-muted">Belum ada prestasi yang tercatat dalam sistem.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Extracurriculars -->
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        <i class="mdi mdi-star me-1"></i>
                        Ekstrakurikuler Terpopuler
                    </h4>
                    @if ($extracurricularEnrollments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Ekstrakurikuler</th>
                                        <th>Pembina</th>
                                        <th>Pendaftar</th>
                                        <th>Kapasitas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($extracurricularEnrollments as $extracurricular)
                                        <tr>
                                            <td>{{ $extracurricular->name }}</td>
                                            <td>{{ $extracurricular->coach->user->name }}</td>
                                            <td>{{ $extracurricular->enrollments_count }}</td>
                                            <td>
                                                @php
                                                    $percentage =
                                                        ($extracurricular->enrollments_count /
                                                            $extracurricular->capacity) *
                                                        100;
                                                    $progressClass =
                                                        $percentage >= 90
                                                            ? 'bg-gradient-danger'
                                                            : ($percentage >= 70
                                                                ? 'bg-gradient-warning'
                                                                : 'bg-gradient-success');
                                                @endphp
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar {{ $progressClass }}" role="progressbar"
                                                        style="width: {{ $percentage }}%"
                                                        aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                                <small
                                                    class="text-muted">{{ $extracurricular->enrollments_count }}/{{ $extracurricular->capacity }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="mdi mdi-chart-line-variant text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Belum Ada Data</h5>
                            <p class="text-muted">Belum ada pendaftaran ekstrakurikuler yang tercatat.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/purpleadmin/vendors/chart.js/Chart.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enrollment Chart
            const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
            const enrollmentChart = new Chart(enrollmentCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($extracurricularEnrollments->pluck('name')) !!},
                    datasets: [{
                        label: 'Jumlah Siswa',
                        data: {!! json_encode($extracurricularEnrollments->pluck('enrollments_count')) !!},
                        backgroundColor: 'rgba(123, 92, 250, 0.7)',
                        borderColor: 'rgba(123, 92, 250, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.parsed.y} Siswa`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
