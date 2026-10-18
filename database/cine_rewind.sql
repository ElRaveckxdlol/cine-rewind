-- phpMyAdmin SQL Dump
-- Base de datos: `cine_rewind`
-- Versión: 2.2 (IDs corregidos, sin usuarios)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- ============================================
-- BASE DE DATOS
-- ============================================

CREATE DATABASE IF NOT EXISTS `cine_rewind` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `cine_rewind`;

-- ============================================
-- DROP (por si ya existe)
-- ============================================

DROP TABLE IF EXISTS `detalleventa`;
DROP TABLE IF EXISTS `ventas`;
DROP TABLE IF EXISTS `butacas`;
DROP TABLE IF EXISTS `funciones`;
DROP TABLE IF EXISTS `comentarios`;
DROP TABLE IF EXISTS `peliculas`;
DROP TABLE IF EXISTS `salas`;
DROP TABLE IF EXISTS `temas`;
DROP TABLE IF EXISTS `configuracion`;
DROP TABLE IF EXISTS `usuario`;

-- ============================================
-- TABLA: usuario
-- ============================================
CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('usuario','admin') NOT NULL DEFAULT 'usuario',
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA: peliculas
-- ============================================
CREATE TABLE `peliculas` (
  `id_pelicula` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text NOT NULL,
  `duracion` int(11) NOT NULL,
  `genero` varchar(50) NOT NULL,
  `clasificacion` varchar(10) NOT NULL,
  `anio_estreno` int(11) NOT NULL,
  `posterurl` varchar(255) NOT NULL,
  `trailerurl` varchar(255) DEFAULT NULL,
  `puntaje` decimal(3,1) DEFAULT NULL,
  `destacada` tinyint(1) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_pelicula`),
  KEY `idx_titulo` (`titulo`),
  KEY `idx_genero` (`genero`),
  KEY `idx_anio_estreno` (`anio_estreno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA: salas
-- ============================================
CREATE TABLE `salas` (
  `id_sala` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_sala`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA: funciones
-- ============================================
CREATE TABLE `funciones` (
  `id_funcion` int(11) NOT NULL AUTO_INCREMENT,
  `id_pelicula` int(11) NOT NULL,
  `id_sala` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `horario` time NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_funcion`),
  KEY `id_pelicula` (`id_pelicula`),
  KEY `id_sala` (`id_sala`),
  CONSTRAINT `funciones_ibfk_1` FOREIGN KEY (`id_pelicula`) REFERENCES `peliculas` (`id_pelicula`),
  CONSTRAINT `funciones_ibfk_2` FOREIGN KEY (`id_sala`) REFERENCES `salas` (`id_sala`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA: butacas
-- ============================================
CREATE TABLE `butacas` (
  `id_butaca` int(11) NOT NULL AUTO_INCREMENT,
  `id_funcion` int(11) NOT NULL,
  `numero_butaca` varchar(10) NOT NULL,
  `estado` enum('disponible','reservada','ocupada') NOT NULL DEFAULT 'disponible',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_butaca`),
  KEY `id_funcion` (`id_funcion`),
  CONSTRAINT `butacas_ibfk_1` FOREIGN KEY (`id_funcion`) REFERENCES `funciones` (`id_funcion`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA: ventas
-- ============================================
CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `codigo_reserva` varchar(20) NOT NULL,
  `cantidad_entradas` int(11) NOT NULL DEFAULT 1,
  `fechacompra` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `monto_total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','confirmada','cancelada') NOT NULL DEFAULT 'pendiente',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_venta`),
  UNIQUE KEY `codigo_reserva` (`codigo_reserva`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA: detalleventa
-- ============================================
CREATE TABLE `detalleventa` (
  `id_detalleventa` int(11) NOT NULL AUTO_INCREMENT,
  `id_venta` int(11) NOT NULL,
  `id_funcion` int(11) NOT NULL,
  `id_butaca` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `estado` enum('confirmada','cancelada') NOT NULL DEFAULT 'confirmada',
  PRIMARY KEY (`id_detalleventa`),
  KEY `id_venta` (`id_venta`),
  KEY `id_funcion` (`id_funcion`),
  KEY `id_butaca` (`id_butaca`),
  CONSTRAINT `detalleventa_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE CASCADE,
  CONSTRAINT `detalleventa_ibfk_2` FOREIGN KEY (`id_funcion`) REFERENCES `funciones` (`id_funcion`),
  CONSTRAINT `detalleventa_ibfk_3` FOREIGN KEY (`id_butaca`) REFERENCES `butacas` (`id_butaca`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA: comentarios
-- ============================================
CREATE TABLE `comentarios` (
  `id_comentario` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_pelicula` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `puntaje` tinyint(1) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('pendiente','aprobado','rechazado') NOT NULL DEFAULT 'pendiente',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_comentario`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_pelicula` (`id_pelicula`),
  CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`id_pelicula`) REFERENCES `peliculas` (`id_pelicula`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA: temas
-- ============================================
CREATE TABLE `temas` (
  `id_tema` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `color_fondo` varchar(7) NOT NULL DEFAULT '#e9bd96',
  `color_primario` varchar(7) NOT NULL DEFAULT '#1a1a1a',
  `color_acento` varchar(7) NOT NULL DEFAULT '#f5c518',
  `color_acento_hover` varchar(7) NOT NULL DEFAULT '#ffd700',
  `color_texto` varchar(7) NOT NULL DEFAULT '#333333',
  `color_texto_claro` varchar(7) NOT NULL DEFAULT '#ffffff',
  `es_predeterminado` tinyint(1) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_tema`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TABLA: configuracion
-- ============================================
CREATE TABLE `configuracion` (
  `clave` varchar(50) NOT NULL,
  `valor` varchar(255) NOT NULL,
  PRIMARY KEY (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- DATOS PRECARGADOS
-- ============================================

-- Películas
INSERT INTO `peliculas` (`titulo`, `descripcion`, `duracion`, `genero`, `clasificacion`, `anio_estreno`, `posterurl`, `puntaje`, `destacada`) VALUES
('The Breakfast Club', 'Cinco estudiantes muy diferentes son castigados un sábado en la biblioteca. Descubren que tienen más en común de lo que pensaban, rompiendo estereotipos sociales.', 97, 'Comedia Dramática', 'R', 1985, 'breakfastclub.jpg', 4.5, 1),
('Breakfast at Tiffany\'s', 'Basada en la novela de Truman Capote, es una comedia romántica icónica sobre Holly Golightly, una excéntrica y encantadora joven neoyorquina que busca casarse con un millonario.', 115, 'Comedia Romántica', 'PG', 1961, 'breakfast_at_tifannys.webp', 4.2, 1),
('Girl, Interrupted', 'Susanna Kaysen, una joven de 18 años, es ingresada en 1967 a un hospital psiquiátrico tras un intento de suicidio. Allí, conviviendo con un grupo de jóvenes problemáticas, busca entender su trastorno límite de la personalidad.', 127, 'Drama', 'R', 1999, 'GirlInterrupted.jpg', 4.0, 1),
('The Godfather', 'Una saga familiar sobre la mafia italiana en Nueva York. Don Vito Corleone, el jefe de la familia, debe enfrentar amenazas mientras su hijo Michael se involucra en los negocios familiares.', 175, 'Crimen', 'R', 1972, 'thegodfather.jpg', 4.9, 1),
('A Clockwork Orange', 'En un futuro distópico, Alex y su pandilla cometen actos de violencia extrema. Tras ser arrestado, se somete a un experimento de condicionamiento para curar su agresividad.', 136, 'Ciencia Ficción', 'R', 1971, 'orangemecanique.jpg', 4.3, 1);

-- Temas predeterminados
INSERT INTO `temas` (`nombre`, `color_fondo`, `color_primario`, `color_acento`, `color_acento_hover`, `color_texto`, `color_texto_claro`, `es_predeterminado`) VALUES
('Retro Rewind',    '#e9bd96', '#1a1a1a', '#f5c518', '#ffd700', '#333333', '#ffffff', 1),
('Medianoche',      '#1a1a1a', '#0d0d0d', '#ff4d6d', '#ff6b8a', '#e0e0e0', '#ffffff', 1),
('Pastel Cinema',   '#f8e8ee', '#4a2c3a', '#c89ec4', '#e0b8dc', '#4a2c3a', '#ffffff', 1),
('Neón Ochentero',  '#0f0524', '#1a0b3d', '#00f5ff', '#7dfaff', '#e0f7ff', '#ffffff', 1);

-- Configuración inicial: tema Retro Rewind activo
INSERT INTO `configuracion` (`clave`, `valor`) VALUES ('tema_activo', '1');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;