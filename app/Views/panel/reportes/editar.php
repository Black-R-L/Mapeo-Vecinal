<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="card" style="max-width: 720px;">
    <div class="card-header">Editar reporte</div>
    <div class="card-body">
        <form method="post" action="<?= site_url('panel/reportes/' . $item['id'] . '/editar') ?>" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-6"><label class="form-label" for="r-titulo">Título</label><input id="r-titulo" name="titulo" class="form-control" required value="<?= esc((string) old('titulo', $item['titulo'])) ?>"></div>
            <div class="col-md-6"><label class="form-label" for="r-descripcion">Descripción</label><input id="r-descripcion" name="descripcion" class="form-control" required value="<?= esc((string) old('descripcion', $item['descripcion'])) ?>"></div>
            <div class="col-md-3">
                <label class="form-label" for="r-estado">Estado</label>
                <select id="r-estado" name="estado" class="form-select">
                    <?php foreach (['nuevo', 'en_progreso', 'resuelto', 'rechazado'] as $estado): ?>
                        <option value="<?= $estado ?>" <?= $item['estado'] === $estado ? 'selected' : '' ?>><?= ucfirst(str_replace('_', ' ', $estado)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3"><label class="form-label" for="r-lat">Latitud</label><input id="r-lat" name="latitud" class="form-control" value="<?= esc((string) old('latitud', (string) $item['latitud'])) ?>"></div>
            <div class="col-md-3"><label class="form-label" for="r-lng">Longitud</label><input id="r-lng" name="longitud" class="form-control" value="<?= esc((string) old('longitud', (string) $item['longitud'])) ?>"></div>
            <div class="col-md-3">
                <label class="form-label" for="r-user">Usuario</label>
                <select id="r-user" name="user_id" class="form-select" required>
                    <?php foreach ($usuarios as $u): ?><option value="<?= esc((string) $u['id']) ?>" <?= (int) $item['user_id'] === (int) $u['id'] ? 'selected' : '' ?>><?= esc($u['nombre']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="r-cat">Categoría</label>
                <select id="r-cat" name="categoria_id" class="form-select" required>
                    <?php foreach ($categorias as $c): ?><option value="<?= esc((string) $c['id']) ?>" <?= (int) $item['categoria_id'] === (int) $c['id'] ? 'selected' : '' ?>><?= esc($c['nombre']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 d-flex gap-2">
                <button class="btn btn-brand">Guardar cambios</button>
                <a class="btn btn-outline-secondary" href="<?= site_url('panel/reportes') ?>">Cancelar</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
