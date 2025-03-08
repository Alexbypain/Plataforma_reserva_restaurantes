<?php
class ReservaControlle {

    public function __construct() {
        require_once "models/Reserva.php";
        session_start();
    }

    public function crearReserva() {
        // Obtener los datos del formulario
        $nombre_completo = $_POST['nombre_completo'];
        $fecha_reserva = $_POST['fecha_reserva'];
        $hora_reserva = $_POST['hora_reserva'];
        $motivo = $_POST['motivo'];
        $telefono = $_POST['telefono'];
        $numero_personas = $_POST['numero_personas'];
        $requisitos_especiales = $_POST['requisitos_especiales'];
        $alergias_intolerancias = $_POST['alergias_intolerancias'];

        // Crear una instancia de la clase Reserva
        $Reserva = new Reserva();

        // Intentar guardar la reserva
        if ($Reserva->crearReserva($nombre_completo, $fecha_reserva, $hora_reserva, $motivo, $telefono, $numero_personas, $requisitos_especiales, $alergias_intolerancias)) {
            // Reserva guardada correctamente
            $_SESSION['mensaje'] = "Reserva guardada correctamente.";
            $_SESSION['tipo_mensaje'] = "success"; // Tipo de mensaje (éxito)
        } else {
            // Error al guardar la reserva
            $_SESSION['mensaje'] = "Error al guardar la reserva. Inténtalo de nuevo.";
            $_SESSION['tipo_mensaje'] = "error"; // Tipo de mensaje (error)
        }

        // Redirigir de vuelta al formulario
        header("Location: ../../views/Booking/index.php");
        exit();
    }
}
?>