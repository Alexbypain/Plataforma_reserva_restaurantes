package plataforma.reserva.restaurantes.domain.dto;

public record DatosCrearCalificacion(
        String usuario_id,
        String reserva_id,
        String rating,
        String comentario
) {
}
