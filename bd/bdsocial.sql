-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-03-2026 a las 00:24:07
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
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`idempresa`),
  ADD UNIQUE KEY `ruc` (`ruc`);

--
-- Indices de la tabla `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`idlogin`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

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
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `idempresa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `login`
--
ALTER TABLE `login`
  MODIFY `idlogin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

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
