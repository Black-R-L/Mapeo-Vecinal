<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>

<div class="card mb-4">
    <div class="card-header">Crear usuario</div>
    <div class="card-body">
        <form method="post" action="<?= site_url('panel/usuarios') ?>" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-4"><label class="form-label" for="u-nombre">Nombre</label><input id="u-nombre" name="nombre" class="form-control" required value="<?= esc((string) old('nombre')) ?>"></div>
            <div class="col-md-4"><label class="form-label" for="u-email">Email</label><input id="u-email" type="email" name="email" class="form-control" required value="<?= esc((string) old('email')) ?>"></div>
            <div class="col-md-4"><label class="form-label" for="u-password">Contraseña</label><input id="u-password" type="password" name="password" class="form-control" required minlength="6"></div>
            <div class="col-md-3">
                <label class="form-label" for="u-rol">Rol</label>
                <select id="u-rol" name="rol" class="form-select" required>
                    <option value="ciudadano">Ciudadano</option>
                    <option value="autoridad">Autoridad</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="col-md-3"><label class="form-label" for="u-barrio">Barrio</label><input id="u-barrio" name="barrio" class="form-control" required value="<?= esc((string) old('barrio')) ?>"></div>
            <div class="col-md-3">
                <label class="form-label" for="u-estado">Estado</label>
                <select id="u-estado" name="estado" class="form-select" required>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end"><button class="btn btn-brand w-100">Guardar</button></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Listado (<?= count($usuarios) ?>)</div>
    <div class="card-body table-responsive p-0">
        <table class="table table-panel table-hover mb-0">
            <thead><tr><th>Nombre</th><th>Email</th><th>Rol</th><th>Barrio</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= esc($u['nombre']) ?></td>
                    <td><?= esc($u['email']) ?></td>
                    <td><?= esc($u['rol']) ?></td>
                    <td><?= esc($u['barrio']) ?></td>
                    <td><span class="badge <?= $u['estado'] === 'activo' ? 'text-bg-success' : 'text-bg-secondary' ?>"><?= esc($u['estado']) ?></span></td>
                    <td class="row-actions justify-content-end">
                        <a class="btn btn-sm btn-outline-brand" href="<?= site_url('panel/usuarios/' . $u['id'] . '/editar') ?>">Editar</a>
                        <form class="js-confirm" data-confirm="¿Desactivar a <?= esc($u['nombre']) ?>?" method="post" action="<?= site_url('panel/usuarios/' . $u['id'] . '/eliminar') ?>">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-outline-danger">Desactivar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
