<?= $this->extend('layouts/public') ?>

<?php $usuario = session()->get('usuario') ?>

<?= $this->section('content') ?>
<div class="hero">
    <div class="container">
        <?php if ($usuario): ?>
            <h1>Hola, <?= esc($usuario['nombre']) ?></h1>
            <p class="lead">Así está tu barrio hoy: <?= esc((string) $statsReportes['total']) ?> reportes activos en el mapa.</p>
        <?php else: ?>
            <h1>El mapa vivo de tu barrio</h1>
            <p class="lead">Reportá baches, luminarias rotas o basurales, seguí el estado de cada caso y votá las propuestas que más le importan a tu comunidad.</p>
            <a href="<?= site_url('registro') ?>" class="btn btn-light fw-semibold">Crear cuenta gratis</a>
            <span class="ms-2 small">¿Ya tenés cuenta? <a class="text-white text-decoration-underline" href="<?= site_url('login') ?>">Iniciá sesión</a></span>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3"><div class="stat-tile"><div class="stat-value"><?= esc((string) $statsReportes['total']) ?></div><div class="stat-label">Reportes totales</div></div></div>
        <div class="col-6 col-md-3"><div class="stat-tile"><div class="stat-value"><?= esc((string) $statsReportes['nuevo']) ?></div><div class="stat-label">Nuevos</div></div></div>
        <div class="col-6 col-md-3"><div class="stat-tile"><div class="stat-value"><?= esc((string) $statsReportes['en_progreso']) ?></div><div class="stat-label">En progreso</div></div></div>
        <div class="col-6 col-md-3"><div class="stat-tile"><div class="stat-value"><?= esc((string) $statsReportes['resuelto']) ?></div><div class="stat-label">Resueltos</div></div></div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="filter-bar mb-3">
                <select id="filtro-barrio" class="form-select form-select-sm" style="max-width: 200px;" data-centros="<?= esc(json_encode(array_reduce($barrios, static function ($acc, $b) {
                    $acc[$b['barrio']] = ['lat' => (float) $b['lat'], 'lng' => (float) $b['lng']];
                    return $acc;
                }, []))) ?>" aria-label="Ir a un barrio">
                    <option value="">📍 Todo el mapa</option>
                    <?php foreach ($barrios as $b): ?>
                        <option value="<?= esc($b['barrio']) ?>"><?= esc($b['barrio']) ?> (<?= esc((string) $b['total']) ?>)</option>
                    <?php endforeach; ?>
                </select>
                <button type="button" id="btn-cerca-de-mi" class="filter-chip" aria-pressed="false">
                    <i class="fa-solid fa-location-crosshairs" aria-hidden="true"></i> Cerca de mí
                </button>
            </div>

            <div class="filter-bar-group mb-2" role="group" aria-label="Filtrar por categoría">
                <?php foreach ($categorias as $c): ?>
                    <button type="button" class="filter-chip" data-valor="<?= esc((string) $c['id']) ?>" aria-pressed="true">
                        <i class="fa-solid <?= esc($c['icono'] ?: 'fa-tag') ?>" aria-hidden="true"></i> <?= esc($c['nombre']) ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="filter-bar-group mb-3" role="group" aria-label="Filtrar por estado">
                <button type="button" class="filter-chip" data-valor="nuevo" aria-pressed="true"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i> Nuevo</button>
                <button type="button" class="filter-chip" data-valor="en_progreso" aria-pressed="true"><i class="fa-solid fa-person-digging" aria-hidden="true"></i> En progreso</button>
                <button type="button" class="filter-chip" data-valor="resuelto" aria-pressed="true"><i class="fa-solid fa-circle-check" aria-hidden="true"></i> Resuelto</button>
            </div>

            <div id="mapa-home" class="map-container" role="img" aria-label="Mapa con los reportes del barrio"></div>
        </div>

        <div class="col-lg-4">
            <h2 class="h5 mb-3">Propuestas destacadas</h2>
            <?php foreach ($propuestasDestacadas as $p): ?>
                <div class="card mb-2">
                    <div class="card-body py-2">
                        <a class="fw-semibold text-decoration-none" href="<?= site_url('propuestas/' . $p['id']) ?>"><?= esc($p['titulo']) ?></a>
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <span class="categoria-chip"><i class="fa-solid <?= esc($p['icono'] ?: 'fa-tag') ?>" style="color:<?= esc($p['color']) ?>" aria-hidden="true"></i><?= esc($p['categoria_nombre']) ?></span>
                            <span class="text-muted small"><i class="fa-solid fa-thumbs-up" aria-hidden="true"></i> <?= esc((string) $p['votos_totales']) ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if ($propuestasDestacadas === []): ?>
                <p class="text-muted">Todavía no hay propuestas en votación.</p>
            <?php endif; ?>
            <?php if (! $usuario): ?>
                <a href="<?= site_url('propuestas/nueva') ?>" class="btn btn-outline-brand w-100 mt-2">+ Proponer una mejora</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    initMapaReportes('mapa-home', '<?= site_url('mapa/datos') ?>', {
        categoriaChips: document.querySelectorAll('[aria-label="Filtrar por categoría"] .filter-chip'),
        estadoChips: document.querySelectorAll('[aria-label="Filtrar por estado"] .filter-chip'),
        barrioSelect: document.getElementById('filtro-barrio'),
        cercaDeMiBtn: document.getElementById('btn-cerca-de-mi'),
    });
</script>
<?= $this->endSection() ?>
