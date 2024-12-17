// Aseguramos que el DOM esté completamente cargado antes de ejecutar el código
document.addEventListener('DOMContentLoaded', function() {
    const sensores = []; // Aquí se almacenarán los datos de los sensores

    // Cargar los sensores desde el servidor y generar la tabla
    async function cargarElementos() {
        try {
            const response = await fetch('ws/getElement.php');
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            console.log(data); // Verifica la estructura de la respuesta

            if (data.success) {
                // Verificar que cada sensor tenga un 'id'
                data.data.forEach(element => {
                    if (!element.id) {
                        console.error('Error: El elemento no tiene un ID', element);
                    }
                });

                sensores.splice(0, sensores.length, ...data.data); // Rellenamos el array 'sensores'
                generarTabla(); // Generamos la tabla
            } else {
                Swal.fire('Error', 'No se pudieron cargar los elementos: ' + data.message, 'error');
            }
        } catch (error) {
            Swal.fire('Error', 'Hubo un problema con la conexión al servidor: ' + error.message, 'error');
            console.error('Error en la solicitud:', error);
        }
    }

    // Generar la tabla HTML con los datos de los elementos
    function generarTabla() {
        const tabla = document.getElementById("tablaSensores").querySelector("tbody");
        tabla.innerHTML = ''; // Limpiar la tabla antes de agregar nuevas filas

        sensores.forEach((element, index) => {
            const fila = generarFila(element, index);
            tabla.appendChild(fila);
        });
    }

    // Crear una fila de la tabla para un elemento
    function generarFila(element, index) {
        const fila = document.createElement('tr');
        fila.innerHTML = `
            <td>${element.nombre}</td>
            <td>${element.descripcion}</td>
            <td>${element.nserie}</td>
            <td>${element.estado}</td>
            <td>${element.prioridad}</td>
            <td>
                <button class="editarBtn">Editar</button>
                <button class="eliminarBtn">Eliminar</button>
            </td>
        `;

        // Obtener los botones y agregar los event listeners
        const editarBtn = fila.querySelector('.editarBtn');
        const eliminarBtn = fila.querySelector('.eliminarBtn');

        editarBtn.addEventListener('click', function () {
            editarElemento(index); // Llamar a editarElemento con el índice
        });

        eliminarBtn.addEventListener('click', function () {
            eliminarFila(index); // Llamar a eliminarFila con el índice
        });

        return fila;
    }

    // Mostrar el formulario de edición con los datos del elemento seleccionado
    function editarElemento(index) {
        console.log("Editando elemento con índice:", index);
        const element = sensores[index];
        document.getElementById("id").value = element.id; // Añadir ID al formulario
        document.getElementById("nombre").value = element.nombre;
        document.getElementById("descripcion").value = element.descripcion;
        document.getElementById("nserie").value = element.nserie;
        document.getElementById("estado").checked = element.estado === "Activo";
        document.querySelector(`input[name="prioridad"][value="${element.prioridad.toLowerCase()}"]`).checked = true;

        const guardarBtn = document.getElementById("guardarCambios");
        guardarBtn.style.display = "inline";
        guardarBtn.onclick = async function () {
            await guardarCambios(index);
        };

        document.getElementById("formularioEdicion").style.display = "block";
    }

    // Guardar los cambios realizados en el elemento
    async function guardarCambios(index) {
        const elementEditado = {
            id: sensores[index].id,  // El ID se mantiene igual
            nombre: document.getElementById("nombre").value || sensores[index].nombre,
            descripcion: document.getElementById("descripcion").value || sensores[index].descripcion,
            nserie: document.getElementById("nserie").value || sensores[index].nserie,
            estado: document.getElementById("estado").checked ? "Activo" : "Inactivo",
            prioridad: document.querySelector("input[name='prioridad']:checked")?.value || sensores[index].prioridad
        };

        if (elementEditado.nombre === sensores[index].nombre && 
            elementEditado.descripcion === sensores[index].descripcion &&
            elementEditado.nserie === sensores[index].nserie && 
            elementEditado.estado === sensores[index].estado && 
            elementEditado.prioridad === sensores[index].prioridad) {
            Swal.fire('Error', 'No se han realizado cambios en los datos del elemento.', 'error');
            return;
        }

        try {
            const response = await fetch('ws/modifyElement.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(elementEditado),
            });

            const result = await response.json();

            if (result.success) {
                sensores[index] = { ...sensores[index], ...elementEditado };
                generarTabla();
                limpiarFormulario();
                Swal.fire('Éxito', result.message || 'El elemento se modificó correctamente.', 'success');
            } else {
                Swal.fire('Error', result.message || 'No se pudo modificar el elemento.', 'error');
            }
        } catch (error) {
            Swal.fire('Error', 'Hubo un problema con la conexión al servidor.', 'error');
        }
    }

    // Eliminar un elemento de la tabla y del servidor
    async function eliminarFila(index) {
        console.log("Eliminando fila con índice:", index);
        const elementId = sensores[index]?.id; // Acceder correctamente al id del sensor
        console.log('ID del elemento a eliminar:', elementId); // Verifica que el id esté disponible
    
        if (!elementId) {
            Swal.fire('Error', 'ID del elemento no disponible.', 'error');
            return;
        }
    
        try {
            const response = await fetch('ws/deleteElement.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: elementId })  // Pasar el id como JSON
            });
    
            const resultJson = await response.json();
    
            if (resultJson.success) {
                sensores.splice(index, 1);  // Eliminar el elemento del array
                generarTabla();  // Regenerar la tabla
                Swal.fire('Eliminado', 'El elemento ha sido eliminado.', 'success');
            } else {
                Swal.fire('Error', resultJson.message || 'No se pudo eliminar el elemento.', 'error');
            }
        } catch (error) {
            Swal.fire('Error', 'Hubo un problema con la conexión al servidor.', 'error');
            console.error('Error al eliminar el elemento:', error);
        }
    }    

    // Limpiar el formulario de edición
    function limpiarFormulario() {
        document.getElementById("nombre").value = "";
        document.getElementById("descripcion").value = "";
        document.getElementById("nserie").value = "";
        document.getElementById("estado").checked = false;

        const prioridadSeleccionada = document.querySelector("input[name='prioridad']:checked");
        if (prioridadSeleccionada) {
            prioridadSeleccionada.checked = false;
        }

        document.getElementById("guardarCambios").style.display = "none";
        document.getElementById("formularioEdicion").style.display = "none";
    }

    // Filtrar elementos en la tabla
    function filtrarTabla() {
        const buscador = document.getElementById('buscador').value.toLowerCase();
        if (buscador.length < 3) {
            generarTabla();
            return;
        }

        const tabla = document.getElementById('tablaSensores').querySelector('tbody');
        tabla.innerHTML = '';

        const resultados = sensores.filter(element =>
            element.nombre.toLowerCase().includes(buscador) ||
            element.descripcion.toLowerCase().includes(buscador)
        );

        resultados.forEach((element, index) => {
            const fila = generarFila(element, index);
            tabla.appendChild(fila);
        });
    }

    // Event listener para filtrar la tabla
    document.getElementById("buscador").addEventListener("input", filtrarTabla);

    // Cargar elementos al cargar la página
    cargarElementos();
});