<?php
$nombrePaciente = $_SESSION['user_name'] ?? 'Paciente';
$paciente = $paciente ?? null;
if (!$paciente) {
    header('Location: ' . (defined('BASE_URL') ? BASE_URL : '/') . 'paciente/dashboard');
    exit;
}
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
            <span class="texto-bienvenida"><?= htmlspecialchars($nombrePaciente) ?></span>
            <a href="<?= BASE_URL ?>auth/logout" class="btn-salir">Cerrar sesión</a>
        </div>
    </nav>

    <?php Messages::display(); ?>

    <div class="mi-perfil-pac">

        <div class="nav-mi-perfil">
            <a href="<?= BASE_URL ?>paciente/dashboard">Panel paciente</a>
            <span> • Mi perfil</span>
        </div>

        <h1 class="titulo-editar-perfil">Mi perfil</h1>

        <div class="caja-perfil-pac">
            <div class="cabecera-mi-perfil">
                <div class="mi-foto"><i class="fa-solid fa-user"></i></div>
                <div>
                    <div class="mi-nombre-pac">
                        <?= htmlspecialchars($paciente->nombre . ' ' . $paciente->apellido) ?>
                    </div>
                    <span class="rol-badge">Paciente</span>
                </div>
            </div>

            <div class="titulo-caja-pac">DATOS PERSONALES</div>
            <div class="dato-perfil">
                <span class="nombre-dato">Correo electrónico</span>
                <span class="valor-dato-pac"><?= htmlspecialchars($paciente->email) ?></span>
            </div>
            <div class="dato-perfil">
                <span class="nombre-dato">Teléfono</span>
                <span class="valor-dato-pac"><?= htmlspecialchars($paciente->telefono) ?></span>
            </div>
            <div class="dato-perfil">
                <span class="nombre-dato">Fecha de nacimiento</span>
                <span class="valor-dato-pac">
                    <?= !empty($paciente->fecha_nac) ? date('d/m/Y', strtotime($paciente->fecha_nac)) : '—' ?>
                </span>
            </div>
            <div class="dato-perfil">
                <span class="nombre-dato">Sexo</span>
                <span class="valor-dato-pac">
                    <?= ($paciente->sexo === 'M') ? 'Masculino' : 'Femenino' ?>
                </span>
            </div>
        </div>

        <div class="caja-perfil-pac">
            <div class="titulo-caja-pac">CAMBIAR CONTRASEÑA</div>

            <div class="aviso-datos">
                <i class="fa-solid fa-circle-info"></i>
                Deja los campos en blanco si no quieres cambiar tu contraseña.
            </div>

            <form method="POST" action="<?= BASE_URL ?>paciente/perfilPost">
                <div class="etiqueta-mi-campo">Contraseña actual</div>
                <input type="password" name="password_actual" class="mi-input"
                    placeholder="Introduce tu contraseña actual">

                <div class="etiqueta-mi-campo">Nueva contraseña</div>
                <input type="password" name="password_nueva" class="mi-input"
                    placeholder="Mínimo 8 caracteres">

                <div class="etiqueta-mi-campo">Confirmar nueva contraseña</div>
                <input type="password" name="password_nueva2" class="mi-input"
                    placeholder="Repite la nueva contraseña">
                <div class="descripcion-campo">La contraseña debe tener al menos 8 caracteres.</div>

                <div class="fila-mis-botones">
                    <button type="submit" class="guardar-mi-perfil">Guardar cambios</button>
                    <a href="<?= BASE_URL ?>paciente/dashboard" class="cancelar-mi-perfil">Cancelar</a>
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