package plataforma.reserva.restaurantes.domain.entities;

import jakarta.persistence.*;
import lombok.AllArgsConstructor;
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;

@AllArgsConstructor
@NoArgsConstructor
@Getter
@Setter
@Table(name="calificaciones")
@Entity(name="Calificacion")
public class Calificacion {
    @Id
    @GeneratedValue(strategy= GenerationType.IDENTITY)
    @Column(name="calificacion_id")
    private Long id;

    private int rating;
    private String comentario;


    @OneToOne(mappedBy = "calificacion", cascade = CascadeType.ALL, fetch = FetchType.LAZY)
    private Reserva reserva;

}