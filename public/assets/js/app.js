(function () {
  "use strict";

  // Confirmación antes de enviar formularios destructivos (eliminar).
  document.querySelectorAll("form.js-confirm").forEach(function (form) {
    form.addEventListener("submit", function (event) {
      var mensaje = form.getAttribute("data-confirm") || "¿Confirmas esta acción?";
      if (!window.confirm(mensaje)) {
        event.preventDefault();
      }
    });
  });

  // Toggle del sidebar del panel en mobile.
  var toggle = document.querySelector(".panel-sidebar-toggle");
  var sidebar = document.querySelector(".panel-sidebar");
  if (toggle && sidebar) {
    toggle.addEventListener("click", function () {
      sidebar.classList.toggle("is-open");
      toggle.setAttribute("aria-expanded", sidebar.classList.contains("is-open") ? "true" : "false");
    });
  }
})();
