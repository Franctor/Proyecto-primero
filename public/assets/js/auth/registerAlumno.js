inicializarValidaciones("formAlumno");
const formulario = document.getElementById("formAlumno");
const btnTomarFoto = document.getElementById("tomarFoto");
const submitBtn = document.getElementById("submitBtn");
const selectProvincia = document.getElementById("provincia");
const selectLocalidad = document.getElementById("localidad");
const selectFamilia = document.getElementById("familia");
const selectCiclo = document.getElementById("ciclos");
const selectedCiclosDiv = document.getElementById("selectedCiclos");
const selectedCiclosSelect = document.getElementById("ciclosSeleccionados");
const options = document.querySelectorAll('#ciclos option');

btnTomarFoto.addEventListener("click", function () {
    fotoRegistro();
});

formulario.addEventListener("submit", function (e) {
    e.preventDefault();

    if (validarFormulario(formulario)) {
        let datos = new FormData(formulario);

        // Agregar ciclos seleccionados al FormData
        ciclosSeleccionados.forEach(ciclo => {
            datos.append('ciclosSeleccionados[]', ciclo.id);
        });
        console.log(datos.getAll('ciclosSeleccionados[]'));

        fetch('assets/api/api_alumno.php', {
            method: 'POST',
            body: datos
        })
            .then(response => response.json())
            .then(data => {
                // Limpia errores previos si los hay
                const errorExistente = document.querySelector('.error-register');
                if (errorExistente) errorExistente.remove();

                // Si la API responde éxito
                if (data.respuesta) {
                    window.location.href = 'index.php?menu=login&registro=exito';
                } else {
                    const errorDiv = document.createElement('div');
                    const errorMensaje = document.createElement('p');
                    errorMensaje.textContent = data.message || 'Error en el registro: Correo y/o teléfono ya están en uso.';
                    errorDiv.appendChild(errorMensaje);
                    errorDiv.className = 'error-register';

                    if (submitBtn) {
                        formulario.insertBefore(errorDiv, submitBtn);
                    }
                }
            })
            .catch(err => {
                console.error('Error en la petición:', err);
            });

    } else {
        // Que vuelva hacia arriba si hay errores
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
});

cargarSelectProvinciaYLocalidad(selectProvincia, selectLocalidad);
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
    const value = e.target.value;
    ciclosSeleccionados = ciclosSeleccionados.filter(c => c.id != value);
    actualizarSelectCiclos();
});