-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         12.3.1-MariaDB - MariaDB Server
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.14.0.7165
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para citas_medicas
CREATE DATABASE IF NOT EXISTS `citas_medicas` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `citas_medicas`;

-- Volcando estructura para tabla citas_medicas.citas
CREATE TABLE IF NOT EXISTS `citas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `paciente_id` bigint(20) unsigned NOT NULL,
  `doctor_id` bigint(20) unsigned NOT NULL,
  `creado_por` bigint(20) unsigned DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `estado` enum('pendiente','confirmada','cancelada','atendida') NOT NULL DEFAULT 'pendiente',
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_doctor_fecha_hora` (`doctor_id`,`fecha`,`hora`),
  KEY `fk_citas_paciente` (`paciente_id`),
  KEY `fk_citas_creador` (`creado_por`),
  CONSTRAINT `fk_citas_creador` FOREIGN KEY (`creado_por`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_citas_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctores` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_citas_paciente` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla citas_medicas.citas: ~22 rows (aproximadamente)
INSERT INTO `citas` (`id`, `paciente_id`, `doctor_id`, `creado_por`, `fecha`, `hora`, `motivo`, `estado`, `observaciones`, `created_at`, `updated_at`) VALUES
	(2, 2, 2, 2, '2026-07-26', '09:00:00', 'Control periodico', 'confirmada', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(3, 3, 3, 2, '2026-07-27', '10:00:00', 'Dolor abdominal', 'atendida', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(4, 4, 4, 2, '2026-07-28', '11:00:00', 'Chequeo pediatrico', 'cancelada', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(5, 5, 5, 2, '2026-07-29', '12:00:00', 'Control de presion arterial', 'pendiente', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(6, 6, 1, 2, '2026-07-30', '13:00:00', 'Revision dermatologica', 'confirmada', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(7, 7, 2, 2, '2026-07-31', '14:00:00', 'Consulta ginecologica', 'atendida', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(8, 8, 3, 2, '2026-08-01', '15:00:00', 'Seguimiento', 'cancelada', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(9, 9, 4, 2, '2026-08-02', '08:00:00', 'Consulta general', 'pendiente', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(10, 10, 5, 2, '2026-08-03', '09:00:00', 'Control periodico', 'confirmada', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(11, 11, 1, 2, '2026-07-25', '10:00:00', 'Dolor abdominal', 'atendida', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(12, 12, 2, 2, '2026-07-26', '11:00:00', 'Chequeo pediatrico', 'cancelada', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(13, 13, 3, 2, '2026-07-27', '12:00:00', 'Control de presion arterial', 'pendiente', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(14, 14, 4, 2, '2026-07-28', '13:00:00', 'Revision dermatologica', 'confirmada', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(15, 15, 5, 2, '2026-07-29', '14:00:00', 'Consulta de urología', 'atendida', NULL, '2026-07-24 16:31:31', '2026-07-25 07:42:28'),
	(16, 16, 1, 2, '2026-07-30', '15:00:00', 'Seguimiento', 'cancelada', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(17, 17, 2, 2, '2026-07-31', '08:00:00', 'Consulta general', 'pendiente', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(18, 18, 3, 2, '2026-08-01', '09:00:00', 'Control periodico', 'confirmada', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(19, 19, 4, 2, '2026-08-02', '10:00:00', 'Dolor abdominal', 'atendida', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(20, 20, 5, 2, '2026-08-03', '11:00:00', 'Chequeo pediatrico', 'cancelada', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(23, 21, 3, 34, '2026-07-24', '08:00:00', 'Dolor de cabeza', 'atendida', NULL, '2026-07-25 05:22:46', '2026-07-25 07:39:02'),
	(24, 13, 5, 1, '2026-07-28', '08:00:00', 'Consulta dermatológica general.', 'pendiente', NULL, '2026-07-25 08:04:34', '2026-07-25 08:04:34'),
	(25, 13, 3, 2, '2026-07-26', '10:00:00', 'Consulta cardiológica de rutina.', 'pendiente', NULL, '2026-07-25 08:08:59', '2026-07-25 08:08:59');

-- Volcando estructura para tabla citas_medicas.doctores
CREATE TABLE IF NOT EXISTS `doctores` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `especialidad` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `horario_inicio` time NOT NULL DEFAULT '08:00:00',
  `horario_fin` time NOT NULL DEFAULT '17:00:00',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_doctores_user` (`user_id`),
  CONSTRAINT `fk_doctores_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla citas_medicas.doctores: ~5 rows (aproximadamente)
INSERT INTO `doctores` (`id`, `user_id`, `nombre`, `apellido`, `especialidad`, `telefono`, `email`, `horario_inicio`, `horario_fin`, `created_at`, `updated_at`) VALUES
	(1, 9, 'Ricardo', 'Alvarado Pena', 'Medicina General', '0991234561', 'ralvarado@clinica.com', '08:00:00', '17:00:00', '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(2, 10, 'Sofia', 'Mendoza Cruz', 'Pediatria', '0991234562', 'smendoza@clinica.com', '08:00:00', '17:00:00', '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(3, 11, 'Miguel', 'Torres Chavez', 'Cardiologia', '0991234563', 'mtorres@clinica.com', '08:00:00', '17:00:00', '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(4, 12, 'Paola', 'Ibarra Salinas', 'Ginecologia', '0991234564', 'pibarra@clinica.com', '08:00:00', '17:00:00', '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(5, 13, 'Fernando', 'Rojas Delgado', 'Dermatologia', '0991234565', 'frojas@clinica.com', '08:00:00', '17:00:00', '2026-07-24 16:31:31', '2026-07-24 16:31:31');

-- Volcando estructura para tabla citas_medicas.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla citas_medicas.migrations: ~0 rows (aproximadamente)

-- Volcando estructura para tabla citas_medicas.pacientes
CREATE TABLE IF NOT EXISTS `pacientes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `cedula` varchar(15) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `cedula` (`cedula`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_pacientes_user` (`user_id`),
  CONSTRAINT `fk_pacientes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla citas_medicas.pacientes: ~22 rows (aproximadamente)
INSERT INTO `pacientes` (`id`, `user_id`, `nombre`, `apellido`, `cedula`, `telefono`, `email`, `fecha_nacimiento`, `direccion`, `created_at`, `updated_at`) VALUES
	(1, 14, 'Juan', 'Perez Gomez', '0701234501', '0987654301', 'jperez@correo.com', '1990-03-12', 'Ciudad, Ecuador', '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(2, 15, 'Maria', 'Gonzalez Ruiz', '0701234502', '0987654302', 'mgonzalez@correo.com', '1985-07-23', 'Ciudad, Ecuador', '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(3, 16, 'Luis', 'Martinez Soto', '0701234503', '0987654303', 'lmartinez@correo.com', '1998-11-02', 'Ciudad, Ecuador', '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(4, 17, 'Ana', 'Lopez Vera', '0701234504', '0987654304', 'alopez@correo.com', '1975-01-19', 'Ciudad, Ecuador', '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(5, 18, 'Pedro', 'Sanchez Diaz', '0701234505', '0987654305', 'psanchez@correo.com', '2000-05-30', 'Ciudad, Ecuador', '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(6, 19, 'Carmen', 'Ramirez Ortiz', '0701234506', '0987654306', 'cramirez2@correo.com', '1993-09-14', 'Ciudad, Ecuador', '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(7, 20, 'Jose', 'Torres Leon', '0701234507', '0987654307', 'jtorres@correo.com', '1988-02-27', 'Ciudad, Ecuador', '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(8, 21, 'Rosa', 'Flores Castillo', '0701234508', '0987654308', 'rflores@correo.com', '1979-12-05', 'Ciudad, Ecuador', '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(9, 22, 'Manuel', 'Vargas Rios', '0701234509', '0987654309', 'mvargas@correo.com', '1995-06-18', 'Ciudad, Ecuador', '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(10, 23, 'Isabel', 'Castro Mora', '0701234510', '0987654310', 'icastro@correo.com', '1982-04-09', 'Ciudad, Ecuador', '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(11, 24, 'Francisco', 'Morales Pena', '0701234511', '0987654311', 'fmorales@correo.com', '1991-08-22', 'Ciudad, Ecuador', '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(12, 25, 'Elena', 'Jimenez Cordova', '0701234512', '0987654312', 'ejimenez@correo.com', '1997-10-11', 'Ciudad, Ecuador', '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(13, 26, 'Alberto', 'Reyes Salas', '0701234513', '0987654313', 'areyes@correo.com', '1986-03-03', 'Ciudad, Ecuador', '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(14, 27, 'Patricia', 'Suarez Vega', '0701234514', '0987654314', 'psuarez@correo.com', '1992-07-07', 'Ciudad, Ecuador', '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(15, 28, 'Roberto', 'Cruz Herrera', '0701234515', '0987654315', 'rcruz@correo.com', '1978-11-25', 'Ciudad, Ecuador', '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(16, 29, 'Sandra', 'Ortega Paredes', '0701234516', '0987654316', 'sortega@correo.com', '1999-01-30', 'Ciudad, Ecuador', '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(17, 30, 'Ricardo', 'Delgado Nunez', '0701234517', '0987654317', 'rdelgado@correo.com', '1984-05-16', 'Ciudad, Ecuador', '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(18, 31, 'Monica', 'Aguilar Rojas', '0701234518', '0987654318', 'maguilar@correo.com', '1996-09-08', 'Ciudad, Ecuador', '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(19, 32, 'Eduardo', 'Silva Campos', '0701234519', '0987654319', 'esilva@correo.com', '1989-12-20', 'Ciudad, Ecuador', '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(20, 33, 'Veronica', 'Paredes Luna', '0701234520', '0987654320', 'vparedes@correo.com', '1994-02-14', 'Ciudad, Ecuador', '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(21, 34, 'Ana Mishelle', 'Vásquez Fariño', '0750533481', '0993635330', 'anavasquezmisho@gmail.com', '2005-08-23', NULL, '2026-07-25 04:09:16', '2026-07-25 04:09:16'),
	(22, 35, 'Eduardo Raúl', 'Vidal Zapata', '0750284788', '0969447954', 'eduardovidal@gmail.com', '2005-01-18', NULL, '2026-07-25 07:53:14', '2026-07-25 07:57:36');

-- Volcando estructura para tabla citas_medicas.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `codigo` varchar(6) NOT NULL,
  `expira_en` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla citas_medicas.password_resets: ~3 rows (aproximadamente)
INSERT INTO `password_resets` (`id`, `email`, `codigo`, `expira_en`, `created_at`, `updated_at`) VALUES
	(2, 'anavasquezmisho@gmail.com', '597386', '2026-07-25 05:05:12', '2026-07-25 04:50:12', '2026-07-25 04:50:12'),
	(3, 'anavasquezmisho@gmail.com', '546233', '2026-07-25 05:05:26', '2026-07-25 04:50:26', '2026-07-25 04:50:26'),
	(4, 'anavasquezmisho@gmail.com', '988400', '2026-07-25 05:17:44', '2026-07-25 05:02:44', '2026-07-25 05:02:44');

-- Volcando estructura para tabla citas_medicas.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','recepcionista','doctor','paciente') NOT NULL DEFAULT 'paciente' COMMENT 'Rol del usuario dentro del sistema',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla citas_medicas.users: ~35 rows (aproximadamente)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `rol`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Administrador General', 'admin@clinica.com', NULL, '$2y$12$GQ7C8XKfclW9rIgD2JCcDefM8RPAF2IiUl.FpLnlmpagW0.I4hbYe', 'admin', NULL, '2026-07-24 16:31:31', '2026-07-24 21:56:34'),
	(2, 'Maria Fernanda Lopez Torres', 'mlopez@clinica.com', NULL, '$2y$12$3PqjHMfscwQ.vtRQK8moweWTywI9FDkbfhteK/lyUFU3pvBkt0NtO', 'recepcionista', NULL, '2026-07-24 16:31:31', '2026-07-24 22:41:03'),
	(3, 'Carlos Andres Ramirez Vera', 'cramirez@clinica.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'recepcionista', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(4, 'Gabriela Isabel Suarez Mora', 'gsuarez@clinica.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'recepcionista', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(5, 'Jorge Luis Castillo Reyes', 'jcastillo@clinica.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'recepcionista', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(6, 'Andrea Paola Jimenez Cruz', 'ajimenez@clinica.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'recepcionista', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(7, 'Diego Fernando Ortiz Salas', 'dortiz@clinica.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'recepcionista', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(8, 'Valentina Cordova Pena', 'vcordova@clinica.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'recepcionista', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(9, 'Dr. Ricardo Alvarado Pena', 'ralvarado@clinica.com', NULL, '$2y$12$V8lXfiW2qQEfzYj2o8c/UOQ72nwZtZURJoE.BGhvD.RBXKANYBrnu', 'doctor', NULL, '2026-07-24 16:31:31', '2026-07-24 22:41:26'),
	(10, 'Dr. Sofia Mendoza Cruz', 'smendoza@clinica.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'doctor', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(11, 'Dr. Miguel Torres Chavez', 'mtorres@clinica.com', NULL, '$2y$12$uFlDUbeOqeyd0yds9wyNcuP70fws/JDLIvIJodKg86f1LFi8aarMq', 'doctor', NULL, '2026-07-24 16:31:31', '2026-07-25 05:29:28'),
	(12, 'Dr. Paola Ibarra Salinas', 'pibarra@clinica.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'doctor', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(13, 'Dr. Fernando Rojas Delgado', 'frojas@clinica.com', NULL, '$2y$12$KxY8xx28I6rTiD8qdAehKeD468IENd/BdygFhkd7i1sSbsaAZRXqC', 'doctor', NULL, '2026-07-24 16:31:31', '2026-07-25 05:28:57'),
	(14, 'Juan Perez Gomez', 'jperez@correo.com', NULL, '$2y$12$WhMFc.XtB8UfMjombFGvtu2nn6gjT4VF/o5DsD9iy1ZXtwjzhm4M2', 'paciente', NULL, '2026-07-24 16:31:31', '2026-07-24 22:41:52'),
	(15, 'Maria Gonzalez Ruiz', 'mgonzalez@correo.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'paciente', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(16, 'Luis Martinez Soto', 'lmartinez@correo.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'paciente', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(17, 'Ana Lopez Vera', 'alopez@correo.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'paciente', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(18, 'Pedro Sanchez Diaz', 'psanchez@correo.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'paciente', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(19, 'Carmen Ramirez Ortiz', 'cramirez2@correo.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'paciente', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(20, 'Jose Torres Leon', 'jtorres@correo.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'paciente', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(21, 'Rosa Flores Castillo', 'rflores@correo.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'paciente', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(22, 'Manuel Vargas Rios', 'mvargas@correo.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'paciente', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(23, 'Isabel Castro Mora', 'icastro@correo.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'paciente', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(24, 'Francisco Morales Pena', 'fmorales@correo.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'paciente', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(25, 'Elena Jimenez Cordova', 'ejimenez@correo.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'paciente', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(26, 'Alberto Reyes Salas', 'areyes@correo.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'paciente', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(27, 'Patricia Suarez Vega', 'psuarez@correo.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'paciente', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(28, 'Roberto Cruz Herrera', 'rcruz@correo.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'paciente', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(29, 'Sandra Ortega Paredes', 'sortega@correo.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'paciente', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(30, 'Ricardo Delgado Nunez', 'rdelgado@correo.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'paciente', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(31, 'Monica Aguilar Rojas', 'maguilar@correo.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'paciente', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(32, 'Eduardo Silva Campos', 'esilva@correo.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'paciente', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(33, 'Veronica Paredes Luna', 'vparedes@correo.com', NULL, '$2y$10$3YRnU8dTe5nxRrdH3OtxEeNx/T2DwSzMjOp715anlC1Sh9/lzwMjO', 'paciente', NULL, '2026-07-24 16:31:31', '2026-07-24 16:31:31'),
	(34, 'Ana Mishelle Vásquez Fariño', 'anavasquezmisho@gmail.com', NULL, '$2y$12$oth0CiwyF0DTV2DS8ENpdud7BqL.PTfOIBL6q0LWaB7q3XZNA.pSq', 'paciente', NULL, '2026-07-25 04:09:16', '2026-07-25 04:48:49'),
	(35, 'Eduardo Raúl Vidal Zapata', 'eduardovidal@gmail.com', NULL, '$2y$12$TUhPFCxsgWGNGjnNqjYp8.hdApsF.37qoqFUWkWx.b9umLun1tg92', 'paciente', NULL, '2026-07-25 07:53:14', '2026-07-25 07:53:14');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
