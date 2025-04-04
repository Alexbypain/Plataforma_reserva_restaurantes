// register.js

document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');
    
    if (registerForm) {
      registerForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const usuario = document.getElementById('usuario').value;
        const email = document.getElementById('regEmail').value;
        const password = document.getElementById('regPassword').value;
        
        try {
          const response = await fetch('http://localhost:8080/login/register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              nombre: usuario, // Asegúrate de que el DTO en Spring Boot use "nombre" y "password"
              email: email,
              password: password
            })
          });
          
          if (!response.ok) {
            throw new Error('Error en el registro');
          }
          
          // Puedes recibir un mensaje de confirmación
          const result = await response.text();
          alert('Registro exitoso. Ahora inicia sesión.');
          // Redirigir al login
          window.location.href = "login.html";
          
        } catch (error) {
          console.error('Error en el registro:', error);
          alert('Error en el registro. Intenta de nuevo.');
        }
      });
    }
  });
  