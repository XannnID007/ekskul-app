@extends('layouts.pembina')

@section('title', 'Kelola Kehadiran')

@push('styles')
    <style>
        .attendance-card {
            transition: transform 0.2s;
        }

        .attendance-card:hover {
            transform: translateY(-3px);
        }

        .attendance-option {
            cursor: pointer;
            border: 2px solid #dee2e6;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
            margin-bottom: 10px;
            transition: all 0.2s;
        }

        .attendance-option:hover {
            border-color: #adb5bd;
        }

        .attendance-option.selected {
            border-color: #198754;
            background-color: rgba(25, 135, 84, 0.1);
        }

        .attendance-option.hadir.selected {
            border-color: #198754;
            background-color: rgba(25, 135, 84, 0.1);
        }

        .attendance-option.izin.selected {
            border-color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.1);
        }

        .attendance-option.sakit.selected {
            border-color: #ffc107;
            background-color: rgba(255, 193, 7, 0.1);
        }

        .attendance-option.alpha.selected {
            border-color: #dc3545;
            background-color: rgba(220, 53, 69, 0.1);
        }

        .status-icon {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .hadir .status-icon {
            color: #198754;
        }

        .izin .status-icon {
            color: #0d6efd;
        }

        .sakit .status-icon {
            color: #ffc107;
        }

        .alpha .status-icon {
            color: #dc3545;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Kelola Kehadiran</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('pembina.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pembina.attendances.index') }}">Kehadiran</a></li>
            <li class="breadcrumb-item active">Kelola Kehadiran</li>
        </ol>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-clipboard-check me-1"></i>
                                Kehadiran: {{ $meeting->title }}
                            </div>
                            <div>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-calendar-alt me-1"></i> {{ $meeting->date->format('d M Y, H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{ $extracurricular->name }}</h5>
                                <span class="badge bg-primary">
                                    {{ $enrollments->count() }} Siswa
                                </span>
                            </div>
                            <p class="text-muted">
                                {{ $meeting->description ?? 'Tidak ada deskripsi' }}
                            </p>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                        <span>{{ $extracurricular->location ?? 'Lokasi tidak ditentukan' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-clock text-muted me-2"></i>
                                        <span>Durasi: {{ $meeting->duration }} menit</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <form action="{{ route('pembina.attendances.store', $meeting->id) }}" method="POST">
                            @csrf

                            <h5 class="mb-3">Daftar Kehadiran Siswa</h5>

                            @if ($enrollments->isEmpty())
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> Belum ada siswa yang terdaftar dalam
                                    ekstrakurikuler ini.
                                </div>
                            @else
                                <div class="row">
                                    @foreach ($enrollments as $index => $enrollment)
                                        <div class="col-lg-4 col-md-6 mb-4">
                                            <div class="card attendance-card h-100">
                                                <div class="card-header bg-light">
                                                    <h6 class="mb-0">{{ $enrollment->student->user->name }}</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-2">
                                                        <small class="text-muted d-block">Kelas:
                                                            {{ $enrollment->student->kelas }}</small>
                                                        <small class="text-muted d-block">NIS:
                                                            {{ $enrollment->student->nis }}</small>
                                                    </div>

                                                    <input type="hidden" name="enrollment_id[]"
                                                        value="{{ $enrollment->id }}">

                                                    <div class="row">
                                                        <div class="col-6 col-sm-3">
                                                            <div class="attendance-option hadir {{ isset($attendances[$enrollment->id]) && $attendances[$enrollment->id]->status == 'hadir' ? 'selected' : '' }}"
                                                                data-value="hadir" data-index="{{ $index }}">
                                                                <div class="status-icon">
                                                                    <i class="fas fa-check-circle"></i>
                                                                </div>
                                                                <div>Hadir</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6 col-sm-3">
                                                            <div class="attendance-option izin {{ isset($attendances[$enrollment->id]) && $attendances[$enrollment->id]->status == 'izin' ? 'selected' : '' }}"
                                                                data-value="izin" data-index="{{ $index }}">
                                                                <div class="status-icon">
                                                                    <i class="fas fa-envelope"></i>
                                                                </div>
                                                                <div>Izin</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6 col-sm-3">
                                                            <div class="attendance-option sakit {{ isset($attendances[$enrollment->id]) && $attendances[$enrollment->id]->status == 'sakit' ? 'selected' : '' }}"
                                                                data-value="sakit" data-index="{{ $index }}">
                                                                <div class="status-icon">
                                                                    <i class="fas fa-thermometer-half"></i>
                                                                </div>
                                                                <div>Sakit</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6 col-sm-3">
                                                            <div class="attendance-option alpha {{ isset($attendances[$enrollment->id]) && $attendances[$enrollment->id]->status == 'alpha' ? 'selected' : '' }}"
                                                                data-value="alpha" data-index="{{ $index }}">
                                                                <div class="status-icon">
                                                                    <i class="fas fa-times-circle"></i>
                                                                </div>
                                                                <div>Alpha</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <input type="hidden" name="status[]" id="status-{{ $index }}"
                                                        value="{{ isset($attendances[$enrollment->id]) ? $attendances[$enrollment->id]->status : 'alpha' }}">

                                                    <div class="mt-3">
                                                        <label for="notes-{{ $index }}"
                                                            class="form-label">Catatan:</label>
                                                        <textarea name="notes[]" id="notes-{{ $index }}" class="form-control form-control-sm" rows="2">{{ isset($attendances[$enrollment->id]) ? $attendances[$enrollment->id]->notes : '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-4">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6>Tindakan Massal</h6>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-sm btn-success mass-action"
                                                            data-status="hadir">
                                                            <i class="fas fa-check-circle me-1"></i> Semua Hadir
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger mass-action"
                                                            data-status="alpha">
                                                            <i class="fas fa-times-circle me-1"></i> Semua Alpha
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6>Statistik</h6>
                                                    <div class="d-flex justify-content-between text-center">
                                                        <div>
                                                            <div class="fw-bold text-success" id="stat-hadir">0</div>
                                                            <small>Hadir</small>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-primary" id="stat-izin">0</div>
                                                            <small>Izin</small>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-warning" id="stat-sakit">0</div>
                                                            <small>Sakit</small>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-danger" id="stat-alpha">0</div>
                                                            <small>Alpha</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('pembina.attendances.meetings', $extracurricular->id) }}"
                                        class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-1"></i> Simpan Kehadiran
                                    </button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const attendanceOptions = document.querySelectorAll('.attendance-option');
            const statusInputs = document.querySelectorAll('input[name="status[]"]');

            // Update attendance option selection
            attendanceOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const value = this.dataset.value;
                    const index = this.dataset.index;
                    const statusInput = document.getElementById('status-' + index);

                    // Remove selected class from siblings
                    const optionsContainer = this.parentElement.parentElement;
                    const siblingOptions = optionsContainer.querySelectorAll('.attendance-option');
                    siblingOptions.forEach(sib => sib.classList.remove('selected'));

                    // Add selected class to this option
                    this.classList.add('selected');

                    // Update hidden input value
                    statusInput.value = value;

                    // Update statistics
                    updateStatistics();
                });
            });

            // Mass action buttons
            const massActionButtons = document.querySelectorAll('.mass-action');
            massActionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const status = this.dataset.status;

                    // Update all attendance options
                    attendanceOptions.forEach(option => {
                        // Remove selected class from all options
                        option.classList.remove('selected');

                        // Add selected class to options matching the target status
                        if (option.dataset.value === status) {
                            option.classList.add('selected');

                            // Update hidden input
                            const index = option.dataset.index;
                            const statusInput = document.getElementById('status-' + index);
                            statusInput.value = status;
                        }
                    });

                    // Update statistics
                    updateStatistics();
                });
            });

            // Function to update statistics
            function updateStatistics() {
                let statHadir = 0;
                let statIzin = 0;
                let statSakit = 0;
                let statAlpha = 0;

                statusInputs.forEach(input => {
                    const value = input.value;
                    if (value === 'hadir') statHadir++;
                    else if (value === 'izin') statIzin++;
                    else if (value === 'sakit') statSakit++;
                    else if (value === 'alpha') statAlpha++;
                });

                document.getElementById('stat-hadir').textContent = statHadir;
                document.getElementById('stat-izin').textContent = statIzin;
                document.getElementById('stat-sakit').textContent = statSakit;
                document.getElementById('stat-alpha').textContent = statAlpha;
            }

            // Initialize statistics
            updateStatistics();
        });
    </script>
@endpush
