-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-12-2023 a las 22:35:36
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `lacomanda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuestas`
--

CREATE TABLE `encuestas` (
  `id` int(11) NOT NULL,
  `codigoPedido` varchar(20) DEFAULT NULL,
  `codigoMesa` varchar(20) DEFAULT NULL,
  `mesa` int(11) DEFAULT NULL,
  `restaurante` varchar(20) DEFAULT NULL,
  `mozo` varchar(20) DEFAULT NULL,
  `cocinero` varchar(20) DEFAULT NULL,
  `texto` varchar(66) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `encuestas`
--

INSERT INTO `encuestas` (`id`, `codigoPedido`, `codigoMesa`, `mesa`, `restaurante`, `mozo`, `cocinero`, `texto`) VALUES
(8, 'pp111', 'aa123', 9, '10', '10', '10', 'Excelente experiencia.'),
(9, 'pp111', 'aa123', 9, '10', '10', '10', 'Excelente experiencia.'),
(10, 'pp111', 'aa123', 10, '10', '10', '10', 'Excelente experiencia.'),
(11, 'pp111', 'aa123', 7, '10', '10', '10', 'Excelente experiencia.'),
(23, 'pp222', 'bb123', 4, '6', '4', '5', 'No tan bueno'),
(24, 'pp111', 'aa123', 7, '10', '10', '10', 'Excelente experiencia.'),
(25, 'pp111', 'aa123', 7, '10', '10', '10', 'Excelente experiencia.'),
(26, 'pp111', 'aa123', 7, '10', '10', '10', 'Excelente experiencia.'),
(27, 'pp111', 'aa123', 7, '10', '10', '10', 'Excelente experiencia.'),
(28, 'pp111', 'aa123', 7, '10', '10', '10', 'Excelente experiencia.'),
(29, 'pp111', 'aa123', 7, '10', '10', '10', 'Excelente experiencia.'),
(30, 'pp111', 'aa123', 7, '10', '10', '10', 'Excelente experiencia.'),
(31, 'pp222', 'bb123', 4, '6', '4', '5', 'No tan bueno'),
(32, 'pp111', 'aa123', 7, '10', '10', '10', 'Excelente experiencia.'),
(33, 'pp111', 'aa123', 7, '10', '10', '10', 'Excelente experiencia.'),
(34, 'pp111', 'aa123', 7, '10', '10', '10', 'Excelente experiencia.'),
(35, 'pp222', 'bb123', 4, '6', '4', '5', 'No tan bueno'),
(36, 'pp222', 'bb123', 4, '3', '4', '5', 'No tan bueno');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jwtusuarios`
--

CREATE TABLE `jwtusuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `roll` varchar(50) NOT NULL,
  `token` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `jwtusuarios`
--

INSERT INTO `jwtusuarios` (`id`, `nombre`, `roll`, `token`) VALUES
(13, 'Socio1', 'Socio', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MDE3OTg2MDgsImV4cCI6MTcwOTU3NDYwOCwiYXVkIjoiY2FkYzdmZTJkZjcxOWY5NGJlMjBmOTRhNmNlMGQ0NDMyOGE4NDQ4MSIsImRhdGEiOnsiaWQiOjEzLCJub21icmUiOiJTb2NpbzEiLCJyb2xsIjoiU29jaW8ifSwiYXBwIjoiVGVzdCBKV1QifQ.h85R3V-OiK8zdfteqtaNQGckUu8wuY2f3TRxokxSD18'),
(14, 'Mozo1', 'Mozo', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MDE3OTg2NDcsImV4cCI6MTcwOTU3NDY0NywiYXVkIjoiY2FkYzdmZTJkZjcxOWY5NGJlMjBmOTRhNmNlMGQ0NDMyOGE4NDQ4MSIsImRhdGEiOnsiaWQiOjE0LCJub21icmUiOiJNb3pvMSIsInJvbGwiOiJNb3pvIn0sImFwcCI6IlRlc3QgSldUIn0.VoE_bGgfeV069nn_chjx_h60EEwzdzQFq-w7fk2yNk8'),
(15, 'Patelero1', 'Pastelero', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MDE3OTg2NTcsImV4cCI6MTcwOTU3NDY1NywiYXVkIjoiY2FkYzdmZTJkZjcxOWY5NGJlMjBmOTRhNmNlMGQ0NDMyOGE4NDQ4MSIsImRhdGEiOnsiaWQiOjE1LCJub21icmUiOiJQYXRlbGVybzEiLCJyb2xsIjoiUGFzdGVsZXJvIn0sImFwcCI6IlRlc3QgSldUIn0.n40rav4RyNmZpfMzM3SkD1MheaC0tNNBH_qLx2LmRgQ'),
(16, 'Cervecero1', 'Cervecero', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MDE3OTg2NjQsImV4cCI6MTcwOTU3NDY2NCwiYXVkIjoiY2FkYzdmZTJkZjcxOWY5NGJlMjBmOTRhNmNlMGQ0NDMyOGE4NDQ4MSIsImRhdGEiOnsiaWQiOjE2LCJub21icmUiOiJDZXJ2ZWNlcm8xIiwicm9sbCI6IkNlcnZlY2VybyJ9LCJhcHAiOiJUZXN0IEpXVCJ9.bL8u07Ew1PRVrG96yM8L9ZxpNsHB9VhT27VfQog_Nps'),
(17, 'Cocinero1', 'Cocinero', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MDE3OTg2NzAsImV4cCI6MTcwOTU3NDY3MCwiYXVkIjoiY2FkYzdmZTJkZjcxOWY5NGJlMjBmOTRhNmNlMGQ0NDMyOGE4NDQ4MSIsImRhdGEiOnsiaWQiOjE3LCJub21icmUiOiJDb2NpbmVybzEiLCJyb2xsIjoiQ29jaW5lcm8ifSwiYXBwIjoiVGVzdCBKV1QifQ.BaRCgupgFIlLyKMbsChEJgAABmnSM4C2WtoAAx-oRec'),
(18, 'Cliente1', 'Cliente', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MDE3OTg2NzksImV4cCI6MTcwOTU3NDY3OSwiYXVkIjoiY2FkYzdmZTJkZjcxOWY5NGJlMjBmOTRhNmNlMGQ0NDMyOGE4NDQ4MSIsImRhdGEiOnsiaWQiOjE4LCJub21icmUiOiJDbGllbnRlMSIsInJvbGwiOiJDbGllbnRlIn0sImFwcCI6IlRlc3QgSldUIn0.ce1M1urkH9JwwrOSPRcZ3IDGl6n1vvS17Hj-zsTwqi8'),
(19, 'bartender1', 'Bartender', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MDE3OTg2OTMsImV4cCI6MTcwOTU3NDY5MywiYXVkIjoiY2FkYzdmZTJkZjcxOWY5NGJlMjBmOTRhNmNlMGQ0NDMyOGE4NDQ4MSIsImRhdGEiOnsiaWQiOjE5LCJub21icmUiOiJiYXJ0ZW5kZXIxIiwicm9sbCI6IkJhcnRlbmRlciJ9LCJhcHAiOiJUZXN0IEpXVCJ9.GJaYwIs7QoHBFdodsYwvW18ne8vgos3mY9l2-a52Flg'),
(20, 'Cocinero2', 'Cocinero', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MDIyNDg0NjYsImV4cCI6MTcxMDAyNDQ2NiwiYXVkIjoiY2FkYzdmZTJkZjcxOWY5NGJlMjBmOTRhNmNlMGQ0NDMyOGE4NDQ4MSIsImRhdGEiOnsiaWQiOjIwLCJub21icmUiOiJDb2NpbmVybzIiLCJyb2xsIjoiQ29jaW5lcm8ifSwiYXBwIjoiVGVzdCBKV1QifQ.VKWN2F-llRjxvn1cGXFO15TNRpfPeW1fuICCU3BOb98'),
(21, 'Mozo3', 'Mozo', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MDIyNDk0NTQsImV4cCI6MTcxMDAyNTQ1NCwiYXVkIjoiY2FkYzdmZTJkZjcxOWY5NGJlMjBmOTRhNmNlMGQ0NDMyOGE4NDQ4MSIsImRhdGEiOnsiaWQiOjIxLCJub21icmUiOiJNb3pvMyIsInJvbGwiOiJNb3pvIn0sImFwcCI6IlRlc3QgSldUIn0.3jCQiptDuLcJ1hobUsetKq_wL1q8X3UJrzXBvyedM9g'),
(22, 'Patelero2', 'Pastelero', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MDIzMjc4MDYsImV4cCI6MTcxMDEwMzgwNiwiYXVkIjoiY2FkYzdmZTJkZjcxOWY5NGJlMjBmOTRhNmNlMGQ0NDMyOGE4NDQ4MSIsImRhdGEiOnsiaWQiOjIyLCJub21icmUiOiJQYXRlbGVybzIiLCJyb2xsIjoiUGFzdGVsZXJvIn0sImFwcCI6IlRlc3QgSldUIn0.8hECc0sPzulM22r8nQx87EPW6X1MUbBgpERkxuklJO0'),
(23, 'Pastelero2', 'Pastelero', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MDIzMjc4MjYsImV4cCI6MTcxMDEwMzgyNiwiYXVkIjoiY2FkYzdmZTJkZjcxOWY5NGJlMjBmOTRhNmNlMGQ0NDMyOGE4NDQ4MSIsImRhdGEiOnsiaWQiOjIzLCJub21icmUiOiJQYXN0ZWxlcm8yIiwicm9sbCI6IlBhc3RlbGVybyJ9LCJhcHAiOiJUZXN0IEpXVCJ9.1vt-R2TcrD0elQpT6evYu1XkUU5kqY38Y7rMOpRozgo'),
(24, 'admin', 'admin', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MDIzMjc5MzQsImV4cCI6MTcxMDEwMzkzNCwiYXVkIjoiY2FkYzdmZTJkZjcxOWY5NGJlMjBmOTRhNmNlMGQ0NDMyOGE4NDQ4MSIsImRhdGEiOnsiaWQiOjI0LCJub21icmUiOiJhZG1pbiIsInJvbGwiOiJhZG1pbiJ9LCJhcHAiOiJUZXN0IEpXVCJ9.pgdP-mNacYrr3b12w5SNCncl_S5zH4H4E2UkmlZHSIo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `listaproductosporpedido`
--

CREATE TABLE `listaproductosporpedido` (
  `id` int(50) NOT NULL,
  `idPedido` int(11) DEFAULT NULL,
  `idProducto` int(11) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `precio` int(11) DEFAULT NULL,
  `tiempoEstimado` time NOT NULL DEFAULT '00:00:00',
  `sector` varchar(250) NOT NULL,
  `empleadoACargo` varchar(255) DEFAULT NULL,
  `estado` varchar(255) DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `listaproductosporpedido`
--

INSERT INTO `listaproductosporpedido` (`id`, `idPedido`, `idProducto`, `nombre`, `precio`, `tiempoEstimado`, `sector`, `empleadoACargo`, `estado`) VALUES
(11, 43, 1, 'milanesa a caballo', 2500, '00:02:00', 'cocina', 'cocinero1', 'listo para servir'),
(12, 43, 2, 'hamburguesa garbanzo', 1800, '00:04:00', 'cocina', 'cocinero1', 'listo para servir'),
(13, 43, 2, 'hamburguesa garbanzo', 1800, '00:04:00', 'cocina', 'cocinero1', 'listo para servir'),
(14, 43, 3, 'corona', 1500, '00:02:00', 'cervezas', 'cervecero1', 'listo para servir'),
(15, 43, 4, 'daikiri', 1200, '00:01:00', 'tragos', 'Bartender1', 'listo para servir'),
(16, 44, 1, 'milanesa a caballo', 2500, '00:00:00', 'cocina', NULL, 'pendiente'),
(17, 44, 3, 'corona', 1500, '00:00:00', 'cervezas', NULL, 'pendiente'),
(18, 44, 5, 'pizza', 2200, '00:00:00', 'cocina', NULL, 'pendiente'),
(45, 54, 2, 'hamburguesa garbanzo', 1800, '00:02:00', 'cocina', 'cociner1', 'en preparacion'),
(46, 54, 4, 'daikiri', 1200, '00:01:00', 'tragos', 'bartender1', 'listo para servir'),
(206, 96, 1, 'milanesa a caballo', 2500, '00:02:00', 'cocina', 'cocinero1', 'listo para servir'),
(207, 96, 2, 'hamburguesa garbanzo', 2800, '00:00:00', 'cocina', NULL, 'en preparacion'),
(208, 96, 2, 'hamburguesa garbanzo', 2800, '00:00:00', 'cocina', NULL, 'en preparacion'),
(209, 96, 3, 'corona', 1500, '00:00:00', 'cervezas', NULL, 'en preparacion'),
(210, 96, 4, 'daikiri', 1200, '00:00:00', 'tragos', NULL, 'en preparacion');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `codigo` varchar(250) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `puntaje` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `codigo`, `estado`, `puntaje`) VALUES
(1, 'aa123', 'cerrada', 8),
(5, 'cc123', 'cerrada', 10),
(8, 'dd123', 'cerrada', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `idMesa` int(11) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `codigoPedido` varchar(255) DEFAULT NULL,
  `listaProductos` varchar(250) NOT NULL,
  `fotoMesa` varchar(255) DEFAULT NULL,
  `tiempoEstimado` time DEFAULT '00:00:00',
  `horaCreacion` datetime NOT NULL DEFAULT current_timestamp(),
  `horaFinalizacion` time DEFAULT NULL,
  `precioTotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `idMesa`, `estado`, `codigoPedido`, `listaProductos`, `fotoMesa`, `tiempoEstimado`, `horaCreacion`, `horaFinalizacion`, `precioTotal`) VALUES
(43, 1, 'servido', 'pp111', '1,2,2,3,4', './imagenes/mesa1.jpg', '00:04:00', '2023-12-12 10:45:27', '22:22:43', '8800.00'),
(44, 4, 'servido', 'pp222', '1,3,5', './imagenes/mesa1.jpg', '00:12:00', '2023-12-12 13:25:21', '13:38:25', '6200.00'),
(54, 5, 'servido', 'pp333', '2,4', './imagenes/mesa1.jpg', '00:02:00', '2023-12-12 10:49:23', '10:50:34', '3000.00'),
(96, 1, 'pendiente', 'pp444', '1,2,2,3,4', NULL, '00:02:00', '2023-12-12 18:17:09', '00:00:00', '10800.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  `sectorAsignado` varchar(250) NOT NULL,
  `precio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `sectorAsignado`, `precio`) VALUES
(1, 'milanesa a caballo', 'cocina', 2500),
(2, 'hamburguesa garbanzo', 'cocina', 2800),
(3, 'corona', 'cervezas', 1500),
(4, 'daikiri', 'tragos', 1200),
(5, 'pizza', 'cocina', 2200),
(6, 'matambre', 'cocinero', 2200),
(8, 'pizza jamón', 'cocina', 3000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registroacciones`
--

CREATE TABLE `registroacciones` (
  `id` int(11) NOT NULL,
  `usuarioId` int(11) DEFAULT NULL,
  `sector` varchar(50) DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `metodo` varchar(10) DEFAULT NULL,
  `ruta` varchar(255) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registroacciones`
--

INSERT INTO `registroacciones` (`id`, `usuarioId`, `sector`, `nombre`, `metodo`, `ruta`, `fecha`) VALUES
(119, 6, 'Cliente', 'Cliente1', 'POST', 'encuesta/cargarEncuesta', '2023-12-12 17:41:28'),
(120, 6, 'Cliente', 'Cliente2', 'POST', 'encuesta/cargarEncuesta', '2023-12-12 17:42:08'),
(121, 1, 'Mozo', 'Mozo1', 'POST', 'pedidos/relacionarFotoAPedido', '2023-12-12 17:46:14'),
(122, 6, 'Cliente', 'Cliente1', 'GET', 'consultarDemora', '2023-12-12 18:03:04'),
(123, 2, 'Socio', 'Socio1', 'GET', 'operacionesPorSector', '2023-12-12 18:16:00'),
(124, 2, 'Socio', 'Socio1', 'GET', 'cantidadOperacionesPorSector', '2023-12-12 18:19:02'),
(125, 6, 'Cliente', 'Cliente1', 'GET', 'consultarDemora', '2023-12-12 18:31:56'),
(126, 2, 'Socio', 'Socio1', 'GET', 'masVendidoAMenosVendido', '2023-12-12 18:46:52'),
(127, 1, 'Mozo', 'Mozo1', 'POST', 'pedidos/cargarPedido', '2023-12-12 18:47:20'),
(128, 2, 'Socio', 'Socio1', 'GET', 'masVendidoAMenosVendido', '2023-12-12 18:47:28'),
(129, 2, 'Socio', 'Socio1', 'GET', 'masVendidoAMenosVendido', '2023-12-12 18:53:11'),
(130, 2, 'Socio', 'Socio1', 'GET', 'cobroMasCaroMasBarato', '2023-12-12 18:53:35'),
(131, 2, 'Socio', 'Socio1', 'GET', 'cobroMasCaroMasBarato', '2023-12-12 18:54:02'),
(132, 2, 'Socio', 'Socio1', 'GET', 'cobroMasCaroMasBarato', '2023-12-12 18:54:11'),
(133, 2, 'Socio', 'Socio1', 'GET', 'cobroMasCaroMasBarato', '2023-12-12 18:54:44'),
(134, 1, 'Mozo', 'Mozo1', 'POST', 'pedidos/cargarPedido', '2023-12-12 18:55:55'),
(135, 2, 'Socio', 'Socio1', 'GET', 'cobroMasCaroMasBarato', '2023-12-12 18:56:09'),
(136, 2, 'Socio', 'Socio1', 'GET', 'cobroMasCaroMasBarato', '2023-12-12 18:56:50'),
(137, 2, 'Socio', 'Socio1', 'GET', 'cobroMasCaroMasBarato', '2023-12-12 18:56:57'),
(138, 2, 'Socio', 'Socio1', 'GET', 'cobroMasCaroMasBarato', '2023-12-12 18:57:18'),
(139, 2, 'Socio', 'Socio1', 'GET', 'cobroMasCaroMasBarato', '2023-12-12 18:57:51'),
(140, 1, 'Mozo', 'Mozo1', 'POST', 'pedidos/cargarPedido', '2023-12-12 19:03:02'),
(141, 1, 'Mozo', 'Mozo1', 'POST', 'pedidos/cargarPedido', '2023-12-12 19:06:21'),
(142, 1, 'Mozo', 'Mozo1', 'POST', 'pedidos/cargarPedido', '2023-12-12 19:07:52'),
(143, 1, 'Mozo', 'Mozo1', 'POST', 'pedidos/cargarPedido', '2023-12-12 19:09:05'),
(144, 1, 'Mozo', 'Mozo1', 'POST', 'pedidos/cargarPedido', '2023-12-12 19:10:45'),
(145, 2, 'Socio', 'Socio1', 'POST', 'facturoEntreDosFechas', '2023-12-12 19:22:45'),
(146, 2, 'Socio', 'Socio1', 'POST', 'facturoEntreDosFechas', '2023-12-12 19:23:16'),
(147, 2, 'Socio', 'Socio1', 'POST', 'facturoEntreDosFechas', '2023-12-12 19:23:42'),
(148, 2, 'Socio', 'Socio1', 'POST', 'facturoEntreDosFechas', '2023-12-12 19:24:05'),
(149, 2, 'Socio', 'Socio1', 'POST', 'facturoEntreDosFechas', '2023-12-12 19:24:31'),
(150, 2, 'Socio', 'Socio1', 'POST', 'facturoEntreDosFechas', '2023-12-12 19:26:09'),
(151, 2, 'Socio', 'Socio1', 'POST', 'facturoEntreDosFechas', '2023-12-12 19:27:15'),
(152, 2, 'Socio', 'Socio1', 'POST', 'facturoEntreDosFechas', '2023-12-12 19:28:00'),
(153, 2, 'Socio', 'Socio1', 'POST', 'facturoEntreDosFechas', '2023-12-12 19:28:08'),
(154, 2, 'Socio', 'Socio1', 'POST', 'facturoEntreDosFechas', '2023-12-12 19:29:31'),
(155, 2, 'Socio', 'Socio1', 'POST', 'facturoEntreDosFechas', '2023-12-12 19:30:41'),
(156, 2, 'Socio', 'Socio1', 'POST', 'facturoEntreDosFechas', '2023-12-12 19:33:45'),
(157, 2, 'Socio', 'Socio1', 'POST', 'facturoEntreDosFechas', '2023-12-12 19:34:07'),
(158, 2, 'Socio', 'Socio1', 'POST', 'facturoEntreDosFechas', '2023-12-12 19:34:24'),
(159, 2, 'Socio', 'Socio1', 'POST', 'facturoEntreDosFechas', '2023-12-12 19:35:01'),
(160, 2, 'Socio', 'Socio1', 'POST', 'facturoEntreDosFechas', '2023-12-12 19:36:10'),
(161, 2, 'Socio', 'Socio1', 'POST', 'facturoEntreDosFechas', '2023-12-12 19:36:30'),
(162, 2, 'Socio', 'Socio1', 'POST', 'facturoEntreDosFechas', '2023-12-12 19:37:27'),
(163, 2, 'Socio', 'Socio1', 'POST', 'facturoEntreDosFechas', '2023-12-12 19:37:48'),
(164, 2, 'Socio', 'Socio1', 'POST', 'facturoEntreDosFechas', '2023-12-12 19:39:28'),
(165, 2, 'Socio', 'Socio1', 'POST', 'facturoEntreDosFechas', '2023-12-12 19:40:15'),
(166, 2, 'Socio', 'Socio1', 'GET', 'logIngresosSistema', '2023-12-12 20:20:56'),
(167, 2, 'Socio', 'Socio1', 'GET', 'logIngresosSistema', '2023-12-12 20:21:39'),
(168, 2, 'Socio', 'Socio1', 'GET', 'logIngresosSistema', '2023-12-12 20:25:54'),
(169, 2, 'Socio', 'Socio1', 'GET', 'masVendidoAMenosVendido', '2023-12-12 20:26:01'),
(170, 2, 'Socio', 'Socio1', 'GET', 'cantidadOperacionesPorSector', '2023-12-12 20:26:12'),
(171, 2, 'Socio', 'Socio1', 'GET', 'operacionesPorSector', '2023-12-12 20:26:25'),
(172, 2, 'Socio', 'Socio1', 'DELETE', 'usuarios/bajaUsuario', '2023-12-12 20:29:23'),
(173, 2, 'Socio', 'Socio1', 'DELETE', 'usuarios/bajaUsuario', '2023-12-12 20:30:33'),
(174, 2, 'Socio', 'Socio1', 'PUT', 'usuarios/modificarUsuario', '2023-12-12 20:32:09'),
(175, 2, 'Socio', 'Socio1', 'POST', 'productos/cargarProducto', '2023-12-12 20:33:09'),
(176, 1, 'Mozo', 'Mozo1', 'POST', 'pedidos/cargarPedido', '2023-12-12 20:33:53'),
(177, 1, 'Mozo', 'Mozo1', 'POST', 'pedidos/relacionarFotoAPedido', '2023-12-12 20:34:22'),
(178, 7, 'Bartender', 'bartender1', 'GET', 'pedidos/pendientes/tragos', '2023-12-12 20:34:42'),
(179, 7, 'Bartender', 'bartender1', 'GET', 'pedidos/pendientes/tragos', '2023-12-12 20:35:48'),
(180, 7, 'Bartender', 'bartender1', 'GET', 'pedidos/pendientes/cervezas', '2023-12-12 20:36:04'),
(181, 4, 'Cervecero', 'Cervecero1', 'GET', 'pedidos/pendientes/cervezas', '2023-12-12 20:36:15'),
(182, 5, 'Cocinero', 'Cocinero1', 'PUT', 'pedidos/modificar', '2023-12-12 20:39:29'),
(183, 5, 'Cocinero', 'Cocinero1', 'PUT', 'pedidos/modificar', '2023-12-12 20:39:54'),
(184, 5, 'Cocinero', 'Cocinero1', 'PUT', 'pedidos/modificar', '2023-12-12 20:40:30'),
(185, 4, 'Cervecero', 'Cervecero1', 'PUT', 'pedidos/modificar', '2023-12-12 20:40:51'),
(186, 7, 'Bartender', 'bartender1', 'PUT', 'pedidos/modificar', '2023-12-12 20:41:08'),
(187, 6, 'Cliente', 'Cliente1', 'GET', 'consultarDemora', '2023-12-12 20:43:15'),
(188, 1, 'Mozo', 'Mozo1', 'POST', 'pedidos/cargarPedido', '2023-12-12 20:46:39'),
(189, 4, 'Cervecero', 'Cervecero1', 'GET', 'pedidos/pendientes/cervezas', '2023-12-12 20:47:54'),
(190, 1, 'Mozo', 'Mozo1', 'POST', 'pedidos/cargarPedido', '2023-12-12 20:47:59'),
(191, 1, 'Mozo', 'Mozo1', 'POST', 'pedidos/cargarPedido', '2023-12-12 20:56:28'),
(192, 1, 'Mozo', 'Mozo1', 'POST', 'pedidos/cargarPedido', '2023-12-12 20:58:12'),
(193, 1, 'Mozo', 'Mozo1', 'POST', 'pedidos/cargarPedido', '2023-12-12 21:00:06'),
(194, 1, 'Mozo', 'Mozo1', 'POST', 'pedidos/cargarPedido', '2023-12-12 21:04:07'),
(195, 1, 'Mozo', 'Mozo1', 'POST', 'pedidos/cargarPedido', '2023-12-12 21:05:24'),
(196, 1, 'Mozo', 'Mozo1', 'POST', 'pedidos/cargarPedido', '2023-12-12 21:07:21'),
(197, 1, 'Mozo', 'Mozo1', 'POST', 'pedidos/cargarPedido', '2023-12-12 21:10:57'),
(198, 1, 'Mozo', 'Mozo1', 'POST', 'pedidos/cargarPedido', '2023-12-12 21:12:18'),
(199, 1, 'Mozo', 'Mozo1', 'POST', 'pedidos/cargarPedido', '2023-12-12 21:16:08'),
(200, 1, 'Mozo', 'Mozo1', 'POST', 'pedidos/cargarPedido', '2023-12-12 21:17:08'),
(201, 5, 'Cocinero', 'Cocinero1', 'PUT', 'pedidos/modificar', '2023-12-12 21:18:26'),
(202, 6, 'Cliente', 'Cliente1', 'GET', 'consultarDemora', '2023-12-12 21:19:39'),
(203, 2, 'Socio', 'Socio1', 'GET', 'pedidos/listarPedidos', '2023-12-12 21:20:01'),
(204, 5, 'Cocinero', 'Cocinero1', 'PUT', 'pedidos/modificar', '2023-12-12 21:20:59'),
(205, 2, 'Socio', 'Socio1', 'GET', 'pedidos/listarProductosEnPedidos', '2023-12-12 21:21:57'),
(206, 1, 'Mozo', 'Mozo1', 'GET', 'servirpedidos', '2023-12-12 21:22:43'),
(207, 2, 'Socio', 'Socio1', 'GET', 'mesas/listarMesas', '2023-12-12 21:23:50'),
(208, 2, 'Socio', 'Socio1', 'GET', 'mesas/listarMesas', '2023-12-12 21:26:57'),
(209, 1, 'Mozo', 'Mozo1', 'GET', 'cobrar/1', '2023-12-12 21:27:19'),
(210, 2, 'Socio', 'Socio1', 'GET', 'cerrarMesa/1', '2023-12-12 21:27:31'),
(211, 6, 'Cliente', 'Cliente1', 'POST', 'encuesta/cargarEncuesta', '2023-12-12 21:27:53'),
(212, 6, 'Cliente', 'Cliente1', 'POST', 'encuesta/cargarEncuesta', '2023-12-12 21:28:03'),
(213, 2, 'Socio', 'Socio1', 'GET', 'mejoresEncuestas', '2023-12-12 21:28:09'),
(214, 6, 'Cliente', 'Cliente1', 'POST', 'encuesta/cargarEncuesta', '2023-12-12 21:28:31'),
(215, 2, 'Socio', 'Socio1', 'GET', 'mejoresEncuestas', '2023-12-12 21:28:40'),
(216, 2, 'Socio', 'Socio1', 'GET', 'mesas/masusada', '2023-12-12 21:28:52'),
(217, 2, 'Socio', 'Socio1', 'GET', 'pedidosConDemora', '2023-12-12 21:29:07'),
(218, 2, 'Socio', 'Socio1', 'GET', 'pedidosSinDemora', '2023-12-12 21:29:34'),
(219, 2, 'Socio', 'Socio1', 'GET', 'descargarPDFLogo', '2023-12-12 21:29:54'),
(220, 2, 'Socio', 'Socio1', 'GET', 'operacionesPorSector', '2023-12-12 21:30:56'),
(221, 2, 'Socio', 'Socio1', 'GET', 'cantidadOperacionesPorSector', '2023-12-12 21:31:10'),
(222, 2, 'Socio', 'Socio1', 'GET', 'operacionesPorEmpleadoPorSector', '2023-12-12 21:31:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  `clave` varchar(250) NOT NULL,
  `roll` varchar(250) NOT NULL,
  `fechaBaja` date DEFAULT NULL,
  `fechaAlta` date DEFAULT NULL,
  `estado` varchar(50) NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `clave`, `roll`, `fechaBaja`, `fechaAlta`, `estado`) VALUES
(13, 'Socio1', '$2y$10$KKr4W5qUftfYZ8vn0906h.FRlfrfJmn0Le00dMq.OnFG4AVZKE0/u', 'Socio', NULL, '2023-12-05', 'activo'),
(14, 'Mozo1', '$2y$10$17Pf1Pc/GPJOEAE0kOGVEee7NZL8hB7CYf68ZLwTSVzKwBK.kJerO', 'Mozo', NULL, '2023-12-05', 'activo'),
(15, 'Patelero1', '$2y$10$dYkJXJ4iKU8Zsi3gsQnVE.EmjFp8QE7F5tfUPAUMjzeOVWHeVSjuq', 'Pastelero', NULL, '2023-12-05', 'activo'),
(16, 'Cervecero1', '$2y$10$74VLUiI4ycMeT3j79MPjDeFwPb9AORwP7L3zpWNlL2L.3z8QMu4na', 'Cervecero', NULL, '2023-12-05', 'activo'),
(17, 'Cocinero1', '$2y$10$GFY/iiFzPxpaqxDsm2mpieCYUqghpgO0QTwyAnWVaHMWhnv.ZQLCi', 'Cocinero', NULL, '2023-12-05', 'activo'),
(18, 'Cliente1', '$2y$10$D1S7JvlIVhacrgNr1pLNE.dW5Iuf8X/xzDW9lOUomjHNBtUsw3Jrm', 'Cliente', NULL, '2023-12-05', 'activo'),
(19, 'Bartender1', '$2y$10$AmFgKy4o1iWxhRZ0pjlP8.v9FWM8rDbjGzut3ANGxu/F9qXc3PYu6', 'Bartender', NULL, '2020-12-30', 'activo'),
(23, 'Pastelero2', '$2y$10$KnKE5fBXiReYJmCiOwoMXu75nLcE4NHlcbq0a4y64Wj1lA.YSMHFy', 'Pastelero', NULL, '2023-12-11', 'activo'),
(24, 'admin', '$2y$10$QN9fy/Yx0gZ/j622x9jcxec95D4QOCMvLJ.0WkYS63iBe0tjCGieC', 'admin', NULL, '2023-12-11', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarioslogueo`
--

CREATE TABLE `usuarioslogueo` (
  `id` int(11) NOT NULL,
  `idUsuario` int(100) DEFAULT NULL,
  `nombre` varchar(255) NOT NULL,
  `sector` varchar(255) NOT NULL,
  `fechaHoraLogueo` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarioslogueo`
--

INSERT INTO `usuarioslogueo` (`id`, `idUsuario`, `nombre`, `sector`, `fechaHoraLogueo`) VALUES
(1, 18, 'Cliente1', 'Cliente', '2023-12-12 20:11:03'),
(2, 18, 'Cliente1', 'Cliente', '2023-12-12 20:12:51');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `listaproductosporpedido`
--
ALTER TABLE `listaproductosporpedido`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `registroacciones`
--
ALTER TABLE `registroacciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarioslogueo`
--
ALTER TABLE `usuarioslogueo`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `listaproductosporpedido`
--
ALTER TABLE `listaproductosporpedido`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `registroacciones`
--
ALTER TABLE `registroacciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `usuarioslogueo`
--
ALTER TABLE `usuarioslogueo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
