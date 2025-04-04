package plataforma.reserva.restaurantes.domain.dto;

import plataforma.reserva.restaurantes.domain.entities.Reserva;
import plataforma.reserva.restaurantes.domain.entities.Restaurante;

import java.util.Base64;

public record DatosListadoReserva(
        String reserva_id,
        String nombre_restaurante,
        String fecha,
        String motivo,
        String cantidadPersonas,
        String requisitosEspeciales,
        String alergias,
        String calificacion_id

) {

    public DatosListadoReserva(Reserva reserva) {
        this(String.valueOf(reserva.getId()),reserva.getRestaurante().getNombre(),String.valueOf(reserva.getFecha()),
                reserva.getMotivo(),String.valueOf(reserva.getCantidad_personas()),
                reserva.getRequisitos_especiales(),
                reserva.getAlergias(),
                reserva.getCalificacion() != null ? String.valueOf(reserva.getCalificacion().getId()) : null

        );
    }


}