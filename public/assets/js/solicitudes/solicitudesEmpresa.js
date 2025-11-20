const cardsGrid = document.getElementById('cards-grid');
const section = document.querySelector('.solicitudes-empresa-section');
fetch('assets/api/api_empresa.php?me=true',
    { method: 'GET' }).then(response => response.json()).then(empresa => {
        console.log(empresa);
        fetch(`assets/api/api_oferta.php?empresaId=${empresa.id}`,
            { method: 'GET' }).then(response => response.json()).then(ofertas => {
                console.log(ofertas);
                for (const oferta of ofertas) {
                    const card = document.createElement('div');
                    card.classList.add('oferta-card');
                    if (oferta.fecha_fin < new Date().toISOString().split('T')[0]) {
                        card.classList.add('oferta-expirada');
                    }
                    const h2 = document.createElement('h2');
                    h2.textContent = oferta.titulo;
                    card.appendChild(h2);
                    const cardBody = document.createElement('div');

                    cardBody.classList.add('card-body');
                    const descripcion = document.createElement('p');
                    descripcion.innerHTML = `<strong>Descripción:</strong> ${oferta.descripcion}`;
                    cardBody.appendChild(descripcion);
                    const fechaInicio = document.createElement('p');
                    fechaInicio.innerHTML = `<strong>Fecha de creación:</strong> ${oferta.fecha_inicio}`;
                    cardBody.appendChild(fechaInicio);
                    const fechaFin = document.createElement('p');
                    fechaFin.innerHTML = `<strong>Fecha de Fin:</strong> ${oferta.fecha_fin}`;
                    cardBody.appendChild(fechaFin);

                    card.appendChild(cardBody);

                    const actionDiv = document.createElement('div');
                    actionDiv.classList.add('card-action');

                    const btnVerSolicitudes = document.createElement('button');
                    btnVerSolicitudes.textContent = 'Ver Solicitudes';

                    btnVerSolicitudes.addEventListener('click', () => {
                        let modalSolicitudes = new Modal();
                        modalSolicitudes.cargarPlantilla("assets/modals/modalSolicitudes.html").then(() => {
                            modalSolicitudes.mostrar();
                            const tituloOferta = modalSolicitudes.modal.querySelector('#tituloOferta');
                            const descripcionOferta = modalSolicitudes.modal.querySelector('#descripcionOferta');
                            tituloOferta.textContent = oferta.titulo;
                            descripcionOferta.textContent = oferta.descripcion;
                            if (oferta.solicitudes !== 'false' && oferta.solicitudes.length > 0) {
                                const table = document.createElement('table');
                                const thead = document.createElement('thead');
                                const headerRow = document.createElement('tr');
                                const thFecha = document.createElement('th');
                                const section = modalSolicitudes.modal.querySelector('#solicitudes-section');
                                thFecha.textContent = 'Fecha de Solicitud';
                                headerRow.appendChild(thFecha);

                                const thNombreAlumno = document.createElement('th');
                                thNombreAlumno.textContent = 'Nombre del Alumno';
                                headerRow.appendChild(thNombreAlumno);


                                const thApellidoAlumno = document.createElement('th');
                                thApellidoAlumno.textContent = 'Apellido del Alumno';
                                headerRow.appendChild(thApellidoAlumno);


                                const thTelefonoAlumno = document.createElement('th');
                                thTelefonoAlumno.textContent = 'Teléfono del Alumno';
                                headerRow.appendChild(thTelefonoAlumno);

                                const thAcciones = document.createElement('th');
                                thAcciones.textContent = 'Acciones';
                                headerRow.appendChild(thAcciones);

                                thead.appendChild(headerRow);
                                table.appendChild(thead);
                                section.appendChild(table);

                                const tbody = document.createElement('tbody');
                                table.appendChild(tbody);


                                for (const solicitud of oferta.solicitudes) {
                                    const row = document.createElement('tr');

                                    const tdFecha = document.createElement('td');
                                    tdFecha.textContent = solicitud.fecha_solicitud;
                                    row.appendChild(tdFecha);

                                    const tdNombreAlumno = document.createElement('td');
                                    tdNombreAlumno.textContent = solicitud.alumno.nombre;
                                    row.appendChild(tdNombreAlumno);

                                    const tdApellidoAlumno = document.createElement('td');
                                    tdApellidoAlumno.textContent = solicitud.alumno.apellido;
                                    row.appendChild(tdApellidoAlumno);

                                    const tdTelefonoAlumno = document.createElement('td');
                                    tdTelefonoAlumno.textContent = solicitud.alumno.telefono;
                                    row.appendChild(tdTelefonoAlumno);

                                    const tdAcciones = document.createElement('td');
                                    const btnLike = document.createElement('button');
                                    if (solicitud.estado == 1) {
                                        btnLike.innerHTML = '<img src="assets/imagenes/like.png" alt="Like">';
                                        btnLike.classList.add('like');
                                    } else {
                                        btnLike.innerHTML = '<img src="assets/imagenes/dislike.png" alt="Dislike">';
                                        btnLike.classList.add('dislike');
                                    }
                                    const btnEliminar = document.createElement('button');
                                    btnEliminar.textContent = 'Eliminar Solicitud';
                                    tdAcciones.appendChild(btnLike);
                                    tdAcciones.appendChild(btnEliminar);
                                    row.appendChild(tdAcciones);
                                    tbody.appendChild(row);

                                    btnEliminar.addEventListener('click', () => {
                                        let modalConfirmar = new Modal();
                                        modalConfirmar.cargarPlantilla("assets/modals/modalConfirmar.html").then(() => {
                                            modalSolicitudes.ocultar();
                                            modalConfirmar.mostrar();
                                            let btnCancelar = modalConfirmar.modal.querySelector("#cancelar");
                                            let btnConfirmar = modalConfirmar.modal.querySelector("#confirmar");
                                            btnCancelar.addEventListener('click', () => {
                                                modalConfirmar.destruir();
                                                modalSolicitudes.mostrar();
                                            });

                                            btnConfirmar.addEventListener('click', () => {
                                                fetch(`assets/api/api_solicitud.php?id=${solicitud.id}`, {
                                                    method: 'DELETE'
                                                }).then(response => response.json()).then(result => {
                                                    if (result.success == 'true') {
                                                        row.remove();
                                                    }
                                                    modalConfirmar.destruir();
                                                    modalSolicitudes.mostrar();
                                                    oferta.solicitudes = oferta.solicitudes.filter(s => s.id !== solicitud.id);
                                                });
                                            });
                                        });
                                    });

                                    btnLike.addEventListener('click', () => {
                                        const formData = new FormData();
                                        formData.append('id', solicitud.id);

                                        if (btnLike.classList.contains('dislike')) {
                                            formData.append('estado', '1');
                                        } else {
                                            formData.append('estado', '0');
                                        }

                                        formData.append('_method', 'PUT');

                                        fetch(`assets/api/api_solicitud.php?id=${solicitud.id}`, {
                                            method: 'POST',
                                            body: formData
                                        })
                                            .then(response => response.json())
                                            .then(result => {
                                                if (result.success) {
                                                    if (result.estado == 1) {
                                                        btnLike.classList.remove('dislike');
                                                        btnLike.classList.add('like');
                                                        btnLike.innerHTML = '<img src="assets/imagenes/like.png" alt="Like">';
                                                    } else {
                                                        btnLike.classList.remove('like');
                                                        btnLike.classList.add('dislike');
                                                        btnLike.innerHTML = '<img src="assets/imagenes/dislike.png" alt="Dislike">';
                                                    }
                                                    
                                                    solicitud.estado = result.estado;
                                                }
                                            });
                                    });

                                }
                            } else {
                                const p = document.createElement('p');
                                p.textContent = 'No hay solicitudes para esta oferta.';
                                const solicitudesSection = modalSolicitudes.modal.querySelector('#solicitudes-section');
                                solicitudesSection.appendChild(p);
                            }
                            let btnCerrar = modalSolicitudes.modal.querySelector("#cerrar");
                            btnCerrar.addEventListener('click', () => {
                                modalSolicitudes.destruir();
                            });
                        });
                    });
                    actionDiv.appendChild(btnVerSolicitudes);
                    card.appendChild(actionDiv);
                    cardsGrid.appendChild(card);
                }
            });
    });
