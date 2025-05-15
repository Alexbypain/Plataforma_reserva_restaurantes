package plataforma.reserva.restaurantes.domain.dto;

public record DatosActualizarRestaurante (
    String nombre,
    String  direccion,
    String email,
    String telefono,
    String capacidad,
    String horario_apertura,
    String horario_cierre,
    String imagen
){}

