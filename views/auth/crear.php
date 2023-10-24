<div class="contenedor crear">

    <?php include_once __DIR__ . '/../templates/nombre-sitio.php' ?>
    <?php include_once __DIR__ . '/../templates/alertas.php' ?>

    <div class="contenedor-sm">

        <p class="descripcion-pagina">Crea tu Cuenta en UpTask</p>

        <form action="/crear" method="POST" class="formulario">  

            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" placeholder="Tu Nombre" name="nombre" value="<?php echo $usuario->nombre ?>">
            </div>

            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Tu Email" name="email" value="<?php echo $usuario->email ?>">
            </div>

            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" placeholder="Tu Contraseña" name="password">
            </div>

            <div class="campo">
                <label for="password2">Confirma tu Contraseña</label>
                <input type="password" id="password" placeholder="Confirmar Contraseña" name="password2">
            </div>

            <input type="submit" class="boton" value="Crear Cuenta">

        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes cuenta? Inicia Sesión!</a>
            <a href="/olvide">¿Olvidaste tu Contraseña?</a>
        </div>

    </div>
</div>