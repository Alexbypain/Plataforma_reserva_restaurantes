// login.js

// Función para decodificar el JWT (para extraer el payload, sin validación)
function parseJwt(token) {
    try {
      const base64Url = token.split('.')[1];
      const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
      const jsonPayload = decodeURIComponent(
        atob(base64)
          .split('')
          .map(c => '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2))
          .join('')
      );
      return JSON.parse(jsonPayload);
    } catch (error) {
      console.error('Error al decodificar el token:', error);
      return null;
    }
  }
  
  document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    
    if (loginForm) {
      loginForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        
        try {
          const response = await fetch('http://localhost:8080/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email: email, password: password })
          });
          
          if (!response.ok) {
            throw new Error('Error en la autenticación');
          }
          
          const data = await response.json();
          // Se asume que el DTO devuelto tiene la propiedad "jwTtoken"
          const token = data.jwTtoken;
          
          // Guardar el token en localStorage
          localStorage.setItem('jwtToken', token);
          
          // Opcional: extraer y guardar el nombre de usuario (si lo incluye el payload)
          const payload = parseJwt(token);
          if (payload && payload.sub) {
            // Aquí asumimos que el "subject" (sub) es el email, pero puedes almacenar otros datos si incluyes el nombre.
            localStorage.setItem('userEmail', payload.sub);
          }
          
          // Redirigir a la página principal
          window.location.href = "index.html";
          
        } catch (error) {
          console.error('Error en el login:', error);
          alert('Error en la autenticación. Verifica tus credenciales.');
        }
      });
    }
  });
  