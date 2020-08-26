-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-08-2017 a las 13:39:30
-- Versión del servidor: 10.1.21-MariaDB
-- Versión de PHP: 7.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `planillas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

CREATE TABLE `articulos` (
  `id_articulo` int(11) NOT NULL,
  `nombre_articulo` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `articulos`
--

INSERT INTO `articulos` (`id_articulo`, `nombre_articulo`, `descripcion`) VALUES
(1, 'Shampoo de bebé', 'Esta shido');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargos`
--

CREATE TABLE `cargos` (
  `id_cargo` int(11) NOT NULL,
  `nombre_cargo` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cargos`
--

INSERT INTO `cargos` (`id_cargo`, `nombre_cargo`, `descripcion`) VALUES
(1, 'Director de seguridad', 'El Director de Seguridad es la figura del máximo responsable de la seguridad de una Empresa u Organismo, tanto público como privado, en la que esté constituido un Departamento de Seguridad.'),
(2, 'Director de tecnología', 'Es una posición ejecutiva dentro de una organización en el que la persona que ostenta el título se concentra en asuntos tecnológicos y científicos.'),
(3, 'Supervisor', 'Supervisa el area de produccion.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id_empleado` int(11) NOT NULL,
  `nombres` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `apellidos` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `id_cargo` int(11) NOT NULL,
  `salario_basico` decimal(10,2) NOT NULL,
  `id_tipo_salario` int(11) NOT NULL,
  `fecha_inicio_laboral` date NOT NULL,
  `isss` int(11) NOT NULL,
  `nup` int(11) NOT NULL,
  `nit` int(11) NOT NULL,
  `dui` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id_empleado`, `nombres`, `apellidos`, `id_cargo`, `salario_basico`, `id_tipo_salario`, `fecha_inicio_laboral`, `isss`, `nup`, `nit`, `dui`) VALUES
(15, 'Daniel Alberto', 'Brito Delgado', 1, '1210.00', 1, '2017-05-30', 984984984, 21321321, 2147483647, 789798798),
(16, 'Juan Fabricio', 'Perez Ramirez', 1, '800.00', 2, '2017-05-30', 741741741, 78788585, 2147483647, 963963963),
(19, 'Jose Gabriel', 'Bonilla Carmelo', 1, '400.00', 3, '2017-05-30', 452415241, 41245212, 2147483647, 414524124),
(20, 'Carlos Alberto', 'Bonilla', 3, '700.00', 1, '2016-01-01', 111919189, 65164164, 2147483647, 974951981);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventarios`
--

CREATE TABLE `inventarios` (
  `id_inventario` int(11) NOT NULL,
  `metodo` set('peps','ueps','cp') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `doc` set('ii','c','v','r') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `id_articulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unidad` decimal(10,2) NOT NULL,
  `costo_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `inventarios`
--

INSERT INTO `inventarios` (`id_inventario`, `metodo`, `fecha`, `doc`, `estado`, `id_articulo`, `cantidad`, `precio_unidad`, `costo_total`) VALUES
(148, 'peps', '2017-08-01', 'ii', 0, 1, 12, '6.00', '72.00'),
(149, 'peps', '2017-08-04', 'c', 1, 1, 20, '7.00', '140.00'),
(150, 'peps', '2017-08-09', 'v', 1, 1, 10, '6.00', '60.00'),
(151, 'peps', '2017-08-01', 'r', 1, 1, 2, '6.00', '12.00'),
(152, 'peps', '2017-08-15', 'v', 1, 1, 12, '6.00', '72.00'),
(153, 'peps', '2017-08-15', 'v', 1, 1, 3, '6.00', '18.00'),
(154, 'peps', '2017-08-01', 'r', 1, 1, 9, '6.00', '54.00'),
(155, 'peps', '2017-08-19', 'c', 1, 1, 20, '8.00', '160.00'),
(156, 'peps', '2017-08-24', 'v', 1, 1, 5, '6.00', '30.00'),
(157, 'peps', '2017-08-01', 'r', 1, 1, 7, '6.00', '42.00'),
(158, 'peps', '2017-08-31', 'v', 1, 1, 12, '6.00', '72.00'),
(159, 'peps', '2017-08-31', 'v', 1, 1, 4, '6.00', '24.00'),
(160, 'peps', '2017-08-01', 'r', 1, 1, 8, '6.00', '48.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planillas`
--

CREATE TABLE `planillas` (
  `id_planilla` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `fecha_planilla` date NOT NULL,
  `dias_ausentes` int(11) NOT NULL,
  `comision` decimal(10,2) DEFAULT NULL,
  `hora_extra_diurna` int(11) NOT NULL,
  `hora_extra_nocturna` int(11) NOT NULL,
  `horas_extras` decimal(10,2) DEFAULT NULL,
  `vacaciones` decimal(10,2) DEFAULT NULL,
  `aguinaldo` decimal(10,2) DEFAULT NULL,
  `indemnizacion` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `isss` decimal(10,2) NOT NULL,
  `afp` decimal(10,2) NOT NULL,
  `renta` decimal(10,2) DEFAULT NULL,
  `total_retenciones` decimal(10,2) NOT NULL,
  `salario_liquido` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `planillas`
--

INSERT INTO `planillas` (`id_planilla`, `id_empleado`, `fecha_planilla`, `dias_ausentes`, `comision`, `hora_extra_diurna`, `hora_extra_nocturna`, `horas_extras`, `vacaciones`, `aguinaldo`, `indemnizacion`, `subtotal`, `isss`, `afp`, `renta`, `total_retenciones`, `salario_liquido`) VALUES
(8, 15, '2017-05-31', 0, '125.00', 2, 3, '57.98', '0.00', '0.00', '0.00', '1392.98', '30.00', '87.06', '136.14', '253.20', '1139.78'),
(14, 20, '2017-05-31', 0, '0.00', 0, 0, '0.00', '105.00', '0.00', '757.53', '805.00', '24.15', '50.31', '43.52', '117.99', '687.01'),
(15, 20, '2017-05-31', 0, '0.00', 0, 0, '0.00', '105.00', '0.00', '0.00', '805.00', '24.15', '50.31', '43.52', '117.99', '687.01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_salarios`
--

CREATE TABLE `tipos_salarios` (
  `id_tipo_salario` int(11) NOT NULL,
  `nombre_tipo_salario` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipos_salarios`
--

INSERT INTO `tipos_salarios` (`id_tipo_salario`, `nombre_tipo_salario`) VALUES
(1, 'Mensual'),
(2, 'Quincenal'),
(3, 'Semanal');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD PRIMARY KEY (`id_articulo`),
  ADD UNIQUE KEY `nombre_articulo` (`nombre_articulo`);

--
-- Indices de la tabla `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id_cargo`),
  ADD UNIQUE KEY `nombre_cargo` (`nombre_cargo`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id_empleado`),
  ADD UNIQUE KEY `isss` (`isss`),
  ADD UNIQUE KEY `nup` (`nup`),
  ADD UNIQUE KEY `dui` (`dui`),
  ADD KEY `id_tipo_salario` (`id_tipo_salario`),
  ADD KEY `id_cargo` (`id_cargo`);

--
-- Indices de la tabla `inventarios`
--
ALTER TABLE `inventarios`
  ADD PRIMARY KEY (`id_inventario`),
  ADD KEY `id_articulo` (`id_articulo`);

--
-- Indices de la tabla `planillas`
--
ALTER TABLE `planillas`
  ADD PRIMARY KEY (`id_planilla`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `tipos_salarios`
--
ALTER TABLE `tipos_salarios`
  ADD PRIMARY KEY (`id_tipo_salario`),
  ADD UNIQUE KEY `nombre_tipo_salario` (`nombre_tipo_salario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articulos`
--
ALTER TABLE `articulos`
  MODIFY `id_articulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id_cargo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT de la tabla `inventarios`
--
ALTER TABLE `inventarios`
  MODIFY `id_inventario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;
--
-- AUTO_INCREMENT de la tabla `planillas`
--
ALTER TABLE `planillas`
  MODIFY `id_planilla` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT de la tabla `tipos_salarios`
--
ALTER TABLE `tipos_salarios`
  MODIFY `id_tipo_salario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD CONSTRAINT `empleados_ibfk_1` FOREIGN KEY (`id_tipo_salario`) REFERENCES `tipos_salarios` (`id_tipo_salario`),
  ADD CONSTRAINT `empleados_ibfk_2` FOREIGN KEY (`id_cargo`) REFERENCES `cargos` (`id_cargo`);

--
-- Filtros para la tabla `inventarios`
--
ALTER TABLE `inventarios`
  ADD CONSTRAINT `inventarios_ibfk_1` FOREIGN KEY (`id_articulo`) REFERENCES `articulos` (`id_articulo`);

--
-- Filtros para la tabla `planillas`
--
ALTER TABLE `planillas`
  ADD CONSTRAINT `planillas_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
