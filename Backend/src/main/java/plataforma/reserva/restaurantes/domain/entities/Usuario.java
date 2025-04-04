package plataforma.reserva.restaurantes.domain.entities;


import jakarta.persistence.*;
import lombok.*;
import org.springframework.security.core.GrantedAuthority;
import org.springframework.security.core.authority.SimpleGrantedAuthority;
import org.springframework.security.core.userdetails.UserDetails;
import plataforma.reserva.restaurantes.domain.enums.Rol;

import java.util.Collection;
import java.util.List;

@Table(name="usuarios")
@Entity(name="Usuario")
@EqualsAndHashCode(of="id")
@Getter
@Setter
@NoArgsConstructor
@AllArgsConstructor
public class Usuario implements UserDetails {
    @Id
    @GeneratedValue(strategy= GenerationType.IDENTITY)
    @Column(name="usuario_id")
    private Long id;

    private String nombre;

    @Column(unique = true)
    private String email;

    private String contrasenia;

    @Enumerated(EnumType.STRING)
    private Rol rol; // Guarda si es ADMIN o USER

    @OneToOne(mappedBy = "administrador", cascade = CascadeType.ALL, fetch = FetchType.LAZY)
    private Restaurante restaurante;

    @OneToMany(mappedBy = "usuario", cascade = CascadeType.ALL, fetch = FetchType.LAZY)
    private List<Reserva> reservas;

    // Relación One-to-Many: un restaurante tiene muchas reservas
    @OneToMany(mappedBy = "usuario", cascade = CascadeType.ALL, orphanRemoval = true)
    private List<Calificacion> calificaciones;












    @Override
    public Collection<? extends GrantedAuthority> getAuthorities() {
        return List.of(new SimpleGrantedAuthority("ROLE_" + rol.name()));
    }

    @Override
    public String getPassword() {
        return contrasenia;
    }

    @Override
    public String getUsername() {
        return email;
    }

    @Override
    public boolean isAccountNonExpired() {
        return UserDetails.super.isAccountNonExpired();
    }

    @Override
    public boolean isAccountNonLocked() {
        return UserDetails.super.isAccountNonLocked();
    }

    @Override
    public boolean isCredentialsNonExpired() {
        return UserDetails.super.isCredentialsNonExpired();
    }

    @Override
    public boolean isEnabled() {
        return UserDetails.super.isEnabled();
    }
}
