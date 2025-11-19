<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ficha de Empresa - <?= $empresa->getNombre() ?></title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 30px;
            font-size: 13px;
            color: #333;
        }

        h1,
        h2,
        h3 {
            text-align: center;
            margin: 0;
            padding: 0;
        }

        h2 {
            font-size: 22px;
            margin-bottom: 5px;
        }

        p.sub {
            text-align: center;
            font-size: 13px;
            margin-bottom: 20px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        td {
            border: 1px solid #ccc;
            padding: 10px;
            vertical-align: top;
        }

        td strong {
            color: #222;
        }

        .logo {
            max-width: 180px;
            max-height: 120px;
        }

        .estado-verificada {
            color: green;
            font-weight: bold;
        }

        .estado-no-verificada {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <h2>Ficha de la Empresa</h2>
    <p class="sub">Información detallada de la empresa</p>

    <h3 style="margin-top: 30px; margin-bottom: 10px;">Datos de la Empresa</h3>

    <table>
        <tr>
            <td><strong>Email:</strong></td>
            <td><?= $empresa->getUsuario()->getNombreUsuario() ?></td>
        </tr>

        <tr>
            <td><strong>Nombre de la empresa:</strong></td>
            <td><?= $empresa->getNombre() ?></td>
        </tr>

        <tr>
            <td><strong>Teléfono de la empresa:</strong></td>
            <td><?= $empresa->getTelefono() ?></td>
        </tr>

        <tr>
            <td><strong>Dirección:</strong></td>
            <td><?= $empresa->getDireccion() ?></td>
        </tr>

        <tr>
            <td><strong>Nombre contacto:</strong></td>
            <td><?= $empresa->getNombrePersona() ?></td>
        </tr>

        <tr>
            <td><strong>Teléfono contacto:</strong></td>
            <td><?= $empresa->getTelefonoPersona() ?></td>
        </tr>

        <!-- Logo -->
        <tr>
            <td><strong>Logo:</strong></td>
            <td>
                <?php
                $basePath = dirname($_SERVER['DOCUMENT_ROOT']);
                $foto = $empresa->getFoto();

                $posibles = [
                    $foto,
                    str_replace('.png', '.webp', $foto),
                    str_replace('.png', '.jpg', $foto),
                    str_replace('.png', '.jpeg', $foto),
                    str_replace('.png', '.PNG', $foto),
                ];

                $rutaLogo = null;

                foreach ($posibles as $archivo) {
                    $ruta = $basePath . '/' . $archivo;
                    if (file_exists($ruta) && $rutaLogo === null) {
                        $rutaLogo = $ruta;
                    }
                }

                if ($rutaLogo) {
                    $tipo = mime_content_type($rutaLogo);
                    $logoData = base64_encode(file_get_contents($rutaLogo));
                    echo '<img class="logo" src="data:' . $tipo . ';base64,' . $logoData . '" alt="Logo">';
                } else {
                    echo '<i>No hay logo disponible</i>';
                }
                ?>
            </td>
        </tr>

        <tr>
            <td><strong>Verificada:</strong></td>
            <td>
                <?php if ($empresa->getVerificada()): ?>
                    <span class="estado-verificada">Sí</span>
                <?php else: ?>
                    <span class="estado-no-verificada">No</span>
                <?php endif; ?>
            </td>
        </tr>

        <tr>
            <td><strong>Descripción:</strong></td>
            <td><?= nl2br(htmlspecialchars($empresa->getDescripcion())) ?></td>
        </tr>

    </table>

</body>

</html>