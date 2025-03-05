<?php include_once "views/shared/loginHeader.php"?>

<div class="login-container">
        <!-- Título dinámico con un icono -->
        <h2>🍽️ <?= $data['titulo'] ?></h2>
        
        <!-- Muestra de error (si existe) -->
        <?php
            if(isset($data['error'])) {
                echo "<div class='alert alert-danger' role='alert'>  ";
                echo $data['error'];
                echo "</div>";
            }
        ?>
         <form action="index.php?controlador=logeo&accion=register" method="post">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="usuario" placeholder="Usuario" >
                <label for="usuario">Usuario</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" name="email" placeholder="Correo" >
                <label for="email">Correo Electronico</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="contrasenia" placeholder="Contraseña" >
                <label for="password">Contraseña</label>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
        </div>
        </body>

<?php include_once "views/shared/footer.php"?>

