document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();
    window.location.href = "dashboard.html";
});

function abrirCalendario() {
    alert("Aquí se abrirá el calendario de reservas.");
}
