<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Mapeo Vecinal') ?> · Mapeo Vecinal</title>
    <meta name="description" content="Reporta problemas, proponé mejoras y votá las prioridades de tu barrio.">
    <link rel="icon" href="<?= base_url('favicon.ico') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
    <link href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" rel="stylesheet">
    <link href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body>
<a href="#contenido" class="skip-link">Saltar al contenido</a>

<header class="topbar sticky-top">
    <nav class="navbar navbar-expand-lg container py-2">
        <a class="navbar-brand" href="<?= site_url('/') ?>">
            <i class="fa-solid fa-map-location-dot" aria-hidden="true"></i> Mapeo Vecinal
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navPublico" aria-controls="navPublico" aria-expanded="false" aria-label="Abrir menú">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navPublico">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="<?= site_url('/') ?>">Mapa</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('reportes') ?>">Reportes</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('propuestas') ?>">Propuestas</a></li>
            </ul>
            <ul class="navbar-nav align-items-lg-center gap-lg-2">
                <?php $usuario = session()->get('usuario'); ?>
                <?php if ($usuario): ?>
                    <?php if (in_array($usuario['rol'], ['admin', 'autoridad'], true)): ?>
                        <li class="nav-item"><a class="btn btn-outline-brand btn-sm" href="<?= site_url('panel') ?>">Panel</a></li>
                    <?php endif; ?>
                    <li class="nav-item dropdown">
                        <button class="btn btn-brand btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-plus" aria-hidden="true"></i> Participar
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= site_url('reportes/nuevo') ?>"><i class="fa-solid fa-map-pin me-2" aria-hidden="true"></i>Reportar un problema</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('propuestas/nueva') ?>"><i class="fa-solid fa-lightbulb me-2" aria-hidden="true"></i>Proponer una mejora</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= esc($usuario['nombre']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text text-muted small"><?= esc($usuario['barrio']) ?> · <?= esc($usuario['rol']) ?></span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= site_url('logout') ?>">Cerrar sesión</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= site_url('login') ?>">Iniciar sesión</a></li>
                    <li class="nav-item"><a class="btn btn-brand btn-sm" href="<?= site_url('registro') ?>">Crear cuenta</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</header>

<main id="contenido" class="pb-5">
    <div class="container pt-3">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success" role="alert"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <?php $errores = session()->getFlashdata('errors'); ?>
        <?php if (is_array($errores) && $errores !== []): ?>
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    <?php foreach ($errores as $error): ?>
                        <li><?= esc((string) $error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <?= $this->renderSection('content') ?>
</main>

<footer class="site-footer">
    <div class="container d-flex flex-wrap justify-content-between gap-2">
        <span>Mapeo Vecinal — construido con la comunidad, para la comunidad.</span>
        <span>Datos abiertos de tu municipio.</span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<script src="<?= base_url('assets/js/mapa.js') ?>"></script>
<script src="<?= base_url('assets/js/app.js') ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
