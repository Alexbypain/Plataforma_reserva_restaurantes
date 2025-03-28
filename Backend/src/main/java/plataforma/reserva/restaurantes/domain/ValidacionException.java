package plataforma.reserva.restaurantes.domain;


public class ValidacionException extends RuntimeException{
    public ValidacionException(String mensaje) {
        super(mensaje);
    }
}
