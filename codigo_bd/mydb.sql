-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-09-2025 a las 21:21:25
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
-- Base de datos: `mydb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `id administrador` int(11) NOT NULL,
  `ultima conexion` time DEFAULT NULL,
  `menu_id menu` int(11) NOT NULL,
  `usuario_id usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador_has_stock`
--

CREATE TABLE `administrador_has_stock` (
  `administrador_id administrador` int(11) NOT NULL,
  `administrador_menu_id menu` int(11) NOT NULL,
  `stock_id stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id cliente` int(11) NOT NULL,
  `nacionalidad` varchar(50) NOT NULL,
  `usuario_id usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id cliente`, `nacionalidad`, `usuario_id usuario`) VALUES
(1, 'Ghana', 1),
(3, 'Malasia', 4),
(4, 'uruguayo', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_has_promocion`
--

CREATE TABLE `cliente_has_promocion` (
  `cliente_id cliente` int(11) NOT NULL,
  `cliente_usuario_id usuario` int(11) NOT NULL,
  `promocion_id promocion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `id empleado` int(11) NOT NULL,
  `estado` varchar(10) NOT NULL,
  `fecha de ingreso` date NOT NULL,
  `usuario_id usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadistica de venta`
--

CREATE TABLE `estadistica de venta` (
  `estadistica id` int(11) NOT NULL,
  `tipo de estadistica` varchar(30) DEFAULT NULL,
  `tiempo` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial`
--

CREATE TABLE `historial` (
  `numero de historial` int(11) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `tipo de movimiento` varchar(15) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `stock_id stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `id menu` int(11) NOT NULL,
  `tipo` varchar(10) NOT NULL,
  `estado` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa`
--

CREATE TABLE `mesa` (
  `id mesa` int(11) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `estado` varchar(15) NOT NULL,
  `fecha de asignacion` date NOT NULL,
  `cliente_id cliente` int(11) NOT NULL,
  `cliente_usuario_id usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa_has_empleado`
--

CREATE TABLE `mesa_has_empleado` (
  `mesa_id mesa` int(11) NOT NULL,
  `mesa_cliente_id cliente` int(11) NOT NULL,
  `mesa_cliente_usuario_id usuario` int(11) NOT NULL,
  `empleado_id empleado` int(11) NOT NULL,
  `empleado_usuario_id usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa_has_pedido`
--

CREATE TABLE `mesa_has_pedido` (
  `mesa_id mesa` int(11) NOT NULL,
  `mesa_cliente_id cliente` int(11) NOT NULL,
  `mesa_cliente_usuario_id usuario` int(11) NOT NULL,
  `pedido_id pedido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id pedido` int(11) NOT NULL,
  `estado` varchar(15) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_has_platos`
--

CREATE TABLE `pedido_has_platos` (
  `pedido_id pedido` int(11) NOT NULL,
  `platos_id platos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `platos`
--

CREATE TABLE `platos` (
  `id platos` int(11) NOT NULL,
  `nombre` varchar(25) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `precio` decimal(10,0) NOT NULL,
  `menu_id menu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `nombre` varchar(15) NOT NULL,
  `precio` int(11) NOT NULL,
  `tipo de producto` varchar(15) DEFAULT NULL,
  `stock_id stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promocion`
--

CREATE TABLE `promocion` (
  `id promocion` int(11) NOT NULL,
  `condiciones` varchar(10) DEFAULT NULL,
  `duracion` date DEFAULT NULL,
  `titulo` varchar(15) NOT NULL,
  `estado` varchar(10) DEFAULT NULL,
  `tipo` varchar(15) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro de visita`
--

CREATE TABLE `registro de visita` (
  `id visita` int(11) NOT NULL,
  `fecha hora` datetime(3) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `id reserva` int(11) NOT NULL,
  `hora de inicio` time NOT NULL,
  `estado` varchar(10) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `cliente_id cliente` int(11) NOT NULL,
  `cliente_usuario_id usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `reserva`
--

INSERT INTO `reserva` (`id reserva`, `hora de inicio`, `estado`, `cantidad`, `fecha`, `cliente_id cliente`, `cliente_usuario_id usuario`) VALUES
(1, '17:29:00', '', 15, '2025-10-05', 4, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE `stock` (
  `id stock` int(11) NOT NULL,
  `unidad de medida` varchar(45) DEFAULT NULL,
  `cantidad total` int(11) DEFAULT NULL,
  `ultima actualizacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `telefono`
--

CREATE TABLE `telefono` (
  `idtelefono` int(11) NOT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `cliente_id cliente` int(11) NOT NULL,
  `cliente_usuario_id usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `telefono`
--

INSERT INTO `telefono` (`idtelefono`, `telefono`, `cliente_id cliente`, `cliente_usuario_id usuario`) VALUES
(1, 'no', 1, 1),
(2, 'aveces', 3, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id usuario` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `fecha de nacimiento` date DEFAULT NULL,
  `gmail` varchar(100) NOT NULL,
  `contraseña` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id usuario`, `nombre`, `fecha de nacimiento`, `gmail`, `contraseña`) VALUES
(1, 'pedro', '2135-06-12', 'ghjjbjg@gmail.com', '123456789'),
(3, 'fernandez', '2135-06-12', 'yona@gmail.com', 'fern'),
(4, 'yonaa', '2135-06-01', 'fhfhf@gmai.com', '1222333');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id administrador`,`menu_id menu`,`usuario_id usuario`),
  ADD KEY `fk_administrador_menu1_idx` (`menu_id menu`),
  ADD KEY `fk_administrador_usuario1_idx` (`usuario_id usuario`);

--
-- Indices de la tabla `administrador_has_stock`
--
ALTER TABLE `administrador_has_stock`
  ADD PRIMARY KEY (`administrador_id administrador`,`administrador_menu_id menu`,`stock_id stock`),
  ADD KEY `fk_administrador_has_stock_stock1_idx` (`stock_id stock`),
  ADD KEY `fk_administrador_has_stock_administrador1_idx` (`administrador_id administrador`,`administrador_menu_id menu`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id cliente`,`usuario_id usuario`),
  ADD UNIQUE KEY `gmail_UNIQUE` (`nacionalidad`),
  ADD KEY `fk_cliente_usuario1_idx` (`usuario_id usuario`);

--
-- Indices de la tabla `cliente_has_promocion`
--
ALTER TABLE `cliente_has_promocion`
  ADD PRIMARY KEY (`cliente_id cliente`,`cliente_usuario_id usuario`,`promocion_id promocion`),
  ADD KEY `fk_cliente_has_promocion_promocion1_idx` (`promocion_id promocion`),
  ADD KEY `fk_cliente_has_promocion_cliente1_idx` (`cliente_id cliente`,`cliente_usuario_id usuario`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id empleado`,`usuario_id usuario`),
  ADD KEY `fk_empleado_usuario1_idx` (`usuario_id usuario`);

--
-- Indices de la tabla `estadistica de venta`
--
ALTER TABLE `estadistica de venta`
  ADD PRIMARY KEY (`estadistica id`);

--
-- Indices de la tabla `historial`
--
ALTER TABLE `historial`
  ADD PRIMARY KEY (`numero de historial`,`stock_id stock`),
  ADD KEY `fk_historial_stock1_idx` (`stock_id stock`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id menu`);

--
-- Indices de la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`id mesa`,`cliente_id cliente`,`cliente_usuario_id usuario`),
  ADD KEY `fk_mesa_cliente1_idx` (`cliente_id cliente`,`cliente_usuario_id usuario`);

--
-- Indices de la tabla `mesa_has_empleado`
--
ALTER TABLE `mesa_has_empleado`
  ADD PRIMARY KEY (`mesa_id mesa`,`mesa_cliente_id cliente`,`mesa_cliente_usuario_id usuario`,`empleado_id empleado`,`empleado_usuario_id usuario`),
  ADD KEY `fk_mesa_has_empleado_empleado1_idx` (`empleado_id empleado`,`empleado_usuario_id usuario`),
  ADD KEY `fk_mesa_has_empleado_mesa1_idx` (`mesa_id mesa`,`mesa_cliente_id cliente`,`mesa_cliente_usuario_id usuario`);

--
-- Indices de la tabla `mesa_has_pedido`
--
ALTER TABLE `mesa_has_pedido`
  ADD PRIMARY KEY (`mesa_id mesa`,`mesa_cliente_id cliente`,`mesa_cliente_usuario_id usuario`,`pedido_id pedido`),
  ADD KEY `fk_mesa_has_pedido_pedido1_idx` (`pedido_id pedido`),
  ADD KEY `fk_mesa_has_pedido_mesa1_idx` (`mesa_id mesa`,`mesa_cliente_id cliente`,`mesa_cliente_usuario_id usuario`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id pedido`);

--
-- Indices de la tabla `pedido_has_platos`
--
ALTER TABLE `pedido_has_platos`
  ADD PRIMARY KEY (`pedido_id pedido`,`platos_id platos`),
  ADD KEY `fk_pedido_has_platos_platos1_idx` (`platos_id platos`),
  ADD KEY `fk_pedido_has_platos_pedido1_idx` (`pedido_id pedido`);

--
-- Indices de la tabla `platos`
--
ALTER TABLE `platos`
  ADD PRIMARY KEY (`id platos`,`menu_id menu`),
  ADD KEY `fk_platos_menu1_idx` (`menu_id menu`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id producto`,`stock_id stock`),
  ADD KEY `fk_producto_stock1_idx` (`stock_id stock`);

--
-- Indices de la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD PRIMARY KEY (`id promocion`);

--
-- Indices de la tabla `registro de visita`
--
ALTER TABLE `registro de visita`
  ADD PRIMARY KEY (`id visita`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`id reserva`,`cliente_id cliente`,`cliente_usuario_id usuario`),
  ADD KEY `fk_reserva_cliente1_idx` (`cliente_id cliente`,`cliente_usuario_id usuario`);

--
-- Indices de la tabla `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id stock`);

--
-- Indices de la tabla `telefono`
--
ALTER TABLE `telefono`
  ADD PRIMARY KEY (`idtelefono`,`cliente_id cliente`,`cliente_usuario_id usuario`),
  ADD KEY `fk_telefono_cliente1_idx` (`cliente_id cliente`,`cliente_usuario_id usuario`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id usuario`),
  ADD UNIQUE KEY `gmail_UNIQUE` (`gmail`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `id administrador` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id empleado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estadistica de venta`
--
ALTER TABLE `estadistica de venta`
  MODIFY `estadistica id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial`
--
ALTER TABLE `historial`
  MODIFY `numero de historial` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `id menu` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mesa`
--
ALTER TABLE `mesa`
  MODIFY `id mesa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `platos`
--
ALTER TABLE `platos`
  MODIFY `id platos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id producto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `promocion`
--
ALTER TABLE `promocion`
  MODIFY `id promocion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `registro de visita`
--
ALTER TABLE `registro de visita`
  MODIFY `id visita` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `id reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
  MODIFY `id stock` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `telefono`
--
ALTER TABLE `telefono`
  MODIFY `idtelefono` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD CONSTRAINT `fk_administrador_menu1` FOREIGN KEY (`menu_id menu`) REFERENCES `menu` (`id menu`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_administrador_usuario1` FOREIGN KEY (`usuario_id usuario`) REFERENCES `usuario` (`id usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `administrador_has_stock`
--
ALTER TABLE `administrador_has_stock`
  ADD CONSTRAINT `fk_administrador_has_stock_administrador1` FOREIGN KEY (`administrador_id administrador`,`administrador_menu_id menu`) REFERENCES `administrador` (`id administrador`, `menu_id menu`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_administrador_has_stock_stock1` FOREIGN KEY (`stock_id stock`) REFERENCES `stock` (`id stock`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `fk_cliente_usuario1` FOREIGN KEY (`usuario_id usuario`) REFERENCES `usuario` (`id usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `cliente_has_promocion`
--
ALTER TABLE `cliente_has_promocion`
  ADD CONSTRAINT `fk_cliente_has_promocion_cliente1` FOREIGN KEY (`cliente_id cliente`,`cliente_usuario_id usuario`) REFERENCES `cliente` (`id cliente`, `usuario_id usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cliente_has_promocion_promocion1` FOREIGN KEY (`promocion_id promocion`) REFERENCES `promocion` (`id promocion`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `fk_empleado_usuario1` FOREIGN KEY (`usuario_id usuario`) REFERENCES `usuario` (`id usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `historial`
--
ALTER TABLE `historial`
  ADD CONSTRAINT `fk_historial_stock1` FOREIGN KEY (`stock_id stock`) REFERENCES `stock` (`id stock`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD CONSTRAINT `fk_mesa_cliente1` FOREIGN KEY (`cliente_id cliente`,`cliente_usuario_id usuario`) REFERENCES `cliente` (`id cliente`, `usuario_id usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `mesa_has_empleado`
--
ALTER TABLE `mesa_has_empleado`
  ADD CONSTRAINT `fk_mesa_has_empleado_empleado1` FOREIGN KEY (`empleado_id empleado`,`empleado_usuario_id usuario`) REFERENCES `empleado` (`id empleado`, `usuario_id usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_mesa_has_empleado_mesa1` FOREIGN KEY (`mesa_id mesa`,`mesa_cliente_id cliente`,`mesa_cliente_usuario_id usuario`) REFERENCES `mesa` (`id mesa`, `cliente_id cliente`, `cliente_usuario_id usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `mesa_has_pedido`
--
ALTER TABLE `mesa_has_pedido`
  ADD CONSTRAINT `fk_mesa_has_pedido_mesa1` FOREIGN KEY (`mesa_id mesa`,`mesa_cliente_id cliente`,`mesa_cliente_usuario_id usuario`) REFERENCES `mesa` (`id mesa`, `cliente_id cliente`, `cliente_usuario_id usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_mesa_has_pedido_pedido1` FOREIGN KEY (`pedido_id pedido`) REFERENCES `pedido` (`id pedido`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pedido_has_platos`
--
ALTER TABLE `pedido_has_platos`
  ADD CONSTRAINT `fk_pedido_has_platos_pedido1` FOREIGN KEY (`pedido_id pedido`) REFERENCES `pedido` (`id pedido`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pedido_has_platos_platos1` FOREIGN KEY (`platos_id platos`) REFERENCES `platos` (`id platos`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `platos`
--
ALTER TABLE `platos`
  ADD CONSTRAINT `fk_platos_menu1` FOREIGN KEY (`menu_id menu`) REFERENCES `menu` (`id menu`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `fk_producto_stock1` FOREIGN KEY (`stock_id stock`) REFERENCES `stock` (`id stock`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `fk_reserva_cliente1` FOREIGN KEY (`cliente_id cliente`,`cliente_usuario_id usuario`) REFERENCES `cliente` (`id cliente`, `usuario_id usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `telefono`
--
ALTER TABLE `telefono`
  ADD CONSTRAINT `fk_telefono_cliente1` FOREIGN KEY (`cliente_id cliente`,`cliente_usuario_id usuario`) REFERENCES `cliente` (`id cliente`, `usuario_id usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
