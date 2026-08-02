<?php $__env->startSection('titulo', 'Panel principal'); ?>
<?php $__env->startSection('contenido'); ?>

<?php $usuario = auth()->user(); ?>

<h3 class="mb-1"><i class="bi bi-speedometer2 me-2"></i>Bienvenido, <?php echo e($usuario->name); ?></h3>
<p class="text-muted mb-4">Este es el resumen de tu actividad en el sistema.</p>

<?php if(in_array($usuario->rol, ['doctor','paciente'])): ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="bi bi-calendar2-week me-2"></i>Tus próximas citas</h5>
        <?php if($usuario->esPaciente()): ?>
            <a href="<?php echo e(route('mis-citas.index')); ?>" class="btn btn-sm btn-primary">
                <i class="bi bi-calendar2-heart"></i> Gestionar mis citas
            </a>
        <?php endif; ?>
    </div>
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th><?php echo e($usuario->esDoctor() ? 'Paciente' : 'Doctor'); ?></th>
                <th>Motivo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $citas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cita): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($cita->fecha->format('d/m/Y')); ?></td>
                <td><?php echo e(\Illuminate\Support\Carbon::parse($cita->hora)->format('H:i')); ?></td>
                <td><?php echo e($usuario->esDoctor() ? $cita->paciente->nombre.' '.$cita->paciente->apellido : 'Dr. '.$cita->doctor->nombre.' '.$cita->doctor->apellido); ?></td>
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
            <tr><td colspan="5" class="text-center text-muted py-4"><i class="bi bi-calendar-x me-1"></i>No tienes citas registradas.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="row g-3">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #4f7cff, #7b3fe4);">
                <div class="stat-icon"><i class="bi bi-clipboard2-pulse"></i></div>
                <h2><?php echo e($totalDoctores); ?></h2>
                <p>Doctores</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #14b8a6, #0d9488);">
                <div class="stat-icon"><i class="bi bi-people"></i></div>
                <h2><?php echo e($totalPacientes); ?></h2>
                <p>Pacientes</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <div class="stat-icon"><i class="bi bi-calendar2-week"></i></div>
                <h2><?php echo e($totalCitas); ?></h2>
                <p>Citas totales</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #ec4899, #db2777);">
                <div class="stat-icon"><i class="bi bi-calendar2-check"></i></div>
                <h2><?php echo e($citasHoy); ?></h2>
                <p>Citas hoy</p>
            </div>
        </div>
    </div>
    <div class="mt-4 p-3" style="background: #FEF3C7; border-radius: 12px; color: #92400E;">
        <i class="bi bi-hourglass-split me-2"></i>
        Citas pendientes por confirmar: <strong><?php echo e($citasPendientes); ?></strong>
    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Downloads\citas-medicas-laravel-actualizado\citas-medicas-laravel\resources\views/dashboard.blade.php ENDPATH**/ ?>