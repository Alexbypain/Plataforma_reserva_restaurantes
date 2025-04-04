package plataforma.reserva.restaurantes.controller;


import jakarta.validation.Valid;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.security.authentication.AuthenticationManager;
import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;
import org.springframework.security.core.Authentication;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.web.bind.annotation.*;
import plataforma.reserva.restaurantes.domain.dto.DatosAutenticacionUsuario;
import plataforma.reserva.restaurantes.domain.dto.DatosCrearUsuario;
import plataforma.reserva.restaurantes.domain.entities.Usuario;
import plataforma.reserva.restaurantes.domain.repository.UsuarioRepository;
import plataforma.reserva.restaurantes.infra.security.DatosJWTToken;
import plataforma.reserva.restaurantes.infra.security.TokenService;

@RestController
@RequestMapping("/login")
public class AutenticacionController {

    @Autowired
    private AuthenticationManager authenticationManager;

    @Autowired
    private TokenService tokenService;

    @Autowired
    private UsuarioRepository usuarioRepository;

    @Autowired
    private PasswordEncoder passwordEncoder;



    @PostMapping
    public ResponseEntity autenticarUsuario(@RequestBody @Valid DatosAutenticacionUsuario datosAutenticacionUsuario) {
        Authentication authToken = new UsernamePasswordAuthenticationToken(datosAutenticacionUsuario.email(),
                datosAutenticacionUsuario.password());
        var usuarioAutenticado = authenticationManager.authenticate(authToken);
        var JWTtoken = tokenService.generarToken((Usuario) usuarioAutenticado.getPrincipal());
        return ResponseEntity.ok(new DatosJWTToken(JWTtoken));
    }

    @PostMapping("/register")
    public ResponseEntity crearUsuario(@RequestBody @Valid DatosCrearUsuario datosCrearUsuario){
        if (usuarioRepository.findByEmail(datosCrearUsuario.email()) != null) {
            return ResponseEntity.badRequest().body("El usuario ya existe");
        }
        Usuario usuario = new Usuario();
        usuario.setNombre(datosCrearUsuario.nombre());
        usuario.setEmail(datosCrearUsuario.email());
        // Encriptar la contraseña
        usuario.setContrasenia(passwordEncoder.encode(datosCrearUsuario.password()));
        // Asignar un rol por defecto, por ejemplo USER
        usuario.setRol(plataforma.reserva.restaurantes.domain.enums.Rol.USER);

        usuarioRepository.save(usuario);

        return ResponseEntity.status(HttpStatus.CREATED).body("Usuario creado exitosamente");


    }


}
