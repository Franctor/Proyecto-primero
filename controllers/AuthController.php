<?php
namespace controllers;
use League\Plates\Engine;
use repositories\RepoAlumno;
use repositories\RepoEmpresa;
use services\EmpresaService;
use services\UsuarioService;
use services\TokenService;
use helpers\Security;
use helpers\Session;
use helpers\Validator;
class AuthController
{
    private $templates;
    public function __construct(Engine $templates)
    {
        $this->templates = $templates;
    }
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
            $password = $_POST['password'] ?? '';
            $usuarioService = new UsuarioService();

            if (empty($nombre_usuario) || empty($password)) {
                $error = 'Por favor, completa todos los campos.';
            } else {
                $validator = new Validator();

                if ($validator->validarCorreoElectronico($nombre_usuario) !== false) {
                    $user = $usuarioService->getUserByNombreUsuario($nombre_usuario);

                    if ($user && Security::verifyPassword($password, $user->getPassword())) {

                        // Guardar datos específicos según rol
                        if ($user->getRolId() === 2) {
                            $repoAlumno = new RepoAlumno();
                            $alumno = $repoAlumno->getByUsuarioId($user->getId());
                            Session::set('perfil', $alumno);
                            Session::set('tipo', 'alumno');
                            Session::set('activo', $alumno->getActivo());
                        } elseif ($user->getRolId() === 3) {
                            $repoEmpresa = new RepoEmpresa();
                            $empresa = $repoEmpresa->getByUsuarioId($user->getId());
                            Session::set('perfil', $empresa);
                            Session::set('tipo', 'empresa');
                            Session::set('activo', $empresa->getVerificada());
                        } else {
                            Session::set('tipo', 'admin');
                            Session::set('activo', 1);
                        }

                        // Datos generales del usuario
                        //Si no está activa, no iniciamos sesion.
                        if (Session::get('activo') == 1) {
                            Session::set('usuario_id', $user->getId());
                            Session::set('rol', $user->getRolId());
                            Session::set('nombre_usuario', $user->getNombreUsuario());

                            Session::login($user);
                            $token = Security::generateToken();
                            Session::set('token', $token);
                            $tokenService = new TokenService();
                            $tokenService->saveToken(Session::get('usuario_id'), Session::get('token'));
                        }

                        if (Session::isLogged()) {
                            header('Location: /index.php');
                            exit;
                        } else {
                            $error = 'Tu cuenta no está activa. Por favor, verifique su correo u espere a que un administrador la active.';
                        }

                    } else {
                        $error = 'Correo electrónico o contraseña incorrectos.';
                    }
                } else {
                    $error = 'Formato de correo electrónico inválido.';
                }
            }
        }

        echo $this->templates->render('auth/login', [
            'error' => $error ?? null
        ]);
    }


    public function register()
    {
        if (isset($_GET['tipo'])) {
            $tipo = $_GET['tipo'];
            if ($tipo === 'alumno' || $tipo === 'empresa') {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $inputs = $_POST;
                    $files = $_FILES;
                    $validator = new Validator();
                    $errores = $validator->validarFormularioRegistroEmpresa($inputs, $files) ?? [];
                    if (empty($errores)) {
                        $empresaService = new EmpresaService();
                        $empresaService->registrarEmpresa($inputs, $files);
                        header('Location: /index.php?menu=login');
                        exit;
                    }
                }
                echo $this->templates->render('auth/register', ['tipo' => $tipo, 'errores' => $errores ?? []]);
            } else {
                header('Location: /index.php');
                exit;
            }
        } else {
            header('Location: /index.php');
            exit;
        }
    }

    public function logout()
    {
        $tokenService = new TokenService();
        $tokenService->deleteToken(Session::get('usuario_id'));
        Session::logout();
        header('Location: /index.php');
        exit;
    }
}