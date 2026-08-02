
<?php $__env->startSection('titulo', 'Pacientes'); ?>
<?php $__env->startSection('contenido'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="bi bi-people me-2"></i>Pacientes</h3>
    <a href="<?php echo e(route('pacientes.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo paciente</a>
</div>
<form class="mb-3" method="GET">
    <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre o cedula" value="<?php echo e(request('buscar')); ?>">
</form>
<table class="table table-hover align-middle">
    <thead><tr><th>#</th><th>Nombre</th><th>Cedula</th><th>Telefono</th><th>Email</th><th>Acciones</th></tr></thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $pacientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paciente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><?php echo e($paciente->id); ?></td>
            <td><?php echo e($paciente->nombre); ?> <?php echo e($paciente->apellido); ?></td>
            <td><?php echo e($paciente->cedula); ?></td>
            <td><?php echo e($paciente->telefono); ?></td>
            <td><?php echo e($paciente->email); ?></td>
            <td>
                <a href="<?php echo e(route('pacientes.show', $paciente)); ?>" class="btn btn-sm btn-info">Ver</a>
                <a href="<?php echo e(route('pacientes.edit', $paciente)); ?>" class="btn btn-sm btn-warning">Editar</a>
                <form action="<?php echo e(route('pacientes.destroy', $paciente)); ?>" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar paciente?')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="6" class="text-center">No hay pacientes registrados.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<?php echo e($pacientes->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Downloads\citas-medicas-laravel-MERGED\final\resources\views/pacientes/index.blade.php ENDPATH**/ ?>