<?php
class ReservaController {

    public function __construct() {
        require_once "models/Reserva.php";
        require_once "config/session_helper.php";
    }

    public function crear() {
        // Iniciar sesión de manera segura
        iniciar_sesion();
        
        // Código de depuración
        error_log("Método crear() llamado en ReservaController");
        error_log("POST data: " . print_r($_POST, true));
        error_log("SESSION data: " . print_r($_SESSION, true));
        
        // Verificar si se recibieron datos del formulario
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['mensaje'] = "Método no permitido";
            $_SESSION['tipo_mensaje'] = "error";
            header("Location: views/reserva/create.php");
            exit();
        }
        
        // Verificar que todos los campos requeridos estén presentes
        $campos_requeridos = ['nombre_completo', 'fecha_reserva', 'hora_reserva', 'motivo', 'telefono', 'numero_personas'];
        $faltantes = [];
        
        foreach ($campos_requeridos as $campo) {
            if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
                $faltantes[] = $campo;
            }
        }
        
        if (!empty($faltantes)) {
            $_SESSION['mensaje'] = "Faltan campos requeridos: " . implode(", ", $faltantes);
            $_SESSION['tipo_mensaje'] = "error";
            header("Location: views/reserva/create.php");
            exit();
        }
    
        // Recoger los datos del formulario
        $nombre_completo = $_POST['nombre_completo'];
        $fecha_reserva = $_POST['fecha_reserva'];
        $hora_reserva = $_POST['hora_reserva'];
        $motivo = $_POST['motivo'];
        $telefono = $_POST['telefono'];
        $numero_personas = $_POST['numero_personas'];
        $requisitos_especiales = isset($_POST['requisitos_especiales']) ? $_POST['requisitos_especiales'] : '';
        $alergias_intolerancias = isset($_POST['alergias_intolerancias']) ? $_POST['alergias_intolerancias'] : '';
    
        try {
            $Reserva = new Reserva();
            $resultado = $Reserva->crearReserva($nombre_completo, $fecha_reserva, $hora_reserva, $motivo, $telefono, $numero_personas, $requisitos_especiales, $alergias_intolerancias);
            
            if ($resultado) {
                $_SESSION['mensaje'] = "Reserva guardada correctamente.";
                $_SESSION['tipo_mensaje'] = "success";
                error_log("Reserva guardada correctamente en el controlador");
            } else {
                $_SESSION['mensaje'] = "Error al guardar la reserva.";
                $_SESSION['tipo_mensaje'] = "error";
                error_log("Error al guardar la reserva en el controlador");
            }
        } catch (Exception $e) {
            error_log("Error en ReservaController->crear(): " . $e->getMessage());
            $_SESSION['mensaje'] = "Error: " . $e->getMessage();
            $_SESSION['tipo_mensaje'] = "error";
        }
        
        // Guardar la sesión antes de redireccionar
        session_write_close();
        
        // Redireccionar a la página de creación de reservas
        header("Location: views/home/index.php");
        exit();
    }
}
?>