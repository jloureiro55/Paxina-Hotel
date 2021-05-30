-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-05-2021 a las 23:56:53
-- Versión del servidor: 10.4.14-MariaDB
-- Versión de PHP: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `teis3_hotel`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitaciones`
--

CREATE TABLE `habitaciones` (
  `id` bigint(20) NOT NULL,
  `m2` decimal(6,2) NOT NULL,
  `ventana` bit(1) NOT NULL DEFAULT b'0',
  `tipo_de_habitacion` bigint(20) NOT NULL,
  `servicio_limpieza` bit(1) NOT NULL DEFAULT b'0',
  `internet` bit(1) NOT NULL DEFAULT b'0',
  `precio` decimal(6,2) NOT NULL,
  `disponibilidad` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `habitaciones`
--

INSERT INTO `habitaciones` (`id`, `m2`, `ventana`, `tipo_de_habitacion`, `servicio_limpieza`, `internet`, `precio`, `disponibilidad`) VALUES
(1, '20.00', b'0', 1, b'0', b'1', '50.00', b'1'),
(2, '20.00', b'0', 1, b'0', b'1', '50.00', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitaciones_reservas`
--

CREATE TABLE `habitaciones_reservas` (
  `id` bigint(20) NOT NULL,
  `num_reserva` bigint(20) NOT NULL,
  `id_habitacion` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `habitaciones_reservas`
--

INSERT INTO `habitaciones_reservas` (`id`, `num_reserva`, `id_habitacion`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitacion_servicio`
--

CREATE TABLE `habitacion_servicio` (
  `id_habitacion` bigint(20) NOT NULL,
  `id_servicio` bigint(20) NOT NULL,
  `fecha_servicio` datetime NOT NULL,
  `fecha_fin_servicio` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitacion_tipo`
--

CREATE TABLE `habitacion_tipo` (
  `id` bigint(20) NOT NULL,
  `tipo_habitacion` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `habitacion_tipo`
--

INSERT INTO `habitacion_tipo` (`id`, `tipo_habitacion`, `descripcion`) VALUES
(1, 'Habitacion doble', 'Habitacion con 2 camas.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes_habitaciones`
--

CREATE TABLE `imagenes_habitaciones` (
  `id` bigint(20) NOT NULL,
  `id_habitacion_tipo` bigint(20) NOT NULL,
  `imagen_habitacion` varchar(255) NOT NULL,
  `descripcion_imagen` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `imagenes_habitaciones`
--

INSERT INTO `imagenes_habitaciones` (`id`, `id_habitacion_tipo`, `imagen_habitacion`, `descripcion_imagen`) VALUES
(1, 1, 'img/habitacion-doble/habitacion_doble.jpg', 'Imagen de la habitación doble');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log`
--

CREATE TABLE `log` (
  `user` bigint(20) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `log`
--

INSERT INTO `log` (`user`, `fecha`) VALUES
(2, '2021-05-29 10:07:00'),
(2, '2021-05-29 18:58:07'),
(2, '2021-05-29 18:58:24'),
(2, '2021-05-29 19:03:21'),
(2, '2021-05-30 07:42:30'),
(2, '2021-05-30 10:44:06'),
(2, '2021-05-30 16:17:33'),
(2, '2021-05-30 21:42:08'),
(2, '2021-05-30 21:48:13'),
(4, '2021-05-30 21:52:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `num_reserva` bigint(20) NOT NULL,
  `id_usuario` bigint(20) NOT NULL,
  `fecha_reserva` timestamp NOT NULL DEFAULT current_timestamp(),
  `num_dias` smallint(20) NOT NULL,
  `fecha_entrada` date NOT NULL,
  `fecha_salida` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`num_reserva`, `id_usuario`, `fecha_reserva`, `num_dias`, `fecha_entrada`, `fecha_salida`) VALUES
(1, 4, '2021-05-28 07:56:33', 3, '2021-05-29', '2021-06-01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre_rol`) VALUES
(1, 'admin'),
(2, 'estandar'),
(3, 'visitante');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` bigint(20) NOT NULL,
  `nombre_servicio` varchar(255) NOT NULL,
  `precio_servicio` decimal(6,2) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `disponibilidad` bit(1) NOT NULL DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `nombre_servicio`, `precio_servicio`, `descripcion`, `disponibilidad`) VALUES
(1, 'Desayuno', '10.00', 'Desayuno en la cafetería del hotel con buffet.', b'1'),
(2, 'Desayuno en habitacion', '15.00', 'Desayuno entregado en habitacion.', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `telf` varchar(9) NOT NULL,
  `direccion` varchar(60) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol_usuario` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `telf`, `direccion`, `password`, `rol_usuario`) VALUES
(2, 'maruxa', 'javierloureiro2a@gmail.com', '664682983', '', '$2y$10$OZzozR0FtFtlCND1PV3RdOv1MMymv.yaSCF..kXeHPg9yixb00mGm', 2),
(4, 'Suso', 'casadesuso666@gmail.com', '662492933', '', '$2y$10$dl/PR0JXoIGuVwwQgNV2C.EXGps6HQr7Et9IP99FEPugNmptAk3ei', 2),
(5, 'susillo', 'susoloko5@gmail.com', '664567899', '', '$2y$10$4gQIde052qOQYXiHcffxCOS0n08Z012F9sBMinAuvSme7n.mggNPC', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_de_habitacion` (`tipo_de_habitacion`) USING BTREE;

--
-- Indices de la tabla `habitaciones_reservas`
--
ALTER TABLE `habitaciones_reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `num_reserva` (`num_reserva`),
  ADD KEY `id_habitacion` (`id_habitacion`);

--
-- Indices de la tabla `habitacion_servicio`
--
ALTER TABLE `habitacion_servicio`
  ADD PRIMARY KEY (`id_habitacion`,`id_servicio`),
  ADD KEY `id_servicio` (`id_servicio`);

--
-- Indices de la tabla `habitacion_tipo`
--
ALTER TABLE `habitacion_tipo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tipo_habitacion` (`tipo_habitacion`);

--
-- Indices de la tabla `imagenes_habitaciones`
--
ALTER TABLE `imagenes_habitaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_habitacion_tipo` (`id_habitacion_tipo`);

--
-- Indices de la tabla `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`user`,`fecha`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`num_reserva`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `nombre_rol` (`nombre_rol`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `telf` (`telf`),
  ADD KEY `rol_usuario` (`rol_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `habitaciones_reservas`
--
ALTER TABLE `habitaciones_reservas`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `habitacion_tipo`
--
ALTER TABLE `habitacion_tipo`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `imagenes_habitaciones`
--
ALTER TABLE `imagenes_habitaciones`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `num_reserva` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD CONSTRAINT `habitaciones_ibfk_1` FOREIGN KEY (`tipo_de_habitacion`) REFERENCES `habitacion_tipo` (`id`);

--
-- Filtros para la tabla `habitaciones_reservas`
--
ALTER TABLE `habitaciones_reservas`
  ADD CONSTRAINT `habitaciones_reservas_ibfk_1` FOREIGN KEY (`num_reserva`) REFERENCES `reservas` (`num_reserva`),
  ADD CONSTRAINT `habitaciones_reservas_ibfk_2` FOREIGN KEY (`id_habitacion`) REFERENCES `habitaciones` (`id`);

--
-- Filtros para la tabla `habitacion_servicio`
--
ALTER TABLE `habitacion_servicio`
  ADD CONSTRAINT `habitacion_servicio_ibfk_1` FOREIGN KEY (`id_habitacion`) REFERENCES `habitaciones` (`id`),
  ADD CONSTRAINT `habitacion_servicio_ibfk_2` FOREIGN KEY (`id_servicio`) REFERENCES `servicios` (`id`);

--
-- Filtros para la tabla `imagenes_habitaciones`
--
ALTER TABLE `imagenes_habitaciones`
  ADD CONSTRAINT `imagenes_habitaciones_ibfk_1` FOREIGN KEY (`id_habitacion_tipo`) REFERENCES `habitacion_tipo` (`id`);

--
-- Filtros para la tabla `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `log_ibfk_1` FOREIGN KEY (`user`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_usuario`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
