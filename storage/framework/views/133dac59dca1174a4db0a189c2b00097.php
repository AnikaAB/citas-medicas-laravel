<?php $__env->startSection('titulo', 'Detalle de cita'); ?>
<?php $__env->startSection('contenido'); ?>
<h3><i class="bi bi-calendar2-check me-2"></i>Cita #<?php echo e($cita->id); ?></h3>
<table class="table table-borderless">
    <tr><th style="width: 220px;">Paciente</th><td><?php echo e($cita->paciente->nombre); ?> <?php echo e($cita->paciente->apellido); ?></td></tr>
    <tr><th>Doctor</th><td>Dr. <?php echo e($cita->doctor->nombre); ?> <?php echo e($cita->doctor->apellido); ?></td></tr>
    <tr><th>Fecha</th><td><?php echo e($cita->fecha->format('d/m/Y')); ?></td></tr>
    <tr><th>Hora</th><td><?php echo e(\Illuminate\Support\Carbon::parse($cita->hora)->format('H:i')); ?></td></tr>
    <tr><th>Motivo</th><td><?php echo e($cita->motivo); ?></td></tr>
    <tr><th>Estado</th><td><?php if (isset($component)) { $__componentOriginalaaf77d88a4a6caa043dab2e51a7084de = $component; } ?>
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
<?php endif; ?></td></tr>
    <tr><th>Observaciones</th><td><?php echo e($cita->observaciones ?? '—'); ?></td></tr>
    <tr><th>Registrada por</th><td><?php echo e($cita->creadoPor->name ?? '—'); ?></td></tr>
</table>
<a href="<?php echo e(route('citas.index')); ?>" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Volver</a>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Downloads\citas-medicas-laravel-actualizado\citas-medicas-laravel\resources\views/citas/show.blade.php ENDPATH**/ ?>