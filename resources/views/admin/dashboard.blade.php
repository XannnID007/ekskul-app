@extends('layouts.admin')

@section('title', 'Dashboard Admin')

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
            border-left-color: #6610f2;
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
            background-color: rgba(102, 16, 242, 0.05);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>

        <!-- Welcome Alert -->
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            <h4 class="alert-heading">Selamat Datang, {{ Auth::user()->name }}!</h4>
            <p>Selamat datang di panel admin sistem pengelolaan dan rekomendasi kegiatan ekstrakurikuler MA Modern
                Miftahussa'adah Cimahi. Gunakan panel ini untuk mengatur pengguna, ekstrakurikuler, dan parameter
                rekomendasi.</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-primary text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="info-icon bg-white text-primary">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $totalStudents }}</h5>
                                <div class="small">Total Siswa</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link"
                            href="{{ route('admin.users.index', ['role' => 'siswa']) }}">Lihat Detail</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-success text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="info-icon bg-white text-success">
                                <i class="fas fa-users-class"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $totalExtracurriculars }}</h5>
                                <div class="small">Ekstrakurikuler</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('admin.extracurriculars.index') }}">Lihat
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
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $totalEnrollments }}</h5>
                                <div class="small">Pendaftaran</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('admin.reports.enrollments') }}">Lihat
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
                                <h5 class="mb-0">{{ $activeExtracurriculars }}</h5>
                                <div class="small">Ekstrakurikuler Aktif</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link"
                            href="{{ route('admin.extracurriculars.index', ['status' => 'active']) }}">Lihat Detail</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Ekstrakurikuler Chart -->
            <div class="col-xl-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-bar me-1"></i>
                        Distribusi Pendaftaran Ekstrakurikuler
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="enrollmentChart"></canvas>
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
                        @if ($upcomingMeetings > 0)
                            <ul class="list-unstyled mb-0">
                                @foreach ($upcomingMeetings as $meeting)
                                    <li class="activity-list-item">
                                        <div class="activity-time">
                                            <i class="fas fa-clock me-1"></i> {{ $meeting->date->format('d M Y, H:i') }}
                                        </div>
                                        <div class="activity-title">{{ $meeting->title }}</div>
                                        <div class="activity-desc">
                                            <span class="badge bg-primary">{{ $meeting->extracurricular->name }}</span>
                                            <span class="ms-2"><i class="fas fa-map-marker-alt me-1"></i>
                                                {{ $meeting->extracurricular->location ?? 'Lokasi tidak ditentukan' }}</span>
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
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Achievements -->
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-trophy me-1"></i>
                        Prestasi Terbaru
                    </div>
                    <div class="card-body">
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
                            <div class="text-center py-4">
                                <img src="/img/empty-trophy.svg" alt="Tidak ada prestasi" class="img-fluid mb-3"
                                    style="max-height: 150px;">
                                <h5>Belum Ada Prestasi</h5>
                                <p class="text-muted">Belum ada prestasi yang tercatat dalam sistem.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Top Extracurriculars -->
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-star me-1"></i>
                        Ekstrakurikuler Terpopuler
                    </div>
                    <div class="card-body">
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
                                                    <div class="progress" style="height: 6px;">
                                                        @php
                                                            $percentage =
                                                                ($extracurricular->enrollments_count /
                                                                    $extracurricular->capacity) *
                                                                100;
                                                            $progressClass =
                                                                $percentage >= 90
                                                                    ? 'bg-danger'
                                                                    : ($percentage >= 70
                                                                        ? 'bg-warning'
                                                                        : 'bg-success');
                                                        @endphp
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
                            <div class="text-center py-4">
                                <img src="/img/empty-data.svg" alt="Tidak ada data" class="img-fluid mb-3"
                                    style="max-height: 150px;">
                                <h5>Belum Ada Data</h5>
                                <p class="text-muted">Belum ada pendaftaran ekstrakurikuler yang tercatat.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                        backgroundColor: 'rgba(102, 16, 242, 0.7)',
                        borderColor: 'rgba(102, 16, 242, 1)',
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
