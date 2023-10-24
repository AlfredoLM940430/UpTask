<?php 

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {

    public static function logout() {
        isSession();
        $_SESSION = [];
        header('Location: /');
    }

    public static function login(Router $router) {

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarLogin();

            if(empty($alertas)) {

                //Verifica rusuario
                $usuario = Usuario::where('email', $usuario->email);
                
                if(!$usuario || !$usuario->confirmado) {

                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                } else {

                    if(password_verify($_POST['password'], $usuario->password)) {

                        isSession();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        header('Location: /dashboard');

                    } else {
                        Usuario::setAlerta('error', 'Contraseña incorecta');
                    }
                }
            }
        }
        $alertas = Usuario::getAlertas();

        //Vistas
        $router->render('auth/login', [

            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }

    public static function crear(Router $router) {

        $alertas = [];

        $usuario = new Usuario;

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevCuenta();

            if(empty($alertas)) {

                $existeUsuario = Usuario::where('email', $usuario->email);
                if($existeUsuario) {
                    Usuario::setAlerta('error', 'El usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();
                } else {
                    
                    //Hash password
                    $usuario->hashPass();

                    //Eliminar pass2
                    unset($usuario->password2);

                    //Token
                    $usuario->crearToken();

                    
                    //Crear usuario
                    $resultado = $usuario->guardar();
                    
                    
                    //Enviar Email
                    $email = new Email( $usuario->email, $usuario->nombre, $usuario->token );
                    $email->enviarConfirmacion();

                    if($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }


        }
        //Vistas
        $router->render('auth/crear', [
            'titulo' => 'Crea tu Cuenta en UpTask',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router) {

        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)) {
                $usuario = Usuario::where('email', $usuario->email);

                if($usuario && $usuario->confirmado) {
                    
                    //nuevo token
                    $usuario->crearToken();
                    unset($usuario->password2);

                    //actualizar usuario
                    $usuario->guardar();

                    //enviar email
                    $email = new Email( $usuario->email, $usuario->nombre, $usuario->token );
                    $email->enviarInstrucciones();

                    //imprimir alerta
                    $usuario->setAlerta('exito', 'Enviamos instrucciones a tu email');
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                    
                }
            }
        }

        $alertas = Usuario::getAlertas();

        //Vistas
        $router->render('auth/olvide', [
            'titulo' => 'Olvide mi Contraseña',
            'alertas' => $alertas
        ]);
    }

    public static function reestablecer(Router $router) {

        $token = s($_GET['token']);

        $mostrar = true;
        
        if(!$token) header('Location: /');

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            Usuario::setAlerta('error', 'token no valido');
            $mostrar = false;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            //Añdadir nuevo password
            $usuario->sincronizar($_POST);
            
            //Validar
            $alertas = $usuario->validarPassword();

            if(empty($alertas)) {

                $usuario->hashPass();
                unset($usuario->password2);

                $usuario->token = null;

                $resultado = $usuario->guardar();

                if($resultado) {
                    header('Location: /');
                }



            }
        }

        $alertas = Usuario::getAlertas();
        //Vistas
        $router->render('auth/reestablecer', [
            'titulo' => 'Recuperacion Contraseña',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }

    public static function mensaje(Router $router) {
        //Vistas
        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta Creada Exitosamente'
        ]);
    }

    public static function confirmar(Router $router) {

        $token = s($_GET['token']);

        if(!$token) {
            header('Location: /');
        }

        //Extraer usuario
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            //no existe token
            Usuario::setAlerta('error', 'Token no valido');
        } else {
            //Confirmar cuenta
            $usuario->confirmado = 1;
            $usuario->token = null;
            unset($usuario->password2);

            $usuario->guardar();

            Usuario::setAlerta('exito', 'Cuenta comprobada');
        }

        $alertas = Usuario::getAlertas();

        //Vistas
        $router->render('auth/confirmar', [
            'titulo' => 'Confira tu Cuenta',
            'alertas' => $alertas
        ]);
    }

}