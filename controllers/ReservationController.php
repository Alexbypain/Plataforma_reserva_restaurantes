<?php

class ReservationController {
    

    public function register() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            require_once "models/Reserva.php";
            $Reserva = new Reserva();

            $nombre_completo = $_POST['nombre_completo'];
            $fecha_reserva = $_POST['fecha_reserva'];
            $hora_reserva = $_POST['hora_reserva'];
            $motivo = $_POST['motivo'];
            $telefono = $_POST['telefono'];
            $numero_personas = $_POST['numero_personas'];
            $requisitos_especiales = $_POST['requisitos_especiales'];
            $alergias_intolerancias = $_POST['alergias_intolerancias'];
            
            $resultado = $Reserva->crearReserva($nombre_completo, $fecha_reserva, $hora_reserva, $motivo, $telefono, $numero_personas, $requisitos_especiales, $alergias_intolerancias);
            
            if ($resultado) {
                header("Location: ../views/logeos/Reserva.php");
                exit();
            } else {
                header("Location: ../views/logeos/error.php");
                exit();
            }
        }
    }
}

// Verificar la acción y llamar al método correspondiente

if (isset($_GET['action']) && $_GET['action'] == 'register') {
    $controller = new ReservationController();
    $controller->register();
}
