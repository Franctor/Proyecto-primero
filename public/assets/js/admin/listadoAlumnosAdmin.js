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
                        }
                    });
            }
        });
    });
});


btnAgregarVarios.addEventListener('click', function () {

    const modalAddVarios = new Modal();

    modalAddVarios.cargarPlantilla("assets/modals/modalAgregarVarios.html").then(() => {

        modalAddVarios.mostrar();

        const btnCerrarVarios = modalAddVarios.modal.querySelector("#cerrarAdds");
        const btnCargarVarios = modalAddVarios.modal.querySelector("#cargarAdds");
        const file = modalAddVarios.modal.querySelector("#archivo");
        const selectFamilia = modalAddVarios.modal.querySelector("#familia");
        const selectCiclo = modalAddVarios.modal.querySelector("#ciclos");

        cargarSelectFamiliaYCiclo(selectFamilia, selectCiclo);

        // --- Botón Cerrar ---
        btnCerrarVarios.addEventListener("click", function () {
            modalAddVarios.destruir();
        });

        // --- Botón Cargar Varios ---
        btnCargarVarios.addEventListener("click", async function () {
            //Validar que se ha seleccionado un archivo
            //Validar que el formato de csv es correcto
            if (file.files.length === 0) {
                mostrarError(file, "Debe seleccionar un archivo.");
            } else if (!file.files[0].name.endsWith('.csv')) {
                limpiarError(file);
                mostrarError(file, "El archivo debe tener extensión .csv.");
            } else {
                limpiarError(file);
                const formatoCorrecto = await comprobarFormatoCSV(file.files[0]);
                if (!formatoCorrecto) {
                    mostrarError(file, "El formato del CSV no es correcto.");
                } else if (selectCiclo.value === "") {
                    mostrarError(selectCiclo, "Debe seleccionar un ciclo.");
                } else {
                    limpiarError(file);
                    limpiarError(selectCiclo);

                    modalAddVarios.ocultar();
                    const modalCSV = new Modal();

                    modalCSV.cargarPlantilla("assets/modals/modalCargarCSV.html").then(() => {
                        // --- Cargar datos del CSV en la tabla del modal ---
                        const tablaCSV = modalCSV.modal.querySelector("#tablaCSV");
                        const tbodyCSV = tablaCSV.querySelector("tbody");
                        cargarCSV(file.files[0], tbodyCSV);

                        // --- Botón Cerrar (X) ---
                        modalCSV.mostrar();
                        const btnCerrarCSV = modalCSV.modal.querySelector("#cerrarCSV");
                        btnCerrarCSV.addEventListener("click", function () {
                            const modalConfirmar = new Modal();
                            modalConfirmar.cargarPlantilla("assets/modals/modalConfirmar.html").then(() => {
                                modalCSV.ocultar();
                                modalConfirmar.mostrar();

                                const btnCancelar = modalConfirmar.modal.querySelector("#cancelar");
                                const btnConfirmar = modalConfirmar.modal.querySelector("#confirmar");

                                btnCancelar.addEventListener("click", function () {
                                    modalConfirmar.destruir();
                                    modalCSV.mostrar();
                                });

                                btnConfirmar.addEventListener("click", function () {
                                    modalConfirmar.destruir();
                                    modalCSV.destruir();
                                });
                            });
                        });
                        // --- Botón insertar válidos ---
                        const btnInsertarCSV = modalCSV.modal.querySelector("#btnInsertarValidos");
                        btnInsertarCSV.addEventListener("click", function () {
                            let repetidos = [];
                            const filas = tbodyCSV.querySelectorAll("tr.valido");

                            let total = filas.length;
                            let terminadas = 0;

                            filas.forEach(fila => {

                                const columnas = fila.querySelectorAll("td");
                                const nombre = columnas[1].textContent.trim();
                                const apellido = columnas[2].textContent.trim();
                                const email = columnas[3].textContent.trim();
                                const telefono = columnas[4].textContent.trim();

                                const datos = new FormData();
                                datos.append('nombre', nombre);
                                datos.append('apellido', apellido);
                                datos.append('email', email);
                                datos.append('telefono', telefono);
                                datos.append('admin', '1');
                                datos.append('ciclosSeleccionados[]', selectCiclo.value);
                                datos.append('localidad', '777');

                                fetch('../assets/api/api_alumno.php', {
                                    method: 'POST',
                                    body: datos
                                })
                                    .then(response => response.json())
                                    .then(data => {

                                        if (data.respuesta) {
                                            // Añadir fila a la tabla principal
                                            const filaNueva = document.createElement("tr");
                                            filaNueva.innerHTML = `
                                                <td>${data.id}</td>
                                                <td>${data.nombre}</td>
                                                <td>${data.apellido}</td>
                                                <td>${data.email}</td>
                                                <td>${data.telefono}</td>
                                            `;
                                            tabla.querySelector("tbody").appendChild(filaNueva);

                                            /** Si ha sido insertado en la base de datos, se borra la fila del CSV */
                                            fila.remove();

                                        } else {
                                            repetidos.push(email);
                                            fila.classList.remove("valido");
                                            fila.classList.add("invalido");
                                            const checkbox = fila.querySelector("td input[type='checkbox']");
                                            checkbox.checked = false;
                                            /** Si NO se insertó, se deja en la tabla CSV para que el usuario revise */
                                        }

                                        // Marcar como terminada
                                        terminadas++;

                                        // Cuando todas hayan terminado, avisamos de los repetidos
                                        if (terminadas === total && repetidos.length > 0) {
                                            alert("Los siguientes correos ya existen y no se han insertado:\n" + repetidos.join("\n"));
                                        }
                                    });

                            });

                        });

                        // --- Botón descartar inválidos ---
                        const btnDescartarCSV = modalCSV.modal.querySelector("#btnDescartarInvalidos");
                        btnDescartarCSV.addEventListener("click", function () {
                            const filasInvalidas = tbodyCSV.querySelectorAll("tr.invalido");
                            filasInvalidas.forEach(fila => fila.remove());
                        });

                        // --- Botón seleccionar solo validos ---
                        const btnSeleccionarCSV = modalCSV.modal.querySelector("#btnSeleccionarValidos");
                        btnSeleccionarCSV.addEventListener("click", function () {
                            const filas = tbodyCSV.querySelectorAll("tr");
                            filas.forEach(fila => {
                                const checkbox = fila.querySelector("td input[type='checkbox']");
                                if (fila.classList.contains("valido")) {
                                    checkbox.checked = true;
                                } else {
                                    checkbox.checked = false;
                                }
                            });
                        });
                    });
                }
            }
        });

    });

});


async function comprobarFormatoCSV(archivo) {

    let valido = true;

    const texto = await archivo.text();
    const lineas = texto.split(/\r?\n/).filter(l => l.trim() !== ""); // Saltos de linea universales y eliminar lineas vacías

    // 1. Debe haber líneas
    if (valido && lineas.length === 0) {
        valido = false;
    }

    // 2. Cabecera exacta
    const cabeceraCorrecta = ["nombre", "apellido/s", "correo", "teléfono"];

    if (valido) {
        const cabecera = lineas[0]
            .split(";")
            .map(t => t.trim().toLowerCase().replace("apellidos", "apellido/s"));

        if (cabecera.join() !== cabeceraCorrecta.join()) {
            valido = false;
        }
    }

    // 3. Cada fila debe tener exactamente 4 columnas
    let i = 1;
    while (valido && i < lineas.length) {
        const columnas = lineas[i].split(";");
        if (columnas.length !== 4) {
            valido = false;
        }
        i++;
    }

    return valido;
}

async function cargarCSV(archivo, tbody) {
    // Limpiar la tabla
    tbody.innerHTML = "";

    // Leer contenido del CSV
    const texto = await archivo.text();
    const lineas = texto.split(/\r?\n/).filter(l => l.trim() !== "");

    // --- 1. PRIMER RECORRIDO → contar correos y teléfonos ---
    const contadorCorreo = {};
    const contadorTelefono = {};

    for (let i = 1; i < lineas.length; i++) {
        const columnas = lineas[i].split(";").map(c => c.trim());

        const correo = columnas[2] || "";
        const telefono = columnas[3] || "";

        if (correo !== "") {
            if (!contadorCorreo[correo]) contadorCorreo[correo] = 0;
            contadorCorreo[correo]++;
        }

        if (telefono !== "") {
            if (!contadorTelefono[telefono]) contadorTelefono[telefono] = 0;
            contadorTelefono[telefono]++;
        }
    }

    // --- 2. SEGUNDO RECORRIDO → pintar tabla y validar ---
    for (let i = 1; i < lineas.length; i++) {
        const columnas = lineas[i].split(";").map(c => c.trim());

        const correo = columnas[2] || "";
        const telefono = columnas[3] || "";

        // Crear fila
        const tr = document.createElement("tr");

        // Validación básica: 4 columnas
        let valido = (columnas.length === 4);
        let mensajeErrorNombre = validarNombre(columnas[0]);
        let mensajeErrorApellido = validarApellido(columnas[1]);
        let mensajeErrorCorreo = validarCorreoElectronico(columnas[2]);
        let mensajeErrorTelefono = validarTelefono(columnas[3]);
        if (mensajeErrorNombre || mensajeErrorApellido || mensajeErrorCorreo || mensajeErrorTelefono) {
            valido = false;
        }

        // Validación de duplicados
        const correoDuplicado = contadorCorreo[correo] > 1;
        const telefonoDuplicado = contadorTelefono[telefono] > 1;

        if (correoDuplicado || telefonoDuplicado) {
            valido = false;
        }

        // Marcar visualmente
        tr.classList.add(valido ? "valido" : "invalido");

        // Checkbox
        const tdCheck = document.createElement("td");
        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.checked = valido;
        checkbox.id = `selectFila${i}`;
        if (!valido) checkbox.classList.add("error");
        tdCheck.appendChild(checkbox);
        tr.appendChild(tdCheck);

        // Añadir columnas
        for (let j = 0; j < 4; j++) {
            const td = document.createElement("td");
            td.textContent = columnas[j] || "";
            tr.appendChild(td);
        }

        //Añadir errores si existen
        for (let j = 0; j < 4; j++) {
            const td = tr.children[j + 1];
            let mensajeError = "";
            switch (j) {
                case 0:
                    mensajeError = mensajeErrorNombre;
                    break;
                case 1:
                    mensajeError = mensajeErrorApellido;
                    break;
                case 2:
                    mensajeError = mensajeErrorCorreo;
                    if (correoDuplicado) {
                        mensajeError += (mensajeError ? " " : "") + "Correo duplicado.";
                    }
                    break;
                case 3:
                    mensajeError = mensajeErrorTelefono;
                    if (telefonoDuplicado) {
                        mensajeError += (mensajeError ? " " : "") + "Teléfono duplicado.";
                    }
                    break;
            }
            if (mensajeError !== "") {
                const div = document.createElement("div");
                td.appendChild(div);
                const spanError = document.createElement("span");
                spanError.classList.add("error-mensaje");
                spanError.textContent = mensajeError;
                td.appendChild(document.createElement("br"));
                div.appendChild(spanError);
            }
        }

        // Acciones (Editar / Eliminar)
        const br = document.createElement("br");
        const tdAcciones = document.createElement("td");

        const btnEliminar = document.createElement("button");
        btnEliminar.textContent = "Eliminar";
        btnEliminar.classList.add("button", "eliminarEmpresa");
        btnEliminar.addEventListener("click", function () {
            tr.remove();
        });

        const btnEditar = document.createElement("button");
        btnEditar.textContent = "Editar";
        btnEditar.classList.add("button");

        // EDITAR EN LINEA
        btnEditar.addEventListener("click", function () {
            // Eliminar mensajes de error previos para que no se metan en el input
            tr.querySelectorAll(".error-mensaje").forEach(e => e.remove());
            tr.querySelectorAll("br").forEach(e => e.remove());
            tr.querySelectorAll("div").forEach(e => e.remove());
            let valoresOriginales = [];
            for (let j = 0; j < 4; j++) {
                valoresOriginales.push(tr.children[j + 1].textContent);
            }

            for (let j = 0; j < 4; j++) {
                const td = tr.children[j + 1];
                const input = document.createElement("input");
                input.type = "text";
                input.id = `inputEditar${j}`;
                input.value = valoresOriginales[j];
                td.textContent = "";
                td.appendChild(input);
            }

            btnEditar.style.display = "none";
            btnEliminar.style.display = "none";

            const btnGuardar = document.createElement("button");
            btnGuardar.textContent = "Guardar";
            btnGuardar.classList.add("button", "verificarEmpresa");

            

            const btnCancelar = document.createElement("button");
            btnCancelar.textContent = "Cancelar";
            btnCancelar.classList.add("button");

            tdAcciones.appendChild(btnGuardar);
            tdAcciones.appendChild(br);
            tdAcciones.appendChild(btnCancelar);

            btnGuardar.addEventListener("click", function () {

                const esValida = validarFilaCSV(tr);
                if (esValida) {
                    for (let j = 0; j < 4; j++) {
                        const input = tr.children[j + 1].querySelector("input");
                        tr.children[j + 1].textContent = input.value.trim();
                    }

                    // Restaurar botones
                    btnGuardar.remove();
                    btnCancelar.remove();
                    btnEditar.style.display = "";
                    btnEliminar.style.display = "";
                    tr.classList.remove("invalido");
                    tr.classList.add("valido");
                    tr.children[0].querySelector("input").checked = true;
                }
            });

            btnCancelar.addEventListener("click", function () {
                for (let j = 0; j < 4; j++) {
                    tr.children[j + 1].textContent = valoresOriginales[j];
                }
                btnGuardar.remove();
                btnCancelar.remove();
                btnEditar.style.display = "";
                btnEliminar.style.display = "";
            });
        });

        tdAcciones.appendChild(btnEditar);
        tdAcciones.appendChild(br);
        tdAcciones.appendChild(btnEliminar);
        tr.appendChild(tdAcciones);

        tbody.appendChild(tr);
    }
}


