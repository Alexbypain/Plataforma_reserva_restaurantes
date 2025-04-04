document.addEventListener("DOMContentLoaded", function () {
    const token = localStorage.getItem("jwtToken");

    if (!token) {
        alert("Debe iniciar sesión primero");
        window.location.href = "login.html";
        return;
    }

    // 🔹 Decodificar el JWT
    function parseJwt(token) {
        try {
            const base64Url = token.split(".")[1];
            const base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
            const jsonPayload = decodeURIComponent(
                atob(base64)
                    .split("")
                    .map(c => "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2))
                    .join("")
            );
            return JSON.parse(jsonPayload);
        } catch (error) {
            console.error("Error al decodificar el token:", error);
            return null;
        }
    }

    const decodedToken = parseJwt(token);
    if (!decodedToken || !decodedToken.id) {
        alert("Token inválido");
        return;
    }

    const usuarioId = decodedToken.id.toString();

    // 🔹 Obtener calificaciones
    fetch(`http://localhost:8080/calificaciones/reservas?usuario_id=${usuarioId}`, {
        method: "GET",
        headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": "application/json",
        },
    })
        .then(response => {
            if (!response.ok) {
                throw new Error("Error al obtener las calificaciones");
            }
            return response.json();
        })
        .then(data => {
            const container = document.getElementById("calificacionesContainer");
            container.innerHTML = "";

            if (!data.content || data.content.length === 0) {
                container.innerHTML = "<p class='text-center text-muted'>No hay calificaciones disponibles.</p>";
                return;
            }

            data.content.forEach(calificacion => {
                const card = document.createElement("div");
                card.classList.add("col-md-4");

                card.innerHTML = `
                    <div class="card border-success shadow">
                        <div class="card-body">
                            <h5 class="card-title"> ${calificacion.nombre_usuario.split('').map((char, index) =>
                                index === 0 ? char.toUpperCase() : char).join('')} </h5>
                            <h5 class="card-title">⭐ ${calificacion.rating} / 5</h5>
                            <p class="card-text"><strong>Comentario:</strong> ${calificacion.comentario}</p>
                            <p class="card-text"><strong>Fecha:</strong> ${calificacion.fecha.split("T")[0]}</p>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
        })
        .catch(error => {
            console.error("❌ Error al obtener calificaciones:", error);
            const container = document.getElementById("calificacionesContainer");
            container.innerHTML = "<p class='text-danger text-center'>Error al cargar calificaciones.</p>";
        });
});
