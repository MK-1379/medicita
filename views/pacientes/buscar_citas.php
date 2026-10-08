<?php
$nombrePaciente = $_SESSION['user_name'] ?? 'Paciente';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCita - Buscar citas</title>
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

    <div class="busqueda-citas">

        <div class="encabezado-busqueda">
            <h1 class="titulo-busqueda">Citas disponibles</h1>
            <a href="<?= BASE_URL ?>paciente/dashboard" class="volver-inicio-pac">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </div>

        <div class="resultados-citas">
            <?php if (!empty($citas)): ?>
                <?php foreach ($citas as $cita): ?>
                    <div class="cita-disponible">
                        <div class="parte-arriba">
                            <div class="datos-medico">
                                <div class="foto-med-buscar">
                                    <i class="fa-solid fa-user-doctor"></i>
                                </div>
                                <div>
                                    <div class="nombre-med-buscar">
                                        <?= htmlspecialchars($cita->med_nombre . ' ' . $cita->med_apellido) ?>
                                    </div>
                                    <?php if (!empty($cita->especialidad)): ?>
                                        <span class="esp-med-buscar"><?= htmlspecialchars($cita->especialidad) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <span class="libre-badge">Disponible</span>
                        </div>

                        <hr class="linea-tarjeta">

                        <div class="dato-cita">
                            <i class="fa-regular fa-calendar"></i>
                            <?= !empty($cita->fecha) ? date('d/m/Y', strtotime($cita->fecha)) : '—' ?>
                        </div>
                        <div class="dato-cita">
                            <i class="fa-regular fa-clock"></i>
                            <?= !empty($cita->hora) ? date('H:i', strtotime($cita->hora)) . ' h' : '—' ?>
                        </div>
                        <div class="dato-cita">
                            <i class="fa-solid fa-location-dot"></i>
                            <?= htmlspecialchars($cita->lugar) ?>
                        </div>

                        <div class="pie-tarjeta">
                            <form method="POST" action="<?= BASE_URL ?>paciente/reservar/<?= $cita->id ?>">
                                <?= Csrf::field() ?>
                                <div class="form-seguro">
                                    <input type="text" name="aseguradora"
                                        class="seguro-input"
                                        placeholder="Tu aseguradora..." required>
                                    <button type="submit" class="reservar-cita">Reservar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="nada-encontrado">
                    <i class="fa-regular fa-calendar-xmark"></i>
                    <p>No hay citas disponibles en este momento.</p>
                </div>
            <?php endif; ?>
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