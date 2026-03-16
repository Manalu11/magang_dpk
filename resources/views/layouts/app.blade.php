<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Pendaftaran Magang') — Dinas perpustakaan dan Kearsipan Kota Bontang</title>

    <!-- Bootstrap 5 -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>

<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #0F3D73;">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" height="40">
                <div class="lh-1">
                    <div class="fw-700 fs-6">Dinas Perpustakaan dan Kearsipan</div>
                    <div class="text-white-50" style="font-size: 0.7rem;">
                        Sistem Pendaftaran Magang
                    </div>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                            href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('bidang.*') ? 'active' : '' }}"
                            href="{{ route('bidang.index') }}">Bidang Magang</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    @auth
                    @if(auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard Admin
                        </a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('peserta.dashboard') }}">
                            <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                        </a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm ms-2">
                                <i class="bi bi-box-arrow-right me-1"></i>Keluar
                            </button>
                        </form>
                    </li>
                    @else
                    <li class="nav-item me-2">
                        <a class="nav-link" href="{{ route('login') }}">Masuk</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-light btn-sm text-primary fw-600" href="{{ route('register') }}">Daftar
                            Sekarang</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success') || session('error') || session('warning') || session('info'))
    <div class="container mt-3">
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

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="footer mt-5 py-5 text-white"
        style="background: linear-gradient(135deg, #0f2f5f 0%, #1c4b8f 50%, #0f2f5f 100%);">

        <style>
        .footer-link:hover {
            color: #ffffff !important;
            padding-left: 6px;
            transition: all 0.3s ease;
        }
        </style>

        <div class="container">
            <div class="row g-4">

                <!-- Kolom 1 -->
                <div class="col-md-5">
                    <h5 class="fw-bold mb-3">Dinas Perpustakaan dan Kearsipan</h5>
                    <p class="text-white-50 mb-1">
                        Kota Bontang, Kalimantan Timur
                    </p>
                    <p class="text-white-50 mb-0">
                        Gedung Dinas Perpustakaan dan Kearsipan, Jl. HM. Ardans No. 1, Kelurahan Satimpo, Kecamatan
                        Bontang Selatan, Kota Bontang, Kalimantan Timur 75324
                    </p>
                </div>

                <!-- Kolom 2 -->
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">Tautan Cepat</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('home') }}" class="text-white-50 text-decoration-none footer-link">
                                Beranda
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('bidang.index') }}"
                                class="text-white-50 text-decoration-none footer-link">
                                Bidang Magang
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}" class="text-white-50 text-decoration-none footer-link">
                                Pendaftaran
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Kolom 3 -->
                <div class="col-md-3">
                    <h5 class="fw-bold mb-3">Kontak</h5>
                    <p class="text-white-50 mb-2 d-flex align-items-center">
                        <i class="bi bi-envelope me-2"></i>
                        <span>dinasperpustakaan@bontangkota.go.id</span>
                    </p>
                    <p class="text-white-50 mb-0">
                        <i class="bi bi-telephone me-2"></i>
                        0821-4848-4996 ( PPID DPK )
                    </p>
                    <p class="text-white-50 mb-0">
                        <i class="bi bi-telephone me-2"></i>
                        0822-4989-6919 (Call Center Perpustakaan)
                    </p>
                    </p>
                </div>

            </div>

            <hr class="border-light opacity-25 my-4">

            <div class="text-center text-white-50 small">
                &copy; {{ date('Y') }} Dinas Perpustakaan dan Kearsipan Kota Bontang.
                Semua hak dilindungi.
            </div>
        </div>
    </footer>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>