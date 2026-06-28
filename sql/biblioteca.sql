-- sql/biblioteca.sql
-- Script para crear la base de datos y las tablas de la Biblioteca App

-- Crear la base de datos (si no existe)
CREATE DATABASE IF NOT EXISTS `biblioteca_app` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `biblioteca_app`;

--
-- Estructura de la tabla `usuarios`
--
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin') NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Estructura de la tabla `libros`
--
CREATE TABLE `libros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `autor` varchar(100) NOT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `sinopsis` text DEFAULT NULL,
  `portada` varchar(255) DEFAULT 'public/images/default_cover.jpg',
  `cantidad_total` int(11) NOT NULL DEFAULT 0,
  `cantidad_disponible` int(11) NOT NULL DEFAULT 0,
  `ubicacion_fisica` varchar(100) DEFAULT NULL,
  `pdf_ruta` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Estructura de la tabla `prestamos`
--
CREATE TABLE `prestamos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libro_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_prestamo` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_devolucion_estimada` timestamp NOT NULL,
  `fecha_devolucion_real` timestamp NULL DEFAULT NULL,
  `estado` enum('prestado','devuelto','retrasado') NOT NULL,
  `multa` decimal(10,2) DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `libro_id` (`libro_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`) ON DELETE CASCADE,
  CONSTRAINT `prestamos_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Estructura de la tabla `tareas`
--
CREATE TABLE `tareas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `usuario_asignado_id` int(11) NOT NULL,
  `libro_relacionado_id` int(11) DEFAULT NULL,
  `fecha_asignacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_limite` date DEFAULT NULL,
  `estado` enum('pendiente','completada') NOT NULL DEFAULT 'pendiente',
  `respuesta` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_asignado_id` (`usuario_asignado_id`),
  KEY `libro_relacionado_id` (`libro_relacionado_id`),
  CONSTRAINT `tareas_ibfk_1` FOREIGN KEY (`usuario_asignado_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tareas_ibfk_2` FOREIGN KEY (`libro_relacionado_id`) REFERENCES `libros` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Inserción de datos de prueba
--
-- NOTA: La inserción de usuarios se realiza a través del script `sql/seed.php`
-- para garantizar que las contraseñas se hasheen correctamente.
--

-- Libros de prueba
INSERT INTO `libros` (`titulo`, `autor`, `isbn`, `categoria`, `sinopsis`, `portada`, `cantidad_total`, `cantidad_disponible`, `ubicacion_fisica`) VALUES
('Don Quijote de la Mancha', 'Miguel de Cervantes', '978-84-08-06105-2', 'Clásico', 'La historia de un hidalgo que enloquece leyendo libros de caballerías.', 'public/images/covers/cover1.jpg', 5, 5, 'Pasillo A, Estante 1'),
('Cien Años de Soledad', 'Gabriel García Márquez', '978-84-376-0494-7', 'Realismo Mágico', 'La saga de la familia Buendía en el pueblo ficticio de Macondo.', 'public/images/covers/cover2.jpg', 3, 3, 'Pasillo B, Estante 3'),
('La Sombra del Viento', 'Carlos Ruiz Zafón', '978-84-08-04364-5', 'Misterio', 'Un joven encuentra un libro maldito en el Cementerio de los Libros Olvidados.', 'public/images/covers/cover3.jpg', 7, 7, 'Pasillo C, Estante 2'),
('1984', 'George Orwell', '978-84-9989-094-4', 'Distopía', 'Una visión sombría de un futuro totalitario donde el pensamiento es controlado.', 'public/images/covers/cover4.jpg', 4, 4, 'Pasillo A, Estante 2'),
('El Principito', 'Antoine de Saint-Exupéry', '978-84-9838-149-8', 'Infantil', 'Un piloto se encuentra con un joven príncipe que ha caído a la Tierra desde un pequeño asteroide.', 'public/images/covers/cover5.jpg', 10, 10, 'Sección Infantil');

