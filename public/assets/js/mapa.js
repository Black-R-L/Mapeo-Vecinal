(function () {
  "use strict";

  // Centro por defecto si todavía no hay reportes con ubicación.
  var CENTRO_DEFECTO = [-34.6037, -58.3816];
  var ZOOM_DEFECTO = 12;
  var STORAGE_KEY = "mv_filtros_mapa";

  function leerPreferencias() {
    try {
      return JSON.parse(window.localStorage.getItem(STORAGE_KEY)) || {};
    } catch (e) {
      return {};
    }
  }

  function guardarPreferencias(prefs) {
    try {
      window.localStorage.setItem(STORAGE_KEY, JSON.stringify(prefs));
    } catch (e) {
      /* localStorage puede no estar disponible (modo privado); no es crítico. */
    }
  }

  // Pin con el icono real de la categoría, no solo un color: el color es
  // refuerzo visual, el icono es lo que realmente distingue la categoría.
  function iconoParaCategoria(colorHex, iconoClase) {
    var clase = iconoClase ? "fa-solid " + iconoClase : "fa-solid fa-location-dot";
    return L.divIcon({
      className: "",
      html:
        '<span class="mv-marker-pin" style="background:' +
        (colorHex || "#1f7a5c") +
        '"><i class="' +
        clase +
        '" aria-hidden="true"></i></span>',
      iconSize: [30, 30],
      iconAnchor: [15, 28],
      popupAnchor: [0, -26],
    });
  }

  function badgeEstado(estado) {
    var etiquetas = {
      nuevo: "Nuevo",
      en_progreso: "En progreso",
      resuelto: "Resuelto",
      rechazado: "Rechazado",
    };
    return '<span class="badge-estado ' + estado + '">' + (etiquetas[estado] || estado) + "</span>";
  }

  function chipCategoria(nombre, colorHex, iconoClase) {
    var clase = iconoClase ? "fa-solid " + iconoClase : "fa-solid fa-tag";
    return (
      '<span class="categoria-chip"><i class="' +
      clase +
      '" style="color:' +
      (colorHex || "#1f7a5c") +
      '" aria-hidden="true"></i>' +
      nombre +
      "</span>"
    );
  }

  function activarChips(nodeList, valoresActivos) {
    nodeList.forEach(function (chip) {
      var activo = valoresActivos.length === 0 || valoresActivos.indexOf(chip.dataset.valor) !== -1;
      chip.setAttribute("aria-pressed", activo ? "true" : "false");
    });
  }

  function valoresActivosDe(nodeList) {
    var valores = [];
    nodeList.forEach(function (chip) {
      if (chip.getAttribute("aria-pressed") === "true") {
        valores.push(chip.dataset.valor);
      }
    });
    return valores;
  }

  /**
   * Mapa de reportes con filtros por categoría (chips multi-toggle), estado,
   * barrio y "cerca de mí". Agrupa marcadores cercanos (clustering) para
   * que no se sature con muchos puntos.
   *
   * @param {string} elementId id del <div> contenedor
   * @param {string} endpoint  URL que devuelve {success, data:[...]}
   * @param {object} opciones  {categoriaChips, estadoChips, barrioSelect, cercaDeMiBtn}
   */
  window.initMapaReportes = function (elementId, endpoint, opciones) {
    var el = document.getElementById(elementId);
    if (!el || typeof L === "undefined") {
      return;
    }

    opciones = opciones || {};
    var categoriaChips = opciones.categoriaChips || [];
    var estadoChips = opciones.estadoChips || [];
    var barrioSelect = opciones.barrioSelect || null;
    var cercaDeMiBtn = opciones.cercaDeMiBtn || null;

    var mapa = L.map(elementId).setView(CENTRO_DEFECTO, ZOOM_DEFECTO);
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      attribution: "&copy; colaboradores de OpenStreetMap",
      maxZoom: 19,
    }).addTo(mapa);

    var capaMarcadores =
      typeof L.markerClusterGroup === "function"
        ? L.markerClusterGroup({ maxClusterRadius: 50 })
        : L.layerGroup();
    capaMarcadores.addTo(mapa);

    // Restaurar preferencias guardadas (categorías/estado/barrio elegidos la última vez).
    var prefs = leerPreferencias();
    if (categoriaChips.length && prefs.categorias) {
      activarChips(categoriaChips, prefs.categorias);
    }
    if (estadoChips.length && prefs.estados) {
      activarChips(estadoChips, prefs.estados);
    }
    if (barrioSelect && prefs.barrio) {
      barrioSelect.value = prefs.barrio;
    }

    function guardarYCargar() {
      guardarPreferencias({
        categorias: categoriaChips.length ? valoresActivosDe(categoriaChips) : [],
        estados: estadoChips.length ? valoresActivosDe(estadoChips) : [],
        barrio: barrioSelect ? barrioSelect.value : "",
      });
      cargar();
    }

    function cargar() {
      var params = new URLSearchParams();

      if (categoriaChips.length) {
        var cats = valoresActivosDe(categoriaChips);
        if (cats.length && cats.length < categoriaChips.length) {
          params.set("categoria_id", cats.join(","));
        }
      }

      if (estadoChips.length) {
        var estados = valoresActivosDe(estadoChips);
        if (estados.length === 1) {
          params.set("estado", estados[0]);
        }
      }

      if (barrioSelect && barrioSelect.value) {
        params.set("barrio", barrioSelect.value);
      }

      fetch(endpoint + (params.toString() ? "?" + params.toString() : ""))
        .then(function (res) {
          return res.json();
        })
        .then(function (json) {
          capaMarcadores.clearLayers();
          if (!json.success || !json.data) {
            return;
          }

          var puntos = [];
          json.data.forEach(function (r) {
            var lat = parseFloat(r.latitud);
            var lng = parseFloat(r.longitud);
            if (isNaN(lat) || isNaN(lng)) {
              return;
            }
            puntos.push([lat, lng]);

            var popup =
              '<strong><a href="/reportes/' +
              r.id +
              '">' +
              r.titulo +
              "</a></strong><br>" +
              badgeEstado(r.estado) +
              " " +
              chipCategoria(r.categoria, r.color, r.icono) +
              (r.votos_totales > 0 ? "<br>👍 " + r.votos_totales + " apoyos" : "");

            L.marker([lat, lng], { icon: iconoParaCategoria(r.color, r.icono) })
              .bindPopup(popup)
              .addTo(capaMarcadores);
          });

          if (puntos.length && !(barrioSelect && barrioSelect.dataset.centradoManual)) {
            mapa.fitBounds(puntos, { padding: [30, 30], maxZoom: 15 });
          }
        })
        .catch(function () {
          /* Si falla la carga, el mapa queda vacío pero usable. */
        });
    }

    categoriaChips.forEach(function (chip) {
      chip.addEventListener("click", function () {
        chip.setAttribute("aria-pressed", chip.getAttribute("aria-pressed") === "true" ? "false" : "true");
        guardarYCargar();
      });
    });

    estadoChips.forEach(function (chip) {
      chip.addEventListener("click", function () {
        var yaActivo = chip.getAttribute("aria-pressed") === "true";
        estadoChips.forEach(function (c) {
          c.setAttribute("aria-pressed", "false");
        });
        chip.setAttribute("aria-pressed", yaActivo ? "false" : "true");
        guardarYCargar();
      });
    });

    if (barrioSelect) {
      // Centro real (promedio de sus reportes) de cada barrio, provisto por el servidor.
      var centros = JSON.parse(barrioSelect.dataset.centros || "{}");
      barrioSelect.addEventListener("change", function () {
        if (barrioSelect.value && centros[barrioSelect.value]) {
          barrioSelect.dataset.centradoManual = "1";
          mapa.setView([centros[barrioSelect.value].lat, centros[barrioSelect.value].lng], 15);
        } else {
          delete barrioSelect.dataset.centradoManual;
        }
        guardarYCargar();
      });
    }

    if (cercaDeMiBtn && navigator.geolocation) {
      cercaDeMiBtn.addEventListener("click", function () {
        cercaDeMiBtn.disabled = true;
        navigator.geolocation.getCurrentPosition(
          function (pos) {
            if (barrioSelect) {
              barrioSelect.value = "";
              barrioSelect.dataset.centradoManual = "1";
            }
            mapa.setView([pos.coords.latitude, pos.coords.longitude], 15);
            L.circleMarker([pos.coords.latitude, pos.coords.longitude], {
              radius: 8,
              color: "#2f6fed",
              fillColor: "#2f6fed",
              fillOpacity: 0.6,
            })
              .bindPopup("Estás acá")
              .addTo(mapa);
            cercaDeMiBtn.disabled = false;
          },
          function () {
            cercaDeMiBtn.disabled = false;
          }
        );
      });
    } else if (cercaDeMiBtn) {
      cercaDeMiBtn.disabled = true;
      cercaDeMiBtn.title = "Tu navegador no permite geolocalización";
    }

    cargar();
  };

  /**
   * Mini mapa "click para marcar ubicación" usado en el formulario de
   * nuevo reporte. Rellena los inputs ocultos de latitud/longitud.
   */
  window.initMapaSelector = function (elementId, latInputId, lngInputId) {
    var el = document.getElementById(elementId);
    var latInput = document.getElementById(latInputId);
    var lngInput = document.getElementById(lngInputId);
    if (!el || !latInput || !lngInput || typeof L === "undefined") {
      return;
    }

    var mapa = L.map(elementId).setView(CENTRO_DEFECTO, ZOOM_DEFECTO);
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      attribution: "&copy; colaboradores de OpenStreetMap",
      maxZoom: 19,
    }).addTo(mapa);

    var marcador = null;

    function ubicar(lat, lng) {
      latInput.value = lat.toFixed(6);
      lngInput.value = lng.toFixed(6);
      if (marcador) {
        marcador.setLatLng([lat, lng]);
      } else {
        marcador = L.marker([lat, lng], { draggable: true }).addTo(mapa);
        marcador.on("dragend", function () {
          var pos = marcador.getLatLng();
          ubicar(pos.lat, pos.lng);
        });
      }
    }

    mapa.on("click", function (evento) {
      ubicar(evento.latlng.lat, evento.latlng.lng);
    });

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function (pos) {
        mapa.setView([pos.coords.latitude, pos.coords.longitude], 15);
      });
    }
  };
})();
