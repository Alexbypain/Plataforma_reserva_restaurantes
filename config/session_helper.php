<?php
// config/session_helper.php

function iniciar_sesion() {
    if (session_status() == PHP_SESSION_NONE) {
        // Configurar parámetros de sesión para mayor seguridad y duración
        ini_set('session.gc_maxlifetime', 3600); // 1 hora
        ini_set('session.cookie_lifetime', 3600); // 1 hora
        session_start();
    }
}

function cerrar_sesion() {
    iniciar_sesion(); // Asegurarse de que la sesión esté iniciada
    
    // Destruir todas las variables de sesión
    $_SESSION = array();
    
    // Destruir la cookie de sesión
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destruir la sesión
    session_destroy();
}

function esta_logueado() {
    iniciar_sesion();
    return isset($_SESSION['email']) && !empty($_SESSION['email']);
}

function obtener_usuario_actual() {
    iniciar_sesion();
    return $_SESSION['email'] ?? null;
}

function obtener_id_usuario_actual() {
    iniciar_sesion();
    return $_SESSION['id'] ?? null;
}

function establecer_usuario_en_sesion($id, $email) {
    iniciar_sesion();
    $_SESSION['id'] = $id;
    $_SESSION['email'] = $email;
}
?>