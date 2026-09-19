<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="container" style="max-width: 720px;">
    <h1 class="h3 mb-3">Proponer una mejora</h1>
    <div class="card">
        <div class="card-body">
            <form method="post" action="<?= site_url('propuestas/nueva') ?>" novalidate>
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label" for="titulo">Título</label>
                    <input id="titulo" name="titulo" class="form-control" required minlength="5" value="<?= esc((string) old('titulo')) ?>" placeholder="Ej: Ciclovía en la avenida principal">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="form-control" required minlength="10" rows="4" placeholder="Explicá en qué consiste la propuesta y por qué beneficia al barrio"><?= esc((string) old('descripcion')) ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="categoria_id">Categoría</label>
                    <select id="categoria_id" name="categoria_id" class="form-select" required>
                        <option value="">Elegir...</option>
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?= esc((string) $c['id']) ?>"><?= esc($c['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button class="btn btn-brand">Publicar propuesta</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
