<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Reserva</title>
    <link rel="stylesheet" href="../../styles-booking.css">
</head>
<body>
    <h1>RESERVAR</h1>
    <form action="../../controllers/ReservationController.php?action=register" method="POST">
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
</body>
</html>