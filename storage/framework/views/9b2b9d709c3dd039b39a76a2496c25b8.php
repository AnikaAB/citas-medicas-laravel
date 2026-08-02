
<?php $__env->startSection('titulo', 'Recuperar contraseña'); ?>
<?php $__env->startSection('contenido'); ?>
    <h3>¿Olvidaste tu contraseña?</h3>
    <p class="subtitle">Escribe tu correo y te daremos un codigo de verificacion para restablecerla.</p>

    <form method="POST" action="<?php echo e(route('password.enviar')); ?>">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label class="form-label">Correo electronico</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $email)); ?>" required autofocus placeholder="tucorreo@ejemplo.com">
            </div>
        </div>
        <button class="btn btn-gradient w-100 mb-3">
            <i class="bi bi-send me-1"></i> Enviar codigo
        </button>
        <p class="text-center switch-link mb-0">
            <a href="<?php echo e(route('login')); ?>"><i class="bi bi-arrow-left"></i> Volver a iniciar sesion</a>
        </p>
    </form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Downloads\citas-medicas-laravel-MERGED\final\resources\views/auth/olvide-password.blade.php ENDPATH**/ ?>