<?php

class HomeController {
    public function index() {
        // Iniciar sesión si aún no está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['email'])) {
            $data['titulo'] = "Gestión De reservas restaurante";
            require_once "views/home/index.php";
        } else {
            header("Location: index.php?controlador=logeo&accion=verLogin");
            exit();
        }
    
    }
}

?>
