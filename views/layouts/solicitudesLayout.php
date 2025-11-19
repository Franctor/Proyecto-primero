<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $this->e($metaDescription ?? 'EmplyOn') ?>">
    <meta name="author" content="Francisco Castillo Torres">
    <link rel="shortcut icon" href="/assets/imagenes/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/assets/imagenes/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="/assets/css/final.css">
    <?= $this->section('styles') ?>
    <script src="/assets/js/common/menuPerfil.js" defer></script>
    <?= $this->section('scripts') ?>

    <title><?= $this->e($title ?? 'EmplyOn') ?></title>
</head>

<body>
    <?= $this->insert('partials/header') ?>

    <main class="solicitudes-main">
        <?= $this->section('solicitudes') ?>
    </main>

    <?= $this->insert('partials/footer') ?>

</body>