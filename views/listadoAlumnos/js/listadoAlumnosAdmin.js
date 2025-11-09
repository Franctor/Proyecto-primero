window.addEventListener('load', function () {
    const tabla = document.getElementById("tablaAlumno");
    const btnAgregar = document.getElementById("add");
    const btnAgregarVarios = document.getElementById("adds");
    let alumnos = [];

    fetch('../../mockAPI/alumnos.json')
        .then(response => response.json())
        .then(data => {
            alumnos = data;
            tabla.cargarJSON(alumnos);

            tabla.ondblclick = function () {
                if (!this.editada) this.editar();
                else this.noEditar();
            };

            // CABECERAS
            let cabeceras = tabla.querySelectorAll("thead th");

            cabeceras.forEach((th, j) => {
                th.addEventListener("click", function () {
                    let clases = th.className.split(" ");
                    let tiposValidos = ['numero', 'texto', 'fecha'];

                    let claseTipo = clases.find(c => tiposValidos.includes(c)) || 'texto';
                    let ordenActual = clases.find(c => c === '1' || c === '-1') || '1';
                    let nuevoOrden = (parseInt(ordenActual) === 1) ? -1 : 1;

                    th.classList.remove('1', '-1');
                    th.classList.add(String(nuevoOrden));

                    tabla.ordenar(j, claseTipo, nuevoOrden);
                });
            });
        });

    btnAgregar.addEventListener('click', function () {
        const modalAdd = new Modal();
        modalAdd.cargarPlantilla("modalAgregar.html").then(() => {
            const btnCerrar = document.getElementById("cerrarAdd");
            const btnTomarFoto = document.getElementById("tomar-foto");
            const fileFoto = document.getElementById("foto-perfil");
            const btnGuardar = document.getElementById("guardarAdd");
            modalAdd.mostrar();
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
});

