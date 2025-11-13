-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-11-2025 a las 19:45:05
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
-- Base de datos: `restaurante`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `ultima_conexion` time DEFAULT NULL,
  `usuario_id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`admin_id`, `ultima_conexion`, `usuario_id_usuario`) VALUES
(1, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `cliente_id` int(11) NOT NULL,
  `preferencias` varchar(200) DEFAULT NULL,
  `usuario_id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`cliente_id`, `preferencias`, `usuario_id_usuario`) VALUES
(1, '', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `empleado_id` int(11) NOT NULL,
  `estado` enum('activo','inactivo') NOT NULL,
  `salario` decimal(10,2) NOT NULL,
  `usuario_id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`empleado_id`, `estado`, `salario`, `usuario_id_usuario`) VALUES
(2, 'activo', 1000.00, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial`
--

CREATE TABLE `historial` (
  `historial_id` int(11) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `tipo_movimiento` enum('entrada','salida','ajuste') DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `stock_id stock` int(11) NOT NULL,
  `admin_admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `historial`
--

INSERT INTO `historial` (`historial_id`, `descripcion`, `tipo_movimiento`, `cantidad`, `fecha`, `stock_id stock`, `admin_admin_id`) VALUES
(1, 'se rompieron', 'salida', 10, '2025-11-12', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL,
  `tipo` varchar(10) NOT NULL,
  `estado` enum('disponible','no_disponible') NOT NULL,
  `admin_admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`id_menu`, `tipo`, `estado`, `admin_admin_id`) VALUES
(1, 'prueba', 'disponible', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa`
--

CREATE TABLE `mesa` (
  `mesa_id` int(11) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `estado` enum('disponible','ocupada','reservada','mantenimiento') NOT NULL,
  `fecha_asig` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `mesa`
--

INSERT INTO `mesa` (`mesa_id`, `capacidad`, `numero`, `estado`, `fecha_asig`) VALUES
(1, 15, 1, 'disponible', '2025-11-12'),
(2, 20, 2, 'disponible', '2025-11-12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa_cliente`
--

CREATE TABLE `mesa_cliente` (
  `mesa_mesa_id` int(11) NOT NULL,
  `cliente_cliente_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `mesa_cliente`
--

INSERT INTO `mesa_cliente` (`mesa_mesa_id`, `cliente_cliente_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa_empleado`
--

CREATE TABLE `mesa_empleado` (
  `mesa_mesa_id` int(11) NOT NULL,
  `empleado_empleado_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `pedido_id` int(11) NOT NULL,
  `estado` enum('recibido','preparacion','listo','entregado','cancelado') NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `mesa_mesa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`pedido_id`, `estado`, `fecha_hora`, `mesa_mesa_id`) VALUES
(1, 'entregado', '2025-11-12 19:04:17', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_detalle`
--

CREATE TABLE `pedido_detalle` (
  `pedido_pedido_id` int(11) NOT NULL,
  `plato_plato_id` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `pedido_detalle`
--

INSERT INTO `pedido_detalle` (`pedido_pedido_id`, `plato_plato_id`, `cantidad`, `precio_total`) VALUES
(1, 1, 1, 100.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plato`
--

CREATE TABLE `plato` (
  `plato_id` int(11) NOT NULL,
  `nombre` varchar(25) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `menu_id menu` int(11) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `plato`
--

INSERT INTO `plato` (`plato_id`, `nombre`, `descripcion`, `precio`, `menu_id menu`, `imagen`) VALUES
(1, 'prueba', 'aaa', 100.00, 1, '6915040f7d01e_1762984975.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `precio` int(11) NOT NULL,
  `tipo` varchar(30) DEFAULT NULL,
  `admin_admin_id` int(11) NOT NULL,
  `stock_stock_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`producto_id`, `cantidad`, `nombre`, `precio`, `tipo`, `admin_admin_id`, `stock_stock_id`) VALUES
(1, 40, 'c', 10, 'bebida', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promocion`
--

CREATE TABLE `promocion` (
  `promocion_id` int(11) NOT NULL,
  `condiciones` varchar(10) DEFAULT NULL,
  `duracion` date DEFAULT NULL,
  `titulo` varchar(30) NOT NULL,
  `estado` varchar(10) DEFAULT NULL,
  `tipo` enum('descuento','2x1','combo','cumpleaños') NOT NULL,
  `descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `promocion`
--

INSERT INTO `promocion` (`promocion_id`, `condiciones`, `duracion`, `titulo`, `estado`, `tipo`, `descripcion`) VALUES
(1, 'los sabado', '0000-00-00', 'aaaa', 'activa', 'descuento', 'a');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promo_cliente`
--

CREATE TABLE `promo_cliente` (
  `promocion_promocion_id` int(11) NOT NULL,
  `cliente_cliente_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro de visita`
--

CREATE TABLE `registro de visita` (
  `id_visita` int(11) NOT NULL,
  `fecha_hora` datetime(3) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `registro de visita`
--

INSERT INTO `registro de visita` (`id_visita`, `fecha_hora`, `cantidad`) VALUES
(1, '2025-11-12 19:02:16.000', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `id_reserva` int(11) NOT NULL,
  `hora_inicio` time NOT NULL,
  `estado` enum('pendiente','confirmada','cancelada','completada') NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `cliente_cliente_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `reserva`
--

INSERT INTO `reserva` (`id_reserva`, `hora_inicio`, `estado`, `cantidad`, `fecha`, `descripcion`, `cliente_cliente_id`) VALUES
(1, '19:00:00', 'completada', 15, '2025-11-12', '', 1),
(2, '13:08:00', 'pendiente', 10, '2025-11-13', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE `stock` (
  `stock_id` int(11) NOT NULL,
  `unidad_medida` varchar(45) DEFAULT NULL,
  `cantidad_total` int(11) DEFAULT NULL,
  `ultima_actu` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `stock`
--

INSERT INTO `stock` (`stock_id`, `unidad_medida`, `cantidad_total`, `ultima_actu`) VALUES
(1, 'litros', 40, '2025-11-12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `telefono`
--

CREATE TABLE `telefono` (
  `id_telefono` int(11) NOT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `cliente_cliente_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `telefono`
--

INSERT INTO `telefono` (`id_telefono`, `telefono`, `cliente_cliente_id`) VALUES
(1, '1234576786', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `nombre` varchar(20) NOT NULL,
  `fecha_nac` date DEFAULT NULL,
  `gmail` varchar(100) NOT NULL,
  `contraseña` varchar(100) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `nacionalidad` varchar(45) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`nombre`, `fecha_nac`, `gmail`, `contraseña`, `apellido`, `fecha_registro`, `nacionalidad`, `id_usuario`) VALUES
('admin', NULL, 'admin@gmail.com', '123456', 'admin1', '2025-11-09 20:38:06', 'Uruguay', 1),
('pedro', '2000-05-05', 'cliente@gmail.com', '123456', 'juan', '2025-11-12 18:59:21', 'Afganistán', 3),
('pedro500', '2000-05-05', 'empleado@gmail.com', '123456', 'juan', '2025-11-12 19:01:13', 'uruguay', 4);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`,`usuario_id_usuario`),
  ADD KEY `fk_admin_usuario1_idx` (`usuario_id_usuario`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`cliente_id`,`usuario_id_usuario`),
  ADD KEY `fk_cliente_usuario1_idx` (`usuario_id_usuario`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`empleado_id`,`usuario_id_usuario`),
  ADD KEY `fk_empleado_usuario1_idx` (`usuario_id_usuario`);

--
-- Indices de la tabla `historial`
--
ALTER TABLE `historial`
  ADD PRIMARY KEY (`historial_id`,`stock_id stock`,`admin_admin_id`),
  ADD KEY `fk_historial_stock1_idx` (`stock_id stock`),
  ADD KEY `fk_historial_admin1_idx` (`admin_admin_id`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`,`admin_admin_id`),
  ADD KEY `fk_menu_admin1_idx` (`admin_admin_id`);

--
-- Indices de la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`mesa_id`);

--
-- Indices de la tabla `mesa_cliente`
--
ALTER TABLE `mesa_cliente`
  ADD PRIMARY KEY (`mesa_mesa_id`,`cliente_cliente_id`),
  ADD KEY `fk_mesa_has_cliente_cliente1_idx` (`cliente_cliente_id`),
  ADD KEY `fk_mesa_has_cliente_mesa1_idx` (`mesa_mesa_id`);

--
-- Indices de la tabla `mesa_empleado`
--
ALTER TABLE `mesa_empleado`
  ADD PRIMARY KEY (`mesa_mesa_id`,`empleado_empleado_id`),
  ADD KEY `fk_mesa_has_empleado_empleado1_idx` (`empleado_empleado_id`),
  ADD KEY `fk_mesa_has_empleado_mesa1_idx` (`mesa_mesa_id`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`pedido_id`,`mesa_mesa_id`),
  ADD KEY `fk_pedido_mesa1_idx` (`mesa_mesa_id`);

--
-- Indices de la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  ADD PRIMARY KEY (`pedido_pedido_id`,`plato_plato_id`),
  ADD KEY `fk_pedido_detalle_plato1_idx` (`plato_plato_id`);

--
-- Indices de la tabla `plato`
--
ALTER TABLE `plato`
  ADD PRIMARY KEY (`plato_id`,`menu_id menu`),
  ADD KEY `fk_plato_menu1_idx` (`menu_id menu`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`producto_id`,`admin_admin_id`,`stock_stock_id`),
  ADD KEY `fk_producto_admin1_idx` (`admin_admin_id`),
  ADD KEY `fk_producto_stock1_idx` (`stock_stock_id`);

--
-- Indices de la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD PRIMARY KEY (`promocion_id`);

--
-- Indices de la tabla `promo_cliente`
--
ALTER TABLE `promo_cliente`
  ADD PRIMARY KEY (`promocion_promocion_id`,`cliente_cliente_id`),
  ADD KEY `fk_promocion_has_cliente_cliente1_idx` (`cliente_cliente_id`),
  ADD KEY `fk_promocion_has_cliente_promocion1_idx` (`promocion_promocion_id`);

--
-- Indices de la tabla `registro de visita`
--
ALTER TABLE `registro de visita`
  ADD PRIMARY KEY (`id_visita`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`id_reserva`,`cliente_cliente_id`),
  ADD KEY `fk_reserva_cliente1_idx` (`cliente_cliente_id`);

--
-- Indices de la tabla `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`stock_id`);

--
-- Indices de la tabla `telefono`
--
ALTER TABLE `telefono`
  ADD PRIMARY KEY (`id_telefono`,`cliente_cliente_id`),
  ADD KEY `fk_telefono_cliente1_idx` (`cliente_cliente_id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `gmail_UNIQUE` (`gmail`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `cliente_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `empleado_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `historial`
--
ALTER TABLE `historial`
  MODIFY `historial_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `mesa`
--
ALTER TABLE `mesa`
  MODIFY `mesa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `pedido_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `plato`
--
ALTER TABLE `plato`
  MODIFY `plato_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `producto_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `promocion`
--
ALTER TABLE `promocion`
  MODIFY `promocion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `registro de visita`
--
ALTER TABLE `registro de visita`
  MODIFY `id_visita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `telefono`
--
ALTER TABLE `telefono`
  MODIFY `id_telefono` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `fk_admin_usuario1` FOREIGN KEY (`usuario_id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `fk_cliente_usuario1` FOREIGN KEY (`usuario_id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `fk_empleado_usuario1` FOREIGN KEY (`usuario_id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `historial`
--
ALTER TABLE `historial`
  ADD CONSTRAINT `fk_historial_admin1` FOREIGN KEY (`admin_admin_id`) REFERENCES `admin` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_historial_stock1` FOREIGN KEY (`stock_id stock`) REFERENCES `stock` (`stock_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `fk_menu_admin1` FOREIGN KEY (`admin_admin_id`) REFERENCES `admin` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mesa_cliente`
--
ALTER TABLE `mesa_cliente`
  ADD CONSTRAINT `fk_mesa_has_cliente_cliente1` FOREIGN KEY (`cliente_cliente_id`) REFERENCES `cliente` (`cliente_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mesa_has_cliente_mesa1` FOREIGN KEY (`mesa_mesa_id`) REFERENCES `mesa` (`mesa_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mesa_empleado`
--
ALTER TABLE `mesa_empleado`
  ADD CONSTRAINT `fk_mesa_has_empleado_empleado1` FOREIGN KEY (`empleado_empleado_id`) REFERENCES `empleado` (`empleado_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mesa_has_empleado_mesa1` FOREIGN KEY (`mesa_mesa_id`) REFERENCES `mesa` (`mesa_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `fk_pedido_mesa1` FOREIGN KEY (`mesa_mesa_id`) REFERENCES `mesa` (`mesa_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  ADD CONSTRAINT `fk_pedido_detalle_pedido1` FOREIGN KEY (`pedido_pedido_id`) REFERENCES `pedido` (`pedido_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pedido_detalle_plato1` FOREIGN KEY (`plato_plato_id`) REFERENCES `plato` (`plato_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `plato`
--
ALTER TABLE `plato`
  ADD CONSTRAINT `fk_plato_menu1` FOREIGN KEY (`menu_id menu`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `fk_producto_admin1` FOREIGN KEY (`admin_admin_id`) REFERENCES `admin` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_producto_stock1` FOREIGN KEY (`stock_stock_id`) REFERENCES `stock` (`stock_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `promo_cliente`
--
ALTER TABLE `promo_cliente`
  ADD CONSTRAINT `fk_promocion_has_cliente_cliente1` FOREIGN KEY (`cliente_cliente_id`) REFERENCES `cliente` (`cliente_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_promocion_has_cliente_promocion1` FOREIGN KEY (`promocion_promocion_id`) REFERENCES `promocion` (`promocion_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `fk_reserva_cliente1` FOREIGN KEY (`cliente_cliente_id`) REFERENCES `cliente` (`cliente_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `telefono`
--
ALTER TABLE `telefono`
  ADD CONSTRAINT `fk_telefono_cliente1` FOREIGN KEY (`cliente_cliente_id`) REFERENCES `cliente` (`cliente_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;