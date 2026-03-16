<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — Dinas perpustakaan dan kearsipan</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
    .admin-wrapper {
        display: flex;
        min-height: 100vh;
    }

    .sidebar {
        width: 260px;
        height: 100vh;
        /* ← ganti min-height jadi height */
        background: linear-gradient(180deg, #0F3D73 0%, #0a2d57 100%);
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
        overflow-y: auto;
        overflow-x: hidden;
        /* ← tambahan */
        transition: all 0.3s;
        scrollbar-width: thin;
        /* ← tambahan, biar scrollbar tipis */
        scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
    }

    .sidebar-brand {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-nav .nav-link {
        color: rgba(255, 255, 255, 0.75);
        padding: 0.625rem 1.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 0;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.625rem;
    }

    .sidebar-nav .nav-link:hover,
    .sidebar-nav .nav-link.active {
        color: #fff;
        background: rgba(255, 255, 255, 0.12);
        padding-left: 1.75rem;
    }

    .sidebar-nav .nav-section {
        color: rgba(255, 255, 255, 0.4);
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 1.25rem 1.5rem 0.5rem;
    }

    .sidebar-nav .nav-badge {
        margin-left: auto;
        font-size: 0.65rem;
        padding: 2px 7px;
        border-radius: 20px;
    }

    .admin-main {
        margin-left: 260px;
        flex: 1;
        background: #F2F4F7;
        min-height: 100vh;
    }

    .admin-topbar {
        background: #fff;
        padding: 0.875rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 999;
    }

    .admin-content {
        padding: 1.5rem;
    }

    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .admin-main {
            margin-left: 0;
        }
    }
    </style>
    @stack('styles')
</head>

<body>
    <div class="admin-wrapper">

        {{-- ===== SIDEBAR ===== --}}
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width:35px; height:auto;">
                    <div class="text-white fw-700 fs-6">Dinas Perpustakaan dan Kearsipan</div>
                </div>
                <div class="text-white-50" style="font-size:0.7rem; margin-left:43px;">Panel Administrasi</div>
            </div>

            <nav class="sidebar-nav py-2">

                {{-- MENU UTAMA --}}
                <div class="nav-section">Menu Utama</div>
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>

                {{-- PENDAFTARAN --}}
                <div class="nav-section">Pendaftaran</div>
                <a href="{{ route('admin.pendaftaran.index') }}"
                    class="nav-link {{ request()->routeIs('admin.pendaftaran.*') && !request('status') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-person"></i> Semua Pendaftar
                </a>
                <a href="{{ route('admin.pendaftaran.index', ['status' => 'pending']) }}"
                    class="nav-link {{ request()->routeIs('admin.pendaftaran.*') && request('status') === 'pending' ? 'active' : '' }}">
                    <i class="bi bi-hourglass-split"></i> Menunggu Review
                    @php $pendingDaftar = \App\Models\Pendaftaran::where('status','pending')->count(); @endphp
                    @if($pendingDaftar > 0)
                    <span class="nav-badge bg-warning text-dark">{{ $pendingDaftar }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.pendaftaran.index', ['status' => 'diterima']) }}"
                    class="nav-link {{ request()->routeIs('admin.pendaftaran.*') && request('status') === 'diterima' ? 'active' : '' }}">
                    <i class="bi bi-check-circle"></i> Diterima
                </a>
                <a href="{{ route('admin.pendaftaran.index', ['status' => 'ditolak']) }}"
                    class="nav-link {{ request()->routeIs('admin.pendaftaran.*') && request('status') === 'ditolak' ? 'active' : '' }}">
                    <i class="bi bi-x-circle"></i> Ditolak
                </a>

                {{-- LAPORAN & ABSENSI --}}
                <div class="nav-section mt-2 border-top border-white border-opacity-10 pt-3">Laporan & Absensi</div>

                <a href="{{ route('admin.laporan.index') }}"
                    class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-richtext"></i> Laporan Akhir
                    @php $pendingAkhir = \App\Models\LaporanAkhir::where('status','pending')->count(); @endphp
                    @if($pendingAkhir > 0)
                    <span class="nav-badge bg-warning text-dark">{{ $pendingAkhir }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.absensi.index') }}"
                    class="nav-link {{ request()->routeIs('admin.absensi.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i> Laporan Absensi
                </a>

                {{-- MASTER DATA --}}
                <div class="nav-section mt-2 border-top border-white border-opacity-10 pt-3">Master Data</div>
                <a href="{{ route('admin.bidang.index') }}"
                    class="nav-link {{ request()->routeIs('admin.bidang.*') ? 'active' : '' }}">
                    <i class="bi bi-collection"></i> Management Bidang
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Management User
                </a>

                {{-- AKUN --}}
                <div class="nav-section mt-2 border-top border-white border-opacity-10 pt-3">Akun</div>
                <a href="{{ route('home') }}" class="nav-link" target="_blank">
                    <i class="bi bi-globe"></i> Lihat Website
                </a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent"
                        style="color: rgba(255,100,100,0.8)!important;">
                        <i class="bi bi-box-arrow-right"></i> Keluar
                    </button>
                </form>

            </nav>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="admin-main">
            <div class="admin-topbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-sm btn-outline-secondary d-md-none" id="sidebarToggle">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <nav aria-label="breadcrumb" class="mb-0">
                        <ol class="breadcrumb mb-0 small">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
                            </li>
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="text-muted small">{{ auth()->user()->name }}</div>
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-600"
                        style="width:32px;height:32px;font-size:0.8rem;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>

            <div class="admin-content">
                @foreach(['success', 'error', 'warning', 'info'] as $type)
                @if(session($type))
                <div class="alert alert-{{ $type === 'error' ? 'danger' : $type }} alert-dismissible fade show d-flex align-items-center gap-2"
                    role="alert">
                    <i
                        class="bi bi-{{ $type === 'success' ? 'check-circle' : ($type === 'error' ? 'x-circle' : ($type === 'warning' ? 'exclamation-triangle' : 'info-circle')) }}-fill"></i>
                    {{ session($type) }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
                @endif
                @endforeach

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('show');
    });
    </script>
    @stack('scripts')
</body>

</html>