-- =====================================================================
-- Sistema de Gestion de Citas Medicas
-- Script SQL para MariaDB (compatible con MySQL 5.7+/MariaDB 10.x)
-- Contiene: estructura de tablas + datos de ejemplo
--   - 1 administrador
--   - 7 recepcionistas
--   - 5 doctores
--   - 20 pacientes (clientes con login)
--   - 20 citas de ejemplo
-- Password de TODOS los usuarios de prueba: "password"
-- =====================================================================

CREATE DATABASE IF NOT EXISTS citas_medicas
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE citas_medicas;

-- ---------------------------------------------------------------------
-- Tabla: users (login de admin, recepcionistas, doctores y pacientes)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin','recepcionista','doctor','paciente') NOT NULL DEFAULT 'paciente'
        COMMENT 'Rol del usuario dentro del sistema',
    activo TINYINT(1) NOT NULL DEFAULT 1
        COMMENT 'Si es 0, el usuario no puede iniciar sesion (cuenta desactivada por el admin)',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tabla: doctores
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS doctores;
CREATE TABLE doctores (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    especialidad VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    horario_inicio TIME NOT NULL DEFAULT '08:00:00',
    horario_fin TIME NOT NULL DEFAULT '17:00:00',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_doctores_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tabla: pacientes
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS pacientes;
CREATE TABLE pacientes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    cedula VARCHAR(15) NOT NULL UNIQUE,
    telefono VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    fecha_nacimiento DATE NOT NULL,
    direccion VARCHAR(255) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_pacientes_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tabla: citas
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS citas;
CREATE TABLE citas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    paciente_id BIGINT UNSIGNED NOT NULL,
    doctor_id BIGINT UNSIGNED NOT NULL,
    creado_por BIGINT UNSIGNED NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    motivo VARCHAR(255) NOT NULL,
    estado ENUM('pendiente','confirmada','cancelada','atendida') NOT NULL DEFAULT 'pendiente',
    observaciones TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    -- ON DELETE RESTRICT (antes CASCADE): no se puede borrar un doctor o
    -- paciente que todavia tenga citas asociadas (de cualquier estado),
    -- para proteger el historial clinico aunque el borrado se haga por
    -- fuera de los controladores de la app.
    CONSTRAINT fk_citas_paciente FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE RESTRICT,
    CONSTRAINT fk_citas_doctor FOREIGN KEY (doctor_id) REFERENCES doctores(id) ON DELETE RESTRICT,
    CONSTRAINT fk_citas_creador FOREIGN KEY (creado_por) REFERENCES users(id) ON DELETE SET NULL
    -- Nota: NO se define UNIQUE(doctor_id, fecha, hora) a nivel de BD.
    -- La regla "un doctor no puede tener dos citas activas en la misma
    -- fecha/hora" se valida solo en la aplicacion (CitaController), donde
    -- se excluyen las citas con estado = 'cancelada'. Un unique en BD
    -- bloquearia ese horario aunque la cita estuviera cancelada.
) ENGINE=InnoDB;


-- ---------------------------------------------------------------------
-- DATOS DE PRUEBA
-- Password para TODOS: "password"  (hash bcrypt de ejemplo)
-- ---------------------------------------------------------------------
SET @pass = '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO';

-- 1 Administrador
INSERT INTO users (name, email, password, rol) VALUES
('Administrador General', 'admin@clinica.com', @pass, 'admin');

-- 7 Recepcionistas
INSERT INTO users (name, email, password, rol) VALUES
('Maria Fernanda Lopez Torres', 'mlopez@clinica.com', @pass, 'recepcionista'),
('Carlos Andres Ramirez Vera', 'cramirez@clinica.com', @pass, 'recepcionista'),
('Gabriela Isabel Suarez Mora', 'gsuarez@clinica.com', @pass, 'recepcionista'),
('Jorge Luis Castillo Reyes', 'jcastillo@clinica.com', @pass, 'recepcionista'),
('Andrea Paola Jimenez Cruz', 'ajimenez@clinica.com', @pass, 'recepcionista'),
('Diego Fernando Ortiz Salas', 'dortiz@clinica.com', @pass, 'recepcionista'),
('Valentina Cordova Pena', 'vcordova@clinica.com', @pass, 'recepcionista');

-- 5 Doctores (usuarios de login)
INSERT INTO users (name, email, password, rol) VALUES
('Dr. Ricardo Alvarado Pena', 'ralvarado@clinica.com', @pass, 'doctor'),
('Dr. Sofia Mendoza Cruz', 'smendoza@clinica.com', @pass, 'doctor'),
('Dr. Miguel Torres Chavez', 'mtorres@clinica.com', @pass, 'doctor'),
('Dr. Paola Ibarra Salinas', 'pibarra@clinica.com', @pass, 'doctor'),
('Dr. Fernando Rojas Delgado', 'frojas@clinica.com', @pass, 'doctor');

-- Perfiles de doctores (ligados a su user_id: 9 al 13)
INSERT INTO doctores (user_id, nombre, apellido, especialidad, telefono, email, horario_inicio, horario_fin) VALUES
(9,  'Ricardo',  'Alvarado Pena',  'Medicina General', '0991234561', 'ralvarado@clinica.com', '08:00:00', '17:00:00'),
(10, 'Sofia',    'Mendoza Cruz',   'Pediatria',        '0991234562', 'smendoza@clinica.com', '08:00:00', '17:00:00'),
(11, 'Miguel',   'Torres Chavez',  'Cardiologia',      '0991234563', 'mtorres@clinica.com',  '08:00:00', '17:00:00'),
(12, 'Paola',    'Ibarra Salinas', 'Ginecologia',      '0991234564', 'pibarra@clinica.com',  '08:00:00', '17:00:00'),
(13, 'Fernando', 'Rojas Delgado',  'Dermatologia',     '0991234565', 'frojas@clinica.com',   '08:00:00', '17:00:00');

-- 20 Pacientes (usuarios de login: id 14 al 33)
INSERT INTO users (name, email, password, rol) VALUES
('Juan Perez Gomez', 'jperez@correo.com', @pass, 'paciente'),
('Maria Gonzalez Ruiz', 'mgonzalez@correo.com', @pass, 'paciente'),
('Luis Martinez Soto', 'lmartinez@correo.com', @pass, 'paciente'),
('Ana Lopez Vera', 'alopez@correo.com', @pass, 'paciente'),
('Pedro Sanchez Diaz', 'psanchez@correo.com', @pass, 'paciente'),
('Carmen Ramirez Ortiz', 'cramirez2@correo.com', @pass, 'paciente'),
('Jose Torres Leon', 'jtorres@correo.com', @pass, 'paciente'),
('Rosa Flores Castillo', 'rflores@correo.com', @pass, 'paciente'),
('Manuel Vargas Rios', 'mvargas@correo.com', @pass, 'paciente'),
('Isabel Castro Mora', 'icastro@correo.com', @pass, 'paciente'),
('Francisco Morales Pena', 'fmorales@correo.com', @pass, 'paciente'),
('Elena Jimenez Cordova', 'ejimenez@correo.com', @pass, 'paciente'),
('Alberto Reyes Salas', 'areyes@correo.com', @pass, 'paciente'),
('Patricia Suarez Vega', 'psuarez@correo.com', @pass, 'paciente'),
('Roberto Cruz Herrera', 'rcruz@correo.com', @pass, 'paciente'),
('Sandra Ortega Paredes', 'sortega@correo.com', @pass, 'paciente'),
('Ricardo Delgado Nunez', 'rdelgado@correo.com', @pass, 'paciente'),
('Monica Aguilar Rojas', 'maguilar@correo.com', @pass, 'paciente'),
('Eduardo Silva Campos', 'esilva@correo.com', @pass, 'paciente'),
('Veronica Paredes Luna', 'vparedes@correo.com', @pass, 'paciente');

-- Perfiles de pacientes (ligados a su user_id: 14 al 33)
INSERT INTO pacientes (user_id, nombre, apellido, cedula, telefono, email, fecha_nacimiento, direccion) VALUES
(14, 'Juan', 'Perez Gomez', '0701234501', '0987654301', 'jperez@correo.com', '1990-03-12', 'Ciudad, Ecuador'),
(15, 'Maria', 'Gonzalez Ruiz', '0701234502', '0987654302', 'mgonzalez@correo.com', '1985-07-23', 'Ciudad, Ecuador'),
(16, 'Luis', 'Martinez Soto', '0701234503', '0987654303', 'lmartinez@correo.com', '1998-11-02', 'Ciudad, Ecuador'),
(17, 'Ana', 'Lopez Vera', '0701234504', '0987654304', 'alopez@correo.com', '1975-01-19', 'Ciudad, Ecuador'),
(18, 'Pedro', 'Sanchez Diaz', '0701234505', '0987654305', 'psanchez@correo.com', '2000-05-30', 'Ciudad, Ecuador'),
(19, 'Carmen', 'Ramirez Ortiz', '0701234506', '0987654306', 'cramirez2@correo.com', '1993-09-14', 'Ciudad, Ecuador'),
(20, 'Jose', 'Torres Leon', '0701234507', '0987654307', 'jtorres@correo.com', '1988-02-27', 'Ciudad, Ecuador'),
(21, 'Rosa', 'Flores Castillo', '0701234508', '0987654308', 'rflores@correo.com', '1979-12-05', 'Ciudad, Ecuador'),
(22, 'Manuel', 'Vargas Rios', '0701234509', '0987654309', 'mvargas@correo.com', '1995-06-18', 'Ciudad, Ecuador'),
(23, 'Isabel', 'Castro Mora', '0701234510', '0987654310', 'icastro@correo.com', '1982-04-09', 'Ciudad, Ecuador'),
(24, 'Francisco', 'Morales Pena', '0701234511', '0987654311', 'fmorales@correo.com', '1991-08-22', 'Ciudad, Ecuador'),
(25, 'Elena', 'Jimenez Cordova', '0701234512', '0987654312', 'ejimenez@correo.com', '1997-10-11', 'Ciudad, Ecuador'),
(26, 'Alberto', 'Reyes Salas', '0701234513', '0987654313', 'areyes@correo.com', '1986-03-03', 'Ciudad, Ecuador'),
(27, 'Patricia', 'Suarez Vega', '0701234514', '0987654314', 'psuarez@correo.com', '1992-07-07', 'Ciudad, Ecuador'),
(28, 'Roberto', 'Cruz Herrera', '0701234515', '0987654315', 'rcruz@correo.com', '1978-11-25', 'Ciudad, Ecuador'),
(29, 'Sandra', 'Ortega Paredes', '0701234516', '0987654316', 'sortega@correo.com', '1999-01-30', 'Ciudad, Ecuador'),
(30, 'Ricardo', 'Delgado Nunez', '0701234517', '0987654317', 'rdelgado@correo.com', '1984-05-16', 'Ciudad, Ecuador'),
(31, 'Monica', 'Aguilar Rojas', '0701234518', '0987654318', 'maguilar@correo.com', '1996-09-08', 'Ciudad, Ecuador'),
(32, 'Eduardo', 'Silva Campos', '0701234519', '0987654319', 'esilva@correo.com', '1989-12-20', 'Ciudad, Ecuador'),
(33, 'Veronica', 'Paredes Luna', '0701234520', '0987654320', 'vparedes@correo.com', '1994-02-14', 'Ciudad, Ecuador');


-- 20 Citas de ejemplo (paciente_id 1-20, doctor_id 1-5 rotativo)
INSERT INTO citas (paciente_id, doctor_id, creado_por, fecha, hora, motivo, estado) VALUES
(1, 1, 2, '2026-07-25', '08:00:00', 'Consulta general', 'pendiente'),
(2, 2, 2, '2026-07-26', '09:00:00', 'Control periodico', 'confirmada'),
(3, 3, 2, '2026-07-27', '10:00:00', 'Dolor abdominal', 'atendida'),
(4, 4, 2, '2026-07-28', '11:00:00', 'Chequeo pediatrico', 'cancelada'),
(5, 5, 2, '2026-07-29', '12:00:00', 'Control de presion arterial', 'pendiente'),
(6, 1, 2, '2026-07-30', '13:00:00', 'Revision dermatologica', 'confirmada'),
(7, 2, 2, '2026-07-31', '14:00:00', 'Consulta ginecologica', 'atendida'),
(8, 3, 2, '2026-08-01', '15:00:00', 'Seguimiento', 'cancelada'),
(9, 4, 2, '2026-08-02', '08:00:00', 'Consulta general', 'pendiente'),
(10, 5, 2, '2026-08-03', '09:00:00', 'Control periodico', 'confirmada'),
(11, 1, 2, '2026-07-25', '10:00:00', 'Dolor abdominal', 'atendida'),
(12, 2, 2, '2026-07-26', '11:00:00', 'Chequeo pediatrico', 'cancelada'),
(13, 3, 2, '2026-07-27', '12:00:00', 'Control de presion arterial', 'pendiente'),
(14, 4, 2, '2026-07-28', '13:00:00', 'Revision dermatologica', 'confirmada'),
(15, 5, 2, '2026-07-29', '14:00:00', 'Consulta ginecologica', 'atendida'),
(16, 1, 2, '2026-07-30', '15:00:00', 'Seguimiento', 'cancelada'),
(17, 2, 2, '2026-07-31', '08:00:00', 'Consulta general', 'pendiente'),
(18, 3, 2, '2026-08-01', '09:00:00', 'Control periodico', 'confirmada'),
(19, 4, 2, '2026-08-02', '10:00:00', 'Dolor abdominal', 'atendida'),
(20, 5, 2, '2026-08-03', '11:00:00', 'Chequeo pediatrico', 'cancelada');
