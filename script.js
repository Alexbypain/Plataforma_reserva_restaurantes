document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();
    window.location.href = "dashboard.html";
});

function abrirCalendario() {
    alert("Aquí se abrirá el calendario de reservas.");
}
// Mostrar el calendario al hacer clic en "Reservar Ahora"
function mostrarCalendario() {
    document.getElementById("calendario").style.display = "flex";
}


