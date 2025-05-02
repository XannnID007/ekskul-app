<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - Sistem Ekstrakurikuler</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

    <style>
        .sidebar {
            background-color: #212529;
        }

        .sidebar-brand {
            background-color: #198754;
        }

        .sidebar-brand-text {
            font-size: 1.2rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.75);
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }

        .nav-link:hover {
            color: rgba(255, 255, 255, 1);
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-link.active {
            color: white;
            font-weight: 600;
            background-color: rgba(25, 135, 84, 0.25);
        }

        .sidebar-heading {
            padding: 0.75rem 1rem;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.5);
        }

        .topbar {
            height: 4.375rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(33, 40, 50, 0.15);
        }

        .topbar .dropdown-menu {
            min-width: 12rem;
        }

        .dropdown-user .dropdown-menu {
            min-width: 15rem;
        }

        .dropdown-menu .dropdown-header {
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e3e6f0;
        }

        body {
            background-color: #f8f9fa;
        }

        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(33, 40, 50, 0.15);
            border: 0;
        }

        .card-header {
            font-weight: 500;
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(33, 40, 50, 0.125);
        }
    </style>

    @stack('styles')
</head>

<body class="sb-nav-fixed">
    <!-- Top Navigation -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand -->
        <a class="navbar-brand ps-3" href="{{ route('pembina.dashboard') }}">
            <i class="fas fa-school me-2"></i>
            EKSTRAKURIKULER
        </a>

        <!-- Sidebar Toggle -->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Navbar Search -->
        <div class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Cari..." aria-label="Search">
                <button class="btn btn-success" type="button"><i class="fas fa-search"></i></button>
            </div>
        </div>

        <!-- Navbar -->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle fa-fw"></i> {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="{{ route('pembina.profile') }}"><i
                                class="fas fa-user-cog fa-fw me-2"></i> Profil</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt fa-fw me-2"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    <div id="layoutSidenav">
        <!-- Sidebar Navigation -->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Utama</div>
                        <a class="nav-link {{ request()->routeIs('pembina.dashboard') ? 'active' : '' }}"
                            href="{{ route('pembina.dashboard') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>

                        <div class="sb-sidenav-menu-heading">Ekstrakurikuler</div>
                        <a class="nav-link {{ request()->routeIs('pembina.extracurriculars.*') ? 'active' : '' }}"
                            href="{{ route('pembina.extracurriculars.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-users-class"></i></div>
                            Kelola Ekstrakurikuler
                        </a>
                        <a class="nav-link {{ request()->routeIs('pembina.enrollments.*') ? 'active' : '' }}"
                            href="{{ route('pembina.enrollments.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-check"></i></div>
                            Kelola Pendaftaran
                        </a>

                        <div class="sb-sidenav-menu-heading">Kegiatan</div>
                        <a class="nav-link {{ request()->routeIs('pembina.meetings.*') ? 'active' : '' }}"
                            href="{{ route('pembina.meetings.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-calendar-alt"></i></div>
                            Kelola Pertemuan
                        </a>
                        <a class="nav-link {{ request()->routeIs('pembina.attendances.*') ? 'active' : '' }}"
                            href="{{ route('pembina.attendances.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-clipboard-check"></i></div>
                            Kelola Kehadiran
                        </a>
                        <a class="nav-link {{ request()->routeIs('pembina.achievements.*') ? 'active' : '' }}"
                            href="{{ route('pembina.achievements.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-trophy"></i></div>
                            Kelola Prestasi
                        </a>

                        <div class="sb-sidenav-menu-heading">Laporan</div>
                        <a class="nav-link {{ request()->routeIs('pembina.reports.attendance') ? 'active' : '' }}"
                            href="{{ route('pembina.reports.attendance') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-bar"></i></div>
                            Laporan Kehadiran
                        </a>
                        <a class="nav-link {{ request()->routeIs('pembina.reports.achievements') ? 'active' : '' }}"
                            href="{{ route('pembina.reports.achievements') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-medal"></i></div>
                            Laporan Prestasi
                        </a>

                        <div class="sb-sidenav-menu-heading">Akun</div>
                        <a class="nav-link {{ request()->routeIs('pembina.profile') ? 'active' : '' }}"
                            href="{{ route('pembina.profile') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div>
                            Profil Saya
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Login sebagai:</div>
                    {{ Auth::user()->name }}
                </div>
            </nav>
        </div>

        <!-- Page Content -->
        <div id="layoutSidenav_content">
            <main>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mx-4 mt-4" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mx-4 mt-4" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show mx-4 mt-4" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Hak Cipta &copy; Sistem Ekstrakurikuler MA Modern Miftahussa'adah
                            {{ date('Y') }}</div>
                        <div>
                            <a href="#">Kebijakan Privasi</a>
                            &middot;
                            <a href="#">Syarat &amp; Ketentuan</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>

    <script>
        // Toggle sidebar
        window.addEventListener('DOMContentLoaded', event => {
            const sidebarToggle = document.body.querySelector('#sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', event => {
                    event.preventDefault();
                    document.body.classList.toggle('sb-sidenav-toggled');
                    localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains(
                        'sb-sidenav-toggled'));
                });
            }
        });

        // Datatable initialization
        document.addEventListener('DOMContentLoaded', function() {
            const dataTables = document.querySelectorAll('.datatable');
            if (dataTables.length > 0) {
                dataTables.forEach(table => {
                    new simpleDatatables.DataTable(table);
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
