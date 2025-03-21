<?php
// index.php en la raíz del proyecto
require_once "config/config.php";
require_once "config/database.php";
require_once "core/routes.php";
require_once "config/session_helper.php";

// Iniciar sesión de manera segura
iniciar_sesion();

// Obtener parámetros de la URL
$controlador = $_GET['controlador'] ?? CONTROLADOR_PRINCIPAL;
$accion = $_GET['accion'] ?? ACCION_PRINCIPAL;
$id = $_GET['id'] ?? null;

// Cargar controlador y ejecutar acción
try {
    $controlador = cargarControlador($controlador);
    cargarAccion($controlador, $accion, $id);
} catch (Exception $e) {
    $_SESSION['mensaje'] = "Error en el sistema: " . $e->getMessage();
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: index.php?controlador=home&accion=index");
    exit();
}
?>