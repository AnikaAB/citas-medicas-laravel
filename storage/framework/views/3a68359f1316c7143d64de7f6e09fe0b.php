<?php $__env->startSection('titulo', 'Mis citas'); ?>
<?php $__env->startSection('contenido'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0"><i class="bi bi-calendar2-heart me-2"></i>Mis citas</h3>
    <a href="<?php echo e(route('mis-citas.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Agendar nueva cita
    </a>
</div>

<h5 class="mb-3"><i class="bi bi-calendar2-week me-2"></i>Próximas citas</h5>
<div class="table-responsive mb-5">
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>Doctor</th>
                <th>Especialidad</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Motivo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $proximas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cita): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>Dr. <?php echo e($cita->doctor->nombre); ?> <?php echo e($cita->doctor->apellido); ?></td>
                    <td><?php echo e($cita->doctor->especialidad); ?></td>
                    <td><?php echo e($cita->fecha->format('d/m/Y')); ?></td>
                    <td><?php echo e(\Illuminate\Support\Carbon::parse($cita->hora)->format('H:i')); ?></td>
                    <td><?php echo e($cita->motivo); ?></td>
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
                        <?php if($cita->puedeModificarse()): ?>
                            <a href="<?php echo e(route('mis-citas.reprogramar', $cita)); ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-arrow-repeat"></i> Reprogramar
                            </a>
                            <form action="<?php echo e(route('mis-citas.cancelar', $cita)); ?>" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Seguro que quieres cancelar esta cita?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </button>
                            </form>
                        <?php else: ?>
                            <span class="text-muted small"><?php echo e($cita->motivoNoModificable()); ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" class="text-center text-muted py-4"><i class="bi bi-calendar-x me-1"></i>No tienes citas próximas.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<h5 class="mb-3"><i class="bi bi-clock-history me-2"></i>Historial</h5>
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>Doctor</th>
                <th>Especialidad</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Motivo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $historial; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cita): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>Dr. <?php echo e($cita->doctor->nombre); ?> <?php echo e($cita->doctor->apellido); ?></td>
                    <td><?php echo e($cita->doctor->especialidad); ?></td>
                    <td><?php echo e($cita->fecha->format('d/m/Y')); ?></td>
                    <td><?php echo e(\Illuminate\Support\Carbon::parse($cita->hora)->format('H:i')); ?></td>
                    <td><?php echo e($cita->motivo); ?></td>
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
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">Aun no tienes historial de citas.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Downloads\citas-medicas-laravel-actualizado\citas-medicas-laravel\resources\views/mis-citas/index.blade.php ENDPATH**/ ?>