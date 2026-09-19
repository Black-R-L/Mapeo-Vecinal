<?php
$estados = ['propuesta' => 'fa-lightbulb', 'en_votacion' => 'fa-check-to-slot', 'aprobada' => 'fa-circle-check', 'rechazada' => 'fa-ban'];
?>
<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h1 class="h3 mb-0">Propuestas vecinales</h1>
        <?php if (! session()->get('usuario')): ?>
            <a href="<?= site_url('propuestas/nueva') ?>" class="btn btn-brand"><i class="fa-solid fa-lightbulb me-1" aria-hidden="true"></i> Proponer una mejora</a>
        <?php endif; ?>
    </div>

    <div class="filter-bar-group mb-4" role="group" aria-label="Filtrar por estado">
        <?php foreach ($estados as $e => $icono): ?>
            <a href="<?= site_url('propuestas') ?>?estado=<?= $estado === $e ? '' : $e ?>" class="filter-chip" aria-pressed="<?= $estado === $e ? 'true' : 'false' ?>">
                <i class="fa-solid <?= $icono ?>" aria-hidden="true"></i> <?= ucfirst(str_replace('_', ' ', $e)) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="row g-3">
        <?php foreach ($propuestas as $p): ?>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge-estado <?= esc($p['estado']) ?>"><?= esc($p['estado']) ?></span>
                            <span class="categoria-chip"><i class="fa-solid <?= esc($p['icono'] ?: 'fa-tag') ?>" style="color:<?= esc($p['color']) ?>" aria-hidden="true"></i><?= esc($p['categoria_nombre']) ?></span>
                        </div>
                        <h2 class="h6"><a class="text-decoration-none" href="<?= site_url('propuestas/' . $p['id']) ?>"><?= esc($p['titulo']) ?></a></h2>
                        <p class="text-muted small"><?= esc(mb_strimwidth($p['descripcion'], 0, 110, '…')) ?></p>
                        <span class="text-muted small"><i class="fa-solid fa-thumbs-up" aria-hidden="true"></i> <?= esc((string) $p['votos_totales']) ?> votos a favor</span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if ($propuestas === []): ?>
            <p class="text-muted">No hay propuestas con ese filtro todavía.</p>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
