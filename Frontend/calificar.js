document.addEventListener("DOMContentLoaded", function() {
    // Mostrar datos del restaurante (opcional)
    const restauranteId = localStorage.getItem("restaurante_id");
    const restauranteNombre = localStorage.getItem("restaurante_nombre");
    const infoRestaurante = document.getElementById("infoRestaurante");
  

    // Obtener el reserva_id almacenado
    const reservaId = localStorage.getItem("reserva_id");
    if (!reservaId) {
        alert("No se encontró información de la reserva a calificar.");
        // Redirigir o manejar el error según corresponda
        return;
    }

    // Obtener el token y extraer usuario_id
    const token = localStorage.getItem("jwtToken");
    if (!token) {
        alert("Debe iniciar sesión primero");
        window.location.href = "login.html";
        return;
    }

    function parseJwt(token) {
        try {
            const base64Url = token.split(".")[1];
            const base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
            const jsonPayload = decodeURIComponent(atob(base64).split("").map(c =>
                "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2)
            ).join(""));
            return JSON.parse(jsonPayload);
        } catch (error) {
            console.error("Error al decodificar el token:", error);
            return null;
        }
    }

    const decodedToken = parseJwt(token);
    if (!decodedToken || !decodedToken.id) {
        alert("Token inválido o falta información de usuario");
        window.location.href = "login.html";
        return;
    }

    const usuarioId = decodedToken.id.toString();

    // Manejo del envío del formulario de calificación
    const formCalificar = document.getElementById("formCalificar");
    formCalificar.addEventListener("submit", function(event) {
        event.preventDefault();
        
        const comentario = document.getElementById("comentario").value.trim();
        const rating = document.getElementById("rating").value;

        if (!comentario || !rating) {
            alert("Por favor, completa todos los campos.");
            return;
        }

        // Construir el JSON a enviar
        const payload = {
            usuario_id: usuarioId,
            reserva_id: reservaId,
            comentario: comentario,
            rating: Number(rating)
        };

        // Realizar la petición POST
        fetch("http://localhost:8080/calificaciones", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify(payload)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Error al enviar la calificación");
            }
            return response.json();
        })
        .then(data => {
            alert("¡Calificación enviada con éxito!");
            // Opcional: redirigir a otra página o limpiar el formulario
            window.location.href = "historial_reservas.html";
        })
        .catch(error => {
            console.error("❌ Error al enviar la calificación:", error);
            alert("Ocurrió un error al enviar la calificación. Inténtalo de nuevo.");
        });
    });
});
