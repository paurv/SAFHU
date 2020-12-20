-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 06-10-2019 a las 18:11:12
-- Versión del servidor: 8.0.17
-- Versión de PHP: 7.3.9


-- NOTAS IMPORTANTES
--
-- LLENAR LA TABLA PERSONAL, CON EL PERSONAL DE LA EMPRESA
-- AGREGAR A LA TABLA DE USUARIOS EL PERSONAL QUE SERA EL ADMINISTRADOR
-- NO CAMBIAR EL ORDEN DE LOS REGISTROS DE LA TABLA posicion
-- NO CAMBIAR EL ORDEN DE LOS REGISTROS DE LA TABLA tipos_de_respuesta
-- NO CAMBIAR EL ORDEN DE LOS REGISTROS DE LA TABLA respuestas
--




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
(1, '¿Estoy en forma para realizar el turno?', '2019-09-29', '2019-09-29');

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
