//Consulta para poblar inputs del modificar reserva
document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);
    const reservaId = params.get("id");
    const token = localStorage.getItem("jwtToken");

    if (!reservaId || !token) {
        alert("Falta información de la reserva o no has iniciado sesión");
        window.location.href = "login.html";
        return;
    }

    // Obtener datos
    fetch(`http://localhost:8080/reservas/${reservaId}`, {
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
        document.getElementById("reserva_id").value = reserva.reserva_id;
        document.getElementById("restauranteNombre").textContent = reserva.nombre_restaurante || "";
        document.getElementById("fecha_reserva").value = reserva.fecha.split("T")[0];
        document.getElementById("hora_reserva").value = reserva.fecha.split("T")[1].substring(0, 5);
        document.getElementById("motivo").value = reserva.motivo || "";
        document.getElementById("numero_personas").value = reserva.cantidadPersonas || 1;
        document.getElementById("requisitos_especiales").value = reserva.requisitosEspeciales || "";
        document.getElementById("alergias_intolerancias").value = reserva.alergias || "";
    })
    .catch(error => {
        console.error("❌ Error cargando datos de la reserva:", error);
        alert("No se pudo cargar la reserva.");
    });

    // Enviar datos actualizados
    document.getElementById("reservaForm").addEventListener("submit", function(e) {
        e.preventDefault(); // Evita el envío tradicional

        const updatedReserva = {
            id: reservaId,
            fecha: `${document.getElementById("fecha_reserva").value}T${document.getElementById("hora_reserva").value}`,
            motivo: document.getElementById("motivo").value,
            cantidadPersonas: document.getElementById("numero_personas").value,
            requisitosEspeciales: document.getElementById("requisitos_especiales").value,
            alergias: document.getElementById("alergias_intolerancias").value
        };

        fetch(`http://localhost:8080/reservas/${reservaId}`, {
            method: "PUT",
            headers: {
                "Authorization": `Bearer ${token}`,
                "Content-Type": "application/json"
            },
            body: JSON.stringify(updatedReserva)
        })
        .then(res => {
            if (!res.ok) throw new Error("No se pudo modificar la reserva");
            return res.json();
        })
        .then(data => {
            alert("✅ Reserva actualizada exitosamente.");
            window.location.href = "listar_reservas.html";
        })
        .catch(error => {
            console.error("❌ Error actualizando reserva:", error);
            alert("Hubo un problema al actualizar la reserva.");
        });
    });
});