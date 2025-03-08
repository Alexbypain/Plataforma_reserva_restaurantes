<?php
class Reserva {

    private $db;

    public function __construct() {
        try {
            $this->db = Conexion::conectar();
        } catch (Exception $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    public function crearReserva($nombre_completo, $fecha_reserva, $hora_reserva, $motivo, $telefono, $numero_personas, $requisitos_especiales, $alergias_intolerancias) {
        try {
            // Preparar la consulta SQL
            $sql = "INSERT INTO reserva (nombre_completo, fecha_reserva, hora_reserva, motivo, telefono, numero_personas, requisitos_especiales, alergias_intolerancias)
                    VALUES ('$nombre_completo', '$fecha_reserva', '$hora_reserva', '$motivo', '$telefono', $numero_personas, '$requisitos_especiales', '$alergias_intolerancias')";

            // Ejecutar la consulta
            if ($this->db->query($sql)) {
                return true; // Reserva guardada correctamente
            } else {
                throw new Exception("Error en la consulta SQL: " . $this->db->error);
            }
        } catch (Exception $e) {
            throw new Exception("Error al guardar la reserva: " . $e->getMessage());
        }
    }
}
?>