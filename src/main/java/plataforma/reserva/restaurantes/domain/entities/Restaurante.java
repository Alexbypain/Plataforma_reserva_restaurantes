package plataforma.reserva.restaurantes.domain.entities;


import jakarta.persistence.*;
import lombok.*;

import java.time.LocalTime;
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

    @Lob
    @Column(columnDefinition = "LONGBLOB") // Para bases de datos MySQL/MariaDB
    private byte[] imagen;

    @OneToOne
    @JoinColumn(name = "admin_id", unique = true)
    private Usuario administrador; // Solo admins pueden tener restaurante


    // Relación One-to-Many: un restaurante tiene muchas reservas
    @OneToMany(mappedBy = "restaurante", cascade = CascadeType.ALL, orphanRemoval = true)
    private List<Reserva> reservas;



}
