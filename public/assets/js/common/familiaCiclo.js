function cargarSelectFamiliaYCiclo(selectFamilia, selectCiclo, cicloId = null) {
    fetch('assets/api/api_familia.php', { method: 'GET' })
        .then(res => res.json())
        .then(data => {
            // Limpiar los select
            selectFamilia.innerHTML = '';
            selectCiclo.innerHTML = '';

            // Opción por defecto
            const defaultFam = document.createElement('option');
            defaultFam.value = '';
            defaultFam.textContent = 'Seleccione una familia profesional';
            defaultFam.disabled = true;
            defaultFam.selected = cicloId === null;
            selectFamilia.appendChild(defaultFam);

            const defaultCic = document.createElement('option');
            defaultCic.value = '';
            defaultCic.textContent = 'Seleccione un ciclo formativo';
            defaultCic.disabled = true;
            defaultCic.selected = cicloId === null;
            selectCiclo.appendChild(defaultCic);

            let familiaSeleccionada = null;
            let cicloSeleccionado = null;

            // Buscar familia y ciclo si se pasa un cicloId (modo edición)
            if (cicloId !== null) {
                for (let fam of data) {
                    const ciclo = fam.ciclos.find(c => c.id == cicloId);
                    if (ciclo) {
                        familiaSeleccionada = fam;
                        cicloSeleccionado = ciclo;
                        break;
                    }
                }
            }

            // Cargar familias
            data.forEach(fam => {
                const option = document.createElement('option');
                option.value = fam.id;
                option.textContent = fam.nombre;
                if (familiaSeleccionada && fam.id == familiaSeleccionada.id) option.selected = true;
                selectFamilia.appendChild(option);
            });

            // Si hay familia seleccionada, cargar ciclos correspondientes
            if (familiaSeleccionada) {
                cargarSelectCiclo(selectCiclo, familiaSeleccionada, cicloSeleccionado ? cicloSeleccionado.nombre : '');
            }

            // Evento al cambiar la familia
            selectFamilia.addEventListener('change', () => {
                const fam = data.find(f => f.id == selectFamilia.value);
                if (fam) {
                    cargarSelectCiclo(selectCiclo, fam);
                } else {
                    // Si se deselecciona, limpiar ciclos
                    selectCiclo.innerHTML = '';
                    const def = document.createElement('option');
                    def.value = '';
                    def.textContent = 'Seleccione un ciclo formativo';
                    def.disabled = true;
                    def.selected = true;
                    selectCiclo.appendChild(def);
                }
            });
        })
        .catch(err => console.error("Error al cargar familias:", err));
}

function cargarSelectCiclo(selectCiclo, familia, nombreCicloSeleccionado = '') {
    selectCiclo.innerHTML = '';

    // Opción por defecto
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = 'Seleccione un ciclo formativo';
    defaultOption.disabled = true;
    defaultOption.selected = nombreCicloSeleccionado === '';
    selectCiclo.appendChild(defaultOption);

    // Cargar ciclos
    familia.ciclos.forEach(ciclo => {
        const option = document.createElement('option');
        option.value = ciclo.id;
        option.textContent = ciclo.nombre;
        if (ciclo.nombre === nombreCicloSeleccionado) option.selected = true;
        selectCiclo.appendChild(option);
    });
}
