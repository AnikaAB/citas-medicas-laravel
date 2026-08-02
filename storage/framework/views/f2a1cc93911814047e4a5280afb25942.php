<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('titulo', 'Sistema de Citas Medicas'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    <style>
        :root {
            --brand-1: #4f7cff;
            --brand-2: #7b3fe4;
            --brand-3: #14b8a6;
            --neon-cyan: #22d3ee;
            --neon-pink: #f472b6;
            --neon-purple: #a78bfa;
            --ink: #101423;
        }
        * { font-family: 'Inter', sans-serif; }
        body {
            min-height: 100vh;
            margin: 0;
            background: radial-gradient(1200px 800px at 10% 10%, #1b2140 0%, #0d1024 45%, #08091a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .auth-shell {
            width: 100%;
            max-width: 980px;
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0,0,0,0.55), 0 0 0 1px rgba(124,143,255,0.2);
            background: rgba(255,255,255,0.02);
        }
        @media (max-width: 860px) {
            .auth-shell { grid-template-columns: 1fr; }
            .auth-side { display: none; }
        }
        .auth-side {
            position: relative;
            background:
                radial-gradient(600px 400px at 100% 0%, rgba(34,211,238,0.35), transparent 60%),
                linear-gradient(135deg, var(--brand-2), var(--brand-1));
            color: #fff;
            padding: 48px 42px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }
        .auth-side::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.14) 1px, transparent 1px);
            background-size: 26px 26px;
            opacity: 0.5;
        }
        .auth-side .brand-icon {
            width: 54px; height: 54px;
            border-radius: 16px;
            background: rgba(255,255,255,0.16);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            backdrop-filter: blur(6px);
            box-shadow: 0 0 24px rgba(34,211,238,0.4);
        }
        .auth-side h2 {
            font-weight: 800; letter-spacing: -0.02px; margin-top: 22px;
            text-shadow: 0 0 30px rgba(34,211,238,0.35);
        }
        .auth-side p { opacity: 0.92; }
        .feature-pill {
            display: flex; align-items: center; gap: 10px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 14px;
            padding: 10px 14px;
            margin-bottom: 10px;
            font-size: 0.92rem;
            backdrop-filter: blur(6px);
            transition: background 0.15s ease, border-color 0.15s ease;
        }
        .feature-pill:hover {
            background: rgba(34,211,238,0.18);
            border-color: rgba(34,211,238,0.5);
        }
        .auth-form-wrap {
            background: linear-gradient(180deg, #131829, #0f1326);
            padding: 48px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }
        .auth-form-wrap::before {
            content: "";
            position: absolute;
            top: -80px; right: -80px;
            width: 240px; height: 240px;
            background: radial-gradient(circle, rgba(34,211,238,0.18), transparent 70%);
            pointer-events: none;
        }
        .auth-form-wrap h3 { font-weight: 800; color: #fff; }
        .auth-form-wrap .subtitle { color: rgba(230,233,245,0.55); margin-bottom: 28px; }
        .auth-form-wrap label { color: rgba(230,233,245,0.8); font-weight: 600; font-size: 0.9rem; }
        .auth-form-wrap .form-check-label { color: rgba(230,233,245,0.75); }
        .form-control {
            padding: 0.7rem 0.9rem;
            border-radius: 12px;
            border: 1.5px solid rgba(255,255,255,0.12);
            background: rgba(255,255,255,0.04);
            color: #e6e9f5;
        }
        .form-control::placeholder { color: rgba(230,233,245,0.35); }
        .form-control:focus {
            background: rgba(255,255,255,0.06);
            color: #e6e9f5;
            border-color: var(--neon-cyan);
            box-shadow: 0 0 0 4px rgba(34,211,238,0.16);
        }
        .input-group-text {
            border-radius: 12px 0 0 12px;
            border: 1.5px solid rgba(255,255,255,0.12);
            border-right: none;
            background: rgba(255,255,255,0.04);
            color: rgba(230,233,245,0.6);
        }
        .input-group .form-control { border-radius: 0 12px 12px 0; }
        .btn-gradient {
            background: linear-gradient(90deg, var(--neon-cyan), var(--brand-2));
            color: #0c1020;
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-weight: 700;
            letter-spacing: 0.2px;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            box-shadow: 0 6px 20px rgba(34,211,238,0.3);
        }
        .btn-gradient:hover {
            color: #0c1020;
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(34,211,238,0.45);
        }
        .switch-link { color: rgba(230,233,245,0.6); }
        .switch-link a { color: var(--neon-cyan); font-weight: 600; text-decoration: none; }
        .switch-link a:hover { text-decoration: underline; }
        #linkOlvidoPassword { color: var(--neon-cyan); text-decoration: none; }
        #linkOlvidoPassword:hover { text-decoration: underline; }
        .form-check-input { background-color: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.25); }
        .form-check-input:checked { background-color: var(--neon-cyan); border-color: var(--neon-cyan); }
        .auth-form-wrap .text-muted { color: rgba(230,233,245,0.45) !important; }
        .alert { border-radius: 12px; border: 1px solid transparent; }
        .alert-success { background: rgba(20,184,166,0.12); color: #5eead4; border-color: rgba(20,184,166,0.35); }
        .alert-danger { background: rgba(244,63,94,0.12); color: #fca5a5; border-color: rgba(244,63,94,0.35); }
    </style>
</head>
<body>
    <div class="auth-shell">
        <div class="auth-side">
            <div>
                <div class="brand-icon"><i class="bi bi-heart-pulse-fill"></i></div>
                <h2>Sistema de Gestión<br>de Citas Médicas</h2>
                <p>Administra pacientes, doctores y citas desde una sola plataforma, rápida y segura.</p>
            </div>
            <div>
                <div class="feature-pill"><i class="bi bi-shield-check"></i> Datos clínicos protegidos</div>
                <div class="feature-pill"><i class="bi bi-calendar2-check"></i> Agenda de citas en tiempo real</div>
                <div class="feature-pill"><i class="bi bi-people"></i> Roles para admin, recepción y doctores</div>
            </div>
        </div>
        <div class="auth-form-wrap">
            <?php if(session('exito')): ?>
                <div class="alert alert-success"><?php echo e(session('exito')); ?></div>
            <?php endif; ?>
            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php echo $__env->yieldContent('contenido'); ?>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\EDUARDO VIDAL\Desktop\citas-medicas-laravel\resources\views/layouts/auth.blade.php ENDPATH**/ ?>