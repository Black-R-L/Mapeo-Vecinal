<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="container" style="max-width: 720px;">
    <h1 class="h3 mb-3">Reportar un problema</h1>
    <div class="card">
        <div class="card-body">
            <form method="post" action="<?= site_url('reportes/nuevo') ?>" novalidate>
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label" for="titulo">Título</label>
                    <input id="titulo" name="titulo" class="form-control" required minlength="5" value="<?= esc((string) old('titulo')) ?>" placeholder="Ej: Bache grande en la esquina">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="form-control" required minlength="10" rows="3" placeholder="Contanos qué pasa y desde cuándo"><?= esc((string) old('descripcion')) ?></textarea>
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
                <div class="mb-3">
                    <label class="form-label" for="mapa-selector">Ubicación (opcional, hacé click en el mapa)</label>
                    <div id="mapa-selector" class="map-container map-picker"></div>
                    <div class="row g-2 mt-2">
                        <div class="col-6"><input id="latitud" name="latitud" class="form-control" placeholder="Latitud" readonly value="<?= esc((string) old('latitud')) ?>"></div>
                        <div class="col-6"><input id="longitud" name="longitud" class="form-control" placeholder="Longitud" readonly value="<?= esc((string) old('longitud')) ?>"></div>
                    </div>
                </div>
                <button class="btn btn-brand">Publicar reporte</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>initMapaSelector('mapa-selector', 'latitud', 'longitud');</script>
<?= $this->endSection() ?>
