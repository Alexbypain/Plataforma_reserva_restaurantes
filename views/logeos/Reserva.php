<?php
// Puedes incluir aquí cualquier lógica PHP que necesites
$view = __DIR__ . '/success.php'; // Esto puede ser opcional si no lo necesitas
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva Exitosa</title>
    <style>
        /* Estilos CSS */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .reservation-success {
            text-align: center;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            max-width: 500px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .reservation-success h2 {
            color: #4CAF50;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .reservation-success p {
            color: #333;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .btn-redirect {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .btn-redirect:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="reservation-success">
        <h2>Reserva creada con éxito</h2>
        <p>Gracias por su reserva. Nos pondremos en contacto con usted pronto.</p>
        <a href="/index.php" class="btn-redirect">Aceptar</a>
    </div>
</body>
</html>