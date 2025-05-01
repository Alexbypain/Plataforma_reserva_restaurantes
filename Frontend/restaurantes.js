// restaurantes.js

// 1. Mostrar mensaje de éxito (si viene ?success=reservada)
function mostrarMensajeExito() {
    const params = new URLSearchParams(window.location.search);
    if (params.get("success") === "reservada") {
      const msg = document.getElementById("successMessage");
      if (msg) {
        msg.style.display = "block";
        setTimeout(() => msg.style.display = "none", 5000);
        params.delete("success");
        const base = window.location.pathname;
        const rest = params.toString();
        window.history.replaceState({}, document.title, rest ? `${base}?${rest}` : base);
      }
    }
  }
  
  // 2. Parseo de JWT para extraer usuario y roles
  function parseJwt(token) {
    try {
      const base64Url = token.split(".")[1];
      const base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
      const json = decodeURIComponent(atob(base64).split("").map(c =>
        "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2)
      ).join(""));
      return JSON.parse(json);
    } catch {
      return null;
    }
  }
  
  // 3. Renderizar usuario y menú según rol
  function inicializarUsuario() {
    const token = localStorage.getItem("jwtToken");
    if (!token) {
      return window.location.href = "login.html";
    }
  
    const payload = parseJwt(token);
    if (!payload) {
      localStorage.removeItem("jwtToken");
      return window.location.href = "login.html";
    }
  
    // Nombre de usuario
    document.getElementById("userName").textContent = payload.nombre;
  
    // Opciones admin
    if (payload.roles === 0) {
      document.getElementById("adminLink")?.style.setProperty("display","inline-block");
      document.getElementById("adminDropdown")?.style.setProperty("display","block");
    }
  
    // Opciones user (restaurante y reservas)
    if (payload.roles === 1) {
      document.getElementById("userRestauranteLink")?.style.setProperty("display","inline-block");
      document.getElementById("userReservasLink")?.style.setProperty("display","inline-block");
    } else {
      // Si no es USER, ocultar
      document.getElementById("userRestauranteLink")?.style.setProperty("display","none");
      document.getElementById("userReservasLink")?.style.setProperty("display","none");
    }
  
    // Dropdown toggle
    document.getElementById("userBtn").addEventListener("click", () => {
      document.getElementById("menu").classList.toggle("show");
    });
    window.addEventListener("click", e => {
      if (!e.target.closest(".dropdown")) {
        document.getElementById("menu").classList.remove("show");
      }
    });
  }
  
  // 4. Cargar restaurantes (filtrado o lista)
  function cargarRestaurantes() {
    const token = localStorage.getItem("jwtToken");
    if (!token) return;
  
    const params = new URLSearchParams(window.location.search);
    const nombre = params.get("restaurante_nombre");
    const endpoint = nombre
      ? `http://localhost:8080/restaurantes/nombre?restaurante_nombre=${encodeURIComponent(nombre)}`
      : "http://localhost:8080/restaurantes?page=0&size=50&sort=rating,desc";
  
    console.log("🔍 Fetch:", endpoint);
    fetch(endpoint, {
      method: "GET",
      headers: {
        "Authorization": `Bearer ${token}`,
        "Content-Type": "application/json"
      }
    })
    .then(res => {
      if (!res.ok) throw new Error("Error en servidor");
      return res.json();
    })
    .then(data => {
      const container = document.getElementById("restaurantesContainer");
      container.innerHTML = "";
  
      const lista = Array.isArray(data.content)
        ? data.content
        : Array.isArray(data)
          ? data
          : [];
  
      if (lista.length === 0) {
        container.innerHTML = `<p class="text-center mt-4">No se encontraron restaurantes.</p>`;
        return;
      }
  
      lista.forEach(r => {
        const rating = (r.rating != null && r.rating !== "null")
          ? `${r.rating} <span class="star">★</span>`
          : "No ha sido calificado";
        const img = r.imagen
          ? `data:image/jpeg;base64,${r.imagen}`
          : "assets/imagenes/default-restaurante.jpg";
  
        const col = document.createElement("div");
        col.className = "col-md-4 mb-4";
        col.innerHTML = `
          <div class="card shadow-sm h-100">
            <img src="${img}" class="card-img-top" alt="${r.nombre}">
            <div class="card-body d-flex flex-column text-center">
              <h5 class="card-title">${r.nombre}</h5>
              <p class="calificacion mb-1">${rating}</p>
              <p class="mb-3">${r.direccion}</p>
              <button class="btn btn-primary mt-auto reservar-btn"
                data-id="${r.restaurante_id}"
                data-nombre="${r.nombre}">
                Reservar
              </button>
            </div>
          </div>`;
        container.appendChild(col);
      });
  
      container.querySelectorAll(".reservar-btn").forEach(btn => {
        btn.addEventListener("click", () => {
          localStorage.setItem("restaurante_id", btn.dataset.id);
          localStorage.setItem("restaurante_nombre", btn.dataset.nombre);
          window.location.href = "reservar.html";
        });
      });
    })
    .catch(err => console.error("❌ Error cargando restaurantes:", err));
  }
  
  // 5. Orquestación al cargar la página
  document.addEventListener("DOMContentLoaded", () => {
    mostrarMensajeExito();
    inicializarUsuario();
    cargarRestaurantes();
  });
  