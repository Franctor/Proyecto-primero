<?php $this->layout('layouts/adminLayout', ['title' => 'Panel Admin']) ?>

<?php if ($seccion === 'alumnos'): ?>
    <?php $this->start('scripts') ?>
        <script src="/assets/js/admin/tablasAlumnoAdmin.js" defer></script>
        <script src="/assets/js/common/Modal.js" defer></script>
        <script src="/assets/js/common/provinciaLocalidad.js" defer></script>
        <script src="/assets/js/common/validacionesAlumno.js" defer></script>
        <script src="/assets/js/common/familiaCiclo.js" defer></script>
        <script src="/assets/js/admin/listadoAlumnosAdmin.js" defer></script>
    <?php $this->stop() ?>
<?php endif; ?>


<?php $this->start('contenido') ?>

<div class="adm-header-section">
    <h1 class="adm-title">Panel de Administración</h1>

    <?php if (isset($seccion)): ?>
        <h2 class="adm-subtitle">Sección: <?= $this->e($seccion) ?></h2>
    <?php else: ?>
        <p class="adm-subtitle">Selecciona una opción del menú</p>
    <?php endif; ?>
</div>

<div class="adm-content">
    <?php if (isset($seccion)): ?>

        <?php if ($seccion === 'alumnos'): ?>
            <div class="adm-section">
                <h3 class="adm-subtitle">Gestión de Alumnos</h3>

                <section id="listado">
                    <button id="add" class="adm-btn">Añadir alumno</button>
                    <button id="adds" class="adm-btn">Añadir varios</button>

                    <table id="tablaAlumno" class="adm-table editable ordenable">
                    </table>
                </section>
            </div>

        <?php elseif ($seccion === 'empresas'): ?>
            <div class="adm-section"><p>Gestión de empresas.</p></div>

        <?php elseif ($seccion === 'solicitudes'): ?>
            <div class="adm-section"><p>Gestión de solicitudes.</p></div>

        <?php elseif ($seccion === 'ofertas'): ?>
            <div class="adm-section"><p>Gestión de ofertas.</p></div>

        <?php endif; ?>

    <?php else: ?>
        <div class="adm-empty">
            <p>Selecciona una opción del menú lateral para comenzar</p>
        </div>
    <?php endif; ?>
</div>

<?php $this->stop() ?>
