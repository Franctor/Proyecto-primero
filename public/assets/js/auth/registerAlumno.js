inicializarValidaciones("formAlumno");
const formulario = document.getElementById("formAlumno");
const btnTomarFoto = document.getElementById("tomarFoto");
const submitBtn = document.getElementById("submitBtn");
btnTomarFoto.addEventListener("click", function() {
    fotoRegistro();
});

// CORRECCIÓN: Usar submit en el formulario, no click en el botón
    if (formulario) {
        formulario.addEventListener("submit", function(e) {
            e.preventDefault(); // Esto previene completamente el envío
            
            console.log("Validando formulario..."); // Para debug
            
            if (validarFormulario(formulario)) {
                console.log("Formulario válido, enviando...");
                // Solo si es válido, enviamos programáticamente
            } else {
                console.log("Formulario inválido, no se envía");
                // Aquí puedes mostrar mensajes de error globales
            }
        });
    }
