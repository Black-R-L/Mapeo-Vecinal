<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="container" style="max-width: 760px;">
    <a href="<?= site_url('reportes') ?>" class="text-decoration-none">&larr; Volver a reportes</a>
    <div class="card mt-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2 flex-wrap gap-2">
                <span class="badge-estado <?= esc($reporte['estado']) ?>"><?= esc($reporte['estado']) ?></span>
                <span class="categoria-chip"><i class="fa-solid <?= esc($reporte['icono'] ?: 'fa-tag') ?>" style="color:<?= esc($reporte['color']) ?>" aria-hidden="true"></i><?= esc($reporte['categoria']) ?></span>
            </div>
            <h1 class="h4"><?= esc($reporte['titulo']) ?></h1>
            <p><?= esc($reporte['descripcion']) ?></p>
            <p class="text-muted small mb-0">
                Reportado por <?= esc($reporte['autor']) ?> (<?= esc($reporte['barrio']) ?>) el
                <?= esc(date('d/m/Y', strtotime((string) $reporte['created_at']))) ?>
            </p>
        </div>
        <?php if ($reporte['latitud'] && $reporte['longitud']): ?>
            <div id="mapa-detalle" class="map-container" style="border-radius: 0;" role="img" aria-label="Ubicación del reporte en el mapa"></div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if ($reporte['latitud'] && $reporte['longitud']): ?>
<script>
    (function () {
        var mapa = L.map('mapa-detalle', { scrollWheelZoom: false }).setView([<?= (float) $reporte['latitud'] ?>, <?= (float) $reporte['longitud'] ?>], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; colaboradores de OpenStreetMap' }).addTo(mapa);
        L.marker([<?= (float) $reporte['latitud'] ?>, <?= (float) $reporte['longitud'] ?>]).addTo(mapa);
    })();
</script>
<?php endif; ?>
<?= $this->endSection() ?>
