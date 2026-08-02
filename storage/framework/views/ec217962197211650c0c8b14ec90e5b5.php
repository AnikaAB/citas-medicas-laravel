<?php $__env->startSection('titulo', 'Usuarios'); ?>
<?php $__env->startSection('contenido'); ?>
<h3><i class="bi bi-people-fill me-2"></i>Gestión de usuarios</h3>
<p class="text-muted">Activa o desactiva el acceso al sistema. Un usuario desactivado no puede iniciar sesión, pero conserva su historial.</p>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <select name="rol" class="form-select" onchange="this.form.submit()">
            <option value="">Todos los roles</option>
            <option value="admin" <?php if(request('rol') === 'admin'): echo 'selected'; endif; ?>>Admin</option>
            <option value="recepcionista" <?php if(request('rol') === 'recepcionista'): echo 'selected'; endif; ?>>Recepcionista</option>
            <option value="doctor" <?php if(request('rol') === 'doctor'): echo 'selected'; endif; ?>>Doctor</option>
            <option value="paciente" <?php if(request('rol') === 'paciente'): echo 'selected'; endif; ?>>Paciente</option>
        </select>
    </div>
    <div class="col-md-4">
        <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre o correo" value="<?php echo e(request('buscar')); ?>">
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary w-100">Buscar</button>
    </div>
</form>

<table class="table table-hover align-middle">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Estado</th>
            <th class="text-end">Acción</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><?php echo e($usuario->name); ?></td>
            <td><?php echo e($usuario->email); ?></td>
            <td><span class="badge bg-secondary"><?php echo e(ucfirst($usuario->rol)); ?></span></td>
            <td>
                <?php if($usuario->activo): ?>
                    <span class="badge bg-success">Activo</span>
                <?php else: ?>
                    <span class="badge bg-danger">Inactivo</span>
                <?php endif; ?>
            </td>
            <td class="text-end">
                <?php if($usuario->id !== auth()->id()): ?>
                    <form method="POST" action="<?php echo e(route('usuarios.alternarEstado', $usuario)); ?>" class="d-inline">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <?php if($usuario->activo): ?>
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Desactivar a <?php echo e($usuario->name); ?>? No podrá iniciar sesión.')">Desactivar</button>
                        <?php else: ?>
                            <button class="btn btn-sm btn-outline-success">Activar</button>
                        <?php endif; ?>
                    </form>
                <?php else: ?>
                    <span class="text-muted small">Tu cuenta</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="5" class="text-center text-muted py-4">No se encontraron usuarios.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php echo e($usuarios->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\EDUARDO VIDAL\Desktop\citas-medicas-laravel\resources\views/usuarios/index.blade.php ENDPATH**/ ?>