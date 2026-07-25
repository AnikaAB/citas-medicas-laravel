<?php $__env->startSection('titulo', 'Editar cita'); ?>
<?php $__env->startSection('contenido'); ?>
<h3>Editar cita #<?php echo e($cita->id); ?></h3>
<form method="POST" action="<?php echo e(route('citas.update', $cita)); ?>">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <div class="mb-3">
        <label class="form-label">Paciente</label>
        <select name="paciente_id" class="form-select" required>
            <?php $__currentLoopData = $pacientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paciente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($paciente->id); ?>" <?php if($cita->paciente_id==$paciente->id): echo 'selected'; endif; ?>><?php echo e($paciente->nombre); ?> <?php echo e($paciente->apellido); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Doctor</label>
        <select name="doctor_id" class="form-select" required>
            <?php $__currentLoopData = $doctores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($doctor->id); ?>" <?php if($cita->doctor_id==$doctor->id): echo 'selected'; endif; ?>>Dr. <?php echo e($doctor->nombre); ?> <?php echo e($doctor->apellido); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha" class="form-control" required value="<?php echo e($cita->fecha->format('Y-m-d')); ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Hora</label>
            <input type="time" name="hora" class="form-control" required value="<?php echo e(\Illuminate\Support\Carbon::parse($cita->hora)->format('H:i')); ?>">
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Motivo</label>
        <input type="text" name="motivo" class="form-control" required value="<?php echo e($cita->motivo); ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-select" required>
            <?php $__currentLoopData = ['pendiente','confirmada','cancelada','atendida']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($estado); ?>" <?php if($cita->estado==$estado): echo 'selected'; endif; ?>><?php echo e(ucfirst($estado)); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Observaciones</label>
        <textarea name="observaciones" class="form-control"><?php echo e($cita->observaciones); ?></textarea>
    </div>
    <button class="btn btn-primary">Actualizar</button>
    <a href="<?php echo e(route('citas.index')); ?>" class="btn btn-secondary">Cancelar</a>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Downloads\citas-medicas-laravel-actualizado\citas-medicas-laravel\resources\views/citas/edit.blade.php ENDPATH**/ ?>