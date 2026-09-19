# Mapeo Vecinal - Instrucciones de Copilot

## Descripción del Proyecto
Mapeo Vecinal es una plataforma cívica de código abierto construida con **CodeIgniter 4** que permite a los ciudadanos reportar problemas, proponer mejoras y votar soluciones para su barrio.

## Stack Técnico
- **Backend**: CodeIgniter 4 (MVC, Query Builder, Filtros)
- **Frontend**: HTML5, CSS3, JavaScript
- **Plantillas**: Smarty (motor de plantillas)
- **Base de Datos**: MySQL 8.0+
- **Autenticación**: JWT / Sesiones nativas de CI4

## Estructura del Proyecto
```
mapeo-vecinal/
├── app/
│   ├── Controllers/       # Controladores CRUD
│   ├── Models/            # Modelos de entidades
│   ├── Views/             # Plantillas Smarty
│   ├── Database/
│   │   ├── Migrations/    # Migraciones de BD
│   │   └── Seeds/         # Datos iniciales
│   ├── Filters/           # Filtros de autenticación
│   └── Config/            # Configuraciones personalizadas
├── public/
│   └── assets/            # CSS, JS, imágenes
├── docs/                  # Documentación profesional
└── README.md
```

## Entidades Principales (5 CRUDs)
1. **Usuarios** - Gestión de cuentas y roles (admin, ciudadano, autoridad)
2. **Categorías** - Tipos de problemas/propuestas (Infraestructura, Seguridad, etc.)
3. **Reportes** - Problemas reportados por ciudadanos
4. **Propuestas** - Mejoras propuestas por la comunidad
5. **Votaciones** - Sistema de votos en propuestas

## Reglas de Desarrollo
- Seguir convenciones de CodeIgniter 4
- Validación en cliente y servidor
- Documentación inline en inglés
- Commits descriptivos en español
- Tests unitarios para modelos críticos

## URLs de Referencia
- [CodeIgniter 4 Docs](https://codeigniter.com/user_guide/)
- [Smarty Template Engine](https://www.smarty.net/)

---
**Última actualización**: 2026-06-10
