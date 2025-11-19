<?php $this->layout('layouts/ofertasAlumnoLayout', [
    'title' => 'Ofertas - EmplyOn',
    'metaDescription' => 'Conecta el talento con las mejores empresas'
]); ?>

<?php $this->start('ofertasAlumno') ?>
<section class="ofertas-alumnos-section">
    <h1>Ofertas Disponibles</h1>
    <div class="filtros">
        <form method="GET" action="index.php" class="filtros-form">
            <input type="hidden" name="menu" value="ofertas">

            <label for="ordenFecha">Ordenar por fecha:</label>
            <select name="ordenFecha" id="ordenFecha">
                <option value="asc" <?= ($filtros['ordenFecha'] ?? '') === 'asc' ? 'selected' : '' ?>>Más antiguas primero
                </option>
                <option value="desc" <?= ($filtros['ordenFecha'] ?? '') === 'desc' ? 'selected' : '' ?>>Más recientes
                    primero
                </option>
            </select>

            <label for="ciclo">Filtrar por ciclo:</label>
            <select name="ciclo" id="ciclo">
                <option value="">Todos los ciclos</option>
                <?php foreach ($ciclos as $ciclo): ?>
                    <option value="<?= $ciclo->getId() ?>"><?= htmlspecialchars($ciclo->getNombre()) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="button">Filtrar</button>
            <a href="index.php?menu=ofertas" class="button reset-button">Reset filtros</a>
        </form>
    </div>
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
                    <div class="card-actions">
                        <form method="GET" action="index.php">
                            <input type="hidden" name="menu" value="ofertas">
                            <input type="hidden" name="oferta_id" value="<?= $oferta->getId() ?>">
                            <input type="hidden" name="accion" value="verDetalles">
                            <button>Ver detalles</button>
                        </form>
                        <form method="POST" action="index.php?menu=ofertas">
                            <input type="hidden" name="oferta_id" value="<?= $oferta->getId() ?>">
                            <input type="hidden" name="ordenFecha" value="<?= $filtros['ordenFecha'] ?>">
                            <input type="hidden" name="ciclo" value="<?= $filtros['ciclo'] ?? '' ?>">

                            <?php if (isset($aplicadas[$oferta->getId()])): ?>
                                <input type="hidden" name="accion" value="desaplicar">
                                <input type="hidden" name="solicitud_id" value="<?= $aplicadas[$oferta->getId()] ?>">
                                <button type="submit">Desaplicar</button>
                            <?php else: ?>
                                <input type="hidden" name="accion"  value="solicitar">
                                <button type="submit" class='aplicar'>Aplicar</button>
                            <?php endif; ?>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>


<?php $this->end('ofertasAlumno') ?>