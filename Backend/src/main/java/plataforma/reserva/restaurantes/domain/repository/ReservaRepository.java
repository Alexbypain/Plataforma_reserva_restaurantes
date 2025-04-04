package plataforma.reserva.restaurantes.domain.repository;


import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;
import plataforma.reserva.restaurantes.domain.entities.Reserva;
import plataforma.reserva.restaurantes.domain.entities.Restaurante;

import java.time.LocalDateTime;
import java.util.List;

public interface ReservaRepository extends JpaRepository<Reserva,Long> {

    @Query("SELECT r FROM Reserva r WHERE r.usuario.id = :usuarioId AND r.fecha > :now")
    Page<Reserva> findByUsuarioIdAndUpcomingReservations(Long usuarioId, LocalDateTime now, Pageable pageable);

    @Query("SELECT r FROM Reserva r WHERE r.usuario.id = :usuarioId AND r.fecha < :fechaLimite")
    Page<Reserva> findByUsuarioIdAndCompletedReservations(@Param("usuarioId") Long usuarioId, @Param("fechaLimite") LocalDateTime fechaLimite, Pageable pageable);


    List<Reserva> findByRestaurante(Restaurante restaurante);
}