<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCita – Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jura:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link href="<?= BASE_URL ?>assets/css/app.css" rel="stylesheet">
</head>

<body class="pagina-login">

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
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>auth/login" class="btn btn-entrar">Iniciar sesión</a>
            <a href="<?= BASE_URL ?>auth/register" class="btn btn-registrarse">Registrarse</a>
        </div>
    </nav>

    <div class="cuerpo-login">
        <div class="caja-login">

            <h1>Iniciar sesión</h1>

            <?php Messages::display(); ?>

            <div class="selector-login">
                <button type="button" class="btn-tipo-login active" id="btnPaciente" onclick="setRole('paciente')">Paciente</button>
                <button type="button" class="btn-tipo-login" id="btnMedico" onclick="setRole('medico')">Médico</button>
            </div>

            <form action="<?= BASE_URL ?>auth/loginPost" method="POST">
                <?= Csrf::field() ?>
                <input type="hidden" name="role" id="roleInput" value="paciente">

                <div class="grupo-login">
                    <label>Teléfono o correo electrónico <span class="req">*</span></label>
                    <input type="text" name="identifier"
                        placeholder="Ej. 610 11 11 11 o mario@gmail.com"
                        value="<?= htmlspecialchars($_POST['identifier'] ?? '') ?>" required>
                </div>

                <div class="grupo-login">
                    <label>Contraseña <span class="req">*</span></label>
                    <input type="password" name="password" placeholder="********" required>
                </div>

                <button type="submit" class="btn-acceder">Iniciar sesión</button>
            </form>

            <p class="pie-login">
                ¿No tienes cuenta? <a href="<?= BASE_URL ?>auth/register">Regístrate aquí</a>
            </p>

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
        function setRole(role) {
            document.getElementById('roleInput').value = role;
            document.getElementById('btnPaciente').classList.toggle('active', role === 'paciente');
            document.getElementById('btnMedico').classList.toggle('active', role === 'medico');
        }
    </script>

</body>

</html>