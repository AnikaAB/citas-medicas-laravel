<?php $__env->startSection('titulo', 'Agendar cita'); ?>
<?php $__env->startSection('contenido'); ?>

<h3 class="mb-4"><i class="bi bi-plus-circle me-2"></i>Agendar nueva cita</h3>

<form method="POST" action="<?php echo e(route('mis-citas.store')); ?>" style="max-width: 520px;">
    <?php echo csrf_field(); ?>

    <div class="mb-3">
        <label class="form-label">Doctor</label>
        <select name="doctor_id" class="form-select" required>
            <option value="">Selecciona un doctor</option>
            <?php $__currentLoopData = $doctores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($doctor->id); ?>" <?php if(old('doctor_id')==$doctor->id): echo 'selected'; endif; ?>>
                    Dr. <?php echo e($doctor->nombre); ?> <?php echo e($doctor->apellido); ?> — <?php echo e($doctor->especialidad); ?>

                    (<?php echo e(substr($doctor->horario_inicio,0,5)); ?> a <?php echo e(substr($doctor->horario_fin,0,5)); ?>)
                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha" class="form-control" value="<?php echo e(old('fecha')); ?>" min="<?php echo e(now()->toDateString()); ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Hora</label>
            <input type="time" name="hora" class="form-control" value="<?php echo e(old('hora')); ?>" required>
            <small class="text-muted">Formato 24 horas. Ej: 14:00 = 2:00 pm.</small>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Motivo de la consulta</label>
        <textarea name="motivo" class="form-control" rows="3" required placeholder="Ej: Dolor de cabeza persistente"><?php echo e(old('motivo')); ?></textarea>
    </div>

    <div class="alert alert-info small">
        <i class="bi bi-info-circle me-1"></i>
        Tu cita quedara en estado <strong>pendiente</strong> hasta que recepcion la confirme.
    </div>

    <button class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> Agendar cita</button>
    <a href="<?php echo e(route('mis-citas.index')); ?>" class="btn btn-outline-secondary">Cancelar</a>
</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Downloads\citas-medicas-laravel-actualizado\citas-medicas-laravel\resources\views/mis-citas/create.blade.php ENDPATH**/ ?>