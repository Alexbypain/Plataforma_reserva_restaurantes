<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .error-message {
            text-align: center;
            background-color: #ffebee;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: #c62828;
        }
        .error-message a {
            color: #1565c0;
            text-decoration: none;
        }
        .error-message a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="error-message">
        <h2>¡Algo salió mal!</h2>
        <p>No se pudo procesar tu reserva. Por favor, intenta nuevamente.</p>
        <a href="/views/Booking/create.php">Volver al inicio</a>
    </div>
</body>
</html>