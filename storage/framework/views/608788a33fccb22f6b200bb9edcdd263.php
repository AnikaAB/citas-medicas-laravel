
<?php $__env->startSection('titulo', 'Editar doctor'); ?>
<?php $__env->startSection('contenido'); ?>
<h3>Editar doctor</h3>
<form method="POST" action="<?php echo e(route('doctores.update', $doctor)); ?>">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Nombre</label><input name="nombre" class="form-control" required value="<?php echo e($doctor->nombre); ?>"></div>
        <div class="col-md-6 mb-3"><label class="form-label">Apellido</label><input name="apellido" class="form-control" required value="<?php echo e($doctor->apellido); ?>"></div>
    </div>
    <div class="mb-3"><label class="form-label">Especialidad</label><input name="especialidad" class="form-control" required value="<?php echo e($doctor->especialidad); ?>"></div>
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Telefono</label><input name="telefono" class="form-control" required pattern="[0-9]{7,10}" title="Solo numeros, entre 7 y 10 digitos" value="<?php echo e($doctor->telefono); ?>"></div>
        <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required value="<?php echo e($doctor->email); ?>"></div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Horario inicio</label><input type="time" name="horario_inicio" class="form-control" required value="<?php echo e(\Illuminate\Support\Carbon::parse($doctor->horario_inicio)->format('H:i')); ?>"></div>
        <div class="col-md-6 mb-3"><label class="form-label">Horario fin</label><input type="time" name="horario_fin" class="form-control" required value="<?php echo e(\Illuminate\Support\Carbon::parse($doctor->horario_fin)->format('H:i')); ?>"></div>
    </div>
    <button class="btn btn-primary">Actualizar</button>
    <a href="<?php echo e(route('doctores.index')); ?>" class="btn btn-secondary">Cancelar</a>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Downloads\citas-medicas-laravel-MERGED\final\resources\views/doctores/edit.blade.php ENDPATH**/ ?>