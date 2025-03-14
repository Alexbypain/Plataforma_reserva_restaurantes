<?php
// Incluir el helper de sesión
require_once "../../config/session_helper.php";

// Iniciar la sesión de manera segura
iniciar_sesion();

// Obtener el mensaje y el tipo de mensaje de la sesión
$mensaje = $_SESSION['mensaje'] ?? null;
$tipo_mensaje = $_SESSION['tipo_mensaje'] ?? null;

// Limpiar el mensaje de la sesión después de mostrarlo
unset($_SESSION['mensaje']);
unset($_SESSION['tipo_mensaje']);

// Guardar la sesión después de modificarla
session_write_close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Reserva</title>
    <link rel="stylesheet" href="../../styles-booking.css">
    <!-- Incluir SweetAlert2 para alertas bonitas -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <h1>RESERVAR</h1>

    <!-- Formulario de reserva -->
    <!-- Por esto: -->
    <form action="../../index.php?controlador=reserva&accion=crear" method="post" id="reservaForm">
    <!-- Campos del formulario -->
        <label for="nombre_completo">Nombre Completo:</label><br>
        <input type="text" id="nombre_completo" name="nombre_completo" required><br><br>

        <label for="fecha_reserva">Fecha de Reserva:</label><br>
        <input type="date" id="fecha_reserva" name="fecha_reserva" required><br><br>

        <label for="hora_reserva">Hora de Reserva:</label><br>
        <input type="time" id="hora_reserva" name="hora_reserva" required><br><br>

        <label for="motivo">Motivo de la Reserva:</label><br>
        <textarea id="motivo" name="motivo" rows="4" cols="50" required></textarea><br><br>

        <label for="telefono">Teléfono de Contacto:</label><br>
        <input type="tel" id="telefono" name="telefono" pattern="[0-9]{3}[0-9]{3}[0-9]{4}" placeholder="Formato: 1234567890" required><br><br>

        <label for="numero_personas">Número de Personas:</label><br>
        <input type="number" id="numero_personas" name="numero_personas" min="1" required><br><br>

        <label for="requisitos_especiales">Requisitos Especiales:</label><br>
        <textarea id="requisitos_especiales" name="requisitos_especiales" rows="4" cols="50"></textarea><br><br>

        <label for="alergias_intolerancias">Alergias o Intolerancias Alimenticias:</label><br>
        <textarea id="alergias_intolerancias" name="alergias_intolerancias" rows="4" cols="50"></textarea><br><br>

        <input type="submit" value="Reservar">
    </form>

    <!-- Script para mostrar la alerta -->
    <script>
        <?php if ($mensaje): ?>
            Swal.fire({
                icon: '<?= $tipo_mensaje === "success" ? "success" : "error" ?>',
                title: '<?= $tipo_mensaje === "success" ? "Éxito" : "Error" ?>',
                text: '<?= $mensaje ?>',
                confirmButtonText: 'Aceptar'
            });
        <?php endif; ?>
        
        // Validación adicional del formulario
        document.getElementById('reservaForm').addEventListener('submit', function(event) {
            const fechaReserva = document.getElementById('fecha_reserva').value;
            const horaReserva = document.getElementById('hora_reserva').value;
            
            // Validar que la fecha no sea anterior a hoy
            const hoy = new Date();
            hoy.setHours(0, 0, 0, 0);
            const fechaSeleccionada = new Date(fechaReserva);
            
            if (fechaSeleccionada < hoy) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'La fecha de reserva no puede ser anterior a hoy',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    </script>
</body>
</html>