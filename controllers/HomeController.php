<?php
class HomeController {
    
    public function __construct() {
        require_once "config/session_helper.php";
    }
    
    public function index() {
        iniciar_sesion(); // Usar la función centralizada

        if (esta_logueado()) {
            $data['titulo'] = "Gestión De reservas restaurante";
            $data['usuario'] = obtener_usuario_actual();
            require_once "views/home/index.php";
        } else {
            header("Location: index.php?controlador=logeo&accion=verLogin");
            exit(); // Añadir exit después de redireccionar
        }
    }
}
?>