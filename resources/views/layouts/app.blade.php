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
            --neon-cyan: #0891b2;
            --neon-pink: #ec4899;
            --neon-purple: #7c3aed;
            --ink: #1e2433;
            --bg-app: #f4f6fb;
            --panel: #ffffff;
            --panel-2: #eef1f8;
            --border-glow: rgba(79, 124, 255, 0.18);
        }
        * { font-family: 'Inter', sans-serif; }
        body {
            background:
                radial-gradient(900px 500px at 85% -10%, rgba(167,139,250,0.07), transparent 55%),
                radial-gradient(700px 500px at -5% 20%, rgba(34,211,238,0.06), transparent 55%),
                var(--panel-2);
            min-height: 100vh;
            color: var(--ink);
        }

        /* ---- Navbar ---- */
        .navbar-brand-custom {
            background: #ffffff;
            border-bottom: 1px solid var(--border-glow);
            box-shadow: 0 1px 24px rgba(79,124,255,0.07), 0 6px 20px rgba(15,23,42,0.06);
            backdrop-filter: blur(10px);
        }
        .navbar-brand-custom .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.2px;
            display: flex; align-items: center; gap: 10px;
            color: #0f172a;
        }
        .navbar-brand-custom .brand-icon {
            width: 34px; height: 34px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--neon-cyan), var(--neon-purple));
            box-shadow: 0 0 16px rgba(34,211,238,0.35);
            display: flex; align-items: center; justify-content: center;
            color: #ffffff;
        }
        .navbar-brand-custom .nav-link {
            font-weight: 500;
            color: rgba(30,41,59,0.7) !important;
            border-radius: 999px;
            padding: 0.5rem 1.1rem !important;
            transition: all 0.15s ease;
            border: 1px solid transparent;
        }
        .navbar-brand-custom .nav-link i { margin-right: 6px; }
        .navbar-brand-custom .nav-link:hover,
        .navbar-brand-custom .nav-link.active {
            background: rgba(34,211,238,0.1);
            border: 1px solid rgba(34,211,238,0.3);
            color: #0f172a !important;
            box-shadow: 0 0 12px rgba(34,211,238,0.15) inset;
        }
        .user-chip {
            background: rgba(15,23,42,0.04);
            border: 1px solid var(--border-glow);
            border-radius: 999px;
            padding: 6px 16px 6px 6px;
            color: #0f172a;
            font-size: 0.85rem;
            display: flex; align-items: center; gap: 8px;
        }
        .user-chip .rol-tag {
            background: rgba(59,130,246,0.14);
            color: #2563eb;
            border-radius: 999px;
            padding: 2px 10px;
            font-size: 0.7rem;
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
            background: #ffffff;
            border-radius: 18px;
            padding: 28px 30px;
            box-shadow: 0 20px 50px rgba(15,23,42,0.08), 0 0 0 1px var(--border-glow);
            border: 1px solid var(--border-glow);
            position: relative;
            overflow: hidden;
        }
        .content-card::before {
            content: "";
            position: absolute;
            top: -60px; right: -60px;
            width: 220px; height: 220px;
            background: radial-gradient(circle, rgba(34,211,238,0.08), transparent 70%);
            pointer-events: none;
        }
        h3 { font-weight: 800; color: #0f172a; letter-spacing: -0.3px; }
        h5 { color: #0f172a; }
        .text-muted { color: rgba(30,41,59,0.55) !important; }

        /* ---- Botones ---- */
        .btn-primary {
            background: linear-gradient(90deg, var(--neon-cyan), var(--brand-2));
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 0.55rem 1.1rem;
            box-shadow: 0 6px 16px rgba(34,211,238,0.25);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 26px rgba(34,211,238,0.35); }
        .btn-outline-secondary, .btn-secondary {
            border-radius: 10px; font-weight: 500;
            background: rgba(15,23,42,0.03);
            border-color: rgba(15,23,42,0.15);
            color: var(--ink);
        }
        .btn-outline-light {
            border-radius: 8px;
            color: rgba(30,41,59,0.7);
            border-color: rgba(15,23,42,0.18);
            background: transparent;
        }
        .btn-outline-light:hover {
            background: rgba(15,23,42,0.06);
            color: #0f172a;
            border-color: rgba(15,23,42,0.3);
        }
        .btn-sm { border-radius: 8px; }
        .btn-warning { border-radius: 8px; font-weight: 600; box-shadow: 0 4px 14px rgba(245,158,11,0.25); }
        .btn-danger { border-radius: 8px; font-weight: 600; box-shadow: 0 4px 14px rgba(239,68,68,0.25); }
        .btn-info { border-radius: 8px; font-weight: 600; color: #ffffff; box-shadow: 0 4px 14px rgba(34,211,238,0.25); }

        /* ---- Formularios ---- */
        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid rgba(15,23,42,0.15);
            padding: 0.6rem 0.85rem;
            background: #ffffff;
            color: var(--ink);
        }
        .form-control::placeholder { color: rgba(30,41,59,0.35); }
        .form-control:focus, .form-select:focus {
            background: #ffffff;
            color: var(--ink);
            border-color: var(--neon-cyan);
            box-shadow: 0 0 0 4px rgba(34,211,238,0.12);
        }
        .form-select option { background: #ffffff; color: var(--ink); }
        .form-label { font-weight: 600; color: rgba(30,41,59,0.8); font-size: 0.9rem; }

        /* ---- Tablas ---- */
        .table {
            background: transparent;
            border-radius: 14px;
            overflow: hidden;
            --bs-table-color: var(--ink);
            --bs-table-bg: transparent;
            --bs-table-striped-color: var(--ink);
            --bs-table-hover-color: var(--ink);
            --bs-emphasis-color: var(--ink);
        }
        .table thead th {
            background: rgba(34,211,238,0.06);
            color: var(--neon-cyan) !important;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border-bottom: 2px solid rgba(34,211,238,0.2);
            font-weight: 700;
        }
        .table td, .table th, .table tbody tr {
            vertical-align: middle;
            color: var(--ink) !important;
            border-color: rgba(15,23,42,0.08);
        }
        .table-hover tbody tr:hover { background: rgba(34,211,238,0.05); --bs-table-bg-state: rgba(34,211,238,0.05); }

        /* ---- Paginación ---- */
        .pagination { --bs-pagination-bg: transparent; }
        .page-link {
            background: #ffffff;
            border: 1px solid rgba(15,23,42,0.12);
            color: var(--ink);
        }
        .page-link:hover {
            background: rgba(34,211,238,0.1);
            border-color: rgba(34,211,238,0.35);
            color: #0f172a;
        }
        .page-item.active .page-link {
            background: linear-gradient(90deg, var(--neon-cyan), var(--brand-2));
            border-color: transparent;
            color: #ffffff;
            font-weight: 700;
        }
        .page-item.disabled .page-link {
            background: rgba(15,23,42,0.02);
            border-color: rgba(15,23,42,0.06);
            color: rgba(30,41,59,0.3);
        }

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
        .alert { border-radius: 12px; border: 1px solid transparent; }
        .alert-success { background: rgba(20,184,166,0.1); color: #0f766e; border-color: rgba(20,184,166,0.3); }
        .alert-danger { background: rgba(244,63,94,0.1); color: #be123c; border-color: rgba(244,63,94,0.3); }

        /* ---- Cards del dashboard ---- */
        .stat-card {
            border-radius: 16px;
            padding: 20px;
            color: #fff;
            box-shadow: 0 12px 30px rgba(15,23,42,0.15);
            height: 100%;
            position: relative;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.14), transparent 60%);
        }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 18px 40px rgba(15,23,42,0.22); }
        .stat-card .stat-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            background: rgba(255,255,255,0.22);
            backdrop-filter: blur(4px);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 10px;
            position: relative;
        }
        .stat-card h2 { font-weight: 800; margin: 0; position: relative; }
        .stat-card p { margin: 0; opacity: 0.9; font-size: 0.85rem; position: relative; }
    </style>
</head>
<body>
    @auth
    <nav class="navbar navbar-expand-lg navbar-light navbar-brand-custom">
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
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" href="{{ route('usuarios.index') }}">
                                <i class="bi bi-people-fill"></i>Usuarios
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
