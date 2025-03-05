<?php include_once "views/shared/LoginHeader.php"?>

    <div class="login-container">
        <!-- Título dinámico con un icono -->
        <h2>🍽️ <?= $data['titulo'] ?></h2>
        
        <!-- Muestra de error (si existe) -->
        <?php if(isset($data['error'])): ?>
            <div class="error">
                <?= $data['error'] ?>
            </div>
        <?php endif; ?>
        
        <!-- Formulario de inicio de sesión -->
        <form action="index.php?controlador=logeo&accion=login" method="post" id="loginForm">
            <input type="email" name="email" id="email" placeholder="Correo electrónico" required>
            <input type="password" name="contrasenia" id="password" placeholder="Contraseña" required>
            <label>
                <input type="checkbox" id="rememberMe" name="rememberMe"> Recordarme
            </label>
            <button type="submit">Iniciar Sesión</button>
        </form>
        
        <!-- Enlaces adicionales -->
        <p><a href="#">¿Olvidaste tu contraseña?</a></p>
        <p>¿No tienes cuenta? <a href="index.php?controlador=logeo&accion=verRegistro">Regístrate aquí</a></p>
    </div>
    <!--script src="script.js"></script-->
</body>

<?php include_once "views/shared/footer.php"?>

