<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $this->e($metaDescription ?? 'Panel Administrador') ?>">

    <link rel="stylesheet" href="/assets/css/final.css">
    <link rel="icon" href="/assets/imagenes/favicon.ico" type="image/x-icon">
    
    <?= $this->section('styles') ?>
    <?= $this->section('scripts') ?>

    <title><?= $this->e($title ?? 'Panel Administrador') ?></title>
</head>

<body>
    <header class="adm-header">
        <?= $this->insert('partials/adminHeader') ?>
    </header>

    <div class="adm-container">
        <aside class="adm-sidebar">
            <?= $this->insert('partials/adminSidebar') ?>
        </aside>

        <main class="adm-main">
            <?= $this->section('contenido') ?>
        </main>
    </div>

</body>