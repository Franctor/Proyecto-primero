<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $this->e($metaDescription ?? 'Sitio web de ejemplo') ?>">
    <meta name="author" content="Francisco Castillo Torres">
    <link rel="shortcut icon" href="/assets/imagenes/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/assets/imagenes/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="/assets/css/final.css">
    <?= $this->section('scripts') ?>

    <title><?= $this->e($title ?? 'Mi sitio web') ?></title>
</head>

<body class="auth-page">
    <main>
        <?= $this->section('login-register') ?>
    </main>
</body>