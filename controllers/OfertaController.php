<?php
namespace controllers;
use helpers\Session;
use helpers\Validator;
use League\Plates\Engine;
use services\OfertaService;
use services\SolicitudService;
use services\CicloService;
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
            
        }
        if (Session::get('tipo') === 'empresa') {
            $this->manejoEmpresa();
        } else if (Session::get('tipo') === 'alumno') {
            $this->manejoAlumno();
        }

    }

    private function formularioAgregarOferta($errores = [], $old = [])
    {
        return $this->templates->render('ofertas/agregarOferta', [
            'errores' => $errores,
            'old' => $old
        ]);
    }

    private function manejoEmpresa()
    {
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
                                header('Location: index.php?menu=ofertas&accion=activas');
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
                                header('Location: index.php?menu=ofertas&accion=activas');
                                
                            } else {
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
                    $renderPanel = false;
                    header('Location: index.php?menu=ofertas&accion=activas');
                    break;
                case 'eliminarPasada':
                    $ofertaId = $_POST['oferta_id'] ?? null;
                    if ($ofertaId) {
                        $oferta = $ofertaService->eliminarOferta($ofertaId);
                    }
                    $renderPanel = false;
                    header('Location: index.php?menu=ofertas&accion=pasadas');
                    break;
                case 'eliminarPasadas':
                    $empresaId = Session::get('perfil')->getId();
                    if ($empresaId) {
                        $oferta = $ofertaService->eliminarOfertasPasadas($empresaId);
                    }
                    $renderPanel = false;
                    header('Location: index.php?menu=ofertas&accion=pasadas');
                    break;
                case 'eliminarActivas':
                    $empresaId = Session::get('perfil')->getId();
                    if ($empresaId) {
                        $oferta = $ofertaService->eliminarOfertasActivas($empresaId);
                    }
                    $renderPanel = false;
                    header('Location: index.php?menu=ofertas&accion=activas');
                    break;
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
                            'fecha_fiin_oferta' => $oferta->getFechaFin()->format('Y-m-d'),
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
    }


    private function manejoAlumno()
    {
        $renderPanel = true;
        $alumnoId = Session::get('perfil')->getId();
        $ofertaService = new OfertaService();
        $solicitudService = new SolicitudService();

        // --- POST: aplicar/desaplicar
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'], $_POST['oferta_id'])) {
            $ofertaId = $_POST['oferta_id'];
            if ($_POST['accion'] === 'solicitar') {
                $solicitudService->aplicarOferta($ofertaId, $alumnoId);
            } elseif ($_POST['accion'] === 'desaplicar') {
                $solicitudService->desaplicarOferta($_POST['solicitud_id']);
            }

            // --- Redirigir a la misma página conservando filtros 
            $url = "index.php?menu=ofertas";
            if (isset($_POST['ordenFecha']) && $_POST['ordenFecha'] !== '') {
                $url .= "&ordenFecha=" . $_POST['ordenFecha'];
            }
            if (isset($_POST['ciclo']) && $_POST['ciclo'] !== '') {
                $url .= "&ciclo=" . $_POST['ciclo'];
            }

            header("Location: $url");
            
        }

        // --- GET: filtros
        $filtros = [];
        $filtros['ordenFecha'] = $_GET['ordenFecha'] ?? 'asc'; // por defecto ascendente
        if (isset($_GET['ciclo']) && is_numeric($_GET['ciclo'])) {
            $filtros['ciclo'] = (int) $_GET['ciclo'];
        }

        // --- GET: ver detalles oferta
        if (
            isset($_GET['oferta_id'])
            && is_numeric($_GET['oferta_id'])
            && isset($_GET['accion'])
            && $_GET['accion'] === 'verDetalles'
        ) {
            $ofertaId = (int) $_GET['oferta_id'];
            $oferta = $ofertaService->getOfertaById($ofertaId);
            if ($oferta) {
                echo $this->templates->render('ofertas/verOferta', [
                    'oferta' => $oferta
                ]);
                $renderPanel = false;
            }
        }

        // --- Obtener ofertas filtradas
        $ofertas = $ofertaService->getOfertasPorAlumno($alumnoId, $filtros);

        // --- Ofertas ya aplicadas
        $aplicadas = $ofertaService->getOfertasAplicadas($alumnoId);

        // --- Ciclos disponibles para filtro
        $cicloService = new CicloService();
        $ciclos = $cicloService->getCiclosAlumno($alumnoId);

        if ($renderPanel) {
            echo $this->templates->render('ofertas/ofertasAlumno', [
                'ofertas' => $ofertas,
                'aplicadas' => $aplicadas,
                'ciclos' => $ciclos,
                'filtros' => $filtros
            ]);
        }
    }


}