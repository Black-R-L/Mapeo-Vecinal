<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="card" style="max-width: 640px;">
    <div class="card-header">Editar usuario</div>
    <div class="card-body">
        <form method="post" action="<?= site_url('panel/usuarios/' . $item['id'] . '/editar') ?>" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-6"><label class="form-label" for="u-nombre">Nombre</label><input id="u-nombre" name="nombre" class="form-control" required value="<?= esc((string) old('nombre', $item['nombre'])) ?>"></div>
            <div class="col-md-6"><label class="form-label" for="u-email">Email</label><input id="u-email" type="email" name="email" class="form-control" required value="<?= esc((string) old('email', $item['email'])) ?>"></div>
            <div class="col-md-4">
                <label class="form-label" for="u-rol">Rol</label>
                <select id="u-rol" name="rol" class="form-select" required>
                    <?php foreach (['ciudadano', 'autoridad', 'admin'] as $rol): ?>
                        <option value="<?= $rol ?>" <?= $item['rol'] === $rol ? 'selected' : '' ?>><?= ucfirst($rol) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4"><label class="form-label" for="u-barrio">Barrio</label><input id="u-barrio" name="barrio" class="form-control" required value="<?= esc((string) old('barrio', $item['barrio'])) ?>"></div>
            <div class="col-md-4">
                <label class="form-label" for="u-estado">Estado</label>
                <select id="u-estado" name="estado" class="form-select" required>
                    <?php foreach (['activo', 'inactivo'] as $estado): ?>
                        <option value="<?= $estado ?>" <?= $item['estado'] === $estado ? 'selected' : '' ?>><?= ucfirst($estado) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 d-flex gap-2">
                <button class="btn btn-brand">Guardar cambios</button>
                <a class="btn btn-outline-secondary" href="<?= site_url('panel/usuarios') ?>">Cancelar</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
