<?php
$estados = ['nuevo' => 'fa-circle-exclamation', 'en_progreso' => 'fa-person-digging', 'resuelto' => 'fa-circle-check', 'rechazado' => 'fa-ban'];

$hrefFiltro = static function (string $campo, string $valor) use ($filtros) {
    $nuevos = $filtros;
    $nuevos[$campo] = ($filtros[$campo] === $valor) ? '' : $valor;

    return site_url('reportes') . '?' . http_build_query(array_filter($nuevos));
};
?>
<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1 class="h3 mb-3">Reportes del barrio</h1>

    <div class="filter-bar mb-2">
        <a href="<?= esc($hrefFiltro('barrio', '')) ?>" class="filter-chip" aria-pressed="<?= $filtros['barrio'] === '' ? 'true' : 'false' ?>">📍 Todo el mapa</a>
        <?php foreach ($barrios as $b): ?>
            <a href="<?= esc($hrefFiltro('barrio', $b['barrio'])) ?>" class="filter-chip" aria-pressed="<?= $filtros['barrio'] === $b['barrio'] ? 'true' : 'false' ?>"><?= esc($b['barrio']) ?> (<?= esc((string) $b['total']) ?>)</a>
        <?php endforeach; ?>
    </div>

    <div class="filter-bar-group mb-2" role="group" aria-label="Filtrar por categoría">
        <?php foreach ($categorias as $c): ?>
            <a href="<?= esc($hrefFiltro('categoria_id', (string) $c['id'])) ?>" class="filter-chip" aria-pressed="<?= $filtros['categoria_id'] === (string) $c['id'] ? 'true' : 'false' ?>">
                <i class="fa-solid <?= esc($c['icono'] ?: 'fa-tag') ?>" aria-hidden="true"></i> <?= esc($c['nombre']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="filter-bar-group mb-4" role="group" aria-label="Filtrar por estado">
        <?php foreach ($estados as $estado => $icono): ?>
            <a href="<?= esc($hrefFiltro('estado', $estado)) ?>" class="filter-chip" aria-pressed="<?= $filtros['estado'] === $estado ? 'true' : 'false' ?>">
                <i class="fa-solid <?= $icono ?>" aria-hidden="true"></i> <?= ucfirst(str_replace('_', ' ', $estado)) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="row g-3">
        <?php foreach ($reportes as $r): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge-estado <?= esc($r['estado']) ?>"><?= esc($r['estado']) ?></span>
                            <span class="categoria-chip"><i class="fa-solid <?= esc($r['icono'] ?: 'fa-tag') ?>" style="color:<?= esc($r['color']) ?>" aria-hidden="true"></i><?= esc($r['categoria_nombre']) ?></span>
                        </div>
                        <h2 class="h6"><a class="text-decoration-none" href="<?= site_url('reportes/' . $r['id']) ?>"><?= esc($r['titulo']) ?></a></h2>
                        <p class="text-muted small mb-0"><?= esc(mb_strimwidth($r['descripcion'], 0, 110, '…')) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if ($reportes === []): ?>
            <p class="text-muted">No hay reportes con esos filtros todavía.</p>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
