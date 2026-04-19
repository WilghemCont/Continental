-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-04-2026 a las 22:53:43
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

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

INSERT INTO `donaciones` (`id`, `nombre`, `email`, `monto`, `metodo`, `mensaje`, `fecha`) VALUES
(9, 'JERI SANTILLANA WILGHEM GIOVANNY', 'wjeri77@gmail.com', 5000.00, 'Yape/Plin', 'donacion yape', '2026-04-12 01:47:46'),
(10, 'WILGHEM GIOVANNY', 'wjeri77@gmail.com', 2000.00, 'Transferencia', 'donacion por transferencia', '2026-04-12 01:50:51'),
(11, 'wilghem jeri', 'wjeri77@gmail.com', 1000.00, 'Efectivo', 'donacion en efectivo', '2026-04-12 01:53:35'),
(12, 'JERI SANTILLANA WILGHEM GIOVANNY', 'wjeri77@gmail.com', 50000.00, 'Yape/Plin', 'asd', '2026-04-12 02:13:18'),
(13, 'JERI SANTILLANA WILGHEM GIOVANNY', 'wjeri77@gmail.com', 20.00, 'Efectivo', '', '2026-04-12 02:15:37');

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
(27, 'Patrocinio', 'Publicidad', 'peru2', 'patrocinio  de prueba', 5000.00, 400.00, NULL, '2026-04-19');

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
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`idempresa`),
  ADD UNIQUE KEY `ruc` (`ruc`);

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
-- Indices de la tabla `provincia`
--
ALTER TABLE `provincia`
  ADD PRIMARY KEY (`idprovincia`),
  ADD KEY `fk_prov_dept` (`iddepartamento`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `idempresa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `login`
--
ALTER TABLE `login`
  MODIFY `idlogin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pais`
--
ALTER TABLE `pais`
  MODIFY `idpais` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `provincia`
--
ALTER TABLE `provincia`
  MODIFY `idprovincia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  MODIFY `idtipodoc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

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
-- Filtros para la tabla `provincia`
--
ALTER TABLE `provincia`
  ADD CONSTRAINT `fk_prov_dept` FOREIGN KEY (`iddepartamento`) REFERENCES `departamento` (`iddepartamento`);

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
