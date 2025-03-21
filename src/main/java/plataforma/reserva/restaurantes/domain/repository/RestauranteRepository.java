package plataforma.reserva.restaurantes.domain.repository;

import org.springframework.data.jpa.repository.JpaRepository;
import plataforma.reserva.restaurantes.domain.entities.Restaurante;

public interface RestauranteRepository extends JpaRepository<Restaurante,Long> {
}
