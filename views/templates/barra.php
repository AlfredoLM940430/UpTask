
<div class="barra-mobile">
    <h1>UpTask</h1>

    <div class="menu">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-menu-2" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round" id="mobile-menu">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <line x1="4" y1="6" x2="20" y2="6" />
            <line x1="4" y1="12" x2="20" y2="12" />
            <line x1="4" y1="18" x2="20" y2="18" />
        </svg>
    </div>
</div>

<div class="barra">
    <p>Hola <span><?php echo $_SESSION['nombre']; ?></span></p>

    <a href="/logout" class="cerrar-session">Cerrar sesión</a>
</div>