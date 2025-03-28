package plataforma.reserva.restaurantes.domain.dto;

import plataforma.reserva.restaurantes.domain.entities.Reserva;

public record DatosRespuestaReservaActualizado(
        String fecha,
        String motivo,
        String cantidadPersonas,
        String requisitosEspeciales,
        String alergias
) {
    public DatosRespuestaReservaActualizado(Reserva reserva){
        this(String.valueOf(reserva.getFecha()), reserva.getMotivo(), String.valueOf(reserva.getCantidad_personas()),reserva.getRequisitos_especiales() ,reserva.getAlergias());

    }
}
