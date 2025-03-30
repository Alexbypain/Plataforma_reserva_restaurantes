package plataforma.reserva.restaurantes.domain.dto;

import plataforma.reserva.restaurantes.domain.entities.Reserva;
import plataforma.reserva.restaurantes.domain.entities.Restaurante;

import java.time.format.DateTimeFormatter;

public record DatosListadoRestaurantesHoy(
        Long reserva_id,
        Long usuario_id,
        String usuario_nombre,
        String fecha,
        String motivo,
        Integer cantidad_personas,
        String requisitos_especiales,
        String alergias
) {
    public DatosListadoRestaurantesHoy(Reserva reserva) {
        this(
            reserva.getId(),
            reserva.getUsuario().getId(),
            reserva.getUsuario().getNombre(),
            reserva.getFecha().format(DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss")),
            reserva.getMotivo(),
            reserva.getCantidad_personas(),
            reserva.getRequisitos_especiales(),
            reserva.getAlergias()
        );
    }
}