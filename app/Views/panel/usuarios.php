<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="h3 mb-3">Usuarios</h1>

<div class="card mb-4">
    <div class="card-header">Crear usuario</div>
    <div class="card-body">
        <form method="post" action="<?= site_url('/panel/usuarios/crear') ?>" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-4"><input name="nombre" class="form-control" placeholder="Nombre" required value="<?= esc((string) old('nombre')) ?>"></div>
            <div class="col-md-4"><input type="email" name="email" class="form-control" placeholder="Email" required value="<?= esc((string) old('email')) ?>"></div>
            <div class="col-md-4"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
            <div class="col-md-3">
                <select name="rol" class="form-select" required>
                    <option value="ciudadano">ciudadano</option>
                    <option value="autoridad">autoridad</option>
                    <option value="admin">admin</option>
                </select>
            </div>
            <div class="col-md-3"><input name="barrio" class="form-control" placeholder="Barrio" required value="<?= esc((string) old('barrio')) ?>"></div>
            <div class="col-md-3">
                <select name="estado" class="form-select" required>
                    <option value="activo">activo</option>
                    <option value="inactivo">inactivo</option>
                </select>
            </div>
            <div class="col-md-3"><button class="btn btn-primary w-100">Guardar</button></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Listado</div>
    <div class="card-body table-responsive">
        <table class="table table-striped table-sm">
            <thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Barrio</th><th>Estado</th></tr></thead>
            <tbody>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= esc((string) $u['id']) ?></td>
                    <td><?= esc($u['nombre']) ?></td>
                    <td><?= esc($u['email']) ?></td>
                    <td><?= esc($u['rol']) ?></td>
                    <td><?= esc($u['barrio']) ?></td>
                    <td><?= esc($u['estado']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

