const selectFamilia = document.getElementById('familia');
const selectCiclo = document.getElementById('ciclos');
const selectedCiclosSelect = document.getElementById('ciclosSeleccionados');
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