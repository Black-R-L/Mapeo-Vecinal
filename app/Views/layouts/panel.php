<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Panel') ?> · Panel · Mapeo Vecinal</title>
    <link rel="icon" href="<?= base_url('favicon.ico') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
<a href="#contenido" class="skip-link">Saltar al contenido</a>
<?php $usuario = session()->get('usuario'); ?>

<div class="panel-shell">
    <nav class="panel-sidebar" aria-label="Navegación del panel">
        <a class="brand" href="<?= site_url('panel') ?>"><i class="fa-solid fa-map-location-dot" aria-hidden="true"></i> Mapeo Vecinal</a>
        <div class="nav flex-column">
            <a class="nav-link<?= uri_string() === 'panel' ? ' active' : '' ?>" href="<?= site_url('panel') ?>"><i class="fa-solid fa-gauge me-2"></i>Dashboard</a>
            <?php if ($usuario && $usuario['rol'] === 'admin'): ?>
                <a class="nav-link<?= str_contains(uri_string(), 'panel/usuarios') ? ' active' : '' ?>" href="<?= site_url('panel/usuarios') ?>"><i class="fa-solid fa-users me-2"></i>Usuarios</a>
            <?php endif; ?>
            <a class="nav-link<?= str_contains(uri_string(), 'panel/categorias') ? ' active' : '' ?>" href="<?= site_url('panel/categorias') ?>"><i class="fa-solid fa-tags me-2"></i>Categorías</a>
            <a class="nav-link<?= str_contains(uri_string(), 'panel/reportes') ? ' active' : '' ?>" href="<?= site_url('panel/reportes') ?>"><i class="fa-solid fa-triangle-exclamation me-2"></i>Reportes</a>
            <a class="nav-link<?= str_contains(uri_string(), 'panel/propuestas') ? ' active' : '' ?>" href="<?= site_url('panel/propuestas') ?>"><i class="fa-solid fa-lightbulb me-2"></i>Propuestas</a>
            <a class="nav-link<?= str_contains(uri_string(), 'panel/votaciones') ? ' active' : '' ?>" href="<?= site_url('panel/votaciones') ?>"><i class="fa-solid fa-check-to-slot me-2"></i>Votaciones</a>
            <hr class="border-light opacity-25">
            <a class="nav-link" href="<?= site_url('/') ?>"><i class="fa-solid fa-arrow-left me-2"></i>Ver sitio público</a>
            <a class="nav-link" href="<?= site_url('logout') ?>"><i class="fa-solid fa-right-from-bracket me-2"></i>Cerrar sesión</a>
        </div>
    </nav>

    <div class="panel-main">
        <div class="panel-topbar">
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary panel-sidebar-toggle" aria-expanded="false" aria-label="Abrir menú del panel">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1 class="h4 mb-0"><?= esc($title ?? 'Panel') ?></h1>
            </div>
            <?php if ($usuario): ?>
                <span class="text-muted small"><?= esc($usuario['nombre']) ?> · <?= esc($usuario['rol']) ?></span>
            <?php endif; ?>
        </div>

        <main id="contenido">
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

            <?= $this->renderSection('content') ?>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/app.js') ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
