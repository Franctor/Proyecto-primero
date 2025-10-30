window.addEventListener('load', function () {
    let tablas = document.querySelectorAll("table.editable");
    if (tablas.length === 0) return;

    let tabla = tablas[0];
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
});
