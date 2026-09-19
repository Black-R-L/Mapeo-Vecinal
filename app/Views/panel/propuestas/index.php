<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>

<div class="card mb-4">
    <div class="card-header">Crear propuesta</div>
    <div class="card-body">
        <form method="post" action="<?= site_url('panel/propuestas') ?>" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-4"><label class="form-label" for="p-titulo">Título</label><input id="p-titulo" name="titulo" class="form-control" required value="<?= esc((string) old('titulo')) ?>"></div>
            <div class="col-md-4"><label class="form-label" for="p-descripcion">Descripción</label><input id="p-descripcion" name="descripcion" class="form-control" required value="<?= esc((string) old('descripcion')) ?>"></div>
            <div class="col-md-2">
                <label class="form-label" for="p-estado">Estado</label>
                <select id="p-estado" name="estado" class="form-select">
                    <option value="propuesta">Propuesta</option>
                    <option value="en_votacion">En votación</option>
                    <option value="aprobada">Aprobada</option>
                    <option value="rechazada">Rechazada</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label" for="p-user">Usuario</label>
                <select id="p-user" name="user_id" class="form-select" required>
                    <option value="">Elegir...</option>
                    <?php foreach ($usuarios as $u): ?><option value="<?= esc((string) $u['id']) ?>"><?= esc($u['nombre']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label" for="p-cat">Categoría</label>
                <select id="p-cat" name="categoria_id" class="form-select" required>
                    <option value="">Elegir...</option>
                    <?php foreach ($categorias as $c): ?><option value="<?= esc((string) $c['id']) ?>"><?= esc($c['nombre']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end"><button class="btn btn-brand w-100">Guardar</button></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Listado (<?= count($propuestas) ?>)</div>
    <div class="card-body table-responsive p-0">
        <table class="table table-panel table-hover mb-0">
            <thead><tr><th>Título</th><th>Estado</th><th>Autor</th><th>Categoría</th><th>Votos</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
            <?php foreach ($propuestas as $p): ?>
                <tr>
                    <td><?= esc($p['titulo']) ?></td>
                    <td><span class="badge-estado <?= esc($p['estado']) ?>"><?= esc($p['estado']) ?></span></td>
                    <td><?= esc($p['usuario']) ?></td>
                    <td><span class="categoria-chip"><i class="fa-solid <?= esc($p['icono'] ?: 'fa-tag') ?>" style="color:<?= esc($p['color']) ?>" aria-hidden="true"></i><?= esc($p['categoria']) ?></span></td>
                    <td><?= esc((string) $p['votos_totales']) ?></td>
                    <td class="row-actions justify-content-end">
                        <a class="btn btn-sm btn-outline-brand" href="<?= site_url('panel/propuestas/' . $p['id'] . '/editar') ?>">Editar</a>
                        <form class="js-confirm" data-confirm="¿Eliminar la propuesta «<?= esc($p['titulo']) ?>»?" method="post" action="<?= site_url('panel/propuestas/' . $p['id'] . '/eliminar') ?>">
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
