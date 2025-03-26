document.addEventListener("DOMContentLoaded", function() {
    // Recuperar los datos del restaurante desde localStorage
    const restauranteId = localStorage.getItem("restaurante_id");
    const restauranteNombre = localStorage.getItem("restaurante_nombre");

    console.log("Restaurante recuperado:", restauranteId, restauranteNombre);

    if (!restauranteId || !restauranteNombre) {
        console.error("No se encontraron datos del restaurante en localStorage.");
        window.location.href = "restaurantes.html";
        return;
    }

    // Mostrar datos en la página
    document.getElementById("restauranteNombre").textContent = restauranteNombre;
    document.getElementById("restauranteIdInput").value = restauranteId;

    // Recuperar el token del localStorage
    const token = localStorage.getItem("jwtToken");
    if (!token) {
        window.location.href = "login.html";
        return;
    }

    // Función para decodificar el token
    function parseJwt(token) {
        try {
            const base64Url = token.split('.')[1];
            const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
            const jsonPayload = decodeURIComponent(atob(base64).split('').map(c =>
                '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)
            ).join(''));
            return JSON.parse(jsonPayload);
        } catch (error) {
            console.error("Error al decodificar el token:", error);
            return null;
        }
    }

    // Decodificar el token para obtener el ID del usuario
    const decodedToken = parseJwt(token);
    if (!decodedToken || !decodedToken.id) {
        alert('Token inválido o falta información de usuario');
        window.location.href = "login.html";
        return;
    }
    // Guardamos el usuario_id obtenido del token en localStorage (opcional) o lo usamos directamente
    localStorage.setItem("usuario_id", decodedToken.id.toString());
});

document.getElementById("reservaForm").addEventListener("submit", function(event) {
    event.preventDefault();

    const token = localStorage.getItem("jwtToken");
    // Tomar usuario_id directamente del token decodificado
    const decodedToken = (function(token) {
        try {
            const base64Url = token.split('.')[1];
            const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
            const jsonPayload = decodeURIComponent(atob(base64).split('').map(c =>
                '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)
            ).join(''));
            return JSON.parse(jsonPayload);
        } catch (error) {
            console.error("Error al decodificar el token:", error);
            return null;
        }
    })(token);

    const usuarioId = decodedToken ? decodedToken.id.toString() : null;
    const restauranteId = document.getElementById("restauranteIdInput").value;
    const fecha = document.getElementById("fecha").value;
    const hora = document.getElementById("hora").value;
    const motivo = document.getElementById("motivo").value;
    const cantidadPersonas = document.getElementById("cantidadPersonas").value;
    const requisitosEspeciales = document.getElementById("requisitosEspeciales").value;
    const alergias = document.getElementById("alergias").value;

    console.log("Enviando reserva:");
    console.log("Restaurante ID:", restauranteId);
    console.log("Usuario ID:", usuarioId);
    console.log("Fecha y Hora:", `${fecha}T${hora}:00`);

    if (!restauranteId || !usuarioId) {
        console.error("Error: Restaurante ID o Usuario ID no definidos.");
        return;
    }

    const reservaData = {
        restaurante_id: restauranteId,
        usuario_id: usuarioId,
        fecha: `${fecha}T${hora}:00`,
        motivo,
        cantidadPersonas,
        requisitosEspeciales,
        alergias
    };

    fetch("http://localhost:8080/reservas", {
        method: "POST",
        headers: {
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json"
        },
        body: JSON.stringify(reservaData)
    })
    .then(response => {
        console.log("Respuesta del servidor:", response);
        if (!response.ok) {
            throw new Error(`Error en la reserva: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log("Reserva exitosa:", data);
        window.location.href = "restaurantes.html?success=reservada";
    })
    .catch(error => console.error("Error al reservar:", error));
});
