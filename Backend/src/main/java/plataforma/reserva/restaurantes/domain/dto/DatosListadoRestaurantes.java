package plataforma.reserva.restaurantes.domain.dto;

import plataforma.reserva.restaurantes.domain.entities.Restaurante;

import java.util.Base64;

public record DatosListadoRestaurantes(
        String restaurante_id,
        String nombre,
        String direccion,
        String rating,
        String email,
        String telefono,
        String capacidad,
        String horario_apertura,
        String horario_cierre,
        String imagen

) {
    public DatosListadoRestaurantes(Restaurante restaurante) {
        this(String.valueOf(restaurante.getId()),restaurante.getNombre(),restaurante.getDireccion(), String.valueOf(restaurante.getRating()),restaurante.getEmail(),
                restaurante.getTelefono(),String.valueOf(restaurante.getCapacidad()),String.valueOf(restaurante.getHorario_apertura()),
                String.valueOf(restaurante.getHorario_cierre()),
                (restaurante.getImagen() != null) ? Base64.getEncoder().encodeToString(restaurante.getImagen()) : null);

    }
}
