<?php
// Incluir el helper de sesión y la conexión a la base de datos
require_once __DIR__ . '/../../config/session_helper.php';

// Iniciar la sesión de manera segura
iniciar_sesion();

// Obtener el mensaje y el tipo de mensaje de la sesión
$mensaje = $_SESSION['mensaje'] ?? null;
$tipo_mensaje = $_SESSION['tipo_mensaje'] ?? null;

// Limpiar el mensaje de la sesión después de mostrarlo
unset($_SESSION['mensaje']);
unset($_SESSION['tipo_mensaje']);
session_write_close();

// Obtener la reserva a editar
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM reservas WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $reserva = $result->fetch_assoc();
}

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombre_completo = $_POST['nombre_completo'];
    $fecha_reserva = $_POST['fecha_reserva'];
    $hora_reserva = $_POST['hora_reserva'];
    $motivo = $_POST['motivo'];
    $telefono = $_POST['telefono'];
    $numero_personas = $_POST['numero_personas'];
    $requisitos_especiales = $_POST['requisitos_especiales'];
    $alergias_intolerancias = $_POST['alergias_intolerancias'];

    $query = "UPDATE reservas SET nombre_completo=?, fecha_reserva=?, hora_reserva=?, motivo=?, telefono=?, numero_personas=?, requisitos_especiales=?, alergias_intolerancias=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssisii", $nombre_completo, $fecha_reserva, $hora_reserva, $motivo, $telefono, $numero_personas, $requisitos_especiales, $alergias_intolerancias, $id);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Reserva actualizada exitosamente";
        $_SESSION['tipo_mensaje'] = "success";
        header("Location: reservas.php");
    } else {
        $_SESSION['mensaje'] = "Error al actualizar la reserva";
        $_SESSION['tipo_mensaje'] = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Reserva</title>
    <link rel="stylesheet" href="../../styles-booking.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <h1>EDITAR RESERVA</h1>

    <!-- Formulario de edición -->
    <form action="editar_reserva.php" method="post" id="reservaForm">
        <input type="hidden" name="id" value="<?= $reserva['id'] ?>">

        <label for="nombre_completo">Nombre Completo:</label><br>
        <input type="text" id="nombre_completo" name="nombre_completo" value="" required><br><br>

        <label for="fecha_reserva">Fecha de Reserva:</label><br>
        <input type="date" id="fecha_reserva" name="fecha_reserva" value="" required><br><br>

        <label for="hora_reserva">Hora de Reserva:</label><br>
        <input type="time" id="hora_reserva" name="hora_reserva" value="" required><br><br>

        <label for="motivo">Motivo de la Reserva:</label><br>
        <textarea id="motivo" name="motivo" rows="4" cols="50" required></textarea><br><br>

        <label for="telefono">Teléfono de Contacto:</label><br>
        <input type="tel" id="telefono" name="telefono" pattern="[0-9]{3}[0-9]{3}[0-9]{4}" placeholder="Formato: 1234567890" value="" required><br><br>

        <label for="numero_personas">Número de Personas:</label><br>
        <input type="number" id="numero_personas" name="numero_personas" min="1" value="" required><br><br>

        <label for="requisitos_especiales">Requisitos Especiales:</label><br>
        <textarea id="requisitos_especiales" name="requisitos_especiales" rows="4" cols="50"></textarea><br><br>

        <label for="alergias_intolerancias">Alergias o Intolerancias Alimenticias:</label><br>
        <textarea id="alergias_intolerancias" name="alergias_intolerancias" rows="4" cols="50"></textarea><br><br>

        <input type="submit" value="Guardar Cambios">
        <br>
        <button type="button" onclick="window.history.back();" class="btn-volver">Volver</button>
    </form>

    <!-- Validaciones Formulario -->
    <script>
        <?php if ($mensaje): ?>
            Swal.fire({
                icon: '<?= $tipo_mensaje === "success" ? "success" : "error" ?>',
                title: '<?= $tipo_mensaje === "success" ? "Éxito" : "Error" ?>',
                text: '<?= $mensaje ?>',
                confirmButtonText: 'Aceptar',
                background: '#f4f1de',
                color: '#1b4332',
                confirmButtonColor: '#28a745'
            });
        <?php endif; ?>

        document.getElementById('reservaForm').addEventListener('submit', function(event) {

            const fechaReserva = document.getElementById('fecha_reserva').value;
            const horaReserva = document.getElementById('hora_reserva').value;
            const numPersonas = document.getElementById('numero_personas').value;
            const correo = document.getElementById('correo').value;
            const telefono = document.getElementById('telefono').value;
            const nombreCompleto = document.getElementById('nombre_completo').value.trim();
            const requisitosEspeciales = document.getElementById('requisitos_especiales').value.trim();
            const alergias = document.getElementById('alergias_intolerancias').value.trim();

            const regexCorreo = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            const regexTelefono = /^[0-9]{10}$/;
            const regexNombre = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;

            const horaApertura = "11:00"; // 11:00 AM
            const horaCierre = "17:00";   // 05:00 PM

            const hoy = new Date();
            hoy.setHours(0, 0, 0, 0);
            const fechaSeleccionada = new Date(fechaReserva);
            const horaActual = hoy.getHours().toString().padStart(2, '0') + ":" + hoy.getMinutes().toString().padStart(2, '0');

            // Función para mostrar las alertas personalizadas
            function mostrarAlerta(icono, titulo, texto) {
                event.preventDefault();
                Swal.fire({
                    icon: icono,
                    title: titulo,
                    text: texto,
                    confirmButtonText: 'Aceptar',
                    background: '#f4f1de',
                    color: '#1b4332',
                    confirmButtonColor: '#28a745'
                });
            }

            // Validación de nombre
            if (nombreCompleto.length < 5 || !regexNombre.test(nombreCompleto) || !nombreCompleto.includes(' ')) {
                mostrarAlerta('error', 'Nombre inválido', 'Ingresa tu nombre completo con al menos 2 palabras y sin números o caracteres especiales.');
                return;
            }

            // Validación de número de teléfono
            if (!regexTelefono.test(telefono)) {
                mostrarAlerta('error', 'Teléfono inválido', 'El teléfono debe contener exactamente 10 dígitos numéricos.');
                return;
            }

            // Validación de fecha (no puede ser anterior a la fecha actual)
            if (fechaSeleccionada < hoy) {
                mostrarAlerta('error', 'Fecha inválida', 'La fecha de reserva no puede ser anterior a la fecha actual.');
                return;
            }

            // Validar que si se escoge la fecha de hoy, la hora no sea menor que la actual
            if (fechaSeleccionada.toDateString() === hoy.toDateString() && horaReserva < horaActual) {
                mostrarAlerta('error', 'Hora inválida', 'No puedes reservar en un horario anterior al actual.');
                return;
            }

            // Validación de horario del restaurante
            if (horaReserva < horaApertura || horaReserva > horaCierre) {
                mostrarAlerta('error', 'Horario no disponible', 'El restaurante solo recibe reservas entre 11:00 AM y 5:00 PM.');
                return;
            }

            // Validación de límite de personas (máx. 30)
            if (numPersonas > 30) {
                mostrarAlerta('error', 'Límite excedido', 'No puedes reservar para más de 30 personas.');
                return;
            }

            // Validación de correo electrónico
            if (!regexCorreo.test(correo)) {
                mostrarAlerta('error', 'Correo inválido', 'Por favor, ingresa un correo válido. Ejemplo: usuario@dominio.com.');
                return;
            }

            // Validación de requisitos especiales
            if (requisitosEspeciales.length > 0 && requisitosEspeciales.length < 5) {
                mostrarAlerta('warning', 'Descripción muy corta', 'Si indicas requisitos especiales, la descripción debe tener al menos 5 caracteres.');
                return;
            }

            // Validación de alergias
            if (alergias.length > 0 && alergias.length < 5) {
                mostrarAlerta('warning', 'Descripción de alergias muy corta', 'Si indicas alergias, la descripción debe tener al menos 5 caracteres.');
                return;
            }

        });

        // Validación en tiempo real del correo
        document.getElementById('correo').addEventListener('input', function() {
            let correo = this.value;
            let regexCorreo = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            if (!regexCorreo.test(correo)) {
                this.style.border = "2px solid red";
            } else {
                this.style.border = "2px solid green";
            }
        });
    </script>
</body>
</html>