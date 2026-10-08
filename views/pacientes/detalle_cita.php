<?php
$nombrePaciente = $_SESSION['user_name'] ?? 'Paciente';
$cita = $cita ?? null;
if (!$cita) {
    header('Location: ' . (defined('BASE_URL') ? BASE_URL : '/') . 'paciente/dashboard');
    exit;
}

$fecha = !empty($cita->fecha) ? date('d / m / Y', strtotime($cita->fecha)) : '—';
$hora  = !empty($cita->hora)  ? date('H:i', strtotime($cita->hora)) : '—';
$diasSemana = ['Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles', 'Thursday' => 'Jueves', 'Friday' => 'Viernes', 'Saturday' => 'Sábado', 'Sunday' => 'Domingo'];
$meses = ['January' => 'enero', 'February' => 'febrero', 'March' => 'marzo', 'April' => 'abril', 'May' => 'mayo', 'June' => 'junio', 'July' => 'julio', 'August' => 'agosto', 'September' => 'septiembre', 'October' => 'octubre', 'November' => 'noviembre', 'December' => 'diciembre'];
$fechaLarga = '—';
if (!empty($cita->fecha)) {
    $dt = new DateTime($cita->fecha);
    $diaSemana = $diasSemana[$dt->format('l')] ?? $dt->format('l');
    $mes = $meses[$dt->format('F')] ?? $dt->format('F');
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
            <span class="texto-bienvenida">Hola, <?= htmlspecialchars($nombrePaciente) ?></span>
            <form method="POST" action="<?= BASE_URL ?>auth/logout" class="form-en-linea">
                <?= Csrf::field() ?>
                <button type="submit" class="btn-salir">Cerrar sesión</button>
            </form>
        </div>
    </nav>

    <?php Messages::display(); ?>

    <div class="detalle-pac">

        <div class="ruta-pac">
            <a href="<?= BASE_URL ?>paciente/dashboard">Mis citas</a>
            <span> • Detalle de cita #<?= str_pad($cita->id, 4, '0', STR_PAD_LEFT) ?></span>
        </div>

        <h1 class="titulo-mi-cita">Detalle de cita</h1>

        <div class="tarjeta-detalle">
            <div class="encabezado-tarjeta">
                <span class="nombre-seccion">DATOS DE LA CITA</span>
                <?php if ($cita->estado === 'asignada'): ?>
                    <span class="confirmada-badge">Confirmada</span>
                <?php else: ?>
                    <span class="libre-badge">Disponible</span>
                <?php endif; ?>
            </div>
            <div class="fila-info-cita">
                <span class="nombre-campo-cita">Fecha</span>
                <span class="contenido-campo-cita"><?= $fechaLarga ?></span>
            </div>
            <div class="fila-info-cita">
                <span class="nombre-campo-cita">Hora</span>
                <span class="contenido-campo-cita"><?= $hora ?> h</span>
            </div>
            <div class="fila-info-cita">
                <span class="nombre-campo-cita">Lugar</span>
                <span class="contenido-campo-cita"><?= htmlspecialchars($cita->lugar) ?></span>
            </div>
            <?php if (!empty($cita->aseguradora)): ?>
                <div class="fila-info-cita">
                    <span class="nombre-campo-cita">Aseguradora</span>
                    <span class="contenido-campo-cita"><?= htmlspecialchars($cita->aseguradora) ?></span>
                </div>
            <?php endif; ?>
        </div>

        <div class="tarjeta-detalle">
            <div class="nombre-seccion" style="margin-bottom:16px">MÉDICO ASIGNADO</div>
            <div class="datos-mi-medico">
                <div class="foto-mi-medico"><i class="fa-solid fa-user-doctor"></i></div>
                <div>
                    <div class="nombre-mi-medico">
                        <?= htmlspecialchars($cita->med_nombre . ' ' . $cita->med_apellido) ?>
                    </div>
                    <?php if (!empty($cita->especialidad)): ?>
                        <span class="esp-mi-medico"><?= htmlspecialchars($cita->especialidad) ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <a href="<?= BASE_URL ?>paciente/dashboard" class="volver-mis-citas">
            <i class="fa-solid fa-arrow-left"></i> Volver a mis citas
        </a>

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