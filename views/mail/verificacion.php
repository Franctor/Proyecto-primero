<?php
$colorHeader = match($estado ?? 'verificada') {
    'verificada' => '#2a9d8f',
    'desverificada' => '#e76f51',
    default => '#2a9d8f'
};
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?? 'Notificación' ?></title>
</head>
<body style="margin:0; padding:0; font-family: Arial, sans-serif; background-color:#f5f5f5;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f5f5; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 0 10px rgba(0,0,0,0.1);">

                    <!-- Header -->
                    <tr>
                        <td style="background-color:<?= $colorHeader ?>; color:#ffffff; text-align:center; padding:20px 0;">
                            <h1 style="margin:0; font-size:24px;"><?= $titulo ?? 'Notificación de Mi App' ?></h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:30px;">
                            <p style="font-size:16px; color:#333333;">Hola <strong><?= $nombre ?></strong>,</p>
                            <p style="font-size:16px; color:#333333; line-height:1.5;">
                                <?= $mensaje ?>
                            </p>

                            <?php if (!empty($accionUrl)): ?>
                                <p style="text-align:center; margin:30px 0;">
                                    <a href="<?= $accionUrl ?>" style="display:inline-block; background-color:<?= $colorHeader ?>; color:#ffffff; text-decoration:none; padding:12px 25px; border-radius:5px; font-weight:bold;">
                                        <?= $accionTexto ?? 'Acceder a Mi App' ?>
                                    </a>
                                </p>
                            <?php endif; ?>

                            <p style="font-size:14px; color:#999999; text-align:center; margin-top:40px;">
                                Este correo es automático, por favor no respondas a este mensaje.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color:#eeeeee; text-align:center; padding:15px; font-size:12px; color:#666666;">
                            &copy; <?= date('Y') ?> Mi App. Todos los derechos reservados.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
