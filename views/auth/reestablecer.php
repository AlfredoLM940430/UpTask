<div class="contenedor reestablecer">

    <?php include_once __DIR__ . '/../templates/nombre-sitio.php' ?>

    <div class="contenedor-sm">

        <p class="descripcion-pagina">Coloca tu Nueva Contraseña</p>

        <?php include_once __DIR__ . '/../templates/alertas.php' ?>

        <?php if($mostrar) { ?>

        <form method="POST" class="formulario">  

            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" placeholder="Nueva Contraseña" name="password">
            </div>

            <input type="submit" class="boton" value="Iniciar Sesión">

        </form>

        <?php } ?>

        <div class="acciones">
            <a href="/crear">¿Aun no tienes cuenta? Obten una!</a>
            <a href="/olvide">¿Olvidaste tu Contraseña?</a>
        </div>

    </div>
</div>