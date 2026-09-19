<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="h3 mb-3">Dashboard</h1>

<div class="row g-3 mb-4">
    <div class="col-md-2"><div class="card"><div class="card-body"><strong>Usuarios</strong><div><?= esc((string) $stats['usuarios']) ?></div></div></div></div>
    <div class="col-md-2"><div class="card"><div class="card-body"><strong>Categorias</strong><div><?= esc((string) $stats['categorias']) ?></div></div></div></div>
    <div class="col-md-2"><div class="card"><div class="card-body"><strong>Reportes</strong><div><?= esc((string) $stats['reportes']) ?></div></div></div></div>
    <div class="col-md-2"><div class="card"><div class="card-body"><strong>Propuestas</strong><div><?= esc((string) $stats['propuestas']) ?></div></div></div></div>
    <div class="col-md-2"><div class="card"><div class="card-body"><strong>Votaciones</strong><div><?= esc((string) $stats['votaciones']) ?></div></div></div></div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">Reportes recientes</div>
            <div class="card-body table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>ID</th><th>Titulo</th><th>Estado</th><th>Categoria</th></tr></thead>
                    <tbody>
                    <?php foreach ($reportesRecientes as $item): ?>
                        <tr>
                            <td><?= esc((string) $item['id']) ?></td>
                            <td><?= esc($item['titulo']) ?></td>
                            <td><?= esc($item['estado']) ?></td>
                            <td><?= esc($item['categoria']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">Propuestas recientes</div>
            <div class="card-body table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>ID</th><th>Titulo</th><th>Estado</th><th>Votos</th></tr></thead>
                    <tbody>
                    <?php foreach ($propuestasRecientes as $item): ?>
                        <tr>
                            <td><?= esc((string) $item['id']) ?></td>
                            <td><?= esc($item['titulo']) ?></td>
                            <td><?= esc($item['estado']) ?></td>
                            <td><?= esc((string) $item['votos_totales']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

