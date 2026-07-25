# Sistema de Gestión de Citas Médicas — Laravel

Proyecto para la materia **Ingeniería de Software 2**. Aplicación web construida con
**Laravel 11** para gestionar citas de una clínica pequeña: pacientes, doctores,
recepcionistas y un administrador, cada uno con su propio login y permisos.

## Roles del sistema

| Rol | Puede hacer |
|---|---|
| `admin` | Todo: gestiona doctores, pacientes y citas |
| `recepcionista` | Gestiona pacientes y citas (CRUD completo) |
| `doctor` | Ve su propia agenda de citas (solo lectura) |
| `paciente` | Ve sus propias citas al iniciar sesión |

## Requisitos previos (en tu computador, no en este chat)

- PHP >= 8.2
- Composer
- MariaDB 10.x o MySQL 5.7+
- Node.js + npm (opcional, solo si vas a compilar assets)
- Extensión de VS Code recomendada: **PHP Intelephense**, **Laravel Blade Snippets**

## Instalación paso a paso (Visual Studio Code)

1. Descomprime este proyecto y ábrelo en VS Code (`code .`).
2. Instala las dependencias de PHP:
   ```bash
   composer install
   ```
3. Copia el archivo de entorno y genera la clave de la app:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Crea la base de datos en MariaDB (puedes usar phpMyAdmin, HeidiSQL o el cliente `mysql`):
   ```sql
   CREATE DATABASE citas_medicas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
5. Edita `.env` con tus credenciales reales de MariaDB:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=citas_medicas
   DB_USERNAME=root
   DB_PASSWORD=tu_password
   ```
6. Ejecuta las migraciones y el seeder (esto genera la estructura Y los datos de prueba
   —5 doctores, 7 recepcionistas, 20 pacientes— usando las clases en `database/seeders`):
   ```bash
   php artisan migrate --seed
   ```
   **Alternativa:** si prefieres cargar la base de datos directamente en MariaDB sin usar
   los seeders de Laravel, importa el archivo `database/sql/citas_medicas.sql` (ver sección
   siguiente).
7. Levanta el servidor de desarrollo:
   ```bash
   php artisan serve
   ```
8. Abre `http://127.0.0.1:8000` en el navegador.

## Usuarios de prueba (contraseña para todos: `password`)

- **Admin:** admin@clinica.com
- **Recepcionista:** mlopez@clinica.com
- **Doctor:** ralvarado@clinica.com
- **Paciente:** jperez@correo.com

## Base de datos en MariaDB (opción manual)

En `database/sql/citas_medicas.sql` está el script SQL puro (estructura + datos) por si
quieres importarlo directamente sin pasar por Laravel:

```bash
mysql -u root -p < database/sql/citas_medicas.sql
```

Esto crea la base `citas_medicas` con las 4 tablas (`users`, `doctores`, `pacientes`,
`citas`) y los mismos datos de ejemplo que genera el seeder.

## Estructura relevante del proyecto

```
app/Models/            -> User, Doctor, Paciente, Cita
app/Http/Controllers/   -> LoginController, CitaController, PacienteController, DoctorController, DashboardController
app/Http/Middleware/    -> EnsureRole.php (control de acceso por rol)
database/migrations/    -> estructura de las 4 tablas
database/seeders/       -> datos de prueba (5 doctores, 7 recepcionistas, 20 pacientes, 20 citas)
database/sql/           -> script SQL equivalente para importar directo en MariaDB
resources/views/        -> vistas Blade (login, dashboard, citas, pacientes, doctores)
routes/web.php          -> todas las rutas de la aplicación
```

## Funcionalidades implementadas (según los requisitos del proyecto)

- **CRUD completo** de Citas, Pacientes y Doctores (crear, leer, actualizar, eliminar).
- **Autenticación de usuarios (login)** para los 4 roles, con sesiones de Laravel.
- **Control de acceso** por rol mediante middleware (`EnsureRole`).
- **Validación de entradas** en cada formulario (`$request->validate(...)`), incluyendo
  reglas de unicidad (cédula, email) y reglas de negocio (un doctor no puede tener dos
  citas a la misma fecha/hora).
- **Manejo de errores** con mensajes de validación mostrados en las vistas y bloqueo de
  duplicados a nivel de base de datos (índice único compuesto en `citas`).
