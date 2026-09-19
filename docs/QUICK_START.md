# ⚡ Inicio Rapido - Mapeo Vecinal

## 1) Requisitos
- PHP 8.1+
- Composer
- MySQL 8.0+
- XAMPP (opcional)

## 2) Instalacion
```bash
cd C:\xampp\htdocs\mapeo-vecinal
composer install
copy .env.example .env
```

Configura `.env`:
```ini
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'
database.default.hostname = localhost
database.default.database = mapeo_vecinal
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
```

## 3) Base de datos
```bash
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS mapeo_vecinal;"
php spark migrate --all
php spark db:seed UsuarioSeeder
php spark db:seed CategoriaSeeder
```

## 4) Ejecutar proyecto
```bash
php spark serve
```

Accede a:
- http://localhost:8080
- o http://localhost/mapeo-vecinal/ si usas Apache de XAMPP

## 5) Comandos utiles
```bash
php spark migrate:status
php spark migrate:rollback
php spark cache:clear
```
