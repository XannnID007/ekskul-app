<!-- File: resources/views/auth/login.blade.php -->

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sistem Ekstrakurikuler</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/purpleadmin/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/purpleadmin/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/purpleadmin/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/purpleadmin/images/favicon.ico') }}" />
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
                <div class="row flex-grow">
                    <div class="col-lg-6 d-flex align-items-center justify-content-center">
                        <div class="auth-form-transparent text-left p-3">
                            <div class="brand-logo">
                                <h3 class="text-primary"><i class="mdi mdi-school"></i> MA Modern Miftahussa'adah</h3>
                            </div>
                            <h4>Selamat Datang!</h4>
                            <h6 class="font-weight-light">Login ke Akun Anda</h6>

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

                            <form class="pt-3" method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="mdi mdi-account-outline text-primary"></i>
                                            </span>
                                        </div>
                                        <input type="email"
                                            class="form-control form-control-lg border-left-0 @error('email') is-invalid @enderror"
                                            id="email" name="email" placeholder="Email"
                                            value="{{ old('email') }}" required autofocus>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="mdi mdi-lock-outline text-primary"></i>
                                            </span>
                                        </div>
                                        <input type="password"
                                            class="form-control form-control-lg border-left-0 @error('password') is-invalid @enderror"
                                            id="password" name="password" placeholder="Password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="my-2 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <label class="form-check-label text-muted">
                                            <input type="checkbox" class="form-check-input" name="remember"
                                                id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            Ingat Saya
                                        </label>
                                    </div>
                                </div>

                                <div class="my-3">
                                    <button type="submit"
                                        class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                                        <i class="mdi mdi-login me-2"></i> Login
                                    </button>
                                </div>

                                <div class="text-center mt-4 font-weight-light">
                                    Belum memiliki akun? <a href="{{ route('register') }}" class="text-primary">Daftar
                                        Sekarang</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6 login-half-bg d-flex flex-row"
                        style="background: linear-gradient(rgba(103, 58, 183, 0.8), rgba(103, 58, 183, 0.8)), url('/img/school-bg.jpg'); background-size: cover;">
                        <div class="p-5 text-center text-white">
                            <h2 class="mb-3">Sistem Pengelolaan dan Rekomendasi Kegiatan Ekstrakurikuler</h2>

                            <ul class="list-unstyled text-start mt-4">
                                <li class="mb-3"><i class="mdi mdi-check-circle me-2"></i> Pengelolaan kegiatan
                                    ekstrakurikuler terintegrasi</li>
                                <li class="mb-3"><i class="mdi mdi-check-circle me-2"></i> Rekomendasi ekstrakurikuler
                                    sesuai dengan minat dan bakat</li>
                                <li class="mb-3"><i class="mdi mdi-check-circle me-2"></i> Pelacakan kehadiran dan
                                    prestasi siswa</li>
                                <li class="mb-3"><i class="mdi mdi-check-circle me-2"></i> Laporan dan analitik
                                    kegiatan ekstrakurikuler</li>
                            </ul>
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
</body>

</html>
