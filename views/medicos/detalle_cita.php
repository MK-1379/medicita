<?php
$cita = $cita ?? null;
if (!$cita) {
    header('Location: ' . (defined('BASE_URL') ? BASE_URL : '/') . 'medico/dashboard');
    exit;
}

$fecha = !empty($cita->fecha) ? date('d / m / Y', strtotime($cita->fecha)) : '';
$hora  = !empty($cita->hora)  ? date('H:i', strtotime($cita->hora)) : '';

$fechaLarga = '';
if (!empty($cita->fecha)) {
    $diasSemana = ['Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles', 'Thursday' => 'Jueves', 'Friday' => 'Viernes', 'Saturday' => 'Sábado', 'Sunday' => 'Domingo'];
    $meses      = ['January' => 'enero', 'February' => 'febrero', 'March' => 'marzo', 'April' => 'abril', 'May' => 'mayo', 'June' => 'junio', 'July' => 'julio', 'August' => 'agosto', 'September' => 'septiembre', 'October' => 'octubre', 'November' => 'noviembre', 'December' => 'diciembre'];
    $dt         = new DateTime($cita->fecha);
    $diaSemana  = $diasSemana[$dt->format('l')] ?? $dt->format('l');
    $mes        = $meses[$dt->format('F')] ?? $dt->format('F');
    $fechaLarga = $diaSemana . ', ' . $dt->format('d') . ' de ' . $mes . ' de ' . $dt->format('Y');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCita - Detalle de cita</title>
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
            <span class="texto-bienvenida">Hola, <?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></span>
            <a href="<?= BASE_URL ?>auth/logout" class="btn-salir">Cerrar sesión</a>
        </div>
    </nav>

    <?php Messages::display(); ?>

    <div class="detalle-cita-med">

        <div class="miga-pan-med">
            <a href="<?= BASE_URL ?>medico/dashboard">Mis citas</a>
            <span> • Detalle de cita #<?= str_pad($cita->id, 4, '0', STR_PAD_LEFT) ?></span>
        </div>

        <h1 class="titulo-detalle-med">Detalle de cita</h1>

        <div class="layout-detalle-med">

            <div class="med-detalle__left-col">

                <div class="bloque-info">
                    <div class="encabezado-bloque">
                        <span class="subtitulo-bloque">DATOS DE LA CITA</span>
                        <?php if ($cita->estado === 'asignada'): ?>
                            <span class="badge-ok">Confirmada</span>
                        <?php else: ?>
                            <span class="badge-libre">Disponible</span>
                        <?php endif; ?>
                    </div>
                    <div class="linea-dato">
                        <span class="texto-label">Fecha</span>
                        <span class="texto-valor"><?= ucfirst($fechaLarga) ?></span>
                    </div>
                    <div class="linea-dato">
                        <span class="texto-label">Hora</span>
                        <span class="texto-valor"><?= $hora ?> h</span>
                    </div>
                    <div class="linea-dato">
                        <span class="texto-label">Lugar</span>
                        <span class="texto-valor"><?= htmlspecialchars($cita->lugar) ?></span>
                    </div>
                    <?php if (!empty($cita->aseguradora)): ?>
                        <div class="linea-dato">
                            <span class="texto-label">Aseguradora</span>
                            <span class="texto-valor"><?= htmlspecialchars($cita->aseguradora) ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="bloque-info">
                    <div class="subtitulo-bloque" style="margin-bottom:16px">MÉDICO ASIGNADO</div>
                    <div class="info-dr">
                        <div class="foto-dr"><i class="fa-solid fa-user-doctor"></i></div>
                        <div>
                            <div class="nombre-dr">
                                <?= htmlspecialchars('Dr. ' . $cita->med_apellido) ?>
                            </div>
                            <?php if (!empty($cita->especialidad ?? '')): ?>
                                <span class="esp-dr"><?= htmlspecialchars($cita->especialidad) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if ($cita->estado === 'asignada'): ?>
                    <div class="bloque-info">
                        <div class="linea-dato">
                            <span class="texto-label">Paciente</span>
                            <span class="texto-valor">
                                <?= htmlspecialchars(($cita->pac_nombre ?? '') . ' ' . ($cita->pac_apellido ?? '')) ?>
                            </span>
                        </div>
                        <div class="linea-dato">
                            <span class="texto-label">Teléfono</span>
                            <span class="texto-valor"><?= htmlspecialchars($cita->pac_telefono ?? '') ?></span>
                        </div>
                        <div class="linea-dato">
                            <span class="texto-label">Aseguradora</span>
                            <span class="texto-valor"><?= htmlspecialchars($cita->aseguradora ?? '') ?></span>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <div class="columna-acciones">

                <a href="<?= BASE_URL ?>medico/dashboard" class="volver-dash">Volver a mis citas</a>

                <div class="titulo-notas">Modificar cita</div>
                <form method="POST" action="<?= BASE_URL ?>medico/editarCitaPost/<?= $cita->id ?>">
                    <input type="date" name="fecha" class="input-nota"
                        value="<?= htmlspecialchars($cita->fecha) ?>">
                    <input type="time" name="hora" class="input-nota"
                        value="<?= htmlspecialchars($cita->hora) ?>">
                    <input type="text" name="lugar" class="input-nota"
                        placeholder="Consultorio 3, Planta 2"
                        value="<?= htmlspecialchars($cita->lugar) ?>">
                    <button type="submit" class="guardar-nota">Guardar cambios</button>
                </form>

                <a href="<?= BASE_URL ?>medico/eliminarCita/<?= $cita->id ?>"
                    class="eliminar-cita"
                    onclick="return confirm('¿Seguro que quieres eliminar esta cita?')">
                    Eliminar cita
                </a>

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
</body>

</html>