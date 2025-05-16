package plataforma.reserva.restaurantes.domain.dto;

import plataforma.reserva.restaurantes.domain.entities.Restaurante;

import java.time.LocalTime;

public record DatosRespuestaRestauranteActualizado(
        String nombre,
        String  direccion,
        String email,
        String telefono,
        String capacidad,
        String horario_apertura,
        String horario_cierre
) {
    public DatosRespuestaRestauranteActualizado(Restaurante restaurante) {
        this(restaurante.getNombre(), restaurante.getDireccion(),
                restaurante.getEmail(),
                restaurante.getTelefono(),
                String.valueOf(restaurante.getCapacidad()),
                String.valueOf(restaurante.getHorario_apertura()),
                String.valueOf(restaurante.getHorario_cierre()));
    }

}

