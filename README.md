# 🗺️ Mapeo Vecinal

**Una plataforma cívica para reportar problemas, proponer mejoras y votar soluciones en tu barrio.**

## 📋 Descripción general

Mapeo Vecinal empodera a los vecinos para:

- 📍 **Reportar problemas** (baches, iluminación dañada, basurales) marcando la ubicación en un mapa interactivo (Leaflet + OpenStreetMap)
- 💡 **Proponer mejoras** (parques, ciclovías, señalización)
- 🗳️ **Votar propuestas** a favor o en contra para priorizar soluciones
- 🗺️ **Ver el mapa público** con todos los reportes activos del barrio, filtrable por categoría y estado

Las autoridades y administradores acceden a un **panel administrativo** (`/panel`) con CRUD completo (crear, editar, eliminar) sobre usuarios, categorías, reportes, propuestas y votaciones. Además existe una **API REST en JSON** bajo `/api` para integraciones externas.

---

## 🚀 Puesta en marcha (XAMPP / local)

```bash
composer install
copy .env.example .env
```

Editá `.env` con tus credenciales de MySQL si difieren de las de por defecto (`root` sin contraseña), luego:

```bash
mysql -u root -e "CREATE DATABASE mapeo_vecinal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php spark migrate
php spark db:seed DatabaseSeeder
php spark serve
```

Abrí **http://localhost:8080**. Si preferís Apache de XAMPP: **http://localhost/mapeo-vecinal/public**.

`db:seed DatabaseSeeder` siembra, en el orden correcto, usuarios de prueba, categorías, y reportes/propuestas/votos de ejemplo para que el mapa y las propuestas no arranquen vacíos.

### Usuarios de demo (ver `UsuarioSeeder`)

| Email | Contraseña | Rol |
|---|---|---|
| admin@mapeo-vecinal.local | admin123 | admin |
| autoridad1@municipio.local | autoridad123 | autoridad |
| carlos@ejemplo.com | ciudadano123 | ciudadano |

---

## 🏗️ Stack técnico

| Componente | Tecnología |
|---|---|
| Framework | CodeIgniter 4.6+ |
| Base de datos | MySQL 8 (utf8mb4) |
| Vistas | Plantillas nativas de CodeIgniter (`extend`/`section`), sin motor de terceros |
| Frontend | Bootstrap 5 (CDN) + sistema de diseño propio (`public/assets/css/app.css`) |
| Mapas | Leaflet.js + tiles de OpenStreetMap |
| Iconos | Font Awesome 6 (CDN) |
| Auth | Sesión propia sobre `UsuarioModel` (bcrypt), sin dependencias externas |

---

## 🔗 Mapa de rutas

| Área | Rutas | Acceso |
|---|---|---|
| Sitio público | `/`, `/reportes`, `/reportes/{id}`, `/propuestas`, `/propuestas/{id}` | Público |
| Sitio (autenticado) | `/reportes/nuevo`, `/propuestas/nueva`, `POST /propuestas/{id}/votar` | Cualquier usuario logueado |
| Auth | `/login`, `/registro`, `/logout` | Público |
| Panel | `/panel`, `/panel/categorias`, `/panel/reportes`, `/panel/propuestas`, `/panel/votaciones` | `autoridad` o `admin` |
| Panel (usuarios) | `/panel/usuarios` | solo `admin` |
| API REST (JSON) | `/api/usuarios`, `/api/categorias`, `/api/reportes`, `/api/propuestas`, `/api/votaciones` | Público (sin filtro de auth todavía) |

Ver [docs/API.md](docs/API.md) para el detalle de cada endpoint.

---

## 📁 Estructura del proyecto

```
app/
├── Controllers/
│   ├── AuthController.php          # login / registro / logout
│   ├── SitioController.php         # mapa público, reportes, propuestas, votar
│   ├── BaseApiController.php       # helpers ok()/fail()/attempt() para la API
│   ├── UsuariosController.php      # API REST (extiende BaseApiController)
│   ├── CategoriasController.php    # API REST
│   ├── ReportesController.php      # API REST
│   ├── PropuestasController.php    # API REST
│   ├── VotacionesController.php    # API REST
│   └── Panel/                      # controladores del panel admin (CRUD completo)
│       ├── BasePanelController.php
│       ├── DashboardPanelController.php
│       ├── UsuariosPanelController.php
│       ├── CategoriasPanelController.php
│       ├── ReportesPanelController.php
│       ├── PropuestasPanelController.php
│       └── VotacionesPanelController.php
├── Models/                         # UsuarioModel, CategoriaModel, ReporteModel, PropuestaModel, VotacionModel
├── Filters/AuthFilter.php          # sesión + roles opcionales (auth:admin,autoridad)
├── Views/
│   ├── layouts/public.php          # topbar + hero + footer (sitio ciudadano)
│   ├── layouts/panel.php           # sidebar (panel admin)
│   ├── sitio/                      # home con mapa, listados, formularios
│   ├── auth/                       # login, registro
│   └── panel/                      # dashboard + una carpeta index/editar por entidad
├── Database/Migrations/            # 5 migraciones (usuarios, categorias, reportes, propuestas, votaciones)
└── Database/Seeds/                 # UsuarioSeeder, CategoriaSeeder, ReporteSeeder, PropuestaSeeder,
                                     # VotacionSeeder y DatabaseSeeder (orquestador)

public/assets/
├── css/app.css                     # sistema de diseño (tokens de color, componentes)
└── js/{app.js,mapa.js}             # confirmaciones, sidebar mobile, inicialización de Leaflet
```

---

## 🔐 Roles

| Rol | Puede |
|---|---|
| **ciudadano** | Crear reportes/propuestas, votar, ver el mapa y los listados públicos |
| **autoridad** | Todo lo anterior + panel de categorías/reportes/propuestas/votaciones |
| **admin** | Todo lo anterior + gestión de usuarios (`/panel/usuarios`) |

---

## 🧪 Testing

```bash
vendor\bin\phpunit
```

---

## 📚 Documentación

- [docs/DATABASE.md](docs/DATABASE.md) — Diseño de la base de datos
- [docs/API.md](docs/API.md) — Endpoints de la API REST
- [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md) — Decisiones de arquitectura

---

**Mapeo Vecinal** — construido con la comunidad, para la comunidad.
