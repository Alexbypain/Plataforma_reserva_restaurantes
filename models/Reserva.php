<?php

class Reserva {

    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    public function crearReserva($nombre_completo, $fecha_reserva, $hora_reserva, $motivo, $telefono, $numero_personas, $requisitos_especiales, $alergias_intolerancias) {

        $sql = "INSERT INTO reserva(nombre_completo, fecha_reserva, hora_reserva,motivo, telefono,numero_personas,requisitos_especiales,alergias_intolerancias)
                VALUES ('$nombre_completo', '$fecha_reserva', '$hora_reserva', '$motivo', $telefono, $numero_personas, '$requisitos_especiales', '$alergias_intolerancias')";

        $this->db->query($sql);
    }

    public function consultarReserva($email) {
        $sql = "SELECT * FROM logeo
                WHERE email = '$email'";
        $consulta = $this->db->query($sql);
        $objetoUsuario = $consulta->fetch_assoc();
        return $objetoUsuario;
    }


}


