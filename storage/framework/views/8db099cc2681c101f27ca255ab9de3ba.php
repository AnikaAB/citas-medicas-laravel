
<?php $__env->startSection('titulo', 'Citas'); ?>
<?php $__env->startSection('contenido'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="bi bi-calendar2-week me-2"></i>Gestión de Citas</h3>
    <?php if(auth()->user()->esAdmin() || auth()->user()->esRecepcionista()): ?>
        <a href="<?php echo e(route('citas.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nueva cita</a>
    <?php endif; ?>
</div>

<form class="row g-2 mb-3" method="GET">
    <div class="col-auto">
        <select name="estado" class="form-select">
            <option value="">Todos los estados</option>
            <?php $__currentLoopData = ['pendiente','confirmada','cancelada','atendida']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($estado); ?>" <?php if(request('estado')==$estado): echo 'selected'; endif; ?>><?php echo e(ucfirst($estado)); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="col-auto">
        <input type="date" name="fecha" class="form-control" value="<?php echo e(request('fecha')); ?>">
    </div>
    <div class="col-auto">
        <button class="btn btn-outline-secondary">Filtrar</button>
    </div>
</form>

<table class="table table-hover align-middle">
    <thead>
        <tr>
            <th>#</th><th>Paciente</th><th>Doctor</th><th>Fecha</th><th>Hora</th><th>Estado</th><th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $citas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cita): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><?php echo e($cita->id); ?></td>
            <td><?php echo e($cita->paciente->nombre); ?> <?php echo e($cita->paciente->apellido); ?></td>
            <td>Dr. <?php echo e($cita->doctor->nombre); ?> <?php echo e($cita->doctor->apellido); ?></td>
            <td><?php echo e($cita->fecha->format('d/m/Y')); ?></td>
            <td><?php echo e(\Illuminate\Support\Carbon::parse($cita->hora)->format('H:i')); ?></td>
            <td><?php if (isset($component)) { $__componentOriginalaaf77d88a4a6caa043dab2e51a7084de = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaaf77d88a4a6caa043dab2e51a7084de = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.estado-badge','data' => ['estado' => $cita->estado]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('estado-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['estado' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cita->estado)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaaf77d88a4a6caa043dab2e51a7084de)): ?>
<?php $attributes = $__attributesOriginalaaf77d88a4a6caa043dab2e51a7084de; ?>
<?php unset($__attributesOriginalaaf77d88a4a6caa043dab2e51a7084de); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaaf77d88a4a6caa043dab2e51a7084de)): ?>
<?php $component = $__componentOriginalaaf77d88a4a6caa043dab2e51a7084de; ?>
<?php unset($__componentOriginalaaf77d88a4a6caa043dab2e51a7084de); ?>
<?php endif; ?></td>
            <td>
                <?php if(auth()->user()->esAdmin() || auth()->user()->esRecepcionista()): ?>
                    <a href="<?php echo e(route('citas.edit', $cita)); ?>" class="btn btn-sm btn-warning">Editar</a>
                    <form action="<?php echo e(route('citas.destroy', $cita)); ?>" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta cita?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo e(route('citas.show', $cita)); ?>" class="btn btn-sm btn-outline-secondary">Ver</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="7" class="text-center">No hay citas registradas.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<?php echo e($citas->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Desktop\Proyecto de Ingieneria de Software H2\citas-medicas-laravel\resources\views/citas/index.blade.php ENDPATH**/ ?>