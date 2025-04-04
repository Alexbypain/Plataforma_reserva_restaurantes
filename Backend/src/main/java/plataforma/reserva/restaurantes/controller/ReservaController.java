package plataforma.reserva.restaurantes.controller;


import jakarta.transaction.Transactional;
import jakarta.validation.Valid;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.web.PageableDefault;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;
import plataforma.reserva.restaurantes.domain.ValidacionException;
import plataforma.reserva.restaurantes.domain.dto.*;
import plataforma.reserva.restaurantes.domain.entities.Reserva;
import plataforma.reserva.restaurantes.domain.entities.Restaurante;
import plataforma.reserva.restaurantes.domain.entities.Usuario;
import plataforma.reserva.restaurantes.domain.repository.ReservaRepository;
import plataforma.reserva.restaurantes.domain.repository.RestauranteRepository;
import plataforma.reserva.restaurantes.domain.repository.UsuarioRepository;

import java.time.LocalDate;
import java.time.LocalDateTime;
import java.time.LocalTime;
import java.util.Base64;
import java.util.Collections;

@RestController
@RequestMapping("/reservas")
public class ReservaController {
    @Autowired
    private RestauranteRepository restauranteRepository;

    @Autowired
    private UsuarioRepository usuarioRepository;

    @Autowired
    private ReservaRepository reservaRepository;

    @PostMapping
    public ResponseEntity crearReserva(@RequestBody @Valid DatosCrearReserva datosCrearReserva){
        Reserva reserva=new Reserva();

        Restaurante restaurante=restauranteRepository.findById(Long.valueOf(datosCrearReserva.restaurante_id())).get();
        reserva.setRestaurante(restaurante);

        Usuario usuario=usuarioRepository.findById(Long.valueOf(datosCrearReserva.usuario_id())).get();
        reserva.setUsuario(usuario);

        reserva.setFecha(LocalDateTime.parse(datosCrearReserva.fecha()));
        reserva.setMotivo(datosCrearReserva.motivo());

        reserva.setCantidad_personas(Integer.parseInt(datosCrearReserva.cantidadPersonas()));

        reserva.setRequisitos_especiales(datosCrearReserva.requisitosEspeciales());
        reserva.setAlergias(datosCrearReserva.alergias());


        reservaRepository.save(reserva);
        return ResponseEntity.ok(Collections.singletonMap("mensaje", "Reserva creada exitosamente"));
    }


    @GetMapping
    public ResponseEntity<Page<DatosListadoReserva>> listarReservasPorUsuario(
            @RequestParam Long usuario_id,
            @PageableDefault(size = 5) Pageable paginacion) {

        Page<Reserva> reservas = reservaRepository.findByUsuarioIdAndUpcomingReservations(usuario_id, LocalDateTime.now(), paginacion);
        return ResponseEntity.ok(reservas.map(DatosListadoReserva::new));
    }

    @DeleteMapping("/{id}")
    @org.springframework.transaction.annotation.Transactional
    public ResponseEntity eliminar(@PathVariable Long id) {
        if(!reservaRepository.existsById(id)){
            throw new ValidacionException("No existe una reserva con el id informado");
        }
        reservaRepository.deleteById(id);
        return ResponseEntity.ok("reserva eliminada");
    }
    @GetMapping("/historial")
    public ResponseEntity<Page<DatosListadoReserva>> listarHistorialReservasPorUsuario(
            @RequestParam Long usuario_id,
            @PageableDefault(size = 5) Pageable paginacion) {

        LocalDateTime now = LocalDateTime.now().minusHours(1);
        Page<Reserva> reservas = reservaRepository.findByUsuarioIdAndCompletedReservations(usuario_id, now, paginacion);

        return ResponseEntity.ok(reservas.map(DatosListadoReserva::new));
    }





    @PutMapping("/{id}")
    @Transactional
    public ResponseEntity<DatosRespuestaReservaActualizado> actualizar(@RequestBody @Valid DatosActualizarReserva datos, @PathVariable Long id) {

            if(!reservaRepository.existsById(id)){
                throw new ValidacionException("No existe una reserva con el id informado");
            }

            var reserva = reservaRepository.getReferenceById(id);
            reserva.actualizarInformaciones(datos);
            var reservaActualizada= new DatosRespuestaReservaActualizado(reserva);
        return ResponseEntity.ok(reservaActualizada);
    }

    @GetMapping("/reservas_hoy")
    public ResponseEntity<Page<DatosListadoRestaurantesHoy>> listarReservasHoyPorRestaurante(
        @RequestParam Long usuario_id,
        @PageableDefault(size = 5) Pageable paginacion) {
        Usuario administrador = usuarioRepository.findById(usuario_id).get();
        Restaurante restaurantes = restauranteRepository.findByAdministrador(administrador).get();
        var restaurante_id = restaurantes.getId();
        Page<Reserva> reservas = reservaRepository.findByRestauranteIdAndUpcomingReservations(restaurante_id,LocalDate.now(), paginacion);
        return ResponseEntity.ok(reservas.map(DatosListadoRestaurantesHoy::new));
    }


}