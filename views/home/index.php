<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurantes en Ibagué - ¡Reserva fácil y rápido!</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #1e1e2e;
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
        }
        /* Navbar */
        .navbar {
            background-color: #29293d;
            padding: 15px;
            border-radius: 10px;
        }
        .navbar a {
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 15px;
            transition: 0.3s;
            display: flex;
            align-items: center;
        }
        .navbar a i {
            margin-right: 8px;
            font-size: 18px;
        }
        .navbar a:hover {
            background-color: #ff7300;
            border-radius: 5px;
        }
        .nav-container {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        /* Hero */
        .hero {
            background: linear-gradient(to right, #ff4d00, #ff7300);
            color: white;
            text-align: center;
            padding: 100px 20px;
            border-radius: 10px;
        }
        .hero h1 {
            font-size: 2.8rem;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }
        .hero p {
            font-size: 1.3rem;
            font-weight: 300;
        }
        .form-control {
            border-radius: 25px;
            border: none;
        }
        .btn-warning {
            background-color: #ff7300;
            border: none;
            font-weight: bold;
            border-radius: 25px;
            padding: 10px 20px;
            font-size: 1.1rem;
            transition: 0.3s;
        }
        .btn-warning:hover {
            background-color: #e65c00;
        }
        /* Sección Beneficios */
        .benefits {
            text-align: center;
            margin-top: 50px;
        }
        .benefits h2 {
            color: #ffcc00;
        }
        .benefits p {
            font-size: 1.2rem;
            margin-bottom: 15px;
        }
        /* Tarjetas de restaurantes */
        .card {
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
            background-color: #29293d;
            color: white;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card img {
            height: 200px;
            object-fit: cover;
            border-bottom: 5px solid #ff7300;
        }
        .rating {
            color: #ffcc00;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Menú de navegación -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.html"><i class="fa-solid fa-user"></i> Iniciar sesión</a>
            <a href="restaurantes.html"><i class="fa-solid fa-utensils"></i> Restaurantes</a>
            <a href="#"><i class="fa-solid fa-tag"></i> Promociones</a>
            <a href="#"><i class="fa-solid fa-envelope"></i> Contacto</a>
        </div>
    </nav>

    <!-- Sección de mensaje de sesión -->
    <?php 
        if(isset($_SESSION['email'])) {
            echo "<div class='container text-center'><h4>Bienvenido al sistema usuario " . $_SESSION['email'] . "</h4></div>";
        } else {
            echo "<p class='container text-center'>No tiene acceso al sistema</p>";
        }
    ?>

    <!-- Hero -->
    <header class="hero">
        <h1>Descubre los Mejores Restaurantes de Ibagué</h1>
        <p>Tu próxima experiencia gastronómica está a solo un clic de distancia. Encuentra los sabores que amas y reserva en segundos.</p>
        <input type="text" class="form-control w-50 mx-auto" placeholder="Buscar por nombre, tipo de cocina...">
        <button class="btn btn-warning mt-3">Explorar Restaurantes</button>
    </header>

    <!-- Beneficios -->
    <section class="container benefits">
        <h2>¿Por qué reservar con nosotros?</h2>
        <p>✅ Accede a los restaurantes más exclusivos de la ciudad.</p>
        <p>✅ Reserva de manera rápida, fácil y segura.</p>
        <p>✅ Descubre promociones y descuentos especiales solo para ti.</p>
        <p>✅ Lee opiniones reales de otros clientes antes de reservar.</p>
        <p>✅ Recibe atención personalizada y recomendaciones únicas.</p>
    </section>

    <!-- Sección de restaurantes -->
    <div class="container mt-5">
        <h2 class="text-center text-warning">Sabores que debes probar</h2>
        <p class="text-center">Te presentamos los restaurantes mejor valorados por nuestros usuarios. ¡No te los puedes perder!</p>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card shadow-lg">
                    <img src="assets/la-ricotta.jpg" class="card-img-top" alt="La Ricotta Ibagué">
                    <div class="card-body text-center">
                        <h5 class="card-title">La Ricotta Ibagué</h5>
                        <p class="rating">&#9733;&#9733;&#9733;&#9733;&#9734; 103 opiniones</p>
                        <p class="card-text">Italiana, Europea - $$ - $$$</p>
                        <p class="text-muted">"Una explosión de sabores en cada bocado."</p>
                        <a href="/views/Booking/create.php" class="btn btn-warning">Reservar ahora</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
