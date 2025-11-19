<?php $this->layout('layouts/ofertasEmpresaLayout', [
    'title' => 'Ofertas - EmplyOn',
    'metaDescription' => 'Conecta el talento con las mejores empresas'
]); ?>

<?php $this->start('scripts') ?>
<script defer src="/assets/js/common/familiaCiclo.js"></script>
<script defer src="/assets/js/ofertas/cargaFamiliaCiclos.js"></script>
<?php $this->stop() ?>

<?php $this->start('ofertasEmpresa') ?>

<h1>Editar Oferta</h1>

<form action="index.php?menu=ofertas" method="POST" class="oferta-form">
    <input type="hidden" name="oferta_id" value="<?= $this->e($old['oferta_id'] ?? '') ?>">
    <input type="hidden" name="accion" value="editar">

    <div class="form-group">
        <label for="titulo">Título de la Oferta:</label>
        <input type="text" id="titulo" name="titulo" 
            value="<?= $this->e($old['titulo'] ?? '') ?>"
            class="<?= !empty($errores['titulo'] ?? []) ? 'invalido' : '' ?>">
        <?php if (!empty($errores['titulo'] ?? [])): ?>
            <span class="error"><?= $this->e($errores['titulo'][0]) ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion"
            class="<?= !empty($errores['descripcion'] ?? []) ? 'invalido' : '' ?>"><?= $this->e($old['descripcion'] ?? '') ?></textarea>
        <?php if (!empty($errores['descripcion'] ?? [])): ?>
            <span class="error"><?= $this->e($errores['descripcion'][0]) ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="fecha_fiin_oferta">Fecha de Fin:</label>
        <input type="date" id="fecha_fiin_oferta" name="fecha_fiin_oferta" 
            value="<?= $this->e($old['fecha_fiin_oferta'] ?? '') ?>"
            class="<?= !empty($errores['fecha_fiin_oferta'] ?? []) ? 'invalido' : '' ?>">
        <?php if (!empty($errores['fecha_fiin_oferta'] ?? [])): ?>
            <span class="error"><?= $this->e($errores['fecha_fiin_oferta'][0]) ?></span>
        <?php endif; ?>
    </div>

    <div class="ciclos-row">
        <div class="form-group">
            <label for="familia">Familia profesional</label>
            <select name="familia" id="familia" class="<?= !empty($errores['familia'] ?? []) ? 'invalido' : '' ?>">
                <option value="">Selecciona una familia</option>
            </select>
        </div>

        <div class="form-group">
            <label for="ciclos">Ciclos disponibles</label>
            <select name="ciclos[]" id="ciclos" multiple
                class="<?= !empty($errores['ciclos'] ?? []) ? 'invalido' : '' ?>">
                <option value="">Selecciona un ciclo</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <div id="selectedCiclos">
            <label for="ciclosSeleccionados">Ciclos seleccionados</label>
            <select name="ciclosSeleccionados[]" id="ciclosSeleccionados" multiple>
                <?php if (!empty($old['ciclosSeleccionados'] ?? [])): ?>
                    <?php foreach ($old['ciclosSeleccionados'] as $cicloSel): ?>
                        <option value="<?= $this->e($cicloSel) ?>" selected><?= $this->e($cicloSel) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <?php if (!empty($errores['ciclosSeleccionados'] ?? [])): ?>
                <span class="error"><?= $this->e($errores['ciclosSeleccionados'][0]) ?></span>
            <?php endif; ?>
        </div>
    </div>

    <button type="submit" class="button">Guardar cambios</button>
</form>

<?php $this->end('ofertasEmpresa') ?>
