# Arquitectura del Sistema - Mapeo Vecinal

## Descripción General

Mapeo Vecinal sigue el patrón **MVC (Model-View-Controller)** de CodeIgniter 4 con una arquitectura modular, escalable y profesional.

---

## 1. Capas de Arquitectura

```
┌─────────────────────────────────────────┐
│        PRESENTACIÓN (Frontend)           │
│  HTML5 | CSS3 | JavaScript | Leaflet    │
└────────────────┬────────────────────────┘
                 │
        ┌────────▼─────────┐
        │  REST API (JSON)  │
        │   JSON Responses  │
        └────────┬──────────┘
                 │
┌────────────────▼──────────────────────┐
│      LÓGICA DE NEGOCIO (Backend)       │
│  Controllers | Filters | Validators    │
└────────────────┬──────────────────────┘
                 │
┌────────────────▼──────────────────────┐
│    MODELO DE DATOS (Business Logic)    │
│  Models | Query Builder | Validations  │
└────────────────┬──────────────────────┘
                 │
┌────────────────▼──────────────────────┐
│   PERSISTENCIA (Database)              │
│  MySQL 8.0 | Migrations | Seeders     │
└────────────────────────────────────────┘
```

---

## 2. Componentes Principales

### 2.1 Models (Capa de Datos)

Ubicación: `/app/Models/`

```
UsuarioModel
├── Métodos CRUD básicos
├── getByEmail() - Búsqueda de usuario
├── validarCredenciales() - Autenticación
├── cambiarPassword() - Cambio de contraseña
└── getByRol() - Filtrar por rol

CategoriaModel
├── getAllCategorias() - Listar todas
├── getByNombre() - Búsqueda
└── getCategoriasConConteo() - CON JOIN

ReporteModel
├── getReportesConDetalles() - Join usuarios + categorías
├── getReportesPorCategoria() - Filtro
├── getMasVotados() - Ordenamiento
├── getEstadisticas() - Agregación
└── cambiarEstado() - Update

PropuestaModel
├── getPropuestasConDetalles() - Información completa
├── getEnVotacion() - Votación activa
├── getMasVotadas() - Top N
├── activarVotacion() - Cambio de estado
└── getEstadisticas() - Conteos

VotacionModel
├── yaVoto() - Verificar voto único
├── cambiarVoto() - Update voto
├── getResumenVotacion() - Agregación de votos
├── contarVotosAfavor() - Count
└── contarVotosEnContra() - Count
```

**Características**:
- ✅ Validación integrada
- ✅ Query Builder de CodeIgniter
- ✅ Timestamps automáticos
- ✅ Soft Deletes
- ✅ Relaciones mediante Joins

### 2.2 Controllers (Lógica de Aplicación)

Ubicación: `/app/Controllers/`

```
UsuariosController
├── index() - GET /usuarios
├── obtener() - GET /usuarios/{id}
├── crear() - POST /usuarios
├── actualizar() - PUT /usuarios/{id}
├── cambiarPassword() - POST /usuarios/{id}/cambiar-password
├── eliminar() - DELETE /usuarios/{id}
├── porBarrio() - GET /usuarios/barrio/{barrio}
└── estadisticas() - GET /usuarios/estadisticas

CategoriasController
├── index() - Listar
├── obtener() - Obtener uno
├── crear() - POST
├── actualizar() - PUT
├── eliminar() - DELETE
└── conConteo() - Categorías con estadísticas

ReportesController
├── index() - Listar con paginación
├── obtener() - Obtener uno
├── crear() - POST
├── actualizar() - PUT
├── cambiarEstado() - PATCH
├── eliminar() - DELETE
├── porCategoria() - Filtro
├── porUsuario() - Filtro
├── filtro() - Múltiples filtros
├── populares() - Top N
└── estadisticas() - Agregación

PropuestasController
├── index() - Listar
├── obtener() - Obtener
├── crear() - POST
├── actualizar() - PUT
├── cambiarEstado() - PATCH
├── activarVotacion() - PATCH
├── eliminar() - DELETE
├── enVotacion() - Filtro estado
├── populares() - Top N
├── porUsuario() - Filtro usuario
└── estadisticas() - Conteos

VotacionesController
├── porPropuesta() - GET /votaciones/propuesta/{id}
├── crear() - POST (con validación de voto único)
├── cambiarVoto() - PUT
├── eliminar() - DELETE
├── resumen() - Estadísticas de votación
├── porUsuario() - Votos del usuario
└── verificar() - Check de voto existente
```

**Características**:
- ✅ Respuestas JSON consistentes
- ✅ Manejo de errores con códigos HTTP
- ✅ Validación de entrada
- ✅ Autorización (mediante filtros)
- ✅ Paginación

### 2.3 Filters (Middleware)

Ubicación: `/app/Filters/`

```
AuthFilter
├── Verifica usuario autenticado
├── Maneja AJAX y HTTP requests
├── Redirige o devuelve JSON 401
└── Adjunta usuario a $request
```

**Uso**:
```php
// En Routes.php
$routes->group('api', ['filter' => 'auth'], function($routes) {
    // Rutas protegidas
});
```

### 2.4 Views (Presentación)

Ubicación: `/app/Views/`

**Estructura propuesta**:
```
views/
├── layouts/
│   ├── base.php
│   ├── admin.php
│   └── public.php
├── usuarios/
│   ├── index.php
│   ├── crear.php
│   ├── editar.php
│   └── show.php
├── reportes/
│   ├── index.php
│   ├── crear.php
│   ├── mapa.php
│   └── detalle.php
├── propuestas/
│   ├── index.php
│   ├── crear.php
│   ├── votacion.php
│   └── estadisticas.php
└── dashboard/
    ├── admin.php
    ├── autoridad.php
    └── ciudadano.php
```

**Nota**: Usa Smarty para templates si se requiere separación estricta

### 2.5 Database (Persistencia)

Ubicación: `/app/Database/`

```
Migrations/
├── 2026-06-10-000001_CreateUsuarios.php
├── 2026-06-10-000002_CreateCategorias.php
├── 2026-06-10-000003_CreateReportes.php
├── 2026-06-10-000004_CreatePropuestas.php
└── 2026-06-10-000005_CreateVotaciones.php

Seeds/
├── UsuarioSeeder.php
└── CategoriaSeeder.php
```

---

## 3. Flujos de Datos

### 3.1 Crear Reporte

```
USUARIO (Frontend)
    │ POST /reportes
    ▼
UsuariosController::crear()
    │ Valida datos
    ▼
ReporteModel::insert($data)
    │ Guarda en BD
    ▼
Response: 201 { success: true }
    │
    ▼
USUARIO (Frontend)
```

### 3.2 Obtener Reportes + Información

```
ReportesController::index()
    │
    ├─ Obtiene query params (page, perPage, estado)
    │
    ▼
ReporteModel::getReportesConDetalles()
    │ Query Builder con JOINs
    │ SELECT r.*, u.nombre, c.nombre
    │ FROM reportes r
    │ JOIN usuarios u ON...
    │ JOIN categorias c ON...
    │
    ▼
Response: 200 { data: [...] }
```

### 3.3 Sistema de Votación

```
USUARIO intenta votar
    │
    ▼
VotacionesController::crear()
    │
    ├─ Verifica yaVoto($userId, $propuestaId)
    │
    ├─ Si ya votó → Error 409
    │
    └─ Si no votó:
        │
        ├─ VotacionModel::insert($data)
        │
        ├─ PropuestaModel::incrementarVotos($id)
        │
        └─ Response: 201 { success: true }
```

---

## 4. Seguridad

### 4.1 Autenticación
- Contraseñas con **bcrypt** (password_hash)
- Sesiones con timeout
- Filtro de autenticación en rutas

### 4.2 Validación
- **Cliente**: JavaScript
- **Servidor**: CodeIgniter Rules
- Sanitización de entrada

### 4.3 Autorización
- Roles: ciudadano, autoridad, admin
- Filtros por rol en controladores
- Soft deletes para historial

### 4.4 SQL Injection Prevention
- Query Builder de CI4 (prepared statements)
- Placeholders en lugar de concatenación

---

## 5. Patrones de Diseño Utilizados

### 5.1 MVC (Model-View-Controller)
Separación clara de responsabilidades

### 5.2 Repository Pattern (Implícito)
Models actúan como repositorios de datos

### 5.3 Dependency Injection
Automático en CodeIgniter 4

### 5.4 Service Locator
Para acceder a modelos desde controladores

### 5.5 Query Builder Pattern
Para construcción dinámica de queries

---

## 6. Escalabilidad

### Consideraciones para Escalar

1. **Caché**
   ```php
   // Cachear categorías (cambian raramente)
   $cache->save('categorias', $categorias, 3600);
   ```

2. **Búsqueda Avanzada**
   ```php
   // Integrar Elasticsearch para reportes
   // SELECT * FROM reportes WHERE MATCH(titulo, descripcion)
   ```

3. **Notificaciones Asincrónicas**
   ```php
   // Queue de trabajos (RabbitMQ, Redis)
   // Enviar emails en background
   ```

4. **API Versionado**
   ```php
   // /api/v1/reportes
   // /api/v2/reportes (cambios futuros)
   ```

5. **Load Balancing**
   ```
   Nginx (Load Balancer)
   ├─ App Server 1
   ├─ App Server 2
   └─ App Server 3
   
   MySQL (Master-Slave Replication)
   ```

---

## 7. Configuración de Rutas

### Archivo: `app/Config/Routes.php`

Rutas reales, en tres bloques: API bajo `/api`, sitio público (con un subgrupo que exige sesión para las acciones de escritura), y panel bajo `/panel` con el filtro `auth:admin,autoridad` (y `auth:admin` para el subgrupo de usuarios):

```php
$routes->group('api', function ($routes) {
    $routes->get('usuarios', 'UsuariosController::index');
    $routes->post('usuarios', 'UsuariosController::crear');
    // ...resto del CRUD de usuarios/categorias/reportes/propuestas/votaciones
});

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::procesarLogin', ['filter' => 'csrf']);
$routes->get('/', 'SitioController::index');

$routes->post('reportes/nuevo', 'SitioController::crearReporte', ['filter' => ['auth', 'csrf']]);
$routes->post('propuestas/(:num)/votar', 'SitioController::votar/$1', ['filter' => ['auth', 'csrf']]);

$routes->group('panel', ['filter' => 'auth:admin,autoridad'], static function ($routes) {
    $routes->group('usuarios', ['filter' => 'auth:admin'], static function ($routes) {
        // CRUD de usuarios, solo admin
    });
    // categorias/reportes/propuestas/votaciones: admin o autoridad
});
```

`AuthFilter::before()` acepta los roles permitidos como argumentos del filtro (`auth:admin,autoridad`); sin argumentos solo exige sesión iniciada.

---

## 8. Estándares de Código

### Nomenclatura
- **Clases**: PascalCase (`UsuarioModel`)
- **Métodos**: camelCase (`getByEmail()`)
- **Variables**: snake_case (`$usuario_id`)
- **Constantes**: SCREAMING_SNAKE_CASE (`DB_HOST`)

### Documentación
```php
/**
 * Obtener usuario por email
 * 
 * @param string $email Email del usuario
 * @return array|null Usuario o null
 */
public function getByEmail(string $email)
{
    // Implementación
}
```

### Formato de Respuesta
```php
return $this->response->setJSON([
    'success' => true/false,
    'message' => 'Descripción',
    'data' => $data,
    'errors' => $errors,
])->setStatusCode($code);
```

---

## 9. Stack Técnico Completo

```
Frontend                Backend              Database
├─ HTML5                ├─ CodeIgniter 4     ├─ MySQL 8.0
├─ CSS3                 ├─ PHP 8.1+          ├─ Migrations
├─ JavaScript (ES6+)    ├─ Composer          ├─ Query Builder
├─ Leaflet.js (Mapas)   ├─ Validation        ├─ Soft Deletes
└─ Responsive Design    ├─ Authentication    └─ Indexes
                        ├─ Middleware        
                        └─ RESTful API
```

---

## 10. Deployment

### Opciones de Hosting

1. **Shared Hosting** (cPanel)
   - Upload via FTP
   - MySQL incluido

2. **VPS** (Linode, DigitalOcean)
   - Full control
   - Mejor performance

3. **Docker** (Contenedores)
   ```dockerfile
   FROM php:8.1-fpm
   RUN composer install
   EXPOSE 8080
   ```

4. **Cloud** (AWS, Google Cloud, Azure)
   - Escalabilidad automática
   - Managed databases

---

## 11. Monitoreo y Logs

### Archivos de Log
```
writable/logs/
├── log-2026-06-10.log
├── log-2026-06-11.log
└── ...
```

### Niveles de Log
- **1**: Emergency
- **2**: Alert
- **3**: Critical
- **4**: Error
- **5**: Warning
- **6**: Notice
- **7**: Info
- **8**: Debug

---

**Última actualización**: 2026-06-10
