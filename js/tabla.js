document.addEventListener('DOMContentLoaded', function() {
    const sensores = [];

    async function cargarElementos() {
        const response = await fetch('ws/getElement.php');
        const data = await response.json();
        
        if (data.success) {
            data.data.forEach(element => {
                if (!element.id) {
                    console.error('Error: El elemento no tiene un ID', element);
                }
            });
    
            sensores.splice(0, sensores.length, ...data.data);
            generarTabla();
        } else {
            Swal.fire('Error', 'No se pudieron cargar los elementos', 'error');
        }
    }    

    function generarTabla() {
        const tabla = document.getElementById("tablaSensores").querySelector("tbody");
        tabla.innerHTML = '';

        sensores.forEach((element, index) => {
            const fila = generarFila(element, index);
            tabla.appendChild(fila);
        });
    }

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

        const editarBtn = fila.querySelector('.editarBtn');
        const eliminarBtn = fila.querySelector('.eliminarBtn');

        editarBtn.addEventListener('click', function () {
            editarElemento(index);
        });

        eliminarBtn.addEventListener('click', function () {
            eliminarFila(index);
        });

        return fila;
    }

    function editarElemento(index) {
        console.log("Editando elemento con índice:", index);
        const element = sensores[index];
        document.getElementById("id").value = element.id;
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

    async function guardarCambios(index) {
        const elementEditado = {
            id: sensores[index].id,
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

    async function eliminarFila(index) {
        const elementId = sensores[index]?.id;
        if (!elementId) {
            Swal.fire('Error', 'ID del elemento no disponible.', 'error');
            return;
        }
    
        try {
            const response = await fetch('ws/deleteElement.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: elementId }),
            });
    
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
    
            const result = await response.json();
    
            if (result.success) {
                sensores.splice(index, 1);
                generarTabla();
                Swal.fire('Eliminado', 'El elemento ha sido eliminado.', 'success');
            } else {
                Swal.fire('Error', result.message || 'No se pudo eliminar el elemento.', 'error');
            }
        } catch (error) {
            if (error.message.includes('NetworkError')) {
                Swal.fire('Error', 'Hubo un problema con la conexión al servidor.', 'error');
            } else {
                Swal.fire('Error', `Error al eliminar el elemento: ${error.message}`, 'error');
            }
            console.error('Error al eliminar el elemento:', error);
        }
    }        

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

    document.getElementById("buscador").addEventListener("input", filtrarTabla);

    cargarElementos();
});