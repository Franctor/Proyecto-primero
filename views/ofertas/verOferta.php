<?php $this->layout('layouts/ofertasAlumnoLayout', [
    'title' => 'Ofertas - EmplyOn',
    'metaDescription' => 'Conecta el talento con las mejores empresas'
]); ?>


<?php $this->start('ofertasAlumno') ?>
<section class="ofertas-alumnos-section">
    <h1>Detalles de la oferta</h1>
    <div class="oferta-detalles-card">
        <h2><?= $this->e($oferta->getTitulo()) ?></h2>
        <div class="card-body">
            <p><strong>Descripción:</strong> <?= $this->e($oferta->getDescripcion()) ?></p>
            <p><strong>Fecha inicio:</strong> <?= $this->e($oferta->getFechaInicio()->format('d/m/Y')) ?></p>
            <p><strong>Fecha fin:</strong> <?= $this->e($oferta->getFechaFin()->format('d/m/Y')) ?></p>
        </div>
        <div class="card-actions">
            <a href="index.php?menu=ofertas" class="button">Volver a las ofertas</a>
        </div>
        
</section>


<?php $this->end('ofertasAlumno') ?>