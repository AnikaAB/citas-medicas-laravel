<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('titulo', 'Error'); ?> · Sistema de Citas Medicas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,800" rel="stylesheet" />
    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            min-height: 100vh;
            margin: 0;
            display: flex; align-items: center; justify-content: center;
            background: radial-gradient(1200px 800px at 10% 10%, #1b2140 0%, #0d1024 45%, #08091a 100%);
        }
        .error-card {
            background: #fff;
            border-radius: 22px;
            padding: 48px 40px;
            max-width: 460px;
            text-align: center;
            box-shadow: 0 30px 80px rgba(0,0,0,0.4);
        }
        .error-code {
            font-weight: 800;
            font-size: 4rem;
            background: linear-gradient(90deg, #4f7cff, #7b3fe4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
        }
        .error-icon {
            width: 60px; height: 60px;
            border-radius: 16px;
            background: linear-gradient(135deg, #7b3fe4, #4f7cff);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem;
            margin: 0 auto 18px;
        }
        .btn-volver {
            background: linear-gradient(90deg, #4f7cff, #7b3fe4);
            color: #fff; border: none; border-radius: 12px;
            padding: 0.7rem 1.4rem; font-weight: 600; text-decoration: none;
            display: inline-block; margin-top: 6px;
        }
        .btn-volver:hover { color: #fff; opacity: 0.92; }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-icon"><i class="bi bi-exclamation-lg"></i></div>
        <div class="error-code"><?php echo $__env->yieldContent('codigo'); ?></div>
        <h1 class="h5 mt-2 mb-3" style="font-weight:700;"><?php echo $__env->yieldContent('titulo'); ?></h1>
        <p class="text-muted mb-4"><?php echo $__env->yieldContent('mensaje'); ?></p>
        <a href="<?php echo e(url('/')); ?>" class="btn-volver"><i class="bi bi-house-door me-1"></i>Volver al inicio</a>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Usuario\Downloads\citas-medicas-laravel-actualizado\citas-medicas-laravel\resources\views/errors/layout.blade.php ENDPATH**/ ?>