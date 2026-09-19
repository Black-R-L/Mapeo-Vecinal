<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>

<div class="card mb-4">
    <div class="card-header">Registrar votación</div>
    <div class="card-body">
        <form method="post" action="<?= site_url('panel/votaciones') ?>" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-5">
                <label class="form-label" for="v-user">Usuario</label>
                <select id="v-user" name="user_id" class="form-select" required>
                    <option value="">Elegir...</option>
                    <?php foreach ($usuarios as $u): ?><option value="<?= esc((string) $u['id']) ?>"><?= esc($u['nombre']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label" for="v-propuesta">Propuesta</label>
                <select id="v-propuesta" name="propuesta_id" class="form-select" required>
                    <option value="">Elegir...</option>
                    <?php foreach ($propuestas as $p): ?><option value="<?= esc((string) $p['id']) ?>"><?= esc($p['titulo']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-1">
                <label class="form-label" for="v-tipo">Voto</label>
                <select id="v-tipo" name="tipo_voto" class="form-select">
                    <option value="favor">Favor</option>
                    <option value="contra">Contra</option>
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end"><button class="btn btn-brand w-100">Votar</button></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Listado (<?= count($votaciones) ?>)</div>
    <div class="card-body table-responsive p-0">
        <table class="table table-panel table-hover mb-0">
            <thead><tr><th>Usuario</th><th>Propuesta</th><th>Tipo</th><th>Fecha</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
            <?php foreach ($votaciones as $v): ?>
                <tr>
                    <td><?= esc($v['usuario']) ?></td>
                    <td><?= esc($v['propuesta']) ?></td>
                    <td><span class="badge <?= $v['tipo_voto'] === 'favor' ? 'text-bg-success' : 'text-bg-secondary' ?>"><?= esc($v['tipo_voto']) ?></span></td>
                    <td><?= esc((string) $v['fecha']) ?></td>
                    <td class="row-actions justify-content-end">
                        <form class="js-confirm" data-confirm="¿Eliminar este voto?" method="post" action="<?= site_url('panel/votaciones/' . $v['id'] . '/eliminar') ?>">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
