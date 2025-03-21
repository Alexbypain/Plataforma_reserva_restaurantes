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
    <title>Mis Reservas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../styles-booking.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<div class="container-listar mt-5">
    <h1 class="text-center mb-4">📅 MIS RESERVAS</h2>

    <!-- Mensajes de éxito o error -->
    <?php if ($mensaje): ?>
        <script>
            Swal.fire({
                icon: '<?= $tipo_mensaje === "success" ? "success" : "error" ?>',
                title: '<?= $tipo_mensaje === "success" ? "Éxito" : "Error" ?>',
                text: '<?= $mensaje ?>',
                confirmButtonText: 'Aceptar',
                background: '#f4f1de',
                color: '#1b4332',
                confirmButtonColor: '#28a745'
            });
        </script>
    <?php endif; ?>

    <div class="table-container-listar">
        <div class="table-responsive">
            <table class="table-listar table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Ubicación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <a href="edit.php" class="btn btn-edit">
                                ✏️ EDITAR
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
