package plataforma.reserva.restaurantes.domain.entities;


import jakarta.persistence.*;
import lombok.*;
import plataforma.reserva.restaurantes.domain.dto.DatosActualizarReserva;
import plataforma.reserva.restaurantes.domain.dto.DatosActualizarRestaurante;

import java.time.LocalDateTime;
import java.time.LocalTime;
import java.time.format.DateTimeParseException;
import java.util.List;

@Getter
@Setter
@NoArgsConstructor
@AllArgsConstructor
@Table(name="restaurantes")
@Entity(name="Restaurante")
@EqualsAndHashCode(of="id")
public class Restaurante {
    @Id
    @GeneratedValue(strategy= GenerationType.IDENTITY)
    @Column(name="restaurante_id")
    private Long id;

    private String nombre;

    private String direccion;
    private String telefono;
    private String email;
    private int capacidad;
    private LocalTime horario_apertura;
    private LocalTime horario_cierre;

    private Double rating;
    @Lob
    @Column(columnDefinition = "LONGBLOB") // Para bases de datos MySQL/MariaDB
    private byte[] imagen;

    @OneToOne
    @JoinColumn(name = "admin_id", unique = true)
    private Usuario administrador; // Solo admins pueden tener restaurante


    // Relación One-to-Many: un restaurante tiene muchas reservas
    @OneToMany(mappedBy = "restaurante", cascade = CascadeType.ALL, orphanRemoval = true)
    private List<Reserva> reservas;


    public void actualizarInformaciones(DatosActualizarRestaurante datos) {
        // Nombre
        if (datos.nombre() != null && !datos.nombre().isBlank()) {
            this.nombre = datos.nombre();
        }
        // Capacidad
        if (datos.capacidad() != null && !datos.capacidad().isBlank()) {
            try {
                this.capacidad = Integer.parseInt(datos.capacidad());
            } catch (NumberFormatException ignored) { }
        }
        // Dirección
        if (datos.direccion() != null && !datos.direccion().isBlank()) {
            this.direccion = datos.direccion();
        }
        // Email
        if (datos.email() != null && !datos.email().isBlank()) {
            this.email = datos.email();
        }
        // Teléfono
        if (datos.telefono() != null && !datos.telefono().isBlank()) {
            this.telefono = datos.telefono();
        }
        // Horario de apertura
        if (datos.horario_apertura() != null && !datos.horario_apertura().isBlank()) {
            try {
                this.horario_apertura = LocalTime.parse(datos.horario_apertura());
            } catch (DateTimeParseException ignored) { }
        }
        // Horario de cierre
        if (datos.horario_cierre() != null && !datos.horario_cierre().isBlank()) {
            try {
                this.horario_cierre = LocalTime.parse(datos.horario_cierre());
            } catch (DateTimeParseException ignored) { }
        }
    }




}
