# Configuración Local - Mapeo Vecinal

> Guía para ejecutar Mapeo Vecinal localmente en tu máquina con XAMPP

---

## 📋 Tabla de Contenidos

1. [Opción 1: Instalación Recomendada (Manual Guiada)](#opción-1-instalación-recomendada-manual-guiada)
2. [Opción 2: Instalación Manual](#opción-2-instalación-manual)
3. [Verificación de la Instalación](#verificación-de-la-instalación)
4. [Solución de Problemas](#solución-de-problemas)
5. [Comandos Útiles](#comandos-útiles)

---

## Opción 1: Instalación Recomendada (Manual Guiada)

### ⚙️ Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:

- **XAMPP** - Descarga desde: https://www.apachefriends.org/
  - Versión mínima: 8.0
  - Instalación típica en: `C:\xampp` (Windows)
- **Composer** - Descarga desde: https://getcomposer.org/
- **Git** (opcional, para actualizaciones)

### 🚀 Pasos recomendados

```bash
cd C:\xampp\htdocs\mapeo-vecinal
composer install
copy .env.example .env
```

Configura `.env` y luego ejecuta:

```bash
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS mapeo_vecinal;"
php spark migrate --all
php spark db:seed UsuarioSeeder
php spark db:seed CategoriaSeeder
```

---

## Opción 2: Instalación Manual

Si prefieres controlar cada paso:

### Paso 1: Copiar Proyecto a XAMPP

```bash
# Copiar el directorio completo
xcopy C:\ruta\original\mapeo-vecinal C:\xampp\htdocs\mapeo-vecinal\ /E /I

# O desde PowerShell
Copy-Item -Path "C:\ruta\original\mapeo-vecinal" `
          -Destination "C:\xampp\htdocs\mapeo-vecinal" `
          -Recurse
```

### Paso 2: Configurar Variables de Entorno

```bash
cd C:\xampp\htdocs\mapeo-vecinal

# Copiar archivo de ejemplo
copy .env.example .env

# Editar .env con tu editor favorito (VSCode, Notepad++, etc.)
code .env
```

**Configuración necesaria en `.env`:**

```ini
# Configuración de aplicación
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost/mapeo-vecinal/'

# Configuración de base de datos (XAMPP típicamente)
database.default.hostname = localhost
database.default.database = mapeo_vecinal
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.DBPrefix = 
database.default.port = 3306

# Configuración de sesión
app.sessionDriver = FileHandler
app.sessionCookieName = PHPSESSID
app.sessionExpiration = 7200

# Logging
log.threshold = 4
```

### Paso 3: Instalar Dependencias

```bash
cd C:\xampp\htdocs\mapeo-vecinal
composer install
```

### Paso 4: Crear Base de Datos

#### Opción A: Desde línea de comandos

```bash
# Abrir MySQL desde XAMPP
C:\xampp\mysql\bin\mysql.exe -u root

# En el prompt de MySQL
CREATE DATABASE mapeo_vecinal;
EXIT;
```

#### Opción B: Desde phpMyAdmin

1. Accede a: http://localhost/phpmyadmin
2. Click en "Nueva" o "New Database"
3. Nombre: `mapeo_vecinal`
4. Click en "Crear"

### Paso 5: Ejecutar Migraciones

```bash
cd C:\xampp\htdocs\mapeo-vecinal
php spark migrate --all
```

### Paso 6: Cargar Datos Iniciales

```bash
php spark db:seed UsuarioSeeder
php spark db:seed CategoriaSeeder
```

---

## Verificación de la Instalación

### 1. Verificar Estructura de Directorios

```bash
# Debería verse:
C:\xampp\htdocs\mapeo-vecinal\
├── app/
│   ├── Controllers/
│   ├── Models/
│   ├── Views/
│   ├── Database/
│   └── Config/
├── public/
├── vendor/
├── .env
├── .env.example
└── spark
```

### 2. Verificar Base de Datos

```bash
# En MySQL
mysql -u root mapeo_vecinal

# Listar tablas (debería mostrar: usuarios, categorias, reportes, propuestas, votaciones)
SHOW TABLES;

# Ver estructura de usuarios
DESC usuarios;
```

### 3. Probar Servidor

**Opción A: Servidor integrado de CodeIgniter**

```bash
cd C:\xampp\htdocs\mapeo-vecinal
php spark serve
```

Accede a: http://localhost:8080

**Opción B: Usar Apache de XAMPP**

1. Abre Control Panel de XAMPP
2. Inicia "Apache" y "MySQL"
3. Accede a: http://localhost/mapeo-vecinal/

### 4. Verificar Permisos

```bash
# Crear carpeta de logs si no existe
mkdir C:\xampp\htdocs\mapeo-vecinal\writable
mkdir C:\xampp\htdocs\mapeo-vecinal\writable\logs
mkdir C:\xampp\htdocs\mapeo-vecinal\writable\cache
mkdir C:\xampp\htdocs\mapeo-vecinal\writable\session

# En Windows, XAMPP típicamente tiene permisos suficientes
# Si hay problemas, dar permisos de lectura/escritura a la carpeta
```

---

## Solución de Problemas

### ❌ "Composer command not found"

**Solución:**
```bash
# Instala Composer desde: https://getcomposer.org/download/
# O agrega a PATH de Windows

# Verificar instalación:
composer --version
```

### ❌ "MySQL connection error"

**Soluciones:**

1. **Verificar que MySQL está corriendo:**
   ```bash
   # En XAMPP Control Panel, inicia MySQL
   # O desde línea de comandos:
   cd C:\xampp\mysql\bin
   mysql -u root
   ```

2. **Verificar credenciales en `.env`:**
   ```ini
   database.default.hostname = localhost
   database.default.username = root
   database.default.password =  # Vacío si es la configuración por defecto
   ```

3. **Restablecer contraseña MySQL:**
   ```bash
   # En phpMyAdmin (http://localhost/phpmyadmin)
   # Click en "Cuentas" → "root" → Cambiar contraseña
   ```

### ❌ "Error: SQLSTATE[HY000]"

**Causas comunes:**

- Base de datos no existe → Ejecuta el paso 4
- Usuario MySQL sin permisos → Verifica credenciales
- Puerto MySQL incorrecto → Verifica puerto en `.env` (típicamente 3306)

**Solución:**

```bash
# Recrear base de datos
mysql -u root
DROP DATABASE IF EXISTS mapeo_vecinal;
CREATE DATABASE mapeo_vecinal;
EXIT;

# Ejecutar migraciones de nuevo
cd C:\xampp\htdocs\mapeo-vecinal
php spark migrate --all
```

### ❌ "Clase X no encontrada" o errores de autoload

**Solución:**

```bash
# Regenerar autoloader de Composer
cd C:\xampp\htdocs\mapeo-vecinal
composer dump-autoload
```

### ❌ "Permission denied" en carpeta `writable`

**Solución (Windows):**

```powershell
# Dar permisos de escritura
$path = "C:\xampp\htdocs\mapeo-vecinal\writable"
$acl = Get-Acl $path
$rule = New-Object System.Security.AccessControl.FileSystemAccessRule(
    "Everyone", "Modify", "ContainerInherit,ObjectInherit", "None", "Allow"
)
$acl.SetAccessRule($rule)
Set-Acl -Path $path -AclObject $acl
```

---

## Comandos Útiles

### Desarrollo

```bash
# Iniciar servidor de desarrollo
php spark serve

# Ejecutar migraciones (crear tablas)
php spark migrate

# Deshacer última migración
php spark migrate:rollback

# Ver estado de migraciones
php spark migrate:status

# Crear nueva migración
php spark make:migration create_tabla_name
```

### Base de Datos

```bash
# Ejecutar un seeder específico
php spark db:seed UsuarioSeeder

# Ejecutar todos los seeders
php spark db:seed --all

# Acceder a MySQL
mysql -u root mapeo_vecinal

# Exportar base de datos
mysqldump -u root mapeo_vecinal > backup.sql

# Importar base de datos
mysql -u root mapeo_vecinal < backup.sql
```

### Depuración

```bash
# Ver última línea del log
tail writable/logs/log-*.log

# Ejecutar en modo debug
CI_ENVIRONMENT=development php spark serve

# Limpiar cache
php spark cache:clear
```

### Composer

```bash
# Instalar dependencias
composer install

# Actualizar paquetes
composer update

# Regenerar autoloader
composer dump-autoload

# Mostrar dependencias instaladas
composer show
```

---

## ✅ Checklist de Configuración

- [ ] XAMPP instalado en `C:\xampp`
- [ ] PHP 8.1+ disponible
- [ ] Composer instalado
- [ ] Proyecto copiado a `C:\xampp\htdocs\mapeo-vecinal\`
- [ ] Archivo `.env` configurado
- [ ] Dependencias instaladas (`composer install`)
- [ ] Base de datos creada (`mapeo_vecinal`)
- [ ] Migraciones ejecutadas (`php spark migrate`)
- [ ] Datos iniciales cargados (`php spark db:seed`)
- [ ] Servidor accesible en `http://localhost/mapeo-vecinal/`

---

## 📞 Soporte

Si encuentras problemas:

1. **Revisa los logs:**
   ```bash
   tail -f C:\xampp\htdocs\mapeo-vecinal\writable\logs\log-*.log
   ```

2. **Consulta la documentación:**
   - [docs/INSTALL.md](./INSTALL.md) - Instalación general
   - [docs/API.md](./API.md) - Referencia de API
   - [CodeIgniter 4 Docs](https://codeigniter.com/user_guide/)

3. **Reporta issues:**
   - GitHub Issues (si está disponible)
   - Equipo de desarrollo

---

**Última actualización:** Junio 2026  
**Versión:** 1.0
