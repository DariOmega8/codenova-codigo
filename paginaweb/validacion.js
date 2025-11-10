// Espera a que el DOM esté completamente cargado antes de ejecutar el código
document.addEventListener("DOMContentLoaded", () => {

  // Expresiones regulares para validación de campos
  const reEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Valida formato de email
  const rePhone = /^[0-9]{6,15}$/; // Valida teléfono: 6-15 dígitos numéricos
  const reName = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{2,60}$/; // Valida nombre: letras y espacios, 2-60 caracteres
  
  // Función para mostrar errores (usa alertas nativas)
  function showError(msg){ alert(msg); }
  

  // Selecciona todos los formularios de la página y les agrega el validador
  document.querySelectorAll("form").forEach(form => {
    // Agrega un event listener para el evento 'submit' de cada formulario
    form.addEventListener("submit", (e) => {
      // Obtiene todos los campos requeridos del formulario actual
      const inputs = Array.from(form.querySelectorAll("input[required], textarea[required], select[required]"));
      
      // Itera sobre cada campo requerido para validarlo
      for (const inp of inputs) {
        // Obtiene y limpia el valor del campo
        const val = (inp.value || "").trim();
        
        // Validación 1: Campo vacío
        if (!val) {
          e.preventDefault(); // Evita el envío del formulario
          showError("Completa el campo: " + (inp.getAttribute("name") || inp.getAttribute("id") || "campo requerido"));
          inp.focus(); // Enfoca el campo problemático
          return; // Detiene la validación
        }
        
        // Obtiene el nombre del campo para validaciones específicas
        const name = inp.getAttribute("name") || "";
      
        // Validación 2: Email
        if (name.toLowerCase().includes("email") || inp.type === "email") {
          if (!reEmail.test(val)) { 
            e.preventDefault(); 
            showError("Ingresa un email válido."); 
            inp.focus(); 
            return; 
          }
        }
        
        // Validación 3: Teléfono
        if (name.toLowerCase().includes("tel") || name.toLowerCase().includes("telefono") || inp.type === "tel") {
          if (!rePhone.test(val)) { 
            e.preventDefault(); 
            showError("El teléfono debe contener solo números (6-15 dígitos)."); 
            inp.focus(); 
            return; 
          }
        }
        
        // Validación 4: Nombre
        if (name.toLowerCase().includes("nombre") || name.toLowerCase().includes("nombre")) {
          if (!reName.test(val)) { 
            e.preventDefault(); 
            showError("El nombre contiene caracteres inválidos."); 
            inp.focus(); 
            return; 
          }
        }
        
        // Validación 5: Campos numéricos
        if (inp.type === "number") {
          if (isNaN(val) || val === "") { 
            e.preventDefault(); 
            showError("Introduce un número válido en: " + (name || inp.id)); 
            inp.focus(); 
            return; 
          }
        }
      
        // Validación 6: Longitud mínima y máxima
        const min = inp.getAttribute("minlength");
        const max = inp.getAttribute("maxlength");
        
        // Verifica longitud mínima si está definida
        if (min && val.length < parseInt(min)) { 
          e.preventDefault(); 
          showError("El campo requiere al menos " + min + " caracteres."); 
          inp.focus(); 
          return; 
        }
        
        // Verifica longitud máxima si está definida
        if (max && val.length > parseInt(max)) { 
          e.preventDefault(); 
          showError("El campo admite como máximo " + max + " caracteres."); 
          inp.focus(); 
          return; 
        }
      }
    
    });
  });
});