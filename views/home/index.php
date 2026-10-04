<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
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
            <?php if (isset($_SESSION['user_id'])): ?>
                <span style="font-size:14px;font-weight:bold;color:#1a1a2e;">
                    <?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>
                </span>
                <a href="<?= BASE_URL ?><?= $_SESSION['user_role'] ?>/dashboard" class="btn btn-entrar">Mi panel</a>
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-registrarse">Cerrar sesión</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>auth/login" class="btn btn-entrar">Iniciar sesión</a>
                <a href="<?= BASE_URL ?>auth/register" class="btn btn-registrarse">Registrarse</a>
            <?php endif; ?>
        </div>
    </nav>

    <?php Messages::display(); ?>

    <section class="seccion-hero">
        <h1>RESERVA TU CITA MÉDICA FÁCILMENTE</h1>
        <p>Encuentra tu médico, elige horario y confirma tu cita en minutos</p>
        <form class="caja-busqueda d-flex justify-content-center" method="GET" action="<?= BASE_URL ?>home/medicos">
            <input type="text" name="especialidad" placeholder="Busca por especialidad...">
            <button type="submit">Buscar</button>
        </form>
        <div class="mt-3">
            <a href="<?= BASE_URL ?>home/medicos?especialidad=Cardiología" class="tag-especialidad">Cardiología</a>
            <a href="<?= BASE_URL ?>home/medicos?especialidad=Pediatría" class="tag-especialidad">Pediatría</a>
            <a href="<?= BASE_URL ?>home/medicos?especialidad=Dermatología" class="tag-especialidad">Dermatología</a>
            <a href="<?= BASE_URL ?>home/medicos?especialidad=Traumatología" class="tag-especialidad">Traumatología</a>
        </div>
    </section>

    <div class="contenido-principal">
        <div class="columna-filtros">
            <aside class="filtros">
                <h6>ESPECIALIDAD</h6>
                <form method="GET" action="<?= BASE_URL ?>home/medicos">
                    <?php foreach (['Cardiología', 'Pediatría', 'Dermatología', 'Traumatología'] as $esp): ?>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox"
                                name="especialidad" value="<?= $esp ?>"
                                onchange="this.form.submit()">
                            <label class="form-check-label"><?= $esp ?></label>
                        </div>
                    <?php endforeach; ?>
                </form>
            </aside>

            <aside class="filtros">
                <h6>DÍA DISPONIBLE</h6>
                <div>
                    <?php foreach (['L', 'M', 'X', 'J', 'V'] as $dia): ?>
                        <button type="button" class="btn-dia"
                            onclick="this.classList.toggle('active')"><?= $dia ?></button>
                    <?php endforeach; ?>
                </div>
            </aside>
        </div>

        <div class="rejilla-medicos">
            <?php if (!empty($medicos)): ?>
                <?php foreach ($medicos as $medico): ?>
                    <div class="tarjeta-medico">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="foto-medico">
                                <i class="fa-solid fa-user-doctor"></i>
                            </div>
                            <div>
                                <div class="nombre-medico">
                                    <?= htmlspecialchars($medico->nombre . ' ' . $medico->apellido) ?>
                                </div>
                                <?php if (!empty($medico->especialidad)): ?>
                                    <span class="etiqueta-especialidad"><?= htmlspecialchars($medico->especialidad) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <hr>
                        <?php if (!empty($medico->horario)): ?>
                            <p class="texto-horario">HORARIO DISPONIBLE</p>
                            <div class="horario-disponible">
                                <?php
                                $partes = explode('|', $medico->horario);
                                $dias   = trim($partes[0] ?? $medico->horario);
                                $horas  = trim($partes[1] ?? '');
                                ?>
                                <span><?= htmlspecialchars($dias) ?></span>
                                <?php if ($horas): ?>
                                    <div class="linea-sep"></div>
                                    <span><?= htmlspecialchars($horas) ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <a href="<?= BASE_URL ?>home/medicos" class="btn-ver-perfil">Ver citas</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted mt-2">No se encontraron médicos.</p>
            <?php endif; ?>
        </div>
    </div>

    <section class="seccion-info">
        <div class="grid-info">
            <div class="item-info">
                <i class="fa-solid fa-magnifying-glass"></i>
                <h5>BUSCA TU MÉDICO</h5>
                <p>Filtra por especialidad</p>
            </div>
            <div class="item-info">
                <i class="fa-regular fa-calendar-days"></i>
                <h5>ELIGE TU CITA</h5>
                <p>Selecciona una fecha y hora</p>
            </div>
            <div class="item-info">
                <i class="fa-solid fa-check"></i>
                <h5>CONFIRMA</h5>
                <p>Solo si estás registrado</p>
            </div>
        </div>
    </section>

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