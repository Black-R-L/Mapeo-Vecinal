<?= $this->extend('layouts/panel') ?>

<?= $this->section('content') ?>

<div class="card mb-4">
    <div class="card-header">Crear reporte</div>
    <div class="card-body">
        <form method="post" action="<?= site_url('panel/reportes') ?>" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-6"><label class="form-label" for="r-titulo">Título</label><input id="r-titulo" name="titulo" class="form-control" required value="<?= esc((string) old('titulo')) ?>"></div>
            <div class="col-md-6"><label class="form-label" for="r-descripcion">Descripción</label><input id="r-descripcion" name="descripcion" class="form-control" required value="<?= esc((string) old('descripcion')) ?>"></div>
            <div class="col-md-3">
                <label class="form-label" for="r-estado">Estado</label>
                <select id="r-estado" name="estado" class="form-select">
                    <option value="nuevo">Nuevo</option>
                    <option value="en_progreso">En progreso</option>
                    <option value="resuelto">Resuelto</option>
                    <option value="rechazado">Rechazado</option>
                </select>
            </div>
            <div class="col-md-3"><label class="form-label" for="r-lat">Latitud</label><input id="r-lat" name="latitud" class="form-control" value="<?= esc((string) old('latitud')) ?>"></div>
            <div class="col-md-3"><label class="form-label" for="r-lng">Longitud</label><input id="r-lng" name="longitud" class="form-control" value="<?= esc((string) old('longitud')) ?>"></div>
            <div class="col-md-3">
                <label class="form-label" for="r-user">Usuario</label>
                <select id="r-user" name="user_id" class="form-select" required>
                    <option value="">Elegir...</option>
                    <?php foreach ($usuarios as $u): ?><option value="<?= esc((string) $u['id']) ?>"><?= esc($u['nombre']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="r-cat">Categoría</label>
                <select id="r-cat" name="categoria_id" class="form-select" required>
                    <option value="">Elegir...</option>
                    <?php foreach ($categorias as $c): ?><option value="<?= esc((string) $c['id']) ?>"><?= esc($c['nombre']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 d-flex align-items-end"><button class="btn btn-brand w-100">Guardar</button></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Listado (<?= count($reportes) ?>)</div>
    <div class="card-body table-responsive p-0">
        <table class="table table-panel table-hover mb-0">
            <thead><tr><th>Título</th><th>Estado</th><th>Autor</th><th>Categoría</th><th>Votos</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
            <?php foreach ($reportes as $r): ?>
                <tr>
                    <td><?= esc($r['titulo']) ?></td>
                    <td><span class="badge-estado <?= esc($r['estado']) ?>"><?= esc($r['estado']) ?></span></td>
                    <td><?= esc($r['usuario']) ?></td>
                    <td><span class="categoria-chip"><i class="fa-solid <?= esc($r['icono'] ?: 'fa-tag') ?>" style="color:<?= esc($r['color']) ?>" aria-hidden="true"></i><?= esc($r['categoria']) ?></span></td>
                    <td><?= esc((string) $r['votos_totales']) ?></td>
                    <td class="row-actions justify-content-end">
                        <a class="btn btn-sm btn-outline-brand" href="<?= site_url('panel/reportes/' . $r['id'] . '/editar') ?>">Editar</a>
                        <form class="js-confirm" data-confirm="¿Eliminar el reporte «<?= esc($r['titulo']) ?>»?" method="post" action="<?= site_url('panel/reportes/' . $r['id'] . '/eliminar') ?>">
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
