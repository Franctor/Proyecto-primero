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
    <script defer src="/assets/js/common/Modal.js""></script>
    <script defer src=" /assets/js/common/foto.js"></script>
    <script defer src="/assets/js/common/provinciaLocalidad.js"></script>
    <script defer src="/assets/js/common/familiaCiclo.js"></script>
    <script defer src="/assets/js/common/validacionesAlumno.js"></script>
    <script defer src="/assets/js/auth/registerAlumno.js"></script>

    <?php $this->stop() ?>
<?php endif; ?>

<?php $this->start('login-register') ?>
<section class="register-section">
    <h1>Registro de <?= $this->e($tipo === 'alumno' ? 'Alumno' : 'Empresa') ?></h1>

    <form <?php if ($tipo === 'empresa'): ?> action="/index.php?menu=register&tipo=empresa" method="POST"
            enctype="multipart/form-data" <?php else: ?> id="formAlumno" <?php endif; ?> class="register-form">

        <div class="form-group">
            <label for="email">Correo electrónico</label>
            <input type="email" name="email" id="email">
        </div>

        <div class="form-group">
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
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="provincia">Provincia</label>
                <select name="provincia" id="provincia">
                    <option value="">Selecciona una provincia</option>
                </select>
            </div>

            <div class="form-group">
                <label for="localidad">Localidad</label>
                <select name="localidad" id="localidad">
                    <option value="">Selecciona una localidad</option>
                </select>
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
                <input type="text" name="nombre_empresa" id="nombre_empresa">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" name="telefono" id="telefono">
                </div>

                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" name="direccion" id="direccion">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="nombre_persona">Persona de contacto</label>
                    <input type="text" name="nombre_persona" id="nombre_persona">
                </div>

                <div class="form-group">
                    <label for="telefono_persona">Teléfono de contacto</label>
                    <input type="tel" name="telefono_persona" id="telefono_persona">
                </div>
            </div>

            <div class="form-group">
                <label for="logo">Logo de la empresa</label>
                <input type="file" name="logo" id="logo" accept="image/png, image/jpeg, image/webp">
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="3"></textarea>
            </div>
        <?php endif; ?>

        <button type="submit" class="button" id="submitBtn">Registrarse</button>
    </form>

    <p class="back-link">
        <a href="/index.php">Volver al inicio</a>
    </p>
</section>
<?php $this->stop() ?>