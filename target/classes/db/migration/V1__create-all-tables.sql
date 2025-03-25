create table IF NOT EXISTS usuarios(
    `usuario_id` bigint not null auto_increment,
    `nombre` varchar(50) not null,
    `rol` varchar(40) not null,
    `email` varchar(60) UNIQUE,
    `contrasenia` varchar(70) not null,
    PRIMARY KEY (`usuario_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

create table IF NOT EXISTS restaurantes(
    `restaurante_id` bigint not null auto_increment,
    `admin_id` bigint UNIQUE,
    `nombre` varchar(50) not null,
    `direccion` varchar(60) not null,
    `telefono` varchar(10) not null,
    `email` varchar(60) not null,
    `capacidad` tinyint not null,
    `horario_apertura` time,
    `horario_cierre` time,
    `imagen` longblob,
    PRIMARY KEY (`restaurante_id`),
    FOREIGN KEY (`admin_id`) REFERENCES usuarios(`usuario_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


create table IF NOT EXISTS reservas(
    `reserva_id` bigint not null auto_increment,
    `restaurante_id` bigint not null,
    `usuario_id` bigint not null,
    `fecha` datetime,
    `motivo` varchar(100),
    `cantidad_personas` tinyint,
    `requisitos_especiales` varchar(100),
    `alergias` varchar(100),
    `estado` varchar(50),
    PRIMARY KEY (`reserva_id`),
    FOREIGN KEY (`restaurante_id`) REFERENCES restaurantes(`restaurante_id`),
    FOREIGN KEY (`usuario_id`) REFERENCES usuarios(`usuario_id`)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



create table IF NOT EXISTS calificaciones(
    `calificacion_id` bigint not null auto_increment,
    `reserva_id` bigint not null,
    `rating` tinyint not null,
    `comentario` varchar(200) not null,
    PRIMARY KEY (`calificacion_id`),
    FOREIGN KEY (`reserva_id`) REFERENCES reservas(`reserva_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
