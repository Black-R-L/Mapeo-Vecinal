<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="container" style="max-width: 760px;">
    <a href="<?= site_url('propuestas') ?>" class="text-decoration-none">&larr; Volver a propuestas</a>
    <div class="card mt-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2 flex-wrap gap-2">
                <span class="badge-estado <?= esc($propuesta['estado']) ?>"><?= esc($propuesta['estado']) ?></span>
                <span class="categoria-chip"><i class="fa-solid <?= esc($propuesta['icono'] ?: 'fa-tag') ?>" style="color:<?= esc($propuesta['color']) ?>" aria-hidden="true"></i><?= esc($propuesta['categoria']) ?></span>
            </div>
            <h1 class="h4"><?= esc($propuesta['titulo']) ?></h1>
            <p><?= esc($propuesta['descripcion']) ?></p>
            <p class="text-muted small">
                Propuesto por <?= esc($propuesta['autor']) ?> el <?= esc(date('d/m/Y', strtotime((string) $propuesta['created_at']))) ?>
            </p>

            <div class="mt-4">
                <div class="d-flex justify-content-between small text-muted mb-1">
                    <span><?= esc((string) $resumen['favor']) ?> a favor</span>
                    <span><?= esc((string) $resumen['contra']) ?> en contra</span>
                </div>
                <div class="vote-progress" role="progressbar" aria-valuenow="<?= esc((string) $resumen['porcentaje_favor']) ?>" aria-valuemin="0" aria-valuemax="100" aria-label="Porcentaje de votos a favor">
                    <div class="a-favor" style="width: <?= esc((string) $resumen['porcentaje_favor']) ?>%"></div>
                    <div class="en-contra" style="width: <?= esc((string) (100 - $resumen['porcentaje_favor'])) ?>%"></div>
                </div>
                <p class="text-muted small mt-1 mb-0"><?= esc((string) $resumen['total']) ?> voto(s) en total</p>
            </div>

            <div class="mt-4">
                <?php if (! session()->get('usuario')): ?>
                    <a href="<?= site_url('login') ?>" class="btn btn-outline-brand">Iniciá sesión para votar</a>
                <?php elseif ($miVoto): ?>
                    <p class="mb-0"><i class="fa-solid fa-check-circle text-success"></i> Ya votaste <strong><?= $miVoto['tipo_voto'] === 'favor' ? 'a favor' : 'en contra' ?></strong> de esta propuesta.</p>
                <?php else: ?>
                    <div class="d-flex gap-2">
                        <form method="post" action="<?= site_url('propuestas/' . $propuesta['id'] . '/votar') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="tipo_voto" value="favor">
                            <button class="btn btn-brand"><i class="fa-solid fa-thumbs-up me-1"></i> Votar a favor</button>
                        </form>
                        <form method="post" action="<?= site_url('propuestas/' . $propuesta['id'] . '/votar') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="tipo_voto" value="contra">
                            <button class="btn btn-outline-secondary"><i class="fa-solid fa-thumbs-down me-1"></i> Votar en contra</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
