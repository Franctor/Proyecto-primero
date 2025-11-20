<?php $this->layout('layouts/solicitudesLayout', [
    'title' => 'Solicitudes - EmplyOn',
    'metaDescription' => 'Conecta el talento con las mejores empresas'
]); ?>

<?php $this->start('scripts') ?>
    <script src="/assets/js/solicitudes/solicitudesEmpresa.js" defer></script>
    <script src="assets/js/common/Modal.js" defer></script>
<?php $this->stop('scripts') ?>

<?php $this->start('solicitudes') ?>
<section class="solicitudes-empresa-section">
    <h1>Mis Solicitantes</h1>
    <div id="cards-grid">
    </div>
</section>
<?php $this->stop('solicitudes') ?>