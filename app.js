// Cambio de tema claro/oscuro con persistencia. Sin dependencias.
(function () {
  // Clave de almacenamiento para recordar la preferencia del visitante.
  var STORAGE_KEY = "laravel-landing-theme";
  var LIGHT = "light";
  var DARK = "dark";

  var toggleButton = document.getElementById("theme-toggle");
  var icon = toggleButton ? toggleButton.querySelector(".theme-toggle-icon") : null;
  var label = toggleButton ? toggleButton.querySelector(".theme-toggle-text") : null;
  var year = document.getElementById("year");

  // Actualiza el año del pie de página si el elemento existe.
  if (year) {
    year.textContent = String(new Date().getFullYear());
  }

  // Aplica un tema válido al documento y actualiza el botón.
  function applyTheme(theme) {
    var next = theme === DARK ? DARK : LIGHT;
    document.documentElement.dataset.theme = next;

    if (toggleButton) {
      var isDark = next === DARK;
      toggleButton.setAttribute("aria-pressed", isDark ? "true" : "false");
      toggleButton.setAttribute(
        "aria-label",
        isDark ? "Cambiar a tema claro" : "Cambiar a tema oscuro"
      );
    }

    if (icon) {
      icon.textContent = next === DARK ? "🌙" : "☀️";
    }


    return next;
  }

  // Lee el tema guardado; devuelve null si no hay valor válido.
  function readStoredTheme() {
    try {
      var stored = window.localStorage.getItem(STORAGE_KEY);
      if (stored === LIGHT || stored === DARK) {
        return stored;
      }
      return null;
    } catch (error) {
      return null;
    }
  }

  // Guarda la preferencia; ignora errores de almacenamiento privado.
  function storeTheme(theme) {
    try {
      window.localStorage.setItem(STORAGE_KEY, theme);
    } catch (error) {
      // Sin almacenamiento disponible: el tema solo dura la sesión actual.
    }
  }

  // Tema inicial: preferencia guardada, luego sistema, luego claro.
  function resolveInitialTheme() {
    var stored = readStoredTheme();
    if (stored) {
      return stored;
    }

    if (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches) {
      return DARK;
    }

    return LIGHT;
  }

  var currentTheme = applyTheme(resolveInitialTheme());

  if (toggleButton) {
    toggleButton.addEventListener("click", function () {
      currentTheme = currentTheme === DARK ? LIGHT : DARK;
      applyTheme(currentTheme);
      storeTheme(currentTheme);
    });
  }

  // Menú móvil: muestra u oculta la navegación principal en pantallas pequeñas.
  var navToggle = document.getElementById("nav-toggle");
  var primaryNav = document.getElementById("primary-nav");
  var navIcon = navToggle ? navToggle.querySelector(".nav-toggle-icon") : null;

  // Aplica el estado del menú y sincroniza texto accesible e icono.
  function setNavOpen(open) {
    if (!navToggle || !primaryNav) {
      return;
    }
    primaryNav.classList.toggle("is-open", open);
    navToggle.setAttribute("aria-expanded", open ? "true" : "false");
    navToggle.setAttribute(
      "aria-label",
      open ? "Cerrar menú de navegación" : "Abrir menú de navegación"
    );
    if (navIcon) {
      navIcon.textContent = open ? "✕" : "☰";
    }
  }

  if (navToggle && primaryNav) {
    navToggle.addEventListener("click", function () {
      setNavOpen(!primaryNav.classList.contains("is-open"));
    });

    // Cierra el menú al elegir una sección.
    primaryNav.addEventListener("click", function (event) {
      var link = event.target && event.target.closest ? event.target.closest("a") : null;
      if (link) {
        setNavOpen(false);
      }
    });

    // Cierra el menú con la tecla Escape.
    document.addEventListener("keydown", function (event) {
      if (event.key === "Escape") {
        setNavOpen(false);
      }
    });
  }
})();
