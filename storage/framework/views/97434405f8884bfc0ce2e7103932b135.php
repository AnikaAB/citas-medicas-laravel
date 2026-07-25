<?php $__env->startSection('titulo', 'Detalle paciente'); ?>
<?php $__env->startSection('contenido'); ?>
<h3><?php echo e($paciente->nombre); ?> <?php echo e($paciente->apellido); ?></h3>
<table class="table table-hover align-middle">
    <tr><th>Cedula</th><td><?php echo e($paciente->cedula); ?></td></tr>
    <tr><th>Telefono</th><td><?php echo e($paciente->telefono); ?></td></tr>
    <tr><th>Email</th><td><?php echo e($paciente->email); ?></td></tr>
    <tr><th>Fecha nacimiento</th><td><?php echo e($paciente->fecha_nacimiento->format('d/m/Y')); ?></td></tr>
    <tr><th>Direccion</th><td><?php echo e($paciente->direccion); ?></td></tr>
</table>
<h5>Historial de citas</h5>
<table class="table table-hover align-middle">
    <thead><tr><th>Fecha</th><th>Doctor</th><th>Estado</th></tr></thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $paciente->citas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cita): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr><td><?php echo e($cita->fecha->format('d/m/Y')); ?></td><td>Dr. <?php echo e($cita->doctor->nombre); ?></td><td><?php echo e($cita->estado); ?></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="3" class="text-center">Sin citas registradas.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<a href="<?php echo e(route('pacientes.index')); ?>" class="btn btn-secondary">Volver</a>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Downloads\citas-medicas-laravel-actualizado\citas-medicas-laravel\resources\views/pacientes/show.blade.php ENDPATH**/ ?>