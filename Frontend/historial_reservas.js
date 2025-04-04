document.addEventListener("DOMContentLoaded", function () {
    const mensajeExito = localStorage.getItem("mensajeExito");

    if (mensajeExito) {
        const contenedor = document.getElementById("mensajeExito");
        if (contenedor) {
            contenedor.textContent = mensajeExito;
            contenedor.classList.remove("d-none");

            setTimeout(() => {
                contenedor.classList.add("d-none");
            }, 2000);
        }
        localStorage.removeItem("mensajeExito");
    }


    // 🔹 Obtener el token y extraer usuario_id
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
            const jsonPayload = decodeURIComponent(
                atob(base64)
                    .split("")
                    .map((c) => "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2))
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
        alert("Token inválido o falta información de usuario");
        window.location.href = "login.html";
        return;
    }

    const usuarioId = decodedToken.id.toString();

    // 🔹 Hacer la petición al servidor
    fetch(`http://localhost:8080/reservas/historial?usuario_id=${usuarioId}`, {
        method: "GET",
        headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": "application/json",
        },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Error al obtener las reservas");
            }
            return response.json();
        })
        .then((data) => {
            console.log("✅ Reservas obtenidas:", data);

            const container = document.getElementById("reservasContainer");
            if (!container) {
                console.error("❌ No se encontró el contenedor de reservas.");
                return;
            }
            container.innerHTML = "";

            // 🔹 Verificar si hay reservas
            if (!data.content || data.content.length === 0) {
                container.innerHTML = "<p class='text-center text-muted'>No tienes reservas activas.</p>";
                return;
            }

            // 🔹 Iterar sobre las reservas y mostrarlas en tarjetas
            data.content.forEach((reserva) => {
                const card = document.createElement("div");
                card.classList.add("col-md-4");

                // Verifica si la reserva ya tiene una calificación
                const yaCalificada = reserva.calificacion_id !== null;

                card.innerHTML = `
                    <div class="card shadow-lg">
                        <div class="card-body text-center">
                            <h5 class="card-title">${reserva.nombre_restaurante}</h5>
                            <p class="text-muted">${reserva.fecha.replace("T", " a las ")}</p>
                            <button class="btn btn-primary ver-detalles" data-id="${reserva.reserva_id}">
                                Mostrar detalles
                            </button>
                            ${
                                !yaCalificada
                                    ? `<button class="btn btn-success calificar" data-id="${reserva.reserva_id}">
                                           Calificar
                                       </button>`
                                    : `<span class="badge bg-secondary mt-2">Ya calificada</span>`
                            }
                            <div class="detalles-reserva mt-3" id="detalles-${reserva.reserva_id}" style="display: none;">
                                <p><strong>Motivo:</strong> ${reserva.motivo}</p>
                                <p><strong>Personas:</strong> ${reserva.cantidadPersonas}</p>
                                <p><strong>Requisitos:</strong> ${reserva.requisitosEspeciales}</p>
                                <p><strong>Alergias:</strong> ${reserva.alergias}</p>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });

            // 🔹 Agregar evento a los botones "Mostrar detalles"
            document.querySelectorAll(".ver-detalles").forEach((button) => {
                button.addEventListener("click", function () {
                    const reservaId = this.getAttribute("data-id");
                    const detallesDiv = document.getElementById(`detalles-${reservaId}`);
                    if (detallesDiv.style.display === "none") {
                        detallesDiv.style.display = "block";
                        this.textContent = "Ocultar detalles";
                    } else {
                        detallesDiv.style.display = "none";
                        this.textContent = "Mostrar detalles";
                    }
                });
            });

            // 🔹 Agregar evento a los botones "Calificar"
            document.querySelectorAll(".calificar").forEach((button) => {
                button.addEventListener("click", function () {
                    const reservaId = this.getAttribute("data-id");
                    // Guardamos el reserva_id en el localStorage
                    localStorage.setItem("reserva_id", reservaId);
                    // Redirigimos a la página calificar.html
                    window.location.href = "calificar.html";
                });
            });
        })
        .catch((error) => console.error("❌ Error al obtener reservas:", error));
});
