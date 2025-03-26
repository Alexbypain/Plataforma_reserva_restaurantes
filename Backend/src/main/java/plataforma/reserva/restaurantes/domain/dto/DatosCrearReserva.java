package plataforma.reserva.restaurantes.domain.dto;

import jakarta.validation.constraints.NotBlank;

public record DatosCrearReserva(
        @NotBlank
        String restaurante_id,
        @NotBlank
        String usuario_id,
        @NotBlank
        String fecha,
        @NotBlank
        String motivo,
        @NotBlank
        String cantidadPersonas,
        String requisitosEspeciales,
        String alergias
) {
}