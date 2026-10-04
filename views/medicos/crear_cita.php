<?php
$medico = $medico ?? null;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCita - Crear nueva cita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jura:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/app.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="<?= BASE_URL ?>">
            <i class="fa-solid fa-stethoscope"></i>
            <p>MediCita</p>
        </a>
        <div class="collapse navbar-collapse justify-content-center">
            <ul class="navbar-nav gap-4">
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>">INICIO</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>home/medicos">MÉDICOS</a></li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="texto-bienvenida"><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></span>
            <a href="<?= BASE_URL ?>auth/logout" class="btn-salir">Cerrar sesión</a>
        </div>
    </nav>

    <?php Messages::display(); ?>

    <div class="pagina-cita">

        <div class="ruta-cita">
            <a href="<?= BASE_URL ?>medico/dashboard">Mis citas</a>
            <span> • Crear nueva cita</span>
        </div>

        <h1 class="titulo-nueva-cita">Crear nueva cita</h1>

        <div class="layout-cita">

            <div class="formulario-cita">
                <form method="POST" action="<?= BASE_URL ?>medico/crearCitaPost">

                    <div class="mb-4">
                        <div class="etiqueta-campo">Fecha <span class="campo-req">*</span></div>
                        <input type="date" name="fecha" class="entrada-cita"
                            placeholder="DD / MM / AAAA" required>
                    </div>

                    <div class="mb-4">
                        <div class="etiqueta-campo">Hora <span class="campo-req">*</span></div>
                        <div class="fila-horario">
                            <select name="hora" class="desplegable-cita" required>
                                <option value="" disabled selected>Hora inicio: 10:00</option>
                                <?php
                                for ($h = 8; $h <= 20; $h++) {
                                    foreach (['00', '30'] as $m) {
                                        $val = sprintf('%02d:%s', $h, $m);
                                        echo "<option value=\"$val\">$val</option>";
                                    }
                                }
                                ?>
                            </select>
                            <select name="hora_fin" class="desplegable-cita">
                                <option value="" disabled selected>Hora fin: 10:30</option>
                                <?php
                                for ($h = 8; $h <= 20; $h++) {
                                    foreach (['00', '30'] as $m) {
                                        $val = sprintf('%02d:%s', $h, $m);
                                        echo "<option value=\"$val\">$val</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="etiqueta-campo">Lugar <span class="campo-req">*</span></div>
                        <input type="text" name="lugar" class="entrada-cita"
                            placeholder="Ej. Consultorio 3, Planta 2 - C/ Mayor 14" required>
                    </div>

                    <hr class="separador-form">

                    <div class="botones-cita">
                        <button type="submit" class="btn-confirmar">Crear cita</button>
                        <a href="<?= BASE_URL ?>medico/dashboard" class="btn-cancelar-cita">Cancelar</a>
                    </div>

                </form>
            </div>

            <div class="panel-medico">
                <div class="info-med-lateral">
                    <div class="avatar-med"><i class="fa-solid fa-user-doctor"></i></div>
                    <div>
                        <div class="nombre-med-lateral">
                            <?= htmlspecialchars(($_SESSION['user_name'] ?? 'Dr.')) ?>
                        </div>
                        <?php if (!empty($medico->especialidad)): ?>
                            <span class="esp-badge"><?= htmlspecialchars($medico->especialidad) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <hr class="linea-lateral">

                <div class="preview-nombre">Fecha</div>
                <div class="preview-dato" id="prev-fecha">—</div>

                <div class="preview-nombre">Hora</div>
                <div class="preview-dato" id="prev-hora">—</div>

                <div class="preview-nombre">Lugar</div>
                <div class="preview-dato" id="prev-lugar">—</div>

                <span class="estado-disponible">Disponible - sin paciente asignado</span>

                <hr class="linea-lateral">

                <div class="titulo-requeridos">Campos obligatorios</div>
                <div class="item-requerido">
                    <div class="puntito"></div> Fecha <span class="campo-req">*</span>
                </div>
                <div class="item-requerido">
                    <div class="puntito"></div> Hora <span class="campo-req">*</span>
                </div>
                <div class="item-requerido">
                    <div class="puntito"></div> Lugar <span class="campo-req">*</span>
                </div>

                <hr class="linea-lateral">

                <div class="titulo-automatico">Asignado automáticamente</div>
                <div class="dato-automatico">
                    Médico → <?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>
                </div>
            </div>

        </div>
    </div>

    <footer>
        <a href="<?= BASE_URL ?>" class="footer-logo">
            <i class="fa-solid fa-stethoscope"></i>
            <p>MediCita</p>
        </a>
        <div class="footer-links">
            <a href="<?= BASE_URL ?>">INICIO</a>
            <a href="<?= BASE_URL ?>home/medicos">MÉDICOS</a>
        </div>
        <div class="footer-contacto">
            <p><i class="fa-regular fa-envelope"></i> medicita@gmail.com</p>
            <p><i class="fa-solid fa-phone"></i> +34 123 456 789</p>
            <p><i class="fa-solid fa-location-dot"></i> Calle San Lázaro 23</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const inputFecha = document.querySelector('input[name="fecha"]');
        const selectHora = document.querySelector('select[name="hora"]');
        const selectFin = document.querySelector('select[name="hora_fin"]');
        const inputLugar = document.querySelector('input[name="lugar"]');

        function actualizarPreview() {
            const f = inputFecha.value;
            document.getElementById('prev-fecha').textContent = f ?
                new Date(f + 'T00:00:00').toLocaleDateString('es-ES', {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                }) :
                '—';

            const hi = selectHora.value;
            const hf = selectFin.value;
            document.getElementById('prev-hora').textContent = hi ? (hf ? hi + ' - ' + hf : hi) : '—';

            document.getElementById('prev-lugar').textContent = inputLugar.value || '—';
        }

        inputFecha.addEventListener('change', actualizarPreview);
        selectHora.addEventListener('change', actualizarPreview);
        selectFin.addEventListener('change', actualizarPreview);
        inputLugar.addEventListener('input', actualizarPreview);
    </script>
</body>

</html>