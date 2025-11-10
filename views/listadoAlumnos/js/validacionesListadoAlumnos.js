function inicializarValidaciones(id) {
    const formularioEditar = document.getElementById(id);
    const inputFoto = formularioEditar.querySelector("#foto-perfil");
    const inputCV = formularioEditar.querySelector("#cv");
    const inputNombre = formularioEditar.querySelector("#nombre");
    const inputApellidos = formularioEditar.querySelector("#apellido");
    const inputDireccion = formularioEditar.querySelector("#direccion");
    const inputEmail = formularioEditar.querySelector("#email");
    const inputTelefono = formularioEditar.querySelector("#telefono");
    const inputLocalidad = formularioEditar.querySelector("#localidad");
    const inputProvincia = formularioEditar.querySelector("#provincia");

    /* Evento para validar la foto de perfil dinámico */
    inputFoto.addEventListener("change", function () {
        const file = inputFoto.files[0];
        const mensajeError = validarImagen(file);
        mostrarError(this, mensajeError);
    });

    /* Evento para validar el CV dinámico */
    inputCV.addEventListener("change", function () {
        const file = inputCV.files[0];
        const mensajeError = validarCV(file);
        mostrarError(this, mensajeError);
    });

    /* Evento para validar el nombre dinámico */
    inputNombre.addEventListener("blur", function () {
        if (this.value.trim() !== "") {
            const mensajeError = validarNombre(this.value);
            mostrarError(this, mensajeError);
        } else {
            limpiarError(this);
        }
    });

    /* Evento para validar los apellidos dinámico */
    inputApellidos.addEventListener("blur", function () {
        if (this.value.trim() !== "") {
            const mensajeError = validarNombre(this.value);
            mostrarError(this, mensajeError);
        } else {
            limpiarError(this);
        }
    });

    /* Evento para validar la dirección dinámica */
    inputDireccion.addEventListener("blur", function () {
        if (this.value.trim() !== "") {
            const mensajeError = validarDireccion(this.value);
            mostrarError(this, mensajeError);
        } else {
            limpiarError(this);
        }
    });

    /* Evento para validar el teléfono dinámico */
    inputTelefono.addEventListener("blur", function () {
        if (this.value.trim() !== "") {
            const mensajeError = validarTelefono(this.value);
            mostrarError(this, mensajeError);
        } else {
            limpiarError(this);
        }
    });

    /* Evento para validar el email dinámico */
    inputEmail.addEventListener("blur", function () {
        if (this.value.trim() !== "") {
            const mensajeError = validarCorreoElectronico(this.value);
            mostrarError(this, mensajeError);
        } else {
            limpiarError(this);
        }
    });

    /* Evento para validar select localidad */
    inputLocalidad.addEventListener("change", function () {
        const mensajeError = validarSelect(this.value, "localidad");
        mostrarError(this, mensajeError);
    });

    /* Evento para validar select provincia */
    inputProvincia.addEventListener("change", function () {
        const mensajeError = validarSelect(this.value, "provincia");
        mostrarError(this, mensajeError);
    });


}

function validarFormulario(formulario) {
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
    // Validar cada campo utilizando las funciones de validación
    if (inputFoto) {
        const mensajeErrorFoto = validarImagen(inputFoto.files[0]);
        if (mensajeErrorFoto) {
            mostrarError(inputFoto, mensajeErrorFoto);
            formularioValido = false;
        }
    }
    if (inputCV) {
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