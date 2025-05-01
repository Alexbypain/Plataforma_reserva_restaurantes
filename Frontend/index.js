// Solo redirección: NO toca restaurantes.js ni carga datos aquí
document.getElementById("exploreBtn").addEventListener("click", () => {
    const q = document.getElementById("searchInput").value.trim();
    if (q) {
      window.location.href = `restaurantes.html?restaurante_nombre=${encodeURIComponent(q)}`;
    } else {
      window.location.href = "restaurantes.html";
    }
  });

document.addEventListener("DOMContentLoaded", function() {
    // ✅ Verificar mensaje de éxito
    const urlParams = new URLSearchParams(window.location.search);
    const successParam = urlParams.get("success");
    
    if (successParam === "restaurante-creado") {
        const messageDiv = document.getElementById("successMessage");
        if (messageDiv) {
            messageDiv.style.display = "block";
            setTimeout(() => {
                messageDiv.style.display = "none";
            }, 5000);
        }
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    // ✅ Obtener el token del Local Storage
    const token = localStorage.getItem("jwtToken");
    if (!token) {
        window.location.href = "login.html"; // Redirigir si no hay sesión activa
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

    const payload = parseJwt(token);
    if (payload) {
        document.getElementById("userName").textContent = payload.nombre;

        // ✅ Mostrar enlaces de admin si el rol es ADMIN (0)
        if (payload) {
            document.getElementById("userName").textContent = payload.nombre;
        
            // Mostrar opciones para el rol ADMIN (0)
            if (payload.roles === 0) {
                document.getElementById("adminLink").style.display = "inline-block";
                document.getElementById("adminDropdown").style.display = "block";
                document.getElementById("adminReservasNowLink").style.display = "block";

            }
        
            // Mostrar enlaces de Restaurante y Reservas solo si es rol USER (1)
            if (payload.roles === 1) {
                const restauranteLink = document.getElementById("userRestauranteLink");
                const reservasLink = document.getElementById("userReservasLink");
        
                if (restauranteLink) restauranteLink.style.display = "inline-block";
                if (reservasLink) reservasLink.style.display = "inline-block";
            } else {
                // Ocultar explícitamente si no es rol USER
                const restauranteLink = document.getElementById("userRestauranteLink");
                const reservasLink = document.getElementById("userReservasLink");
        
                if (restauranteLink) restauranteLink.style.display = "none";
                if (reservasLink) reservasLink.style.display = "none";
            }
        }
        
    } else {
        localStorage.removeItem("jwtToken");
        window.location.href = "login.html";
    }

    // ✅ Obtener restaurantes
    fetch("http://localhost:8080/restaurantes?page=0&size=4&sort=rating,desc", {
        method: "GET",
        headers: {
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json"
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Error en la respuesta del servidor");
        }
        return response.json();
    })
    .then(data => {
        console.log("Datos de restaurantes recibidos:", data);
        const container = document.getElementById("restaurantesContainer");
        container.innerHTML = ""; // Limpiar contenido previo

        // Iterar sobre los restaurantes y agregarlos al HTML
        data.content.forEach(restaurante => {
            const card = document.createElement("div");
            card.classList.add("col-md-4");

            // Verificar si la imagen existe y convertirla a base64
            let imagenSrc = restaurante.imagen
                ? `data:image/jpeg;base64,${restaurante.imagen}`
                : "assets/imagenes/default-restaurante.jpg"; // Imagen por defecto si no hay imagen

            card.innerHTML = `
                <div class="card shadow-lg">
                    <img src="${imagenSrc}" class="card-img-top" alt="${restaurante.nombre}">
                    <div class="card-body text-center">
                        <h5 class="card-title">${restaurante.nombre}</h5>
                        <p class="rating">${restaurante.rating} <span class="star">★</span> </p>
                        <p class="rating">${restaurante.direccion}</p>
                    </div>
                </div>
            `;
            container.appendChild(card);
        });
    })
    .catch(error => console.error("Error cargando restaurantes:", error));
});

// ✅ Manejo del dropdown
document.getElementById("userBtn").addEventListener("click", function() {
    document.getElementById("menu").classList.toggle("show");
});

window.addEventListener("click", function(event) {
    if (!event.target.closest(".dropdown")) {
        document.getElementById("menu").classList.remove("show");
    }
});
