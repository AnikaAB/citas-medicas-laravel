
<?php $__env->startSection('titulo', 'Nueva cita'); ?>
<?php $__env->startSection('contenido'); ?>
<h3>Registrar nueva cita</h3>
<form method="POST" action="<?php echo e(route('citas.store')); ?>">
    <?php echo csrf_field(); ?>
    <div class="mb-3">
        <label class="form-label">Paciente</label>
        <select name="paciente_id" class="form-select" required>
            <option value="">Seleccione...</option>
            <?php $__currentLoopData = $pacientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paciente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($paciente->id); ?>"><?php echo e($paciente->nombre); ?> <?php echo e($paciente->apellido); ?> - <?php echo e($paciente->cedula); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Doctor</label>
        <select name="doctor_id" class="form-select" required>
            <option value="">Seleccione...</option>
            <?php $__currentLoopData = $doctores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($doctor->id); ?>">Dr. <?php echo e($doctor->nombre); ?> <?php echo e($doctor->apellido); ?> (<?php echo e($doctor->especialidad); ?>)</option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha" class="form-control" required value="<?php echo e(old('fecha')); ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Hora</label>
            <input type="time" name="hora" class="form-control" required value="<?php echo e(old('hora')); ?>">
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Motivo</label>
        <input type="text" name="motivo" class="form-control" required value="<?php echo e(old('motivo')); ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-select" required>
            <option value="pendiente">Pendiente</option>
            <option value="confirmada">Confirmada</option>
            <option value="cancelada">Cancelada</option>
            <option value="atendida">Atendida</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Observaciones</label>
        <textarea name="observaciones" class="form-control"></textarea>
    </div>
    <button class="btn btn-primary">Guardar</button>
    <a href="<?php echo e(route('citas.index')); ?>" class="btn btn-secondary">Cancelar</a>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\EDUARDO VIDAL\Desktop\citas-medicas-laravel\resources\views/citas/create.blade.php ENDPATH**/ ?>