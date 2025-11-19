<header>
    <a href="/" class="header-logo">
        <img src="/assets/imagenes/Principal.svg" alt="logo de la empresa">
    </a>

    <input type="checkbox" id="menu-toggle" class="menu-toggle">
    <label for="menu-toggle" class="hamburger">
        <span></span>
        <span></span>
        <span></span>
    </label>

    <nav class="header-nav">
        <ul class="header-menu">
        <?php if (helpers\Session::get('tipo') === 'admin'): ?>
            <li class="header-item"><a href="/index.php?menu=adminPanel&accion=panelSolicitudes" class="header-link">Solicitudes</a></li>
            <li class="header-item"><a href="/index.php?menu=adminPanel&accion=panelOfertas" class="header-link">Ofertas</a></li>
            <li class="header-item"><a href="/index.php?menu=notificaciones" class="header-link">Notificaciones</a></li>
        <?php else: ?>
            <li class="header-item"><a href="/index.php?menu=solicitudes" class="header-link">Solicitudes</a></li>
            <li class="header-item"><a href="/index.php?menu=ofertas" class="header-link">Ofertas</a></li>
            <li class="header-item"><a href="/index.php?menu=notificaciones" class="header-link">Notificaciones</a></li>
        <?php endif; ?>
        </ul>
    </nav>

    <div class="header-login">
        <?php if (helpers\Session::get('usuario_id')): ?>
            <?php
            // Determinar la foto basándose en el rol y perfil disponible
            $foto = 'storage/foto_perfil/default.png';

            if (helpers\Session::get('rol') !== 1 && isset($perfil) && method_exists($perfil, 'getFoto')) {
                $fotoTemp = $perfil->getFoto();
                if (!empty($fotoTemp)) {
                    $foto = $fotoTemp;
                }
            }
            ?>
            <div class="header-profile">
                <img src="/assets/api/api_imagen.php?file=<?= urlencode($foto) ?>" alt="Foto de perfil" class="profile-img"
                    id="profileMenuBtn">
                <div class="dropdown-menu" id="profileMenu">
                    <?php if (helpers\Session::get('rol') === 1): ?>
                        <a href="/index.php?menu=adminPanel">Panel Admin</a>
                    <?php else: ?>
                        <a href="/index.php?menu=perfil">Mi cuenta</a>
                    <?php endif; ?>
                    <a href="/index.php?menu=logout">Cerrar sesión</a>
                </div>
            </div>
        <?php else: ?>
            <a href="/index.php?menu=login" class="button button-login">Login</a>
        <?php endif; ?>
    </div>
</header>