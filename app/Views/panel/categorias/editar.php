<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>
<div class="card" style="max-width: 640px;">
    <div class="card-header">Editar categoría</div>
    <div class="card-body">
        <form method="post" action="<?= site_url('panel/categorias/' . $item['id'] . '/editar') ?>" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-6"><label class="form-label" for="c-nombre">Nombre</label><input id="c-nombre" name="nombre" class="form-control" required value="<?= esc((string) old('nombre', $item['nombre'])) ?>"></div>
            <div class="col-md-6"><label class="form-label" for="c-icono">Icono (Font Awesome)</label><input id="c-icono" name="icono" class="form-control" value="<?= esc((string) old('icono', $item['icono'])) ?>"></div>
            <div class="col-md-3"><label class="form-label" for="c-color">Color</label><input id="c-color" type="color" name="color" class="form-control form-control-color" value="<?= esc((string) old('color', $item['color'])) ?>"></div>
            <div class="col-md-9"><label class="form-label" for="c-descripcion">Descripción</label><input id="c-descripcion" name="descripcion" class="form-control" value="<?= esc((string) old('descripcion', (string) $item['descripcion'])) ?>"></div>
            <div class="col-12 d-flex gap-2">
                <button class="btn btn-brand">Guardar cambios</button>
                <a class="btn btn-outline-secondary" href="<?= site_url('panel/categorias') ?>">Cancelar</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
