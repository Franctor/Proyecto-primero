const tabla = document.getElementById("tablaAlumno");
const btnAgregar = document.getElementById("add");
const btnAgregarVarios = document.getElementById("adds");
let alumnos = [];

fetch('assets/api/api_alumno.php', {
    method: 'GET'
})
    .then(response => response.json())
    .then(data => {
        if (Array.isArray(data) && data.length > 0) {
            alumnos = data;
            tabla.cargarJSON(alumnos);
        } else {
            const thead = tabla.querySelector("thead") || tabla.createTHead();
            const tbody = tabla.querySelector("tbody") || tabla.createTBody();

            thead.innerHTML = "";
            tbody.innerHTML = "";

            // Crear cabecera
            const filaHead = document.createElement("tr");
            const columnas = ["ID", "Nombre", "Apellido", "Email", "Teléfono"];
            const tipos = ["numero", "texto", "texto", "texto", "numero"];

            columnas.forEach((col, i) => {
                const th = document.createElement("th");
                th.textContent = col;
                th.classList.add(tipos[i], "1");
                filaHead.appendChild(th);
            });

            thead.appendChild(filaHead);

            const cabeceras = thead.querySelectorAll("th");
            cabeceras.forEach((th, j) => {
                th.addEventListener("click", function () {
                    const clases = th.className.split(" ");
                    const tiposValidos = ['numero', 'texto', 'fecha'];

                    const claseTipo = clases.find(c => tiposValidos.includes(c)) || 'texto';
                    const ordenActual = clases.find(c => c === '1' || c === '-1') || '1';
                    const nuevoOrden = (parseInt(ordenActual) === 1) ? -1 : 1;

                    th.classList.remove('1', '-1');
                    th.classList.add(String(nuevoOrden));

                    tabla.ordenar(j, claseTipo, nuevoOrden);
                });
            });
        }

    });

tabla.ondblclick = function () {
    if (!this.editada) this.editar();
    else this.noEditar();
};

btnAgregar.addEventListener('click', function () {
    const modalAdd = new Modal();
    modalAdd.cargarPlantilla("assets/modals/modalAgregar.html").then(() => {
        const btnCerrar = document.getElementById("cerrarAdd");
        const btnGuardar = document.getElementById("guardarAdd");
        const selectProvincia = document.getElementById("provincia");
        const selectLocalidad = document.getElementById("localidad");
        const selectFamilia = document.getElementById("familia");
        const selectCiclo = document.getElementById("ciclos");
        const selectedCiclosSelect = document.getElementById("ciclosSeleccionados");


        modalAdd.mostrar();
        cargarSelectFamiliaYCiclo(selectFamilia, selectCiclo);
        let ciclosSeleccionados = [];

        function actualizarSelectCiclos() {
            selectedCiclosSelect.innerHTML = '';

            ciclosSeleccionados.forEach(ciclo => {
                const option = document.createElement('option');
                option.value = ciclo.id;
                option.textContent = ciclo.nombre;
                selectedCiclosSelect.appendChild(option);
            });
        }

        // Añadir ciclo con doble clic en selectCiclo
        selectCiclo.addEventListener('dblclick', () => {
            const selectedIndex = selectCiclo.selectedIndex;
            const selectedOption = selectCiclo.options[selectedIndex];

            // Si hay un ciclo seleccionado y no está ya en la lista, añadirlo
            if (selectedOption && !ciclosSeleccionados.some(c => c.id == selectedOption.value)) {
                ciclosSeleccionados.push({
                    id: selectedOption.value,
                    nombre: selectedOption.textContent
                });
                actualizarSelectCiclos();
            }
            selectCiclo.value = '';
        });

        // Eliminar ciclo con doble clic en select múltiple
        selectedCiclosSelect.addEventListener('dblclick', (e) => {
            if (e.target.tagName === 'OPTION') {
                const value = e.target.value;
                ciclosSeleccionados = ciclosSeleccionados.filter(c => c.id != value);
                actualizarSelectCiclos();
            }
        });
        cargarSelectProvinciaYLocalidad(selectProvincia, selectLocalidad);
        inicializarValidaciones("form-add-alumno");

        // --- Botón Cerrar ---
        btnCerrar.addEventListener("click", function a() {
            modalAdd.ocultar();
            modalAdd.destruir();
            btnCerrar.removeEventListener("click", a);
        });

        // --- Botón Guardar ---
        btnGuardar.addEventListener("click", function c() {
            const formulario = document.getElementById("form-add-alumno");
            if (validarFormulario(formulario)) {
                const datos = new FormData(formulario);
                datos.append('admin', '1');
                // Agregar ciclos seleccionados al FormData
                ciclosSeleccionados.forEach(ciclo => {
                    datos.append('ciclosSeleccionados[]', ciclo.id);
                });
                fetch('../assets/api/api_alumno.php', {
                    method: 'POST',
                    body: datos
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.respuesta) {
                            modalAdd.destruir();
                            // Añadir nueva fila a la tabla
                            const fila = document.createElement("tr");
                            let id = document.createElement("td");
                            id.textContent = data.id;
                            fila.appendChild(id);

                            let nombre = document.createElement("td");
                            nombre.textContent = data.nombre;
                            fila.appendChild(nombre);
                            let apellido = document.createElement("td");
                            apellido.textContent = data.apellido;
                            fila.appendChild(apellido);
                            let email = document.createElement("td");
                            email.textContent = data.email;
                            fila.appendChild(email);

                            let telefono = document.createElement("td");
                            telefono.textContent = data.telefono;
                            fila.appendChild(telefono);
                            tabla.querySelector("tbody").appendChild(fila);
                        } else {
                            console.warn("Error al crear el alumno.");
                        }
                    });
            } else {
                console.warn("Formulario inválido, revisa los campos.");
            }
        });
    });
});

btnAgregarVarios.addEventListener('click', function () {
    const modalAddVarios = new Modal();
    modalAddVarios.cargarPlantilla("modalAgregarVarios.html").then(() => {
        modalAddVarios.mostrar();
    });
});


