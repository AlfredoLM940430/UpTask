
const mobileBtn = document.querySelector('#mobile-menu');
const cerrarBtn = document.querySelector('#cerrar-menu');
const sidebar = document.querySelector('.sidebar');

if(mobileBtn) {
    mobileBtn.addEventListener('click', function() {
        sidebar.classList.add('mostrar')
    });
}

if(cerrarBtn) {
    cerrarBtn.addEventListener('click', function() {  
        sidebar.classList.add('ocultar');

        setTimeout(() => {
            sidebar.classList.remove('mostrar');  
            sidebar.classList.remove('ocultar');  
        }, 1000);

    });
}



window.addEventListener('resize', function() {

    //Elimina mostrar en pantalla grande
    const ancho = document.body.clientWidth;
    
    if(ancho >= 768) {
        sidebar.classList.remove('mostrar');
    }
});