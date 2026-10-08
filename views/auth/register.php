<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCita – Crear cuenta</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jura:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/app.css" rel="stylesheet">
</head>

<body class="pagina-registro">

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

    <div class="cuerpo-registro">
        <div class="caja-registro">

            <h1>Crear cuenta</h1>
            <?php Messages::display(); ?>

            <div class="selector-registro">
                <button type="button"
                    class="btn-tipo-registro <?= (!isset($role) || $role === 'paciente') ? 'active' : '' ?>"
                    id="btnPaciente" onclick="setRole('paciente')">Paciente</button>
                <button type="button"
                    class="btn-tipo-registro <?= (isset($role) && $role === 'medico') ? 'active' : '' ?>"
                    id="btnMedico" onclick="setRole('medico')">Médico</button>
            </div>

            <form action="<?= BASE_URL ?>auth/registerPost" method="POST" id="registerForm">
                <?= Csrf::field() ?>
                <input type="hidden" name="role" id="roleInput"
                    value="<?= htmlspecialchars($role ?? 'paciente') ?>">

                <div class="grid-registro">

                    <div class="grupo-registro">
                        <label>Nombre <span class="req">*</span></label>
                        <input type="text" name="nombre"
                            placeholder="Ej. Mario"
                            value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" required>
                    </div>

                    <div class="grupo-registro">
                        <label>Apellidos <span class="req">*</span></label>
                        <input type="text" name="apellido"
                            placeholder="Ej. Díaz González"
                            value="<?= htmlspecialchars($_POST['apellido'] ?? '') ?>" required>
                    </div>

                    <div class="grupo-registro">
                        <label>Teléfono <span class="req">*</span></label>
                        <input type="tel" name="telefono"
                            placeholder="Ej. 610 11 11 11"
                            value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>" required>
                    </div>

                    <div class="grupo-registro">
                        <label>Correo electrónico <span class="req">*</span></label>
                        <input type="email" name="email"
                            placeholder="Ej. mario@gmail.com"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>

                    <div class="grupo-registro">
                        <label>Fecha de nacimiento <span class="req">*</span></label>
                        <input type="date" name="fecha_nac"
                            value="<?= htmlspecialchars($_POST['fecha_nac'] ?? '') ?>" required>
                    </div>

                    <div class="grupo-registro">
                        <label>Sexo <span class="req">*</span></label>
                        <div class="select-contenedor">
                            <select name="sexo" required>
                                <option value="" disabled <?= empty($_POST['sexo']) ? 'selected' : '' ?>>Seleccionar...</option>
                                <option value="M" <?= (($_POST['sexo'] ?? '') === 'M') ? 'selected' : '' ?>>Masculino</option>
                                <option value="F" <?= (($_POST['sexo'] ?? '') === 'F') ? 'selected' : '' ?>>Femenino</option>
                                <option value="O" <?= (($_POST['sexo'] ?? '') === 'O') ? 'selected' : '' ?>>Otro</option>
                            </select>
                        </div>
                    </div>

                    <div class="grupo-registro">
                        <label>Contraseña <span class="req">*</span></label>
                        <input type="password" id="password" name="password"
                            placeholder="********" minlength="8" required>
                    </div>

                    <div class="grupo-registro">
                        <label>Repetir contraseña <span class="req">*</span></label>
                        <input type="password" id="password2" name="password2"
                            placeholder="********" minlength="8" required>
                    </div>

                </div>

                <div class="acciones-form">
                    <button type="submit" class="btn-crear-cuenta">Crear cuenta</button>
                    <a href="<?= BASE_URL ?>" class="btn-cancelar-reg">Cancelar</a>
                </div>
            </form>

            <p class="pie-registro">
                ¿Ya tienes cuenta? <a href="<?= BASE_URL ?>auth/login">Inicia sesión</a>
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

        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const p1 = document.getElementById('password').value;
            const p2 = document.getElementById('password2').value;
            if (p1 !== p2) {
                e.preventDefault();
                alert('Las contraseñas no coinciden.');
                document.getElementById('password2').focus();
            }
        });
    </script>

</body>

</html>