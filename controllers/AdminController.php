<?php
namespace controllers;

use helpers\Session;
use helpers\Validator;
use League\Plates\Engine;
use services\EmpresaService;
use services\MailService;

class AdminController
{
    private $templates;

    public function __construct(Engine $templates)
    {
        $this->templates = $templates;
    }

    public function adminPanel()
    {
        if (!(Session::isLogged() && Session::get('rol') === 1)) {
            header('Location: index.php');
            exit;
        }

        if (!isset($_GET['accion'])) {
            echo $this->templates->render('admin/panelAdmin');
            return;
        }

        $empresaService = new EmpresaService();
        $accion = $_GET['accion'];

        switch ($accion) {

            case 'panelAlumnos':
                echo $this->templates->render('admin/panelAdmin', ['seccion' => 'alumnos']);
                break;

            case 'panelEmpresas':

                $renderPanel = true; // Por defecto mostramos el panel

                // --- POST: agregar, editar, eliminar ---
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['opcion'])) {

                    $opcion = $_POST['opcion'];

                    switch ($opcion) {

                        case 'agregar':
                            $data = $_POST;

                            if (isset($data)) {
                                $validator = new Validator();
                                $errores = $validator->validarEmpresaAdmin($data);

                                if (empty($errores)) {
                                    // No hay errores -> guardar y redirigir
                                    if ($empresaService->registrarEmpresa($data) != null) {
                                        header('Location: index.php?menu=adminPanel&accion=panelEmpresas');
                                        exit;
                                    }
                                } else {
                                    // Sí hay errores -> mostrar el formulario de nuevo
                                    echo $this->formularioAgregarEmpresa($errores, $data);
                                    $renderPanel = false;
                                }
                            }
                            break;

                        case 'editar':
                            $empresaId = $_POST['empresa_id'] ?? null;
                            $data = $_POST;
                            if (isset($data)) {
                                if ($empresaId) {
                                    $empresa = $empresaService->getEmpresaById($empresaId);
                                    // Lógica para editar
                                    $validator = new Validator();
                                    $errores = $validator->validarEmpresaAdminEditar($data, $empresa->getUsuario()->getNombreUsuario());
                                    if (empty($errores)) {
                                        // No hay errores -> guardar y redirigir
                                        $empresaService->editarEmpresa($empresa->getId(), $data);
                                        $mailService = new MailService($this->templates);
                                        if ($data['verificada'] == 1) {
                                            $mailService->enviarCorreo(
                                                $empresa->getUsuario()->getNombreUsuario(),
                                                'Tu empresa ha sido desverificada',
                                                'mail/verificacion',
                                                [
                                                    'titulo' => 'Empresa Desverificada',
                                                    'nombre' => $empresa->getNombre(),
                                                    'mensaje' => 'Lamentablemente tu empresa ha sido desverificada y ya no puede acceder a la plataforma hasta que se resuelvan los problemas.',
                                                    'estado'=> 'desverificada',
                                                    'accionUrl' => 'localhost/index.php?menu=contacto',
                                                    'accionTexto' => 'Contactar con soporte'
                                                ]
                                            );
                                        } else {
                                            $mailService->enviarCorreo(
                                                $empresa->getUsuario()->getNombreUsuario(),
                                                'Tu empresa ha sido verificada',
                                                'mail/verificacion',
                                                [
                                                    'titulo' => 'Empresa Verificada',
                                                    'nombre' => $empresa->getNombre(),
                                                    'mensaje' => 'Tu empresa ha sido verificada y ahora puedes acceder a la plataforma con estas credenciales:',
                                                    'usuario' => $empresa->getUsuario()->getNombreUsuario(),
                                                    'password' => $empresa->getUsuario()->getPassword(),
                                                    'accionUrl' => 'localhost/index.php?menu=login',
                                                    'accionTexto' => 'Ver Mi Empresa'
                                                ]
                                            );
                                        }
                                        header('Location: index.php?menu=adminPanel&accion=panelEmpresas');
                                        exit;
                                    } else {
                                        // Sí hay errores -> mostrar el formulario de nuevo
                                        echo $this->formularioEditarEmpresa($empresa, $errores, $data);
                                        $renderPanel = false;
                                    }
                                }

                            }
                            break;


                        case 'eliminar':
                            $empresaId = $_POST['empresa_id'] ?? null;
                            if ($empresaId) {
                                $empresaService->eliminarEmpresa($empresaId);
                            }
                            header('Location: index.php?menu=adminPanel&accion=panelEmpresas');
                            exit;
                        case 'verificar':
                            $empresaId = $_POST['empresa_id'] ?? null;
                            if ($empresaId) {
                                $empresa = $empresaService->getEmpresaById($empresaId);
                                if ($empresa) {
                                    $data = [
                                        'verificada' => 1
                                    ];
                                    $empresaService->editarEmpresa($empresa->getId(), $data);
                                    $mailService = new MailService($this->templates);
                                    $mailService->enviarCorreo(
                                        $empresa->getUsuario()->getNombreUsuario(),
                                        'Tu empresa ha sido verificada',
                                        'mail/verificacion',
                                        [
                                            'titulo' => 'Empresa Verificada',
                                            'nombre' => $empresa->getNombre(),
                                            'mensaje' => 'Tu empresa ha sido verificada y ahora puedes acceder a la plataforma con estas credenciales:',
                                            'usuario' => $empresa->getUsuario()->getNombreUsuario(),
                                            'password' => $empresa->getUsuario()->getPassword(),
                                            'accionUrl' => 'localhost/index.php?menu=login',
                                            'accionTexto' => 'Ver Mi Empresa'
                                        ]
                                    );
                                }
                            }
                            header('Location: index.php?menu=adminPanel&accion=panelEmpresas');
                            exit;
                    }
                }

                // --- GET: ver ficha ---
                if (
                    $_SERVER['REQUEST_METHOD'] === 'GET'
                    && isset($_GET['opcion'])
                    && $_GET['opcion'] === 'ver'
                ) {

                    $empresaId = $_GET['empresa_id'] ?? null;

                    if ($empresaId) {
                        $empresa = $empresaService->getEmpresaById($empresaId);

                        echo $this->templates->render('admin/fichaEmpresa', [
                            'empresa' => $empresa
                        ]);

                        $renderPanel = false;
                    }
                }

                // --- GET: agregar ---
                if (
                    $_SERVER['REQUEST_METHOD'] === 'GET'
                    && isset($_GET['opcion'])
                    && $_GET['opcion'] === 'agregar'
                ) {
                    echo $this->formularioAgregarEmpresa();
                    $renderPanel = false;
                }

                // --- GET: editar ---
                if (
                    $_SERVER['REQUEST_METHOD'] === 'GET'
                    && isset($_GET['opcion'])
                    && $_GET['opcion'] === 'editar'
                ) {
                    $empresaId = $_GET['empresa_id'] ?? null;
                    if ($empresaId) {
                        $empresa = $empresaService->getEmpresaById($empresaId);
                        echo $this->formularioEditarEmpresa($empresa);
                        $renderPanel = false;
                    }
                }

                // --- Render del panel por defecto ---
                if ($renderPanel) {
                    echo $this->templates->render('admin/panelAdmin', ['seccion' => 'empresas']);
                }
                break;

            case 'panelSolicitudes':
                echo $this->templates->render('admin/panelAdmin', ['seccion' => 'solicitudes']);
                break;

            case 'panelOfertas':
                echo $this->templates->render('admin/panelAdmin', ['seccion' => 'ofertas']);
                break;

            default:
                echo $this->templates->render('admin/panelAdmin');
                break;
        }
    }

    private function formularioAgregarEmpresa($errores = [], $old = [])
    {
        return $this->templates->render('admin/agregarEmpresa', [
            'errores' => $errores,
            'old' => $old
        ]);
    }

    private function formularioEditarEmpresa($empresa, $errores = [], $old = [])
    {
        return $this->templates->render('admin/editarEmpresa', [
            'empresa' => $empresa,
            'errores' => $errores,
            'old' => $old
        ]);
    }
}
