package plataforma.reserva.restaurantes.domain.dto;

import plataforma.reserva.restaurantes.domain.entities.Calificacion;
import plataforma.reserva.restaurantes.domain.entities.Usuario;

public record DatosListadoCalificacion(
        String nombre_usuario,
        String fecha,
        String rating,
        String comentario
) {
    public DatosListadoCalificacion(Calificacion calificacion) {
        this(calificacion.getUsuario().getNombre(),
                String.valueOf(calificacion.getReserva().getFecha()),
                String.valueOf(calificacion.getRating()),
                calificacion.getComentario());
    }
}
