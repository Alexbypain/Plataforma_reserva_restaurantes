function mostrarMensajeExito() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get("success") === "reservada") {
        const messageDiv = document.getElementById("successMessage");
        if (messageDiv) {
            messageDiv.style.display = "block";

            // Ocultar el mensaje después de 5 segundos
            setTimeout(() => {
                messageDiv.style.display = "none";
            }, 5000);

            // Limpiar el parámetro de la URL sin recargar la página
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    }
}

function cargarRestaurantes() {
    const token = localStorage.getItem("jwtToken");
    if (!token) {
        console.error("❌ No hay token disponible.");
        return;
    }

    fetch("http://localhost:8080/restaurantes?page=0&size=50&sort=rating,desc", {
        method: "GET",
        headers: {
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json"
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("❌ Error en la respuesta del servidor");
        }
        return response.json();
    })
    .then(data => {
        console.log("✅ Datos de restaurantes recibidos:", data);
        const container = document.getElementById("restaurantesContainer");
        if (!container) {
            console.error("❌ No se encontró el contenedor de restaurantes.");
            return;
        }
        container.innerHTML = "";

        data.content.forEach(restaurante => {
            // Verificamos que restaurante_id esté definido
            if (!restaurante.restaurante_id) {
                console.error("❌ Falta el restaurante_id en el objeto:", restaurante);
                return;
            }

            const ratingHtml = (restaurante.rating != "null")
             ? `${restaurante.rating} <span class="star">★</span>`
             : 'No ha sido calificado';

            const card = document.createElement("div");
            card.classList.add("col-md-4");

            let imagenSrc = restaurante.imagen
                ? `data:image/jpeg;base64,${restaurante.imagen}`
                : "assets/imagenes/default-restaurante.jpg";

            card.innerHTML = `
                <div class="card shadow-lg">
                    <img src="${imagenSrc}" class="card-img-top" alt="${restaurante.nombre}">
                    <div class="card-body text-center">
                        <h5 class="card-title">${restaurante.nombre}</h5>
                        <p class="calificacion">${ratingHtml}</p>
                        <p class="rating">${restaurante.direccion}</p>
                        <button class="btn btn-primary mt-2 reservar-btn" 
                            data-id="${restaurante.restaurante_id}" 
                            data-nombre="${restaurante.nombre}">
                            Reservar
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(card);
        });

        // Agregar evento a los botones de "Reservar"
        const reservarButtons = document.querySelectorAll(".reservar-btn");
        if (reservarButtons.length === 0) {
            console.error("❌ No se encontraron botones de reservar.");
        }
        reservarButtons.forEach(button => {
            button.addEventListener("click", function() {
                const restauranteId = this.getAttribute("data-id");
                const restauranteNombre = this.getAttribute("data-nombre");

                console.log("✅ Restaurante seleccionado:", restauranteId, restauranteNombre);

                if (restauranteId && restauranteNombre) {
                    localStorage.setItem("restaurante_id", restauranteId);
                    localStorage.setItem("restaurante_nombre", restauranteNombre);
                    // Redirigir a la página de reservas sin parámetros en la URL
                    window.location.href = "reservar.html";
                } else {
                    console.error("❌ Falta restaurante_id o restaurante_nombre en el botón.");
                }
            });
        });
    })
    .catch(error => console.error("❌ Error cargando restaurantes:", error));
}

document.addEventListener("DOMContentLoaded", cargarRestaurantes);
