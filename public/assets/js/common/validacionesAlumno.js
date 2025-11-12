/* Funciones de ayuda para mostrar y limpiar errores */
function mostrarError(input, mensaje) {
    const errorPrevio = input.parentNode.querySelector(".error");
    if (errorPrevio) errorPrevio.remove();

    if (mensaje) {
        const div = document.createElement("div");
        const error = document.createElement("span");
        div.classList.add("error");
        error.textContent = mensaje;
        div.appendChild(error);
        input.parentNode.appendChild(div);
    }
}

function limpiarError(input) {
    let error = input.parentNode.querySelector(".error");
    if (error) error.remove();
}

/* Funciones de validación específicas */
function validarNombre(nombre) {
    nombre = nombre.trim();
    let mensajeError = "";

    if (nombre === "") {
        mensajeError = "El nombre no puede estar vacío.";
    } else if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s'.-]+$/.test(nombre)) {
        mensajeError = "El nombre solo puede contener letras y espacios.";
    } else if (nombre.length < 2 || nombre.length > 45) {
        mensajeError = "El nombre debe tener entre 2 y 45 caracteres.";
    }
    return mensajeError;
}

function validarDireccion(direccion) {
    direccion = direccion.trim();
    let mensajeError = "";
    if (direccion === "") {
        mensajeError = "La dirección no puede estar vacía.";
    } else if (direccion.length < 5 || direccion.length > 100) {
        mensajeError = "La dirección debe tener entre 5 y 100 caracteres.";
    } else if (!/^[A-Za-zÁÉÍÓÚáéíóúñÑ0-9\s.,\-\/ºª]+$/.test(direccion)) {
        mensajeError = "La dirección contiene caracteres no válidos.";
    }
    return mensajeError;
}

function validarTelefono(telefono) {
    telefono = telefono.trim();
    let mensajeError = "";
    if (telefono === "") {
        mensajeError = "El teléfono no puede estar vacío.";
    } else if (!/^(?:\+34)?[67]\d{8}$/.test(telefono)) {
        mensajeError = "El teléfono debe tener un formato válido español.";
    }
    return mensajeError;
}

function validarCorreoElectronico(email) {
    email = email.trim();
    let mensajeError = "";
    if (email === "") {
        mensajeError = "El correo electrónico no puede estar vacío.";
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        mensajeError = "El correo electrónico debe tener un formato válido.";
    } else if (email.length < 5 || email.length > 100) {
        mensajeError = "El correo electrónico debe tener entre 5 y 100 caracteres.";
    }
    return mensajeError;
}

function validarSelect(valor, campo) {
    valor = valor.trim();
    let mensajeError = "";

    if (valor === "" || valor === "0" || valor === "Selecciona" || valor === "Seleccione") {
        mensajeError = `Debes seleccionar una ${campo}.`;
    }

    return mensajeError;
}

function validarImagen(file) {
    let mensajeError = "";
    if (file != null) {
        const validTypes = ["image/png", "image/jpeg", "image/webp"];
        if (!validTypes.includes(file.type)) {
            mensajeError = "El archivo de la foto de perfil debe ser una imagen (PNG, JPEG, WEBP).";
        } else if (file.size > 1 * 1024 * 1024) { // 1MB
            mensajeError = "El archivo de la foto de perfil no debe superar los 1MB.";
        }
    } else {
        mensajeError = "Debes seleccionar una foto de perfil.";
    }
    return mensajeError;
}

function validarCV(file) {
    let mensajeError = "";
    if (file != null) {
        if (file.type !== "application/pdf") {
            mensajeError = "El archivo del CV debe ser un documento PDF.";
        } else if (file.size > 5 * 1024 * 1024) { // 5MB
            mensajeError = "El archivo del CV no debe superar los 5MB.";
        }
    } else {
        mensajeError = "Debes seleccionar un CV.";
    }
    return mensajeError;
}

function validarCiclosSeleccionados(ciclosSeleccionados) {
    let mensajeError = "";
    if (ciclosSeleccionados.length === 0) {
        mensajeError = "Debes seleccionar al menos un ciclo formativo.";
    }
    return mensajeError;
}

function validarPassword(password) {
    const errorCaracteres = document.getElementById("caracteres");
    const errorMayus = document.getElementById("mayus");
    const errorMinus = document.getElementById("minus");
    const errorNum = document.getElementById("num");
    const errorEspecial = document.getElementById("especial");

    const requisitos = [errorCaracteres, errorMayus, errorMinus, errorNum, errorEspecial].filter(Boolean);
    requisitos.forEach(el => el.style.color = "#666");

    if (typeof password !== "string" || password.length === 0) {
        return "La contraseña no puede estar vacía.";
    }

    let esValida = true;

    if (password.length < 8) { if (errorCaracteres) errorCaracteres.style.color = "red"; esValida = false; } else { if (errorCaracteres) errorCaracteres.style.color = "green"; }
    if (!/[A-Z]/.test(password)) { if (errorMayus) errorMayus.style.color = "red"; esValida = false; } else { if (errorMayus) errorMayus.style.color = "green"; }
    if (!/[a-z]/.test(password)) { if (errorMinus) errorMinus.style.color = "red"; esValida = false; } else { if (errorMinus) errorMinus.style.color = "green"; }
    if (!/[0-9]/.test(password)) { if (errorNum) errorNum.style.color = "red"; esValida = false; } else { if (errorNum) errorNum.style.color = "green"; }
    if (!/[!@#$%^&*()\-_+=]/.test(password)) { if (errorEspecial) errorEspecial.style.color = "red"; esValida = false; } else { if (errorEspecial) errorEspecial.style.color = "green"; }

    if (/\s/.test(password)) return "No se permiten espacios en la contraseña.";

    return esValida ? "" : "La contraseña no cumple con todos los requisitos.";
}



function inicializarValidaciones(id) {
    const formulario = document.getElementById(id);

    const inputFoto = formulario.querySelector("#foto-perfil");
    const inputCV = formulario.querySelector("#cv");
    const inputNombre = formulario.querySelector("#nombre");
    const inputApellidos = formulario.querySelector("#apellido");
    const inputDireccion = formulario.querySelector("#direccion");
    const inputEmail = formulario.querySelector("#email");
    const inputPassword = formulario.querySelector("#password");
    const inputTelefono = formulario.querySelector("#telefono");
    const inputLocalidad = formulario.querySelector("#localidad");
    const inputProvincia = formulario.querySelector("#provincia");
    const inputCiclos = formulario.querySelector("#ciclos");
    const inputFamilia = formulario.querySelector("#familia");
    const selectedCiclosSelect = formulario.querySelector("#ciclosSeleccionados");

    /* Foto */
    if (inputFoto) {
        inputFoto.addEventListener("change", function () {
            ;
            const file = inputFoto.files[0];
            const mensajeError = validarImagen(file);
            mostrarError(this, mensajeError);
        });
    }

    /* CV */
    if (inputCV) {
        inputCV.addEventListener("change", function () {
            const file = inputCV.files[0];
            const mensajeError = validarCV(file);
            mostrarError(this, mensajeError);
        });
    }

    /* Nombre */
    if (inputNombre) {
        inputNombre.addEventListener("blur", function () {
            const mensajeError = this.value.trim() ? validarNombre(this.value) : "";
            mostrarError(this, mensajeError);
        });
    }

    /* Apellidos */
    if (inputApellidos) {
        inputApellidos.addEventListener("blur", function () {
            const mensajeError = this.value.trim() ? validarNombre(this.value) : "";
            mostrarError(this, mensajeError);
        });
    }

    /* Dirección */
    if (inputDireccion) {
        inputDireccion.addEventListener("blur", function () {
            const mensajeError = this.value.trim() ? validarDireccion(this.value) : "";
            mostrarError(this, mensajeError);
        });
    }

    /* Teléfono */
    if (inputTelefono) {
        inputTelefono.addEventListener("blur", function () {
            const mensajeError = this.value.trim() ? validarTelefono(this.value) : "";
            mostrarError(this, mensajeError);
        });
    }

    /* Email */
    if (inputEmail) {
        inputEmail.addEventListener("blur", function () {
            const mensajeError = this.value.trim() ? validarCorreoElectronico(this.value) : "";
            mostrarError(this, mensajeError);
        });
    }

    /* Localidad */
    if (inputLocalidad) {
        inputLocalidad.addEventListener("change", function () {
            const mensajeError = validarSelect(this.value, "localidad");
            mostrarError(this, mensajeError);
        });
    }

    /* Provincia */
    if (inputProvincia) {
        inputProvincia.addEventListener("change", function () {
            const mensajeError = validarSelect(this.value, "provincia");
            mostrarError(this, mensajeError);
        });
    }

    /* Password (opcional) */
    if (inputPassword) {
        inputPassword.addEventListener("input", function () {
            validarPassword(this.value);
            if (this.value.trim() !== "") {
                limpiarError(this);
            }
        });

        inputPassword.addEventListener("focus", function () {
            validarPassword(this.value);
        });

        inputPassword.addEventListener("blur", function () {
            //Resetear a neutral cuando pierde el foco y está vacío
            if (this.value === "") {
                const requisitos = [
                    document.getElementById("caracteres"),
                    document.getElementById("mayus"),
                    document.getElementById("minus"),
                    document.getElementById("num"),
                    document.getElementById("especial")
                ];

                requisitos.forEach(el => {
                    if (el) el.style.color = "";
                });
            }
        });
    }

    /* Familia */
    if (inputFamilia) {
        inputFamilia.addEventListener("change", function () {
            const mensajeError = validarSelect(this.value, "familia");
            mostrarError(this, mensajeError);
        });
    }

    /* Ciclos seleccionados */
    if (selectedCiclosSelect) {
        selectedCiclosSelect.addEventListener("change", function () {
            limpiarError(this);

            const seleccionados = Array.from(this.selectedOptions); // solo los seleccionados
            const mensajeError = validarCiclosSeleccionados(seleccionados);
            mostrarError(this, mensajeError);
        });
    }
}


function validarFormulario(formulario) {
    const inputFoto = formulario.querySelector("#foto-perfil");
    const inputCV = formulario.querySelector("#cv");
    const inputNombre = formulario.querySelector("#nombre");
    const inputApellidos = formulario.querySelector("#apellido");
    const inputDireccion = formulario.querySelector("#direccion");
    const inputEmail = formulario.querySelector("#email");
    const inputPassword = formulario.querySelector("#password");
    const inputTelefono = formulario.querySelector("#telefono");
    const inputLocalidad = formulario.querySelector("#localidad");
    const inputProvincia = formulario.querySelector("#provincia");
    const inputFamilia = formulario.querySelector("#familia");
    const selectedCiclosSelect = formulario.querySelector("#ciclosSeleccionados");

    let formularioValido = true;

    /* Foto */
    if (inputFoto) {
        limpiarError(inputFoto);
        const mensajeErrorFoto = validarImagen(inputFoto.files[0]);
        if (mensajeErrorFoto) {
            mostrarError(inputFoto, mensajeErrorFoto);
            formularioValido = false;
        }
    }

    /* CV */
    if (inputCV) {
        limpiarError(inputCV);
        const mensajeErrorCV = validarCV(inputCV.files[0]);
        if (mensajeErrorCV) {
            mostrarError(inputCV, mensajeErrorCV);
            formularioValido = false;
        }
    }

    /* Nombre */
    if (inputNombre) {
        limpiarError(inputNombre);
        const mensajeErrorNombre = validarNombre(inputNombre.value);
        if (mensajeErrorNombre) {
            mostrarError(inputNombre, mensajeErrorNombre);
            formularioValido = false;
        }
    }

    /* Apellidos */
    if (inputApellidos) {
        limpiarError(inputApellidos);
        const mensajeErrorApellidos = validarNombre(inputApellidos.value);
        if (mensajeErrorApellidos) {
            mostrarError(inputApellidos, mensajeErrorApellidos);
            formularioValido = false;
        }
    }

    /* Dirección */
    if (inputDireccion) {
        limpiarError(inputDireccion);
        const mensajeErrorDireccion = validarDireccion(inputDireccion.value);
        if (mensajeErrorDireccion) {
            mostrarError(inputDireccion, mensajeErrorDireccion);
            formularioValido = false;
        }
    }

    /* Email */
    if (inputEmail) {
        limpiarError(inputEmail);
        const mensajeErrorEmail = validarCorreoElectronico(inputEmail.value);
        if (mensajeErrorEmail) {
            mostrarError(inputEmail, mensajeErrorEmail);
            formularioValido = false;
        }
    }

    /* Password */
    if (inputPassword) {
        limpiarError(inputPassword);
        const mensajeErrorPassword = validarPassword(inputPassword.value);
        if (mensajeErrorPassword) {
            mostrarError(inputPassword, mensajeErrorPassword);
            formularioValido = false;
        }
    }

    /* Teléfono */
    if (inputTelefono) {
        limpiarError(inputTelefono);
        const mensajeErrorTelefono = validarTelefono(inputTelefono.value);
        if (mensajeErrorTelefono) {
            mostrarError(inputTelefono, mensajeErrorTelefono);
            formularioValido = false;
        }
    }

    /* Localidad */
    if (inputLocalidad) {
        limpiarError(inputLocalidad);
        const mensajeErrorLocalidad = validarSelect(inputLocalidad.value, "localidad");
        if (mensajeErrorLocalidad) {
            mostrarError(inputLocalidad, mensajeErrorLocalidad);
            formularioValido = false;
        }
    }

    /* Provincia */
    if (inputProvincia) {
        limpiarError(inputProvincia);
        const mensajeErrorProvincia = validarSelect(inputProvincia.value, "provincia");
        if (mensajeErrorProvincia) {
            mostrarError(inputProvincia, mensajeErrorProvincia);
            formularioValido = false;
        }
    }

    /* Familia */
    if (inputFamilia) {
        limpiarError(inputFamilia);
        const mensajeErrorFamilia = validarSelect(inputFamilia.value, "familia");
        if (mensajeErrorFamilia) {
            mostrarError(inputFamilia, mensajeErrorFamilia);
            formularioValido = false;
        }
    }

    /* Ciclos seleccionados */
    if (selectedCiclosSelect) {
        limpiarError(selectedCiclosSelect);
        const mensajeErrorCiclos = validarCiclosSeleccionados(Array.from(selectedCiclosSelect.options));
        if (mensajeErrorCiclos) {
            mostrarError(selectedCiclosSelect, mensajeErrorCiclos);
            formularioValido = false;
        }
    }

    return formularioValido;
}

function validarFormularioEditar(formulario) {
    const inputFoto = formulario.querySelector("#foto-perfil");
    const inputCV = formulario.querySelector("#cv");
    const inputNombre = formulario.querySelector("#nombre");
    const inputApellidos = formulario.querySelector("#apellido");
    const inputDireccion = formulario.querySelector("#direccion");
    const inputEmail = formulario.querySelector("#email");
    const inputTelefono = formulario.querySelector("#telefono");
    const inputLocalidad = formulario.querySelector("#localidad");
    const inputProvincia = formulario.querySelector("#provincia");

    let formularioValido = true;

    // Validar foto (solo si hay archivo)
    if (inputFoto && inputFoto.files.length > 0) {
        const mensajeErrorFoto = validarImagen(inputFoto.files[0]);
        if (mensajeErrorFoto) {
            mostrarError(inputFoto, mensajeErrorFoto);
            formularioValido = false;
        }
    }

    // Validar CV (solo si hay archivo)
    if (inputCV && inputCV.files.length > 0) {
        const mensajeErrorCV = validarCV(inputCV.files[0]);
        if (mensajeErrorCV) {
            mostrarError(inputCV, mensajeErrorCV);
            formularioValido = false;
        }
    }

    const mensajeErrorNombre = validarNombre(inputNombre.value);
    if (mensajeErrorNombre) {
        mostrarError(inputNombre, mensajeErrorNombre);
        formularioValido = false;
    }

    const mensajeErrorApellidos = validarNombre(inputApellidos.value);
    if (mensajeErrorApellidos) {
        mostrarError(inputApellidos, mensajeErrorApellidos);
        formularioValido = false;
    }

    const mensajeErrorDireccion = validarDireccion(inputDireccion.value);
    if (mensajeErrorDireccion) {
        mostrarError(inputDireccion, mensajeErrorDireccion);
        formularioValido = false;
    }

    const mensajeErrorEmail = validarCorreoElectronico(inputEmail.value);
    if (mensajeErrorEmail) {
        mostrarError(inputEmail, mensajeErrorEmail);
        formularioValido = false;
    }

    const mensajeErrorTelefono = validarTelefono(inputTelefono.value);
    if (mensajeErrorTelefono) {
        mostrarError(inputTelefono, mensajeErrorTelefono);
        formularioValido = false;
    }

    const mensajeErrorLocalidad = validarSelect(inputLocalidad.value, "localidad");
    if (mensajeErrorLocalidad) {
        mostrarError(inputLocalidad, mensajeErrorLocalidad);
        formularioValido = false;
    }

    const mensajeErrorProvincia = validarSelect(inputProvincia.value, "provincia");
    if (mensajeErrorProvincia) {
        mostrarError(inputProvincia, mensajeErrorProvincia);
        formularioValido = false;
    }

    return formularioValido;
}


