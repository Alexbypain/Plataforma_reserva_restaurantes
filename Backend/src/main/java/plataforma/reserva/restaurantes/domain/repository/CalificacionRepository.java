package plataforma.reserva.restaurantes.domain.repository;

import org.springframework.data.jpa.repository.JpaRepository;
import plataforma.reserva.restaurantes.domain.entities.Calificacion;
import plataforma.reserva.restaurantes.domain.entities.Reserva;

public interface CalificacionRepository extends JpaRepository<Calificacion,Long> {


}
