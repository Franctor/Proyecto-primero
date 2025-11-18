<?php
$this->layout('layouts/adminEmpresaLayout', ['title' => 'Editar Empresa']);
?>

<?php $this->start('accionEmpresa') ?>
<section class="adm-section">
    <h2 class="adm-title">Editar Empresa</h2>

    <?php if (!empty($errores['repetido'] ?? [])): ?>
        <div class="error">
            <?php foreach ($errores['repetido'] as $error): ?>
                <p><?= $this->e($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="index.php?menu=adminPanel&accion=panelEmpresas" method="POST">
        <input type="hidden" name="opcion" value="editar">
        <input type="hidden" name="empresa_id" value="<?= $empresa->getId() ?>">

        <div class="form-row">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email"
                    value="<?= $this->e($old['email'] ?? $empresa->getUsuario()->getNombreUsuario()) ?>"
                    class="<?= !empty($errores['email'] ?? []) ? 'invalido' : '' ?>">
                <?php if (!empty($errores['email'] ?? [])): ?>
                    <span class="error"><?= $this->e($errores['email'][0]) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="nombre">Nombre de la Empresa:</label>
                <input type="text" id="nombre" name="nombre"
                    value="<?= $this->e($old['nombre'] ?? $empresa->getNombre()) ?>"
                    class="<?= !empty($errores['nombre'] ?? []) ? 'invalido' : '' ?>">
                <?php if (!empty($errores['nombre'] ?? [])): ?>
                    <span class="error"><?= $this->e($errores['nombre'][0]) ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="telefono">Teléfono de la empresa:</label>
                <input type="text" id="telefono" name="telefono"
                    value="<?= $this->e($old['telefono'] ?? $empresa->getTelefono()) ?>"
                    class="<?= !empty($errores['telefono'] ?? []) ? 'invalido' : '' ?>">
                <?php if (!empty($errores['telefono'] ?? [])): ?>
                    <span class="error"><?= $this->e($errores['telefono'][0]) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion"
                    value="<?= $this->e($old['direccion'] ?? $empresa->getDireccion()) ?>"
                    class="<?= !empty($errores['direccion'] ?? []) ? 'invalido' : '' ?>">
                <?php if (!empty($errores['direccion'] ?? [])): ?>
                    <span class="error"><?= $this->e($errores['direccion'][0]) ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="nombre_persona">Nombre de la persona de contacto:</label>
                <input type="text" id="nombre_persona" name="nombre_persona"
                    value="<?= $this->e($old['nombre_persona'] ?? $empresa->getNombrePersona()) ?>"
                    class="<?= !empty($errores['nombre_persona'] ?? []) ? 'invalido' : '' ?>">
                <?php if (!empty($errores['nombre_persona'] ?? [])): ?>
                    <span class="error"><?= $this->e($errores['nombre_persona'][0]) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="telefono_persona">Teléfono de la persona de contacto:</label>
                <input type="text" id="telefono_persona" name="telefono_persona"
                    value="<?= $this->e($old['telefono_persona'] ?? $empresa->getTelefonoPersona()) ?>"
                    class="<?= !empty($errores['telefono_persona'] ?? []) ? 'invalido' : '' ?>">
                <?php if (!empty($errores['telefono_persona'] ?? [])): ?>
                    <span class="error"><?= $this->e($errores['telefono_persona'][0]) ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="verificada">¿Empresa verificada?</label>
                <select id="verificada" name="verificada"
                    class="<?= !empty($errores['verificada'] ?? []) ? 'invalido' : '' ?>">
                    <?php
                    $valorVerificada = $old['verificada'] ?? $empresa->getVerificada();
                    ?>
                    <option value="0" <?= $valorVerificada ? '' : 'selected' ?>>No</option>
                    <option value="1" <?= $valorVerificada ? 'selected' : '' ?>>Sí</option>
                </select>
            </div>
        </div>

        <button type="submit" class="button">Guardar Cambios</button>
    </form><br>

    <a href="index.php?menu=adminPanel&accion=panelEmpresas" class="button">Volver al panel de empresas</a>
</section>
<?php $this->stop() ?>