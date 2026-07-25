@php
    $estilos = [
        'pendiente'  => ['bg' => '#FEF3C7', 'color' => '#92400E', 'icono' => 'bi-hourglass-split'],
        'confirmada' => ['bg' => '#DBEAFE', 'color' => '#1E40AF', 'icono' => 'bi-check2-circle'],
        'atendida'   => ['bg' => '#D1FAE5', 'color' => '#065F46', 'icono' => 'bi-clipboard2-check'],
        'cancelada'  => ['bg' => '#FEE2E2', 'color' => '#991B1B', 'icono' => 'bi-x-circle'],
    ];
    $estilo = $estilos[$estado] ?? ['bg' => '#E5E7EB', 'color' => '#374151', 'icono' => 'bi-question-circle'];
@endphp
<span class="badge-estado" style="background: {{ $estilo['bg'] }}; color: {{ $estilo['color'] }};">
    <i class="bi {{ $estilo['icono'] }}"></i> {{ ucfirst($estado) }}
</span>
