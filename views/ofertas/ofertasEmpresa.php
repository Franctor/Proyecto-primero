<?php $this->layout('layouts/ofertasEmpresaLayout', [
    'title' => 'Ofertas - EmplyOn',
    'metaDescription' => 'Conecta el talento con las mejores empresas'
]); ?>

<?php $this->start('ofertasEmpresa') ?>

<?php if (empty($ofertas)): ?>
    <p>No hay ofertas disponibles en este apartado.</p>

<?php else: ?>
    <div class="cards-grid">

        <?php foreach ($ofertas as $oferta): ?>
            <article class="oferta-card">
                <h2><?= $this->e($oferta->getTitulo()) ?></h2>

                <div class="card-body">
                    <p><strong>Descripción:</strong> <?= $this->e($oferta->getDescripcion()) ?></p>
                    <p><strong>Fecha inicio:</strong> <?= $this->e($oferta->getFechaInicio()->format('d/m/Y')) ?></p>
                    <p><strong>Fecha fin:</strong> <?= $this->e($oferta->getFechaFin()->format('d/m/Y')) ?></p>
                </div>

                <?php if ($accion === 'activas'): ?>
                    <div class="card-actions">
                        <form action="index.php?menu=ofertas" method="GET">
                            <input type="hidden" name="menu" value="ofertas">
                            <input type="hidden" name="accion" value="editar">
                            <input type="hidden" name="oferta_id" value="<?= $oferta->getId() ?>">
                            <button>Editar</button>
                        </form>

                        <form action="index.php?menu=ofertas" method="POST"
                            onsubmit="return confirm('¿Seguro que quieres eliminar esta oferta?');">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="oferta_id" value="<?= $oferta->getId() ?>">
                            <button>Eliminar</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="card-actions">
                        <form action="index.php?menu=ofertas" method="POST"
                            onsubmit="return confirm('¿Seguro que quieres eliminar esta oferta?');">
                            <input type="hidden" name="accion" value="eliminarPasada">
                            <input type="hidden" name="oferta_id" value="<?= $oferta->getId() ?>">
                            <button>Eliminar</button>
                        </form>
                    </div>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>

    </div>
    <?php if (!empty($ofertas) && $accion === 'pasadas'): ?>
        <form action="index.php?menu=ofertas" method="POST" class="eliminarTodas"
            onsubmit="return confirm('¿Seguro que quieres eliminar todas las ofertas pasadas?');">
            <input type="hidden" name="accion" value="eliminarPasadas">
            <button>Eliminar todas las ofertas pasadas</button>
        </form>
    <?php else: ?>
        <form action="index.php?menu=ofertas" method="POST" class="eliminarTodas"
            onsubmit="return confirm('¿Seguro que quieres eliminar todas las ofertas activas?');">
            <input type="hidden" name="accion" value="eliminarActivas">
            <button>Eliminar todas las ofertas activas</button>
        </form>
    <?php endif; ?>
<?php endif; ?>

<?php $this->end('ofertasEmpresa') ?>