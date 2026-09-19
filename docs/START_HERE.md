# 📖 ÍNDICE - Mapeo Vecinal

> Bienvenido al proyecto. Aquí encontrarás todo lo que necesitas.

---

## 🚀 PRIMEROS PASOS

1. Sigue la guía rápida: **[QUICK_START.md](./QUICK_START.md)**
2. Si necesitas más detalle: **[SETUP_LOCAL.md](./SETUP_LOCAL.md)**
3. Para referencia general: **[INSTALL.md](./INSTALL.md)**

---

## 📚 DOCUMENTACIÓN POR TEMA

### 🔧 Configuración e Instalación
- **[QUICK_START.md](./QUICK_START.md)** - Inicio en 5 minutos
- **[docs/SETUP_LOCAL.md](./SETUP_LOCAL.md)** - Instalación completa
- **[docs/INSTALL.md](./INSTALL.md)** - Requisitos y pasos

### 🏗️ Arquitectura y Estructura
- **[README.md](../README.md)** - Descripción del proyecto
- **[docs/ARCHITECTURE.md](./ARCHITECTURE.md)** - Arquitectura del sistema
- **[docs/DATABASE.md](./DATABASE.md)** - Diseño de base de datos
- **[ESTRUCTURA_COMPLETA.md](./ESTRUCTURA_COMPLETA.md)** - Estructura de archivos

### 🌐 API y Desarrollo
- **[docs/API.md](./docs/API.md)** - Documentación de API
- **[docs/EJEMPLOS_API.md](./docs/EJEMPLOS_API.md)** - Ejemplos de uso

---

## 🎯 SEGÚN TU NECESIDAD

### "Quiero ejecutar el proyecto YA"
```
1. Abre terminal en esta carpeta
2. Ejecuta: composer install
3. Configura .env y base de datos
4. Accede a: http://localhost/mapeo-vecinal/
```
👉 **[QUICK_START.md](./QUICK_START.md)**

---

### "Tengo problemas de instalación"
```
1. Lee: docs/SETUP_LOCAL.md
2. Busca tu error en la sección "Solución de problemas"
3. Revisa logs en writable/logs
```
👉 **[docs/SETUP_LOCAL.md#solución-de-problemas](./SETUP_LOCAL.md)**

---

### "Quiero entender la estructura del proyecto"
```
1. Lee: README.md (Descripción general)
2. Lee: docs/ARCHITECTURE.md (Diseño técnico)
3. Lee: docs/DATABASE.md (Base de datos)
```
👉 **[README.md](../README.md)**

---

### "Voy a desarrollar en el proyecto"
```
1. Ejecuta setup manual (composer + env + migrate)
2. Lee: docs/API.md (Endpoints disponibles)
3. Lee: ESTRUCTURA_COMPLETA.md (Convenciones)
4. Comienza a desarrollar
```
👉 **[docs/API.md](./API.md)**

---

## 📋 CHECKLIST DE INSTALACIÓN

### ✅ Antes de Comenzar
- [ ] Lees el [README.md](./README.md)
- [ ] Tienes XAMPP instalado (o tu servidor PHP preferido)
- [ ] Tienes Composer instalado
- [ ] Tienes MySQL/MariaDB instalado

### ✅ Durante la Instalación
- [ ] Ejecutas `composer install`
- [ ] Configuras `.env`
- [ ] Ejecutas migraciones y seeders

### ✅ Después de la Instalación
- [ ] Accedes a http://localhost/mapeo-vecinal/
- [ ] El proyecto carga sin errores
- [ ] Puedes loguear con usuario de prueba
- [ ] Ves el dashboard y opciones de menú

---

## 📁 ESTRUCTURA DE CARPETAS

```
mapeo-vecinal/
├── app/                      # Código de la aplicación
│   ├── Controllers/          # Controladores (CRUD)
│   ├── Models/               # Modelos (BD)
│   ├── Views/                # Plantillas (Vistas)
│   ├── Database/             # Migraciones y Seeds
│   └── Filters/              # Middleware
│
├── public/                   # Carpeta pública (web)
│   ├── index.php             # Punto de entrada
│   └── assets/               # CSS, JS, imágenes
│
├── docs/                     # Documentación
│   ├── API.md                # API endpoints
│   ├── DATABASE.md           # Esquema BD
│   ├── ARCHITECTURE.md       # Arquitectura
│   ├── INSTALL.md            # Instalación
│   ├── EJEMPLOS_API.md       # Ejemplos
│   └── SETUP_LOCAL.md        # Setup local
│
├── writable/                 # Permisos de escritura
│   ├── logs/                 # Logs de aplicación
│   ├── cache/                # Cache
│   └── session/              # Sesiones
│
└── spark                     # CLI de CodeIgniter
```

---

## ⚡ COMANDOS RÁPIDOS

```bash
# Instalar dependencias
composer install

# Iniciar servidor
php spark serve
# → http://localhost:8080

# Usar XAMPP Apache
# Accede a: http://localhost/mapeo-vecinal

# Ver migraciones ejecutadas
php spark migrate:status

# Ejecutar migraciones
php spark migrate

# Cargar datos de prueba
php spark db:seed

# Limpiar caché
php spark cache:clear
```

---

## 🔗 ENLACES RÁPIDOS

| Documento | Descripción |
|-----------|-----------|
| [README.md](../README.md) | Descripción general del proyecto |
| [QUICK_START.md](./QUICK_START.md) | Inicio rápido (5 minutos) |
| [docs/SETUP_LOCAL.md](./SETUP_LOCAL.md) | Instalación local completa |
| [docs/INSTALL.md](./INSTALL.md) | Instalación general |
| [docs/API.md](./API.md) | Documentación de API |
| [docs/DATABASE.md](./DATABASE.md) | Esquema de base de datos |
| [docs/ARCHITECTURE.md](./ARCHITECTURE.md) | Arquitectura del sistema |
| [ESTRUCTURA_COMPLETA.md](./ESTRUCTURA_COMPLETA.md) | Estructura de archivos |

---

## 🆘 NECESITO AYUDA

### Problema: Instalación
→ Ver: [docs/SETUP_LOCAL.md#solución-de-problemas](./docs/SETUP_LOCAL.md)

### Problema: Base de datos
→ Ver: [docs/DATABASE.md](./docs/DATABASE.md)

### Problema: API
→ Ver: [docs/API.md](./docs/API.md)

### Problema: Otra cosa
→ Buscar en: [docs/](./docs/)

---

## 👨‍💻 PARA DESARROLLADORES

### Configuración de Desarrollo
```bash
CI_ENVIRONMENT = development
```

### Base de Datos
- Host: `localhost`
- Puerto: `3306`
- Usuario: `root`
- BD: `mapeo_vecinal`

### Servidor
- Acceso local: `http://localhost:8080`
- Acceso por XAMPP: `http://localhost/mapeo-vecinal`

### Migraciones
```bash
# Ver estado
php spark migrate:status

# Crear nueva migración
php spark make:migration create_tabla

# Ejecutar todas
php spark migrate --all

# Rollback
php spark migrate:rollback
```

---

## 📅 VERSIONES

- **Proyecto:** Mapeo Vecinal v1.0
- **CodeIgniter:** 4.6+
- **PHP:** 8.1+
- **MySQL:** 8.0+

---

**Última actualización:** Junio 2026  
**Versión:** 1.0

👉 **¿Listo para comenzar?** → [QUICK_START.md](./QUICK_START.md)
