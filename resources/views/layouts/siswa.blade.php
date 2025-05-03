<!-- File: resources/views/layouts/siswa.blade.php -->

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Siswa Sistem Ekstrakurikuler</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/purpleadmin/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/purpleadmin/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/purpleadmin/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/purpleadmin/images/favicon.ico') }}" />

    <style>
        .sidebar {
            background: linear-gradient(to bottom, #007bff, #00c6ff);
        }

        .required-label::after {
            content: " *";
            color: #dc3545;
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="container-scroller">
        <!-- Navbar -->
        <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo" href="{{ route('siswa.dashboard') }}">
                    <span class="font-weight-bold">EKSKUL SISWA</span>
                </a>
                <a class="navbar-brand brand-logo-mini" href="{{ route('siswa.dashboard') }}">
                    <span class="font-weight-bold">ES</span>
                </a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-stretch">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="mdi mdi-menu"></span>
                </button>
                <div class="search-field d-none d-md-block">
                    <form class="d-flex align-items-center h-100" action="#">
                        <div class="input-group">
                            <div class="input-group-prepend bg-transparent">
                                <i class="input-group-text border-0 mdi mdi-magnify"></i>
                            </div>
                            <input type="text" class="form-control bg-transparent border-0" placeholder="Cari...">
                        </div>
                    </form>
                </div>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" id="profileDropdown" href="#"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="nav-profile-img">
                                <img src="{{ asset('assets/purpleadmin/images/faces/face1.jpg') }}" alt="profile">
                                <span class="availability-status online"></span>
                            </div>
                            <div class="nav-profile-text">
                                <p class="mb-1 text-black">{{ Auth::user()->name }}</p>
                            </div>
                        </a>
                        <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                            <a class="dropdown-item" href="{{ route('siswa.profile') }}">
                                <i class="mdi mdi-account me-2 text-primary"></i> Profil
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="dropdown-item" href="#"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="mdi mdi-logout me-2 text-primary"></i> Logout
                                </a>
                            </form>
                        </div>
                    </li>
                    <li class="nav-item d-none d-lg-block full-screen-link">
                        <a class="nav-link">
                            <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#"
                            data-bs-toggle="dropdown">
                            <i class="mdi mdi-bell-outline"></i>
                            <span class="count-symbol bg-danger"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                            aria-labelledby="notificationDropdown">
                            <h6 class="p-3 mb-0">Notifikasi</h6>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-success">
                                        <i class="mdi mdi-calendar"></i>
                                    </div>
                                </div>
                                <!-- File: resources/views/layouts/siswa.blade.php (continued) -->
                                <div
                                    class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                    <h6 class="preview-subject font-weight-normal mb-1">Kegiatan Hari Ini</h6>
                                    <p class="text-gray ellipsis mb-0">Latihan basket 15:00 WIB</p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-info">
                                        <i class="mdi mdi-trophy"></i>
                                    </div>
                                </div>
                                <div
                                    class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                    <h6 class="preview-subject font-weight-normal mb-1">Prestasi Baru</h6>
                                    <p class="text-gray ellipsis mb-0">Selamat! Kamu mendapatkan penghargaan</p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <h6 class="p-3 mb-0 text-center">Lihat Semua Notifikasi</h6>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>

        <div class="container-fluid page-body-wrapper">
            <!-- Sidebar -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item nav-profile">
                        <a href="#" class="nav-link">
                            <div class="nav-profile-image">
                                <img src="{{ asset('assets/purpleadmin/images/faces/face1.jpg') }}" alt="profile">
                                <span class="login-status online"></span>
                            </div>
                            <div class="nav-profile-text d-flex flex-column">
                                <span class="font-weight-bold mb-2">{{ Auth::user()->name }}</span>
                                <span class="text-secondary text-small">Siswa</span>
                            </div>
                            <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('siswa.dashboard') }}">
                            <span class="menu-title">Dashboard</span>
                            <i class="mdi mdi-home menu-icon"></i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('siswa.recommendations.show') }}">
                            <span class="menu-title">Rekomendasi</span>
                            <i class="mdi mdi-lightbulb-on menu-icon"></i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('siswa.enrollments.index') }}">
                            <span class="menu-title">Ekstrakurikuler Saya</span>
                            <i class="mdi mdi-school menu-icon"></i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('siswa.schedule') }}">
                            <span class="menu-title">Jadwal Kegiatan</span>
                            <i class="mdi mdi-calendar menu-icon"></i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('siswa.achievements') }}">
                            <span class="menu-title">Prestasi</span>
                            <i class="mdi mdi-trophy menu-icon"></i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('siswa.profile') }}">
                            <span class="menu-title">Profil Saya</span>
                            <i class="mdi mdi-account-cog menu-icon"></i>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Main Content -->
            <div class="main-panel">
                <div class="content-wrapper">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>

                <!-- Footer -->
                <footer class="footer">
                    <div class="container-fluid d-flex justify-content-between">
                        <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">
                            Hak Cipta &copy; Sistem Ekstrakurikuler MA Modern Miftahussa'adah {{ date('Y') }}
                        </span>
                        <span class="float-none float-sm-end mt-1 mt-sm-0 text-end">
                            <a href="#">Kebijakan Privasi</a> &middot; <a href="#">Syarat &amp;
                                Ketentuan</a>
                        </span>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/purpleadmin/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/purpleadmin/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/purpleadmin/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets/purpleadmin/js/misc.js') }}"></script>

    <script>
        // Initialize DataTable if present
        document.addEventListener('DOMContentLoaded', function() {
            const dataTables = document.querySelectorAll('.datatable');
            if (dataTables.length > 0 && typeof simpleDatatables !== 'undefined') {
                dataTables.forEach(table => {
                    new simpleDatatables.DataTable(table);
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
