<?php
use services\EmpresaService;
$this->layout('layouts/adminLayout', ['title' => 'Panel Admin'])

    ?>

<?php if ($seccion === 'alumnos'): ?>
    <?php $this->start('scripts') ?>
    <script src="/assets/js/admin/tablasAlumnoAdmin.js" defer></script>
    <script src="/assets/js/common/Modal.js" defer></script>
    <script src="/assets/js/common/provinciaLocalidad.js" defer></script>
    <script src="/assets/js/common/validacionesAlumno.js" defer></script>
    <script src="/assets/js/common/familiaCiclo.js" defer></script>
    <script src="/assets/js/admin/listadoAlumnosAdmin.js" defer></script>
    <?php $this->stop() ?>
<?php endif; ?>


<?php $this->start('contenido') ?>

<div class="adm-header-section">
    <h1 class="adm-title">Panel de Administración</h1>

    <?php if (isset($seccion)): ?>
        <h2 class="adm-subtitle">Sección: <?= $this->e($seccion) ?></h2>
    <?php else: ?>
        <p class="adm-subtitle">Selecciona una opción del menú</p>
    <?php endif; ?>
</div>

<div class="adm-content">
    <?php if (isset($seccion)): ?>

        <?php if ($seccion === 'alumnos'): ?>
            <section class="adm-section">
                <h3 class="adm-subtitle">Gestión de Alumnos</h3>

                <div id="listado">
                    <button id="add" class="button">Añadir alumno</button>
                    <button id="adds" class="button">Añadir varios</button>

                    <table id="tablaAlumno" class="adm-table editable ordenable">
                    </table>
                </div>
            </section>

        <?php elseif ($seccion === 'empresas'): ?>
            <section class="adm-section">
                <h3 class="adm-subtitle">Gestión de Empresas</h3>


                <form method='get' action='index.php'>
                    <input type='hidden' name='menu' value='adminPanel'>
                    <input type='hidden' name='accion' value='panelEmpresas'>
                    <input type='hidden' name='opcion' value='agregar'>
                    <button type='submit' class='button'>Agregar nueva empresa</button>
                </form>

                <?php
                $empresaService = new EmpresaService();
                $empresas = $empresaService->obtenerTodasEmpresas();

                if (!empty($empresas)) {
                    echo "
                <table class='adm-table'>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Verificada</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>";
                    foreach ($empresas as $empresa) {
                        echo "<tr>
                        <td>" . $this->e($empresa->getNombre()) . "</td>
                        <td>" . $this->e($empresa->getTelefono()) . "</td>
                        <td>" . $this->e($empresa->getDireccion()) . "</td>
                        <td>
                            <span style='color: " . ($empresa->getVerificada() ? 'green' : 'red') . "; font-weight: 600;'>
                                " . ($empresa->getVerificada() ? 'Sí' : 'No') . "
                            </span>
                        </td>
                        <td>
                            <div class='acciones'>";
                        if (!$empresa->getVerificada()) {
                            echo
                                "<form method='post' action='index.php?menu=adminPanel&accion=panelEmpresas'>
                                    <input type='hidden' name='empresa_id' value='" . $this->e($empresa->getId()) . "'>
                                    <input type='hidden' name='opcion' value='verificar'>
                                    <button type='submit' class='button verificarEmpresa' onclick=\"return confirm('¿Estás seguro de que deseas verificar esta empresa?')\">Verificar empresa</button>
                                </form>";
                        }

                        echo
                            "<form method='get' action='index.php'>
                                    <input type='hidden' name='menu' value='adminPanel'>
                                    <input type='hidden' name='accion' value='panelEmpresas'>
                                    <input type='hidden' name='empresa_id' value='" . $this->e($empresa->getId()) . "'>
                                    <input type='hidden' name='opcion' value='ver'>
                                    <button type='submit' class='button'>Ver ficha</button>
                                </form>
                                <form method='get' action='index.php'>
                                    <input type='hidden' name='menu' value='adminPanel'>
                                    <input type='hidden' name='accion' value='panelEmpresas'>
                                    <input type='hidden' name='empresa_id' value='" . $this->e($empresa->getId()) . "'>
                                    <input type='hidden' name='opcion' value='editar'>
                                    <button type='submit' class='button'>Editar</button>
                                </form>
                                <form method='post' action='index.php?menu=adminPanel&accion=panelEmpresas'>
                                    <input type='hidden' name='empresa_id' value='" . $this->e($empresa->getId()) . "'>
                                    <input type='hidden' name='opcion' value='eliminar'>
                                    <button type='submit' class='button eliminarEmpresa' onclick=\"return confirm('¿Estás seguro de que deseas eliminar esta empresa?')\">Eliminar</button>
                                </form>
                            </div>
                        </td>
                      </tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "
                <div class='adm-empty'>
                    <p>No hay empresas registradas.</p>
                </div>";
                }
                ?>
            </section>

        <?php elseif ($seccion === 'solicitudes'): ?>
            <div class="adm-section">
                <p>Gestión de solicitudes.</p>
            </div>

        <?php elseif ($seccion === 'ofertas'): ?>
            <div class="adm-section">
                <p>Gestión de ofertas.</p>
            </div>

        <?php endif; ?>

    <?php else: ?>
        <div class="adm-empty">
            <p>Selecciona una opción del menú lateral para comenzar</p>
        </div>
    <?php endif; ?>
</div>

<?php $this->stop() ?>