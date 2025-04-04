package plataforma.reserva.restaurantes.domain.dto;

import jakarta.validation.constraints.NotBlank;

public record DatosActualizarReserva(
        String fecha,
        String motivo,
        String cantidadPersonas,
        String requisitosEspeciales,
        String alergias

) {
}
