# Guía de Instalación - Mapeo Vecinal

## Requisitos Previos

### Software Requerido

- **PHP** 8.1 o superior
- **MySQL** 8.0 o superior (o MariaDB 10.5+)
- **Composer** (gestor de dependencias de PHP)
- **Git** (opcional, para clonar repositorio)
- **Node.js 18+** (opcional, para Assets frontend)

### Verificar Instalación

```bash
# Verificar PHP
php -v

# Verificar MySQL
mysql --version

# Verificar Composer
composer --version
```

---

## Instalación Paso a Paso

### 1. Descargar o Clonar el Proyecto

**Opción A: Clonar desde Git**
```bash
git clone https://github.com/tuusuario/mapeo-vecinal.git
cd mapeo-vecinal
```

**Opción B: Descargar ZIP**
```bash
# Descargar y descomprimir
unzip mapeo-vecinal-main.zip
cd mapeo-vecinal
```

### 2. Instalar Dependencias PHP

```bash
composer install
```

Esto instalará:
- **CodeIgniter 4** (Framework MVC)
- **Migrations** (Versionado de BD)
- **Validation** (Validadores)
- Otras librerías necesarias

**Tiempo estimado**: 2-5 minutos

### 3. Configurar Variables de Entorno

```bash
# En Windows
copy .env.example .env

# En Linux/Mac
cp .env.example .env
```

Editar el archivo `.env` con tu editor favorito:

```ini
# ============================================================
# APP SETTINGS
# ============================================================
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'

# ============================================================
# DATABASE
# ============================================================
database.default.hostname = localhost
database.default.database = mapeo_vecinal
database.default.username = root
database.default.password = tu_contraseña_mysql
database.default.DBDriver = MySQLi
database.default.DBPrefix = 
database.default.port = 3306

# ============================================================
# SECURITY
# ============================================================
app.sessionDriver = 'FileHandler'
app.sessionCookieName = 'PHPSESSID'
app.sessionExpiration = 7200

# ============================================================
# LOGGING
# ============================================================
log.threshold = 4
```

### 4. Crear Base de Datos

**Opción A: Desde Terminal MySQL**

```bash
mysql -u root -p
```

```sql
CREATE DATABASE mapeo_vecinal;
CREATE USER 'mapeo_user'@'localhost' IDENTIFIED BY 'contraseña_segura';
GRANT ALL PRIVILEGES ON mapeo_vecinal.* TO 'mapeo_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**Opción B: Usando phpMyAdmin**

1. Abrir `http://localhost/phpmyadmin`
2. Crear base de datos: `mapeo_vecinal`
3. Crear usuario: `mapeo_user`
4. Asignar permisos totales

### 5. Ejecutar Migraciones de Base de Datos

Las migraciones crearán todas las tablas automáticamente:

```bash
php spark migrate
```

**Salida esperada**:
```
Running all migrations...
✓ App\Database\Migrations\CreateUsuarios
✓ App\Database\Migrations\CreateCategorias
✓ App\Database\Migrations\CreateReportes
✓ App\Database\Migrations\CreatePropuestas
✓ App\Database\Migrations\CreateVotaciones
```

### 6. Cargar Datos Iniciales (Seeders)

```bash
# Crear usuarios de prueba
php spark db:seed UsuarioSeeder

# Crear categorías
php spark db:seed CategoriaSeeder
```

**Salida esperada**:
```
✓ 8 usuarios creados exitosamente
  - admin@mapeo-vecinal.local (Contraseña: admin123)
  - autoridad1@municipio.local (Contraseña: autoridad123)
  - ...
✓ 10 categorías creadas exitosamente
```

### 7. Crear Carpetas Necesarias

```bash
# Carpeta para cargas de archivos
mkdir -p public/uploads
mkdir -p writable/cache
mkdir -p writable/logs
mkdir -p writable/session

# Dar permisos de escritura
chmod -R 755 writable/
chmod -R 755 public/uploads/
```

### 8. Verificar Permisos (Importante)

```bash
# En desarrollo, permitir escritura
chmod -R 777 writable/
chmod -R 777 public/uploads/
```

### 9. Generar Claves de Seguridad

```bash
php spark key:generate
```

Esto generará una clave de encriptación en `.env`

### 10. Iniciar Servidor de Desarrollo

```bash
php spark serve
```

**Salida esperada**:
```
CodeIgniter development server started on http://localhost:8080
Press Control + C to quit
```

Acceder en el navegador: `http://localhost:8080`

---

## Verificación Posterior a Instalación

### Checklist de Verificación

- [ ] Base de datos creada: `mapeo_vecinal`
- [ ] Tablas creadas: `usuarios`, `categorias`, `reportes`, `propuestas`, `votaciones`
- [ ] Usuarios seeders cargados (8 usuarios)
- [ ] Categorías seeders cargadas (10 categorías)
- [ ] Servidor iniciando sin errores
- [ ] Accesible en `http://localhost:8080`

### Prueba Rápida de API

```bash
# Obtener todas las categorías
curl http://localhost:8080/categorias

# Obtener usuarios
curl http://localhost:8080/usuarios
```

**Respuesta esperada**: JSON con datos de categorías y usuarios

---

## Usuarios de Prueba

Después de ejecutar los seeders, tienes estos usuarios:

### Admin
- **Email**: admin@mapeo-vecinal.local
- **Contraseña**: admin123
- **Rol**: Administrador

### Autoridades
- **Email**: autoridad1@municipio.local
- **Contraseña**: autoridad123
- **Rol**: Autoridad (puede ver dashboard, responder reportes)

- **Email**: autoridad2@municipio.local
- **Contraseña**: autoridad123
- **Rol**: Autoridad

### Ciudadanos
- carlos@ejemplo.com | ciudadano123
- ana@ejemplo.com | ciudadano123
- luis@ejemplo.com | ciudadano123
- elena@ejemplo.com | ciudadano123
- francisco@ejemplo.com | ciudadano123

---

## Solución de Problemas Comunes

### Error: "Database connection failed"

**Causa**: Credenciales MySQL incorrectas

**Solución**:
```bash
# Verificar credenciales en .env
# Verificar que MySQL está corriendo
mysql -u root -p
```

### Error: "Tables do not exist"

**Causa**: Migraciones no ejecutadas

**Solución**:
```bash
php spark migrate
```

### Error: "Permission denied" en carpetas

**Causa**: Permisos incorrectos en `writable/`

**Solución**:
```bash
chmod -R 777 writable/
```

### Puerto 8080 ya en uso

**Causa**: Otro proceso usando el puerto

**Solución**:
```bash
# Usar puerto diferente
php spark serve --port 8081

# O buscar proceso en puerto 8080
# Windows:
netstat -ano | findstr :8080
taskkill /PID <PID> /F

# Linux:
lsof -i :8080
kill -9 <PID>
```

### Error de "Charset"

**Causa**: Configuración de MySQL

**Solución** en `.env`:
```ini
database.default.charset = utf8mb4
database.default.collation = utf8mb4_unicode_ci
```

---

## Configuración Adicional (Opcional)

### Activar HTTPS en Desarrollo

```bash
# Generar certificado autofirmado
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout key.pem -out cert.pem

# Iniciar servidor con SSL
php spark serve --host localhost --port 8443 --ssl
```

### Configurar Email

Editar `.env`:
```ini
email.protocol = smtp
email.SMTPHost = smtp.gmail.com
email.SMTPUser = tu_email@gmail.com
email.SMTPPass = tu_contraseña
email.SMTPPort = 587
```

### Activar Logging Detallado

Editar `.env`:
```ini
log.threshold = 1  # Log todo
```

---

## Instalación en Producción

Para desplegar en producción:

1. **Establecer ambiente a production**
   ```ini
   CI_ENVIRONMENT = production
   ```

2. **Ocultar archivos sensibles**
   ```bash
   rm .env
   chmod 600 .env
   ```

3. **Optimizar Autoloader**
   ```bash
   composer dump-autoload --optimize
   ```

4. **Deshabilitar debug**
   ```ini
   CI_DEBUG = 0
   ```

5. **Configurar SSL/TLS**
   ```nginx
   # nginx
   listen 443 ssl;
   ssl_certificate /path/to/cert.pem;
   ssl_certificate_key /path/to/key.pem;
   ```

---

## Próximos Pasos

1. ✅ Instalación completada
2. 📖 Leer [ARCHITECTURE.md](ARCHITECTURE.md) para entender estructura
3. 📚 Leer [API.md](API.md) para documentación de endpoints
4. 💾 Leer [DATABASE.md](DATABASE.md) para diseño de BD
5. 🚀 Comenzar a desarrollar features

---

## Soporte

Para problemas o dudas:

1. Revisar los logs: `writable/logs/`
2. Consultar [CodeIgniter 4 Docs](https://codeigniter.com/user_guide/)
3. Abrir un issue en GitHub
4. Contactar: info@mapeo-vecinal.com

---

**Última actualización**: 2026-06-10
