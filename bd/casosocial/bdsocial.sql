-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-04-2026 a las 23:20:07
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bdsocial`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `casos_sociales`
--

CREATE TABLE `casos_sociales` (
  `id` int(11) NOT NULL,
  `id_ong` int(11) NOT NULL,
  `titulo_caso` varchar(300) NOT NULL,
  `clasificacion` enum('salud','desastres','medio_ambiente','educacion') NOT NULL,
  `descripcion` text NOT NULL,
  `monto_requerido` decimal(10,2) DEFAULT 0.00,
  `monto_recaudado` decimal(10,2) DEFAULT 0.00,
  `ubicacion` varchar(200) DEFAULT NULL,
  `nombre_beneficiario` varchar(200) DEFAULT NULL,
  `dni_beneficiario` varchar(15) DEFAULT NULL,
  `edad_beneficiario` int(11) DEFAULT NULL,
  `estado` enum('pendiente','observado','aprobado','publicado','cerrado') DEFAULT 'pendiente',
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `nombre_ong` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `casos_sociales`
--

INSERT INTO `casos_sociales` (`id`, `id_ong`, `titulo_caso`, `clasificacion`, `descripcion`, `monto_requerido`, `monto_recaudado`, `ubicacion`, `nombre_beneficiario`, `dni_beneficiario`, `edad_beneficiario`, `estado`, `fecha_registro`, `fecha_actualizacion`, `nombre_ong`) VALUES
(1, 1, 'Apoyo médico urgente', 'salud', 'Caso de operación urgente', 5000.00, 0.00, 'Lima', 'Juan Pérez', '12345678', 45, 'cerrado', '2026-04-30 11:25:45', '2026-04-30 16:09:14', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `checklist_items`
--

CREATE TABLE `checklist_items` (
  `id` int(11) NOT NULL,
  `tipo` enum('evaluacion','cierre') NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `checklist_items`
--

INSERT INTO `checklist_items` (`id`, `tipo`, `nombre`) VALUES
(1, 'evaluacion', 'Documento del beneficiario'),
(2, 'evaluacion', 'Sustento del caso'),
(3, 'evaluacion', 'Firma válida'),
(4, 'cierre', 'Carta de la ONG'),
(5, 'cierre', 'Informe final'),
(6, 'cierre', 'Evidencia de uso de fondos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `checklist_respuestas`
--

CREATE TABLE `checklist_respuestas` (
  `id` int(11) NOT NULL,
  `id_caso` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `estado` enum('SI','NO') NOT NULL,
  `comentario` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `checklist_respuestas`
--

INSERT INTO `checklist_respuestas` (`id`, `id_caso`, `id_item`, `estado`, `comentario`) VALUES
(31, 1, 4, 'SI', ''),
(32, 1, 5, 'SI', ''),
(33, 1, 6, 'SI', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos_caso`
--

CREATE TABLE `documentos_caso` (
  `id` int(11) NOT NULL,
  `id_caso` int(11) NOT NULL,
  `tipo` enum('solicitud','dni_beneficiario','sustento','firma','cierre','otros') DEFAULT NULL,
  `nombre_archivo` varchar(255) DEFAULT NULL,
  `ruta_archivo` varchar(500) DEFAULT NULL,
  `tipo_mime` varchar(100) DEFAULT NULL,
  `tamanio` int(11) DEFAULT NULL,
  `fecha_subida` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `documentos_caso`
--

INSERT INTO `documentos_caso` (`id`, `id_caso`, `tipo`, `nombre_archivo`, `ruta_archivo`, `tipo_mime`, `tamanio`, `fecha_subida`) VALUES
(1, 1, 'sustento', 'ejemplo.pdf', 'uploads/ejemplo.pdf', 'application/pdf', 123456, '2026-04-30 13:44:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_estados`
--

CREATE TABLE `historial_estados` (
  `id` int(11) NOT NULL,
  `id_caso` int(11) NOT NULL,
  `estado_anterior` varchar(50) DEFAULT NULL,
  `estado_nuevo` varchar(50) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_estados`
--

INSERT INTO `historial_estados` (`id`, `id_caso`, `estado_anterior`, `estado_nuevo`, `usuario_id`, `fecha`) VALUES
(1, 1, 'pendiente', 'aprobado', NULL, '2026-04-30 12:25:28'),
(2, 1, 'pendiente', 'observado', NULL, '2026-04-30 13:48:31'),
(3, 1, 'observado', 'aprobado', NULL, '2026-04-30 13:55:24'),
(4, 1, 'aprobado', 'publicado', NULL, '2026-04-30 13:56:42'),
(5, 1, 'pendiente', 'aprobado', NULL, '2026-04-30 15:24:13'),
(6, 1, 'observado', 'aprobado', NULL, '2026-04-30 15:25:04'),
(7, 1, 'aprobado', 'publicado', NULL, '2026-04-30 15:25:18'),
(8, 1, 'pendiente', 'observado', NULL, '2026-04-30 16:06:34'),
(9, 1, 'observado', 'aprobado', NULL, '2026-04-30 16:07:10'),
(10, 1, 'aprobado', 'publicado', NULL, '2026-04-30 16:07:38'),
(11, 1, 'publicado', 'cerrado', NULL, '2026-04-30 16:09:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ongs`
--

CREATE TABLE `ongs` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `ruc` varchar(20) DEFAULT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ongs`
--

INSERT INTO `ongs` (`id`, `nombre`, `ruc`, `contacto`, `email`, `fecha_registro`) VALUES
(1, 'ONG Esperanza', '20123456789', '987654321', 'contacto@ong.com', '2026-04-30 11:25:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicaciones`
--

CREATE TABLE `publicaciones` (
  `id` int(11) NOT NULL,
  `id_caso` int(11) DEFAULT NULL,
  `titulo_publico` varchar(255) DEFAULT NULL,
  `descripcion_publica` text DEFAULT NULL,
  `imagen_portada` varchar(500) DEFAULT NULL,
  `archivo_pdf` varchar(500) DEFAULT NULL,
  `estado` enum('borrador','publicado') DEFAULT 'borrador',
  `fecha_publicacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `publicaciones`
--

INSERT INTO `publicaciones` (`id`, `id_caso`, `titulo_publico`, `descripcion_publica`, `imagen_portada`, `archivo_pdf`, `estado`, `fecha_publicacion`) VALUES
(1, 1, 'Caso prueba', 'ayuda prueba', '', '', 'publicado', '2026-04-30 16:07:38');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `casos_sociales`
--
ALTER TABLE `casos_sociales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ong` (`id_ong`);

--
-- Indices de la tabla `checklist_items`
--
ALTER TABLE `checklist_items`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `checklist_respuestas`
--
ALTER TABLE `checklist_respuestas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_caso` (`id_caso`),
  ADD KEY `id_item` (`id_item`);

--
-- Indices de la tabla `documentos_caso`
--
ALTER TABLE `documentos_caso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_caso` (`id_caso`);

--
-- Indices de la tabla `historial_estados`
--
ALTER TABLE `historial_estados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_caso` (`id_caso`);

--
-- Indices de la tabla `ongs`
--
ALTER TABLE `ongs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_caso` (`id_caso`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `casos_sociales`
--
ALTER TABLE `casos_sociales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `checklist_items`
--
ALTER TABLE `checklist_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `checklist_respuestas`
--
ALTER TABLE `checklist_respuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `documentos_caso`
--
ALTER TABLE `documentos_caso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `historial_estados`
--
ALTER TABLE `historial_estados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `ongs`
--
ALTER TABLE `ongs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `casos_sociales`
--
ALTER TABLE `casos_sociales`
  ADD CONSTRAINT `casos_sociales_ibfk_1` FOREIGN KEY (`id_ong`) REFERENCES `ongs` (`id`);

--
-- Filtros para la tabla `checklist_respuestas`
--
ALTER TABLE `checklist_respuestas`
  ADD CONSTRAINT `checklist_respuestas_ibfk_1` FOREIGN KEY (`id_caso`) REFERENCES `casos_sociales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `checklist_respuestas_ibfk_2` FOREIGN KEY (`id_item`) REFERENCES `checklist_items` (`id`);

--
-- Filtros para la tabla `documentos_caso`
--
ALTER TABLE `documentos_caso`
  ADD CONSTRAINT `documentos_caso_ibfk_1` FOREIGN KEY (`id_caso`) REFERENCES `casos_sociales` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `historial_estados`
--
ALTER TABLE `historial_estados`
  ADD CONSTRAINT `historial_estados_ibfk_1` FOREIGN KEY (`id_caso`) REFERENCES `casos_sociales` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD CONSTRAINT `publicaciones_ibfk_1` FOREIGN KEY (`id_caso`) REFERENCES `casos_sociales` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
