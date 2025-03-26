package plataforma.reserva.restaurantes.domain.dto;

import jakarta.validation.constraints.NotBlank;

public record DatosCrearUsuario(
        @NotBlank
        String nombre,
        @NotBlank
        String email,
        @NotBlank
        String password
) {
}
