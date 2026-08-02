
<?php $__env->startSection('titulo', 'Doctores'); ?>
<?php $__env->startSection('contenido'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="bi bi-clipboard2-pulse me-2"></i>Doctores</h3>
    <a href="<?php echo e(route('doctores.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo doctor</a>
</div>
<table class="table table-hover align-middle">
    <thead><tr><th>#</th><th>Nombre</th><th>Especialidad</th><th>Telefono</th><th>Email</th><th>Horario</th><th>Acciones</th></tr></thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $doctores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><?php echo e($doctor->id); ?></td>
            <td>Dr. <?php echo e($doctor->nombre); ?> <?php echo e($doctor->apellido); ?></td>
            <td><?php echo e($doctor->especialidad); ?></td>
            <td><?php echo e($doctor->telefono); ?></td>
            <td><?php echo e($doctor->email); ?></td>
            <td><?php echo e(\Illuminate\Support\Carbon::parse($doctor->horario_inicio)->format('H:i')); ?> - <?php echo e(\Illuminate\Support\Carbon::parse($doctor->horario_fin)->format('H:i')); ?></td>
            <td>
                <a href="<?php echo e(route('doctores.show', $doctor)); ?>" class="btn btn-sm btn-info">Ver</a>
                <a href="<?php echo e(route('doctores.edit', $doctor)); ?>" class="btn btn-sm btn-warning">Editar</a>
                <form action="<?php echo e(route('doctores.destroy', $doctor)); ?>" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar doctor?')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="7" class="text-center">No hay doctores registrados.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<?php echo e($doctores->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Downloads\citas-medicas-laravel-MERGED\final\resources\views/doctores/index.blade.php ENDPATH**/ ?>