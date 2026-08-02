@extends('layouts.app')
@section('titulo', 'Agendar cita')
@section('contenido')
<h3><i class="bi bi-plus-circle me-2"></i>Agendar nueva cita</h3>

<form method="POST" action="{{ route('paciente.citas.store') }}" id="form-agendar">
    @csrf

    <div class="mb-3">
        <label class="form-label">Especialidad</label>
        <select id="especialidad" class="form-select" required>
            <option value="">Seleccione...</option>
            @foreach($especialidades as $especialidad)
                <option value="{{ $especialidad }}" @selected(old('especialidad') == $especialidad)>{{ $especialidad }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Doctor</label>
        <select name="doctor_id" id="doctor_id" class="form-select" required disabled>
            <option value="">Seleccione primero una especialidad...</option>
        </select>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha" id="fecha" class="form-control" required
                   min="{{ now()->toDateString() }}" value="{{ old('fecha') }}" disabled>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Hora</label>
            <select name="hora" id="hora" class="form-select" required disabled>
                <option value="">Seleccione doctor y fecha primero...</option>
            </select>
            <div class="form-text" id="hora-ayuda"></div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Motivo de la consulta</label>
        <textarea name="motivo" class="form-control" required maxlength="255">{{ old('motivo') }}</textarea>
    </div>

    <button class="btn btn-primary" id="btn-agendar"><i class="bi bi-check-lg me-1"></i>Agendar</button>
    <a href="{{ route('paciente.citas.index') }}" class="btn btn-secondary"><i class="bi bi-x-lg me-1"></i>Cancelar</a>
</form>

<script>
(function () {
    const urlDoctores = "{{ route('paciente.citas.doctores') }}";
    const urlHorarios = "{{ route('paciente.citas.horarios') }}";

    const selectEspecialidad = document.getElementById('especialidad');
    const selectDoctor = document.getElementById('doctor_id');
    const inputFecha = document.getElementById('fecha');
    const selectHora = document.getElementById('hora');
    const ayudaHora = document.getElementById('hora-ayuda');

    function resetSelect(select, placeholder, deshabilitar) {
        select.innerHTML = '';
        const opt = document.createElement('option');
        opt.value = '';
        opt.textContent = placeholder;
        select.appendChild(opt);
        select.disabled = deshabilitar;
    }

    // 1) Especialidad -> carga doctores de esa especialidad
    selectEspecialidad.addEventListener('change', function () {
        resetSelect(selectDoctor, 'Cargando doctores...', true);
        resetSelect(selectHora, 'Seleccione doctor y fecha primero...', true);
        inputFecha.disabled = true;
        inputFecha.value = '';
        ayudaHora.textContent = '';

        if (!this.value) {
            resetSelect(selectDoctor, 'Seleccione primero una especialidad...', true);
            return;
        }

        fetch(urlDoctores + '?especialidad=' + encodeURIComponent(this.value))
            .then(r => r.json())
            .then(doctores => {
                resetSelect(selectDoctor, 'Seleccione...', false);
                doctores.forEach(d => {
                    const opt = document.createElement('option');
                    opt.value = d.id;
                    opt.textContent = d.nombre;
                    selectDoctor.appendChild(opt);
                });
                inputFecha.disabled = false;
            })
            .catch(() => {
                resetSelect(selectDoctor, 'Error al cargar doctores', true);
            });
    });

    // 2) Doctor o fecha cambian -> recalcula horas libres
    function cargarHorarios() {
        resetSelect(selectHora, 'Cargando horarios...', true);
        ayudaHora.textContent = '';

        if (!selectDoctor.value || !inputFecha.value) {
            resetSelect(selectHora, 'Seleccione doctor y fecha primero...', true);
            return;
        }

        fetch(urlHorarios + '?doctor_id=' + encodeURIComponent(selectDoctor.value) + '&fecha=' + encodeURIComponent(inputFecha.value))
            .then(r => r.json())
            .then(horas => {
                if (horas.length === 0) {
                    resetSelect(selectHora, 'Sin horarios libres ese día', true);
                    ayudaHora.textContent = 'Prueba con otra fecha o doctor.';
                    return;
                }
                resetSelect(selectHora, 'Seleccione...', false);
                horas.forEach(h => {
                    const opt = document.createElement('option');
                    opt.value = h;
                    opt.textContent = h;
                    selectHora.appendChild(opt);
                });
            })
            .catch(() => {
                resetSelect(selectHora, 'Error al cargar horarios', true);
            });
    }

    selectDoctor.addEventListener('change', cargarHorarios);
    inputFecha.addEventListener('change', cargarHorarios);
})();
</script>
@endsection
