package plataforma.reserva.restaurantes.domain.repository;


import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import plataforma.reserva.restaurantes.domain.entities.Reserva;
import java.time.LocalDateTime;

public interface ReservaRepository extends JpaRepository<Reserva,Long> {

    @Query("SELECT r FROM Reserva r WHERE r.usuario.id = :usuarioId AND r.fecha >= :now")
    Page<Reserva> findByUsuarioIdAndUpcomingReservations(Long usuarioId, LocalDateTime now, Pageable pageable);


}