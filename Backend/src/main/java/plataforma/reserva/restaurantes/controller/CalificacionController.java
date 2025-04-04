package plataforma.reserva.restaurantes.controller;


import jakarta.validation.Valid;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.PageImpl;
import org.springframework.data.domain.Pageable;
import org.springframework.data.web.PageableDefault;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;
import plataforma.reserva.restaurantes.domain.dto.DatosCrearCalificacion;
import plataforma.reserva.restaurantes.domain.dto.DatosCrearReserva;
import plataforma.reserva.restaurantes.domain.dto.DatosListadoCalificacion;
import plataforma.reserva.restaurantes.domain.dto.DatosListadoReserva;
import plataforma.reserva.restaurantes.domain.entities.Calificacion;
import plataforma.reserva.restaurantes.domain.entities.Reserva;
import plataforma.reserva.restaurantes.domain.entities.Restaurante;
import plataforma.reserva.restaurantes.domain.entities.Usuario;
import plataforma.reserva.restaurantes.domain.repository.CalificacionRepository;
import plataforma.reserva.restaurantes.domain.repository.ReservaRepository;
import plataforma.reserva.restaurantes.domain.repository.RestauranteRepository;
import plataforma.reserva.restaurantes.domain.repository.UsuarioRepository;

import java.time.LocalDateTime;
import java.util.Collections;
import java.util.List;

@RestController
@RequestMapping("/calificaciones")
public class CalificacionController {
    @Autowired
    private ReservaRepository reservaRepository;

    @Autowired
    private CalificacionRepository calificacionRepository;

    @Autowired
    private UsuarioRepository usuarioRepository;

    @Autowired
    private RestauranteRepository restauranteRepository;


    @PostMapping
    public ResponseEntity crearCalificacion(@RequestBody @Valid DatosCrearCalificacion datosCrearCalificacion){
        Calificacion calificacion=new Calificacion();

        Reserva reserva=reservaRepository.findById(Long.valueOf(datosCrearCalificacion.reserva_id())).get();
        calificacion.setReserva(reserva);

        Usuario usuario=usuarioRepository.findById(Long.valueOf(datosCrearCalificacion.usuario_id())).get();
        calificacion.setUsuario(usuario);

        calificacion.setComentario(datosCrearCalificacion.comentario());
        calificacion.setRating(Integer.parseInt(datosCrearCalificacion.rating()));

        // Primero guarda la calificación para que tenga ID
        calificacionRepository.save(calificacion);
        if (reserva.getCalificacion() != null) {
            return ResponseEntity.status(HttpStatus.CONFLICT)
                    .body(Collections.singletonMap("mensaje", "Esta reserva ya tiene una calificación"));
        }
        // Ahora asocia la calificación a la reserva
        reserva.setCalificacion(calificacion);
        reservaRepository.save(reserva); // este paso es el que actualiza la FK en la base de datos



        return ResponseEntity.ok(Collections.singletonMap("mensaje", "La calificacion ha sido creada exitosamente"));
    }



}
