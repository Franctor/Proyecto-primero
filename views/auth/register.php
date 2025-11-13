<?php $this->layout('layouts/authLayout', [
    'title' => 'Registro ' . ucfirst($tipo),
    'metaDescription' => 'Formulario de registro para ' . $tipo,
]) ?>

<?php $this->start('styles') ?>
<link rel="stylesheet" href="/assets/css/common/global.css">
<link rel="stylesheet" href="/assets/css/auth/register.css">
<?php $this->stop() ?>

<?php if ($tipo === 'alumno'): ?>
    <?php $this->start('scripts') ?>
    <script defer src="/assets/js/common/Modal.js"></script>
    <script defer src="/assets/js/common/foto.js"></script>
    <script defer src="/assets/js/common/provinciaLocalidad.js"></script>
    <script defer src="/assets/js/common/familiaCiclo.js"></script>
    <script defer src="/assets/js/common/validacionesAlumno.js"></script>
    <script defer src="/assets/js/auth/registerAlumno.js"></script>
    <?php $this->stop() ?>
<?php elseif ($tipo === 'empresa'): ?>
    <?php $this->start('scripts') ?>
    <script defer src="/assets/js/common/provinciaLocalidad.js"></script>
    <script defer src="/assets/js/auth/registerEmpresa.js"></script>
    <?php $this->stop() ?>
<?php endif; ?>

<?php $this->start('login-register') ?>
<section class="register-section">
    <h1>Registro de <?= $this->e($tipo === 'alumno' ? 'Alumno' : 'Empresa') ?></h1>
    <?php
    if (!empty(($errores['repetido'])) && empty($errores['telefono']) && empty($errores['email'])) {
        ?>
        <p class="error"><?= implode('<br>', $errores['repetido']) ?></p>
        <?php
    }
    ?>

    <form <?php if ($tipo === 'empresa'): ?> action="/index.php?menu=register&tipo=empresa" method="POST"
            enctype="multipart/form-data" <?php else: ?> id="formAlumno" <?php endif; ?> class="register-form">

        <div class="form-group">
            <label for="email">Correo electrónico</label>
            <input type="email" name="email" id="email" value="<?= $this->e($_POST['email'] ?? '') ?>">
            <?php if (!empty($errores['email'])): ?>
                <p class="error"><?= implode('<br>', $errores['email']) ?></p>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <?php
            if ($tipo === 'empresa') {
                $passwordValue = $_POST['password'] ?? '';
                $erroresPassword = $errores['password'] ?? [];

                if ($passwordValue === '') {
                    $requisitos = [
                        'caracteres' => null,
                        'mayus' => null,
                        'minus' => null,
                        'num' => null,
                        'especial' => null,
                    ];
                } else {
                    $requisitos = [
                        'caracteres' => !in_array('Debe tener al menos 8 caracteres', $erroresPassword),
                        'mayus' => !in_array('Debe contener al menos una letra mayúscula', $erroresPassword),
                        'minus' => !in_array('Debe contener al menos una letra minúscula', $erroresPassword),
                        'num' => !in_array('Debe contener al menos un número', $erroresPassword),
                        'especial' => !in_array('Debe contener al menos un carácter especial', $erroresPassword),
                    ];
                }

                ?>
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" value="<?= htmlspecialchars($passwordValue) ?>">
                <div class="password-requirements">
                    <ul>
                        <li id="caracteres"
                            class="<?= $requisitos['caracteres'] === null ? 'neutral' : ($requisitos['caracteres'] ? 'ok' : 'error') ?>">
                            Mínimo 8 caracteres</li>
                        <li id="mayus"
                            class="<?= $requisitos['mayus'] === null ? 'neutral' : ($requisitos['mayus'] ? 'ok' : 'error') ?>">
                            1 letra mayúscula</li>
                        <li id="minus"
                            class="<?= $requisitos['minus'] === null ? 'neutral' : ($requisitos['minus'] ? 'ok' : 'error') ?>">
                            1 letra minúscula</li>
                        <li id="num"
                            class="<?= $requisitos['num'] === null ? 'neutral' : ($requisitos['num'] ? 'ok' : 'error') ?>">1
                            número</li>
                        <li id="especial"
                            class="<?= $requisitos['especial'] === null ? 'neutral' : ($requisitos['especial'] ? 'ok' : 'error') ?>">
                            1 carácter especial</li>
                    </ul>
                </div>
                <?php
            } elseif ($tipo === 'alumno') {
                ?>

                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password">
                <div class="password-requirements">
                    <ul>
                        <li id="caracteres">Mínimo 8 caracteres</li>
                        <li id="mayus">1 letra mayúscula</li>
                        <li id="minus">1 letra minúscula</li>
                        <li id="num">1 número</li>
                        <li id="especial">1 carácter especial</li>
                    </ul>
                </div>
                <?php
            }
            ?>

        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="provincia">Provincia</label>
                <select name="provincia" id="provincia">
                    <option value="">Selecciona una provincia</option>
                </select>
                <?php if (!empty($errores['provincia'])): ?>
                    <p class="error"><?= implode('<br>', $errores['provincia']) ?></p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="localidad">Localidad</label>
                <select name="localidad" id="localidad">
                    <option value="">Selecciona una localidad</option>
                </select>
                <?php if (!empty($errores['localidad'])): ?>
                    <p class="error"><?= implode('<br>', $errores['localidad']) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($tipo === 'alumno'): ?>
            <div class="form-row">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre">
                </div>
                <div class="form-group">
                    <label for="apellido">Apellido/s</label>
                    <input type="text" name="apellido" id="apellido">
                </div>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="tel" name="telefono" id="telefono">
            </div>

            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" name="direccion" id="direccion">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="foto-perfil">Foto</label>
                    <input type="file" name="foto-perfil" id="foto-perfil" accept="image/png, image/jpeg, image/webp">
                    <button type="button" id="tomarFoto">Tomar foto</button>
                </div>

                <div class="form-group">
                    <label for="cv">Currículum (PDF)</label>
                    <input type="file" name="cv" id="cv" accept="application/pdf">
                </div>
            </div>

            <div class="form-group">
                <label for="familia">Familia profesional</label>
                <select name="familia" id="familia">
                    <option value="">Selecciona una familia</option>
                </select>
            </div>

            <div class="form-group">
                <label for="ciclos">Ciclos</label>
                <select name="ciclos[]" id="ciclos" multiple>
                    <option value="">Selecciona un ciclo</option>
                </select>
            </div>

            <div class="form-group">
                <div id="selectedCiclos">
                    <label for="ciclosSeleccionados">Ciclos seleccionados</label>
                    <select name="ciclosSeleccionados[]" id="ciclosSeleccionados" multiple></select>
                </div>
            </div>

        <?php elseif ($tipo === 'empresa'): ?>
            <div class="form-group">
                <label for="nombre_empresa">Nombre de la empresa</label>
                <input type="text" name="nombre_empresa" id="nombre_empresa"
                    value="<?= $this->e($_POST['nombre_empresa'] ?? '') ?>">
                <?php if (!empty($errores['nombre_empresa'])): ?>
                    <p class="error"><?= implode('<br>', $errores['nombre_empresa']) ?></p>
                <?php endif; ?>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" name="telefono" id="telefono" value="<?= $this->e($_POST['telefono'] ?? '') ?>">
                    <?php if (!empty($errores['telefono'])): ?>
                        <p class="error"><?= implode('<br>', $errores['telefono']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" name="direccion" id="direccion" value="<?= $this->e($_POST['direccion'] ?? '') ?>">
                    <?php if (!empty($errores['direccion'])): ?>
                        <p class="error"><?= implode('<br>', $errores['direccion']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="nombre_persona">Persona de contacto</label>
                    <input type="text" name="nombre_persona" id="nombre_persona"
                        value="<?= $this->e($_POST['nombre_persona'] ?? '') ?>">
                    <?php if (!empty($errores['nombre_persona'])): ?>
                        <p class="error"><?= implode('<br>', $errores['nombre_persona']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="telefono_persona">Teléfono de contacto</label>
                    <input type="tel" name="telefono_persona" id="telefono_persona"
                        value="<?= $this->e($_POST['telefono_persona'] ?? '') ?>">
                    <?php if (!empty($errores['telefono_persona'])): ?>
                        <p class="error"><?= implode('<br>', $errores['telefono_persona']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="logo">Logo de la empresa</label>
                <input type="file" name="logo" id="logo" accept="image/png, image/jpeg, image/webp">
                <?php if (!empty($errores['logo'])): ?>
                    <p class="error"><?= implode('<br>', $errores['logo']) ?></p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" id="descripcion"
                    rows="3"><?= $this->e($_POST['descripcion'] ?? '') ?></textarea>
                <?php if (!empty($errores['descripcion'])): ?>
                    <p class="error"><?= implode('<br>', $errores['descripcion']) ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <button type="submit" class="button" id="submitBtn">Registrarse</button>
    </form>

    <p class="back-link">
        <a href="/index.php">Volver al inicio</a>
    </p>
</section>
<?php $this->stop() ?>