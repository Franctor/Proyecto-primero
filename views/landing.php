<?php $this->layout('layouts/main', [
    'title' => 'EmplyOn',
    'metaDescription' => 'Conecta el talento con las mejores empresas'
]); ?>

<?php $this->start('styles') ?>
<link rel="stylesheet" href="/assets/css/common/global.css">
<link rel="stylesheet" href="/assets/css/common/header.css">
<link rel="stylesheet" href="/assets/css/common/footer.css">
<link rel="stylesheet" href="/assets/css/home/landing.css">
<?php $this->stop() ?>


<section class="hero">
    <div class="hero-content">
        <h1 class="hero-title">Conecta el talento con las mejores empresas</h1>
        <p class="hero-subtitle">Tu futuro profesional comienza aquí. Regístrate y empieza a conectar
            oportunidades.</p>
        <div class="hero-buttons">
            <?php if (!$usuario): ?>
                <a href="/index.php?menu=register&tipo=alumno" class="button hero-button-alumno">Soy Alumno - Busca
                    Empleo</a>
                <a href="/index.php?menu=register&tipo=empresa" class="button hero-button-empresa">Soy Empresa - Publica una
                    Oferta</a>
            <?php else: ?>
                <div class="hero-buttons-logged">
                    <a href="/index.php?menu=solicitudes" class="button hero-button-alumno">Ver Solicitudes</a>
                    <a href="/index.php?menu=ofertas" class="button hero-button-empresa">Ver Ofertas</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- El resto de tu código se mantiene igual -->
<section class="empresas">
    <h2 class="section-title">Empresas que ya confían en nosotros</h2>
    <div class="empresas-grid">
        <article class="empresa-card">
            <div class="empresa-logo-container">
                <img src="/assets/imagenes/NTT Data.png" alt="Logo empresa NTT Data">
            </div>
            <h3>NTT Data</h3>
            <p>Especializada en servicios de consultoría tecnológica, integración de sistemas, y soluciones de negocio,
                con un enfoque en la transformación digital.</p>
        </article>
        <article class="empresa-card">
            <div class="empresa-logo-container">
                <img src="/assets/imagenes/Nter.png" alt="Logo empresa Nter">
            </div>
            <h3>Nter</h3>
            <p>Especializada en consultoría tecnológica que ofrece servicios en áreas como el desarrollo de software, la
                inteligencia artificial, el big data, la visualización de datos y las tecnologías emergentes como el
                Internet de las cosas y la robótica.</p>
        </article>
        <article class="empresa-card">
            <div class="empresa-logo-container">
                <img src="/assets/imagenes/AM System.png" alt="Logo empresa AM System">
            </div>
            <h3>AM System</h3>
            <p>AM System está especializada en el desarrollo e implantación de software de gestión empresarial en la
                nube para PYMES,</p>
        </article>
    </div>
    <div class="empresas-button">
        <a href="#" class="button">Ver más empresas</a>
    </div>
</section>

<section class="funcionamiento">
    <h2 class="section-title">¿Cómo funciona?</h2>
    <div class="pasos">
        <div class="paso">
            <span class="paso-numero">1</span>
            <div class="paso-contenido">
                <h3>Regístrate</h3>
                <p>Crea tu cuenta como alumno o empresa y completa tu perfil.</p>
            </div>
        </div>
        <div class="paso">
            <span class="paso-numero">2</span>
            <div class="paso-contenido">
                <h3>Crea tu perfil o publica ofertas</h3>
                <p>Sube tu CV o comparte las vacantes disponibles en tu empresa.</p>
            </div>
        </div>
        <div class="paso">
            <span class="paso-numero">3</span>
            <div class="paso-contenido">
                <h3>Conecta y encuentra oportunidades</h3>
                <p>Explora ofertas personalizadas y contacta con los mejores candidatos.</p>
            </div>
        </div>
    </div>
</section>

<section class="ventajas">
    <h2 class="section-title">Ventajas del portal</h2>
    <div class="ventajas-grid">
        <div class="ventaja-card">
            <h3>Para alumnos</h3>
            <ul>
                <li>Accede a ofertas adaptadas a tu perfil académico.</li>
                <li>Sube tu CV en formato PDF fácilmente.</li>
                <li>Recibe notificaciones de nuevas oportunidades.</li>
            </ul>
        </div>
        <div class="ventaja-card">
            <h3>Para empresas</h3>
            <ul>
                <li>Publica y gestiona tus ofertas en minutos.</li>
                <li>Consulta los CVs de los candidatos interesados.</li>
                <li>Obtén estadísticas de participación.</li>
            </ul>
        </div>
    </div>
</section>

<section class="contact">
    <h2 class="contact-title">Ponte en contacto con nosotros</h2>
    <p class="contact-text">¿Tienes dudas o sugerencias? Escríbenos y te ayudaremos lo antes posible.</p>
    <button aria-label="Ir a la página de contacto" class="button contact-button">Contacta</button>
</section>