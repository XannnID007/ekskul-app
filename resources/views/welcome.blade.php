<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengelolaan dan Rekomendasi Ekstrakurikuler - MA Modern Miftahussa'adah Cimahi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
        }

        .hero-section {
            background: linear-gradient(rgba(0, 123, 255, 0.7), rgba(0, 123, 255, 0.7)), url('/img/school-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
        }

        .feature-box {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
            transition: transform 0.3s;
        }

        .feature-box:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: #007bff;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-school me-2"></i>
                MA Modern Miftahussa'adah
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#"><i class="fas fa-home me-1"></i> Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features"><i class="fas fa-list me-1"></i> Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about"><i class="fas fa-info-circle me-1"></i> Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact"><i class="fas fa-envelope me-1"></i> Kontak</a>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-light ms-2" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-light text-primary ms-2" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i> Daftar
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @if (Auth::user()->isAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                @elseif(Auth::user()->isSiswa())
                                    <li><a class="dropdown-item" href="{{ route('siswa.dashboard') }}">Dashboard</a></li>
                                @elseif(Auth::user()->isPembina())
                                    <li><a class="dropdown-item" href="{{ route('pembina.dashboard') }}">Dashboard</a></li>
                                @endif
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Sistem Pengelolaan dan Rekomendasi Ekstrakurikuler</h1>
            <p class="lead mb-5">Platform terintegrasi untuk mengelola dan merekomendasikan kegiatan ekstrakurikuler
                yang sesuai dengan minat dan bakat siswa.</p>
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-light btn-lg px-4 gap-3">
                        <i class="fas fa-sign-in-alt me-2"></i> Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="fas fa-user-plus me-2"></i> Daftar
                    </a>
                @else
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-lg px-4 gap-3">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard Admin
                        </a>
                    @elseif(Auth::user()->isSiswa())
                        <a href="{{ route('siswa.dashboard') }}" class="btn btn-light btn-lg px-4 gap-3">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard Siswa
                        </a>
                    @elseif(Auth::user()->isPembina())
                        <a href="{{ route('pembina.dashboard') }}" class="btn btn-light btn-lg px-4 gap-3">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard Pembina
                        </a>
                    @endif
                @endguest
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5" id="features">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Fitur Utama</h2>
                <p class="lead text-muted">Mengoptimalkan pengelolaan ekstrakurikuler dengan berbagai fitur unggulan</p>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-box text-center">
                        <div class="feature-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h3>Rekomendasi Pintar</h3>
                        <p>Sistem rekomendasi ekstrakurikuler berbasis algoritma Naive Bayes yang memberikan saran
                            berdasarkan minat dan bakat siswa.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box text-center">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3>Pengelolaan Jadwal</h3>
                        <p>Manajemen jadwal kegiatan ekstrakurikuler yang efisien dan terintegrasi untuk memudahkan
                            koordinasi.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box text-center">
                        <div class="feature-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h3>Pelacakan Kehadiran</h3>
                        <p>Sistem pencatatan kehadiran digital untuk memantau partisipasi siswa dalam kegiatan
                            ekstrakurikuler.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box text-center">
                        <div class="feature-icon">
                            <i class="fas fa-medal"></i>
                        </div>
                        <h3>Pencatatan Prestasi</h3>
                        <p>Dokumentasi prestasi siswa dalam kegiatan ekstrakurikuler untuk evaluasi dan pengembangan.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box text-center">
                        <div class="feature-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h3>Laporan & Analitik</h3>
                        <p>Analisis data dan laporan komprehensif tentang kegiatan ekstrakurikuler untuk pengambilan
                            keputusan.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box text-center">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>Manajemen Pengguna</h3>
                        <p>Sistem manajemen pengguna dengan role berbeda (Admin, Siswa, Pembina) untuk mengoptimalkan
                            alur kerja.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="bg-light py-5" id="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">Tentang Sistem</h2>
                    <p class="lead">Sistem Pengelolaan dan Rekomendasi Kegiatan Ekstrakurikuler dikembangkan untuk
                        mengoptimalkan proses pengelolaan dan pemilihan ekstrakurikuler di MA Modern Miftahussa'adah
                        Cimahi.</p>
                    <p>Menggunakan algoritma Naive Bayes dengan pendekatan Rapid Application Development, sistem ini
                        dirancang untuk:</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent"><i class="fas fa-check text-primary me-2"></i>
                            Meningkatkan efisiensi pengelolaan kegiatan</li>
                        <li class="list-group-item bg-transparent"><i class="fas fa-check text-primary me-2"></i>
                            Membantu siswa memilih ekstrakurikuler yang sesuai</li>
                        <li class="list-group-item bg-transparent"><i class="fas fa-check text-primary me-2"></i>
                            Meningkatkan partisipasi siswa dalam kegiatan</li>
                        <li class="list-group-item bg-transparent"><i class="fas fa-check text-primary me-2"></i>
                            Mempermudah koordinasi antar stakeholder</li>
                        <li class="list-group-item bg-transparent"><i class="fas fa-check text-primary me-2"></i>
                            Mendokumentasikan prestasi dan perkembangan siswa</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <img src="/img/about-illustration.svg" alt="Ilustrasi Sistem" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-5" id="contact">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Kontak Kami</h2>
                <p class="lead text-muted">Hubungi kami untuk informasi lebih lanjut</p>
            </div>
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h3 class="card-title mb-4">Informasi Kontak</h3>
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                    Jl. Contoh No. 123, Cimahi, Jawa Barat
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-phone text-primary me-2"></i>
                                    (022) 1234567
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-envelope text-primary me-2"></i>
                                    info@miftahussaadah.sch.id
                                </li>
                                <li>
                                    <i class="fas fa-clock text-primary me-2"></i>
                                    Senin - Jumat: 08.00 - 16.00
                                </li>
                            </ul>
                            <div class="mt-4">
                                <a href="#" class="text-decoration-none me-3 fs-5">
                                    <i class="fab fa-facebook-square text-primary"></i>
                                </a>
                                <a href="#" class="text-decoration-none me-3 fs-5">
                                    <i class="fab fa-instagram text-primary"></i>
                                </a>
                                <a href="#" class="text-decoration-none me-3 fs-5">
                                    <i class="fab fa-twitter-square text-primary"></i>
                                </a>
                                <a href="#" class="text-decoration-none fs-5">
                                    <i class="fab fa-youtube text-primary"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h3 class="card-title mb-4">Kirim Pesan</h3>
                            <form>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="name"
                                        placeholder="Masukkan nama Anda">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email"
                                        placeholder="Masukkan email Anda">
                                </div>
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subjek</label>
                                    <input type="text" class="form-control" id="subject"
                                        placeholder="Masukkan subjek pesan">
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Pesan</label>
                                    <textarea class="form-control" id="message" rows="4" placeholder="Masukkan pesan Anda"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i> Kirim Pesan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-school me-2"></i> MA Modern Miftahussa'adah Cimahi</h5>
                    <p>Sistem Pengelolaan dan Rekomendasi Kegiatan Ekstrakurikuler</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; {{ date('Y') }} Hak Cipta Dilindungi. Dikembangkan oleh Tim Pengembang.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
