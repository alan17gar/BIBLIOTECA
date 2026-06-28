-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: btguvsqpmzkocvxuyht7-mysql.services.clever-cloud.com:3306
-- Tiempo de generación: 28-06-2026 a las 03:27:43
-- Versión del servidor: 8.0.22-13
-- Versión de PHP: 8.2.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `btguvsqpmzkocvxuyht7`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id` int NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `anio_secundaria` enum('1er Año','2do Año','3er Año','4to Año','5to Año') NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id`, `cedula`, `nombre_completo`, `anio_secundaria`, `fecha_registro`) VALUES
(1, '30966036', 'Alan Garcia', '2do Año', '2026-06-26 00:11:49'),
(3, '30973610', 'Maria Garcia', '5to Año', '2026-06-26 00:47:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
  `id` int NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `autor` varchar(100) NOT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `sinopsis` text,
  `portada` varchar(255) DEFAULT 'public/images/default_cover.jpg',
  `cantidad_total` int NOT NULL DEFAULT '0',
  `cantidad_disponible` int NOT NULL DEFAULT '0',
  `ubicacion_fisica` varchar(100) DEFAULT NULL,
  `pdf_ruta` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO `libros` (`id`, `titulo`, `autor`, `isbn`, `categoria`, `sinopsis`, `portada`, `cantidad_total`, `cantidad_disponible`, `ubicacion_fisica`, `pdf_ruta`) VALUES
(1, 'Don Quijote de la Mancha', 'Miguel de Cervantes', '978-84-08-06105-2', 'Clásico', 'La historia de un hidalgo que enloquece leyendo libros de caballerías.', 'public/images/covers/cover1.jpg', 5, 5, 'Pasillo A, Estante 1', NULL),
(2, 'Cien Años de Soledad', 'Gabriel García Márquez', '978-84-376-0494-7', 'Realismo Mágico', 'La saga de la familia Buendía en el pueblo ficticio de Macondo.', 'public/images/covers/cover2.jpg', 3, 3, 'Pasillo B, Estante 3', NULL),
(3, 'La Sombra del Viento', 'Carlos Ruiz Zafón', '978-84-08-04364-5', 'Misterio', 'Un joven encuentra un libro maldito en el Cementerio de los Libros Olvidados.', 'public/images/covers/cover3.jpg', 7, 7, 'Pasillo C, Estante 2', NULL),
(4, '1984', 'George Orwell', '978-84-9989-094-4', 'Distopía', 'Una visión sombría de un futuro totalitario donde el pensamiento es controlado.', 'public/images/covers/cover4.jpg', 4, 4, 'Pasillo A, Estante 2', NULL),
(5, 'El Principito', 'Antoine de Saint-Exupéry', '978-84-9838-149-8', 'Infantil', 'Un piloto se encuentra con un joven príncipe que ha caído a la Tierra desde un pequeño asteroide.', 'public/images/covers/cover5.jpg', 10, 8, 'Sección Infantil', NULL),
(7, 'Cincuenta sombras de grey', 'Erika Leonard Mitchell', '90001910291', 'Romance', 'Cuando la estudiante de Literatura Anastasia Steele recibe el encargo de entrevistar al exitoso y joven empresario Christian Grey, queda impresionada al encontrarse ante un hombre atractivo, seductor y también muy intimidante. La inexperta e inocente Ana intenta olvidarle, pero pronto comprende cuánto le desea. Cuando la pareja por fin inicia una apasionada relación, Ana se sorprende por las peculiares prácticas eróticas de Grey, al tiempo que descubre los límites de sus propios y más oscuros deseos…', 'uploads/covers/546987.jpg', 4, 4, 'estante 4', 'uploads/pdfs/Cincuenta_sombras_de_Grey_E_L_James.pdf');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `id` int NOT NULL,
  `libro_id` int NOT NULL,
  `estudiante_id` int NOT NULL,
  `fecha_prestamo` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_devolucion_estimada` timestamp NOT NULL,
  `fecha_devolucion_real` timestamp NULL DEFAULT NULL,
  `estado` enum('prestado','devuelto','retrasado') NOT NULL,
  `ubicacion_lectura` enum('Biblioteca','Aula con Profesor','Hogar') NOT NULL,
  `multa` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO `prestamos` (`id`, `libro_id`, `estudiante_id`, `fecha_prestamo`, `fecha_devolucion_estimada`, `fecha_devolucion_real`, `estado`, `ubicacion_lectura`, `multa`) VALUES
(1, 2, 1, '2026-06-26 00:12:00', '2026-07-11 00:12:00', '2026-06-26 00:47:02', 'devuelto', 'Biblioteca', 0.00),
(2, 2, 3, '2026-06-26 00:48:04', '2026-07-11 00:48:04', '2026-06-26 00:52:47', 'devuelto', 'Hogar', 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `id` int NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `usuario_asignado_id` int NOT NULL,
  `libro_relacionado_id` int DEFAULT NULL,
  `fecha_asignacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_limite` date DEFAULT NULL,
  `estado` enum('pendiente','completada') NOT NULL DEFAULT 'pendiente',
  `respuesta` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','student') NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `password`, `rol`, `nombre_completo`, `correo`, `fecha_creacion`) VALUES
(1, 'admin', '$2y$10$MPUOWVRjaxHp5ofRwdxo2OF8dXSyJdnnjqy5670RS1oTX5mrnwKM2', 'admin', 'Administrador del Sistema', 'admin@biblioteca.app', '2026-06-25 06:35:42'),
(7, 'alan17gar', '$2y$10$tTe6hfqgV.tLuRYX/ELme.A3PCdqjuh3S0F/UCJFUIAD1bIzpD.ny', 'admin', 'Alan Hernandez', 'alanmem16gar@gmail.com', '2026-06-25 18:41:11');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`);

--
-- Indices de la tabla `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `libro_id` (`libro_id`),
  ADD KEY `prestamos_ibfk_estudiante` (`estudiante_id`);

--
-- Indices de la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_asignado_id` (`usuario_asignado_id`),
  ADD KEY `libro_relacionado_id` (`libro_relacionado_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prestamos_ibfk_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD CONSTRAINT `tareas_ibfk_1` FOREIGN KEY (`usuario_asignado_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tareas_ibfk_2` FOREIGN KEY (`libro_relacionado_id`) REFERENCES `libros` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
