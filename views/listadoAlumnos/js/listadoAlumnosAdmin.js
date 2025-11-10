window.addEventListener('load', function () {
    const tabla = document.getElementById("tablaAlumno");
    const btnAgregar = document.getElementById("add");
    const btnAgregarVarios = document.getElementById("adds");
    let alumnos = [];

    fetch('../../API/ApiAlumno.php', {
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
        modalAdd.cargarPlantilla("modalAgregar.html").then(() => {
            const btnCerrar = document.getElementById("cerrarAdd");
            const btnTomarFoto = document.getElementById("tomar-foto");
            const fileFoto = document.getElementById("foto-perfil");
            const btnGuardar = document.getElementById("guardarAdd");
            const selectProvincia = document.getElementById("provincia");
            const selectLocalidad = document.getElementById("localidad");
            modalAdd.mostrar();
            cargarSelectProvinciaYLocalidad(selectProvincia, selectLocalidad);
            inicializarValidaciones("form-add-alumno");

            // --- Botón Cerrar ---
            btnCerrar.addEventListener("click", function a() {
                modalAdd.ocultar();
                modalAdd.destruir();
                btnCerrar.removeEventListener("click", a);
            });

            // --- Botón Tomar foto ---
            btnTomarFoto.addEventListener("click", function b() {
                gestionFoto(modalAdd); // función definida en foto.js
            });

            // --- Previsualizar imagen seleccionada ---
            fileFoto.addEventListener("change", function d() {
                const fotoDiv = document.getElementById("foto");
                const file = fileFoto.files[0];
                if (file) {
                    const url = URL.createObjectURL(file);
                    fotoDiv.style.backgroundImage = `url(${url})`;
                }
            });

            // --- Botón Guardar ---
            btnGuardar.addEventListener("click", function c() {
                const formulario = document.getElementById("form-add-alumno");
                if (validarFormulario(formulario)) {
                    fetch('../../API/ApiAlumno.php', {
                        method: 'POST',
                        body: new FormData(formulario)
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

    function cargarSelectProvinciaYLocalidad(selectProvincia, selectLocalidad, localidadId = null) {
        fetch('../../API/ApiProvincia.php', { method: 'GET' })
            .then(res => res.json())
            .then(data => {
                selectProvincia.innerHTML = '';
                selectLocalidad.innerHTML = '';

                // Opción por defecto
                const defaultProv = document.createElement('option');
                defaultProv.value = '';
                defaultProv.textContent = 'Seleccione una provincia';
                defaultProv.disabled = true;
                defaultProv.selected = localidadId === null;
                selectProvincia.appendChild(defaultProv);

                const defaultLoc = document.createElement('option');
                defaultLoc.value = '';
                defaultLoc.textContent = 'Seleccione una localidad';
                defaultLoc.disabled = true;
                defaultLoc.selected = localidadId === null;
                selectLocalidad.appendChild(defaultLoc);

                let provinciaSeleccionada = null;
                let localidadSeleccionada = null;

                // Buscar la provincia y localidad que corresponden al localidadId
                if (localidadId !== null) {
                    for (let prov of data) {
                        const loc = prov.localidades.find(l => l.id == localidadId);
                        if (loc) {
                            provinciaSeleccionada = prov;
                            localidadSeleccionada = loc;
                            break;
                        }
                    }
                }

                // Cargar todas las provincias
                data.forEach(prov => {
                    const option = document.createElement('option');
                    option.value = prov.id;
                    option.textContent = prov.nombre_prov;
                    if (provinciaSeleccionada && prov.id == provinciaSeleccionada.id) option.selected = true;
                    selectProvincia.appendChild(option);
                });

                // Cargar localidades si hay provincia seleccionada
                if (provinciaSeleccionada) {
                    cargarSelectLocalidad(selectLocalidad, provinciaSeleccionada, localidadSeleccionada.nombre_loc);
                }

                // Evento al cambiar la provincia
                selectProvincia.addEventListener('change', () => {
                    const prov = data.find(p => p.id == selectProvincia.value);
                    if (prov) {
                        cargarSelectLocalidad(selectLocalidad, prov);
                    }
                });
            })
            .catch(err => console.error("Error al cargar provincias:", err));
    }

    function cargarSelectLocalidad(selectLocalidad, provincia, nombreLocalidadSeleccionada = '') {
        selectLocalidad.innerHTML = '';

        // Opción por defecto
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Seleccione una localidad';
        defaultOption.disabled = true;
        defaultOption.selected = nombreLocalidadSeleccionada === '';
        selectLocalidad.appendChild(defaultOption);

        provincia.localidades.forEach(loc => {
            const option = document.createElement('option');
            option.value = loc.id;
            option.textContent = loc.nombre_loc;
            if (loc.nombre_loc === nombreLocalidadSeleccionada) option.selected = true;
            selectLocalidad.appendChild(option);
        });
    }
});

