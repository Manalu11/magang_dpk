<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Pendaftaran Magang') — Dinas Perpustakaan dan Kearsipan Kota Bontang</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite(['resources/css/app.css','resources/js/app.js'])
    @stack('styles')

    <style>
    /* ===== SIDEBAR ===== */
    .sidebar-nav .nav-link {
        color: #64748b;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .sidebar-nav .nav-link:hover {
        background-color: #eff6ff;
        color: #2563eb;
    }

    .sidebar-nav .nav-link.active-menu {
        background-color: #2563eb;
        color: #ffffff !important;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
    }

    .sidebar-nav .nav-link.active-menu i {
        color: #ffffff;
    }

    .sidebar-brand {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        padding: 20px;
    }

    .sidebar-brand h5 {
        color: #fff;
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 2px;
    }

    .sidebar-brand small {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.75rem;
    }

    .sidebar-nav .logout-btn {
        color: #ef4444;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s ease;
        width: 100%;
        text-align: left;
    }

    .sidebar-nav .logout-btn:hover {
        background-color: #fef2f2;
        color: #dc2626;
    }

    /* ===== HEADER ===== */
    .main-header {
        background: #fff;
        border-bottom: 1px solid #e2e8f0;
        padding: 14px 28px;
    }

    .main-header h6 {
        font-weight: 700;
        color: #1e293b;
        font-size: 1rem;
    }

    /* ===== FOOTER LINK ===== */
    .footer-link:hover {
        color: #ffffff !important;
        padding-left: 6px;
        transition: all 0.3s ease;
    }
    </style>
</head>

<body>

    {{-- ================= NAVBAR ================= --}}
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #0F3D73;">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" width="40">
                <div class="lh-1">
                    <div class="fw-bold fs-6">Dinas Perpustakaan dan Kearsipan</div>
                    <div class="text-white-50" style="font-size: 0.7rem;">Sistem Pendaftaran Magang</div>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <span class="nav-link text-white-50">
                            <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                        </span>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm ms-2">
                                <i class="bi bi-box-arrow-right me-1"></i>Keluar
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success') || session('error') || session('warning') || session('info'))
    <div class="container-fluid px-4 mt-3">
        @foreach(['success', 'error', 'warning', 'info'] as $type)
        @if(session($type))
        <div class="alert alert-{{ $type === 'error' ? 'danger' : $type }} alert-dismissible fade show d-flex align-items-center gap-2"
            role="alert">
            <i
                class="bi bi-{{ $type === 'success' ? 'check-circle' : ($type === 'error' ? 'x-circle' : ($type === 'warning' ? 'exclamation-triangle' : 'info-circle')) }}-fill"></i>
            <span>{{ session($type) }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @endforeach
    </div>
    @endif

    {{-- ================= BODY: SIDEBAR + KONTEN ================= --}}
    <div class="d-flex" style="min-height: calc(100vh - 56px); background: #f1f5f9;">

        {{-- SIDEBAR --}}
        <aside class="bg-white border-end d-flex flex-column" style="width: 260px; min-height: 100%;">

            <div class="sidebar-brand">
                <h5><i class="bi bi-mortarboard-fill me-2"></i>E-Magang</h5>
                <small>Panel Peserta</small>
            </div>

            <div class="p-3 flex-grow-1">
                <p class="text-uppercase text-muted px-2 mb-2"
                    style="font-size: 0.7rem; letter-spacing: 0.08em; font-weight: 600;">
                    Menu Utama
                </p>

                <ul class="nav flex-column gap-1 sidebar-nav">
                    <li class="nav-item">
                        <a href="{{ route('peserta.dashboard') }}"
                            class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('peserta.dashboard*') ? 'active-menu' : '' }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('peserta.pendaftaran.show') }}"
                            class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('peserta.pendaftaran.*') ? 'active-menu' : '' }}">
                            <i class="bi bi-file-earmark-text"></i> Status Pendaftaran
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('peserta.laporan.index') }}"
                            class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('peserta.laporan.*') ? 'active-menu' : '' }}">
                            <i class="bi bi-journal-text"></i> Laporan Akhir
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('peserta.absensi.index') }}"
                            class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('peserta.absensi.*') ? 'active-menu' : '' }}">
                            <i class="bi bi-calendar-check"></i> Absensi
                        </a>
                    </li>
                </ul>
            </div>

            <div class="p-3 border-top sidebar-nav">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn border-0 bg-transparent d-flex align-items-center gap-2">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>

        </aside>

        {{-- MAIN CONTENT --}}
        <div class="flex-grow-1 d-flex flex-column">


            {{-- Konten --}}
            <div class="p-4 flex-grow-1">
                @yield('content')
            </div>

        </div>
    </div>

    {{-- ================= FOOTER ================= --}}
    <footer class="footer py-5 text-white"
        style="background: linear-gradient(135deg, #0f2f5f 0%, #1c4b8f 50%, #0f2f5f 100%);">
        <div class="container">
            <div class="row g-4">

                <div class="col-md-5">
                    <h5 class="fw-bold mb-3">Dinas Perpustakaan dan Kearsipan</h5>
                    <p class="text-white-50 mb-1">Kota Bontang, Kalimantan Timur</p>
                    <p class="text-white-50 mb-0">
                        Gedung Dinas Perpustakaan dan Kearsipan, Jl. HM. Ardans No. 1,
                        Kelurahan Satimpo, Kecamatan Bontang Selatan, Kota Bontang,
                        Kalimantan Timur 75324
                    </p>
                </div>

                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">Tautan Cepat</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('home') }}"
                                class="text-white-50 text-decoration-none footer-link">Beranda</a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('bidang.index') }}"
                                class="text-white-50 text-decoration-none footer-link">Bidang Magang</a>
                        </li>
                        <li>
                            <a href="{{ route('peserta.dashboard') }}"
                                class="text-white-50 text-decoration-none footer-link">Dashboard</a>
                        </li>
                    </ul>
                </div>

                <div class="col-md-3">
                    <h5 class="fw-bold mb-3">Kontak</h5>
                    <p class="text-white-50 mb-2 d-flex align-items-center">
                        <i class="bi bi-envelope me-2"></i>
                        <span>dinasperpustakaan@bontangkota.go.id</span>
                    </p>
                    <p class="text-white-50 mb-1">
                        <i class="bi bi-telephone me-2"></i>0821-4848-4996 (PPID DPK)
                    </p>
                    <p class="text-white-50 mb-0">
                        <i class="bi bi-telephone me-2"></i>0822-4989-6919 (Call Center)
                    </p>
                </div>

            </div>

            <hr class="border-light opacity-25 my-4">

            <div class="text-center text-white-50 small">
                &copy; {{ date('Y') }} Dinas Perpustakaan dan Kearsipan Kota Bontang. Semua hak dilindungi.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

</body>

</html>