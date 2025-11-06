function gestionFoto(modalEditar) {
    const modalFoto = new Modal();
    modalFoto.cargarPlantilla("modalCamara.html").then(() => {
        modalFoto.mostrar();
        modalEditar.ocultar();
        const apagarCamara = manejoCamara(modalFoto, modalEditar);
        const btnCerrarCamara = document.getElementById("cerrarCamara");
        btnCerrarCamara.addEventListener("click", function c() {
            apagarCamara();
            modalFoto.destruir();
            modalEditar.mostrar();
            btnCerrarCamara.removeEventListener("click", c);
        });
    });
}

function manejoCamara(modalFoto, modalEditar) {
    const video = document.getElementById('video');
    const snap = document.getElementById('snap');
    const guardarBtn = document.getElementById('guardar');
    const repetirBtn = document.getElementById('repetir');
    const canvas = document.getElementById("canvas");
    const context = canvas.getContext("2d");
    const recorte = document.getElementById("recorte");

    let stream = null;

    // --- Inicializa webcam ---
    async function init() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480 } });
            video.srcObject = stream;
        } catch (e) {
            console.error("Error accediendo a la webcam:", e);
        }
    }

    // --- Apagar cámara ---
    function apagarCamara() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
    }

    // --- Capturar imagen ---
    snap.addEventListener('click', function () {
        const rect = recorte.getBoundingClientRect();
        const videoRect = video.getBoundingClientRect();

        const x = rect.left - videoRect.left;
        const y = rect.top - videoRect.top;
        const w = recorte.offsetWidth;
        const h = recorte.offsetHeight;

        context.drawImage(video, x, y, w, h, 0, 0, canvas.width, canvas.height);

        video.style.display = "none";
        canvas.style.display = "block";
        guardarBtn.style.display = "inline-block";
        repetirBtn.style.display = "inline-block";
        snap.style.display = "none";
        recorte.style.display = "none";
    });

    // --- Guardar recorte ---
    guardarBtn.addEventListener('click', async function () {
        canvas.toBlob(blob => {
            // Crear un File con el blob del canvas
            const file = new File([blob], "foto.png", { type: "image/png" });

            // Asignar el archivo al input del modal editar
            const inputFile = modalEditar.modal.querySelector('#foto-perfil');
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            inputFile.files = dataTransfer.files;

            // Actualizar la vista previa de la foto en el modal editar
            const fotoDiv = modalEditar.modal.querySelector('#foto');
            const url = URL.createObjectURL(file);
            fotoDiv.style.backgroundImage = `url(${url})`;

            // Cerrar cámara y mostrar modal editar
            guardarBtn.style.display = "none";
            apagarCamara();
            modalFoto.destruir();
            modalEditar.mostrar();
        }, 'image/png');
    });

    // --- Repetir foto ---
    repetirBtn.addEventListener('click', function () {
        video.style.display = "block";
        canvas.style.display = "none";
        guardarBtn.style.display = "none";
        repetirBtn.style.display = "none";
        snap.style.display = "inline-block";
        recorte.style.display = "block";
    });

    init();

    return apagarCamara;
}
