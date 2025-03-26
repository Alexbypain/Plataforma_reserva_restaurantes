ALTER TABLE reservas ADD COLUMN calificacion_id BIGINT;

ALTER TABLE reservas
ADD CONSTRAINT fk_reservas_calificacion
FOREIGN KEY (calificacion_id) REFERENCES calificacionES(calificacion_id)
ON DELETE CASCADE ON UPDATE CASCADE;