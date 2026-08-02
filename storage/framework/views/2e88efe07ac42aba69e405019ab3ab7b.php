
<?php $__env->startSection('titulo', 'Nuevo doctor'); ?>
<?php $__env->startSection('contenido'); ?>
<h3>Registrar doctor</h3>
<form method="POST" action="<?php echo e(route('doctores.store')); ?>">
    <?php echo csrf_field(); ?>
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Nombre</label><input name="nombre" class="form-control" required value="<?php echo e(old('nombre')); ?>"></div>
        <div class="col-md-6 mb-3"><label class="form-label">Apellido</label><input name="apellido" class="form-control" required value="<?php echo e(old('apellido')); ?>"></div>
    </div>
    <div class="mb-3"><label class="form-label">Especialidad</label><input name="especialidad" class="form-control" required value="<?php echo e(old('especialidad')); ?>"></div>
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Telefono</label><input name="telefono" class="form-control" required value="<?php echo e(old('telefono')); ?>"></div>
        <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required value="<?php echo e(old('email')); ?>"></div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Horario inicio</label><input type="time" name="horario_inicio" class="form-control" required value="08:00"></div>
        <div class="col-md-6 mb-3"><label class="form-label">Horario fin</label><input type="time" name="horario_fin" class="form-control" required value="17:00"></div>
    </div>
    <div class="mb-3"><label class="form-label">Contraseña (para el login del doctor)</label><input type="password" name="password" class="form-control" required minlength="8"></div>
    <button class="btn btn-primary">Guardar</button>
    <a href="<?php echo e(route('doctores.index')); ?>" class="btn btn-secondary">Cancelar</a>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Desktop\Proyecto de Ingieneria de Software H2\citas-medicas-laravel\resources\views/doctores/create.blade.php ENDPATH**/ ?>