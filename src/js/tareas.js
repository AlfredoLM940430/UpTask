(function() {

    obtenerTareas();
    let tareas = [];
    let filtradas = [];
    //Boton Modal Agregar tarea
    const nuevaTaresBTN = document.querySelector('#agregar-tarea');
    nuevaTaresBTN.addEventListener('click', function() {
        mostrarFormulario();
    });

    //Filtros
    const filtros = document.querySelectorAll('#filtros input[type="radio"]');
    filtros.forEach(radio => {
        radio.addEventListener('input', filtrarTareas);
    });

    function filtrarTareas(e) {

        const filtro = e.target.value;
        if(filtro !== '') {

            filtradas = tareas.filter(tarea => tarea.estado === filtro);
        } else {

            filtradas = [];
        }
        mostrarTareas();
    }

    async function obtenerTareas() {
        try {
            const id = obtenerPoryecto();
            const url = `/api/tareas?id=${id}`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();

            tareas = resultado.tareas;
            mostrarTareas();

        } catch (error) {
            console.log(error);
        }
    }

    function mostrarTareas() {

        limiarHTML();
        totalPendientes();
        totalCompletas();

        const arrayTareas = filtradas.length ? filtradas : tareas;

        if(arrayTareas.length === 0) {
            const contenedorTareas = document.querySelector('#listado-tareas');

            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No hay tareas';
            textoNoTareas.classList.add('no-tareas');

            contenedorTareas.appendChild(textoNoTareas);
            return;
        }

        const estados = {

            0: 'Pendiente',
            1: 'Completa'
        }

        arrayTareas.forEach(tarea => {
            const contenedorTarea = document.createElement('LI');
            contenedorTarea.dataset.tareaid = tarea.id;
            contenedorTarea.classList.add('tarea');

            const nombreTarea = document.createElement('P');
            nombreTarea.textContent = tarea.nombre;
            nombreTarea.ondblclick = function () {
                mostrarFormulario(editar = true, {...tarea});
            }

            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');

            //Botones
            const btnEstado = document.createElement('BUTTON');
            btnEstado.classList.add('estado-tarea');
            btnEstado.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstado.textContent = estados[tarea.estado];
            btnEstado.dataset.estadoTarea = tarea.estado;
            btnEstado.ondblclick = function () {
                camniarEstado({...tarea});
            }

            const btnEliminar = document.createElement('BUTTON');
            btnEliminar.classList.add('eliminar-tarea');
            btnEliminar.dataset.idTarea = tarea.id;
            btnEliminar.textContent = 'Eliminar';

            btnEliminar.ondblclick = function() {
                confirmarEliminar({...tarea});
            };

            opcionesDiv.appendChild(btnEstado);
            opcionesDiv.appendChild(btnEliminar);

            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);

            const listadoTareas = document.querySelector('#listado-tareas');
            listadoTareas.appendChild(contenedorTarea);

        });
    }

    function totalPendientes() {

        const totalPendientes = tareas.filter(tarea => tarea.estado === "0");
        
        const pendientesRadio = document.querySelector('#pendientes');

        if(totalPendientes.length === 0) {

            pendientesRadio.disabled = true;
        } else {

            pendientesRadio.disabled = false;
        }
    }

    function totalCompletas() {

        const totalCompletas = tareas.filter(tarea => tarea.estado === "1");
        
        const completadasRadio = document.querySelector('#completadas');

        if(totalCompletas.length === 0) {

            completadasRadio.disabled = true;
        } else {

            completadasRadio.disabled = false;
        }
    }

    function mostrarFormulario(editar = false, tarea = {}) {
        console.log(tarea);
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `

        <form class="formulario nueva-tarea">
            <legend>${editar ? 'Editar Tarea' : 'Añade una nueva tarea'}</legend>
            <div class="campo">
                <label>Tarea</label>
                <input 
                    type="text" 
                    name="tarea" 
                    placeholder="${tarea.nombre ? 'Editar' : 'Añadir Tarea al Proyecto Actual'}"
                    id="tarea" 
                    value="${tarea.nombre ? tarea.nombre : ''}"
                />
            </div>
            <div class="opciones">
                <input 
                    type="submit" 
                    class="submit-nueva-tarea" 
                    value="${tarea.nombre ? 'Guardar cambios' : 'Añadir Tarea'}"
                />
                <button type="button" class="cerrar-modal">Cancelar</button>
            </div>
        </form>

        `;

        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 0);

        modal.addEventListener('click', function(e) {
            e.preventDefault();

            if(e.target.classList.contains('cerrar-modal')) {
                
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(() => {
                    modal.remove();
                }, 500);
            }

            if(e.target.classList.contains('submit-nueva-tarea')) {

                const nombreTarea = document.querySelector('#tarea').value.trim();
        
                if(nombreTarea=== '') {
                    //Alerta error
                    mostrarAlerta('El nombre de la tarea es obligatorio', 'error', document.querySelector('.formulario legend'));
                    return;
                }

                if(editar) {
                    tarea.nombre = nombreTarea;
                    actualizarTarea(tarea);
                } else {

                    agregarTarea(nombreTarea);
                }
            }

        });

        document.querySelector('.dashboard').appendChild(modal);
    }

    function mostrarAlerta(mensaje, tipo, referencia) {

        //Previene multiples alertas
        const alertaPrevia = document.querySelector('.alerta');

        if(alertaPrevia) {
            alertaPrevia.remove();
        }

        const alerta = document.createElement('DIV');
        alerta.classList.add('alerta', tipo);
        alerta.textContent = mensaje;
        referencia.appendChild(alerta);

        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

        //Eliminar alerta
        setTimeout(() => {
            alerta.remove();
        }, 3500);
    }

    async function agregarTarea(tarea) {

        //Peticion
        const datos = new FormData();
        datos.append('nombre', tarea);
        datos.append('proyectoid', obtenerPoryecto());

        try {
            const url = 'http://localhost:3000/api/tarea';

            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();
            console.log(resultado);

            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.formulario legend'));

            if(resultado.tipo === 'exito') {
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                }, 1000);

                //Objeto tarea
                const tareaObj = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: "0",
                    proyectoid: resultado.proyectoid
                }

                tareas = [...tareas, tareaObj];
                mostrarTareas();

                console.log(tareaObj);
            }

        } catch (error) {
            console.log(error);
        }
    }

    function camniarEstado(tarea) {

        const nuevoEsdo = tarea.estado === "1" ? "0" : "1";
        tarea.estado = nuevoEsdo;
        actualizarTarea(tarea);
    }
    async function actualizarTarea(tarea) {

        const {estado, id, nombre, proyectoid} = tarea;

        const datos = new FormData();

        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoid', obtenerPoryecto());

        try {
            const url = 'http://localhost:3000/api/tarea/actualizar';
            const respuesta = await fetch(url , {
                method: 'POST',
                body: datos
            });

            const resultado = await  respuesta.json();

            if(resultado.respuesta.tipo === 'exito') {

                Swal.fire(
                    resultado.respuesta.mensaje,
                    resultado.respuesta.mensaje,
                    'success'
                );

                const modal = document.querySelector('.modal');

                if(modal) {
                    modal.remove();
                }
               
                tareas = tareas.map(tareaMemoria => {

                    if(tareaMemoria.id === id) {
                        tareaMemoria.estado = estado;
                        tareaMemoria.nombre = nombre;
                    }
                    return tareaMemoria; 
                });

                mostrarTareas(); 
            }
            
        } catch (error) {
            console.log(error);
        }

        // for(let valor of datos.values())  {
        //     console.log(valor);
        // }
    }

    function confirmarEliminar(tarea) {
        Swal.fire({
            title: '¿Eliminar Tarea?',
            text: "No se podrá revertir este cambio!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'OK'
          }).then((result) => {
            if (result.isConfirmed) {
              Swal.fire(
                'Tarea Eliminada!',
                'Tarea Eliminada Exitosamente.',
                'success'
              );
              eliminarTarea(tarea);
            }
          });
    }

    async function eliminarTarea(tarea) {

        const {estado, id, nombre} = tarea;
        const datos = new FormData();

        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoid', obtenerPoryecto());

        try {

            const url = 'http://localhost:3000/api/tarea/eliminar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== tarea.id);
            mostrarTareas();

        } catch (error) {

            console.log(error);
        }
    }

    function obtenerPoryecto() {
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.id;
    }

    function limiarHTML() {
        const listadoTareas = document.querySelector('#listado-tareas');

        while(listadoTareas.firstChild) {
            listadoTareas.removeChild(listadoTareas.firstChild);
        }
    }

})();