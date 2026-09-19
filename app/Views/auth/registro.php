<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="container" style="max-width: 480px;">
    <div class="card">
        <div class="card-body p-4">
            <h1 class="h4 mb-3 text-center">Crear cuenta</h1>
            <p class="text-muted text-center small">Te registrás como ciudadano/a: podrás reportar problemas y votar propuestas.</p>
            <form method="post" action="<?= site_url('registro') ?>" novalidate>
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label" for="nombre">Nombre completo</label>
                    <input id="nombre" name="nombre" class="form-control" required autofocus value="<?= esc((string) old('nombre')) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input id="email" type="email" name="email" class="form-control" required value="<?= esc((string) old('email')) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="barrio">Barrio</label>
                    <input id="barrio" name="barrio" class="form-control" required value="<?= esc((string) old('barrio')) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Contraseña</label>
                    <input id="password" type="password" name="password" class="form-control" required minlength="6">
                    <div class="form-text">Mínimo 6 caracteres.</div>
                </div>
                <button class="btn btn-brand w-100">Crear cuenta</button>
            </form>
            <p class="text-center text-muted mt-3 mb-0">
                ¿Ya tenés cuenta? <a href="<?= site_url('login') ?>">Iniciá sesión</a>
            </p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
