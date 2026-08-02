
<?php $__env->startSection('titulo', 'Panel principal'); ?>
<?php $__env->startSection('contenido'); ?>

<?php $usuario = auth()->user(); ?>

<h3 class="mb-1"><i class="bi bi-speedometer2 me-2"></i>Bienvenido, <?php echo e($usuario->name); ?></h3>
<p class="text-muted mb-4">Este es el resumen de tu actividad en el sistema.</p>

<style>
    .stats-card {
        border-radius: 18px;
        padding: 22px;
        height: 100%;
        background: #ffffff;
        border: 1px solid rgba(15,23,42,0.08);
        box-shadow: 0 8px 24px rgba(15,23,42,0.06);
        position: relative;
        overflow: hidden;
    }
    .stats-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(400px 160px at 10% -10%, rgba(34,211,238,0.06), transparent 60%);
        pointer-events: none;
    }
    .stats-card h6 {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 700;
        color: rgba(30,41,59,0.55);
        margin-bottom: 22px;
    }

    /* --- Grafico de barras (citas por estado) --- */
    .bar-chart { display: flex; align-items: flex-end; justify-content: space-around; height: 190px; gap: 10px; }
    .bar-col { display: flex; flex-direction: column; align-items: center; flex: 1; }
    .bar-value { font-weight: 800; font-size: 1.05rem; color: var(--ink); margin-bottom: 8px; }
    .bar-track { width: 100%; max-width: 46px; height: 140px; display: flex; align-items: flex-end; }
    .bar-fill {
        width: 100%;
        border-radius: 8px 8px 3px 3px;
        position: relative;
        transform-origin: bottom;
        animation: growBar 0.7s cubic-bezier(.2,.8,.2,1) both;
        box-shadow: 0 0 16px -4px var(--bar-glow, transparent), inset 0 1px 0 rgba(255,255,255,0.35);
    }
    .bar-fill::after {
        content: '';
        position: absolute; inset: 0;
        border-radius: inherit;
        background: linear-gradient(180deg, rgba(255,255,255,0.28), transparent 55%);
    }
    @keyframes growBar { from { transform: scaleY(0); opacity: 0; } to { transform: scaleY(1); opacity: 1; } }
    .bar-label { display: flex; align-items: center; gap: 6px; margin-top: 12px; font-size: 0.8rem; color: rgba(30,41,59,0.7); font-weight: 600; }
    .bar-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--bar-glow, #94a3b8); box-shadow: 0 0 8px var(--bar-glow, transparent); }

    /* --- Ranking de especialidades (admin) --- */
    .spec-row { margin-bottom: 16px; }
    .spec-row:last-child { margin-bottom: 0; }
    .spec-row-top { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 6px; }
    .spec-rank {
        display: inline-flex; align-items: center; justify-content: center;
        width: 20px; height: 20px; border-radius: 6px; margin-right: 10px;
        font-size: 0.7rem; font-weight: 800; color: #ffffff;
        background: linear-gradient(135deg, var(--neon-cyan), var(--neon-purple));
    }
    .spec-name { font-weight: 600; color: var(--ink); font-size: 0.92rem; }
    .spec-count { font-weight: 800; color: var(--neon-cyan); font-size: 0.92rem; }
    .spec-bar-track { height: 8px; border-radius: 999px; background: rgba(15,23,42,0.06); overflow: hidden; }
    .spec-bar-fill {
        height: 100%; border-radius: 999px;
        background: linear-gradient(90deg, var(--neon-cyan), var(--brand-2));
        animation: growWidth 0.8s cubic-bezier(.2,.8,.2,1) both;
    }
    @keyframes growWidth { from { width: 0%; } }

    /* --- Proximas citas (doctor) --- */
    .upcoming-row { display: flex; align-items: center; gap: 14px; padding: 10px 0; border-bottom: 1px solid rgba(15,23,42,0.08); }
    .upcoming-row:last-child { border-bottom: none; padding-bottom: 0; }
    .upcoming-date {
        width: 46px; height: 46px; border-radius: 12px; flex-shrink: 0;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        background: linear-gradient(135deg, var(--neon-cyan), var(--brand-2)); color: #ffffff;
    }
    .upcoming-day { font-weight: 800; font-size: 1rem; line-height: 1; }
    .upcoming-month { font-size: 0.62rem; font-weight: 700; letter-spacing: 0.3px; }
    .upcoming-info { flex: 1; min-width: 0; }
    .upcoming-name { font-weight: 600; color: var(--ink); font-size: 0.92rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .upcoming-time { font-size: 0.78rem; color: rgba(30,41,59,0.55); }

    /* --- Tarjetas de estadisticas con ritmo cardiaco --- */
    .stat-card-v2 {
        border-radius: 16px;
        padding: 20px;
        height: 100%;
        background: var(--accent-soft);
        border: 1px solid var(--accent-soft-2);
        position: relative;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card-v2:hover { transform: translateY(-3px); box-shadow: 0 10px 24px var(--accent-soft-2); }
    .stat-card-v2-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 14px; }
    .stat-card-v2-icon {
        width: 44px; height: 44px; border-radius: 12px;
        background: var(--accent-soft-2);
        color: var(--accent);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .stat-card-v2-heartbeat {
        width: 90px; height: 30px;
        stroke: var(--accent);
        opacity: 0.45;
    }
    .stat-card-v2-heartbeat polyline {
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
    .stat-card-v2 h2 { font-weight: 800; margin: 0 0 2px 0; color: var(--accent); font-size: 1.9rem; }
    .stat-card-v2 p { margin: 0; color: rgba(30,41,59,0.6); font-size: 0.88rem; font-weight: 600; }
</style>

<?php if($usuario->esPaciente()): ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="bi bi-calendar2-week me-2"></i>Tus próximas citas</h5>
        <a href="<?php echo e(route('mis-citas.index')); ?>" class="btn btn-sm btn-primary">
            <i class="bi bi-calendar2-heart"></i> Gestionar mis citas
        </a>
    </div>
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Doctor</th>
                <th>Motivo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $citas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cita): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($cita->fecha->format('d/m/Y')); ?></td>
                <td><?php echo e(\Illuminate\Support\Carbon::parse($cita->hora)->format('H:i')); ?></td>
                <td>Dr. <?php echo e($cita->doctor->nombre); ?> <?php echo e($cita->doctor->apellido); ?></td>
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

<?php elseif($usuario->esDoctor()): ?>
    <h5 class="mb-3"><i class="bi bi-bar-chart-line me-2"></i>Resumen de tu agenda</h5>
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="stats-card">
                <h6><i class="bi bi-pie-chart-fill me-1"></i>Tus citas por estado</h6>
                <?php
                    $maxEstado = max($citasPorEstado->max(), 1);
                    $colores = [
                        'pendiente' => '#8b93a7',
                        'confirmada' => '#3b82f6',
                        'atendida' => '#22c55e',
                        'cancelada' => '#f43f5e',
                    ];
                ?>
                <div class="bar-chart">
                    <?php $__currentLoopData = $citasPorEstado; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estado => $cantidad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $color = $colores[$estado] ?? '#94a3b8'; ?>
                        <div class="bar-col">
                            <div class="bar-value"><?php echo e($cantidad); ?></div>
                            <div class="bar-track">
                                <div class="bar-fill" style="--bar-glow: <?php echo e($color); ?>; background: linear-gradient(180deg, <?php echo e($color); ?>, color-mix(in srgb, <?php echo e($color); ?> 55%, #0c1020)); height: <?php echo e(max((int) round(($cantidad / $maxEstado) * 100), 6)); ?>%; animation-delay: <?php echo e($loop->index * 0.08); ?>s;"></div>
                            </div>
                            <div class="bar-label"><span class="bar-dot" style="--bar-glow: <?php echo e($color); ?>;"></span><?php echo e(ucfirst($estado)); ?></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="stats-card">
                <h6><i class="bi bi-calendar2-week-fill me-1"></i>Próximas citas</h6>
                <?php $__empty_1 = true; $__currentLoopData = $proximasCitas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cita): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="upcoming-row">
                        <div class="upcoming-date">
                            <div class="upcoming-day"><?php echo e($cita->fecha->format('d')); ?></div>
                            <div class="upcoming-month"><?php echo e($cita->fecha->format('m')); ?>/<?php echo e($cita->fecha->format('y')); ?></div>
                        </div>
                        <div class="upcoming-info">
                            <div class="upcoming-name"><?php echo e($cita->paciente->nombre); ?> <?php echo e($cita->paciente->apellido); ?></div>
                            <div class="upcoming-time"><i class="bi bi-clock me-1"></i><?php echo e(\Illuminate\Support\Carbon::parse($cita->hora)->format('H:i')); ?> · <?php echo e($cita->motivo); ?></div>
                        </div>
                        <?php if (isset($component)) { $__componentOriginalaaf77d88a4a6caa043dab2e51a7084de = $component; } ?>
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
<?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted mb-0"><i class="bi bi-calendar-x me-1"></i>No tienes citas próximas.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php else: ?>
    <div class="row g-3">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card-v2" style="--accent:#3b82f6; --accent-soft: rgba(59,130,246,0.07); --accent-soft-2: rgba(59,130,246,0.16);">
                <div class="stat-card-v2-top">
                    <div class="stat-card-v2-icon"><i class="bi bi-clipboard2-pulse"></i></div>
                    <svg class="stat-card-v2-heartbeat" viewBox="0 0 120 40" preserveAspectRatio="none">
                        <polyline points="0,20 18,20 25,8 33,32 41,20 55,20 63,5 71,35 79,20 120,20" />
                    </svg>
                </div>
                <h2><?php echo e($totalDoctores); ?></h2>
                <p>Doctores</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card-v2" style="--accent:#16a34a; --accent-soft: rgba(22,163,74,0.07); --accent-soft-2: rgba(22,163,74,0.16);">
                <div class="stat-card-v2-top">
                    <div class="stat-card-v2-icon"><i class="bi bi-people"></i></div>
                    <svg class="stat-card-v2-heartbeat" viewBox="0 0 120 40" preserveAspectRatio="none">
                        <polyline points="0,20 18,20 25,8 33,32 41,20 55,20 63,5 71,35 79,20 120,20" />
                    </svg>
                </div>
                <h2><?php echo e($totalPacientes); ?></h2>
                <p>Pacientes</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card-v2" style="--accent:#f59e0b; --accent-soft: rgba(245,158,11,0.08); --accent-soft-2: rgba(245,158,11,0.18);">
                <div class="stat-card-v2-top">
                    <div class="stat-card-v2-icon"><i class="bi bi-calendar2-week"></i></div>
                    <svg class="stat-card-v2-heartbeat" viewBox="0 0 120 40" preserveAspectRatio="none">
                        <polyline points="0,20 18,20 25,8 33,32 41,20 55,20 63,5 71,35 79,20 120,20" />
                    </svg>
                </div>
                <h2><?php echo e($totalCitas); ?></h2>
                <p>Citas totales</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card-v2" style="--accent:#9333ea; --accent-soft: rgba(147,51,234,0.07); --accent-soft-2: rgba(147,51,234,0.16);">
                <div class="stat-card-v2-top">
                    <div class="stat-card-v2-icon"><i class="bi bi-calendar2-check"></i></div>
                    <svg class="stat-card-v2-heartbeat" viewBox="0 0 120 40" preserveAspectRatio="none">
                        <polyline points="0,20 18,20 25,8 33,32 41,20 55,20 63,5 71,35 79,20 120,20" />
                    </svg>
                </div>
                <h2><?php echo e($citasHoy); ?></h2>
                <p>Citas hoy</p>
            </div>
        </div>
    </div>
    <div class="mt-4 p-3" style="background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.3); border-radius: 12px; color: #92400e;">
        <i class="bi bi-hourglass-split me-2"></i>
        Citas pendientes por confirmar: <strong><?php echo e($citasPendientes); ?></strong>
    </div>

    <h5 class="mt-4 mb-3"><i class="bi bi-bar-chart-line me-2"></i>Estadísticas</h5>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="stats-card">
                <h6><i class="bi bi-pie-chart-fill me-1"></i>Citas por estado</h6>
                <?php
                    $maxEstado = max($citasPorEstado->max(), 1);
                    $colores = [
                        'pendiente' => '#8b93a7',
                        'confirmada' => '#3b82f6',
                        'atendida' => '#22c55e',
                        'cancelada' => '#f43f5e',
                    ];
                ?>
                <div class="bar-chart">
                    <?php $__currentLoopData = $citasPorEstado; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estado => $cantidad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $color = $colores[$estado] ?? '#94a3b8'; ?>
                        <div class="bar-col">
                            <div class="bar-value"><?php echo e($cantidad); ?></div>
                            <div class="bar-track">
                                <div class="bar-fill" style="--bar-glow: <?php echo e($color); ?>; background: linear-gradient(180deg, <?php echo e($color); ?>, color-mix(in srgb, <?php echo e($color); ?> 55%, #0c1020)); height: <?php echo e(max((int) round(($cantidad / $maxEstado) * 100), 6)); ?>%; animation-delay: <?php echo e($loop->index * 0.08); ?>s;"></div>
                            </div>
                            <div class="bar-label"><span class="bar-dot" style="--bar-glow: <?php echo e($color); ?>;"></span><?php echo e(ucfirst($estado)); ?></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="stats-card">
                <h6><i class="bi bi-clipboard2-pulse-fill me-1"></i>Citas por especialidad</h6>
                <?php $maxEspecialidad = max($citasPorEspecialidad->max() ?? 0, 1); ?>
                <?php $__empty_1 = true; $__currentLoopData = $citasPorEspecialidad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $especialidad => $cantidad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="spec-row">
                        <div class="spec-row-top">
                            <span><span class="spec-rank"><?php echo e($loop->iteration); ?></span><span class="spec-name"><?php echo e($especialidad); ?></span></span>
                            <span class="spec-count"><?php echo e($cantidad); ?></span>
                        </div>
                        <div class="spec-bar-track">
                            <div class="spec-bar-fill" style="width: <?php echo e(max((int) round(($cantidad / $maxEspecialidad) * 100), 4)); ?>%; animation-delay: <?php echo e($loop->index * 0.08); ?>s;"></div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted mb-0">Sin datos.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Desktop\Proyecto de Ingieneria de Software H2\citas-medicas-laravel\resources\views/dashboard.blade.php ENDPATH**/ ?>