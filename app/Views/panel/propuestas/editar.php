<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="card" style="max-width: 720px;">
    <div class="card-header">Editar propuesta</div>
    <div class="card-body">
        <form method="post" action="<?= site_url('panel/propuestas/' . $item['id'] . '/editar') ?>" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-6"><label class="form-label" for="p-titulo">Título</label><input id="p-titulo" name="titulo" class="form-control" required value="<?= esc((string) old('titulo', $item['titulo'])) ?>"></div>
            <div class="col-md-6"><label class="form-label" for="p-descripcion">Descripción</label><input id="p-descripcion" name="descripcion" class="form-control" required value="<?= esc((string) old('descripcion', $item['descripcion'])) ?>"></div>
            <div class="col-md-4">
                <label class="form-label" for="p-estado">Estado</label>
                <select id="p-estado" name="estado" class="form-select">
                    <?php foreach (['propuesta', 'en_votacion', 'aprobada', 'rechazada'] as $estado): ?>
                        <option value="<?= $estado ?>" <?= $item['estado'] === $estado ? 'selected' : '' ?>><?= ucfirst(str_replace('_', ' ', $estado)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="p-user">Usuario</label>
                <select id="p-user" name="user_id" class="form-select" required>
                    <?php foreach ($usuarios as $u): ?><option value="<?= esc((string) $u['id']) ?>" <?= (int) $item['user_id'] === (int) $u['id'] ? 'selected' : '' ?>><?= esc($u['nombre']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="p-cat">Categoría</label>
                <select id="p-cat" name="categoria_id" class="form-select" required>
                    <?php foreach ($categorias as $c): ?><option value="<?= esc((string) $c['id']) ?>" <?= (int) $item['categoria_id'] === (int) $c['id'] ? 'selected' : '' ?>><?= esc($c['nombre']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 d-flex gap-2">
                <button class="btn btn-brand">Guardar cambios</button>
                <a class="btn btn-outline-secondary" href="<?= site_url('panel/propuestas') ?>">Cancelar</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
