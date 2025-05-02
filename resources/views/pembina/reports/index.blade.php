@extends('layouts.pembina')

@section('title', 'Laporan Kehadiran')

@push('styles')
    <style>
        .progress-bar-hadir {
            background-color: #198754;
        }

        .progress-bar-izin {
            background-color: #0d6efd;
        }

        .progress-bar-sakit {
            background-color: #ffc107;
        }

        .progress-bar-alpha {
            background-color: #dc3545;
        }

        .progress {
            height: 10px;
            margin-bottom: 5px;
        }

        .stat-number {
            font-size: 18px;
            font-weight: bold;
        }

        .stat-label {
            font-size: 12px;
        }

        .stat-card {
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .attendance-detail {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .attendance-detail.show {
            max-height: 1000px;
        }

        .attendance-toggle {
            cursor: pointer;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.25rem 0.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Laporan Kehadiran</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('pembina.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pembina.attendances.index') }}">Kehadiran</a></li>
            <li class="breadcrumb-item active">Laporan</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-chart-bar me-1"></i>
                        Laporan Kehadiran: {{ $extracurricular->name }}
                    </div>
                    <div>
                        <a href="{{ route('pembina.reports.attendance.pdf', $extracurricular->id) }}"
                            class="btn btn-sm btn-light" target="_blank">
                            <i class="fas fa-file-pdf me-1"></i> Ekspor PDF
                        </a>
                        <a href="{{ route('pembina.reports.attendance.excel', $extracurricular->id) }}"
                            class="btn btn-sm btn-light ms-2">
                            <i class="fas fa-file-excel me-1"></i> Ekspor Excel
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="card stat-card border-0 bg-light mb-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-success text-white p-3 rounded">
                                            <i class="fas fa-users fa-2x"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-muted">Jumlah Siswa</div>
                                        <div class="stat-number">{{ $enrollments->count() }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card stat-card border-0 bg-light mb-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-primary text-white p-3 rounded">
                                            <i class="fas fa-calendar-alt fa-2x"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-muted">Jumlah Pertemuan</div>
                                        <div class="stat-number">{{ $meetings->count() }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card stat-card border-0 bg-light mb-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-success text-white p-3 rounded">
                                            <i class="fas fa-check-circle fa-2x"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-muted">Rata-rata Kehadiran</div>
                                        @php
                                            $avgAttendance =
                                                $enrollments->count() > 0
                                                    ? $enrollments->sum(function ($e) {
                                                            return $e->attendance_stats['percentage'] ?? 0;
                                                        }) / $enrollments->count()
                                                    : 0;
                                        @endphp
                                        <div class="stat-number">{{ number_format($avgAttendance, 1) }}%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card stat-card border-0 bg-light mb-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-danger text-white p-3 rounded">
                                            <i class="fas fa-times-circle fa-2x"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-muted">Rata-rata Ketidakhadiran</div>
                                        <div class="stat-number">{{ number_format(100 - $avgAttendance, 1) }}%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overall Attendance Chart -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-pie me-1"></i>
                        Statistik Kehadiran Keseluruhan
                    </div>
                    <div class="card-body">
                        @php
                            $totalAttendance = 0;
                            $hadirCount = 0;
                            $izinCount = 0;
                            $sakitCount = 0;
                            $alphaCount = 0;

                            foreach ($enrollments as $enrollment) {
                                $hadirCount += $enrollment->attendance_stats['hadir'] ?? 0;
                                $izinCount += $enrollment->attendance_stats['izin'] ?? 0;
                                $sakitCount += $enrollment->attendance_stats['sakit'] ?? 0;
                                $alphaCount += $enrollment->attendance_stats['alpha'] ?? 0;
                                $totalAttendance += $enrollment->attendance_stats['total'] ?? 0;
                            }

                            $hadirPercent = $totalAttendance > 0 ? ($hadirCount / $totalAttendance) * 100 : 0;
                            $izinPercent = $totalAttendance > 0 ? ($izinCount / $totalAttendance) * 100 : 0;
                            $sakitPercent = $totalAttendance > 0 ? ($sakitCount / $totalAttendance) * 100 : 0;
                            $alphaPercent = $totalAttendance > 0 ? ($alphaCount / $totalAttendance) * 100 : 0;
                        @endphp

                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <canvas id="overallAttendanceChart" height="100"></canvas>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Hadir</span>
                                        <span class="text-success">{{ $hadirCount }}
                                            ({{ number_format($hadirPercent, 1) }}%)</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-hadir" role="progressbar"
                                            style="width: {{ $hadirPercent }}%"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Izin</span>
                                        <span class="text-primary">{{ $izinCount }}
                                            ({{ number_format($izinPercent, 1) }}%)</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-izin" role="progressbar"
                                            style="width: {{ $izinPercent }}%"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Sakit</span>
                                        <span class="text-warning">{{ $sakitCount }}
                                            ({{ number_format($sakitPercent, 1) }}%)</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-sakit" role="progressbar"
                                            style="width: {{ $sakitPercent }}%"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Alpha</span>
                                        <span class="text-danger">{{ $alphaCount }}
                                            ({{ number_format($alphaPercent, 1) }}%)</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-alpha" role="progressbar"
                                            style="width: {{ $alphaPercent }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Meeting Attendance Table -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-calendar-check me-1"></i>
                        Kehadiran Per Pertemuan
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Judul Pertemuan</th>
                                        <th>Hadir</th>
                                        <th>Izin</th>
                                        <th>Sakit</th>
                                        <th>Alpha</th>
                                        <th>Persentase Kehadiran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($meetings as $meeting)
                                        @php
                                            $meetingAttendance = [];
                                            $totalStudents = $enrollments->count();
                                            $hadirCount = 0;
                                            $izinCount = 0;
                                            $sakitCount = 0;
                                            $alphaCount = 0;

                                            // Count attendance for this meeting
                                            foreach ($enrollments as $enrollment) {
                                                $attendance = App\Models\Attendance::where(
                                                    'enrollment_id',
                                                    $enrollment->id,
                                                )
                                                    ->where('meeting_id', $meeting->id)
                                                    ->first();

                                                if ($attendance) {
                                                    if ($attendance->status == 'hadir') {
                                                        $hadirCount++;
                                                    } elseif ($attendance->status == 'izin') {
                                                        $izinCount++;
                                                    } elseif ($attendance->status == 'sakit') {
                                                        $sakitCount++;
                                                    } elseif ($attendance->status == 'alpha') {
                                                        $alphaCount++;
                                                    }
                                                } else {
                                                    $alphaCount++;
                                                }
                                            }

                                            $attendancePercentage =
                                                $totalStudents > 0 ? ($hadirCount / $totalStudents) * 100 : 0;
                                        @endphp

                                        <tr>
                                            <td>{{ $meeting->date->format('d M Y, H:i') }}</td>
                                            <td>{{ $meeting->title }}</td>
                                            <td class="text-center">{{ $hadirCount }}</td>
                                            <td class="text-center">{{ $izinCount }}</td>
                                            <td class="text-center">{{ $sakitCount }}</td>
                                            <td class="text-center">{{ $alphaCount }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-2">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                            style="width: {{ $attendancePercentage }}%"></div>
                                                    </div>
                                                    <span>{{ number_format($attendancePercentage, 1) }}%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Student Attendance Table -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-user-check me-1"></i>
                        Kehadiran Per Siswa
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Hadir</th>
                                        <th>Izin</th>
                                        <th>Sakit</th>
                                        <th>Alpha</th>
                                        <th>Persentase</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($enrollments as $enrollment)
                                        <tr>
                                            <td>{{ $enrollment->student->user->name }}</td>
                                            <td>{{ $enrollment->student->kelas }}</td>
                                            <td class="text-center">{{ $enrollment->attendance_stats['hadir'] ?? 0 }}</td>
                                            <td class="text-center">{{ $enrollment->attendance_stats['izin'] ?? 0 }}</td>
                                            <td class="text-center">{{ $enrollment->attendance_stats['sakit'] ?? 0 }}</td>
                                            <td class="text-center">{{ $enrollment->attendance_stats['alpha'] ?? 0 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-2">
                                                        <div class="progress-bar {{ $enrollment->attendance_stats['percentage'] >= 75 ? 'bg-success' : ($enrollment->attendance_stats['percentage'] >= 50 ? 'bg-warning' : 'bg-danger') }}"
                                                            role="progressbar"
                                                            style="width: {{ $enrollment->attendance_stats['percentage'] }}%">
                                                        </div>
                                                    </div>
                                                    <span>{{ number_format($enrollment->attendance_stats['percentage'], 1) }}%</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-primary attendance-toggle"
                                                    data-student-id="{{ $enrollment->student_id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr class="attendance-detail" id="detail-{{ $enrollment->student_id }}">
                                            <td colspan="8">
                                                <div class="p-3">
                                                    <h6>Detail Kehadiran: {{ $enrollment->student->user->name }}</h6>
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-bordered">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>Tanggal</th>
                                                                    <th>Pertemuan</th>
                                                                    <th>Status</th>
                                                                    <th>Catatan</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($meetings as $meeting)
                                                                    @php
                                                                        $attendance = App\Models\Attendance::where(
                                                                            'enrollment_id',
                                                                            $enrollment->id,
                                                                        )
                                                                            ->where('meeting_id', $meeting->id)
                                                                            ->first();

                                                                        $status = $attendance
                                                                            ? $attendance->status
                                                                            : 'alpha';
                                                                        $statusClass = '';

                                                                        if ($status == 'hadir') {
                                                                            $statusClass = 'text-success';
                                                                        } elseif ($status == 'izin') {
                                                                            $statusClass = 'text-primary';
                                                                        } elseif ($status == 'sakit') {
                                                                            $statusClass = 'text-warning';
                                                                        } elseif ($status == 'alpha') {
                                                                            $statusClass = 'text-danger';
                                                                        }
                                                                    @endphp

                                                                    <tr>
                                                                        <td>{{ $meeting->date->format('d M Y') }}</td>
                                                                        <td>{{ $meeting->title }}</td>
                                                                        <td class="{{ $statusClass }}">
                                                                            <i
                                                                                class="fas fa-{{ $status == 'hadir' ? 'check-circle' : ($status == 'izin' ? 'envelope' : ($status == 'sakit' ? 'thermometer-half' : 'times-circle')) }} me-1"></i>
                                                                            {{ ucfirst($status) }}
                                                                        </td>
                                                                        <td>{{ $attendance ? $attendance->notes : '-' }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('pembina.attendances.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                    <a href="{{ route('pembina.reports.attendance.pdf', $extracurricular->id) }}" class="btn btn-success"
                        target="_blank">
                        <i class="fas fa-file-pdf me-1"></i> Ekspor Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Attendance chart
            const ctx = document.getElementById('overallAttendanceChart').getContext('2d');
            const attendanceChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Hadir', 'Izin', 'Sakit', 'Alpha'],
                    datasets: [{
                        data: [{{ $hadirCount }}, {{ $izinCount }}, {{ $sakitCount }},
                            {{ $alphaCount }}
                        ],
                        backgroundColor: [
                            '#198754', // Success/Green - Hadir
                            '#0d6efd', // Primary/Blue - Izin
                            '#ffc107', // Warning/Yellow - Sakit
                            '#dc3545' // Danger/Red - Alpha
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });

            // Detail toggling
            const toggleButtons = document.querySelectorAll('.attendance-toggle');
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const studentId = this.dataset.studentId;
                    const detailRow = document.getElementById('detail-' + studentId);

                    // Toggle class
                    detailRow.classList.toggle('show');

                    // Toggle icon
                    const icon = this.querySelector('i');
                    if (detailRow.classList.contains('show')) {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });
        });
    </script>
@endpush
