<?php
class LogeoController {

    public function __construct() {
        require_once "models/Logeo.php";
        require_once "config/session_helper.php";
        iniciar_sesion(); // Usar la función centralizada
    }

    public function register() {
        $usuario = $_POST['usuario'] ?? null;
        $email = $_POST['email'] ?? null;
        $contrasenia = $_POST['contrasenia'] ?? null;

        if($usuario == null || $email == null || $contrasenia == null) {
            $data['titulo'] = "Registro de usuario";
            $data['error'] = "Ingrese datos válidos";
            require_once "views/logeos/registro.php";
        } else {
            $logeo = new Logeo();
            $logeo->store($usuario, $email, $contrasenia);
            header('Location: index.php?controlador=logeo&accion=verLogin');
            exit(); // Añadir exit después de redireccionar
        }
    }
    
    public function verLogin() {
        $data['titulo'] = "Iniciar sesión";
        require_once "views/logeos/login.php";
    }

    public function verRegistro() {
        $data['titulo'] = "Registro de usuario";
        require_once "views/logeos/registro.php";
    }

    public function login() {
        $email = $_POST['email'] ?? null;
        $contrasenia = $_POST['contrasenia'] ?? null;

        if($email == null || $contrasenia == null) {
            $data['titulo'] = "Iniciar sesión";
            $data['error'] = "Ingrese datos válidos";
            require_once "views/logeos/login.php";
            return;
        }

        $modeloLogeo = new Logeo();
        $logeo = $modeloLogeo->consultarUsuario($email);

        if($logeo == null) {
            $data['titulo'] = "Iniciar sesión";
            $data['error'] = "No existe un usuario con ese correo electrónico";
            require_once "views/logeos/login.php";
        } else {
            // Verificar contraseña
            if(password_verify($contrasenia, $logeo['contrasenia'])) {
                // Guardar datos en la sesión ANTES de redireccionar
                establecer_usuario_en_sesion($logeo['id'], $logeo['email']);
                
                // Redireccionar al home
                header("Location: index.php?controlador=home&accion=index");
                exit(); // Añadir exit después de redireccionar
            } else {
                $data['titulo'] = "Iniciar sesión";
                $data['error'] = "Contraseña incorrecta";
                require_once "views/logeos/login.php";
            }
        }
    }

    public function logout() {
        cerrar_sesion(); // Usar la función centralizada
        header('Location: index.php?controlador=logeo&accion=verLogin');
        exit(); // Añadir exit después de redireccionar
    }
}
?>