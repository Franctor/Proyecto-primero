<?php
$accionActual = $_GET['accion'] ?? 'activas';
?>

<nav class="sidebar-nav">
    <h2 class="sidebar-title">Gestión de Ofertas</h2>
    <ul class="sidebar-menu">
        <li>
            <a href="index.php?menu=ofertas&accion=activas" class="<?= $accionActual === 'activas' ? 'active' : '' ?>">
                <span class="link-text">Ofertas Activas</span>
            </a>
        </li>
        <li>
            <a href="index.php?menu=ofertas&accion=pasadas" class="<?= $accionActual === 'pasadas' ? 'active' : '' ?>">
                <span class="link-text">Ofertas Pasadas</span>
            </a>
        </li>
        <li>
            <a href="index.php?menu=ofertas&accion=crear" class="<?= $accionActual === 'crear' ? 'active' : '' ?>">
                <span>Crear Nueva Oferta</span>
            </a>
        </li>
    </ul>
</nav>