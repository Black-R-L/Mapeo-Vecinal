<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="h3 mb-3">Categorias</h1>

<div class="card mb-4">
    <div class="card-header">Crear categoria</div>
    <div class="card-body">
        <form method="post" action="<?= site_url('/panel/categorias/crear') ?>" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-3"><input name="nombre" class="form-control" placeholder="Nombre" required value="<?= esc((string) old('nombre')) ?>"></div>
            <div class="col-md-3"><input name="icono" class="form-control" placeholder="Icono (fa-road)" value="<?= esc((string) old('icono')) ?>"></div>
            <div class="col-md-2"><input name="color" class="form-control" value="<?= esc((string) old('color', '#3498db')) ?>"></div>
            <div class="col-md-4"><input name="descripcion" class="form-control" placeholder="Descripcion" value="<?= esc((string) old('descripcion')) ?>"></div>
            <div class="col-12"><button class="btn btn-primary">Guardar</button></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Listado</div>
    <div class="card-body table-responsive">
        <table class="table table-striped table-sm">
            <thead><tr><th>ID</th><th>Nombre</th><th>Icono</th><th>Color</th><th>Descripcion</th></tr></thead>
            <tbody>
            <?php foreach ($categorias as $c): ?>
                <tr>
                    <td><?= esc((string) $c['id']) ?></td>
                    <td><?= esc($c['nombre']) ?></td>
                    <td><?= esc($c['icono']) ?></td>
                    <td><span class="badge" style="background: <?= esc($c['color']) ?>"><?= esc($c['color']) ?></span></td>
                    <td><?= esc((string) $c['descripcion']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

