// Toggle del menú de perfil
document.getElementById('profileMenuBtn').addEventListener('click', function(e) {
    e.stopPropagation();
    document.getElementById('profileMenu').classList.toggle('show');
});

// Cerrar menú al hacer click fuera
document.addEventListener('click', function() {
    document.getElementById('profileMenu').classList.remove('show');
});

// Evitar que el click dentro del dropdown lo cierre
document.getElementById('profileMenu').addEventListener('click', function(e) {
    e.stopPropagation();
});