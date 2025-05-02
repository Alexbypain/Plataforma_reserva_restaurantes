package plataforma.reserva.restaurantes.domain.repository;

import java.time.LocalDateTime;
import java.util.Optional;

import org.hibernate.Hibernate;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;

import plataforma.reserva.restaurantes.domain.entities.Restaurante;
import plataforma.reserva.restaurantes.domain.entities.Usuario;


public interface RestauranteRepository extends JpaRepository<Restaurante,Long> { 
   Restaurante findByAdministrador (Usuario usuarioId);

   Page<Restaurante> findByNombreContainingIgnoreCase(String restaurante_nombre, Pageable pageable);


}
