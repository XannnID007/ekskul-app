<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registrasi - Sistem Ekstrakurikuler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <style>
        body {
            background: linear-gradient(rgba(0, 123, 255, 0.7), rgba(0, 123, 255, 0.7)), url('/img/school-bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #333;
            font-family: 'Nunito', sans-serif;
            min-height: 100vh;
            padding: 40px 0;
        }

        .register-container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            margin: 0 auto;
        }

        .register-header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .register-logo {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .register-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .register-subtitle {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .register-form {
            padding: 30px;
        }

        .form-section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
        }

        .form-floating {
            margin-bottom: 20px;
        }

        .btn-register {
            padding: 10px 0;
            font-weight: 600;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 30px 0;
            color: #6c757d;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #dee2e6;
        }

        .divider::before {
            margin-right: 10px;
        }

        .divider::after {
            margin-left: 10px;
        }

        .required-label::after {
            content: " *";
            color: #dc3545;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="register-container">
            <div class="register-header">
                <div class="register-logo">
                    <i class="fas fa-school"></i>
                </div>
                <h2 class="register-title">MA Modern Miftahussa'adah</h2>
                <p class="register-subtitle">Daftar Akun Sistem Ekstrakurikuler</p>
            </div>

            <div class="register-form">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-section">
                        <h3 class="section-title">Informasi Akun</h3>

                        <div class="mb-3">
                            <label for="role" class="form-label required-label">Daftar Sebagai</label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role"
                                name="role" required>
                                <option value="">Pilih Peran</option>
                                <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                                <option value="pembina" {{ old('role') == 'pembina' ? 'selected' : '' }}>Pembina
                                    Ekstrakurikuler</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="Nama Lengkap"
                                        value="{{ old('name') }}" required>
                                    <label for="name">Nama Lengkap</label>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" placeholder="Email" value="{{ old('email') }}"
                                        required>
                                    <label for="email">Email</label>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Password" required>
                                    <label for="password">Password</label>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" placeholder="Konfirmasi Password" required>
                                    <label for="password_confirmation">Konfirmasi Password</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Siswa -->
                    <div id="siswa-form" class="form-section" style="display: none;">
                        <h3 class="section-title">Informasi Siswa</h3>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('nis') is-invalid @enderror"
                                        id="nis" name="nis" placeholder="NIS" value="{{ old('nis') }}">
                                    <label for="nis">NIS</label>
                                    @error('nis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('kelas') is-invalid @enderror"
                                        id="kelas" name="kelas" placeholder="Kelas" value="{{ old('kelas') }}">
                                    <label for="kelas">Kelas</label>
                                    @error('kelas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <div class="d-flex">
                                        <div class="form-check me-4">
                                            <input class="form-check-input" type="radio" name="gender"
                                                id="gender-l" value="L"
                                                {{ old('gender') == 'L' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="gender-l">
                                                Laki-laki
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender"
                                                id="gender-p" value="P"
                                                {{ old('gender') == 'P' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="gender-p">
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
                                <div class="form-floating">
                                    <input type="date"
                                        class="form-control @error('birthdate') is-invalid @enderror" id="birthdate"
                                        name="birthdate" placeholder="Tanggal Lahir" value="{{ old('birthdate') }}">
                                    <label for="birthdate">Tanggal Lahir</label>
                                    @error('birthdate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" placeholder="Nomor Telepon"
                                        value="{{ old('phone') }}">
                                    <label for="phone">Nomor Telepon</label>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address"
                                        placeholder="Alamat" style="height: 100px">{{ old('address') }}</textarea>
                                    <label for="address">Alamat</label>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Pembina -->
                    <div id="pembina-form" class="form-section" style="display: none;">
                        <h3 class="section-title">Informasi Pembina</h3>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text"
                                        class="form-control @error('specialty') is-invalid @enderror" id="specialty"
                                        name="specialty" placeholder="Spesialisasi" value="{{ old('specialty') }}">
                                    <label for="specialty">Spesialisasi</label>
                                    @error('specialty')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number"
                                        class="form-control @error('experience') is-invalid @enderror"
                                        id="experience" name="experience" placeholder="Pengalaman (Tahun)"
                                        value="{{ old('experience') }}" min="0">
                                    <label for="experience">Pengalaman (Tahun)</label>
                                    @error('experience')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-floating">
                            <textarea class="form-control @error('certificates') is-invalid @enderror" id="certificates" name="certificates"
                                placeholder="Sertifikat" style="height: 100px">{{ old('certificates') }}</textarea>
                            <label for="certificates">Sertifikat</label>
                            <div class="form-text">Pisahkan dengan baris baru untuk setiap sertifikat</div>
                            @error('certificates')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-register">
                            <i class="fas fa-user-plus me-2"></i> Daftar
                        </button>
                    </div>
                </form>

                <div class="divider">
                    <span>atau</span>
                </div>

                <div class="text-center">
                    <p class="mb-3">Sudah memiliki akun?</p>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-sign-in-alt me-2"></i> Login
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center mt-3 text-white">
            <p>&copy; {{ date('Y') }} MA Modern Miftahussa'adah Cimahi. All rights reserved.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
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
