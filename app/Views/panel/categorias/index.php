<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>

<div class="card mb-4">
    <div class="card-header">Crear categoría</div>
    <div class="card-body">
        <form method="post" action="<?= site_url('panel/categorias') ?>" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-3"><label class="form-label" for="c-nombre">Nombre</label><input id="c-nombre" name="nombre" class="form-control" required value="<?= esc((string) old('nombre')) ?>"></div>
            <div class="col-md-3"><label class="form-label" for="c-icono">Icono (Font Awesome, ej. fa-road)</label><input id="c-icono" name="icono" class="form-control" placeholder="fa-road" value="<?= esc((string) old('icono')) ?>"></div>
            <div class="col-md-2"><label class="form-label" for="c-color">Color</label><input id="c-color" type="color" name="color" class="form-control form-control-color" value="<?= esc((string) old('color', '#1f7a5c')) ?>"></div>
            <div class="col-md-4"><label class="form-label" for="c-descripcion">Descripción</label><input id="c-descripcion" name="descripcion" class="form-control" value="<?= esc((string) old('descripcion')) ?>"></div>
            <div class="col-12"><button class="btn btn-brand">Guardar</button></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Listado (<?= count($categorias) ?>)</div>
    <div class="card-body table-responsive p-0">
        <table class="table table-panel table-hover mb-0">
            <thead><tr><th>Categoría</th><th>Descripción</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
            <?php foreach ($categorias as $c): ?>
                <tr>
                    <td><span class="categoria-chip"><i class="fa-solid <?= esc($c['icono'] ?: 'fa-tag') ?>" style="color:<?= esc($c['color']) ?>" aria-hidden="true"></i><?= esc($c['nombre']) ?></span></td>
                    <td class="text-muted"><?= esc((string) $c['descripcion']) ?></td>
                    <td class="row-actions justify-content-end">
                        <a class="btn btn-sm btn-outline-brand" href="<?= site_url('panel/categorias/' . $c['id'] . '/editar') ?>">Editar</a>
                        <form class="js-confirm" data-confirm="¿Eliminar la categoría <?= esc($c['nombre']) ?>?" method="post" action="<?= site_url('panel/categorias/' . $c['id'] . '/eliminar') ?>">
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
