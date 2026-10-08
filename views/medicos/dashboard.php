<?php
$nombreMedico = $_SESSION['user_name'] ?? 'Médico';
$citas = $citas ?? [];
$page = $page ?? 1;
$totalPages = $totalPages ?? 1;
$disponibles = $disponibles ?? 0;
$asignadas = $asignadas ?? 0;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCita - Panel médico</title>
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
            <form method="POST" action="<?= BASE_URL ?>auth/logout" class="form-en-linea">
                <?= Csrf::field() ?>
                <button type="submit" class="btn-salir">Cerrar sesión</button>
            </form>
        </div>
    </nav>

    <?php Messages::display(); ?>

    <div class="panel-medico">

        <div class="cabecera-dash">
            <h1 class="titulo-dash">Panel médico</h1>
            <div class="acciones-dash">
                <a href="<?= BASE_URL ?>medico/perfil" class="boton-perfil">
                    <i class="fa-solid fa-user"></i> Mi perfil
                </a>
                <a href="<?= BASE_URL ?>medico/crearCita" class="boton-nueva-cita">
                    <i class="fa-solid fa-plus"></i> Nueva cita
                </a>
            </div>
        </div>

        <div class="resumen-estadisticas">
            <div class="caja-estadistica">
                <div class="icono-est blue"><i class="fa-regular fa-calendar"></i></div>
                <div>
                    <div class="numero-est"><?= $disponibles + $asignadas ?></div>
                    <div class="descripcion-est">Citas totales</div>
                </div>
            </div>
            <div class="caja-estadistica">
                <div class="icono-est green"><i class="fa-solid fa-check"></i></div>
                <div>
                    <div class="numero-est"><?= $asignadas ?></div>
                    <div class="descripcion-est">Asignadas</div>
                </div>
            </div>
            <div class="caja-estadistica">
                <div class="icono-est gray"><i class="fa-regular fa-clock"></i></div>
                <div>
                    <div class="numero-est"><?= $disponibles ?></div>
                    <div class="descripcion-est">Disponibles</div>
                </div>
            </div>
        </div>

        <div class="tabla-citas-med">
            <div class="titulo-tabla-med">Mis citas</div>

            <?php if (!empty($citas)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>FECHA</th>
                            <th>HORA</th>
                            <th>LUGAR</th>
                            <th>PACIENTE</th>
                            <th>ESTADO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($citas as $cita): ?>
                            <tr>
                                <td style="color:#94a3b8">#<?= str_pad($cita->id, 4, '0', STR_PAD_LEFT) ?></td>
                                <td><?= !empty($cita->fecha) ? date('d/m/Y', strtotime($cita->fecha)) : '—' ?></td>
                                <td><?= !empty($cita->hora) ? date('H:i', strtotime($cita->hora)) . ' h' : '—' ?></td>
                                <td><?= htmlspecialchars($cita->lugar) ?></td>
                                <td>
                                    <?php if (!empty($cita->pac_nombre)): ?>
                                        <div class="celda-paciente">
                                            <div class="mini-foto"><i class="fa-solid fa-user"></i></div>
                                            <?= htmlspecialchars($cita->pac_nombre . ' ' . $cita->pac_apellido) ?>
                                        </div>
                                    <?php else: ?>
                                        <span style="color:#94a3b8">Sin asignar</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($cita->estado === 'asignada'): ?>
                                        <span class="confirmada">Asignada</span>
                                    <?php else: ?>
                                        <span class="disponible">Disponible</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="acciones-tabla">
                                        <a href="<?= BASE_URL ?>medico/detalleCita/<?= $cita->id ?>" class="ver-detalle">
                                            Ver
                                        </a>
                                        <?php if ($cita->estado === 'disponible'): ?>
                                            <form method="POST" action="<?= BASE_URL ?>medico/eliminarCita/<?= $cita->id ?>" class="form-en-linea"
                                                onsubmit="return confirm('¿Seguro que quieres eliminar esta cita?')">
                                                <?= Csrf::field() ?>
                                                <button type="submit" class="borrar-cita">Borrar</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php if ($totalPages > 1): ?>
                    <div class="paginador-med">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>">&lt;</a>
                        <?php else: ?>
                            <span class="disabled">&lt;</span>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <?php if ($i === $page): ?>
                                <span class="active"><?= $i ?></span>
                            <?php else: ?>
                                <a href="?page=<?= $i ?>"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?= $page + 1 ?>">&gt;</a>
                        <?php else: ?>
                            <span class="disabled">&gt;</span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="mensaje-vacio">
                    <i class="fa-regular fa-calendar-xmark"></i>
                    <p>No tienes citas creadas todavía.</p>
                    <a href="<?= BASE_URL ?>medico/crearCita" class="boton-nueva-cita" style="display:inline-flex">
                        <i class="fa-solid fa-plus"></i> Crear primera cita
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