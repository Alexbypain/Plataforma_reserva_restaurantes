<?php
class Reserva {

    private $db;

    public function __construct() {
        try {
            $this->db = Conexion::conectar();
            if (!$this->db) {
                throw new Exception("No se pudo establecer la conexión a la base de datos");
            }
        } catch (Exception $e) {
            error_log("Error de conexión en Reserva: " . $e->getMessage());
            throw new Exception("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    public function crearReserva($nombre_completo, $fecha_reserva, $hora_reserva, $motivo, $telefono, $numero_personas, $requisitos_especiales, $alergias_intolerancias) {
        try {
            // Verificar que la conexión esté activa
            if (!$this->db->ping()) {
                $this->db = Conexion::conectar(); // Reconectar si la conexión se perdió
            }
            
            // Registrar los datos que se intentan insertar para depuración
            error_log("Intentando insertar reserva con datos: " . 
                      "nombre=$nombre_completo, fecha=$fecha_reserva, hora=$hora_reserva, " . 
                      "motivo=$motivo, telefono=$telefono, personas=$numero_personas");
            
            // Verificar la estructura de la tabla
            $result = $this->db->query("DESCRIBE reserva");
            if (!$result) {
                throw new Exception("No se pudo verificar la estructura de la tabla: " . $this->db->error);
            }
            
            $campos = [];
            while ($row = $result->fetch_assoc()) {
                $campos[] = $row['Field'];
            }
            error_log("Campos en la tabla reserva: " . implode(", ", $campos));
            
            // Escapar los datos para prevenir inyección SQL
            $nombre_completo = $this->db->real_escape_string($nombre_completo);
            $fecha_reserva = $this->db->real_escape_string($fecha_reserva);
            $hora_reserva = $this->db->real_escape_string($hora_reserva);
            $motivo = $this->db->real_escape_string($motivo);
            $telefono = $this->db->real_escape_string($telefono);
            $numero_personas = $this->db->real_escape_string($numero_personas);
            $requisitos_especiales = $this->db->real_escape_string($requisitos_especiales ?? '');
            $alergias_intolerancias = $this->db->real_escape_string($alergias_intolerancias ?? '');
            
            // Preparar la consulta SQL
            $sql = "INSERT INTO reserva (nombre_completo, fecha_reserva, hora_reserva, motivo, telefono, numero_personas, requisitos_especiales, alergias_intolerancias)
                    VALUES ('$nombre_completo', '$fecha_reserva', '$hora_reserva', '$motivo', '$telefono', '$numero_personas', '$requisitos_especiales', '$alergias_intolerancias')";
            
            error_log("SQL a ejecutar: " . $sql);

            // Ejecutar la consulta
            $resultado = $this->db->query($sql);
            
            if ($resultado) {
                $id_insertado = $this->db->insert_id;
                error_log("Reserva guardada correctamente. ID: " . $id_insertado);
                
                // Verificar que realmente se insertó consultando el registro
                $verificacion = $this->db->query("SELECT * FROM reserva WHERE id = $id_insertado");
                if ($verificacion && $verificacion->num_rows > 0) {
                    error_log("Verificación exitosa: el registro existe en la base de datos");
                    return true;
                } else {
                    error_log("Advertencia: No se pudo verificar el registro insertado");
                    return true; // Aún así devolvemos true porque la inserción fue exitosa
                }
            } else {
                error_log("Error al guardar reserva: " . $this->db->error);
                throw new Exception("Error en la consulta SQL: " . $this->db->error);
            }
        } catch (Exception $e) {
            error_log("Excepción en Reserva->crearReserva(): " . $e->getMessage());
            throw new Exception("Error al guardar la reserva: " . $e->getMessage());
        }
    }
}
?>