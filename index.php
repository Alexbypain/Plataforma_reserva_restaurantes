<?php 

    require_once "config/config.php";
    require_once "core/routes.php";

    require_once 'config/database.php';

    if(isset($_GET['controlador'])) {
        $controlador = cargarControlador($_GET['controlador']);

        if(isset($_GET['accion'])) {
            if(isset($_GET['id'])) {
                cargarAccion($controlador, $_GET['accion'], $_GET['id']);
            } else {
                cargarAccion($controlador, $_GET['accion']);
            }
        } else {
            cargarAccion($controlador, ACCION_PRINCIPAL);
        }
        
    } else {
        $controlador = cargarControlador(CONTROLADOR_PRINCIPAL);
        cargarAccion($controlador, ACCION_PRINCIPAL);
    }

?>