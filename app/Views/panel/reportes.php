<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="h3 mb-3">Reportes</h1>

<div class="card mb-4">
    <div class="card-header">Crear reporte</div>
    <div class="card-body">
        <form method="post" action="<?= site_url('/panel/reportes/crear') ?>" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-4"><input name="titulo" class="form-control" placeholder="Titulo" required value="<?= esc((string) old('titulo')) ?>"></div>
            <div class="col-md-4"><input name="descripcion" class="form-control" placeholder="Descripcion" required value="<?= esc((string) old('descripcion')) ?>"></div>
            <div class="col-md-2">
                <select name="estado" class="form-select">
                    <option value="nuevo">nuevo</option>
                    <option value="en_progreso">en_progreso</option>
                    <option value="resuelto">resuelto</option>
                    <option value="rechazado">rechazado</option>
                </select>
            </div>
            <div class="col-md-2"><input name="latitud" class="form-control" placeholder="Latitud" value="<?= esc((string) old('latitud')) ?>"></div>
            <div class="col-md-2"><input name="longitud" class="form-control" placeholder="Longitud" value="<?= esc((string) old('longitud')) ?>"></div>
            <div class="col-md-5">
                <select name="user_id" class="form-select" required>
                    <option value="">Usuario</option>
                    <?php foreach ($usuarios as $u): ?><option value="<?= esc((string) $u['id']) ?>"><?= esc($u['nombre']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-5">
                <select name="categoria_id" class="form-select" required>
                    <option value="">Categoria</option>
                    <?php foreach ($categorias as $c): ?><option value="<?= esc((string) $c['id']) ?>"><?= esc($c['nombre']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-primary w-100">Guardar</button></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Listado</div>
    <div class="card-body table-responsive">
        <table class="table table-striped table-sm">
            <thead><tr><th>ID</th><th>Titulo</th><th>Estado</th><th>Usuario</th><th>Categoria</th><th>Votos</th></tr></thead>
            <tbody>
            <?php foreach ($reportes as $r): ?>
                <tr>
                    <td><?= esc((string) $r['id']) ?></td>
                    <td><?= esc($r['titulo']) ?></td>
                    <td><?= esc($r['estado']) ?></td>
                    <td><?= esc($r['usuario']) ?></td>
                    <td><?= esc($r['categoria']) ?></td>
                    <td><?= esc((string) $r['votos_totales']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

