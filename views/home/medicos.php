<?php
// solo son valores por defcto (las variables que sirven llegan via extract() desde los controladores)
$filtro = $filtro ?? ($_GET['especialidad'] ?? '');
$page = $page ?? (isset($_GET['page']) ? (int) $_GET['page'] : 1);
$totalPages = $totalPages ?? 1;
$medicos = $medicos ?? [];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCita - Médicos</title>
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

    <div class="contenido-medicos">

        <div class="col-filtros-med">

            <aside class="panel-filtros">
                <h6>ESPECIALIDAD</h6>
                <form method="GET" action="<?= BASE_URL ?>home/medicos" id="filtroForm">
                    <?php foreach (['Cardiología', 'Pediatría', 'Dermatología', 'Traumatología'] as $esp): ?>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="especialidad" value="<?= $esp ?>"
                                <?= ($filtro === $esp) ? 'checked' : '' ?> onchange="this.form.submit()">
                            <label class="form-check-label"><?= $esp ?></label>
                        </div>
                    <?php endforeach; ?>
                </form>
            </aside>

            <aside class="panel-filtros">
                <h6>DÍA DISPONIBLE</h6>
                <div>
                    <?php foreach (['L', 'M', 'X', 'J', 'V'] as $dia): ?>
                        <button type="button" class="btn-dia-sem"
                            onclick="this.classList.toggle('active')"><?= $dia ?></button>
                    <?php endforeach; ?>
                </div>
            </aside>

            <aside class="panel-filtros">
                <h6>FRANJA HORARIA</h6>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="manana">
                    <label class="form-check-label" for="manana">Mañana</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="tarde">
                    <label class="form-check-label" for="tarde">Tarde</label>
                </div>
            </aside>

            <a href="<?= BASE_URL ?>home/medicos" class="btn-limpiar">Limpiar filtros</a>

        </div>

        <div class="zona-tarjetas">
            <div class="cuadricula-medicos">
                <?php if (!empty($medicos)): ?>
                    <?php foreach ($medicos as $medico): ?>
                        <div class="tarjeta">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="foto">
                                    <i class="fa-solid fa-user-doctor"></i>
                                </div>
                                <div>
                                    <div class="nombre">
                                        <?= htmlspecialchars($medico->nombre . ' ' . $medico->apellido) ?>
                                    </div>
                                    <?php if (!empty($medico->especialidad)): ?>
                                        <span class="especialidad-badge"><?= htmlspecialchars($medico->especialidad) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <hr>
                            <?php if (!empty($medico->horario)): ?>
                                <p class="label-horario">HORARIO DISPONIBLE</p>
                                <div class="bloque-horario">
                                    <?php
                                    $partes = explode('|', $medico->horario);
                                    $dias = trim($partes[0] ?? $medico->horario);
                                    $horas = trim($partes[1] ?? '');
                                    ?>
                                    <span><?= htmlspecialchars($dias) ?></span>
                                    <?php if ($horas): ?>
                                        <div class="div-sep"></div>
                                        <span><?= htmlspecialchars($horas) ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <a href="<?= BASE_URL ?>paciente/buscarCitas" class="ver-medico">Ver citas</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted mt-2">No se encontraron médicos.</p>
                <?php endif; ?>
            </div>

            <?php if ($totalPages > 1): ?>
                <div class="paginas">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?><?= $filtro ? '&especialidad=' . urlencode($filtro) : '' ?>">&lt;</a>
                    <?php else: ?>
                        <span class="disabled">&lt;</span>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i === $page): ?>
                            <span class="active"><?= $i ?></span>
                        <?php else: ?>
                            <a href="?page=<?= $i ?><?= $filtro ? '&especialidad=' . urlencode($filtro) : '' ?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?= $page + 1 ?><?= $filtro ? '&especialidad=' . urlencode($filtro) : '' ?>">&gt;</a>
                    <?php else: ?>
                        <span class="disabled">&gt;</span>
                    <?php endif; ?>
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