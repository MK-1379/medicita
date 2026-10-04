<?php
$medico = $medico ?? null;
if (!$medico) {
    header('Location: ' . (defined('BASE_URL') ? BASE_URL : '/') . 'medico/dashboard');
    exit;
}
$nombreMedico = $_SESSION['user_name'] ?? 'Médico';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCita - Mi perfil</title>
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
            <span class="texto-bienvenida"><?= htmlspecialchars($nombreMedico) ?></span>
            <a href="<?= BASE_URL ?>auth/logout" class="btn-salir">Cerrar sesión</a>
        </div>
    </nav>

    <?php Messages::display(); ?>

    <div class="pagina-perfil-med">

        <div class="nav-perfil">
            <a href="<?= BASE_URL ?>medico/dashboard">Panel médico</a>
            <span> • Mi perfil</span>
        </div>

        <h1 class="titulo-mi-perfil">Mi perfil</h1>

        <div class="seccion-perfil">
            <div class="presentacion-medico">
                <div class="foto-grande"><i class="fa-solid fa-user-doctor"></i></div>
                <div>
                    <div class="mi-nombre">
                        <?= htmlspecialchars($medico->nombre . ' ' . $medico->apellido) ?>
                    </div>
                    <?php if (!empty($medico->especialidad)): ?>
                        <span class="mi-especialidad"><?= htmlspecialchars($medico->especialidad) ?></span>
                    <?php else: ?>
                        <span class="sin-especialidad">Sin especialidad</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="encabezado-seccion">DATOS PERSONALES</div>
            <div class="fila-perfil">
                <span class="campo-label">Correo electrónico</span>
                <span class="campo-valor"><?= htmlspecialchars($medico->email) ?></span>
            </div>
            <div class="fila-perfil">
                <span class="campo-label">Teléfono</span>
                <span class="campo-valor"><?= htmlspecialchars($medico->telefono) ?></span>
            </div>
            <div class="fila-perfil">
                <span class="campo-label">Fecha de nacimiento</span>
                <span class="campo-valor">
                    <?= !empty($medico->fecha_nac) ? date('d/m/Y', strtotime($medico->fecha_nac)) : '—' ?>
                </span>
            </div>
            <div class="fila-perfil">
                <span class="campo-label">Sexo</span>
                <span class="campo-valor">
                    <?= ($medico->sexo === 'M') ? 'Masculino' : 'Femenino' ?>
                </span>
            </div>
        </div>

        <div class="seccion-perfil">
            <div class="encabezado-seccion">ESPECIALIDAD Y HORARIO</div>
            <form method="POST" action="<?= BASE_URL ?>medico/perfilPost">

                <div class="etiqueta-editar">Especialidad</div>
                <input type="text" name="especialidad" class="input-editar"
                    placeholder="Ej. Cardiología"
                    value="<?= htmlspecialchars($medico->especialidad ?? '') ?>">

                <div class="etiqueta-editar">Horario disponible</div>
                <textarea name="horario" class="textarea-bio"
                    placeholder="Ej. Lun - Jue | 09:00 - 14:00"><?= htmlspecialchars($medico->horario ?? '') ?></textarea>
                <div class="nota-campo">Formato sugerido: Lun - Jue | 09:00 - 14:00</div>

                <div class="botones-guardar">
                    <button type="submit" class="btn-guardar-perfil-med">Guardar cambios</button>
                    <a href="<?= BASE_URL ?>medico/dashboard" class="btn-descartar">Cancelar</a>
                </div>
            </form>
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