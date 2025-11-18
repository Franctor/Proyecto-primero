<?php $this->layout('layouts/authLayout', [
    'title' => 'Inicio de sesión',
    'metaDescription' => 'Formulario de inicio de sesión para alumnos y empresas',
]) ?>

<?php $this->start('login-register') ?>
<section class="login-section">
    <h1>Iniciar sesión</h1>

    <?php if (isset($_GET['registro']) && $_GET['registro'] === 'exito'): ?>
        <div class="alert success">
            <p>Cuenta registrada correctamente. Ya puedes iniciar sesión.</p>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert error">
            <p><?= htmlspecialchars($error) ?></p>
        </div>
    <?php endif; ?>

    <form action="/index.php?menu=login" method="POST" class="login-form">
        <div class="form-group">
            <label for="nombre_usuario">Correo electrónico</label>
            <input type="email" name="nombre_usuario" id="nombre_usuario" required>
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" required>
        </div>

        <button type="submit" class="button">Acceder</button>
    </form>

    <p class="register-link">
        ¿No tienes cuenta?
        <a href="/index.php?menu=register&tipo=alumno">Regístrate como alumno</a> o
        <a href="/index.php?menu=register&tipo=empresa">como empresa</a>.
    </p>

    <p class="back-link">
        <a href="/index.php">Volver al inicio</a>
    </p>
</section>
<?php $this->stop() ?>