function cargarSelectProvinciaYLocalidad(selectProvincia, selectLocalidad, localidadId = null) {
    fetch('assets/api/api_provincia.php', { method: 'GET' })
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

            // Buscar provincia y localidad
            if (localidadId !== null) {
                provinciaSeleccionada = data.find(prov =>
                    prov.localidades.some(l => l.id == localidadId)
                );

                if (provinciaSeleccionada) {
                    localidadSeleccionada = provinciaSeleccionada.localidades.find(l => l.id == localidadId);
                }
            }

            // Cargar provincias
            data.forEach(prov => {
                if (prov.id !== 777) {
                    const option = document.createElement('option');
                    option.value = prov.id;
                    option.textContent = prov.nombre_prov;
                    if (provinciaSeleccionada && prov.id == provinciaSeleccionada.id) option.selected = true;
                    selectProvincia.appendChild(option);
                }
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
        if (loc.id !== 777) {
            const option = document.createElement('option');
            option.value = loc.id;
            option.textContent = loc.nombre_loc;
            if (loc.nombre_loc === nombreLocalidadSeleccionada) option.selected = true;
            selectLocalidad.appendChild(option);
        }
    });
}