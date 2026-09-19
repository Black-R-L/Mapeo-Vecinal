<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2"><div class="stat-tile"><div class="stat-value"><?= esc((string) $stats['usuarios']) ?></div><div class="stat-label">Usuarios</div></div></div>
    <div class="col-6 col-md-4 col-lg-2"><div class="stat-tile"><div class="stat-value"><?= esc((string) $stats['categorias']) ?></div><div class="stat-label">Categorías</div></div></div>
    <div class="col-6 col-md-4 col-lg-2"><div class="stat-tile"><div class="stat-value"><?= esc((string) $stats['reportes']) ?></div><div class="stat-label">Reportes</div></div></div>
    <div class="col-6 col-md-4 col-lg-2"><div class="stat-tile"><div class="stat-value"><?= esc((string) $stats['propuestas']) ?></div><div class="stat-label">Propuestas</div></div></div>
    <div class="col-6 col-md-4 col-lg-2"><div class="stat-tile"><div class="stat-value"><?= esc((string) $stats['votaciones']) ?></div><div class="stat-label">Votaciones</div></div></div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">Reportes recientes</div>
            <div class="card-body table-responsive p-0">
                <table class="table table-panel table-hover mb-0">
                    <thead><tr><th>Título</th><th>Estado</th><th>Categoría</th></tr></thead>
                    <tbody>
                    <?php foreach ($reportesRecientes as $item): ?>
                        <tr>
                            <td><a href="<?= site_url('panel/reportes') ?>"><?= esc($item['titulo']) ?></a></td>
                            <td><span class="badge-estado <?= esc($item['estado']) ?>"><?= esc($item['estado']) ?></span></td>
                            <td><span class="categoria-chip"><i class="fa-solid <?= esc($item['icono'] ?: 'fa-tag') ?>" style="color:<?= esc($item['color']) ?>" aria-hidden="true"></i><?= esc($item['categoria']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($reportesRecientes === []): ?>
                        <tr><td colspan="3" class="text-muted text-center py-3">Todavía no hay reportes.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">Propuestas recientes</div>
            <div class="card-body table-responsive p-0">
                <table class="table table-panel table-hover mb-0">
                    <thead><tr><th>Título</th><th>Estado</th><th>Votos</th></tr></thead>
                    <tbody>
                    <?php foreach ($propuestasRecientes as $item): ?>
                        <tr>
                            <td><a href="<?= site_url('panel/propuestas') ?>"><?= esc($item['titulo']) ?></a></td>
                            <td><span class="badge-estado <?= esc($item['estado']) ?>"><?= esc($item['estado']) ?></span></td>
                            <td><?= esc((string) $item['votos_totales']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($propuestasRecientes === []): ?>
                        <tr><td colspan="3" class="text-muted text-center py-3">Todavía no hay propuestas.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
