<?php
$nombrePaciente = $_SESSION['user_name'] ?? 'Paciente';
$citas = $citas ?? [];
$paciente = $paciente ?? null;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCita - Mis citas</title>
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
            <a href="<?= BASE_URL ?>auth/logout" class="btn-salir">Cerrar sesión</a>
        </div>
    </nav>

    <?php Messages::display(); ?>

    <div class="dashboard-pac">

        <div class="cabecera-pac-dash">
            <h1 class="titulo-pac-dash">Mis citas</h1>
            <div class="d-flex gap-2">
                <a href="<?= BASE_URL ?>paciente/perfil" class="btn-mi-perfil-pac">
                    <i class="fa-solid fa-user"></i> Mi perfil
                </a>
                <a href="<?= BASE_URL ?>paciente/buscarCitas" class="btn-buscar-cita">
                    <i class="fa-solid fa-plus"></i> Buscar cita
                </a>
            </div>
        </div>

        <?php
        $total       = count($citas);
        $confirmadas = 0;
        $proximas    = 0;
        foreach ($citas as $c) {
            if ($c->estado === 'asignada') $confirmadas++;
            if (!empty($c->fecha) && strtotime($c->fecha) >= strtotime('today')) $proximas++;
        }
        ?>

        <div class="mis-estadisticas">
            <div class="caja-stat">
                <div class="icon-stat blue"><i class="fa-regular fa-calendar"></i></div>
                <div>
                    <div class="num-stat"><?= $total ?></div>
                    <div class="label-stat">Citas totales</div>
                </div>
            </div>
            <div class="caja-stat">
                <div class="icon-stat green"><i class="fa-solid fa-check"></i></div>
                <div>
                    <div class="num-stat"><?= $confirmadas ?></div>
                    <div class="label-stat">Confirmadas</div>
                </div>
            </div>
            <div class="caja-stat">
                <div class="icon-stat gray"><i class="fa-solid fa-clock"></i></div>
                <div>
                    <div class="num-stat"><?= $proximas ?></div>
                    <div class="label-stat">Próximas</div>
                </div>
            </div>
        </div>

        <div class="mis-citas-tabla">
            <div class="encabezado-mis-citas">Historial de citas</div>

            <?php if (!empty($citas)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>MÉDICO</th>
                            <th>FECHA</th>
                            <th>HORA</th>
                            <th>LUGAR</th>
                            <th>ESTADO</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($citas as $cita): ?>
                            <tr>
                                <td>
                                    <div class="info-medico-tabla">
                                        <div class="avatar-med-tabla">
                                            <i class="fa-solid fa-user-doctor"></i>
                                        </div>
                                        <div>
                                            <div class="nombre-med-tabla">
                                                <?= htmlspecialchars($cita->med_nombre . ' ' . $cita->med_apellido) ?>
                                            </div>
                                            <?php if (!empty($cita->especialidad)): ?>
                                                <div class="esp-med-tabla"><?= htmlspecialchars($cita->especialidad) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?= !empty($cita->fecha) ? date('d/m/Y', strtotime($cita->fecha)) : '—' ?>
                                </td>
                                <td>
                                    <?= !empty($cita->hora) ? date('H:i', strtotime($cita->hora)) . ' h' : '—' ?>
                                </td>
                                <td><?= htmlspecialchars($cita->lugar) ?></td>
                                <td>
                                    <?php if ($cita->estado === 'asignada'): ?>
                                        <span class="cita-confirmada">Confirmada</span>
                                    <?php else: ?>
                                        <span class="cita-libre">Disponible</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>paciente/detalle/<?= $cita->id ?>" class="ver-mi-cita">
                                        Ver detalle
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="sin-citas">
                    <i class="fa-regular fa-calendar-xmark"></i>
                    <p>No tienes citas reservadas todavía.</p>
                    <a href="<?= BASE_URL ?>paciente/buscarCitas" class="btn-buscar-cita" style="display:inline-flex">
                        <i class="fa-solid fa-plus"></i> Buscar una cita
                    </a>
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