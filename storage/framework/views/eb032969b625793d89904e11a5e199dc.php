
<?php $__env->startSection('titulo', 'Detalle doctor'); ?>
<?php $__env->startSection('contenido'); ?>
<h3>Dr. <?php echo e($doctor->nombre); ?> <?php echo e($doctor->apellido); ?></h3>
<table class="table table-hover align-middle">
    <tr><th>Especialidad</th><td><?php echo e($doctor->especialidad); ?></td></tr>
    <tr><th>Telefono</th><td><?php echo e($doctor->telefono); ?></td></tr>
    <tr><th>Email</th><td><?php echo e($doctor->email); ?></td></tr>
    <tr><th>Horario</th><td><?php echo e(\Illuminate\Support\Carbon::parse($doctor->horario_inicio)->format('H:i')); ?> - <?php echo e(\Illuminate\Support\Carbon::parse($doctor->horario_fin)->format('H:i')); ?></td></tr>
</table>
<h5>Citas asignadas</h5>
<table class="table table-hover align-middle">
    <thead><tr><th>Fecha</th><th>Paciente</th><th>Estado</th></tr></thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $doctor->citas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cita): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr><td><?php echo e($cita->fecha->format('d/m/Y')); ?></td><td><?php echo e($cita->paciente->nombre); ?></td><td><?php echo e($cita->estado); ?></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="3" class="text-center">Sin citas asignadas.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<a href="<?php echo e(route('doctores.index')); ?>" class="btn btn-secondary">Volver</a>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\EDUARDO VIDAL\Desktop\citas-medicas-laravel\resources\views/doctores/show.blade.php ENDPATH**/ ?>