
document.addEventListener("DOMContentLoaded", () => {

  const reEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  const rePhone = /^[0-9]{6,15}$/; // 6-15 digits
  const reName = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{2,60}$/;
  
  function showError(msg){ alert(msg); }
  

  document.querySelectorAll("form").forEach(form => {
    form.addEventListener("submit", (e) => {
      const inputs = Array.from(form.querySelectorAll("input[required], textarea[required], select[required]"));
      for (const inp of inputs) {
        const val = (inp.value || "").trim();
        if (!val) {
          e.preventDefault();
          showError("Completa el campo: " + (inp.getAttribute("name") || inp.getAttribute("id") || "campo requerido"));
          inp.focus();
          return;
        }
        const name = inp.getAttribute("name") || "";
      
        if (name.toLowerCase().includes("email") || inp.type === "email") {
          if (!reEmail.test(val)) { e.preventDefault(); showError("Ingresa un email válido."); inp.focus(); return; }
        }
        if (name.toLowerCase().includes("tel") || name.toLowerCase().includes("telefono") || inp.type === "tel") {
          if (!rePhone.test(val)) { e.preventDefault(); showError("El teléfono debe contener solo números (6-15 dígitos)."); inp.focus(); return; }
        }
        if (name.toLowerCase().includes("nombre") || name.toLowerCase().includes("nombre")) {
          if (!reName.test(val)) { e.preventDefault(); showError("El nombre contiene caracteres inválidos."); inp.focus(); return; }
        }
        if (inp.type === "number") {
          if (isNaN(val) || val === "") { e.preventDefault(); showError("Introduce un número válido en: " + (name || inp.id)); inp.focus(); return; }
        }
      
        const min = inp.getAttribute("minlength");
        const max = inp.getAttribute("maxlength");
        if (min && val.length < parseInt(min)) { e.preventDefault(); showError("El campo requiere al menos " + min + " caracteres."); inp.focus(); return; }
        if (max && val.length > parseInt(max)) { e.preventDefault(); showError("El campo admite como máximo " + max + " caracteres."); inp.focus(); return; }
      }
    
    });
  });
});

