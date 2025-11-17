<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $this->e($metaDescription ?? 'Gestión de empresas') ?>">

    <link rel="stylesheet" href="/assets/css/admin/admin.css">
    <link rel="stylesheet" href="/assets/css/common/global.css">
    <link rel="icon" href="/assets/imagenes/favicon.ico" type="image/x-icon">
    
    <?= $this->section('styles') ?>
    <?= $this->section('scripts') ?>

    <title><?= $this->e($title ?? 'Gestión de empresas') ?></title>
</head>

<body>
    <div class="adm-container">
        <main class="adm-main">
            <?= $this->section('accionEmpresa')?>
        </main> 
    </div>
</body>