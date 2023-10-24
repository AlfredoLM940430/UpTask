<div class="contenedor olvide">

    <?php include_once __DIR__ . '/../templates/nombre-sitio.php' ?>
    <?php include_once __DIR__ . '/../templates/alertas.php' ?>

    <div class="contenedor-sm">

        <p class="descripcion-pagina">Recuperar Nueva Contraseña</p>

        <form action="/olvide" method="POST" class="formulario">  

            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Tu Email" name="email">
            </div>

            <input type="submit" class="boton" value="Recuperar Contraseña">

        </form>

        <div class="acciones">
            <a href="/">Iniciar Sesión</a>
            <a href="/crear">¿Aun no tienes cuenta? Obten una!</a>
        </div>

    </div>
</div>