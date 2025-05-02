@extends('layouts.pembina')

@section('title', 'Laporan Prestasi')

@push('styles')
    <style>
        .achievement-badge {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2rem;
            margin: 0 auto 15px auto;
        }

        .badge-sekolah {
            background-color: #37D1A2;
        }

        .badge-kecamatan {
            background-color: #198754;
        }

        .badge-kabupaten {
            background-color: #0d6efd;
        }

        .badge-provinsi {
            background-color: #6f42c1;
        }

        .badge-nasional {
            background-color: #fd7e14;
        }

        .badge-internasional {
            background-color: #dc3545;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Laporan Prestasi</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('pembina.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Laporan Prestasi</li>
        </ol>

        <div class="row mb-4">
            <div class="col-lg-9">
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-trophy me-1"></i> Daftar Prestasi Siswa
                            </div>
                            <div>
                                <a href="{{ route('pembina.reports.achievements.pdf') }}" class="btn btn-sm btn-light"
                                    target="_blank">
                                    <i class="fas fa-file-pdf me-1"></i> Ekspor PDF
                                </a>
                                <a href="{{ route('pembina.reports.achievements.excel') }}"
                                    class="btn btn-sm btn-light ms-2">
                                    <i class="fas fa-file-excel me-1"></i> Ekspor Excel
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover datatable">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="20%">Siswa</th>
                                        <th width="20%">Ekstrakurikuler</th>
                                        <th width="25%">Prestasi</th>
                                        <th width="10%">Tingkat</th>
                                        <th width="10%">Tanggal</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($achievements as $index => $achievement)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="ms-3">
                                                        <p class="fw-bold mb-1">{{ $achievement->student->user->name }}</p>
                                                        <p class="text-muted mb-0">{{ $achievement->student->kelas }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $achievement->extracurricular->name }}</td>
                                            <td>
                                                <p class="fw-bold mb-1">{{ $achievement->title }}</p>
                                                <p class="text-muted mb-0">{{ Str::limit($achievement->description, 30) }}
                                                </p>
                                            </td>
                                            <td>
                                                @php
                                                    $badgeClass = 'bg-secondary';

                                                    if ($achievement->level == 'sekolah') {
                                                        $badgeClass = 'bg-success';
                                                    } elseif ($achievement->level == 'kecamatan') {
                                                        $badgeClass = 'bg-info';
                                                    } elseif ($achievement->level == 'kabupaten') {
                                                        $badgeClass = 'bg-primary';
                                                    } elseif ($achievement->level == 'provinsi') {
                                                        $badgeClass = 'bg-purple';
                                                    } elseif ($achievement->level == 'nasional') {
                                                        $badgeClass = 'bg-warning';
                                                    } elseif ($achievement->level == 'internasional') {
                                                        $badgeClass = 'bg-danger';
                                                    }
                                                @endphp

                                                <span
                                                    class="badge {{ $badgeClass }}">{{ ucfirst($achievement->level) }}</span>
                                            </td>
                                            <td>{{ $achievement->date->format('d/m/Y') }}</td>
                                            <td>
                                                <a href="{{ route('pembina.achievements.show', $achievement->id) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <div class="py-4">
                                                    <img src="/img/empty-trophy.svg" alt="Tidak ada prestasi"
                                                        class="img-fluid mb-3" style="max-height: 100px;">
                                                    <h5>Belum Ada Prestasi</h5>
                                                    <p class="text-muted">Belum ada prestasi yang tercatat dalam sistem.</p>
                                                    <a href="{{ route('pembina.achievements.create') }}"
                                                        class="btn btn-sm btn-success">
                                                        <i class="fas fa-plus me-1"></i> Tambah Prestasi
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <!-- Statistics -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-bar me-1"></i> Statistik Prestasi
                    </div>
                    <div class="card-body text-center">
                        <h4 class="mb-4">Total Prestasi</h4>
                        <div class="achievement-badge bg-success text-white">
                            <i class="fas fa-award"></i>
                        </div>
                        <h2 class="mb-0">{{ $achievements->count() }}</h2>
                        <p class="text-muted">Prestasi yang diraih</p>

                        <hr>

                        <h5 class="mt-4">Prestasi per Tingkat</h5>

                        <div class="mt-3">
                            @php
                                $sekolahCount = $achievements->where('level', 'sekolah')->count();
                                $kecamatanCount = $achievements->where('level', 'kecamatan')->count();
                                $kabupatenCount = $achievements->where('level', 'kabupaten')->count();
                                $provinsiCount = $achievements->where('level', 'provinsi')->count();
                                $nasionalCount = $achievements->where('level', 'nasional')->count();
                                $internasionalCount = $achievements->where('level', 'internasional')->count();
                            @endphp

                            <div class="d-flex justify-content-between mb-1">
                                <small>Tingkat Sekolah</small>
                                <small>{{ $sekolahCount }}</small>
                            </div>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar badge-sekolah"
                                    style="width: {{ $achievements->count() > 0 ? ($sekolahCount / $achievements->count()) * 100 : 0 }}%">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mb-1">
                                <small>Tingkat Kecamatan</small>
                                <small>{{ $kecamatanCount }}</small>
                            </div>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar badge-kecamatan"
                                    style="width: {{ $achievements->count() > 0 ? ($kecamatanCount / $achievements->count()) * 100 : 0 }}%">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mb-1">
                                <small>Tingkat Kabupaten</small>
                                <small>{{ $kabupatenCount }}</small>
                            </div>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar badge-kabupaten"
                                    style="width: {{ $achievements->count() > 0 ? ($kabupatenCount / $achievements->count()) * 100 : 0 }}%">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mb-1">
                                <small>Tingkat Provinsi</small>
                                <small>{{ $provinsiCount }}</small>
                            </div>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar badge-provinsi"
                                    style="width: {{ $achievements->count() > 0 ? ($provinsiCount / $achievements->count()) * 100 : 0 }}%">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mb-1">
                                <small>Tingkat Nasional</small>
                                <small>{{ $nasionalCount }}</small>
                            </div>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar badge-nasional"
                                    style="width: {{ $achievements->count() > 0 ? ($nasionalCount / $achievements->count()) * 100 : 0 }}%">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mb-1">
                                <small>Tingkat Internasional</small>
                                <small>{{ $internasionalCount }}</small>
                            </div>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar badge-internasional"
                                    style="width: {{ $achievements->count() > 0 ? ($internasionalCount / $achievements->count()) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-bolt me-1"></i> Aksi Cepat
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('pembina.achievements.create') }}" class="btn btn-success">
                                <i class="fas fa-plus me-1"></i> Tambah Prestasi Baru
                            </a>
                            <a href="{{ route('pembina.achievements.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-list me-1"></i> Kelola Prestasi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Achievements by Extracurricular -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-medal me-1"></i> Prestasi per Ekstrakurikuler
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($extracurriculars as $extracurricular)
                        @php
                            $ekstrakurikulerAchievements = $achievements->where(
                                'extracurricular_id',
                                $extracurricular->id,
                            );
                        @endphp

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    {{ $extracurricular->name }}
                                </div>
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <div class="display-4">{{ $ekstrakurikulerAchievements->count() }}</div>
                                        <div class="text-muted">Prestasi</div>
                                    </div>

                                    @if ($ekstrakurikulerAchievements->count() > 0)
                                        <hr>

                                        <div class="mt-3">
                                            <h6>Tingkat Tertinggi</h6>
                                            @php
                                                $levels = [
                                                    'internasional',
                                                    'nasional',
                                                    'provinsi',
                                                    'kabupaten',
                                                    'kecamatan',
                                                    'sekolah',
                                                ];
                                                $highestLevel = null;

                                                foreach ($levels as $level) {
                                                    if (
                                                        $ekstrakurikulerAchievements->where('level', $level)->count() >
                                                        0
                                                    ) {
                                                        $highestLevel = $level;
                                                        break;
                                                    }
                                                }

                                                $badgeClass = 'bg-secondary';

                                                if ($highestLevel == 'sekolah') {
                                                    $badgeClass = 'badge-sekolah';
                                                } elseif ($highestLevel == 'kecamatan') {
                                                    $badgeClass = 'badge-kecamatan';
                                                } elseif ($highestLevel == 'kabupaten') {
                                                    $badgeClass = 'badge-kabupaten';
                                                } elseif ($highestLevel == 'provinsi') {
                                                    $badgeClass = 'badge-provinsi';
                                                } elseif ($highestLevel == 'nasional') {
                                                    $badgeClass = 'badge-nasional';
                                                } elseif ($highestLevel == 'internasional') {
                                                    $badgeClass = 'badge-internasional';
                                                }
                                            @endphp

                                            <span
                                                class="badge {{ $badgeClass }} text-white">{{ ucfirst($highestLevel) }}</span>
                                        </div>

                                        <div class="mt-3">
                                            <h6>Prestasi Terbaru</h6>
                                            @php
                                                $latestAchievement = $ekstrakurikulerAchievements
                                                    ->sortByDesc('date')
                                                    ->first();
                                            @endphp

                                            <p class="mb-0 small">{{ $latestAchievement->title }}</p>
                                            <small
                                                class="text-muted">{{ $latestAchievement->date->format('d M Y') }}</small>
                                        </div>
                                    @else
                                        <div class="alert alert-light mt-3">
                                            <p class="mb-0 small">Belum ada prestasi yang dicatat</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('pembina.achievements.create') }}?extracurricular_id={{ $extracurricular->id }}"
                                        class="btn btn-sm btn-success w-100">
                                        <i class="fas fa-plus me-1"></i> Tambah Prestasi
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                <p class="mb-0">Anda belum memiliki ekstrakurikuler yang dikelola.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
