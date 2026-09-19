<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Mapeo Vecinal') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= site_url('/') ?>">Mapeo Vecinal</a>
        <div class="navbar-nav">
            <a class="nav-link" href="<?= site_url('/') ?>">Dashboard</a>
            <a class="nav-link" href="<?= site_url('/panel/usuarios') ?>">Usuarios</a>
            <a class="nav-link" href="<?= site_url('/panel/categorias') ?>">Categorias</a>
            <a class="nav-link" href="<?= site_url('/panel/reportes') ?>">Reportes</a>
            <a class="nav-link" href="<?= site_url('/panel/propuestas') ?>">Propuestas</a>
            <a class="nav-link" href="<?= site_url('/panel/votaciones') ?>">Votaciones</a>
        </div>
    </div>
</nav>

<main class="container pb-5">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <?php $errors = session()->getFlashdata('errors'); ?>
    <?php if (is_array($errors) && $errors !== []): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= esc((string) $error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
</main>
</body>
</html>

