<?php
$this->layout('layouts/adminEmpresaLayout', ['title' => 'Ficha de Empresa']);
$this->start('scripts') ?>
<?php $this->stop() ?>

<?php $this->start('accionEmpresa') ?>
<section class="adm-section">
    <div class="adm-header-section">
        <h2 class="adm-title">Ficha de la Empresa</h2>
        <p class="adm-subtitle">Información detallada de la empresa</p>
    </div>



    <div class="adm-section">
        <h3>Datos de la Empresa</h3>

        <table class="adm-table">
            <tbody>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td><?= $this->e($empresa->getUsuario()->getNombreUsuario()) ?></td>
                </tr>
                <tr>
                    <td><strong>Nombre de la empresa:</strong></td>
                    <td><?= $this->e($empresa->getNombre()) ?></td>
                </tr>
                <tr>
                    <td><strong>Teléfono de la empresa:</strong></td>
                    <td><?= $this->e($empresa->getTelefono()) ?></td>
                </tr>
                <tr>
                    <td><strong>Dirección:</strong></td>
                    <td><?= $this->e($empresa->getDireccion()) ?></td>
                </tr>
                <tr>
                    <td><strong>Nombre contacto:</strong></td>
                    <td><?= $this->e($empresa->getNombrePersona()) ?></td>
                </tr>
                <tr>
                    <td><strong>Teléfono contacto:</strong></td>
                    <td><?= $this->e($empresa->getTelefonoPersona()) ?></td>
                </tr>
                <tr>
                    <td><strong>Logo:</strong></td>
                    <td>
                        <?php
                        $basePath = dirname($_SERVER['DOCUMENT_ROOT']);
                        $foto = $empresa->getFoto();

                        // Intentar varias extensiones si la que viene no existe
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
                            if (file_exists($ruta)) {
                                $rutaLogo = $ruta;
                                break;
                            }
                        }

                        if ($rutaLogo) {
                            $tipo = mime_content_type($rutaLogo);
                            $logoData = base64_encode(file_get_contents($rutaLogo));
                            echo '<img id="logo" src="data:' . $tipo . ';base64,' . $logoData . '" alt="Logo">';
                        } else {
                            echo '<span class="adm-empty">No hay logo disponible</span>';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>Verificada:</strong></td>
                    <td>
                        <span class="<?= $empresa->getVerificada() ? 'verificada' : 'no-verificada' ?>">
                            <?= $empresa->getVerificada() ? 'Sí' : 'No' ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><strong>Descripción:</strong></td>
                    <td><?= $this->e($empresa->getDescripcion()) ?></td>
                </tr>
            </tbody>
        </table>

    </div>
    <a href="index.php?menu=adminPanel&accion=panelEmpresas" class="adm-btn">Volver al panel de empresas</a>
</section>
<?php $this->stop() ?>