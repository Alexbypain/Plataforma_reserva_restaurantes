document.addEventListener("DOMContentLoaded", function () {
    const mensajeExito = document.getElementById("mensajeExito");
    const mensajeError = document.getElementById("mensajeError");

    function mostrarMensaje(mensajeElemento, texto) {
        mensajeElemento.textContent = texto;
        mensajeElemento.classList.remove("d-none");
    }

    const reservaId = localStorage.getItem("reserva_id");
    if (!reservaId) {
        mostrarMensaje(mensajeError, "❌ No se encontró información de la reserva a calificar.");
        return;
    }

    const token = localStorage.getItem("jwtToken");
    if (!token) {
        mostrarMensaje(mensajeError, "❌ Debe iniciar sesión primero");
        setTimeout(() => {
            window.location.href = "login.html";
        }, 2000);
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
            mostrarMensaje(mensajeError, "❌ Token inválido o falta información de usuario");
            setTimeout(() => {
                window.location.href = "login.html";
            }, 2000);
            return null;
        }
    }

    const decodedToken = parseJwt(token);
    if (!decodedToken || !decodedToken.id) {
        mostrarMensaje(mensajeError, "❌ Token inválido o falta información de usuario");
        setTimeout(() => {
            window.location.href = "login.html";
        }, 2000);
        return;
    }

    const usuarioId = decodedToken.id.toString();

    const formCalificar = document.getElementById("formCalificar");
    formCalificar.addEventListener("submit", function (event) {
        event.preventDefault();

        const comentario = document.getElementById("comentario").value.trim();
        const rating = document.getElementById("rating").value;

        if (!comentario || !rating) {
            mostrarMensaje(mensajeError, "❌ Por favor, completa todos los campos.");
            return;
        }

        const payload = {
            usuario_id: usuarioId,
            reserva_id: reservaId,
            comentario: comentario,
            rating: Number(rating)
        };

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
                localStorage.setItem("mensajeExito", "✅ ¡Tu calificación ha sido enviada con éxito!");
                window.location.href = "historial_reservas.html";
            })
            .catch(error => {
                console.error("❌ Error al enviar la calificación:", error);
                mostrarMensaje(mensajeError, "❌ Ocurrió un error al enviar la calificación. Inténtalo de nuevo.");
            });
    });
});
