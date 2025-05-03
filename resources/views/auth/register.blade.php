<!-- File: resources/views/auth/register.blade.php -->

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registrasi - Sistem Ekstrakurikuler</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/purpleadmin/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/purpleadmin/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/purpleadmin/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/purpleadmin/images/favicon.ico') }}" />
    <style>
        .required-label::after {
            content: " *";
            color: #dc3545;
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow w-100">
                    <div class="col-lg-8 mx-auto">
                        <div class="auth-form-light text-left p-5">
                            <div class="brand-logo text-center">
                                <h3 class="text-primary"><i class="mdi mdi-school"></i> MA Modern Miftahussa'adah</h3>
                                <p class="text-muted">Daftar Akun Sistem Ekstrakurikuler</p>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <form class="pt-3" method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="card mb-4">
                                    <div class="card-header bg-gradient-primary text-white">
                                        <h5 class="mb-0">Informasi Akun</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="role" class="form-label required-label">Daftar
                                                Sebagai</label>
                                            <select class="form-select @error('role') is-invalid @enderror"
                                                id="role" name="role" required>
                                                <option value="">Pilih Peran</option>
                                                <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>
                                                    Siswa</option>
                                                <option value="pembina"
                                                    {{ old('role') == 'pembina' ? 'selected' : '' }}>Pembina
                                                    Ekstrakurikuler</option>
                                            </select>
                                            @error('role')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name" class="required-label">Nama Lengkap</label>
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        id="name" name="name" placeholder="Nama Lengkap"
                                                        value="{{ old('name') }}" required>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email" class="required-label">Email</label>
                                                    <input type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        id="email" name="email" placeholder="Email"
                                                        value="{{ old('email') }}" required>
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="password" class="required-label">Password</label>
                                                    <input type="password"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        id="password" name="password" placeholder="Password" required>
                                                    @error('password')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="password_confirmation" class="required-label">Konfirmasi
                                                        Password</label>
                                                    <input type="password" class="form-control"
                                                        id="password_confirmation" name="password_confirmation"
                                                        placeholder="Konfirmasi Password" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Siswa -->
                                <div id="siswa-form" class="card mb-4" style="display: none;">
                                    <div class="card-header bg-gradient-info text-white">
                                        <h5 class="mb-0">Informasi Siswa</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="nis">NIS</label>
                                                    <input type="text"
                                                        class="form-control @error('nis') is-invalid @enderror"
                                                        id="nis" name="nis" placeholder="NIS"
                                                        value="{{ old('nis') }}">
                                                    @error('nis')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="kelas">Kelas</label>
                                                    <input type="text"
                                                        class="form-control @error('kelas') is-invalid @enderror"
                                                        id="kelas" name="kelas" placeholder="Kelas"
                                                        value="{{ old('kelas') }}">
                                                    @error('kelas')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Jenis Kelamin</label>
                                                    <div class="d-flex">
                                                        <div class="form-check me-4">
                                                            <label class="form-check-label">
                                                                <input type="radio" class="form-check-input"
                                                                    name="gender" id="gender-l" value="L"
                                                                    {{ old('gender') == 'L' ? 'checked' : '' }}>
                                                                Laki-laki
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input type="radio" class="form-check-input"
                                                                    name="gender" id="gender-p" value="P"
                                                                    {{ old('gender') == 'P' ? 'checked' : '' }}>
                                                                Perempuan
                                                            </label>
                                                        </div>
                                                    </div>
                                                    @error('gender')
                                                        <div class="text-danger small">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="birthdate">Tanggal Lahir</label>
                                                    <input type="date"
                                                        class="form-control @error('birthdate') is-invalid @enderror"
                                                        id="birthdate" name="birthdate" placeholder="Tanggal Lahir"
                                                        value="{{ old('birthdate') }}">
                                                    @error('birthdate')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="phone">Nomor Telepon</label>
                                                    <input type="text"
                                                        class="form-control @error('phone') is-invalid @enderror"
                                                        id="phone" name="phone" placeholder="Nomor Telepon"
                                                        value="{{ old('phone') }}">
                                                    @error('phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="address">Alamat</label>
                                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address"
                                                        placeholder="Alamat" rows="3">{{ old('address') }}</textarea>
                                                    @error('address')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Pembina -->
                                <div id="pembina-form" class="card mb-4" style="display: none;">
                                    <div class="card-header bg-gradient-success text-white">
                                        <h5 class="mb-0">Informasi Pembina</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="specialty">Spesialisasi</label>
                                                    <input type="text"
                                                        class="form-control @error('specialty') is-invalid @enderror"
                                                        id="specialty" name="specialty" placeholder="Spesialisasi"
                                                        value="{{ old('specialty') }}">
                                                    @error('specialty')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="experience">Pengalaman (Tahun)</label>
                                                    <input type="number"
                                                        class="form-control @error('experience') is-invalid @enderror"
                                                        id="experience" name="experience"
                                                        placeholder="Pengalaman (Tahun)"
                                                        value="{{ old('experience') }}" min="0">
                                                    @error('experience')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="certificates">Sertifikat</label>
                                            <textarea class="form-control @error('certificates') is-invalid @enderror" id="certificates" name="certificates"
                                                placeholder="Sertifikat" rows="3">{{ old('certificates') }}</textarea>
                                            <small class="form-text text-muted">Pisahkan dengan baris baru untuk setiap
                                                sertifikat</small>
                                            @error('certificates')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3 d-grid">
                                    <button type="submit" class="btn btn-gradient-primary btn-lg font-weight-medium">
                                        <i class="mdi mdi-account-plus me-2"></i> Daftar
                                    </button>
                                </div>

                                <div class="text-center mt-4 font-weight-light">
                                    Sudah memiliki akun? <a href="{{ route('login') }}"
                                        class="text-primary">Login</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    <!-- Scripts -->
    <script src="{{ asset('assets/purpleadmin/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/purpleadmin/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/purpleadmin/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets/purpleadmin/js/misc.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const siswaForm = document.getElementById('siswa-form');
            const pembinaForm = document.getElementById('pembina-form');

            // Show/hide role-specific forms
            function toggleForms() {
                const selectedRole = roleSelect.value;

                if (selectedRole === 'siswa') {
                    siswaForm.style.display = 'block';
                    pembinaForm.style.display = 'none';
                } else if (selectedRole === 'pembina') {
                    siswaForm.style.display = 'none';
                    pembinaForm.style.display = 'block';
                } else {
                    siswaForm.style.display = 'none';
                    pembinaForm.style.display = 'none';
                }
            }

            // Initial state
            toggleForms();

            // On change
            roleSelect.addEventListener('change', toggleForms);
        });
    </script>
</body>

</html>
