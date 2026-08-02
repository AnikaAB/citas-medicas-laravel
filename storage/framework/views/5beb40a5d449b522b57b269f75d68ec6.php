<?php $__env->startSection('titulo', 'Iniciar sesion'); ?>
<?php $__env->startSection('contenido'); ?>
    <h3>Bienvenido de nuevo</h3>
    <p class="subtitle">Ingresa tus credenciales para acceder al sistema</p>

    <form method="POST" action="<?php echo e(route('login.attempt')); ?>">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label class="form-label">Correo electronico</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" required autofocus placeholder="tucorreo@ejemplo.com">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input type="checkbox" name="recordar" class="form-check-input" id="recordar">
                <label class="form-check-label" for="recordar">Recordarme</label>
            </div>
            <a href="<?php echo e(route('password.olvide')); ?>" class="small" id="linkOlvidoPassword">¿Olvidaste tu contraseña?</a>
        </div>
        <button class="btn btn-gradient w-100 mb-3">
            <i class="bi bi-box-arrow-in-right me-1"></i> Ingresar
        </button>
        <p class="text-center switch-link mb-0">
            ¿No tienes una cuenta? <a href="<?php echo e(route('register')); ?>">Regístrate aquí</a>
        </p>
    </form>

    <script>
        // Si el usuario ya escribio su correo en el login, se lo llevamos
        // precargado a la pantalla de "olvide mi contraseña" (mejor UX,
        // no tiene que volver a escribirlo).
        document.addEventListener('DOMContentLoaded', function () {
            const campoEmail = document.querySelector('input[name="email"]');
            const link = document.getElementById('linkOlvidoPassword');
            if (campoEmail && link) {
                campoEmail.addEventListener('input', function () {
                    const base = link.getAttribute('href').split('?')[0];
                    link.setAttribute('href', campoEmail.value ? base + '?email=' + encodeURIComponent(campoEmail.value) : base);
                });
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Downloads\citas-medicas-laravel-actualizado\citas-medicas-laravel\resources\views/auth/login.blade.php ENDPATH**/ ?>