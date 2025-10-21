-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-09-2025 a las 21:53:07
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `colegio_campo_rosso`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `BuscarPagosEstudiante` (IN `nombre` VARCHAR(100))   BEGIN
    SELECT 
        id, curso, mes_pago, monto, 
        comprobante, fecha_pago
    FROM pagos 
    WHERE nombre_estudiante LIKE CONCAT('%', nombre, '%')
    ORDER BY fecha_pago DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ObtenerEstadisticasGenerales` ()   BEGIN
    SELECT 
        (SELECT COUNT(*) FROM pagos) as total_pagos,
        (SELECT SUM(monto) FROM pagos) as total_recaudado,
        (SELECT COUNT(*) FROM pagos WHERE MONTH(fecha_pago) = MONTH(CURRENT_DATE()) AND YEAR(fecha_pago) = YEAR(CURRENT_DATE())) as pagos_mes_actual,
        (SELECT COUNT(DISTINCT nombre_estudiante) FROM pagos) as estudiantes_con_pagos,
        (SELECT COUNT(DISTINCT curso) FROM pagos) as cursos_activos;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `estadisticas_por_curso`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `estadisticas_por_curso` (
`curso` varchar(50)
,`total_pagos` bigint(21)
,`total_recaudado` decimal(32,2)
,`promedio_pago` decimal(14,6)
,`primer_pago` datetime
,`ultimo_pago` datetime
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `estudiantes_pagos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `estudiantes_pagos` (
`nombre_estudiante` varchar(100)
,`curso` varchar(50)
,`total_pagos_realizados` bigint(21)
,`total_pagado` decimal(32,2)
,`primer_pago` datetime
,`ultimo_pago` datetime
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `nombre_estudiante` varchar(100) NOT NULL,
  `curso` varchar(50) NOT NULL,
  `mes_pago` varchar(20) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `comprobante` varchar(255) DEFAULT NULL,
  `fecha_pago` datetime DEFAULT current_timestamp(),
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `nombre_estudiante`, `curso`, `mes_pago`, `monto`, `comprobante`, `fecha_pago`, `usuario_registro`, `fecha_modificacion`) VALUES
(1, 'Ana María García López', '1ro Secundaria', 'Enero', 280.00, NULL, '2024-01-15 09:30:00', 1, NULL),
(2, 'Carlos Eduardo Mamani', '2do Secundaria', 'Enero', 280.00, NULL, '2024-01-16 10:15:00', 1, NULL),
(3, 'María José Quispe', '3ro Secundaria', 'Enero', 320.00, NULL, '2024-01-17 11:20:00', 1, NULL),
(4, 'José Luis Condori', '4to Secundaria', 'Enero', 320.00, NULL, '2024-01-18 14:45:00', 1, NULL),
(5, 'Sofía Isabel Vargas', '5to Secundaria', 'Enero', 350.00, NULL, '2024-01-19 08:30:00', 1, NULL),
(6, 'Ricardo Andrés Rocha', '6to Secundaria', 'Enero', 350.00, NULL, '2024-01-20 16:10:00', 1, NULL),
(7, 'Ana María García López', '1ro Secundaria', 'Febrero', 280.00, NULL, '2024-02-12 09:45:00', 1, NULL),
(8, 'Carlos Eduardo Mamani', '2do Secundaria', 'Febrero', 280.00, NULL, '2024-02-13 10:30:00', 1, NULL),
(9, 'María José Quispe', '3ro Secundaria', 'Febrero', 320.00, NULL, '2024-02-14 12:15:00', 1, NULL),
(10, 'Diego Alejandro Cruz', '1ro Secundaria', 'Febrero', 280.00, NULL, '2024-02-15 13:20:00', 1, NULL),
(11, 'Valentina Nicole Paz', '2do Secundaria', 'Febrero', 280.00, NULL, '2024-02-16 15:30:00', 1, NULL),
(12, 'Ana María García López', '1ro Secundaria', 'Marzo', 280.00, NULL, '2024-03-10 09:15:00', 1, NULL),
(13, 'Carlos Eduardo Mamani', '2do Secundaria', 'Marzo', 280.00, NULL, '2024-03-11 10:45:00', 1, NULL),
(14, 'María José Quispe', '3ro Secundaria', 'Marzo', 320.00, NULL, '2024-03-12 11:30:00', 1, NULL),
(15, 'José Luis Condori', '4to Secundaria', 'Marzo', 320.00, NULL, '2024-03-13 14:20:00', 1, NULL),
(16, 'Sofía Isabel Vargas', '5to Secundaria', 'Marzo', 350.00, NULL, '2024-03-14 16:00:00', 1, NULL),
(17, 'Camila Fernanda Siles', '3ro Secundaria', 'Marzo', 320.00, NULL, '2024-03-15 08:45:00', 1, NULL),
(18, 'Diego Alejandro Cruz', '1ro Secundaria', 'Abril', 280.00, NULL, '2024-04-08 09:30:00', 1, NULL),
(19, 'Valentina Nicole Paz', '2do Secundaria', 'Abril', 280.00, NULL, '2024-04-09 10:15:00', 1, NULL),
(20, 'Ricardo Andrés Rocha', '6to Secundaria', 'Abril', 350.00, NULL, '2024-04-10 11:45:00', 1, NULL),
(21, 'Alejandra Patricia Morales', '4to Secundaria', 'Abril', 320.00, NULL, '2024-04-11 13:30:00', 1, NULL),
(22, 'Ana María García López', '1ro Secundaria', 'Mayo', 280.00, NULL, '2024-05-07 09:20:00', 1, NULL),
(23, 'Carlos Eduardo Mamani', '2do Secundaria', 'Mayo', 280.00, NULL, '2024-05-08 10:50:00', 1, NULL),
(24, 'María José Quispe', '3ro Secundaria', 'Mayo', 320.00, NULL, '2024-05-09 12:10:00', 1, NULL),
(25, 'Sebastián Gonzalo Torres', '5to Secundaria', 'Mayo', 350.00, NULL, '2024-05-10 14:30:00', 1, NULL),
(26, 'Diego Alejandro Cruz', '1ro Secundaria', 'Junio', 280.00, NULL, '2024-06-05 09:40:00', 1, NULL),
(27, 'Valentina Nicole Paz', '2do Secundaria', 'Junio', 280.00, NULL, '2024-06-06 11:20:00', 1, NULL),
(28, 'José Luis Condori', '4to Secundaria', 'Junio', 320.00, NULL, '2024-06-07 13:45:00', 1, NULL),
(29, 'Sofía Isabel Vargas', '5to Secundaria', 'Junio', 350.00, NULL, '2024-06-08 15:15:00', 1, NULL),
(30, 'Ana María García López', '1ro Secundaria', 'Matrícula', 450.00, NULL, '2024-01-05 08:30:00', 1, NULL),
(31, 'Carlos Eduardo Mamani', '2do Secundaria', 'Matrícula', 480.00, NULL, '2024-01-06 09:15:00', 1, NULL),
(32, 'María José Quispe', '3ro Secundaria', 'Matrícula', 520.00, NULL, '2024-01-07 10:30:00', 1, NULL),
(33, 'José Luis Condori', '4to Secundaria', 'Matrícula', 550.00, NULL, '2024-01-08 11:45:00', 1, NULL),
(34, 'Sofía Isabel Vargas', '5to Secundaria', 'Matrícula', 580.00, NULL, '2024-01-09 13:20:00', 1, NULL),
(35, 'Ricardo Andrés Rocha', '6to Secundaria', 'Matrícula', 600.00, NULL, '2024-01-10 14:40:00', 1, NULL),
(36, 'Martín Gabriel Herrera', '1ro Secundaria', 'Agosto', 280.00, NULL, '2024-08-01 09:30:00', 1, NULL),
(37, 'Isabella Sofia Luna', '2do Secundaria', 'Agosto', 280.00, NULL, '2024-08-02 10:45:00', 1, NULL),
(38, 'Emilio Daniel Flores', '3ro Secundaria', 'Agosto', 320.00, NULL, '2024-08-03 11:30:00', 1, NULL),
(39, 'Antonella Beatriz Mendez', '4to Secundaria', 'Agosto', 320.00, NULL, '2024-08-04 13:15:00', 1, NULL),
(40, 'Nicolás Sebastián Aguilar', '5to Secundaria', 'Agosto', 350.00, NULL, '2024-08-05 14:45:00', 1, NULL),
(41, 'Renata Alejandra Vega', '6to Secundaria', 'Agosto', 150.00, NULL, '2024-08-06 15:30:00', 1, '2025-08-06 23:48:09'),
(42, 'Juan de Dios', '6to Secundaria', 'Mayo', 15.00, '', '2025-08-06 23:24:09', NULL, NULL),
(43, 'Marcelo Molina', '6to Secundaria', 'Agosto', 210.00, 'comprobante_68942070161cb0.88871613.jpg', '2025-08-06 23:41:36', NULL, '2025-08-06 23:47:50'),
(44, 'Moises Franco Villa', '6to Secundaria', 'Enero', 150.00, 'comprobante_68949e9d8ceda.jpg', '2025-08-07 08:39:57', 1, NULL),
(45, 'Chavez Rivero Kenia', '6to Secundaria', 'Septiembre', 200.00, 'comprobante_689658b94bc48.jpg', '2025-08-08 16:06:17', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `pagos_por_mes`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `pagos_por_mes` (
`mes_pago` varchar(20)
,`cantidad_pagos` bigint(21)
,`total_mes` decimal(32,2)
,`estudiantes_diferentes` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `clave`, `fecha_creacion`, `activo`) VALUES
(1, 'admin', '$2y$10$DN62K0LSoARU.vDyFdBCa.6FeUR8SqzhNN15Fuq88VDBzK1FJehji', '2025-08-06 21:39:21', 1);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_pagos_completa`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_pagos_completa` (
`id` int(11)
,`nombre_estudiante` varchar(100)
,`curso` varchar(50)
,`mes_pago` varchar(20)
,`monto` decimal(10,2)
,`comprobante` varchar(255)
,`fecha_pago` datetime
,`registrado_por` varchar(50)
,`fecha_modificacion` datetime
);

-- --------------------------------------------------------

--
-- Estructura para la vista `estadisticas_por_curso`
--
DROP TABLE IF EXISTS `estadisticas_por_curso`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `estadisticas_por_curso`  AS SELECT `pagos`.`curso` AS `curso`, count(0) AS `total_pagos`, sum(`pagos`.`monto`) AS `total_recaudado`, avg(`pagos`.`monto`) AS `promedio_pago`, min(`pagos`.`fecha_pago`) AS `primer_pago`, max(`pagos`.`fecha_pago`) AS `ultimo_pago` FROM `pagos` GROUP BY `pagos`.`curso` ORDER BY `pagos`.`curso` ASC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `estudiantes_pagos`
--
DROP TABLE IF EXISTS `estudiantes_pagos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `estudiantes_pagos`  AS SELECT `pagos`.`nombre_estudiante` AS `nombre_estudiante`, `pagos`.`curso` AS `curso`, count(0) AS `total_pagos_realizados`, sum(`pagos`.`monto`) AS `total_pagado`, min(`pagos`.`fecha_pago`) AS `primer_pago`, max(`pagos`.`fecha_pago`) AS `ultimo_pago` FROM `pagos` GROUP BY `pagos`.`nombre_estudiante`, `pagos`.`curso` ORDER BY `pagos`.`nombre_estudiante` ASC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `pagos_por_mes`
--
DROP TABLE IF EXISTS `pagos_por_mes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `pagos_por_mes`  AS SELECT `pagos`.`mes_pago` AS `mes_pago`, count(0) AS `cantidad_pagos`, sum(`pagos`.`monto`) AS `total_mes`, count(distinct `pagos`.`nombre_estudiante`) AS `estudiantes_diferentes` FROM `pagos` GROUP BY `pagos`.`mes_pago` ORDER BY CASE `pagos`.`mes_pago` WHEN 'Matrícula' THEN 0 WHEN 'Enero' THEN 1 WHEN 'Febrero' THEN 2 WHEN 'Marzo' THEN 3 WHEN 'Abril' THEN 4 WHEN 'Mayo' THEN 5 WHEN 'Junio' THEN 6 WHEN 'Julio' THEN 7 WHEN 'Agosto' THEN 8 WHEN 'Septiembre' THEN 9 WHEN 'Octubre' THEN 10 WHEN 'Noviembre' THEN 11 WHEN 'Diciembre' THEN 12 END ASC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_pagos_completa`
--
DROP TABLE IF EXISTS `vista_pagos_completa`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_pagos_completa`  AS SELECT `p`.`id` AS `id`, `p`.`nombre_estudiante` AS `nombre_estudiante`, `p`.`curso` AS `curso`, `p`.`mes_pago` AS `mes_pago`, `p`.`monto` AS `monto`, `p`.`comprobante` AS `comprobante`, `p`.`fecha_pago` AS `fecha_pago`, `u`.`usuario` AS `registrado_por`, `p`.`fecha_modificacion` AS `fecha_modificacion` FROM (`pagos` `p` left join `usuarios` `u` on(`p`.`usuario_registro` = `u`.`id`)) ORDER BY `p`.`fecha_pago` DESC ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_registro` (`usuario_registro`),
  ADD KEY `idx_estudiante` (`nombre_estudiante`),
  ADD KEY `idx_curso` (`curso`),
  ADD KEY `idx_mes` (`mes_pago`),
  ADD KEY `idx_fecha` (`fecha_pago`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`usuario_registro`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
