//Métodos tablas
//Método para activar la edición de una tabla
HTMLTableElement.prototype.editada = false;
HTMLTableElement.prototype.editar = function () {
    if (!this.editada) {
        this.editada = true;
        let trs = this.querySelectorAll("tr");
        let tam = trs.length;
        for (let i = 0; i < tam; i++) {
            if (trs[i].parentElement.nodeName.toUpperCase() == "TBODY") {
                let celda = document.createElement("td");
                let btnBorrar = document.createElement("span");
                let btnEditar = document.createElement("span");
                btnBorrar.innerHTML = "❌";
                btnEditar.innerHTML = "✏️";
                celda.appendChild(btnBorrar);
                celda.appendChild(btnEditar);
                btnBorrar.onclick = function () {
                    this.parentElement.parentElement.borrar();
                }
                btnEditar.onclick = function () {
                    this.parentElement.parentElement.editar();
                }
                trs[i].appendChild(celda);
            } else if (trs[i].parentElement.nodeName.toUpperCase() == "THEAD") {
                let celda = document.createElement("th");
                celda.innerHTML = "EDICIÓN";
                trs[i].appendChild(celda);
            }
        }
    }
}

HTMLTableElement.prototype.noEditar = function () {
    if (this.editada) {
        this.editada = false;
        let trs = this.querySelectorAll("tr");
        for (let i = 0; i < trs.length; i++) {
            let celdas = trs[i].querySelectorAll("td, th");
            let ultima = celdas[celdas.length - 1];
            if (ultima) ultima.remove();
        }
    }
};

//Prototipo para cargar un CSV en una tabla
//Le pasas un array que contenga dos arrays los cuales 
//el primero es la cabecera y el segundo son los datos.
HTMLTableElement.prototype.cargarJSON = function (datos) {
    if (!Array.isArray(datos) || datos.length === 0) return;

    const thead = this.querySelector("thead") || this.createTHead();
    const tbody = this.querySelector("tbody") || this.createTBody();

    thead.innerHTML = "";
    tbody.innerHTML = "";

    // Crear cabecera
    const filaHead = document.createElement("tr");
    const idHead = document.createElement("th");
    idHead.textContent = "ID";
    idHead.classList.add("numero", "1");
    filaHead.appendChild(idHead);
    const nombreHead = document.createElement("th");
    nombreHead.textContent = "Nombre";
    nombreHead.classList.add("texto", "1");
    filaHead.appendChild(nombreHead);
    const apellidoHead = document.createElement("th");
    apellidoHead.textContent = "Apellido";
    apellidoHead.classList.add("texto", "1");
    filaHead.appendChild(apellidoHead);
    const emailHead = document.createElement("th");
    emailHead.textContent = "Email";
    emailHead.classList.add("texto", "1");
    filaHead.appendChild(emailHead);
    const telefonoHead = document.createElement("th");
    telefonoHead.textContent = "Teléfono";
    telefonoHead.classList.add("numero", "1");
    filaHead.appendChild(telefonoHead);
    thead.appendChild(filaHead);

    // Crear filas de datos
    datos.forEach(item => {
        const fila = document.createElement("tr");
        let celdaId = document.createElement("td");
        celdaId.textContent = item.id || "";
        fila.appendChild(celdaId);
        let celdaNombre = document.createElement("td");
        celdaNombre.textContent = item.nombre || "";
        fila.appendChild(celdaNombre);
        let celdaApellido = document.createElement("td");
        celdaApellido.textContent = item.apellido || "";
        fila.appendChild(celdaApellido);
        let celdaEmail = document.createElement("td");
        celdaEmail.textContent = item.email || "";
        fila.appendChild(celdaEmail);
        let celdaTelefono = document.createElement("td");
        celdaTelefono.textContent = item.telefono || "";
        fila.appendChild(celdaTelefono);
        tbody.appendChild(fila);
    });

    // AÑADIMOS AQUÍ EL EVENTO DE ORDENAR
    const self = this;
    const cabeceras = this.querySelectorAll("thead th");

    cabeceras.forEach((th, j) => {
        th.addEventListener("click", function () {
            let clases = th.className.split(" ");
            let tiposValidos = ['numero', 'texto', 'fecha'];

            let claseTipo = clases.find(c => tiposValidos.includes(c)) || 'texto';
            let ordenActual = clases.find(c => c === '1' || c === '-1') || '1';
            let nuevoOrden = (parseInt(ordenActual) === 1) ? -1 : 1;

            th.classList.remove('1', '-1');
            th.classList.add(String(nuevoOrden));

            self.ordenar(j, claseTipo, nuevoOrden);
        });
    });
};


//Método tabla ordenar que le paso un objeto que contiene
//columna por la cual quiero ordenar y el tipo de ordenación.
//El objeto propiedades tiene que tener propiedades={columna:valor,tipo=ordenacion}
//valor= de 0 a n columnas
//ordenacion= 1 o -1
HTMLTableElement.prototype.ordenar = function (columna, clase = 'texto', tipo = 1) {
    const cuerpo = this.tBodies[0];
    const filas = Array.from(cuerpo.rows);

    filas.sort((a, b) => {
        let valorA = a.cells[columna].innerText.trim();
        let valorB = b.cells[columna].innerText.trim();

        switch (clase.toLowerCase()) {
            case 'numero':
                valorA = parseFloat(valorA.replace(',', '.')) || 0;
                valorB = parseFloat(valorB.replace(',', '.')) || 0;
                return tipo * (valorA - valorB);

            case 'fecha':
                const parseFecha = str => {
                    const partes = str.split(/[-\/]/);
                    if (partes.length === 3) {
                        const [d, m, y] = partes.map(Number);
                        return new Date(y, m - 1, d);
                    }
                    return new Date(str);
                };
                return tipo * (parseFecha(valorA) - parseFecha(valorB));

            case 'texto':
            default:
                return tipo * valorA.localeCompare(valorB, 'es', { sensitivity: 'base' });
        }
    });

    // Reinsertar filas ya ordenadas
    for (const fila of filas) {
        cuerpo.appendChild(fila);
    }
};

//Métodos de fila
//Método para borrar una fila
HTMLTableRowElement.prototype.borrar = function () {
    const modalBorrar = new Modal();
    modalBorrar.cargarPlantilla("assets/modals/modalEliminar.html").then(() => {
        modalBorrar.mostrar();
        let borrar = document.getElementById("delete");
        let cancelar = document.getElementById("cancel");
        let self = this;
        borrar.addEventListener("click", function a() {
            modalBorrar.destruir();
            fetch('/assets/api/api_alumno.php?id=' + self.cells[0].textContent, {
                method: 'DELETE'
            })
                .then(response => {
                    if (response.status === 204) {
                        self.remove();
                    }
                });
            borrar.removeEventListener("click", a);
        });

        cancelar.addEventListener("click", function b() {
            modalBorrar.destruir();
            cancelar.removeEventListener("click", b);
        });
    });

}


HTMLTableRowElement.prototype.editar = function () {
    const modalEditar = new Modal();
    const fila = this;
    modalEditar.cargarPlantillaConDatos("assets/modals/modalEditar.html", "/assets/api/api_alumno.php?id=" + fila.cells[0].textContent, rellenar)
        .then((datos) => {
            const btnCerrar = document.getElementById("cerrarEditar"); const btnGuardar = document.getElementById("guardarEditar"); const selectLocalidad = document.getElementById("localidad");
            const selectProvincia = document.getElementById("provincia");
            const localidadIdAlumno = document.getElementById("localidad_id").value;
            const selectCiclos = document.getElementById("ciclos");
            const selectFamilia = document.getElementById("familia");
            //const divFoto = document.getElementById("foto");
            //const fotoBase64 = divFoto.dataset.foto;
            /**
             * Logica para rellenar los ciclos seleccionados
             */
            // --- Rellenar ciclos seleccionados ---
            const selectedCiclosSelect = document.getElementById("ciclosSeleccionados");
            selectedCiclosSelect.innerHTML = "";

            let ciclosSeleccionados = [];
            if (datos.ciclos && Array.isArray(datos.ciclos)) {
                datos.ciclos.forEach(ciclo => {
                    const option = document.createElement("option");
                    option.value = ciclo.id;
                    option.textContent = ciclo.nombre;
                    selectedCiclosSelect.appendChild(option);
                    ciclosSeleccionados.push({
                        id: ciclo.id,
                        nombre: ciclo.nombre
                    });
                });
            }
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
            selectCiclos.addEventListener('dblclick', () => {
                const selectedIndex = selectCiclos.selectedIndex;
                const selectedOption = selectCiclos.options[selectedIndex];

                // Si hay un ciclo seleccionado y no está ya en la lista, añadirlo
                if (selectedOption && !ciclosSeleccionados.some(c => c.id == selectedOption.value)) {
                    ciclosSeleccionados.push({
                        id: selectedOption.value,
                        nombre: selectedOption.textContent
                    });
                    actualizarSelectCiclos();
                }
                selectCiclos.value = '';
            });

            // Eliminar ciclo con doble clic en select múltiple
            selectedCiclosSelect.addEventListener('dblclick', (e) => {
                const value = e.target.value;
                ciclosSeleccionados = ciclosSeleccionados.filter(c => c.id != value);
                actualizarSelectCiclos();
            });
            //if (fotoBase64 && fotoBase64.trim() !== "") {
            // Aplica la imagen desde JS, sin mostrarla en el HTML
            //    divFoto.style.backgroundImage = `url(data:image/png;base64,${fotoBase64})`;
            //} else {
            //    divFoto.style.backgroundImage = 'url(../../storage/foto_perfil/default.png)';
            //}
            //divFoto.removeAttribute("data-foto");

            inicializarValidaciones("form-editar-alumno");

            modalEditar.mostrar();
            cargarSelectProvinciaYLocalidad(selectProvincia, selectLocalidad, localidadIdAlumno);
            cargarSelectFamiliaYCiclo(selectFamilia, selectCiclos);


            // --- Botón Cerrar ---
            btnCerrar.addEventListener("click", function a() {
                modalEditar.destruir();
                btnCerrar.removeEventListener("click", a);
            });


            btnGuardar.addEventListener("click", function () {
                const formulario = document.getElementById("form-editar-alumno");
                const formData = new FormData(formulario);
                formData.append('_method', 'PUT'); // indicamos que es un update
                ciclosSeleccionados.forEach(ciclo => {
                    formData.append('ciclosSeleccionados[]', ciclo.id);
                });
                if (validarFormularioEditar(formulario)) {
                    fetch('assets/api/api_alumno.php?id=' + fila.cells[0].textContent, {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.respuesta) {
                                modalEditar.destruir();
                                const celdas = fila.cells;
                                celdas[0].textContent = data.id || celdas[0].textContent;
                                celdas[1].textContent = data.nombre || celdas[1].textContent;
                                celdas[2].textContent = data.apellidos || celdas[2].textContent;
                                celdas[3].textContent = data.email || celdas[3].textContent;
                                celdas[4].textContent = data.telefono || celdas[4].textContent;
                            } else {
                                console.warn("Error al editar el alumno.");
                            }
                        });
                } else {
                    console.warn("Formulario inválido, revisa los campos.");
                }
            });
        });
};

function rellenar(plantilla, datos) {
    let html = plantilla;
    for (let clave in datos) {
        const regex = new RegExp(`{{${clave}}}`, "g");
        html = html.replace(regex, datos[clave] ?? "");
    }
    return html;
}

