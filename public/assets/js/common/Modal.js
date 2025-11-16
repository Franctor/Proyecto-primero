class Modal {
  constructor() {
    const main = document.querySelector("main");
    this.modal = document.createElement("div");
    this.modal.classList.add("modal");
    this.velo = document.createElement("div");
    this.velo.classList.add("velo");
    main.append(this.velo, this.modal);
  }

  async cargarPlantillaConDatos(plantillaURL, datosURL, funcionRellenar) {
    const plantilla = await fetch(plantillaURL).then(r => r.text());
    const datos = await fetch(datosURL).then(r => r.json());
    const contenido = funcionRellenar(plantilla, datos);
    this.modal.innerHTML = contenido;
    return datos;
  }

  async cargarPlantilla(plantillaUrl){
    const plantilla = await fetch(plantillaUrl).then(r => r.text());
    this.modal.innerHTML = plantilla;
  }

  mostrar() {
    this.velo.style.display = "block";
    this.modal.style.display = "block";
  }

  ocultar() {
    this.velo.style.display = "none";
    this.modal.style.display = "none";
  }

  destruir() {
    this.modal.remove();
    this.velo.remove();
  }
}
