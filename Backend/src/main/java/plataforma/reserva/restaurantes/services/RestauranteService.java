package plataforma.reserva.restaurantes.services;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import plataforma.reserva.restaurantes.domain.repository.ReservaRepository;

@Service
public class RestauranteService {
    @Autowired
    private ReservaRepository reservaRepository;

    public Double calcularPromedioCalificaciones(Long restauranteId) {
        Double promedio = reservaRepository.obtenerPromedioCalificacionPorRestaurante(restauranteId);
        return promedio != null ? promedio : 0.0;
    }


}
