# 🗺️ Mapeo Vecinal

**Una plataforma cívica para reportar problemas, proponer mejoras y votar soluciones en tu barrio.**

> 👉 **¿PRIMERA VEZ?** Lee primero: **[START_HERE.md](./docs/START_HERE.md)** ← Índice de navegación

## 📋 Descripción General

Mapeo Vecinal es una aplicación web que empodera a los ciudadanos permitiéndoles:
- 📍 **Reportar problemas** (baches, iluminación dañada, basura ilegal)
- 💡 **Proponer mejoras** (parques nuevos, ciclovías, señalización)
- 🗳️ **Votar propuestas** para priorizar soluciones
- 📊 **Ver dashboards** con las prioridades reales de la comunidad

Las autoridades locales pueden acceder a un panel administrativo con datos en tiempo real sobre las necesidades de su municipio.

---

## 🚀 Inicio Rápido (XAMPP)

### Instalación Manual

Para instrucciones detalladas ver: **[docs/SETUP_LOCAL.md](./docs/SETUP_LOCAL.md)**

```bash
# Pasos básicos:
1. composer install
2. copy .env.example .env
3. Editar .env con credenciales de BD
4. mysql -u root -e "CREATE DATABASE mapeo_vecinal;"
5. php spark migrate
6. php spark db:seed
7. php spark serve
```

Accede a: **http://localhost:8080**

---

## 👀 Ver la página ya

Desde la raíz del proyecto:

```bash
composer install
copy .env.example .env
php spark serve
```

Luego abre:
- **http://localhost:8080**

Si usas Apache de XAMPP, abre:
- **http://localhost/mapeo-vecinal/public**

---

## 🏗️ Stack Técnico

| Componente | Tecnología | Versión |
|-----------|-----------|---------|
| Framework | CodeIgniter 4 | 4.6+ |
| Base de Datos | MySQL | 8.0+ |
| Plantillas | Smarty | 4.0+ |
| Frontend | HTML5 + CSS3 | ES6+ |
| Mapas | Leaflet.js | 1.9+ |
| Validación | JavaScript + PHP | Nativa |

---

## 📁 Estructura del Proyecto

```
mapeo-vecinal/
├── app/
│   ├── Controllers/               # Controladores CRUD
│   │   ├── AuthController.php     # Autenticación
│   │   ├── UsuariosController.php # CRUD de Usuarios
│   │   ├── CategoriasController.php
│   │   ├── ReportesController.php
│   │   ├── PropuestasController.php
│   │   └── VotacionesController.php
│   │
│   ├── Models/                    # Modelos + Query Builder
│   │   ├── UsuarioModel.php
│   │   ├── CategoriaModel.php
│   │   ├── ReporteModel.php
│   │   ├── PropuestaModel.php
│   │   └── VotacionModel.php
│   │
│   ├── Views/                     # Plantillas Smarty
│   │   ├── layouts/               # Layouts reutilizables
│   │   ├── usuarios/              # Vistas de Usuarios
│   │   ├── reportes/              # Vistas de Reportes
│   │   ├── propuestas/            # Vistas de Propuestas
│   │   └── dashboard/             # Dashboard administrativo
│   │
│   ├── Database/
│   │   ├── Migrations/            # Versionado de BD
│   │   │   ├── 2026-06-10-000001_CreateUsuarios.php
│   │   │   ├── 2026-06-10-000002_CreateCategorias.php
│   │   │   ├── 2026-06-10-000003_CreateReportes.php
│   │   │   ├── 2026-06-10-000004_CreatePropuestas.php
│   │   │   └── 2026-06-10-000005_CreateVotaciones.php
│   │   └── Seeds/                 # Data de prueba
│   │       ├── UsuarioSeeder.php
│   │       └── CategoriaSeeder.php
│   │
│   ├── Filters/                   # Middleware de autenticación
│   │   └── AuthFilter.php
│   │
│   └── Config/
│       ├── Routes.php             # Rutas personalizadas
│       ├── Database.php           # Conexión a BD
│       └── App.php
│
├── public/
│   ├── index.php                  # Punto de entrada
│   └── assets/
│       ├── css/
│       │   ├── bootstrap.min.css
│       │   ├── leaflet.css
│       │   └── main.css
│       ├── js/
│       │   ├── leaflet.min.js
│       │   ├── api-client.js
│       │   └── validators.js
│       └── images/
│           ├── logo.png
│           └── icons/
│
├── docs/
│   ├── DATABASE.md                # Diseño de BD
│   ├── API.md                     # Documentación API
│   ├── INSTALL.md                 # Guía de instalación
│   └── ARCHITECTURE.md            # Arquitectura del sistema
│
├── .env                           # Variables de entorno
├── .env.example                   # Template de .env
├── .gitignore
├── composer.json
├── spark                          # CLI de CodeIgniter
└── README.md
```

---

## 🚀 Instalación y Configuración

### Requisitos Previos
- PHP 8.1+
- Composer
- MySQL 8.0+
- Git

### Pasos de Instalación

#### 1. Clonar o descargar el proyecto
```bash
git clone https://github.com/tuusuario/mapeo-vecinal.git
cd mapeo-vecinal
```

#### 2. Instalar dependencias
```bash
composer install
```

#### 3. Configurar variables de entorno
```bash
cp .env.example .env
```

Editar `.env` con tus credenciales:
```ini
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'

database.default.hostname = localhost
database.default.database = mapeo_vecinal
database.default.username = root
database.default.password = tu_contraseña
database.default.DBDriver = MySQLi
```

#### 4. Crear la base de datos
```bash
mysql -u root -p
CREATE DATABASE mapeo_vecinal;
EXIT;
```

#### 5. Ejecutar migraciones
```bash
php spark migrate
```

#### 6. Ejecutar seeders (datos de prueba)
```bash
php spark db:seed UsuarioSeeder
php spark db:seed CategoriaSeeder
```

#### 7. Iniciar servidor de desarrollo
```bash
php spark serve
```

Acceder a: `http://localhost:8080`

---

## 📊 Diagrama de Entidades

```
┌──────────────────────────────────────────────────────┐
│                    USUARIOS                          │
│  (id, nombre, email, password, rol, barrio, estado) │
└──────────────────────────────────────────────────────┘
           │                            │
           ├─────────────────┬──────────┘
           │                 │
           ▼                 ▼
┌───────────────────────┐  ┌──────────────────────────┐
│      REPORTES         │  │      PROPUESTAS          │
│ (id, titulo,         │  │ (id, titulo,             │
│  descripcion,        │  │  descripcion,            │
│  estado, lat, lng,   │  │  estado, user_id,        │
│  foto,user_id,       │  │  cat_id)                 │
│  cat_id)             │  │                          │
└───────────────────────┘  └──────────────────────────┘
           │                          │
           └──────────┬───────────────┘
                      │
           ┌──────────▼──────────────┐
           │      VOTACIONES        │
           │ (id, user_id,          │
           │  propuesta_id, fecha)  │
           └────────────────────────┘

           ┌──────────────────────────┐
           │      CATEGORIAS          │
           │ (id, nombre, icono,     │
           │  color)                 │
           └──────────────────────────┘
```

---

## 🔐 Sistema de Roles y Permisos

| Rol | Acciones Permitidas |
|-----|-------------------|
| **Ciudadano** | Crear reportes/propuestas, votar, ver dashboard público |
| **Autoridad** | Ver dashboard administrativo, responder reportes, cerrar propuestas |
| **Admin** | Gestión total: usuarios, categorías, moderar contenido |

---

## 🗄️ Tablas de la Base de Datos

### usuarios
```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('ciudadano', 'autoridad', 'admin') DEFAULT 'ciudadano',
    barrio VARCHAR(100) NOT NULL,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);
```

### categorias
```sql
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    icono VARCHAR(50) DEFAULT 'folder',
    color VARCHAR(7) DEFAULT '#3498db',
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### reportes
```sql
CREATE TABLE reportes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT NOT NULL,
    estado ENUM('nuevo', 'en_progreso', 'resuelto', 'rechazado') DEFAULT 'nuevo',
    latitud DECIMAL(10, 8),
    longitud DECIMAL(11, 8),
    foto VARCHAR(255),
    user_id INT NOT NULL,
    categoria_id INT NOT NULL,
    votos_totales INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES usuarios(id),
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);
```

### propuestas
```sql
CREATE TABLE propuestas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT NOT NULL,
    estado ENUM('propuesta', 'en_votacion', 'aprobada', 'rechazada') DEFAULT 'propuesta',
    user_id INT NOT NULL,
    categoria_id INT NOT NULL,
    votos_totales INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES usuarios(id),
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);
```

### votaciones
```sql
CREATE TABLE votaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    propuesta_id INT NOT NULL,
    tipo_voto ENUM('favor', 'contra') DEFAULT 'favor',
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_voto (user_id, propuesta_id),
    FOREIGN KEY (user_id) REFERENCES usuarios(id),
    FOREIGN KEY (propuesta_id) REFERENCES propuestas(id)
);
```

---

## 🔗 Rutas Principales

| Método | Ruta | Controlador | Descripción |
|--------|------|-------------|-------------|
| GET | `/` | DashboardController | Dashboard público |
| POST | `/auth/login` | AuthController | Iniciar sesión |
| GET | `/auth/logout` | AuthController | Cerrar sesión |
| GET/POST | `/usuarios` | UsuariosController | CRUD de usuarios |
| GET/POST | `/reportes` | ReportesController | CRUD de reportes |
| GET/POST | `/propuestas` | PropuestasController | CRUD de propuestas |
| POST | `/votaciones/crear` | VotacionesController | Crear voto |

---

## ✅ Checklist de Características

### MVP (Fase 1)
- [x] Autenticación de usuarios (registro/login)
- [x] CRUD de Usuarios (admin)
- [x] CRUD de Categorías (admin)
- [x] CRUD de Reportes (ciudadanos)
- [x] CRUD de Propuestas (ciudadanos)
- [x] Sistema de votaciones
- [x] Dashboard administrativo básico
- [x] Validaciones en cliente y servidor

### Fase 2 (Mejoras)
- [ ] Mapa interactivo con Leaflet.js
- [ ] Sistema de notificaciones por email
- [ ] Exportar reportes a PDF
- [ ] API REST para aplicación móvil
- [ ] Sistema de comentarios en reportes

### Fase 3 (Escalabilidad)
- [ ] Caché con Redis
- [ ] Búsqueda avanzada con Elasticsearch
- [ ] Sistema de modelos de IA para clasificación automática
- [ ] Integración con redes sociales

---

## 🧪 Testing

### Ejecutar pruebas unitarias
```bash
vendor\bin\phpunit
```

### Coverage de código
```bash
vendor\bin\phpunit --coverage-text
```

---

## 📚 Documentación

Consulta los siguientes documentos para más detalles:

- [DATABASE.md](docs/DATABASE.md) - Diseño detallado de la base de datos
- [API.md](docs/API.md) - Endpoints y parámetros
- [INSTALL.md](docs/INSTALL.md) - Guía completa de instalación
- [ARCHITECTURE.md](docs/ARCHITECTURE.md) - Decisiones arquitectónicas

---

## 🤝 Contribuir

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

---

## 📝 Licencia

Este proyecto está bajo la Licencia MIT. Ver [LICENSE](LICENSE) para más detalles.

---

## 📧 Contacto

Para preguntas o sugerencias, abre un issue en el repositorio o contacta a:
- **Email**: info@mapeo-vecinal.com
- **Web**: www.mapeo-vecinal.com

---

**Mapeo Vecinal** - Construido con ❤️ por la comunidad, para la comunidad.
