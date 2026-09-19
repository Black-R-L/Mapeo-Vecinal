<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="h3 mb-3">Votaciones</h1>

<div class="card mb-4">
    <div class="card-header">Crear votacion</div>
    <div class="card-body">
        <form method="post" action="<?= site_url('/panel/votaciones/crear') ?>" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-5">
                <select name="user_id" class="form-select" required>
                    <option value="">Usuario</option>
                    <?php foreach ($usuarios as $u): ?><option value="<?= esc((string) $u['id']) ?>"><?= esc($u['nombre']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-5">
                <select name="propuesta_id" class="form-select" required>
                    <option value="">Propuesta</option>
                    <?php foreach ($propuestas as $p): ?><option value="<?= esc((string) $p['id']) ?>"><?= esc($p['titulo']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-1">
                <select name="tipo_voto" class="form-select">
                    <option value="favor">favor</option>
                    <option value="contra">contra</option>
                </select>
            </div>
            <div class="col-md-1"><button class="btn btn-primary w-100">Votar</button></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Listado</div>
    <div class="card-body table-responsive">
        <table class="table table-striped table-sm">
            <thead><tr><th>ID</th><th>Usuario</th><th>Propuesta</th><th>Tipo</th><th>Fecha</th></tr></thead>
            <tbody>
            <?php foreach ($votaciones as $v): ?>
                <tr>
                    <td><?= esc((string) $v['id']) ?></td>
                    <td><?= esc($v['usuario']) ?></td>
                    <td><?= esc($v['propuesta']) ?></td>
                    <td><?= esc($v['tipo_voto']) ?></td>
                    <td><?= esc($v['fecha']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

