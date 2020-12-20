-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 06-10-2019 a las 18:11:12
-- Versión del servidor: 8.0.17
-- Versión de PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_is`
--
CREATE DATABASE IF NOT EXISTS `db_is` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `db_is`;

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `area_guardar`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `area_guardar` (IN `nombre` VARCHAR(45), IN `descripcion` VARCHAR(300))  BEGIN
	INSERT INTO `areas_de_preguntas`( 
		`nombre`, 
		`descripcion`, 
		`fecha_creacion`
	)
	VALUES (
		nombre,
        descripcion,
        now()
	);
END$$

DROP PROCEDURE IF EXISTS `log_guardar`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `log_guardar` (IN `log_key` INT, IN `user_key` INT, IN `usuario` VARCHAR(20), IN `detalle` MEDIUMTEXT, IN `llave` INT, IN `tabla` VARCHAR(60), IN `accion` MEDIUMTEXT, IN `comando` MEDIUMTEXT, IN `ip` VARCHAR(20))  BEGIN
	INSERT INTO `seglog`(
		`SegLogKey`,
        `SegLogFecha`, 
        `SegLogHora`, 
        `SegUsrKey`, 
        `SegUsrUsuario`, 
        `SegLogDetalle`, 
        `SegLogLlave`, 
        `SegLogTabla`, 
        `SegLogAccion`, 
        `SegLogComando`, 
        `SegLogIp`
	) VALUES (
		log_key,
        now(),
        time,
        user_key,
        usuario,
        detalle,
        llave,
        tabla,
        accion,
        comando,
        ip);
END$$

DROP PROCEDURE IF EXISTS `log_usuarios_guardar`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `log_usuarios_guardar` (IN `id_personal` INT, IN `id_respuesta` INT, IN `id_area` INT, IN `id_pregunta` INT, IN `id_turno` INT, IN `ip` VARCHAR(20), IN `es_pregunta_fltro` TINYINT)  BEGIN
	INSERT INTO `log_usuarios`(
    `id_personal`, 
    `id_respuesta`, 
    `id_area`, 
    `id_pregunta`, 
    `id_turno`,
    `ip`,
    `es_pregunta_filtro`,
    `fecha_creacion`) 
    VALUES (
    id_personal,
    id_respuesta,
    id_area,
    id_pregunta,
    id_turno,
    ip,
    es_pregunta_fltro,
    now());
END$$

DROP PROCEDURE IF EXISTS `perdidas_de_contrasena_guardar`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `perdidas_de_contrasena_guardar` (IN `ip` VARCHAR(20))  BEGIN
	INSERT INTO `perdidas_de_contrasena`(
			`ip`, 
            `fecha_creacion`
	) VALUES (
			ip,
            now()
	);
END$$

DROP PROCEDURE IF EXISTS `pregunta_crear`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `pregunta_crear` (IN `id_area` INT, IN `id_tipo` INT, IN `contenido` VARCHAR(100))  BEGIN
	INSERT INTO `preguntas`(
		`id_area`, 
        `id_tipo`,
        `contenido`, 
        `fecha_creacion`
	) VALUES (
		id_area,
        id_tipo,
        contenido,
        now());
END$$

DROP PROCEDURE IF EXISTS `preg_filtro_crear`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `preg_filtro_crear` (IN `pregunta` VARCHAR(60))  BEGIN
	INSERT INTO `pregunta_filtro`(
		`pregunta`,
        `fecha_creacion`,
        `fecha_modificacion`
    )
    VALUES(
		pregunta,
        now(),
        now()
    );
END$$

DROP PROCEDURE IF EXISTS `preg_filtro_modificar`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `preg_filtro_modificar` (IN `pregunta` VARCHAR(60))  BEGIN
	INSERT INTO `pregunta_filtro`(
		`pregunta`,
        `fecha_modificacion`
    )
    VALUES(
		pregunta,
        now()
    );
END$$

DROP PROCEDURE IF EXISTS `reg_entrada_guardar`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `reg_entrada_guardar` (IN `nombreUsuario` VARCHAR(255), IN `llaveAutorizacion` VARCHAR(32), IN `hashContrasena` VARCHAR(255), IN `correo` VARCHAR(255), IN `estado` SMALLINT(6), IN `token` VARCHAR(255))  BEGIN
INSERT INTO `user`(
	`username`, 
    `auth_key`, 
    `password_hash`, 
	`email`, 
    `status`, 
    `created_at`, 
	`updated_at`,
    `verification_token`
) 
VALUES (
	nombreUsuario,
    llaveAutorizacion,
    hashContrasena,
	correo,
    estado,
    unix_timestamp(),
    unix_timestamp(),
    token
);
END$$

DROP PROCEDURE IF EXISTS `seglog_guardar`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `seglog_guardar` (IN `noEmpleado` INT, IN `nombre` VARCHAR(20), IN `accion` MEDIUMTEXT, IN `tabla` VARCHAR(60), IN `accionTabla` MEDIUMTEXT, IN `comando` MEDIUMTEXT, IN `ip` VARCHAR(20))  BEGIN
	INSERT INTO `seglog`(
                    `SegLogFecha`, 
                    `SegLogHora`, 
                    `SegUsrKey`, 
                    `SegUsrUsuario`, 
                    `SegLogDetalle`, 
                    `SegLogLlave`, 
                    `SegLogTabla`, 
                    `SegLogAccion`, 
                    `SegLogComando`, 
                    `SegLogIp`
				) 
		VALUES (
			now(),
            CURRENT_TIME(),
            noEmpleado,
            nombre,
            accion,
            noEmpleado,
            tabla,
            accionTabla,
            comando,
            ip
		);
END$$

DROP PROCEDURE IF EXISTS `usuarios_guardar`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `usuarios_guardar` (IN `idPersonal` INT, IN `idTurno` INT, IN `idPosicion` INT, IN `email` VARCHAR(45), IN `contrasena` VARCHAR(200))  BEGIN
	INSERT INTO `usuarios`(
		`id_personal`, 
		`id_turno`, 
		`id_posicion`, 
		`email`, 
		`contrasena`, 
		`fecha_creacion`
	) VALUES (
		idPersonal,
		idTurno,
		idPosicion,
		email,
		contrasena,
		now()
	);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas_de_preguntas`
--

DROP TABLE IF EXISTS `areas_de_preguntas`;
CREATE TABLE `areas_de_preguntas` (
  `id_area` int(6) NOT NULL,
  `nombre` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `descripcion` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `fecha_creacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `areas_de_preguntas`
--

INSERT INTO `areas_de_preguntas` (`id_area`, `nombre`, `descripcion`, `fecha_creacion`) VALUES
(1, 'Enfermedad', 'Acerca del estado fisico', NULL),
(2, 'Automedicación', 'Uso de medicamentos', NULL),
(3, 'Estado de animo', 'Acerca de problemas psicologicos', NULL),
(4, 'Fatiga', 'Causas que generan fatiga', NULL),
(5, 'Alimentación', 'Confirmar niveles de energia', NULL),
(6, 'Matemáticas', 'Problemas fáciles', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_usuarios`
--

DROP TABLE IF EXISTS `log_usuarios`;
CREATE TABLE `log_usuarios` (
  `id_log_usuario` int(11) NOT NULL,
  `id_personal` int(3) NOT NULL,
  `id_respuesta` int(6) NOT NULL,
  `id_area` int(6) DEFAULT NULL,
  `id_pregunta` int(6) DEFAULT NULL,
  `id_turno` int(3) DEFAULT NULL,
  `ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `es_pregunta_filtro` tinyint(4) DEFAULT '0',
  `fecha_creacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `log_usuarios`
--

INSERT INTO `log_usuarios` (`id_log_usuario`, `id_personal`, `id_respuesta`, `id_area`, `id_pregunta`, `id_turno`, `ip`, `es_pregunta_filtro`, `fecha_creacion`) VALUES
(1, 2, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-09-16 00:05:11'),
(2, 2, 2, 5, 15, NULL, '127.0.0.1', 0, '2019-09-28 00:05:17'),
(3, 2, 6, 6, 16, NULL, '127.0.0.1', 0, '2019-09-29 00:05:24'),
(4, 2, 2, 6, 17, NULL, '127.0.0.1', 0, '2019-09-29 00:05:24'),
(5, 3, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-09-30 00:11:28'),
(6, 3, 1, 1, 1, NULL, '127.0.0.1', 0, '2019-09-30 00:11:36'),
(7, 3, 2, 1, 2, NULL, '127.0.0.1', 0, '2019-09-30 00:11:39'),
(8, 3, 2, 1, 3, NULL, '127.0.0.1', 0, '2019-09-30 00:11:43'),
(9, 3, 2, 1, 4, NULL, '127.0.0.1', 0, '2019-09-30 00:11:47'),
(10, 2, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-09-30 12:17:46'),
(11, 2, 1, 4, 11, NULL, '127.0.0.1', 0, '2019-09-30 12:17:53'),
(12, 2, 1, 4, 12, NULL, '127.0.0.1', 0, '2019-09-30 12:17:57'),
(13, 2, 2, 4, 13, NULL, '127.0.0.1', 0, '2019-09-30 12:18:00'),
(14, 2, 1, 4, 14, NULL, '127.0.0.1', 0, '2019-09-30 12:18:03'),
(15, 2, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-09-30 12:21:23'),
(16, 2, 6, 6, 16, NULL, '127.0.0.1', 0, '2019-09-30 12:21:30'),
(17, 2, 2, 6, 17, NULL, '127.0.0.1', 0, '2019-10-01 12:21:33'),
(18, 8, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-09-30 12:53:54'),
(19, 8, 2, 2, 5, NULL, '127.0.0.1', 0, '2019-09-30 12:54:04'),
(20, 8, 2, 2, 6, NULL, '127.0.0.1', 0, '2019-09-30 12:54:07'),
(21, 8, 2, 2, 7, NULL, '127.0.0.1', 0, '2019-09-30 12:54:11'),
(22, 10, 1, NULL, NULL, NULL, '127.0.0.1', 1, '2019-09-30 13:24:25'),
(23, 2, 1, NULL, NULL, NULL, '127.0.0.1', 1, '2019-09-30 13:45:46'),
(24, 3, 1, NULL, NULL, NULL, '127.0.0.1', 1, '2019-09-30 14:04:11'),
(25, 3, 1, NULL, NULL, NULL, '127.0.0.1', 1, '2019-09-30 14:05:17'),
(26, 3, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-09-30 14:13:11'),
(27, 3, 6, 6, 16, NULL, '127.0.0.1', 0, '2019-09-30 14:13:17'),
(28, 3, 2, 6, 17, NULL, '127.0.0.1', 0, '2019-09-30 14:13:19'),
(29, 3, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-09-30 14:15:41'),
(30, 3, 2, 1, 1, NULL, '127.0.0.1', 0, '2019-09-30 14:15:44'),
(31, 3, 1, 1, 2, NULL, '127.0.0.1', 0, '2019-09-30 14:15:46'),
(32, 3, 2, 1, 3, NULL, '127.0.0.1', 0, '2019-09-30 14:15:48'),
(33, 3, 2, 1, 4, NULL, '127.0.0.1', 0, '2019-09-30 14:15:49'),
(34, 3, 1, 3, 8, NULL, '127.0.0.1', 0, '2019-09-30 14:16:16'),
(35, 3, 2, 3, 9, NULL, '127.0.0.1', 0, '2019-09-30 14:16:19'),
(36, 3, 1, 3, 10, NULL, '127.0.0.1', 0, '2019-09-30 14:16:20'),
(37, 2, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-10-03 11:54:51'),
(38, 2, 1, 4, 11, NULL, '127.0.0.1', 0, '2019-10-03 11:54:57'),
(39, 2, 1, 4, 12, NULL, '127.0.0.1', 0, '2019-10-03 11:55:03'),
(40, 2, 1, 4, 13, NULL, '127.0.0.1', 0, '2019-10-03 11:55:06'),
(41, 2, 1, 4, 14, NULL, '127.0.0.1', 0, '2019-10-03 11:55:09'),
(42, 2, 3, 6, 16, NULL, '127.0.0.1', 0, '2019-10-03 12:01:45'),
(43, 2, 2, 6, 17, NULL, '127.0.0.1', 0, '2019-10-03 12:01:47'),
(44, 8, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-10-03 12:19:39'),
(45, 8, 2, 4, 11, NULL, '127.0.0.1', 0, '2019-10-03 12:19:51'),
(46, 8, 1, 4, 12, NULL, '127.0.0.1', 0, '2019-10-03 12:19:53'),
(47, 8, 1, 4, 13, NULL, '127.0.0.1', 0, '2019-10-03 12:19:55'),
(48, 8, 2, 4, 14, NULL, '127.0.0.1', 0, '2019-10-03 12:19:56'),
(49, 2, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-10-03 12:24:03'),
(50, 2, 3, 6, 16, NULL, '127.0.0.1', 0, '2019-10-03 12:24:07'),
(51, 2, 2, 6, 17, NULL, '127.0.0.1', 0, '2019-10-03 12:24:09'),
(52, 8, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-10-03 12:26:29'),
(53, 8, 4, 6, 16, NULL, '127.0.0.1', 0, '2019-10-03 12:26:37'),
(54, 8, 2, 6, 17, NULL, '127.0.0.1', 0, '2019-10-03 12:26:40'),
(55, 2, 1, NULL, NULL, NULL, '127.0.0.1', 1, '2019-10-04 18:14:35'),
(56, 2, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-10-04 23:13:25'),
(57, 2, 2, 1, 1, NULL, '127.0.0.1', 0, '2019-10-04 23:13:33'),
(58, 2, 1, 1, 2, NULL, '127.0.0.1', 0, '2019-10-04 23:13:35'),
(59, 2, 2, 1, 3, NULL, '127.0.0.1', 0, '2019-10-04 23:13:37'),
(60, 2, 2, 1, 4, NULL, '127.0.0.1', 0, '2019-10-04 23:13:41'),
(61, 2, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-10-05 12:57:02'),
(62, 2, 2, 2, 5, NULL, '127.0.0.1', 0, '2019-10-05 12:57:14'),
(63, 2, 2, 2, 6, NULL, '127.0.0.1', 0, '2019-10-05 12:57:15'),
(64, 2, 2, 2, 7, NULL, '127.0.0.1', 0, '2019-10-05 12:57:16'),
(65, 2, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-10-05 13:04:52'),
(66, 2, 7, 6, 16, NULL, '127.0.0.1', 0, '2019-10-05 13:14:07'),
(67, 2, 2, 6, 17, NULL, '127.0.0.1', 0, '2019-10-05 13:14:09'),
(68, 5, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-10-05 13:17:09'),
(69, 5, 7, 6, 16, NULL, '127.0.0.1', 0, '2019-10-05 13:25:08'),
(70, 5, 2, 6, 17, NULL, '127.0.0.1', 0, '2019-10-05 13:25:10'),
(71, 2, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-10-05 13:30:19'),
(72, 2, 1, 4, 11, NULL, '127.0.0.1', 0, '2019-10-05 13:30:25'),
(73, 2, 2, 4, 12, NULL, '127.0.0.1', 0, '2019-10-05 13:30:27'),
(74, 2, 1, 4, 13, NULL, '127.0.0.1', 0, '2019-10-05 13:30:28'),
(75, 2, 2, 4, 14, NULL, '127.0.0.1', 0, '2019-10-05 13:30:29'),
(76, 5, 1, NULL, NULL, NULL, '127.0.0.1', 1, '2019-10-06 00:37:30'),
(77, 5, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-10-06 00:38:19'),
(78, 5, 2, 4, 11, NULL, '127.0.0.1', 0, '2019-10-06 00:38:22'),
(79, 5, 2, 4, 12, NULL, '127.0.0.1', 0, '2019-10-06 00:38:23'),
(80, 5, 2, 4, 13, NULL, '127.0.0.1', 0, '2019-10-06 00:38:25'),
(81, 5, 2, 4, 14, NULL, '127.0.0.1', 0, '2019-10-06 00:38:27'),
(82, 5, 1, NULL, NULL, NULL, '127.0.0.1', 1, '2019-10-06 00:42:23'),
(83, 5, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-10-06 00:56:49'),
(84, 5, 2, 5, 15, NULL, '127.0.0.1', 0, '2019-10-06 00:56:53'),
(85, 2, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-10-06 01:00:57'),
(86, 2, 1, 5, 15, NULL, '127.0.0.1', 0, '2019-10-06 01:01:01'),
(87, 2, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-10-06 01:01:58'),
(88, 2, 1, 5, 15, NULL, '127.0.0.1', 0, '2019-10-06 01:02:01'),
(89, 5, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-10-06 14:41:58'),
(90, 5, 2, 1, 1, NULL, '127.0.0.1', 0, '2019-10-06 14:42:02'),
(91, 5, 1, 1, 2, NULL, '127.0.0.1', 0, '2019-10-06 14:42:04'),
(92, 5, 2, 1, 3, NULL, '127.0.0.1', 0, '2019-10-06 14:42:06'),
(93, 5, 1, 1, 4, NULL, '127.0.0.1', 0, '2019-10-06 14:42:08'),
(94, 5, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-10-06 15:17:38'),
(95, 5, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-10-06 15:46:25'),
(96, 5, 1, NULL, NULL, NULL, '127.0.0.1', 1, '2019-10-06 15:46:39'),
(97, 2, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-10-06 16:03:39'),
(98, 2, 1, 4, 11, NULL, '127.0.0.1', 0, '2019-10-06 16:03:49'),
(99, 2, 1, 4, 12, NULL, '127.0.0.1', 0, '2019-10-06 16:03:51'),
(100, 2, 2, 4, 13, NULL, '127.0.0.1', 0, '2019-10-06 16:03:54'),
(101, 2, 1, 4, 14, NULL, '127.0.0.1', 0, '2019-10-06 16:03:56'),
(102, 5, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-10-06 16:05:29'),
(103, 5, 4, 6, 16, NULL, '127.0.0.1', 0, '2019-10-06 16:13:03'),
(104, 5, 1, 6, 17, NULL, '127.0.0.1', 0, '2019-10-06 16:13:05'),
(105, 2, 2, NULL, NULL, NULL, '127.0.0.1', 1, '2019-10-06 17:02:04'),
(106, 2, 2, 4, 11, NULL, '127.0.0.1', 0, '2019-10-06 17:02:22'),
(107, 2, 2, 4, 12, NULL, '127.0.0.1', 0, '2019-10-06 17:02:24'),
(108, 2, 2, 4, 13, NULL, '127.0.0.1', 0, '2019-10-06 17:02:26'),
(109, 2, 2, 4, 14, NULL, '127.0.0.1', 0, '2019-10-06 17:02:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perdidas_de_contrasena`
--

DROP TABLE IF EXISTS `perdidas_de_contrasena`;
CREATE TABLE `perdidas_de_contrasena` (
  `id_perdida` int(11) NOT NULL,
  `ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `perdidas_de_contrasena`
--

INSERT INTO `perdidas_de_contrasena` (`id_perdida`, `ip`, `fecha_creacion`) VALUES
(1, '127.0.0.1', '2019-09-30 14:16:34'),
(2, '127.0.0.1', '2019-10-04 18:08:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal`
--

DROP TABLE IF EXISTS `personal`;
CREATE TABLE `personal` (
  `id_personal` int(3) NOT NULL,
  `nombres` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `apellidos` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `sexo` enum('M','F') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `no_empleado` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `personal`
--

INSERT INTO `personal` (`id_personal`, `nombres`, `apellidos`, `fecha_nacimiento`, `fecha_ingreso`, `sexo`, `no_empleado`, `activo`) VALUES
(1, 'Administrador', 'del sistema', '1988-12-07', '1991-12-10', 'F', '111', 1),
(2, 'Controlador de ', 'Trafico aéreo', '1985-10-01', '2010-03-11', 'M', '222', 1),
(3, 'Supervisor', 'del sistema', '1982-04-25', '2018-09-25', 'M', '333', 1),
(4, 'RRHH', 'sistema', '2015-05-23', '1970-06-30', 'M', '444', 1),
(5, 'qui minus', 'sit molestiae', '1982-01-06', '2015-05-14', 'M', '125', 1),
(6, 'vel cum', 'id enim', '2012-10-10', '1999-03-10', 'F', '134', 1),
(7, 'autem nihil', 'et error', '1992-01-28', '2007-02-17', 'F', '161', 1),
(8, 'necessitatibus sunt', 'quia aliquam', '1986-08-29', '1978-11-28', 'F', '458', 1),
(9, 'illo minima', 'maiores cumque', '1970-02-13', '2004-05-13', 'M', '501', 1),
(10, 'inventore similique', 'quia illum', '1989-09-13', '1975-05-14', 'M', '669', 1),
(11, 'qui tempora', 'eos molestiae', '1996-11-03', '1993-04-29', 'F', '627', 1),
(12, 'recusandae est', 'consequatur aliquam', '1986-08-14', '2001-09-20', 'F', '651', 1),
(13, 'rerum iure', 'voluptas voluptatem', '1986-05-14', '2001-02-06', 'M', '770', 1),
(14, 'esse quod', 'voluptas natus', '1979-10-15', '2009-09-05', 'F', '24', 1),
(15, 'rem recusandae', 'exercitationem illum', '1994-04-06', '1988-06-20', 'M', '543', 1),
(16, 'sapiente saepe', 'distinctio qui', '2010-06-17', '2005-02-01', 'M', '341', 1),
(17, 'molestiae nesciunt', 'debitis rerum', '2000-08-19', '1979-05-07', 'M', '663', 1),
(18, 'qui sit', 'incidunt deleniti', '2009-09-15', '2008-05-21', 'F', '862', 1),
(19, 'fugit illo', 'eius ad', '2006-04-21', '1979-06-01', 'F', '810', 1),
(20, 'exercitationem aut', 'rerum perspiciatis', '2017-03-24', '2005-01-16', 'F', '723', 1),
(21, 'minus maiores', 'vel enim', '2007-09-01', '1974-02-24', 'M', '837', 1),
(22, 'magni esse', 'consequatur iste', '2013-06-16', '1981-06-03', 'F', '968', 1),
(23, 'necessitatibus nam', 'pariatur commodi', '1981-05-28', '2000-10-16', 'M', '859', 1),
(24, 'dolores aut', 'mollitia at', '1992-04-19', '1982-02-01', 'M', '177', 1),
(25, 'omnis necessitatibus', 'impedit sapiente', '1971-06-01', '1979-01-16', 'M', '851', 1),
(26, 'velit quaerat', 'neque repellendus', '2001-09-11', '1988-08-10', 'F', '135', 1),
(27, 'nesciunt dignissimos', 'quisquam quia', '2014-11-04', '1975-07-04', 'F', '875', 1),
(28, 'quos expedita', 'nulla nobis', '1997-06-22', '1977-01-03', 'F', '827', 1),
(29, 'eaque rerum', 'qui rerum', '2013-04-09', '1991-06-04', 'F', '912', 1),
(30, 'itaque et', 'sapiente quibusdam', '1991-11-13', '1980-09-26', 'M', '3', 1),
(31, 'itaque qui', 'et consequatur', '1986-08-10', '2007-01-29', 'M', '195', 1),
(32, 'mollitia dolorem', 'dolorum ut', '2005-02-10', '1985-10-05', 'F', '151', 1),
(33, 'sapiente veritatis', 'nesciunt esse', '1976-07-16', '1982-04-20', 'M', '754', 1),
(34, 'ut ea', 'sequi in', '1971-10-12', '1981-08-05', 'F', '21', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `posicion`
--

DROP TABLE IF EXISTS `posicion`;
CREATE TABLE `posicion` (
  `id_posicion` int(3) NOT NULL,
  `posicion` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `posicion`
--

INSERT INTO `posicion` (`id_posicion`, `posicion`) VALUES
(1, 'Administrador'),
(2, 'Controlador'),
(3, 'Supervisor'),
(4, 'RRHH');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

DROP TABLE IF EXISTS `preguntas`;
CREATE TABLE `preguntas` (
  `id_pregunta` int(6) NOT NULL,
  `id_area` int(6) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `contenido` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `fecha_creacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `preguntas`
--

INSERT INTO `preguntas` (`id_pregunta`, `id_area`, `id_tipo`, `contenido`, `fecha_creacion`) VALUES
(1, 1, 1, '¿Tengo algún malestar fisico?', NULL),
(2, 1, 1, '¿Tengo algún dolor?', NULL),
(3, 1, 1, '¿Tengo algún sintoma?', NULL),
(4, 1, 1, '¿Representa ese sintoma alguna enfermedad?', NULL),
(5, 2, 1, '¿Me encuentro usando algún medicamento autorecetado?', NULL),
(6, 2, 1, '¿Me encuentro usando algún medicamento recomendado por un amigo?', NULL),
(7, 2, 1, '¿He vuelto a tomar algún medicamento si consultar a un especialista?', NULL),
(8, 3, 1, '¿Me siento bajo presión psicologica?', NULL),
(9, 3, 1, '¿Siento que tengo problemas en mi ambiente laboral?', NULL),
(10, 3, 1, '¿Siento que tengo problemas personales?', NULL),
(11, 4, 1, '¿Estoy cansado?', NULL),
(12, 4, 1, '¿Tengo sueño constantemente?', NULL),
(13, 4, 1, '¿Tengo necesidad constantemente de acostarme o recostarme?', NULL),
(14, 4, 1, '¿Siento que todo me cuesta el doble?', NULL),
(15, 5, 1, '¿He comido en los horarios correspondientes hoy?', NULL),
(16, 6, 2, '¿2 + 2?', NULL),
(17, 6, 1, '¿2 = 5?', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pregunta_filtro`
--

DROP TABLE IF EXISTS `pregunta_filtro`;
CREATE TABLE `pregunta_filtro` (
  `id_pregunta` int(11) NOT NULL,
  `pregunta` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `fecha_creacion` date NOT NULL,
  `fecha_modificacion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `pregunta_filtro`
--

INSERT INTO `pregunta_filtro` (`id_pregunta`, `pregunta`, `fecha_creacion`, `fecha_modificacion`) VALUES
(1, '¿Estoy en forma para realizar el turno?', '2019-09-29', '2019-09-29'),
(2, '¿Estoy en forma para realizar el turno?d', '2019-10-04', '2019-10-04'),
(3, '¿Estoy en forma para realizar el turno?', '2019-10-05', '2019-10-05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `razones`
--

DROP TABLE IF EXISTS `razones`;
CREATE TABLE `razones` (
  `id_razon` int(11) NOT NULL,
  `razon` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `razones`
--

INSERT INTO `razones` (`id_razon`, `razon`, `fecha_creacion`) VALUES
(6, 's', '2019-10-06 00:03:30'),
(7, 's', '2019-10-06 00:03:35'),
(8, 's', '2019-10-06 00:04:02'),
(9, 's', '2019-10-06 00:04:06'),
(10, 'zzz', '2019-10-06 00:37:24'),
(11, 'Esta muy enfermo', '2019-10-06 00:38:17'),
(12, 'asd', '2019-10-06 00:42:12'),
(50, 'asd', '2019-10-06 15:48:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas`
--

DROP TABLE IF EXISTS `respuestas`;
CREATE TABLE `respuestas` (
  `id_respuesta` int(6) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `contenido` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `fecha_creacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `respuestas`
--

INSERT INTO `respuestas` (`id_respuesta`, `id_tipo`, `contenido`, `fecha_creacion`) VALUES
(1, 1, 'si', NULL),
(2, 1, 'no', NULL),
(3, 2, '1', NULL),
(4, 2, '2', NULL),
(5, 2, '3', NULL),
(6, 2, '4', NULL),
(7, 2, '5', NULL),
(8, 3, 'bajo', NULL),
(9, 3, 'medio', NULL),
(10, 3, 'alto', NULL),
(11, 4, 'muy bien', NULL),
(12, 4, 'bien', NULL),
(13, 4, 'regular', NULL),
(14, 4, 'mal', NULL),
(15, 4, 'muy mal', NULL),
(16, 5, 'mucho', NULL),
(17, 5, 'medio', NULL),
(18, 5, 'poco', NULL),
(19, 5, 'muy poco', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seglog`
--

DROP TABLE IF EXISTS `seglog`;
CREATE TABLE `seglog` (
  `SegLogKey` int(11) NOT NULL,
  `SegLogFecha` date DEFAULT NULL,
  `SegLogHora` time DEFAULT NULL,
  `SegUsrKey` int(11) DEFAULT NULL,
  `SegUsrUsuario` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `SegLogDetalle` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `SegLogLlave` int(11) DEFAULT NULL,
  `SegLogTabla` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `SegLogAccion` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `SegLogComando` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `SegLogIp` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `seglog`
--

INSERT INTO `seglog` (`SegLogKey`, `SegLogFecha`, `SegLogHora`, `SegUsrKey`, `SegUsrUsuario`, `SegLogDetalle`, `SegLogLlave`, `SegLogTabla`, `SegLogAccion`, `SegLogComando`, `SegLogIp`) VALUES
(1, '2019-09-30', '00:05:31', 222, '0', 'Enviar correos', 222, 'usuarios', 'Se envió por correo los resultados de la encuesta del usuario con Numero de empleado 222', 'SEND', '127.0.0.1'),
(2, '2019-09-30', '00:11:50', 333, '0', 'Enviar correos', 333, 'usuarios', 'Se envió por correo los resultados de la encuesta del usuario con Numero de empleado 333', 'SEND', '127.0.0.1'),
(3, '2019-09-30', '12:18:06', 222, '0', 'Enviar correos', 222, 'usuarios', 'Se envió por correo los resultados de la encuesta del usuario con Numero de empleado 222', 'SEND', '127.0.0.1'),
(4, '2019-09-30', '12:21:35', 222, '0', 'Enviar correos', 222, 'usuarios', 'Se envió por correo los resultados de la encuesta del usuario con Numero de empleado 222', 'SEND', '127.0.0.1'),
(5, '2019-09-30', '12:53:35', 458, '0', 'Actualizar usuario', 458, 'usuarios', 'Actualizar usuario 458 usando el id_posicion \"2\" y el email \"yost.favian@ullrich.com\"', 'UPDATE', '127.0.0.1'),
(6, '2019-09-30', '12:54:16', 458, '0', 'Enviar correos', 458, 'usuarios', 'Se envió por correo los resultados de la encuesta del usuario con Numero de empleado 458', 'SEND', '127.0.0.1'),
(7, '2019-09-30', '13:17:42', 111, '0', 'Actualizar usuario', 111, 'usuarios', 'Actualizar usuario 669 usando el id_posicion \"2\" y el email \"waelchi.retta@gmail.com\"', 'UPDATE', '127.0.0.1'),
(8, '2019-09-30', '13:19:09', 111, '0', 'Actualizar usuario', 111, 'usuarios', 'Actualizar usuario 669 usando el id_posicion \"1\" y el email \"waelchi.retta@gmail.com\"', 'UPDATE', '127.0.0.1'),
(9, '2019-09-30', '13:19:25', 111, '0', 'Actualizar usuario', 111, 'usuarios', 'Actualizar usuario 669 usando el id_posicion \"2\" y el email \"waelchi.retta@gmail.com\"', 'UPDATE', '127.0.0.1'),
(10, '2019-09-30', '13:21:59', 111, '0', 'Actualizar usuario', 111, 'usuarios', 'Actualizar usuario 669 usando el id_posicion \"1\" y el email \"waelchi.retta@gmail.com\"', 'UPDATE', '127.0.0.1'),
(11, '2019-09-30', '13:22:19', 111, '0', 'Actualizar usuario', 111, 'usuarios', 'Actualizar usuario 669 usando el id_posicion \"2\" y el email \"waelchi.retta@gmail.com\"', 'UPDATE', '127.0.0.1'),
(12, '2019-09-30', '13:23:20', 111, '0', 'Actualizar usuario', 111, 'usuarios', 'Actualizar usuario 669 usando el id_posicion \"1\" y el email \"waelchi.retta@gmail.com\"', 'UPDATE', '127.0.0.1'),
(13, '2019-09-30', '13:23:55', 111, '0', 'Actualizar usuario', 111, 'usuarios', 'Actualizar usuario 669 usando el id_posicion \"2\" y el email \"waelchi.retta@gmail.com\"', 'UPDATE', '127.0.0.1'),
(14, '2019-09-30', '14:01:42', 111, '0', 'Eliminar usuario', 111, 'usuarios', 'Eliminar el usuario con no_empleado \"501\"', 'DELETE', '127.0.0.1'),
(15, '2019-09-30', '14:13:23', 333, '0', 'Enviar correos', 333, 'usuarios', 'Se envió por correo los resultados de la encuesta del usuario con Numero de empleado 333', 'SEND', '127.0.0.1'),
(16, '2019-09-30', '14:16:25', 333, '0', 'Enviar correos', 333, 'usuarios', 'Se envió por correo los resultados de la encuesta del usuario con Numero de empleado 333', 'SEND', '127.0.0.1'),
(17, '2019-10-03', '10:10:47', 111, '0', 'Eliminar usuario', 111, 'usuarios', 'Eliminar el usuario con no_empleado \"333\"', 'DELETE', '127.0.0.1'),
(18, '2019-10-03', '11:51:02', 111, '0', 'Nueva area', 111, 'areas_de_preguntas', 'area_guardar', 'INSERT', '127.0.0.1'),
(19, '2019-10-03', '11:51:25', 111, '0', 'Nueva pregunta', 111, 'preguntas', 'pregunta_guardar(?,?)', 'INSERT', '127.0.0.1'),
(20, '2019-10-03', '11:51:33', 111, '0', 'Actualizar pregunta', 111, 'preguntas', 'Actualizar pregunta 18 usando el contenido \"que es un hola mundo????\" y el tipo \"2\"', 'UPDATE', '127.0.0.1'),
(21, '2019-10-03', '11:51:37', 111, '0', 'Borrar pregunta', 111, 'preguntas', 'Borra la pregunta 18', 'DELETE', '127.0.0.1'),
(22, '2019-10-03', '11:51:42', 111, '0', 'Borrar area', 111, 'areas_de_preguntas', 'Borra el area7', 'DELETE', '127.0.0.1'),
(23, '2019-10-03', '11:52:12', 111, '0', 'Actualizar usuario', 111, 'usuarios', 'Actualizar usuario 111 usando el id_posicion \"1\" y el email \"el@administrador.pP\"', 'UPDATE', '127.0.0.1'),
(24, '2019-10-03', '12:24:16', 222, '0', 'Enviar correos', 222, 'usuarios', 'Se envió por correo los resultados de la encuesta del usuario con Numero de empleado 222', 'SEND', '127.0.0.1'),
(25, '2019-10-03', '12:26:48', 458, '0', 'Enviar correos', 458, 'usuarios', 'Se envió por correo los resultados de la encuesta del usuario con Numero de empleado 458', 'SEND', '127.0.0.1'),
(26, '2019-10-04', '18:09:09', 111, '0', 'Actualizar pregunta filtro', 111, 'pregunta_filtro', 'Actualizar pregunta filtro a: ¿Estoy en forma para realizar el turno?d', 'UPDATE', '127.0.0.1'),
(27, '2019-10-04', '21:10:01', 111, '0', 'Actualizar usuario', 111, 'usuarios', 'Actualizar usuario 111 usando el id_posicion \"1\" y el email \"el@administrador.p\"', 'UPDATE', '127.0.0.1'),
(28, '2019-10-04', '21:11:09', 111, '0', 'Nueva area', 111, 'areas_de_preguntas', 'area_guardar', 'INSERT', '127.0.0.1'),
(29, '2019-10-04', '21:11:24', 111, '0', 'Borrar area', 111, 'areas_de_preguntas', 'Borra el area8', 'DELETE', '127.0.0.1'),
(30, '2019-10-04', '23:25:55', 222, '0', 'Enviar correos', 222, 'usuarios', 'Se envió por correo los resultados de la encuesta del usuario con Numero de empleado 222', 'SEND', '127.0.0.1'),
(31, '2019-10-05', '12:57:20', 222, '0', 'Enviar correos', 222, 'usuarios', 'Se envió por correo los resultados de la encuesta del usuario con Numero de empleado 222', 'SEND', '127.0.0.1'),
(32, '2019-10-05', '12:58:40', 111, '0', 'Actualizar pregunta filtro', 111, 'pregunta_filtro', 'Actualizar pregunta filtro a: ¿Estoy en forma para realizar el turno?', 'UPDATE', '127.0.0.1'),
(33, '2019-10-05', '13:14:16', 222, '0', 'Enviar correos', 222, 'usuarios', 'Se envió por correo los resultados de la encuesta del usuario con Numero de empleado 222', 'SEND', '127.0.0.1'),
(34, '2019-10-05', '13:15:47', 111, '0', 'Actualizar usuario', 111, 'usuarios', 'Actualizar usuario 125 usando el id_posicion \"2\" y el email \"carter.karianne@gmail.com\"', 'UPDATE', '127.0.0.1'),
(35, '2019-10-05', '13:25:14', 125, '0', 'Enviar correos', 125, 'usuarios', 'Se envió por correo los resultados de la encuesta del usuario con Numero de empleado 125', 'SEND', '127.0.0.1'),
(36, '2019-10-05', '13:30:35', 222, '0', 'Enviar correos', 222, 'usuarios', 'Se envió por correo los resultados de la encuesta del usuario con Numero de empleado 222', 'SEND', '127.0.0.1'),
(37, '2019-10-05', '19:52:51', 111, 'dolor nobis', 'Nueva area', 111, 'areas_de_preguntas', 'area_guardar', 'INSERT', '127.0.0.1'),
(38, '2019-10-05', '20:04:44', 111, 'dolor nobis', 'Borrar area', 111, 'areas_de_preguntas', 'Borra el area9', 'DELETE', '127.0.0.1'),
(39, '2019-10-05', '22:58:03', 111, 'dolor nobis', 'Nuevo usuario', 111, 'usuarios', 'Guardar el personal con numero de empleado 333 en la tabla usuarios', 'INSERT', '127.0.0.1'),
(40, '2019-10-06', '00:01:50', 333, 'omnis dolor', 'Llenar encuesta de un controlador', 333, 'razones', 'Se llena la encuesta del controlador: 111', 'INSERT', '127.0.0.1'),
(41, '2019-10-06', '00:01:52', 333, 'omnis dolor', 'Llenar encuesta de un controlador', 333, 'razones', 'Se llena la encuesta del controlador: 111', 'INSERT', '127.0.0.1'),
(42, '2019-10-06', '00:03:30', 333, 'omnis dolor', 'Llenar encuesta de un controlador', 333, 'razones', 'Se llena la encuesta del controlador: 111', 'INSERT', '127.0.0.1'),
(43, '2019-10-06', '00:03:35', 333, 'omnis dolor', 'Llenar encuesta de un controlador', 333, 'razones', 'Se llena la encuesta del controlador: 111', 'INSERT', '127.0.0.1'),
(44, '2019-10-06', '00:04:02', 333, 'omnis dolor', 'Llenar encuesta de un controlador', 333, 'razones', 'Se llena la encuesta del controlador: 111', 'INSERT', '127.0.0.1'),
(45, '2019-10-06', '00:04:06', 111, 'dolor nobis', 'Llenar encuesta de un controlador', 111, 'razones', 'Se llena la encuesta del controlador: 111', 'INSERT', '127.0.0.1'),
(46, '2019-10-06', '00:37:24', 333, 'omnis dolor', 'Llenar encuesta de un controlador', 333, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(47, '2019-10-06', '00:38:17', 333, 'omnis dolor', 'Llenar encuesta de un controlador', 333, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(48, '2019-10-06', '00:38:31', 125, 'qui minus', 'Enviar correos', 125, 'usuarios', 'Se envió por correo los resultados de la encuesta del usuario con Numero de empleado 125', 'SEND', '127.0.0.1'),
(49, '2019-10-06', '00:42:12', 333, 'omnis dolor', 'Ingresa razon de llenar encuesta', 333, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(50, '2019-10-06', '00:42:12', 333, 'omnis dolor', 'Llenar encuesta de un controlador', 333, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(51, '2019-10-06', '00:56:47', 111, 'dolor nobis', 'Ingresa razon de llenar encuesta', 111, 'razones', 'Razon: Asd', 'INSERT', '127.0.0.1'),
(52, '2019-10-06', '00:56:47', 111, 'dolor nobis', 'Llenar encuesta de un controlador', 111, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(53, '2019-10-06', '00:57:00', 125, 'qui minus', 'Enviar correos', 125, 'usuarios', 'Se envió por correo los resultados de la encuesta del usuario con Numero de empleado 125', 'SEND', '127.0.0.1'),
(54, '2019-10-06', '01:02:05', 222, 'Controlador de ', 'Enviar correos', 222, 'usuarios', 'Se envió por correo los resultados de la encuesta del usuario con Numero de empleado 222', 'SEND', '127.0.0.1'),
(55, '2019-10-06', '14:41:56', 111, 'dolor nobis', 'Ingresa razon de llenar encuesta', 111, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(56, '2019-10-06', '14:41:56', 111, 'dolor nobis', 'Llenar encuesta de un controlador', 111, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(57, '2019-10-06', '14:42:17', 125, 'qui minus', 'Enviar correos', 125, 'usuarios', 'Se envió por correo los resultados de la encuesta del usuario con Numero de empleado 125', 'SEND', '127.0.0.1'),
(58, '2019-10-06', '15:09:09', 111, 'dolor nobis', 'Ingresa razon de llenar encuesta', 111, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(59, '2019-10-06', '15:09:09', 111, 'dolor nobis', 'Llenar encuesta de un controlador', 111, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(60, '2019-10-06', '15:09:24', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(61, '2019-10-06', '15:09:24', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(62, '2019-10-06', '15:14:26', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(63, '2019-10-06', '15:14:26', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(64, '2019-10-06', '15:15:07', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(65, '2019-10-06', '15:15:07', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(66, '2019-10-06', '15:15:58', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(67, '2019-10-06', '15:15:58', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(68, '2019-10-06', '15:16:02', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(69, '2019-10-06', '15:16:02', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(70, '2019-10-06', '15:17:26', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(71, '2019-10-06', '15:17:26', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(72, '2019-10-06', '15:17:35', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(73, '2019-10-06', '15:17:35', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(74, '2019-10-06', '15:19:17', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(75, '2019-10-06', '15:19:17', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(76, '2019-10-06', '15:19:23', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(77, '2019-10-06', '15:19:23', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(78, '2019-10-06', '15:20:49', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(79, '2019-10-06', '15:20:49', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(80, '2019-10-06', '15:21:40', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(81, '2019-10-06', '15:21:40', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(82, '2019-10-06', '15:22:53', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(83, '2019-10-06', '15:22:53', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(84, '2019-10-06', '15:23:00', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(85, '2019-10-06', '15:23:00', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(86, '2019-10-06', '15:23:31', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(87, '2019-10-06', '15:23:31', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(88, '2019-10-06', '15:23:54', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(89, '2019-10-06', '15:23:54', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(90, '2019-10-06', '15:24:17', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(91, '2019-10-06', '15:24:17', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(92, '2019-10-06', '15:24:39', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(93, '2019-10-06', '15:24:39', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(94, '2019-10-06', '15:25:58', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(95, '2019-10-06', '15:25:58', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(96, '2019-10-06', '15:26:19', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(97, '2019-10-06', '15:26:19', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(98, '2019-10-06', '15:26:32', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(99, '2019-10-06', '15:26:32', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(100, '2019-10-06', '15:26:38', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(101, '2019-10-06', '15:26:38', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(102, '2019-10-06', '15:26:42', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(103, '2019-10-06', '15:26:42', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(104, '2019-10-06', '15:36:54', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(105, '2019-10-06', '15:36:54', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(106, '2019-10-06', '15:37:11', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(107, '2019-10-06', '15:37:11', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(108, '2019-10-06', '15:37:18', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(109, '2019-10-06', '15:37:18', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(110, '2019-10-06', '15:38:03', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(111, '2019-10-06', '15:38:03', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(112, '2019-10-06', '15:38:58', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(113, '2019-10-06', '15:38:58', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(114, '2019-10-06', '15:40:28', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(115, '2019-10-06', '15:40:28', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(116, '2019-10-06', '15:40:39', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(117, '2019-10-06', '15:40:39', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(118, '2019-10-06', '15:40:49', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(119, '2019-10-06', '15:40:49', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(120, '2019-10-06', '15:43:52', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(121, '2019-10-06', '15:43:52', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(122, '2019-10-06', '15:47:58', NULL, '', 'Ingresa razon de llenar encuesta', NULL, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(123, '2019-10-06', '15:47:58', NULL, '', 'Llenar encuesta de un controlador', NULL, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(124, '2019-10-06', '15:47:59', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(125, '2019-10-06', '15:47:59', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(126, '2019-10-06', '15:47:59', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(127, '2019-10-06', '15:47:59', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(128, '2019-10-06', '15:48:00', 125, 'qui minus', 'Ingresa razon de llenar encuesta', 125, 'razones', 'Razon: asd', 'INSERT', '127.0.0.1'),
(129, '2019-10-06', '15:48:00', 125, 'qui minus', 'Llenar encuesta de un controlador', 125, 'razones', 'Se llena la encuesta del controlador: 125', 'INSERT', '127.0.0.1'),
(130, '2019-10-06', '16:04:07', 222, 'Controlador de ', 'Enviar correos', 222, 'usuarios', 'Se envió por correo los resultados de la encuesta del usuario con Numero de empleado 222', 'SEND', '127.0.0.1'),
(131, '2019-10-06', '16:13:14', 125, 'qui minus', 'Enviar correos', 125, 'usuarios', 'Se envió por correo los resultados de la encuesta del usuario con Numero de empleado 125', 'SEND', '127.0.0.1'),
(132, '2019-10-06', '17:02:31', 222, 'Controlador de ', 'Enviar correos', 222, 'usuarios', 'Se envió por correo los resultados de la encuesta del usuario con Numero de empleado 222', 'SEND', '127.0.0.1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_de_respuesta`
--

DROP TABLE IF EXISTS `tipos_de_respuesta`;
CREATE TABLE `tipos_de_respuesta` (
  `id_tipo` int(11) NOT NULL,
  `tipo` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `fecha_creacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipos_de_respuesta`
--

INSERT INTO `tipos_de_respuesta` (`id_tipo`, `tipo`, `fecha_creacion`) VALUES
(1, 'Cerrada si-no', NULL),
(2, 'Escala númerica 1-5', NULL),
(3, 'Escala ordinal bajo-alto', NULL),
(4, 'Escala ordinal bien-mal', NULL),
(5, 'Escala ordinal mucho-poco', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

DROP TABLE IF EXISTS `turnos`;
CREATE TABLE `turnos` (
  `id_turno` int(3) NOT NULL,
  `turno` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `turnos`
--

INSERT INTO `turnos` (`id_turno`, `turno`, `hora_inicio`, `hora_fin`, `activo`) VALUES
(1, 'id', '22:15:53', '21:54:47', 1),
(2, 'amet', '00:22:29', '00:43:11', 1),
(3, 'similique', '06:06:40', '13:43:23', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `auth_key` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password_reset_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `verification_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`, `verification_token`) VALUES
(1, 'dolor nobis illum eligendi', '111', '$2y$10$q88bZmw.DFxq0IINezRhqO8DI4JTyrrUd2k7nV.a6rkRjpaqjnt9u', NULL, 'el@administrador.p', 1, 1569822497, 1569822497, 'sX9cnfQGObspcCVtZrDg5Den6TLqr9rRn6gpkvby'),
(2, 'tenetur dignissimos voluptas saepe', '222', '$2y$10$qWNPB/LBTwZmYBcAbRcBxezV9ptlRw54VuDMCA2kllgbsozKTP4EO', NULL, 'primer@controlador.p', 1, 1569823509, 1569823509, 'sX9cnfQGObspcCVtZrDg5Den6TLqr9rRn6gpkvby'),
(3, 'dolor nobis illum eligendi', '111', '$2y$10$Cz9R94d9c7syNuc/gIIsd.iUid4LW8T3HBFyxhjPuC7F8WXzOuM06', NULL, 'el@administrador.p', 1, 1569823563, 1569823563, 'sX9cnfQGObspcCVtZrDg5Den6TLqr9rRn6gpkvby'),
(4, 'omnis dolor accusamus adipisci', '333', '$2y$10$skfEBoBDtruSmB3VZZXxpOfDYEkaEK7SOucu02KQjjBIMsVxhtG3O', NULL, 'el@supervisor.p', 1, 1569823887, 1569823887, 'sX9cnfQGObspcCVtZrDg5Den6TLqr9rRn6gpkvby'),
(5, 'tenetur dignissimos voluptas saepe', '222', '$2y$10$DLqVoF2UIu10P1OhwKyIVeQ6LmfR7EwliQY5Cyc6D.Ok9LzukKZGC', NULL, 'primer@controlador.p', 1, 1569867465, 1569867465, 'fOFyp5xUK3KSGwUlKVJmueAVEbUyfglG8zWTTmHv'),
(6, 'tenetur dignissimos voluptas saepe', '222', '$2y$10$VgZrWQlgrn0sdHURNY4.wum726w4Pl6ZrCkiGtkZROsNIdz/MoiuK', NULL, 'primer@controlador.p', 1, 1569867682, 1569867682, 'fOFyp5xUK3KSGwUlKVJmueAVEbUyfglG8zWTTmHv'),
(7, 'necessitatibus sunt quia aliquam', '458', '$2y$10$UU1Iupf00kv.bAgpICTGlu5on4YNAHu.7zEbTqQM.M9sbNR07PSEm', NULL, 'yost.favian@ullrich.com', 1, 1569869592, 1569869592, 'fOFyp5xUK3KSGwUlKVJmueAVEbUyfglG8zWTTmHv'),
(8, 'necessitatibus sunt quia aliquam', '458', '$2y$10$31Rny0Y6pM9C6HCfHD6Ip.wFRR0PcULoU6kwJ4IJxUtztTeK1ivBC', NULL, 'yost.favian@ullrich.com', 1, 1569869632, 1569869632, 'fOFyp5xUK3KSGwUlKVJmueAVEbUyfglG8zWTTmHv'),
(9, 'inventore similique quia illum', '669', '$2y$10$3H1NNBU6m05u8QCZUf4ylOaZejYS.TLTa0aYUx/8kPlqvszADT/V2', NULL, 'waelchi.retta@gmail.com', 1, 1569871463, 1569871463, 'fOFyp5xUK3KSGwUlKVJmueAVEbUyfglG8zWTTmHv'),
(10, 'tenetur dignissimos voluptas saepe', '222', '$2y$10$Z1i60jw3C2uyy5Y6KVpj4erLxLV5Z8tIgGQhksM5JC6N6F29dvVqO', NULL, 'primer@controlador.p', 1, 1569872744, 1569872744, 'fOFyp5xUK3KSGwUlKVJmueAVEbUyfglG8zWTTmHv'),
(11, 'omnis dolor accusamus adipisci', '333', '$2y$10$uqv07RG1IXFITGXYOOyNhOCnr5wouq.OwGfixn1odPY5SIiehFC/.', NULL, 'el@supervisor.p', 1, 1569874387, 1569874387, 'fOFyp5xUK3KSGwUlKVJmueAVEbUyfglG8zWTTmHv'),
(12, 'dolor nobis illum eligendi', '111', '$2y$10$KOY/YAO0r2rqHBD7X5.Y2usa3zBLV475uqBxSWeSGmDDUvGH4cRm2', NULL, 'el@administrador.p', 1, 1570054738, 1570054738, 'hZL0i6PWlnMzxUmpNmEti313OSP80UbBdjZVkDVa'),
(13, 'dolor nobis illum eligendi', '111', '$2y$10$P2MSzfisV6puj957pdW2EOszVM4Z84zOmop6C3aflmR9fNhkFMIH6', NULL, 'el@administrador.p', 1, 1570117772, 1570117772, 'gDebWPSSSwyYwvCZ4yIbVqhqAmsqhZzyimximRdQ'),
(14, 'tenetur dignissimos voluptas saepe', '222', '$2y$10$rrgmvTSYvj2Wrv3JETNCme9jFOMTsUyQ7qju9LAWbd26lgguFiu.6', NULL, 'primer@controlador.p', 1, 1570125290, 1570125290, 'gDebWPSSSwyYwvCZ4yIbVqhqAmsqhZzyimximRdQ'),
(15, 'necessitatibus sunt quia aliquam', '458', '$2y$10$.m.eBI71UJT0cC5r9pxAtemlk85IWeb54c1C.S3bep39uG7PDbbu.', NULL, 'yost.favian@ullrich.com', 1, 1570126777, 1570126777, 'gDebWPSSSwyYwvCZ4yIbVqhqAmsqhZzyimximRdQ'),
(16, 'tenetur dignissimos voluptas saepe', '222', '$2y$10$T0lQZ/U.nUV/gFEfCTu5SOe5oZXfjtzUH.h1sFoMwXNAcZ2TJqC1i', NULL, 'primer@controlador.p', 1, 1570127042, 1570127042, 'gDebWPSSSwyYwvCZ4yIbVqhqAmsqhZzyimximRdQ'),
(17, 'necessitatibus sunt quia aliquam', '458', '$2y$10$k0lXaAqw46PRXG43ugSmL.n6IzbjBcjxatNc6624VG99Jl8HH/fh6', NULL, 'yost.favian@ullrich.com', 1, 1570127187, 1570127187, 'gDebWPSSSwyYwvCZ4yIbVqhqAmsqhZzyimximRdQ'),
(18, 'dolor nobis illum eligendi', '111', '$2y$10$.Sz3rn/ciO9k3EJaTicCyuBl6WoUSefjKJn6fjw.joOIWOeI3yayO', NULL, 'el@administrador.pP', 1, 1570201592, 1570201592, 'hTNxh8powN8vOJ3GSACvltlL026UebAIzx11vytR'),
(19, 'tenetur dignissimos voluptas saepe', '222', '$2y$10$BtpmFxqsh6XilxzGNBzzNOZm8uEOCf.WGKVF02GCvhtbSrDJUnEm.', NULL, 'primer@controlador.p', 1, 1570234473, 1570234473, 'J4uhK664JUTsrv01K34yDlhOgQvDEVxVTjaMlVJD'),
(20, 'eos praesentium sit esse', '444', '$2y$10$pjUmhRp05sZ3ryqsqHfiau22xeS6.MjasUYFGBscZW/aA/Ck.hIJq', NULL, 'RR@HH.p', 1, 1570236800, 1570236800, 'J4uhK664JUTsrv01K34yDlhOgQvDEVxVTjaMlVJD'),
(21, 'Controlador de  Trafico aéreo', '222', '$2y$10$idwhVknEJBmpwa7HIHaKneh73EdwfblDXxfTkaFVy67h7/srVdxIm', NULL, 'primer@controlador.p', 1, 1570252404, 1570252404, 'J4uhK664JUTsrv01K34yDlhOgQvDEVxVTjaMlVJD'),
(22, 'dolor nobis illum eligendi', '111', '$2y$10$CrpCagCz5svOr.DHJGUAMe7YPSKA6zSKncCxv2XprxxAcPjUWB06O', NULL, 'el@administrador.p', 1, 1570301753, 1570301753, '6OypFa5G2ek5pO4ENvZZVrqkBR0rEZTC1DU8Rvv3'),
(23, 'Controlador de  Trafico aéreo', '222', '$2y$10$xkusFB6XCLDoqsOrpFso3OQSNqOO0LKe8HwPNHCtCyycDNloIedga', NULL, 'primer@controlador.p', 1, 1570301820, 1570301820, '6OypFa5G2ek5pO4ENvZZVrqkBR0rEZTC1DU8Rvv3'),
(24, 'Controlador de  Trafico aéreo', '222', '$2y$10$0bILJNTxjgnAT/m9.wI6ieHohdQZK9uJkpEzfFtwBl/i8wgEGrAbu', NULL, 'primer@controlador.p', 1, 1570302291, 1570302291, '6OypFa5G2ek5pO4ENvZZVrqkBR0rEZTC1DU8Rvv3'),
(25, 'qui minus sit molestiae', '125', '$2y$10$xhNOC9QTZ7dYFzejBdKe3uBRbGddI.l6FqHvOysXqLE76dl5k.Gwm', NULL, 'carter.karianne@gmail.com', 1, 1570302922, 1570302922, '6OypFa5G2ek5pO4ENvZZVrqkBR0rEZTC1DU8Rvv3'),
(26, 'qui minus sit molestiae', '125', '$2y$10$PmNF44UCwBpIXDLxGnBErei.qddttTVawjv9hSL/49.PzwNLfE/QS', NULL, 'carter.karianne@gmail.com', 1, 1570303027, 1570303027, '6OypFa5G2ek5pO4ENvZZVrqkBR0rEZTC1DU8Rvv3'),
(27, 'Controlador de  Trafico aéreo', '222', '$2y$10$9XeWp.Up47NswVamSIDXr.QUoBApFucYB94U.cAR2fiaJT3W5tAkO', NULL, 'primer@controlador.p', 1, 1570303817, 1570303817, '6OypFa5G2ek5pO4ENvZZVrqkBR0rEZTC1DU8Rvv3'),
(28, 'omnis dolor accusamus adipisci', '333', '$2y$10$DTfE3pn.qd5l1y4myF2.6eOAB4RpwNTs/O6bgppoP5l/1Nkd6iyN2', NULL, 'el@supervisor.p', 1, 1570337930, 1570337930, 'PuBzrJT5bOBKN1rdNlMwTgJUHXomfLyQNn9BbLbx'),
(29, 'omnis dolor accusamus adipisci', '333', '$2y$10$nvwmiBbi.1fJ3w/nOj850O01O.ZfLvFm6VWOORVFi7HZGe3/aHBfq', NULL, 'el@supervisor.p', 1, 1570341882, 1570341882, 'PuBzrJT5bOBKN1rdNlMwTgJUHXomfLyQNn9BbLbx'),
(30, 'dolor nobis illum eligendi', '111', '$2y$10$TRclqad4l02YuGGe4854A.OaXPjGM/6jXVWhZNA5wiBPKS.OnwWxm', NULL, 'el@administrador.p', 1, 1570343922, 1570343922, 'PuBzrJT5bOBKN1rdNlMwTgJUHXomfLyQNn9BbLbx'),
(31, 'Controlador de  Trafico aéreo', '222', '$2y$10$V1ygRQvWILrOawiYvzR7ouau8d0LAqArxFOfd3igNxHSjBgPQtLNu', NULL, 'primer@controlador.p', 1, 1570345255, 1570345255, 'PuBzrJT5bOBKN1rdNlMwTgJUHXomfLyQNn9BbLbx'),
(32, 'Controlador de  Trafico aéreo', '222', '$2y$10$EJBOOIBMx/CNDMzSR3ZJxOIRq4owT5kjundQaeqJI8a8G05OjcLRC', NULL, 'primer@controlador.p', 1, 1570345317, 1570345317, 'PuBzrJT5bOBKN1rdNlMwTgJUHXomfLyQNn9BbLbx'),
(33, 'Controlador de  Trafico aéreo', '222', '$2y$10$FX5BTwXgFp/FZqFaQ5gu7e.WuBPD58oGoKhIrEk1OI/Iy28703fKi', NULL, 'primer@controlador.p', 1, 1570399416, 1570399416, 'PuBzrJT5bOBKN1rdNlMwTgJUHXomfLyQNn9BbLbx'),
(34, 'qui minus sit molestiae', '125', '$2y$10$RrM4H9dRNx9ZnbNLWjQJMeDPH/uSi2ptAmW/TvdHr.BO1E0vfI.Xe', NULL, 'carter.karianne@gmail.com', 1, 1570399527, 1570399527, 'PuBzrJT5bOBKN1rdNlMwTgJUHXomfLyQNn9BbLbx'),
(35, 'eos praesentium sit esse', '444', '$2y$10$AgLss06Jo/wmC/yq2YQkLOC9rWnPhmmugSM2S9Nbv6GcwLj3qJGZC', NULL, 'RR@HH.p', 1, 1570402769, 1570402769, 'PuBzrJT5bOBKN1rdNlMwTgJUHXomfLyQNn9BbLbx'),
(36, 'Controlador de  Trafico aéreo', '222', '$2y$10$YizoCFDKGcM65RAwWdgTneVH4U2YzEXSu3G6UmpkECHIdMDRzAq4e', NULL, 'primer@controlador.p', 1, 1570402909, 1570402909, 'PuBzrJT5bOBKN1rdNlMwTgJUHXomfLyQNn9BbLbx');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id_personal` int(3) NOT NULL,
  `id_turno` int(3) NOT NULL,
  `id_posicion` int(3) NOT NULL,
  `email` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `contrasena` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0000',
  `nuevo_intento` tinyint(4) DEFAULT '0',
  `fecha_creacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_personal`, `id_turno`, `id_posicion`, `email`, `contrasena`, `nuevo_intento`, `fecha_creacion`) VALUES
(1, 1, 1, 'el@administrador.p', 'eyJpdiI6IkFJblpsdjNKRHYrR1JMZFNpSGM2WUE9PSIsInZhbHVlIjoiU1JZall2MFJ5Tmh3M1ZNZE9jXC9GaFE9PSIsIm1hYyI6ImFmMWI2ZDY2MmM1YjhlYzlhMjhhNWVkYmZiOGYzYTBiYzYwNjIzOGI1MDIwOGM4ZGQ2NjdmZTU5MmQ1Yzc0NjIifQ==', 0, NULL),
(2, 1, 2, 'primer@controlador.p', 'eyJpdiI6IjVFVUNpZHRMZGpEQ3VjUkRSXC9ESHVnPT0iLCJ2YWx1ZSI6InRvaFBBTFVkVHZQd1NhZ0wzTUIxYlE9PSIsIm1hYyI6ImU5NjY2YjEwMmIzYjEwNzc3MTY3MjE3OTAzZjU3NzRmZjRkMzUxZjQwNDY4ZjQwOWJkODc2YmRmMDRmOGViYjMifQ==', 0, NULL),
(3, 1, 3, 'el@supervisor.p', 'eyJpdiI6ImVQZ0IyVEZsVU5UaEZDWFJxNWhzdUE9PSIsInZhbHVlIjoiSWtHMVwvamhqMnhGZGpQRTJ4elAwU3c9PSIsIm1hYyI6ImFiMjNhMDQzYmZmZDZkOTBlYjRiMzE1YjU5NzBkZjVjYTA0MzhjMGM2YzFmYmJjOTY2MGNmYjUxYTI2MzVkMDIifQ==', 0, '2019-10-05 22:58:03'),
(4, 1, 4, 'RR@HH.p', 'eyJpdiI6InhLMlEyWlkwdWRtYnJCNWlDcjhNM3c9PSIsInZhbHVlIjoicDNtOW1LZzVDVWhPdDdITzdqM2dZdz09IiwibWFjIjoiYjVhYmM2OTdmMTU2NjE4MzNlNDY0Yzc0ZGUzYjU2M2FjMDVjYjgzYjNkMWJmZGUzMDdjOTkxNTY3NmZmMDc4NyJ9', 0, NULL),
(5, 2, 2, 'carter.karianne@gmail.com', 'eyJpdiI6ImlCYjZMdnFod003aU4reDBvUjFiYWc9PSIsInZhbHVlIjoiYVZLNHFEblZ5TWdxWE9EanRHRjRjdz09IiwibWFjIjoiNTRkNDNlZmU5MDY0ZDNiMmEyYjEyZTMxNTliYmQ4YzBmOWZlNWNhMzhhOGQ5MTkxZmYwNThlZjZmZTU3OGZjNiJ9', 0, NULL),
(6, 2, 2, 'fisher.dorothea@funk.com', 'eyJpdiI6InE3czV6ZitaRjBwVld2U2prU3QxeGc9PSIsInZhbHVlIjoidjN1TlhNU3JuXC81TFkxV1NIK0ZBK3c9PSIsIm1hYyI6ImE0MTQzYmFhMzRkMWEyZTYyZjczNmJkNzFmMmMxYjdmYWEyM2IwMGJhYjdmZTE1YjIwMmZjOWU2MTU5ZWQ0N2YifQ==', 0, NULL),
(7, 2, 4, 'yost.tyrel@gmail.com', 'eyJpdiI6Iis1SG1jVjA4ZmxHK01SdU9ub3gxZUE9PSIsInZhbHVlIjoiSVl1NkJxUEJiQjlic0VFbjA2bXBTUT09IiwibWFjIjoiZWJmNTZmYTYwNmEwNzhlZmI0OTcyYzQxZTdlZTA1ZTJjMDAwMzM5ZTU0OTExNzZlZTM3OWFkMDhjYjMxMjI5NyJ9', 0, NULL),
(8, 1, 2, 'yost.favian@ullrich.com', 'eyJpdiI6InBFa0JHeVAySXZzdlppR3UxMDhjVWc9PSIsInZhbHVlIjoiTmlMZUVGZUJPQThBS0VJRVJHYWFxUT09IiwibWFjIjoiNTQ4MzY2MjdiYWI3N2I3ZjRiMTBlYTdlMGQyNWI1MDg3NWQ5OWQxYzdlMzNmMTczNzM2NDE1ZGVjNTMwMmYwMyJ9', 0, NULL),
(10, 2, 2, 'waelchi.retta@gmail.com', 'eyJpdiI6Im5EMzExUWN1UkRnemFSR096SDVcL2FRPT0iLCJ2YWx1ZSI6IkNlbUhBSlJDbTczbU1MNkxrd3R3WUE9PSIsIm1hYyI6IjBiZjQ1MzY0ZGU3YzY2NTRiZGU3NjE0MzJmYWJlZGRhZjY0MWU1M2E2Zjg2NjQxMjQ1MTY2NTM3YzE4NzdmNzMifQ==', 0, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `areas_de_preguntas`
--
ALTER TABLE `areas_de_preguntas`
  ADD PRIMARY KEY (`id_area`),
  ADD UNIQUE KEY `id_area_UNIQUE` (`id_area`),
  ADD UNIQUE KEY `nombre_UNIQUE` (`nombre`);

--
-- Indices de la tabla `log_usuarios`
--
ALTER TABLE `log_usuarios`
  ADD PRIMARY KEY (`id_log_usuario`),
  ADD UNIQUE KEY `id_log_usuario_UNIQUE` (`id_log_usuario`),
  ADD KEY `fk_log_usuarios_personal1_idx` (`id_personal`),
  ADD KEY `fk_log_usuarios_respuestas1_idx` (`id_respuesta`),
  ADD KEY `fk_log_usuarios_preguntas1_idx` (`id_pregunta`),
  ADD KEY `fk_log_usuarios_areas_de_preguntas1_idx` (`id_area`),
  ADD KEY `fk_log_usuarios_turnos1_idx` (`id_turno`);

--
-- Indices de la tabla `perdidas_de_contrasena`
--
ALTER TABLE `perdidas_de_contrasena`
  ADD PRIMARY KEY (`id_perdida`),
  ADD UNIQUE KEY `id_perdida_UNIQUE` (`id_perdida`);

--
-- Indices de la tabla `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`id_personal`);

--
-- Indices de la tabla `posicion`
--
ALTER TABLE `posicion`
  ADD PRIMARY KEY (`id_posicion`),
  ADD UNIQUE KEY `id_posicion_UNIQUE` (`id_posicion`);

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`id_pregunta`,`id_area`,`id_tipo`,`contenido`),
  ADD UNIQUE KEY `id_pregunta_UNIQUE` (`id_pregunta`),
  ADD KEY `fk_preguntas_areas_de_preguntas1_idx` (`id_area`),
  ADD KEY `fk_preguntas_tipos_de_respuesta1_idx` (`id_tipo`);

--
-- Indices de la tabla `pregunta_filtro`
--
ALTER TABLE `pregunta_filtro`
  ADD PRIMARY KEY (`id_pregunta`),
  ADD UNIQUE KEY `id_pregunta_UNIQUE` (`id_pregunta`);

--
-- Indices de la tabla `razones`
--
ALTER TABLE `razones`
  ADD PRIMARY KEY (`id_razon`),
  ADD UNIQUE KEY `id_razon_UNIQUE` (`id_razon`);

--
-- Indices de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD PRIMARY KEY (`id_respuesta`,`id_tipo`),
  ADD UNIQUE KEY `id_pregunta_UNIQUE` (`id_respuesta`),
  ADD KEY `fk_respuestas_tipos_de_respuesta1_idx` (`id_tipo`);

--
-- Indices de la tabla `seglog`
--
ALTER TABLE `seglog`
  ADD PRIMARY KEY (`SegLogKey`);

--
-- Indices de la tabla `tipos_de_respuesta`
--
ALTER TABLE `tipos_de_respuesta`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`id_turno`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_personal`,`id_turno`,`id_posicion`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `id_personal_UNIQUE` (`id_personal`),
  ADD KEY `fk_datos_adiciones_turnos1_idx` (`id_turno`),
  ADD KEY `fk_datos_adiciones_posicion1_idx` (`id_posicion`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `areas_de_preguntas`
--
ALTER TABLE `areas_de_preguntas`
  MODIFY `id_area` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `log_usuarios`
--
ALTER TABLE `log_usuarios`
  MODIFY `id_log_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT de la tabla `perdidas_de_contrasena`
--
ALTER TABLE `perdidas_de_contrasena`
  MODIFY `id_perdida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `personal`
--
ALTER TABLE `personal`
  MODIFY `id_personal` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `posicion`
--
ALTER TABLE `posicion`
  MODIFY `id_posicion` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id_pregunta` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `pregunta_filtro`
--
ALTER TABLE `pregunta_filtro`
  MODIFY `id_pregunta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `razones`
--
ALTER TABLE `razones`
  MODIFY `id_razon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  MODIFY `id_respuesta` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `seglog`
--
ALTER TABLE `seglog`
  MODIFY `SegLogKey` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT de la tabla `tipos_de_respuesta`
--
ALTER TABLE `tipos_de_respuesta`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `id_turno` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `log_usuarios`
--
ALTER TABLE `log_usuarios`
  ADD CONSTRAINT `fk_log_usuarios_areas_de_preguntas1` FOREIGN KEY (`id_area`) REFERENCES `areas_de_preguntas` (`id_area`),
  ADD CONSTRAINT `fk_log_usuarios_personal1` FOREIGN KEY (`id_personal`) REFERENCES `personal` (`id_personal`),
  ADD CONSTRAINT `fk_log_usuarios_preguntas1` FOREIGN KEY (`id_pregunta`) REFERENCES `preguntas` (`id_pregunta`),
  ADD CONSTRAINT `fk_log_usuarios_respuestas1` FOREIGN KEY (`id_respuesta`) REFERENCES `respuestas` (`id_respuesta`),
  ADD CONSTRAINT `fk_log_usuarios_turnos1` FOREIGN KEY (`id_turno`) REFERENCES `turnos` (`id_turno`);

--
-- Filtros para la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD CONSTRAINT `fk_preguntas_areas_de_preguntas1` FOREIGN KEY (`id_area`) REFERENCES `areas_de_preguntas` (`id_area`),
  ADD CONSTRAINT `fk_preguntas_tipos_de_respuesta1` FOREIGN KEY (`id_tipo`) REFERENCES `tipos_de_respuesta` (`id_tipo`);

--
-- Filtros para la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD CONSTRAINT `fk_respuestas_tipos_de_respuesta1` FOREIGN KEY (`id_tipo`) REFERENCES `tipos_de_respuesta` (`id_tipo`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_datos_adiciones_personal1` FOREIGN KEY (`id_personal`) REFERENCES `personal` (`id_personal`),
  ADD CONSTRAINT `fk_datos_adiciones_posicion1` FOREIGN KEY (`id_posicion`) REFERENCES `posicion` (`id_posicion`),
  ADD CONSTRAINT `fk_datos_adiciones_turnos1` FOREIGN KEY (`id_turno`) REFERENCES `turnos` (`id_turno`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
