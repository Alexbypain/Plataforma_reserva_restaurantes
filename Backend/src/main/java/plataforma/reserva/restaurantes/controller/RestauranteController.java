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

@RestController
@RequestMapping("/restaurantes")
public class RestauranteController {

    @Autowired
    private RestauranteRepository restauranteRepository;

    @Autowired
    private ReservaRepository reservaRepository;

    @Autowired
    private UsuarioRepository usuarioRepository;

    @PostMapping
    public ResponseEntity crearRestaurante(@RequestBody @Valid DatosCrearRestaurante datosCrearRestaurante){
        Restaurante restaurante=new Restaurante();
        restaurante.setNombre(datosCrearRestaurante.nombre());
        restaurante.setDireccion(datosCrearRestaurante.direccion());
        restaurante.setCapacidad(Integer.parseInt(datosCrearRestaurante.capacidad()));
        restaurante.setTelefono(datosCrearRestaurante.telefono());
        restaurante.setEmail(datosCrearRestaurante.email());
        restaurante.setHorario_apertura(LocalTime.parse(datosCrearRestaurante.horario_apertura()));
        restaurante.setHorario_cierre(LocalTime.parse(datosCrearRestaurante.horario_cierre()));



        // Convertir la cadena base64 a byte[]
        if (datosCrearRestaurante.imagen() != null && !datosCrearRestaurante.imagen().isEmpty()) {
            byte[] imagenBytes = Base64.getDecoder().decode(datosCrearRestaurante.imagen());
            restaurante.setImagen(imagenBytes);
        }

        Usuario usuario=usuarioRepository.findById(datosCrearRestaurante.administrador_id()).get();
        restaurante.setAdministrador(usuario);

        restauranteRepository.save(restaurante);
        return ResponseEntity.status(HttpStatus.CREATED).body("Restaurante creado exitosamente");


    }

    @GetMapping
    public ResponseEntity<Page<DatosListadoRestaurantes>> listadoRestaurantes(@PageableDefault(size = 5) Pageable paginacion) {
//        return medicoRepository.findAll(paginacion).map(DatosListadoMedico::new);

        return ResponseEntity.ok(restauranteRepository.findAll(paginacion).map(DatosListadoRestaurantes::new));
        // return ResponseEntity.ok(topicoRepository.findAllByStatus(ACTIVE,paginacion).map(DatosListadoTopico::new));
    }

    @GetMapping("/nombre")
    public ResponseEntity<Page<DatosListadoRestaurantes>> listadoRestaurantesPorNombre(
            @RequestParam String restaurante_nombre,
            @PageableDefault(size = 50) Pageable paginacion
    ){
        return ResponseEntity.ok(restauranteRepository.findByNombreContainingIgnoreCase(restaurante_nombre,paginacion).map(DatosListadoRestaurantes::new));



    }

    @PutMapping("/{id}")
    @Transactional
    public ResponseEntity<DatosRespuestaRestauranteActualizado> actualizar(@RequestBody @Valid DatosActualizarRestaurante datosActualizarRestaurante, @PathVariable Long id) {

        var admin=usuarioRepository.findById(id);

        var restaurante=restauranteRepository.findByAdministrador(admin.get());


        if(!restauranteRepository.existsById(restaurante.getId())){
            throw new ValidacionException("No existe un restaurante con el id informado");
        }


        if (datosActualizarRestaurante.imagen() != null && !datosActualizarRestaurante.imagen().isEmpty()) {
            byte[] imagenBytes = Base64.getDecoder().decode(datosActualizarRestaurante.imagen());
            restaurante.setImagen(imagenBytes);
        }

        restaurante.actualizarInformaciones(datosActualizarRestaurante);
        var restauranteActualizado= new DatosRespuestaRestauranteActualizado(restaurante);
        return ResponseEntity.ok(restauranteActualizado);
    }





}