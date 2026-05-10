-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-05-2026 a las 23:19:50
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
-- Estructura de tabla para la tabla `actualizaciones_caso`
--

CREATE TABLE `actualizaciones_caso` (
  `id` int(11) NOT NULL,
  `caso_id` int(11) NOT NULL,
  `tipo` enum('avance','coordinacion','entrega','cierre') DEFAULT 'avance',
  `titulo` varchar(300) NOT NULL,
  `contenido` text NOT NULL,
  `autor` varchar(100) DEFAULT 'Administrador',
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actualizaciones_caso`
--

INSERT INTO `actualizaciones_caso` (`id`, `caso_id`, `tipo`, `titulo`, `contenido`, `autor`, `fecha`) VALUES
(1, 6, 'avance', 'Campaña publicada exitosamente', 'El caso ha sido revisado, aprobado y publicado en la plataforma. Ya está disponible para recibir donaciones de la comunidad. Agradecemos tu confianza en SocialFunding.', 'Administrador', '2026-05-03 17:00:00'),
(2, 6, 'avance', 'Primeras donaciones recibidas', 'La campaña ha recibido sus primeras contribuciones. El progreso avanza gracias a la generosidad de los donantes que se han sumado a esta causa. Seguiremos informándote.', 'Administrador', '2026-05-05 10:30:00'),
(3, 6, 'avance', 'Difusión activa en redes sociales', 'El equipo de comunicaciones está difundiendo activamente el caso en redes sociales. El alcance de la campaña sigue creciendo y cada vez más personas conocen tu historia.', 'Administrador', '2026-05-07 14:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `casos_sociales`
--

CREATE TABLE `casos_sociales` (
  `id` int(11) NOT NULL,
  `nombre_ong` varchar(200) NOT NULL,
  `ruc_ong` varchar(20) DEFAULT NULL,
  `contacto_ong` varchar(100) DEFAULT NULL,
  `email_ong` varchar(150) DEFAULT NULL,
  `titulo_caso` varchar(300) NOT NULL,
  `titulo_publico` varchar(300) DEFAULT NULL,
  `clasificacion` enum('salud','desastres','medio_ambiente','educacion') NOT NULL,
  `descripcion` text NOT NULL,
  `descripcion_publica` text DEFAULT NULL,
  `monto_requerido` decimal(10,2) DEFAULT 0.00,
  `porcentaje_comision` decimal(5,2) DEFAULT 0.00,
  `monto_comision` decimal(10,2) DEFAULT 0.00,
  `meta_total` decimal(10,2) DEFAULT 0.00,
  `monto_recaudado` decimal(10,2) DEFAULT 0.00,
  `ubicacion` varchar(200) DEFAULT NULL,
  `nombre_beneficiario` varchar(200) DEFAULT NULL,
  `dni_beneficiario` varchar(15) DEFAULT NULL,
  `edad_beneficiario` int(11) DEFAULT NULL,
  `estado_evaluacion` enum('pendiente','aprobado','observado','rechazado','publicado','cerrado') DEFAULT 'pendiente',
  `comentario_evaluacion` text DEFAULT NULL,
  `publicado` tinyint(1) DEFAULT 0,
  `estado_proceso` enum('sin_proceso','en_proceso','cancelado','finalizado') DEFAULT 'sin_proceso',
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_evaluacion` datetime DEFAULT NULL,
  `fecha_publicacion` datetime DEFAULT NULL,
  `fecha_cierre` datetime DEFAULT NULL,
  `fecha_ult_cambio` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `documento_solicitud` varchar(500) DEFAULT NULL,
  `documento_cierre` varchar(500) DEFAULT NULL,
  `checklist_cierre_completo` tinyint(1) DEFAULT 0,
  `foto_beneficiario` varchar(500) DEFAULT NULL,
  `beneficiario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `casos_sociales`
--

INSERT INTO `casos_sociales` (`id`, `nombre_ong`, `ruc_ong`, `contacto_ong`, `email_ong`, `titulo_caso`, `titulo_publico`, `clasificacion`, `descripcion`, `descripcion_publica`, `monto_requerido`, `porcentaje_comision`, `monto_comision`, `meta_total`, `monto_recaudado`, `ubicacion`, `nombre_beneficiario`, `dni_beneficiario`, `edad_beneficiario`, `estado_evaluacion`, `comentario_evaluacion`, `publicado`, `estado_proceso`, `fecha_registro`, `fecha_evaluacion`, `fecha_publicacion`, `fecha_cierre`, `fecha_ult_cambio`, `documento_solicitud`, `documento_cierre`, `checklist_cierre_completo`, `foto_beneficiario`, `beneficiario_id`) VALUES
(1, 'ONG Vida y Esperanza', '20512345678', NULL, 'contacto@vidaesperanza.pe', 'Niño con leucemia necesita tratamiento urgente', NULL, 'salud', 'Menor de 7 años diagnosticado con leucemia linfoblástica aguda requiere quimioterapia. La familia no cuenta con recursos para costear el tratamiento en clínica especializada.', NULL, 35000.00, 7.00, 2450.00, 37450.00, 0.00, 'Lima, Perú', 'Miguel Torres Ríos', '12345678', 7, 'observado', '', 0, 'sin_proceso', '2026-04-19 20:48:55', '2026-05-03 16:14:48', NULL, NULL, '2026-05-09 21:25:22', NULL, NULL, 0, NULL, NULL),
(2, 'Fundación Manos Unidas', '20598765432', NULL, 'info@manosunidas.org', 'Reconstrucción de viviendas por huayco en Junín', 'Reconstrucción de viviendas por huayco en Junín', 'desastres', 'Familias afectadas por derrumbe de cerro tras lluvias intensas. 15 viviendas destruidas completamente, dejando a 62 personas sin hogar.', 'Familias afectadas por derrumbe de cerro tras lluvias intensas. 15 viviendas destruidas completamente, dejando a 62 personas sin hogar.', 80000.00, 7.00, 5600.00, 85600.00, 85600.00, 'Junín, Perú', 'Comunidad Alto Perú', '00000000', NULL, 'cerrado', NULL, 1, 'finalizado', '2026-04-19 20:48:55', NULL, '2026-05-10 11:27:20', '2026-05-10 11:43:41', '2026-05-10 11:43:41', NULL, NULL, 1, NULL, NULL),
(3, 'EcoPerú ONG', '20511223344', NULL, 'proyectos@ecoperuong.pe', 'Reforestación cuenca río Mantaro', NULL, 'medio_ambiente', 'Proyecto de reforestación con 5,000 árboles nativos para recuperar la cuenca hídrica afectada por actividades mineras ilegales en la zona.', NULL, 25000.00, 7.00, 1750.00, 26750.00, 0.00, 'Huancayo, Junín', 'Comunidades Ribereñas Mantaro', '00000001', NULL, 'observado', NULL, 0, 'sin_proceso', '2026-04-19 20:48:55', NULL, NULL, NULL, '2026-05-09 21:25:22', NULL, NULL, 0, NULL, NULL),
(4, 'Futuro Brillante', '20555667788', NULL, 'becas@futurobrillante.pe', 'Becas escolares para niños en extrema pobreza', NULL, 'educacion', 'Financiamiento de útiles, uniformes y matrícula para 50 niños de familias en situación crítica en zona altoandina de Ayacucho.', NULL, 15000.00, 7.00, 1050.00, 16050.00, 0.00, 'Ayacucho, Perú', 'I.E. N° 38047', '00000002', NULL, 'rechazado', NULL, 0, 'sin_proceso', '2026-04-19 20:48:55', NULL, NULL, NULL, '2026-05-09 21:25:22', NULL, NULL, 0, NULL, NULL),
(5, 'Salud Para Todos', '20533445566', NULL, 'brigadas@saludparatodos.org', 'Brigada médica zona rural Cajamarca', NULL, 'salud', 'Atención médica gratuita para comunidades rurales sin acceso a servicios básicos de salud. Incluye odontología, pediatría y ginecología.', NULL, 18000.00, 7.00, 1260.00, 19260.00, 0.00, 'Cajamarca, Perú', 'Dist. Huambos', '87654321', NULL, 'aprobado', NULL, 1, 'finalizado', '2026-04-19 20:48:55', NULL, NULL, NULL, '2026-05-09 21:25:22', NULL, NULL, 0, NULL, NULL),
(6, 'pedro', '10268744325', 'Juan perez', 'pero@gmail.com', 'Caso de prueba ', 'Caso de prueba ', 'salud', 'es un caso para hacer pruebas ', 'es un caso para hacer pruebas ', 80000.00, 7.00, 5600.00, 85600.00, 85600.00, 'calle 3', 'Jorge', '23649710', 35, 'cerrado', NULL, 1, 'finalizado', '2026-05-03 16:29:58', '2026-05-03 16:30:16', '2026-05-03 16:57:44', '2026-05-10 00:37:03', '2026-05-10 11:30:36', '1777843798_sd.pdf', NULL, 1, '1777843798_images.jpg', 3),
(7, 'ong de prueba', '20654987432', 'juan', 'ong@gmail.com', 'caso de prueba para la ong', 'caso de prueba para la ong', 'educacion', 'descripcion para la ong del caso de prueba', 'descripcion para la ong del caso de prueba', 5000.00, 3.00, 150.00, 5150.00, 150.00, 'lima', 'juan', '52369874', 30, 'publicado', NULL, 1, 'en_proceso', '2026-05-10 12:08:31', '2026-05-10 12:12:57', '2026-05-10 12:13:09', NULL, '2026-05-10 15:45:30', '1778432911_1777843798_cierre.pdf', NULL, 0, '1778432911_1777843798_images.jpg', NULL),
(8, 'ong de prueba 1', '20654987432', 'juan', 'ong@gmail.com', 'caso de prueba para la ong', 'caso de prueba para la ong', 'salud', 'prueba', 'prueba', 7000.00, 3.00, 210.00, 7210.00, 8000.00, 'lima', 'juan', '52369874', 25, 'cerrado', NULL, 1, 'finalizado', '2026-05-10 15:56:44', '2026-05-10 15:57:04', '2026-05-10 15:57:17', '2026-05-10 16:10:45', '2026-05-10 16:10:45', '1778446604_1778432911_1777843798_cierre.pdf', NULL, 1, '1778446604_1778432402_1777774466_images.jpg', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `checklist_items`
--

CREATE TABLE `checklist_items` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `tipo` varchar(50) DEFAULT 'evaluacion',
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `checklist_items`
--

INSERT INTO `checklist_items` (`id`, `nombre`, `tipo`, `estado`) VALUES
(1, 'Documento del beneficiario', 'evaluacion', 1),
(2, 'Sustento del caso', 'evaluacion', 1),
(3, 'Firma válida', 'evaluacion', 1),
(4, 'Carta de la ONG', 'cierre', 1),
(5, 'voucher de pago', 'cierre', 1),
(6, 'Evidencia de uso de fondos', 'cierre', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `checklist_respuestas`
--

CREATE TABLE `checklist_respuestas` (
  `id` int(11) NOT NULL,
  `caso_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `estado` enum('SI','NO') NOT NULL,
  `comentario` text DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `checklist_respuestas`
--

INSERT INTO `checklist_respuestas` (`id`, `caso_id`, `item_id`, `estado`, `comentario`, `fecha_registro`) VALUES
(13, 1, 1, 'NO', 'preuab 1', '2026-05-03 16:14:48'),
(14, 1, 2, 'SI', '', '2026-05-03 16:14:48'),
(15, 1, 3, 'SI', '', '2026-05-03 16:14:48'),
(16, 6, 1, 'SI', '', '2026-05-03 16:30:16'),
(17, 6, 2, 'SI', '', '2026-05-03 16:30:16'),
(18, 6, 3, 'SI', '', '2026-05-03 16:30:16'),
(19, 6, 4, 'SI', '', '2026-05-10 00:37:03'),
(20, 6, 5, 'SI', '', '2026-05-10 00:37:03'),
(21, 6, 6, 'SI', '', '2026-05-10 00:37:03'),
(28, 2, 4, 'SI', '', '2026-05-10 11:43:41'),
(29, 2, 5, 'SI', '', '2026-05-10 11:43:41'),
(30, 2, 6, 'SI', '', '2026-05-10 11:43:41'),
(31, 7, 1, 'SI', '', '2026-05-10 12:12:57'),
(32, 7, 2, 'SI', '', '2026-05-10 12:12:57'),
(33, 7, 3, 'SI', '', '2026-05-10 12:12:57'),
(34, 8, 1, 'SI', '', '2026-05-10 15:57:04'),
(35, 8, 2, 'SI', '', '2026-05-10 15:57:04'),
(36, 8, 3, 'SI', '', '2026-05-10 15:57:04'),
(37, 8, 4, 'SI', '', '2026-05-10 16:10:45'),
(38, 8, 5, 'SI', '', '2026-05-10 16:10:45'),
(39, 8, 6, 'SI', '', '2026-05-10 16:10:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE `departamento` (
  `iddepartamento` int(11) NOT NULL,
  `idpais` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `departamento`
--

INSERT INTO `departamento` (`iddepartamento`, `idpais`, `nombre`) VALUES
(1, 1, 'Lima'),
(2, 1, 'Arequipa'),
(3, 1, 'Cusco');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `distrito`
--

CREATE TABLE `distrito` (
  `iddistrito` int(11) NOT NULL,
  `idprovincia` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `distrito`
--

INSERT INTO `distrito` (`iddistrito`, `idprovincia`, `nombre`) VALUES
(1, 1, 'Miraflores'),
(2, 1, 'Los Olivos'),
(3, 1, 'Santiago de Surco'),
(4, 1, 'San Isidro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `donaciones`
--

CREATE TABLE `donaciones` (
  `id` int(11) NOT NULL,
  `id_caso` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `metodo` varchar(50) DEFAULT NULL,
  `mensaje` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `donaciones`
--

INSERT INTO `donaciones` (`id`, `id_caso`, `id_usuario`, `nombre`, `email`, `monto`, `metodo`, `mensaje`, `fecha`) VALUES
(9, 0, NULL, 'JERI SANTILLANA WILGHEM GIOVANNY', 'wjeri77@gmail.com', 5000.00, 'Yape/Plin', 'donacion yape', '2026-04-12 01:47:46'),
(10, 0, NULL, 'WILGHEM GIOVANNY', 'wjeri77@gmail.com', 2000.00, 'Transferencia', 'donacion por transferencia', '2026-04-12 01:50:51'),
(11, 0, NULL, 'wilghem jeri', 'wjeri77@gmail.com', 1000.00, 'Efectivo', 'donacion en efectivo', '2026-04-12 01:53:35'),
(12, 0, NULL, 'JERI SANTILLANA WILGHEM GIOVANNY', 'wjeri77@gmail.com', 50000.00, 'Yape/Plin', 'asd', '2026-04-12 02:13:18'),
(13, 0, NULL, 'JERI SANTILLANA WILGHEM GIOVANNY', 'wjeri77@gmail.com', 20.00, 'Efectivo', '', '2026-04-12 02:15:37'),
(14, 0, NULL, 'WILGHEM GIOVANNY', 'wjeri77@gmail.com', 0.01, 'Transferencia', '', '2026-04-25 19:55:04'),
(15, 6, 1, 'JERI SANTILLANA WILGHEM GIOVANNY', 'wjeri77@gmail.com', 0.01, 'MercadoPago', NULL, '2026-04-25 19:58:47'),
(16, 7, 1, 'Wilghem Jeri', 'admin@socialfunding.pe', 100.00, 'Directo (Sistema)', '', '2026-05-10 20:44:44'),
(17, 7, 1, 'Wilghem Jeri', 'admin@socialfunding.pe', 50.00, 'Directo (Sistema)', '', '2026-05-10 20:45:30'),
(18, 8, 1, 'Wilghem Jeri', 'admin@socialfunding.pe', 4000.00, 'Directo (Sistema)', '', '2026-05-10 20:57:41'),
(19, 8, 1, 'Wilghem Jeri', 'admin@socialfunding.pe', 4000.00, 'Directo (Sistema)', '', '2026-05-10 20:58:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `donadores`
--

CREATE TABLE `donadores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `documento` varchar(20) DEFAULT '',
  `telefono` varchar(20) DEFAULT '',
  `tipo` enum('persona','empresa') DEFAULT 'persona',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `donadores`
--

INSERT INTO `donadores` (`id`, `nombre`, `email`, `documento`, `telefono`, `tipo`, `created_at`) VALUES
(1, 'Banco Central de Reserva', 'bcrp@ejemplo.com', '20131066505', '', 'empresa', '2026-05-10 00:40:09'),
(2, 'Tech Solutions SAC', 'contacto@techsolutions.pe', '20601234567', '', 'empresa', '2026-05-10 00:40:09'),
(3, 'Peru2 Corp', 'peru2@ejemplo.com', '20987654321', '', 'empresa', '2026-05-10 00:40:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `idempresa` int(11) NOT NULL,
  `ruc` char(11) NOT NULL,
  `razon_social` varchar(150) NOT NULL,
  `nombre_comercial` varchar(150) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `fechacreacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fechaactualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_casos`
--

CREATE TABLE `historial_casos` (
  `id` int(11) NOT NULL,
  `caso_id` int(11) NOT NULL,
  `tipo_cambio` varchar(50) DEFAULT NULL,
  `valor_anterior` varchar(100) DEFAULT NULL,
  `valor_nuevo` varchar(100) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_casos`
--

INSERT INTO `historial_casos` (`id`, `caso_id`, `tipo_cambio`, `valor_anterior`, `valor_nuevo`, `comentario`, `usuario`, `fecha`) VALUES
(8, 1, 'evaluacion', 'pendiente', 'aprobado', '', 'Wilghem', '2026-04-25 12:33:27'),
(9, 1, 'evaluacion', 'aprobado', 'rechazado', '', 'Wilghem', '2026-04-25 12:38:07'),
(10, 1, 'evaluacion', 'rechazado', 'observado', '', 'Wilghem', '2026-04-25 12:38:59'),
(11, 1, 'evaluacion', 'observado', 'aprobado', '', 'Wilghem', '2026-04-25 12:40:35'),
(12, 1, 'evaluacion', 'aprobado', 'pendiente', '', 'Wilghem', '2026-04-25 12:42:16'),
(13, 1, 'evaluacion', 'pendiente', 'aprobado', '', 'Wilghem', '2026-04-25 12:43:15'),
(14, 1, 'evaluacion', 'aprobado', 'observado', '', 'Wilghem', '2026-04-25 12:44:29'),
(15, 1, 'evaluacion', 'observado', 'pendiente', '', 'Wilghem', '2026-04-25 12:44:36'),
(16, 1, 'evaluacion', 'pendiente', 'aprobado', '', 'Wilghem', '2026-04-25 12:46:02'),
(17, 1, 'evaluacion', 'aprobado', 'observado', '', 'Wilghem', '2026-04-25 12:46:11'),
(18, 1, 'evaluacion', 'observado', 'aprobado', '', 'Wilghem', '2026-04-25 12:49:18'),
(19, 1, 'evaluacion', 'aprobado', 'pendiente', '', 'Wilghem', '2026-04-25 12:50:06'),
(20, 1, 'evaluacion_checklist', 'pendiente', 'observado', 'Evaluación mediante checklist finalizada', 'Wilghem', '2026-05-03 15:59:26'),
(21, 1, 'evaluacion_checklist', 'observado', 'observado', 'Evaluación mediante checklist finalizada', 'Wilghem', '2026-05-03 16:07:46'),
(22, 1, 'evaluacion_checklist', 'observado', 'observado', 'Evaluación mediante checklist finalizada', 'Wilghem', '2026-05-03 16:11:44'),
(23, 1, 'evaluacion_checklist', 'observado', 'observado', 'Evaluación mediante checklist finalizada', 'Wilghem', '2026-05-03 16:12:13'),
(24, 1, 'evaluacion_checklist', 'observado', 'observado', 'Evaluación mediante checklist finalizada', 'Wilghem', '2026-05-03 16:14:48'),
(25, 6, 'evaluacion_checklist', 'pendiente', 'aprobado', 'Evaluación mediante checklist finalizada', 'Wilghem', '2026-05-03 16:30:16'),
(26, 6, 'publicacion', NULL, 'borrador', 'Borrador de publicación actualizado', 'Wilghem', '2026-05-03 16:41:42'),
(27, 6, 'publicacion', NULL, 'publicar', 'Caso publicado oficialmente', 'Wilghem', '2026-05-03 16:46:19'),
(28, 6, 'publicacion', NULL, 'publicar', 'Caso publicado oficialmente', 'Wilghem', '2026-05-03 16:53:00'),
(29, 6, 'publicacion', NULL, 'publicar', 'Caso publicado oficialmente', 'Wilghem', '2026-05-03 16:57:44'),
(30, 2, 'publicacion', NULL, 'publicar', 'Caso publicado oficialmente', 'Wilghem', '2026-05-10 11:27:20'),
(31, 7, 'evaluacion_checklist', 'pendiente', 'aprobado', 'Evaluación mediante checklist finalizada', 'Wilghem', '2026-05-10 12:12:57'),
(32, 7, 'publicacion', NULL, 'publicar', 'Caso publicado oficialmente', 'Wilghem', '2026-05-10 12:13:09'),
(33, 8, 'evaluacion_checklist', 'pendiente', 'aprobado', 'Evaluación mediante checklist finalizada', 'Wilghem', '2026-05-10 15:57:04'),
(34, 8, 'publicacion', NULL, 'publicar', 'Caso publicado oficialmente', 'Wilghem', '2026-05-10 15:57:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos`
--

CREATE TABLE `ingresos` (
  `id` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `subtipo` varchar(50) DEFAULT NULL,
  `empresa` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `monto_base` decimal(10,2) DEFAULT NULL,
  `monto_final` decimal(10,2) DEFAULT NULL,
  `porcentaje` decimal(5,4) DEFAULT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ingresos`
--

INSERT INTO `ingresos` (`id`, `tipo`, `subtipo`, `empresa`, `descripcion`, `monto_base`, `monto_final`, `porcentaje`, `fecha`) VALUES
(1, 'Comisión por donación', NULL, 'Donante Anónimo', 'Comisión recaudada', 500.00, 15.00, 0.0300, '2026-04-01'),
(2, 'Comisión por donación', NULL, 'Campaña Escolar', 'Recaudación mensual', 5000.00, 250.00, 0.0500, '2026-04-05'),
(3, 'Donación voluntaria', NULL, 'Socio Fundador', 'Aporte extraordinario', 1200.00, 1200.00, NULL, '2026-04-08'),
(4, 'Patrocinio', 'Económico', 'Banco Central', 'Patrocinio evento anual', 3000.00, 3000.00, NULL, '2026-04-10'),
(5, 'Patrocinio', 'En especie', 'Tech Solutions', 'Donación de 5 laptops', 0.00, 7500.00, NULL, '2026-04-11'),
(10, 'Donación voluntaria', 'Publicidad', 'peru', '', 1000.00, 1000.00, NULL, '2026-04-19'),
(22, 'Patrocinio', 'Publicidad', 'peru2', 'probando', 500.00, 50.00, NULL, '2026-04-19'),
(23, 'Patrocinio', 'Publicidad', 'peru2', 'probando', 500.00, 50.00, NULL, '2026-04-19'),
(24, 'Comisión por donación', 'Económico', '', '', 5000.00, 250.00, 0.0500, '2026-04-19'),
(25, 'Comisión por donación', 'Económico', '', '', 5000.00, 250.00, 0.0500, '2026-04-19'),
(26, 'Patrocinio', 'Publicidad', 'peru2', 'patrocinio  de prueba', 5000.00, 400.00, NULL, '2026-04-19'),
(27, 'Patrocinio', 'Publicidad', 'peru2', 'patrocinio  de prueba', 5000.00, 400.00, NULL, '2026-04-19'),
(28, 'Comisión por donación', 'Económico', '', '', 1000.00, 30.00, 0.0300, '2026-04-25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login`
--

CREATE TABLE `login` (
  `idlogin` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `fechacreacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fechaactualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `login`
--

INSERT INTO `login` (`idlogin`, `usuario`, `contrasena`, `correo`, `estado`, `fechacreacion`, `fechaactualizacion`) VALUES
(1, 'admin', '$2y$10$gkXHBQ/JbovorC84BWXyPONaCeaHgPpw8zjH.hQe2fNfV0oHqwaaa', 'admin@socialfunding.pe', 1, '2026-04-19 21:14:30', '2026-04-19 21:30:25'),
(2, 'jperry', '$2y$10$gkXHBQ/JbovorC84BWXyPONaCeaHgPpw8zjH.hQe2fNfV0oHqwaaa', 'donante@socialfunding.pe', 1, '2026-04-19 21:14:30', '2026-04-19 21:30:25'),
(3, 'beneficiario', '$2y$10$gkXHBQ/JbovorC84BWXyPONaCeaHgPpw8zjH.hQe2fNfV0oHqwaaa', 'beneficiario@socialfunding.pe', 1, '2026-05-09 09:00:00', '2026-05-09 09:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pais`
--

CREATE TABLE `pais` (
  `idpais` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `codigo_iso` char(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pais`
--

INSERT INTO `pais` (`idpais`, `nombre`, `codigo_iso`) VALUES
(1, 'Perú', 'PE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `patrocinios`
--

CREATE TABLE `patrocinios` (
  `id` int(11) NOT NULL,
  `donacion_id` int(11) NOT NULL,
  `tipo_aporte` enum('efectivo','bienes','servicios') NOT NULL,
  `descripcion` text NOT NULL,
  `valor_estimado` decimal(12,2) NOT NULL,
  `archivo_evidencia` varchar(300) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincia`
--

CREATE TABLE `provincia` (
  `idprovincia` int(11) NOT NULL,
  `iddepartamento` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `provincia`
--

INSERT INTO `provincia` (`idprovincia`, `iddepartamento`, `nombre`) VALUES
(1, 1, 'Lima'),
(2, 1, 'Cañete'),
(3, 1, 'Huaral');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `testimonio_beneficiario`
--

CREATE TABLE `testimonio_beneficiario` (
  `id` int(11) NOT NULL,
  `caso_id` int(11) NOT NULL,
  `contenido` text NOT NULL,
  `fecha` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documento`
--

CREATE TABLE `tipo_documento` (
  `idtipodoc` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `abreviatura` varchar(10) NOT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_documento`
--

INSERT INTO `tipo_documento` (`idtipodoc`, `nombre`, `abreviatura`, `estado`) VALUES
(1, 'DNI', 'DNI', 1),
(2, 'Carné de Extranjería', 'CE', 1),
(3, 'Pasaporte', 'PAS', 1),
(4, 'RUC', 'RUC', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `idlogin` int(11) NOT NULL,
  `tipo_documento` varchar(20) NOT NULL,
  `documento` varchar(20) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apemat` varchar(100) DEFAULT NULL,
  `apepat` varchar(100) DEFAULT NULL,
  `fechanac` date NOT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `correo` varchar(100) DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `iddistrito` int(11) DEFAULT NULL,
  `idprovincia` int(11) DEFAULT NULL,
  `iddepartamento` int(11) DEFAULT NULL,
  `idpais` int(11) DEFAULT NULL,
  `edad` int(11) DEFAULT NULL,
  `sexo` enum('M','F') DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `fechacreacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fechaactualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `idlogin`, `tipo_documento`, `documento`, `nombres`, `apemat`, `apepat`, `fechanac`, `estado`, `correo`, `celular`, `direccion`, `iddistrito`, `idprovincia`, `iddepartamento`, `idpais`, `edad`, `sexo`, `tipo`, `fechacreacion`, `fechaactualizacion`) VALUES
(1, 1, 'DNI', '12345678', 'Wilghem', 'Santillana', 'Jeri', '1990-01-01', 1, 'admin@socialfunding.pe', NULL, NULL, NULL, NULL, 1, 1, NULL, 'M', 'ADMIN', '2026-04-19 21:14:30', '2026-04-25 18:25:38'),
(2, 2, 'DNI', '12345679', 'Juan', 'Perry', 'Lopez', '1990-01-01', 1, 'donante@socialfunding.pe', NULL, NULL, NULL, NULL, 1, 1, NULL, 'M', 'DONANTE', '2026-04-19 21:14:30', '2026-04-25 18:25:38'),
(3, 3, 'DNI', '23649710', 'Bren', 'Rojas', 'Fernandez', '1990-01-01', 1, 'beneficiario@socialfunding.pe', NULL, 'calle 3', NULL, NULL, 1, 1, 35, 'M', 'BENEFICIARIO', '2026-05-09 09:00:00', '2026-05-09 09:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_empresa`
--

CREATE TABLE `usuario_empresa` (
  `idusuario` int(11) NOT NULL,
  `idempresa` int(11) NOT NULL,
  `rol` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `fechacreacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actualizaciones_caso`
--
ALTER TABLE `actualizaciones_caso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ac_caso` (`caso_id`);

--
-- Indices de la tabla `casos_sociales`
--
ALTER TABLE `casos_sociales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_clasificacion` (`clasificacion`),
  ADD KEY `idx_estado_evaluacion` (`estado_evaluacion`),
  ADD KEY `idx_publicado` (`publicado`),
  ADD KEY `idx_dni` (`dni_beneficiario`),
  ADD KEY `idx_beneficiario` (`beneficiario_id`);

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
  ADD KEY `fk_resp_caso` (`caso_id`),
  ADD KEY `fk_resp_item` (`item_id`);

--
-- Indices de la tabla `departamento`
--
ALTER TABLE `departamento`
  ADD PRIMARY KEY (`iddepartamento`),
  ADD KEY `fk_dept_pais` (`idpais`);

--
-- Indices de la tabla `distrito`
--
ALTER TABLE `distrito`
  ADD PRIMARY KEY (`iddistrito`),
  ADD KEY `fk_dist_prov` (`idprovincia`);

--
-- Indices de la tabla `donaciones`
--
ALTER TABLE `donaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `donadores`
--
ALTER TABLE `donadores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`idempresa`),
  ADD UNIQUE KEY `ruc` (`ruc`);

--
-- Indices de la tabla `historial_casos`
--
ALTER TABLE `historial_casos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_caso` (`caso_id`);

--
-- Indices de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`idlogin`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `pais`
--
ALTER TABLE `pais`
  ADD PRIMARY KEY (`idpais`);

--
-- Indices de la tabla `patrocinios`
--
ALTER TABLE `patrocinios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donacion_id` (`donacion_id`);

--
-- Indices de la tabla `provincia`
--
ALTER TABLE `provincia`
  ADD PRIMARY KEY (`idprovincia`),
  ADD KEY `fk_prov_dept` (`iddepartamento`);

--
-- Indices de la tabla `testimonio_beneficiario`
--
ALTER TABLE `testimonio_beneficiario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_caso_testimonio` (`caso_id`);

--
-- Indices de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  ADD PRIMARY KEY (`idtipodoc`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`),
  ADD UNIQUE KEY `uq_documento` (`tipo_documento`,`documento`),
  ADD KEY `fk_usuario_login` (`idlogin`);

--
-- Indices de la tabla `usuario_empresa`
--
ALTER TABLE `usuario_empresa`
  ADD PRIMARY KEY (`idusuario`,`idempresa`),
  ADD KEY `fk_ue_empresa` (`idempresa`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actualizaciones_caso`
--
ALTER TABLE `actualizaciones_caso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `casos_sociales`
--
ALTER TABLE `casos_sociales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `checklist_items`
--
ALTER TABLE `checklist_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `checklist_respuestas`
--
ALTER TABLE `checklist_respuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `departamento`
--
ALTER TABLE `departamento`
  MODIFY `iddepartamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `distrito`
--
ALTER TABLE `distrito`
  MODIFY `iddistrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `donaciones`
--
ALTER TABLE `donaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `donadores`
--
ALTER TABLE `donadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `idempresa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_casos`
--
ALTER TABLE `historial_casos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `login`
--
ALTER TABLE `login`
  MODIFY `idlogin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pais`
--
ALTER TABLE `pais`
  MODIFY `idpais` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `patrocinios`
--
ALTER TABLE `patrocinios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `provincia`
--
ALTER TABLE `provincia`
  MODIFY `idprovincia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `testimonio_beneficiario`
--
ALTER TABLE `testimonio_beneficiario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  MODIFY `idtipodoc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actualizaciones_caso`
--
ALTER TABLE `actualizaciones_caso`
  ADD CONSTRAINT `fk_ac_caso` FOREIGN KEY (`caso_id`) REFERENCES `casos_sociales` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `checklist_respuestas`
--
ALTER TABLE `checklist_respuestas`
  ADD CONSTRAINT `fk_resp_caso` FOREIGN KEY (`caso_id`) REFERENCES `casos_sociales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_resp_item` FOREIGN KEY (`item_id`) REFERENCES `checklist_items` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `departamento`
--
ALTER TABLE `departamento`
  ADD CONSTRAINT `fk_dept_pais` FOREIGN KEY (`idpais`) REFERENCES `pais` (`idpais`);

--
-- Filtros para la tabla `distrito`
--
ALTER TABLE `distrito`
  ADD CONSTRAINT `fk_dist_prov` FOREIGN KEY (`idprovincia`) REFERENCES `provincia` (`idprovincia`);

--
-- Filtros para la tabla `historial_casos`
--
ALTER TABLE `historial_casos`
  ADD CONSTRAINT `historial_casos_ibfk_1` FOREIGN KEY (`caso_id`) REFERENCES `casos_sociales` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `patrocinios`
--
ALTER TABLE `patrocinios`
  ADD CONSTRAINT `patrocinios_ibfk_1` FOREIGN KEY (`donacion_id`) REFERENCES `ingresos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `provincia`
--
ALTER TABLE `provincia`
  ADD CONSTRAINT `fk_prov_dept` FOREIGN KEY (`iddepartamento`) REFERENCES `departamento` (`iddepartamento`);

--
-- Filtros para la tabla `testimonio_beneficiario`
--
ALTER TABLE `testimonio_beneficiario`
  ADD CONSTRAINT `fk_tb_caso` FOREIGN KEY (`caso_id`) REFERENCES `casos_sociales` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_login` FOREIGN KEY (`idlogin`) REFERENCES `login` (`idlogin`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuario_empresa`
--
ALTER TABLE `usuario_empresa`
  ADD CONSTRAINT `fk_ue_empresa` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ue_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
