package plataforma.reserva.restaurantes.domain.repository;


import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;
import plataforma.reserva.restaurantes.domain.entities.Reserva;

import java.time.LocalDate;
import java.time.LocalDateTime;
import plataforma.reserva.restaurantes.domain.entities.Restaurante;
import java.util.List;

public interface ReservaRepository extends JpaRepository<Reserva,Long> {

    @Query("SELECT r FROM Reserva r WHERE r.usuario.id = :usuarioId AND r.fecha >= :now")
    Page<Reserva> findByUsuarioIdAndUpcomingReservations(Long usuarioId, LocalDateTime now, Pageable pageable);

    @Query("SELECT r FROM Reserva r WHERE r.usuario.id = :usuarioId AND r.fecha < :fechaLimite")
    Page<Reserva> findByUsuarioIdAndCompletedReservations(@Param("usuarioId") Long usuarioId, @Param("fechaLimite") LocalDateTime fechaLimite, Pageable pageable);

   @Query("SELECT r FROM Reserva r WHERE r.restaurante.id = :restauranteId AND FUNCTION('DATE', r.fecha) = :fecha")
    Page<Reserva> findByRestauranteIdAndUpcomingReservations(@Param("restauranteId") Long restauranteId, @Param("fecha") LocalDate fecha,   Pageable pageable);

    List<Reserva> findByRestaurante(Restaurante restaurante);

    @Query("""
       SELECT AVG(r.calificacion.rating)
       FROM Reserva r
       WHERE r.restaurante.id = :restauranteId
         AND r.calificacion IS NOT NULL
       """)
    Double obtenerPromedioCalificacionPorRestaurante(@Param("restauranteId") Long restauranteId);

}