<?php
// Iniciar la sesión
session_start();

// Obtener el mensaje y el tipo de mensaje de la sesión
$mensaje = $_SESSION['mensaje'] ?? null;
$tipo_mensaje = $_SESSION['tipo_mensaje'] ?? null;

// Limpiar el mensaje de la sesión después de mostrarlo
unset($_SESSION['mensaje']);
unset($_SESSION['tipo_mensaje']);
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
    <form action="../../controllers/ReservaControlle.php" method="POST">
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
    </script>
</body>
</html>