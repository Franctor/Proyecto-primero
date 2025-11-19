const cardsGrid = document.getElementById('cards-grid');
const section = document.querySelector('.solicitudes-alumno-section');
fetch('assets/api/api_alumno.php?me=true', {
    method: 'GET'
})
    .then(response => response.json())
    .then(alumno => {
        fetch(`assets/api/api_solicitud.php?idAlumno=${alumno.id}`, {
            method: 'GET'
        })
            .then(response => response.json())
            .then(solicitudes => {
                if (solicitudes.success !== 'false') {
                    for (const solicitud of solicitudes) {
                        const card = document.createElement('div');
                        card.classList.add('solicitud-card');
                        const h2 = document.createElement('h2');
                        h2.textContent = solicitud.oferta.titulo;
                        card.appendChild(h2);

                        const cardBody = document.createElement('div');
                        cardBody.classList.add('card-body');

                        const empresaNombre = document.createElement('p');
                        empresaNombre.innerHTML = `<strong>Empresa:</strong> ${solicitud.oferta.empresa.nombre}`;
                        cardBody.appendChild(empresaNombre);

                        const descripcion = document.createElement('p');
                        descripcion.innerHTML = `<strong>Descripción:</strong> ${solicitud.oferta.descripcion}`;
                        cardBody.appendChild(descripcion);

                        const fechaEnvio = document.createElement('p');
                        fechaEnvio.innerHTML = `<strong>Fecha de Envío:</strong> ${solicitud.fecha_solicitud}`;
                        cardBody.appendChild(fechaEnvio);

                        card.appendChild(cardBody);

                        const actionDiv = document.createElement('div');
                        actionDiv.classList.add('card-action');

                        const btnEliminar = document.createElement('button');
                        btnEliminar.textContent = 'Eliminar Solicitud';
                        btnEliminar.classList.add('btn');
                        btnEliminar.addEventListener('click', () => {
                            let modalConfirmar = new Modal();
                            modalConfirmar.cargarPlantilla("assets/modals/modalConfirmar.html").then(() => {
                                // fetch para eliminar la solicitud
                                modalConfirmar.mostrar();
                                let btnCancelar = modalConfirmar.modal.querySelector("#cancelar");
                                let btnConfirmar = modalConfirmar.modal.querySelector("#confirmar");

                                btnCancelar.addEventListener('click', () => {
                                    modalConfirmar.destruir();
                                });

                                btnConfirmar.addEventListener('click', () => {
                                    modalConfirmar.destruir();
                                    fetch(`assets/api/api_solicitud.php?id=${solicitud.id}`, {
                                        method: 'DELETE'
                                    })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                card.remove();
                                            } else {
                                                alert('Error al eliminar la solicitud');
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            alert('Error al eliminar la solicitud');
                                        });
                                });

                            });
                        });

                        const verDetalles = document.createElement('button');
                        verDetalles.textContent = 'Ver Detalles';
                        verDetalles.classList.add('btn');
                        verDetalles.addEventListener('click', () => {
                            // modal para ver detalles
                            let modalDetalles = new Modal();
                            modalDetalles.cargarPlantilla("assets/modals/fichaSolicitud.html").then(() => {
                                modalDetalles.mostrar();
                                modalDetalles.modal.querySelector("#tituloOferta").textContent = solicitud.oferta.titulo;
                                modalDetalles.modal.querySelector("#descripcionOferta").textContent = solicitud.oferta.descripcion;
                                modalDetalles.modal.querySelector("#empresaNombre").textContent = solicitud.oferta.empresa.nombre;
                                modalDetalles.modal.querySelector("#empresaDireccion").textContent = solicitud.oferta.empresa.direccion;
                                modalDetalles.modal.querySelector("#empresaTelefono").textContent = solicitud.oferta.empresa.telefono;
                                modalDetalles.modal.querySelector("#fechaSolicitud").textContent = solicitud.fecha_solicitud;
                                modalDetalles.modal.querySelector("#empresaLogo").src = "data:image/png;base64," + solicitud.oferta.empresa.logo;
                                const btnCerrar = modalDetalles.modal.querySelector("#cerrarFicha");
                                btnCerrar.addEventListener('click', () => {
                                    modalDetalles.destruir();
                                });
                            });
                        });

                        actionDiv.appendChild(verDetalles);
                        actionDiv.appendChild(btnEliminar);

                        card.appendChild(actionDiv);

                        cardsGrid.appendChild(card);
                    }
                } else {
                    const noSolicitudesMsg = document.createElement('p');
                    noSolicitudesMsg.textContent = 'No hay solicitudes disponibles.';
                    const ofertasLink = document.createElement('a');
                    ofertasLink.href = 'index.php?menu=ofertas';
                    ofertasLink.textContent = 'Ver ofertas disponibles';
                    noSolicitudesMsg.appendChild(document.createElement('br'));
                    noSolicitudesMsg.appendChild(ofertasLink);
                    section.appendChild(noSolicitudesMsg);
                }
            })
    });