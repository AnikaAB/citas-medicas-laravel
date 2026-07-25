<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Sistema de Citas Medicas')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    <style>
        :root {
            --brand-1: #4f7cff;
            --brand-2: #7b3fe4;
            --brand-3: #14b8a6;
            --ink: #101423;
            --bg-app: #f4f6fb;
        }
        * { font-family: 'Inter', sans-serif; }
        body {
            background: var(--bg-app);
            min-height: 100vh;
        }

        /* ---- Navbar ---- */
        .navbar-brand-custom {
            background: linear-gradient(135deg, var(--brand-2), var(--brand-1));
            box-shadow: 0 6px 20px rgba(79,124,255,0.25);
        }
        .navbar-brand-custom .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.2px;
            display: flex; align-items: center; gap: 10px;
        }
        .navbar-brand-custom .brand-icon {
            width: 34px; height: 34px;
            border-radius: 10px;
            background: rgba(255,255,255,0.18);
            display: flex; align-items: center; justify-content: center;
        }
        .navbar-brand-custom .nav-link {
            font-weight: 500;
            color: rgba(255,255,255,0.85) !important;
            border-radius: 10px;
            padding: 0.5rem 0.9rem !important;
            transition: background 0.15s ease;
        }
        .navbar-brand-custom .nav-link i { margin-right: 6px; }
        .navbar-brand-custom .nav-link:hover,
        .navbar-brand-custom .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: #fff !important;
        }
        .user-chip {
            background: rgba(255,255,255,0.14);
            border-radius: 12px;
            padding: 6px 14px;
            color: #fff;
            font-size: 0.85rem;
            display: flex; align-items: center; gap: 8px;
        }
        .user-chip .rol-tag {
            background: rgba(255,255,255,0.22);
            border-radius: 8px;
            padding: 1px 8px;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            font-weight: 700;
        }

        /* ---- Contenido ---- */
        .page-shell {
            max-width: 1180px;
            margin: 28px auto;
            padding: 0 20px;
        }
        .content-card {
            background: #fff;
            border-radius: 18px;
            padding: 28px 30px;
            box-shadow: 0 10px 30px rgba(16, 20, 35, 0.06);
            border: 1px solid rgba(16, 20, 35, 0.04);
        }
        h3 { font-weight: 800; color: var(--ink); letter-spacing: -0.3px; }

        /* ---- Botones ---- */
        .btn-primary {
            background: linear-gradient(90deg, var(--brand-1), var(--brand-2));
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 0.55rem 1.1rem;
            box-shadow: 0 6px 16px rgba(79,124,255,0.25);
            transition: transform 0.15s ease;
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 10px 22px rgba(79,124,255,0.32); }
        .btn-outline-secondary, .btn-secondary { border-radius: 10px; font-weight: 500; }
        .btn-sm { border-radius: 8px; }
        .btn-warning { border-radius: 8px; font-weight: 500; }
        .btn-danger { border-radius: 8px; font-weight: 500; }
        .btn-info { border-radius: 8px; font-weight: 500; color: #fff; }

        /* ---- Formularios ---- */
        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid #e5e7eb;
            padding: 0.6rem 0.85rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--brand-1);
            box-shadow: 0 0 0 4px rgba(79,124,255,0.12);
        }
        .form-label { font-weight: 600; color: #374151; font-size: 0.9rem; }

        /* ---- Tablas ---- */
        .table { background: #fff; border-radius: 14px; overflow: hidden; }
        .table thead th {
            background: #f8f9fc;
            color: #374151;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border-bottom: 2px solid #eef0f5;
            font-weight: 700;
        }
        .table td { vertical-align: middle; color: #1f2937; }
        .table-hover tbody tr:hover { background: #f7f9ff; }

        /* ---- Badge de estado (usado por el componente x-estado-badge) ---- */
        .badge-estado {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
        }

        /* ---- Alertas ---- */
        .alert { border-radius: 12px; border: none; }
        .alert-success { background: #DCFCE7; color: #14532D; }
        .alert-danger { background: #FEE2E2; color: #7F1D1D; }

        /* ---- Cards del dashboard ---- */
        .stat-card {
            border-radius: 16px;
            padding: 20px;
            color: #fff;
            box-shadow: 0 10px 24px rgba(0,0,0,0.08);
            height: 100%;
        }
        .stat-card .stat-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            background: rgba(255,255,255,0.22);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 10px;
        }
        .stat-card h2 { font-weight: 800; margin: 0; }
        .stat-card p { margin: 0; opacity: 0.9; font-size: 0.85rem; }
    </style>
</head>
<body>
    @auth
    <nav class="navbar navbar-expand-lg navbar-dark navbar-brand-custom">
        <div class="container" style="max-width: 1180px;">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <span class="brand-icon"><i class="bi bi-heart-pulse-fill"></i></span>
                Citas Médicas
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" style="border-color: rgba(255,255,255,0.4);">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2"></i>Panel
                        </a>
                    </li>
                    @if(in_array(auth()->user()->rol, ['admin','recepcionista','doctor']))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('citas.*') ? 'active' : '' }}" href="{{ route('citas.index') }}">
                                <i class="bi bi-calendar2-week"></i>Citas
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->rol === 'paciente')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('mis-citas.*') ? 'active' : '' }}" href="{{ route('mis-citas.index') }}">
                                <i class="bi bi-calendar2-heart"></i>Mis citas
                            </a>
                        </li>
                    @endif
                    @if(in_array(auth()->user()->rol, ['admin','recepcionista']))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('pacientes.*') ? 'active' : '' }}" href="{{ route('pacientes.index') }}">
                                <i class="bi bi-people"></i>Pacientes
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->rol === 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('doctores.*') ? 'active' : '' }}" href="{{ route('doctores.index') }}">
                                <i class="bi bi-clipboard2-pulse"></i>Doctores
                            </a>
                        </li>
                    @endif
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <div class="user-chip">
                        <i class="bi bi-person-circle"></i>
                        {{ auth()->user()->name }}
                        <span class="rol-tag">{{ auth()->user()->rol }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-light btn-sm" style="border-radius: 8px;">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <div class="page-shell">
        @if(session('exito'))
            <div class="alert alert-success"><i class="bi bi-check-circle me-1"></i>{{ session('exito') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-1"></i>
                <ul class="mb-0 ps-4 d-inline-block align-top">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="content-card">
            @yield('contenido')
        </div>
    </div>
</body>
</html>
