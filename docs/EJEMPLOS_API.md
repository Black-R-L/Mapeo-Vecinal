# Mapeo Vecinal - Ejemplo de Uso de la API

## 1. Crear Usuario

```bash
curl -X POST http://localhost:8080/usuarios \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Juan García",
    "email": "juan@ejemplo.com",
    "password": "segura123",
    "rol": "ciudadano",
    "barrio": "Centro",
    "estado": "activo"
  }'
```

## 2. Obtener Categorías

```bash
curl http://localhost:8080/categorias
```

## 3. Crear Reporte

```bash
curl -X POST http://localhost:8080/reportes \
  -H "Content-Type: application/json" \
  -d '{
    "titulo": "Bache en la Calle 5",
    "descripcion": "Gran bache que daña los vehículos",
    "latitud": "4.7110",
    "longitud": "-74.0075",
    "user_id": 1,
    "categoria_id": 1,
    "foto": null
  }'
```

## 4. Listar Reportes

```bash
curl "http://localhost:8080/reportes?page=1&perPage=10&estado=nuevo"
```

## 5. Crear Propuesta

```bash
curl -X POST http://localhost:8080/propuestas \
  -H "Content-Type: application/json" \
  -d '{
    "titulo": "Ciclovía para la Avenida Central",
    "descripcion": "Construcción de carril exclusivo para bicicletas",
    "user_id": 1,
    "categoria_id": 6
  }'
```

## 6. Crear Voto

```bash
curl -X POST http://localhost:8080/votaciones \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 1,
    "propuesta_id": 1,
    "tipo_voto": "favor"
  }'
```

## 7. Obtener Resumen de Votación

```bash
curl http://localhost:8080/votaciones/resumen/1
```

## 8. Cambiar Estado de Reporte

```bash
curl -X PATCH http://localhost:8080/reportes/1/estado \
  -H "Content-Type: application/json" \
  -d '{
    "estado": "en_progreso"
  }'
```

## 9. Obtener Estadísticas

```bash
curl http://localhost:8080/usuarios/estadisticas
curl http://localhost:8080/reportes/estadisticas
curl http://localhost:8080/propuestas/estadisticas
```

## 10. Verificar Voto

```bash
curl http://localhost:8080/votaciones/verificar/1/1
```
