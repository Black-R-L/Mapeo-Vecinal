# Documentación de API - Mapeo Vecinal

## Base URL

```
http://localhost:8080/api
```

---

## Estándares de Respuesta

### Respuesta Exitosa (2xx)

```json
{
  "success": true,
  "message": "Operación exitosa",
  "data": {
    // Objeto o array con datos
  }
}
```

### Respuesta con Error (4xx, 5xx)

```json
{
  "success": false,
  "message": "Descripción del error",
  "errors": {
    // Detalles de errores de validación
  }
}
```

### Códigos HTTP Utilizados

| Código | Significado |
|--------|------------|
| 200 | OK - Solicitud exitosa |
| 201 | Created - Recurso creado |
| 400 | Bad Request - Solicitud inválida |
| 401 | Unauthorized - No autenticado |
| 404 | Not Found - Recurso no encontrado |
| 409 | Conflict - Conflicto (ej: duplicado) |
| 422 | Unprocessable Entity - Validación fallida |
| 500 | Internal Server Error - Error del servidor |

---

## Endpoints: USUARIOS

### GET /usuarios
Listar todos los usuarios activos con paginación

**Parámetros Query**:
- `page` (int, default: 1) - Número de página
- `perPage` (int, default: 10) - Resultados por página
- `rol` (string) - Filtrar por rol: ciudadano, autoridad, admin

**Respuesta**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nombre": "Juan García",
      "email": "juan@ejemplo.com",
      "rol": "ciudadano",
      "barrio": "Centro",
      "estado": "activo",
      "created_at": "2026-06-10 10:30:00"
    }
  ],
  "pagination": {
    "current_page": 1,
    "total_pages": 5,
    "per_page": 10,
    "total": 50
  }
}
```

---

### GET /usuarios/{id}
Obtener usuario específico

**Parámetros**:
- `id` (int, required) - ID del usuario

**Respuesta** (200):
```json
{
  "success": true,
  "data": {
    "id": 1,
    "nombre": "Juan García",
    "email": "juan@ejemplo.com",
    "rol": "ciudadano",
    "barrio": "Centro",
    "estado": "activo",
    "created_at": "2026-06-10 10:30:00"
  }
}
```

---

### POST /usuarios
Crear nuevo usuario

**Body (JSON)**:
```json
{
  "nombre": "Nuevo Usuario",
  "email": "nuevo@ejemplo.com",
  "password": "segura123",
  "rol": "ciudadano",
  "barrio": "Centro",
  "estado": "activo"
}
```

**Respuesta** (201):
```json
{
  "success": true,
  "message": "Usuario creado exitosamente",
  "user_id": 42
}
```

---

### PUT /usuarios/{id}
Actualizar usuario

**Parámetros**:
- `id` (int, required) - ID del usuario

**Body (JSON)**:
```json
{
  "nombre": "Nombre Actualizado",
  "barrio": "Norte",
  "rol": "autoridad"
}
```

**Respuesta** (200):
```json
{
  "success": true,
  "message": "Usuario actualizado exitosamente"
}
```

---

### POST /usuarios/{id}/cambiar-password
Cambiar contraseña

**Parámetros**:
- `id` (int, required) - ID del usuario

**Body (JSON)**:
```json
{
  "password_actual": "antigua123",
  "password_nueva": "nueva456"
}
```

**Respuesta** (200):
```json
{
  "success": true,
  "message": "Contraseña actualizada exitosamente"
}
```

---

### DELETE /usuarios/{id}
Desactivar usuario (soft delete)

**Parámetros**:
- `id` (int, required) - ID del usuario

**Respuesta** (200):
```json
{
  "success": true,
  "message": "Usuario desactivado exitosamente"
}
```

---

### GET /usuarios/barrio/{barrio}
Obtener usuarios por barrio

**Parámetros**:
- `barrio` (string, required) - Nombre del barrio

**Respuesta**:
```json
{
  "success": true,
  "data": [
    { "id": 1, "nombre": "Juan García", "barrio": "Centro" }
  ]
}
```

---

### GET /usuarios/estadisticas
Obtener estadísticas de usuarios

**Respuesta**:
```json
{
  "success": true,
  "data": {
    "total": 50,
    "ciudadanos": 40,
    "autoridades": 8,
    "administradores": 2
  }
}
```

---

## Endpoints: CATEGORÍAS

### GET /categorias
Listar todas las categorías

**Respuesta**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nombre": "Infraestructura",
      "icono": "fa-road",
      "color": "#e74c3c",
      "descripcion": "Problemas con calles, vías y estructuras",
      "created_at": "2026-06-10"
    }
  ]
}
```

---

### GET /categorias/{id}
Obtener categoría específica

**Parámetros**:
- `id` (int, required) - ID de la categoría

---

### POST /categorias
Crear nueva categoría

**Body (JSON)**:
```json
{
  "nombre": "Nueva Categoría",
  "icono": "fa-icon",
  "color": "#3498db",
  "descripcion": "Descripción"
}
```

---

### PUT /categorias/{id}
Actualizar categoría

---

### DELETE /categorias/{id}
Eliminar categoría

---

### GET /categorias/estadisticas/conteo
Obtener categorías con contador de reportes

**Respuesta**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nombre": "Infraestructura",
      "total_reportes": 25
    }
  ]
}
```

---

## Endpoints: REPORTES

### GET /reportes
Listar reportes con paginación

**Parámetros Query**:
- `page` (int, default: 1)
- `perPage` (int, default: 10)
- `estado` (string) - Filtrar: nuevo, en_progreso, resuelto, rechazado

**Respuesta**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "titulo": "Bache en la Calle 5",
      "descripcion": "Gran bache que daña los vehículos",
      "estado": "nuevo",
      "latitud": "4.7110",
      "longitud": "-74.0075",
      "nombre": "Juan García",
      "categoria_nombre": "Infraestructura",
      "votos_totales": 15,
      "created_at": "2026-06-10 10:30:00"
    }
  ]
}
```

---

### GET /reportes/{id}
Obtener reporte específico

---

### POST /reportes
Crear nuevo reporte

**Body (JSON)**:
```json
{
  "titulo": "Iluminación dañada",
  "descripcion": "Poste de luz del parque no funciona",
  "latitud": "4.7110",
  "longitud": "-74.0075",
  "user_id": 1,
  "categoria_id": 3,
  "foto": "uploads/foto.jpg"
}
```

---

### PUT /reportes/{id}
Actualizar reporte

---

### PATCH /reportes/{id}/estado
Cambiar estado del reporte

**Body (JSON)**:
```json
{
  "estado": "en_progreso"
}
```

---

### DELETE /reportes/{id}
Eliminar reporte (soft delete)

---

### GET /reportes/categoria/{categoriaId}
Obtener reportes por categoría

---

### GET /reportes/usuario/{userId}
Obtener reportes de un usuario

---

### POST /reportes/filtro
Obtener reportes con filtros

**Body (JSON)**:
```json
{
  "estado": "nuevo",
  "categoria_id": 1,
  "barrio": "Centro"
}
```

---

### GET /reportes/populares/{limit}
Obtener reportes más votados

**Parámetros**:
- `limit` (int, default: 5) - Cantidad de reportes

---

### GET /reportes/estadisticas
Obtener estadísticas de reportes

**Respuesta**:
```json
{
  "success": true,
  "data": {
    "total": 150,
    "nuevo": 45,
    "en_progreso": 60,
    "resuelto": 45
  }
}
```

---

## Endpoints: PROPUESTAS

### GET /propuestas
Listar propuestas

**Parámetros Query**:
- `page` (int)
- `perPage` (int)
- `estado` (string) - propuesta, en_votacion, aprobada, rechazada

---

### GET /propuestas/{id}
Obtener propuesta específica

---

### POST /propuestas
Crear propuesta

**Body (JSON)**:
```json
{
  "titulo": "Ciclovía para la Avenida Central",
  "descripcion": "Construcción de carril exclusivo para bicicletas",
  "user_id": 1,
  "categoria_id": 6
}
```

---

### PUT /propuestas/{id}
Actualizar propuesta

---

### PATCH /propuestas/{id}/estado
Cambiar estado

---

### PATCH /propuestas/{id}/activar-votacion
Activar votación en propuesta

---

### DELETE /propuestas/{id}
Eliminar propuesta

---

### GET /propuestas/votacion
Obtener propuestas en votación

---

### GET /propuestas/populares/{limit}
Obtener propuestas más votadas

---

### GET /propuestas/usuario/{userId}
Obtener propuestas de usuario

---

### GET /propuestas/estadisticas
Obtener estadísticas

```json
{
  "success": true,
  "data": {
    "total": 80,
    "en_votacion": 20,
    "aprobada": 30,
    "rechazada": 30
  }
}
```

---

## Endpoints: VOTACIONES

### GET /votaciones/propuesta/{propuestaId}
Obtener votantes de propuesta

**Respuesta**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nombre": "Juan García",
      "email": "juan@ejemplo.com",
      "tipo_voto": "favor",
      "fecha": "2026-06-10 14:30:00"
    }
  ]
}
```

---

### POST /votaciones
Crear voto

**Body (JSON)**:
```json
{
  "user_id": 1,
  "propuesta_id": 5,
  "tipo_voto": "favor"
}
```

**Respuesta** (201):
```json
{
  "success": true,
  "message": "Voto registrado exitosamente"
}
```

**Error si ya votó** (409):
```json
{
  "success": false,
  "message": "El usuario ya votó en esta propuesta"
}
```

---

### PUT /votaciones/{userId}/{propuestaId}
Cambiar voto

**Body (JSON)**:
```json
{
  "tipo_voto": "contra"
}
```

---

### DELETE /votaciones/{userId}/{propuestaId}
Eliminar voto

---

### GET /votaciones/resumen/{propuestaId}
Obtener resumen de votación

**Respuesta**:
```json
{
  "success": true,
  "data": {
    "favor": 25,
    "contra": 10,
    "total": 35,
    "porcentaje_favor": 71.43
  }
}
```

---

### GET /votaciones/usuario/{userId}
Obtener votos del usuario

---

### GET /votaciones/verificar/{userId}/{propuestaId}
Verificar si usuario votó

**Respuesta**:
```json
{
  "success": true,
  "ya_voto": true,
  "voto": {
    "id": 1,
    "tipo_voto": "favor"
  }
}
```

---

## Códigos de Error Comunes

| Código | Mensaje | Causa |
|--------|---------|-------|
| 400 | ID requerido | Parámetro obligatorio no enviado |
| 401 | Usuario no autenticado | Sesión expirada o no iniciada |
| 404 | Recurso no encontrado | El ID no existe |
| 409 | El usuario ya votó | Intento de votar dos veces |
| 422 | Validación fallida | Datos inválidos |
| 500 | Error del servidor | Fallo interno |

---

## Rate Limiting

Se recomienda implementar rate limiting:

```bash
# Máximo 100 requests por minuto por IP
```

---

## CORS

Configurar CORS según sea necesario para frontend:

```php
// app/Config/Cors.php
```

---

**Última actualización**: 2026-06-10
