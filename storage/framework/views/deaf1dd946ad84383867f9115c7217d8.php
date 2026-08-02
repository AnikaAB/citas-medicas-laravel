<?php
    $estilos = [
        'pendiente'  => ['bg' => 'rgba(251,191,36,0.15)', 'border' => 'rgba(251,191,36,0.4)', 'color' => '#fcd34d', 'icono' => 'bi-hourglass-split'],
        'confirmada' => ['bg' => 'rgba(34,211,238,0.15)',  'border' => 'rgba(34,211,238,0.4)',  'color' => '#67e8f9', 'icono' => 'bi-check2-circle'],
        'atendida'   => ['bg' => 'rgba(52,211,153,0.15)',  'border' => 'rgba(52,211,153,0.4)',  'color' => '#6ee7b7', 'icono' => 'bi-clipboard2-check'],
        'cancelada'  => ['bg' => 'rgba(244,63,94,0.15)',   'border' => 'rgba(244,63,94,0.4)',   'color' => '#fca5a5', 'icono' => 'bi-x-circle'],
    ];
    $estilo = $estilos[$estado] ?? ['bg' => 'rgba(255,255,255,0.08)', 'border' => 'rgba(255,255,255,0.2)', 'color' => '#e5e7eb', 'icono' => 'bi-question-circle'];
?>
<span class="badge-estado" style="background: <?php echo e($estilo['bg']); ?>; border: 1px solid <?php echo e($estilo['border']); ?>; color: <?php echo e($estilo['color']); ?>;">
    <i class="bi <?php echo e($estilo['icono']); ?>"></i> <?php echo e(ucfirst($estado)); ?>

</span>
<?php /**PATH C:\Users\Usuario\Downloads\citas-medicas-laravel-MERGED\final\resources\views/components/estado-badge.blade.php ENDPATH**/ ?>