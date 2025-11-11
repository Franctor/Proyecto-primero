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


