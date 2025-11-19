<?php
namespace controllers;
use helpers\Session;
use helpers\Validator;
use League\Plates\Engine;
use services\EmpresaService;
use services\OfertaService;
class OfertaController
{
    private $templates;

    public function __construct(Engine $templates)
    {
        $this->templates = $templates;
    }
    public function ofertas()
    {
        if (!(Session::isLogged())) {
            header('Location: index.php?menu=login');
            exit;
        }
        if (Session::get('tipo') === 'empresa') {
            $renderPanel = true;
            $ofertaService = new OfertaService();

            // --- POST: crear oferta, editar y eliminar
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'])) {
                $accion = $_POST['accion'];
                switch ($accion) {
                    case 'crear':
                        $data = $_POST;
                        if (isset($data)) {
                            $validator = new Validator();
                            $errores = $validator->validarOferta($data);
                            if (empty($errores)) {
                                if ($ofertaService->crearOferta($data, Session::get('perfil')->getId())) {
                                    header('Location: index.php?menu=ofertas&opcion=activas');
                                    exit;
                                }
                            } else {
                                echo $this->formularioAgregarOferta($errores, $data);
                                $renderPanel = false;
                            }
                        }
                        break;
                    case 'editar':
                        $ofertaId = $_POST['oferta_id'] ?? null;
                        $data = $_POST;
                        if (isset($data)) {
                            if ($ofertaId) {
                                $validator = new Validator();
                                $errores = $validator->validarOferta($data);
                                if (empty($errores)) {
                                    $ofertaService->editarOferta($ofertaId, $data);
                                    header('Location: index.php?menu=ofertas&opcion=activas');
                                    exit;
                                } else {
                                    // Mostrar formulario con errores
                                    echo $this->templates->render('ofertas/editarOferta', [
                                        'errores' => $errores,
                                        'old' => $data
                                    ]);
                                    $renderPanel = false;
                                }
                            }
                        }
                        break;
                    case 'eliminar':
                        $ofertaId = $_POST['oferta_id'] ?? null;
                        if ($ofertaId) {
                            $oferta = $ofertaService->eliminarOferta($ofertaId);
                        }
                        header('Location: index.php?menu=ofertas&opcion=activas');
                        exit;
                }
            }

            // --- GET: agregar
            if (
                $_SERVER['REQUEST_METHOD'] === 'GET'
                && isset($_GET['accion'])
                && $_GET['accion'] === 'crear'
            ) {
                echo $this->formularioAgregarOferta();
                $renderPanel = false;
            }

            // --- GET: editar
            if (
                $_SERVER['REQUEST_METHOD'] === 'GET'
                && isset($_GET['accion'])
                && $_GET['accion'] === 'editar'
            ) {
                $ofertaId = $_GET['oferta_id'] ?? null;
                if ($ofertaId) {
                    $ofertaService = new OfertaService();
                    $oferta = $ofertaService->getOfertaById($ofertaId);
                    foreach ($oferta->getCiclos() as $ciclo) {
                        $old['ciclosSeleccionados'][] = $ciclo->getId();
                    }
                    
                    if ($oferta) {
                        echo $this->templates->render('ofertas/editarOferta', [
                            'old' => [
                                'oferta_id' => $oferta->getId(),
                                'titulo' => $oferta->getTitulo(),
                                'descripcion' => $oferta->getDescripcion(),
                                'fecha_fin_oferta' => $oferta->getFechaFin()->format('Y-m-d'),
                                'ciclosSeleccionados' => $old['ciclosSeleccionados'] ?? []
                            ]
                        ]);
                        $renderPanel = false;
                    }
                }
            }

            // --- GET: ofertas activas
            if (
                $_SERVER['REQUEST_METHOD'] === 'GET'
                && isset($_GET['accion'])
                && $_GET['accion'] === 'activas'
            ) {
                $empresaId = Session::get('perfil')->getId();
                $ofertasActivas = $ofertaService->getOfertasActivasByEmpresaId($empresaId);
                echo $this->templates->render('ofertas/ofertasEmpresa', [
                    'accion' => 'activas',
                    'ofertas' => $ofertasActivas
                ]);
                $renderPanel = false;
            }

            // --- GET: ofertas pasadas
            if (
                $_SERVER['REQUEST_METHOD'] === 'GET'
                && isset($_GET['accion'])
                && $_GET['accion'] === 'pasadas'
            ) {
                $empresaId = Session::get('perfil')->getId();
                $ofertasPasadas = $ofertaService->getOfertasPasadasByEmpresaId($empresaId);
                echo $this->templates->render('ofertas/ofertasEmpresa', [
                    'accion' => 'pasadas',
                    'ofertas' => $ofertasPasadas
                ]);
                $renderPanel = false;
            }


            if ($renderPanel) {
                $ofertasActivas = $ofertaService->getOfertasActivasByEmpresaId(Session::get('perfil')->getId());
                echo $this->templates->render('ofertas/ofertasEmpresa', ['accion' => 'activas', 'ofertas' => $ofertasActivas]);
            }
        } else if (Session::get('tipo') === 'alumno') {
            //Mostrar ofertas para alumno dependiendo de sus grados (no implementado)
        }

    }

    private function formularioAgregarOferta($errores = [], $old = [])
    {
        return $this->templates->render('ofertas/agregarOferta', [
            'errores' => $errores,
            'old' => $old
        ]);
    }
}