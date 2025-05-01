package plataforma.reserva.restaurantes.domain.entities;


import jakarta.persistence.*;
import jakarta.validation.Valid;
import lombok.*;
import plataforma.reserva.restaurantes.domain.dto.DatosActualizarReserva;
import plataforma.reserva.restaurantes.domain.enums.Estado;

import java.time.LocalDateTime;

@Getter
@Setter
@NoArgsConstructor
@AllArgsConstructor
@Table(name="reservas")
@Entity(name="Reserva")
@EqualsAndHashCode(of="id")
public class Reserva {
    @Id
    @GeneratedValue(strategy= GenerationType.IDENTITY)
    @Column(name="reserva_id")
    private Long id;

    private LocalDateTime fecha;
    private String motivo;
    private int cantidad_personas;
    private String requisitos_especiales;
    private String alergias;

    @Enumerated(EnumType.STRING)
    private Estado estado;

    @ManyToOne
    @JoinColumn(name = "restaurante_id", nullable = false)
    private Restaurante restaurante;


    @ManyToOne
    @JoinColumn(name = "usuario_id", nullable = false)
    private Usuario usuario; // Solo admins pueden tener restaurante

    @OneToOne
    @JoinColumn(name = "calificacion_id", unique = true)
    private Calificacion calificacion; // Solo admins pueden tener restaurante

    public void actualizarInformaciones(DatosActualizarReserva datos) {
        this.fecha= LocalDateTime.parse(datos.fecha());
        this.motivo=datos.motivo();
        this.cantidad_personas= Integer.parseInt(datos.cantidadPersonas());
        this.requisitos_especiales= datos.requisitosEspeciales();
        this.alergias=datos.alergias();
    }
    
}