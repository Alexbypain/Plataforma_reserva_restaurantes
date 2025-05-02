document.addEventListener("DOMContentLoaded", function() {
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

    // 🔹 Hacer la petición al servidor para obtener las reservas
    fetch(`http://localhost:8080/reservas?usuario_id=${usuarioId}`, {
        method: "GET",
        headers: {
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json"
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Error al obtener las reservas");
        }
        return response.json();
    })
    .then(data => {
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
        data.content.forEach(reserva => {
            const card = document.createElement("div");
            card.classList.add("col-md-4");

            card.innerHTML = `
                <div class="card shadow-lg">
                    <div class="card-body text-center">
                        <h5 class="card-title">${reserva.nombre_restaurante}</h5>
                        <p class="text-muted">${reserva.fecha.replace("T", " a las ")}</p>
                        <button class="btn btn-primary ver-detalles" data-id="${reserva.reserva_id}">
                            Mostrar detalles
                        </button>
                        <div class="detalles-reserva mt-3" id="detalles-${reserva.reserva_id}" style="display: none;">
                            <p><strong>Motivo:</strong> ${reserva.motivo}</p>
                            <p><strong>Personas:</strong> ${reserva.cantidadPersonas}</p>
                            <p><strong>Requisitos:</strong> ${reserva.requisitosEspeciales}</p>
                            <p><strong>Alergias:</strong> ${reserva.alergias}</p>
                        </div>
                        <button class="btn btn-success editar" data-id="${reserva.reserva_id}">
                            Modificar
                        </button>
                        <button class="btn btn-danger eliminar" data-id="${reserva.reserva_id}">
                            Eliminar
                        </button>
                        <button class="btn btn-info exportar mt-2" data-id="${reserva.reserva_id}">
                            Exportar PDF
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(card);
        });

        // 🔹 Agregar evento a los botones "Mostrar detalles"
        document.querySelectorAll(".ver-detalles").forEach(button => {
            button.addEventListener("click", function() {
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

        // 🔹 Agregar evento a los botones "Editar"
        document.querySelectorAll(".editar").forEach(button => {
            button.addEventListener("click", function () {
                const reservaId = this.getAttribute("data-id");
                redirigir(reservaId);
            });
        });

        // 🔹 Agregar evento a los botones "Eliminar"
        document.querySelectorAll(".eliminar").forEach(button => {
            button.addEventListener("click", function () {
                const reservaId = this.getAttribute("data-id");
                if (!confirm("¿Estás seguro de que deseas eliminar esta reserva?")) {
                    return;
                }
                // Realizar petición DELETE
                fetch(`http://localhost:8080/reservas/${reservaId}`, {
                    method: "DELETE",
                    headers: {
                        "Authorization": `Bearer ${token}`,
                        "Content-Type": "application/json"
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Error al eliminar la reserva");
                    }
                    // Se elimina la tarjeta del DOM
                    const card = button.closest('.col-md-4');
                    if (card) {
                        card.remove();
                    }
                    alert("Reserva eliminada exitosamente");
                })
                .catch(error => {
                    console.error("❌ Error al eliminar la reserva:", error);
                    alert("Ocurrió un error al eliminar la reserva");
                });
            });
        });

        // Agregar evento al botón "Exportar PDF"
        document.querySelectorAll(".exportar").forEach(button => {
            button.addEventListener("click", function () {
                const reservaId = this.getAttribute("data-id");
                const btn = this;
                btn.disabled = true;
                btn.textContent = "Generando PDF...";

                fetch(`http://localhost:8080/reservas/${reservaId}`, {
                    method: "GET",
                    headers: {
                        "Authorization": `Bearer ${token}`,
                        "Content-Type": "application/json"
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error("No se pudo obtener la reserva");
                    return res.json();
                })
                .then(reserva => {
                    console.log(reserva)
                    const pdfContent = `
                        <div style="font-family: Arial, sans-serif; padding: 30px; border: 1px solid #ccc; color: #333;">
                            <h1 style="color:#4A90E2; font-weight:bold; text-align: center; margin-bottom: 20px;">RESUMEN DE RESERVA</h1>
                            <hr style="border-top: 2px solid #bbb; margin-bottom: 20px;">
                            <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                                <tbody>
                                    <tr>
                                        <th style="background-color: #f8f8f8; color: #555; padding: 10px; border: 1px solid #ddd; text-align: left; text-transform: uppercase;">Restaurante</th>
                                        <td style="background-color: #f8f8f8; color: #555; padding: 10px; border: 1px solid #ddd; text-align: left; text-transform: uppercase;">${reserva.nombre_restaurante}</td>
                                    </tr>
                                    <tr>
                                        <th style="background-color: #f8f8f8; color: #555; padding: 10px; border: 1px solid #ddd; text-align: left; text-transform: uppercase;">Fecha</th>
                                        <td style="background-color: #f8f8f8; color: #555; padding: 10px; border: 1px solid #ddd; text-align: left; text-transform: uppercase;">${reserva.fecha.replace("T", " a las ")}</td>
                                    </tr>
                                    <tr>
                                        <th style="background-color: #f8f8f8; color: #555; padding: 10px; border: 1px solid #ddd; text-align: left; text-transform: uppercase;">Motivo</th>
                                        <td style="background-color: #f8f8f8; color: #555; padding: 10px; border: 1px solid #ddd; text-align: left; text-transform: uppercase;">${reserva.motivo}</td>
                                    </tr>
                                    <tr>
                                        <th style="background-color: #f8f8f8; color: #555; padding: 10px; border: 1px solid #ddd; text-align: left; text-transform: uppercase;">Personas</th>
                                        <td style="background-color: #f8f8f8; color: #555; padding: 10px; border: 1px solid #ddd; text-align: left; text-transform: uppercase;">${reserva.cantidadPersonas} personas</td>
                                    </tr>
                                    <tr>
                                        <th style="background-color: #f8f8f8; color: #555; padding: 10px; border: 1px solid #ddd; text-align: left; text-transform: uppercase;">Requisitos Especiales</th>
                                        <td style="background-color: #f8f8f8; color: #555; padding: 10px; border: 1px solid #ddd; text-align: left; text-transform: uppercase;">${reserva.requisitosEspeciales}</td>
                                    </tr>
                                    <tr>
                                        <th style="background-color: #f8f8f8; color: #555; padding: 10px; border: 1px solid #ddd; text-align: left; text-transform: uppercase;">Alergias</th>
                                        <td style="background-color: #f8f8f8; color: #555; padding: 10px; border: 1px solid #ddd; text-align: left; text-transform: uppercase;">${reserva.alergias}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    `;

                     // Inyectar contenido en el contenedor oculto
                    const container = document.getElementById("pdfContainer");
                    container.innerHTML = pdfContent;

                    const opt = {
                        margin:     0.5,
                        filename:   `reserva_${reserva.reserva_id}.pdf`,
                        image:      { type: 'jpeg', quality: 0.98 },
                        html2canvas: { scale: 2 },
                        jsPDF:      { unit: 'in', format: 'letter', orientation: 'landscape' } // Cambiado a 'landscape'
                    };

                    html2pdf().from(pdfContent).set(opt).save().then(() => {
                        alert("✅ PDF exportado exitosamente");
                        btn.disabled = false;
                        btn.textContent = "Exportar PDF";
                    });
                })
                .catch(err => {
                    console.error("❌ Error exportando PDF:", err);
                    alert("Ocurrió un error al generar el PDF");
                    btn.disabled = false;
                    btn.textContent = "Exportar PDF";
                });
            });
        });
    })
    .catch(error => console.error("❌ Error al obtener reservas:", error));

    // Redirigir al editar
    function redirigir(reservaId) {
        window.location.href = `editar_reserva.html?id=${reservaId}`;
    }
});
