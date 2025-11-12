// Toggle del menú de perfil
document.getElementById('profileMenuBtn').addEventListener('click', function() {
    document.getElementById('profileMenu').classList.toggle('show');
});

// Cerrar menú al hacer click fuera
document.addEventListener('click', function(e) {
    if (!e.target.closest('.header-profile')) {
        document.getElementById('profileMenu').classList.remove('show');
    }
});