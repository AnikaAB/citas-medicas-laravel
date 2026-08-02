
<?php $__env->startSection('titulo', 'Verificar codigo'); ?>
<?php $__env->startSection('contenido'); ?>
    <h3>Verifica tu codigo</h3>
    <p class="subtitle">Escribe el codigo de 6 digitos que generamos para tu correo.</p>

    <?php if(session('codigo_demo')): ?>
        <div class="alert alert-info">
            <strong>Modo demostracion:</strong> como el sistema aun no tiene correo configurado,
            este es tu codigo de verificacion: <strong><?php echo e(session('codigo_demo')); ?></strong>
            <br>(en un ambiente real, este codigo llegaria a tu correo).
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('password.verificar')); ?>">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="email" value="<?php echo e(old('email', $email)); ?>">
        <div class="mb-3">
            <label class="form-label">Correo electronico</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" class="form-control" value="<?php echo e(old('email', $email)); ?>" disabled>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Codigo de verificacion</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                <input type="text" name="codigo" class="form-control" maxlength="6" inputmode="numeric" required autofocus placeholder="123456">
            </div>
        </div>
        <button class="btn btn-gradient w-100 mb-3">
            <i class="bi bi-check2-circle me-1"></i> Verificar codigo
        </button>
        <p class="text-center switch-link mb-0">
            <a href="<?php echo e(route('password.olvide')); ?>">¿No te llego? Solicitar otro codigo</a>
        </p>
    </form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Downloads\citas-medicas-laravel-MERGED\final\resources\views/auth/codigo-password.blade.php ENDPATH**/ ?>