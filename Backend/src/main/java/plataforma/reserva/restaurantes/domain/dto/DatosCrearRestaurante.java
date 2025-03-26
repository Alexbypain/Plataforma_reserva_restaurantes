package plataforma.reserva.restaurantes.domain.dto;

import java.time.LocalTime;

public record DatosCrearRestaurante(
        Long administrador_id,
        String nombre,
        String direccion,
        String email,
        String telefono,
        String capacidad,
        String horario_apertura,
        String horario_cierre,
        String imagen
) {
}
