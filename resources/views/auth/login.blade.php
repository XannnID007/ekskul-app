<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sistem Ekstrakurikuler</title>
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
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
        }

        .login-row {
            display: flex;
            flex-wrap: wrap;
        }

        .login-image {
            flex: 1;
            background-color: #007bff;
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            min-height: 500px;
        }

        .login-image::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('/img/students.svg');
            background-size: cover;
            background-position: center;
            opacity: 0.2;
        }

        .login-image-content {
            position: relative;
            z-index: 1;
        }

        .login-form {
            flex: 1;
            padding: 40px;
        }

        .login-logo {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .login-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .login-subtitle {
            font-size: 1rem;
            margin-bottom: 30px;
            color: rgba(255, 255, 255, 0.8);
        }

        .feature-list {
            text-align: left;
            list-style-type: none;
            padding-left: 0;
            margin-top: 30px;
        }

        .feature-list li {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .feature-list li i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .form-floating {
            margin-bottom: 20px;
        }

        .btn-login {
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

        @media (max-width: 768px) {
            .login-image {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-container">
            <div class="login-row">
                <div class="login-image">
                    <div class="login-image-content">
                        <div class="login-logo">
                            <i class="fas fa-school"></i>
                        </div>
                        <h2 class="login-title">MA Modern Miftahussa'adah</h2>
                        <p class="login-subtitle">Sistem Pengelolaan dan Rekomendasi Kegiatan Ekstrakurikuler</p>

                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle"></i> Pengelolaan kegiatan ekstrakurikuler terintegrasi
                            </li>
                            <li><i class="fas fa-check-circle"></i> Rekomendasi ekstrakurikuler sesuai dengan minat dan
                                bakat</li>
                            <li><i class="fas fa-check-circle"></i> Pelacakan kehadiran dan prestasi siswa</li>
                            <li><i class="fas fa-check-circle"></i> Laporan dan analitik kegiatan ekstrakurikuler</li>
                        </ul>
                    </div>
                </div>

                <div class="login-form">
                    <h3 class="mb-4">Login ke Akun Anda</h3>

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

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-floating">
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" placeholder="name@example.com" value="{{ old('email') }}"
                                required autofocus>
                            <label for="email">Email</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Password" required>
                            <label for="password">Password</label>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Ingat Saya
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-login">
                                <i class="fas fa-sign-in-alt me-2"></i> Login
                            </button>
                        </div>
                    </form>

                    <div class="divider">
                        <span>atau</span>
                    </div>

                    <div class="text-center">
                        <p class="mb-4">Belum memiliki akun?</p>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-3 text-white">
            <p>&copy; {{ date('Y') }} MA Modern Miftahussa'adah Cimahi. All rights reserved.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
