ALTER TABLE calificaciones
    DROP FOREIGN KEY calificaciones_ibfk_1, -- si ya existía
    DROP COLUMN reserva_id; -- si ya existía

