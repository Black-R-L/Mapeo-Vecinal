<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="container" style="max-width: 420px;">
    <div class="card">
        <div class="card-body p-4">
            <h1 class="h4 mb-3 text-center">Iniciar sesión</h1>
            <form method="post" action="<?= site_url('login') ?>" novalidate>
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input id="email" type="email" name="email" class="form-control" required autofocus value="<?= esc((string) old('email')) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Contraseña</label>
                    <input id="password" type="password" name="password" class="form-control" required>
                </div>
                <button class="btn btn-brand w-100">Entrar</button>
            </form>
            <p class="text-center text-muted mt-3 mb-0">
                ¿No tenés cuenta? <a href="<?= site_url('registro') ?>">Creá una</a>
            </p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
