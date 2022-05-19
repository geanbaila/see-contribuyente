-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 02, 2022 at 12:45 AM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `enlaces`
--

-- --------------------------------------------------------

--
-- Table structure for table `adquiriente`
--

CREATE TABLE `adquiriente` (
  `id` int(11) NOT NULL,
  `tipo_documento` varchar(3) DEFAULT NULL,
  `documento` varchar(13) NOT NULL,
  `razon_social` varchar(60) NOT NULL,
  `nombre_comercial` varchar(60) DEFAULT NULL,
  `direccion` varchar(60) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `adquiriente`
--

INSERT INTO `adquiriente` (`id`, `tipo_documento`, `documento`, `razon_social`, `nombre_comercial`, `direccion`, `updated_at`, `created_at`) VALUES
(1, 'DNI', '22222222', 'GEAN CARLOS BAILA LAURENTE', 'GEAN CARLOS BAILA LAURENTE', 'DIRECCIÓN HIPOTÉTICA 1', '2022-01-16 20:12:50', '2022-01-16 20:12:50'),
(2, 'DNI', '44444444', 'GEAN CARLOS BAILA LAURENTE', 'GEAN CARLOS BAILA LAURENTE', 'DIRECCIÓN HIPOTÉTICA 1', '2022-01-16 20:14:02', '2022-01-16 20:14:02'),
(3, 'DNI', '10471343736', 'BITERA EIRL', 'BITERA EIRL', 'LAURA CALLER', '2022-01-16 20:14:27', '2022-01-16 20:14:27'),
(4, 'DNI', '22222222', 'GEAN CARLOS BAILA LAURENTE', 'GEAN CARLOS BAILA LAURENTE', 'DIRECCIÓN HIPOTÉTICA 1', '2022-01-16 20:15:58', '2022-01-16 20:15:58'),
(5, 'DNI', '22222222', 'GEAN CARLOS BAILA LAURENTE', 'GEAN CARLOS BAILA LAURENTE', '2', '2022-01-16 20:25:20', '2022-01-16 20:25:20'),
(6, 'DNI', '44444444', 'GEAN CARLOS BAILA LAURENTE', 'GEAN CARLOS BAILA LAURENTE', 'DIRECCIÓN HIPOTÉTICA 1', '2022-01-16 23:45:57', '2022-01-16 23:45:57'),
(7, 'DNI', '10471343736', 'BITERA EIRL', 'BITERA EIRL', 'LAURA CALLER', '2022-01-16 23:48:11', '2022-01-16 23:48:11'),
(8, 'DNI', '22222222', 'GEAN CARLOS BAILA LAURENTE', 'GEAN CARLOS BAILA LAURENTE', 'DIRECCIÓN HIPOTÉTICA 1', '2022-01-16 23:51:37', '2022-01-16 23:51:37'),
(9, 'DNI', '10471343736', 'BITERA EIRL', 'BITERA EIRL', 'LAURA CALLER', '2022-01-16 23:52:38', '2022-01-16 23:52:38'),
(10, 'DNI', '22222222', 'GEAN CARLOS BAILA LAURENTE', 'GEAN CARLOS BAILA LAURENTE', 'DIRECCIÓN HIPOTÉTICA 1', '2022-01-16 23:55:19', '2022-01-16 23:55:19'),
(11, 'DNI', '10471343736', 'BITERA EIRL', 'BITERA EIRL', 'LAURA CALLER', '2022-01-16 23:58:27', '2022-01-16 23:58:27'),
(12, 'DNI', '47134373', 'GEAN CARLOS BAILA LAURENTE', 'GEAN CARLOS BAILA LAURENTE', 'DIRECCIÓN HIPOTÉTICA 1', '2022-03-29 01:21:36', '2022-03-29 01:21:36');

-- --------------------------------------------------------

--
-- Table structure for table `agencia`
--

CREATE TABLE `agencia` (
  `id` int(11) NOT NULL,
  `sede_id` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `departamento` varchar(20) DEFAULT NULL,
  `direccion` varchar(60) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `horario_predeterminado` varchar(5) NOT NULL,
  `horario_alternativo` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `agencia`
--

INSERT INTO `agencia` (`id`, `sede_id`, `nombre`, `departamento`, `direccion`, `telefono`, `horario_predeterminado`, `horario_alternativo`) VALUES
(1, 2, 'Terrapuerto de Arequipa', 'AREQUIPA', 'Arequipa - Terrapuerto de Arequipa', '954 776 888', '14:00', '15:00'),
(2, 2, 'Centenario', 'AREQUIPA', 'Arequipa - Av. Garci de Carbajal #710, IV Centenario', '970 451 833', '17:00', '18:00'),
(3, 2, 'Jacobo Hunter', 'AREQUIPA', 'Arequipa - Jacobo Hunter, Calle Costa Rica #615', '989 816 835', '18:30', '19:30'),
(4, 3, 'La Victoria', 'LIMA', 'Lima - Av. Nicolás Arriola #197, La Victoria', '989 816 893', '15:00', '16:00'),
(5, 3, 'Terminal Terrestre de Atocongo', 'LIMA', 'Lima - Terminal Terrestre de Atocongo', '989 816 865', '17:00', '18:00');

-- --------------------------------------------------------

--
-- Table structure for table `documento`
--

CREATE TABLE `documento` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `alias` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `documento`
--

INSERT INTO `documento` (`id`, `nombre`, `descripcion`, `alias`) VALUES
(1, 'BOLETA', 'BOLETA DE VENTA ELECTRÓNICA', 'B'),
(2, 'FACTURA', 'FACTURA DE VENTA ELECTRÓNICA', 'F'),
(3, 'G. TRANSPORTISTA', 'GUÍA DE REMISIÓN - TRANSPORTISTA', 'G');

-- --------------------------------------------------------

--
-- Table structure for table `encargo`
--

CREATE TABLE `encargo` (
  `id` int(11) NOT NULL,
  `doc_envia` varchar(20) NOT NULL,
  `nombre_envia` varchar(60) NOT NULL,
  `doc_recibe` varchar(20) NOT NULL,
  `nombre_recibe` varchar(120) NOT NULL,
  `doc_recibe_alternativo` varchar(20) DEFAULT NULL,
  `nombre_recibe_alternativo` varchar(120) DEFAULT NULL,
  `agencia_origen` int(11) NOT NULL,
  `agencia_destino` int(11) NOT NULL,
  `agencia_id` int(11) NOT NULL,
  `adquiriente_id` int(11) NOT NULL,
  `documento_id` int(11) NOT NULL,
  `documento_serie` varchar(4) NOT NULL,
  `documento_correlativo` varchar(8) DEFAULT NULL,
  `documento_fecha` date NOT NULL COMMENT 'fecha y hora de la operación para SUNAT',
  `documento_hora` time NOT NULL COMMENT 'fecha y hora de la operación para SUNAT',
  `fecha_hora_envia` datetime DEFAULT NULL COMMENT 'fecha y hora que el cliente trae su encargo y se lo deja a la empresa',
  `fecha_hora_recibe` datetime DEFAULT NULL COMMENT 'fecha y hora que el cliente recibe su encargo. la empresa se lo da',
  `fecha_recibe` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'X',
  `subtotal` decimal(12,2) NOT NULL,
  `oferta` decimal(12,2) NOT NULL,
  `estado` int(11) NOT NULL COMMENT '1:para trasladar. 2:no trasladar, 3:en manifiesto',
  `descuento` decimal(12,2) NOT NULL,
  `cantidad_item` int(11) NOT NULL DEFAULT 0,
  `detraccion_codigo` varchar(10) DEFAULT NULL,
  `detraccion_medio_pago` varchar(10) DEFAULT NULL,
  `detraccion_cta_banco` varchar(20) DEFAULT NULL,
  `detraccion_porcentaje` varchar(10) DEFAULT NULL,
  `detraccion_monto` decimal(12,2) DEFAULT NULL,
  `monto_gravado` decimal(12,2) NOT NULL DEFAULT 0.00,
  `monto_exonerado` decimal(12,2) NOT NULL DEFAULT 0.00,
  `monto_inafecto` decimal(12,2) NOT NULL DEFAULT 0.00,
  `importe_pagar_con_igv` decimal(12,2) NOT NULL DEFAULT 0.00,
  `importe_pagar_sin_igv` decimal(12,2) NOT NULL DEFAULT 0.00,
  `importe_pagar_igv` decimal(12,2) NOT NULL DEFAULT 0.00,
  `cdr_codigo` varchar(4) DEFAULT NULL,
  `cdr_descripcion` varchar(200) DEFAULT NULL,
  `cdr_id` varchar(20) DEFAULT NULL,
  `cdr_notas` varchar(2000) DEFAULT NULL,
  `nombre_archivo` varchar(200) DEFAULT NULL,
  `url_documento_cdr` varchar(200) DEFAULT NULL,
  `url_documento_pdf` varchar(200) DEFAULT NULL,
  `url_documento_xml` varchar(200) DEFAULT NULL,
  `url_documento_baja` varchar(200) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `pagado` decimal(12,2) DEFAULT 0.00,
  `por_pagar` decimal(12,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `encargo`
--

INSERT INTO `encargo` (`id`, `doc_envia`, `nombre_envia`, `doc_recibe`, `nombre_recibe`, `doc_recibe_alternativo`, `nombre_recibe_alternativo`, `agencia_origen`, `agencia_destino`, `agencia_id`, `adquiriente_id`, `documento_id`, `documento_serie`, `documento_correlativo`, `documento_fecha`, `documento_hora`, `fecha_hora_envia`, `fecha_hora_recibe`, `fecha_recibe`, `subtotal`, `oferta`, `estado`, `descuento`, `cantidad_item`, `detraccion_codigo`, `detraccion_medio_pago`, `detraccion_cta_banco`, `detraccion_porcentaje`, `detraccion_monto`, `monto_gravado`, `monto_exonerado`, `monto_inafecto`, `importe_pagar_con_igv`, `importe_pagar_sin_igv`, `importe_pagar_igv`, `cdr_codigo`, `cdr_descripcion`, `cdr_id`, `cdr_notas`, `nombre_archivo`, `url_documento_cdr`, `url_documento_pdf`, `url_documento_xml`, `url_documento_baja`, `updated_at`, `created_at`, `pagado`, `por_pagar`) VALUES
(1, '22222222', 'GEAN CARLOS BAILA LAURENTE', '44444444', 'GEAN CARLOS BAILA LAURENTE', NULL, NULL, 1, 4, 1, 1, 1, 'B011', '00000017', '2022-01-16', '15:12:50', '2022-01-16 15:12:50', NULL, '2022-01-16 05:00:00', '236.00', '236.00', 3, '0.00', 27, NULL, NULL, NULL, NULL, NULL, '200.00', '0.00', '0.00', '236.00', '200.00', '36.00', '0', 'La Boleta numero B011-00000017, ha sido aceptada', 'B011-00000017', '', '20293185060-03-B011-00000017', 'comprobantes/2022/01/1/R-20293185060-03-B011-00000017.zip', 'comprobantes/2022/01/1/20293185060-03-B011-00000017.pdf', 'comprobantes/2022/01/1/20293185060-03-B011-00000017.xml', NULL, '2022-01-16 20:21:17', '2022-01-16 20:12:50', '236.00', '0.00'),
(2, '22222222', 'GEAN CARLOS BAILA LAURENTE', '44444444', 'GEAN CARLOS BAILA LAURENTE', NULL, NULL, 2, 5, 2, 5, 1, 'B012', '00000042', '2022-01-16', '15:25:20', '2022-01-16 15:14:02', '2022-03-28 20:34:30', '2022-01-16 05:00:00', '82.60', '82.60', 3, '0.00', 14, NULL, NULL, NULL, NULL, NULL, '70.00', '0.00', '0.00', '82.60', '70.00', '12.60', '0', 'La Boleta numero B012-00000042, ha sido aceptada', 'B012-00000042', '', '20293185060-03-B012-00000042', 'comprobantes/2022/01/2/R-20293185060-03-B012-00000042.zip', 'comprobantes/2022/01/2/20293185060-03-B012-00000042.pdf', 'comprobantes/2022/01/2/20293185060-03-B012-00000042.xml', NULL, '2022-03-29 01:34:30', '2022-01-16 20:14:02', '82.60', '0.00'),
(3, '10471343736', 'BITERA EIRL', '44444444', 'GEAN CARLOS BAILA LAURENTE', NULL, NULL, 1, 4, 1, 3, 2, 'F011', '00000036', '2022-01-16', '15:14:27', '2022-01-16 15:14:27', NULL, '2022-01-16 05:00:00', '1180.00', '1180.00', 3, '0.00', 100, '021', '001', '0004-3342343243', '10', '118.00', '1000.00', '0.00', '0.00', '1180.00', '1000.00', '180.00', '0', 'La Factura numero F011-00000036, ha sido aceptada', 'F011-00000036', '', '20293185060-01-F011-00000036', 'comprobantes/2022/01/3/R-20293185060-01-F011-00000036.zip', 'comprobantes/2022/01/3/20293185060-01-F011-00000036.pdf', 'comprobantes/2022/01/3/20293185060-01-F011-00000036.xml', NULL, '2022-01-16 20:21:17', '2022-01-16 20:14:27', '1180.00', '0.00'),
(4, '22222222', 'GEAN CARLOS BAILA LAURENTE', '44444444', 'GEAN CARLOS BAILA LAURENTE', NULL, NULL, 2, 5, 2, 4, 1, 'B012', '00000041', '2022-01-16', '15:15:58', '2022-01-16 15:15:58', NULL, '2022-01-16 05:00:00', '188.80', '188.80', 3, '0.00', 16, NULL, NULL, NULL, NULL, NULL, '160.00', '0.00', '0.00', '188.80', '160.00', '28.80', '0', 'La Boleta numero B012-00000041, ha sido aceptada', 'B012-00000041', '', '20293185060-03-B012-00000041', 'comprobantes/2022/01/4/R-20293185060-03-B012-00000041.zip', 'comprobantes/2022/01/4/20293185060-03-B012-00000041.pdf', 'comprobantes/2022/01/4/20293185060-03-B012-00000041.xml', NULL, '2022-01-16 23:46:45', '2022-01-16 20:15:58', '188.80', '0.00'),
(5, '10471343736', 'BITERA EIRL', '44444444', 'GEAN CARLOS BAILA LAURENTE', NULL, NULL, 2, 5, 2, 7, 2, 'F012', '00000030', '2022-01-16', '18:48:11', '2022-01-16 18:45:57', '2022-01-16 18:49:44', '2022-01-16 05:00:00', '159.30', '159.30', 3, '0.00', 27, NULL, NULL, NULL, NULL, NULL, '135.00', '0.00', '0.00', '159.30', '135.00', '24.30', '0', 'La Factura numero F012-00000030, ha sido aceptada', 'F012-00000030', '', '20293185060-01-F012-00000030', 'comprobantes/2022/01/5/R-20293185060-01-F012-00000030.zip', 'comprobantes/2022/01/5/20293185060-01-F012-00000030.pdf', 'comprobantes/2022/01/5/20293185060-01-F012-00000030.xml', NULL, '2022-01-16 23:49:44', '2022-01-16 23:45:57', '159.30', '0.00'),
(6, '22222222', 'GEAN CARLOS BAILA LAURENTE', '44444444', 'GEAN CARLOS BAILA LAURENTE', NULL, NULL, 4, 3, 4, 8, 1, 'B001', '00000005', '2022-01-16', '18:51:37', '2022-01-16 18:51:37', '2022-01-16 18:54:18', '2022-01-16 05:00:00', '885.00', '885.00', 3, '0.00', 150, '021', '001', '0004-3342343243', '10', '88.50', '750.00', '0.00', '0.00', '885.00', '750.00', '135.00', '0', 'La Boleta numero B001-00000005, ha sido aceptada', 'B001-00000005', '', '20293185060-03-B001-00000005', 'comprobantes/2022/01/6/R-20293185060-03-B001-00000005.zip', 'comprobantes/2022/01/6/20293185060-03-B001-00000005.pdf', 'comprobantes/2022/01/6/20293185060-03-B001-00000005.xml', NULL, '2022-01-16 23:54:18', '2022-01-16 23:51:37', '885.00', '0.00'),
(7, '10471343736', 'BITERA EIRL', '47134373', 'GEAN CARLOS BAILA LAURENTE', NULL, NULL, 4, 3, 4, 9, 2, 'F001', '00000005', '2022-01-16', '18:52:38', '2022-01-16 18:52:38', '2022-01-16 18:54:15', '2022-01-16 05:00:00', '377.60', '377.60', 3, '0.00', 64, NULL, NULL, NULL, NULL, NULL, '320.00', '0.00', '0.00', '377.60', '320.00', '57.60', '0', 'La Factura numero F001-00000005, ha sido aceptada', 'F001-00000005', '', '20293185060-01-F001-00000005', 'comprobantes/2022/01/7/R-20293185060-01-F001-00000005.zip', 'comprobantes/2022/01/7/20293185060-01-F001-00000005.pdf', 'comprobantes/2022/01/7/20293185060-01-F001-00000005.xml', NULL, '2022-01-16 23:54:15', '2022-01-16 23:52:38', '377.60', '0.00'),
(8, '22222222', 'GEAN CARLOS BAILA LAURENTE', '47134373', 'GEAN CARLOS BAILA LAURENTE', '80808080', 'GEAN CARLOS BAILA LAURENTE', 3, 4, 3, 10, 1, 'B013', '00000019', '2022-01-16', '18:55:19', '2022-01-16 18:55:19', '2022-01-16 18:59:23', '2022-01-16 05:00:00', '1180.00', '1180.00', 3, '0.00', 150, '021', '001', '0004-3342343243', '10', '118.00', '1000.00', '0.00', '0.00', '1180.00', '1000.00', '180.00', '0', 'La Boleta numero B013-00000019, ha sido aceptada', 'B013-00000019', '', '20293185060-03-B013-00000019', 'comprobantes/2022/01/8/R-20293185060-03-B013-00000019.zip', 'comprobantes/2022/01/8/20293185060-03-B013-00000019.pdf', 'comprobantes/2022/01/8/20293185060-03-B013-00000019.xml', NULL, '2022-01-16 23:59:23', '2022-01-16 23:55:19', '1180.00', '0.00'),
(9, '10471343736', 'BITERA EIRL', '47134373', 'GEAN CARLOS BAILA LAURENTE', NULL, NULL, 3, 5, 3, 11, 2, 'F013', '00000010', '2022-01-16', '18:58:27', '2022-01-16 18:58:27', '2022-01-16 18:59:22', '2022-01-16 05:00:00', '590.00', '590.00', 3, '0.00', 100, NULL, NULL, NULL, NULL, NULL, '500.00', '0.00', '0.00', '590.00', '500.00', '90.00', '0', 'La Factura numero F013-00000010, ha sido aceptada', 'F013-00000010', '', '20293185060-01-F013-00000010', 'comprobantes/2022/01/9/R-20293185060-01-F013-00000010.zip', 'comprobantes/2022/01/9/20293185060-01-F013-00000010.pdf', 'comprobantes/2022/01/9/20293185060-01-F013-00000010.xml', NULL, '2022-01-16 23:59:22', '2022-01-16 23:58:27', '590.00', '0.00'),
(10, '47134373', 'GEAN CARLOS BAILA LAURENTE', '47134373', 'GEAN CARLOS BAILA LAURENTE', NULL, NULL, 1, 5, 1, 12, 1, 'B011', '00000018', '2022-03-28', '20:21:36', '2022-03-28 20:21:36', '2022-03-28 20:34:09', '2022-03-28 05:00:00', '365.80', '365.80', 3, '0.00', 53, NULL, NULL, NULL, NULL, NULL, '310.00', '0.00', '0.00', '365.80', '310.00', '55.80', '0', 'La Boleta numero B011-00000018, ha sido aceptada', 'B011-00000018', '', '20293185060-03-B011-00000018', 'comprobantes/2022/03/10/R-20293185060-03-B011-00000018.zip', 'comprobantes/2022/03/10/20293185060-03-B011-00000018.pdf', 'comprobantes/2022/03/10/20293185060-03-B011-00000018.xml', NULL, '2022-03-29 01:34:09', '2022-03-29 01:21:36', '365.80', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `encargo_detalle`
--

CREATE TABLE `encargo_detalle` (
  `id` int(11) NOT NULL,
  `encargo_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `codigo_producto` varchar(4) NOT NULL,
  `descripcion` varchar(60) NOT NULL,
  `cantidad_item` int(11) NOT NULL DEFAULT 0,
  `peso` int(11) NOT NULL DEFAULT 1,
  `valor_unitario` decimal(12,2) NOT NULL COMMENT 'no tiene IGV',
  `valor_venta` decimal(12,2) NOT NULL COMMENT 'no tiene IGV',
  `valor_base_igv` decimal(12,2) NOT NULL COMMENT 'no tiene IGV',
  `porcentaje_igv` decimal(12,2) NOT NULL,
  `igv_venta` decimal(12,2) NOT NULL COMMENT 'valor del IGV',
  `tipo_afectacion` varchar(2) NOT NULL,
  `precio_unitario` decimal(12,2) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `encargo_detalle`
--

INSERT INTO `encargo_detalle` (`id`, `encargo_id`, `item_id`, `codigo_producto`, `descripcion`, `cantidad_item`, `peso`, `valor_unitario`, `valor_venta`, `valor_base_igv`, `porcentaje_igv`, `igv_venta`, `tipo_afectacion`, `precio_unitario`, `updated_at`, `created_at`) VALUES
(1, 1, 3, 'P003', 'BALÓN', 13, 1, '10.00', '130.00', '130.00', '18.00', '23.40', '10', '11.80', '2022-01-16 20:12:50', '2022-01-16 20:12:50'),
(2, 1, 4, 'P004', 'BATERÍA', 14, 1, '5.00', '70.00', '70.00', '18.00', '12.60', '10', '5.90', '2022-01-16 20:12:50', '2022-01-16 20:12:50'),
(3, 2, 7, 'P007', 'BOLETO', 14, 1, '5.00', '70.00', '70.00', '18.00', '12.60', '10', '5.90', '2022-01-16 20:14:02', '2022-01-16 20:14:02'),
(4, 3, 3, 'P003', 'BALÓN', 100, 1, '10.00', '1000.00', '1000.00', '18.00', '180.00', '10', '11.80', '2022-01-16 20:14:27', '2022-01-16 20:14:27'),
(5, 4, 3, 'P003', 'BALÓN', 16, 1, '10.00', '160.00', '160.00', '18.00', '28.80', '10', '11.80', '2022-01-16 20:15:58', '2022-01-16 20:15:58'),
(6, 5, 4, 'P004', 'BATERÍA', 10, 1, '5.00', '50.00', '50.00', '18.00', '9.00', '10', '5.90', '2022-01-16 23:45:57', '2022-01-16 23:45:57'),
(7, 5, 6, 'P006', 'BOBINAS', 13, 1, '5.00', '65.00', '65.00', '18.00', '11.70', '10', '5.90', '2022-01-16 23:45:57', '2022-01-16 23:45:57'),
(8, 5, 9, 'P009', 'CAJA', 4, 1, '5.00', '20.00', '20.00', '18.00', '3.60', '10', '5.90', '2022-01-16 23:45:57', '2022-01-16 23:45:57'),
(9, 6, 4, 'P004', 'BATERÍA', 150, 1, '5.00', '750.00', '750.00', '18.00', '135.00', '10', '5.90', '2022-01-16 23:51:37', '2022-01-16 23:51:37'),
(10, 7, 7, 'P007', 'BOLETO', 50, 1, '5.00', '250.00', '250.00', '18.00', '45.00', '10', '5.90', '2022-01-16 23:52:38', '2022-01-16 23:52:38'),
(11, 7, 6, 'P006', 'BOBINAS', 14, 1, '5.00', '70.00', '70.00', '18.00', '12.60', '10', '5.90', '2022-01-16 23:52:38', '2022-01-16 23:52:38'),
(12, 8, 3, 'P003', 'BALÓN', 50, 1, '10.00', '500.00', '500.00', '18.00', '90.00', '10', '11.80', '2022-01-16 23:55:19', '2022-01-16 23:55:19'),
(13, 8, 6, 'P006', 'BOBINAS', 50, 1, '5.00', '250.00', '250.00', '18.00', '45.00', '10', '5.90', '2022-01-16 23:55:19', '2022-01-16 23:55:19'),
(14, 8, 8, 'P008', 'BULTO', 50, 1, '5.00', '250.00', '250.00', '18.00', '45.00', '10', '5.90', '2022-01-16 23:55:19', '2022-01-16 23:55:19'),
(15, 9, 12, 'P012', 'CAMILLA', 100, 1, '5.00', '500.00', '500.00', '18.00', '90.00', '10', '5.90', '2022-01-16 23:58:27', '2022-01-16 23:58:27'),
(16, 10, 1, 'P001', 'OTROS', 3, 1, '20.00', '60.00', '60.00', '18.00', '10.80', '10', '23.60', '2022-03-29 01:21:36', '2022-03-29 01:21:36'),
(17, 10, 10, 'P010', 'CAJITA', 50, 1, '5.00', '250.00', '250.00', '18.00', '45.00', '10', '5.90', '2022-03-29 01:21:36', '2022-03-29 01:21:36');

-- --------------------------------------------------------

--
-- Table structure for table `encargo_estado`
--

CREATE TABLE `encargo_estado` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `encargo_estado`
--

INSERT INTO `encargo_estado` (`id`, `nombre`) VALUES
(1, 'listo para trasladar'),
(2, 'no trasladar'),
(3, 'en manifiesto');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guia_transportista`
--

CREATE TABLE `guia_transportista` (
  `id` int(11) NOT NULL,
  `encargo_id` int(11) DEFAULT NULL,
  `fecha_hora_envia` datetime DEFAULT NULL,
  `fecha_hora_recibe` datetime DEFAULT NULL,
  `doc_envia` varchar(20) NOT NULL,
  `nombre_envia` varchar(60) NOT NULL,
  `doc_recibe` varchar(20) NOT NULL,
  `nombre_recibe` varchar(60) NOT NULL,
  `fecha_recibe` date NOT NULL,
  `agencia_origen` int(11) NOT NULL,
  `agencia_destino` int(11) NOT NULL,
  `agencia_id` int(11) NOT NULL,
  `adquiriente_id` int(11) NOT NULL,
  `documento_id` int(11) NOT NULL,
  `documento_serie` varchar(4) NOT NULL,
  `documento_correlativo` varchar(8) DEFAULT NULL,
  `documento_fecha` date NOT NULL,
  `documento_hora` time NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `oferta` decimal(12,2) NOT NULL,
  `estado` int(11) NOT NULL,
  `descuento` decimal(12,2) NOT NULL,
  `cantidad_item` int(11) NOT NULL DEFAULT 0,
  `detraccion_codigo` varchar(10) DEFAULT NULL,
  `detraccion_medio_pago` varchar(10) DEFAULT NULL,
  `detraccion_cta_banco` varchar(20) DEFAULT NULL,
  `detraccion_porcentaje` varchar(10) DEFAULT NULL,
  `detraccion_monto` decimal(12,2) DEFAULT NULL,
  `monto_gravado` decimal(12,2) NOT NULL DEFAULT 0.00,
  `monto_exonerado` decimal(12,2) NOT NULL DEFAULT 0.00,
  `monto_inafecto` decimal(12,2) NOT NULL DEFAULT 0.00,
  `importe_pagar_con_igv` decimal(12,2) NOT NULL DEFAULT 0.00,
  `importe_pagar_sin_igv` decimal(12,2) NOT NULL DEFAULT 0.00,
  `importe_pagar_igv` decimal(12,2) NOT NULL DEFAULT 0.00,
  `cdr_codigo` varchar(4) DEFAULT NULL,
  `cdr_descripcion` varchar(200) DEFAULT NULL,
  `cdr_id` varchar(20) DEFAULT NULL,
  `cdr_notas` varchar(2000) DEFAULT NULL,
  `nombre_archivo` varchar(200) DEFAULT NULL,
  `url_documento_cdr` varchar(200) DEFAULT NULL,
  `url_documento_pdf` varchar(200) DEFAULT NULL,
  `url_documento_xml` varchar(200) DEFAULT NULL,
  `url_documento_baja` varchar(200) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `guia_transportista`
--

INSERT INTO `guia_transportista` (`id`, `encargo_id`, `fecha_hora_envia`, `fecha_hora_recibe`, `doc_envia`, `nombre_envia`, `doc_recibe`, `nombre_recibe`, `fecha_recibe`, `agencia_origen`, `agencia_destino`, `agencia_id`, `adquiriente_id`, `documento_id`, `documento_serie`, `documento_correlativo`, `documento_fecha`, `documento_hora`, `subtotal`, `oferta`, `estado`, `descuento`, `cantidad_item`, `detraccion_codigo`, `detraccion_medio_pago`, `detraccion_cta_banco`, `detraccion_porcentaje`, `detraccion_monto`, `monto_gravado`, `monto_exonerado`, `monto_inafecto`, `importe_pagar_con_igv`, `importe_pagar_sin_igv`, `importe_pagar_igv`, `cdr_codigo`, `cdr_descripcion`, `cdr_id`, `cdr_notas`, `nombre_archivo`, `url_documento_cdr`, `url_documento_pdf`, `url_documento_xml`, `url_documento_baja`, `updated_at`, `created_at`) VALUES
(1, 2, '2022-01-16 15:14:02', NULL, '22222222', 'GEAN CARLOS BAILA LAURENTE', '44444444', 'GEAN CARLOS BAILA LAURENTE', '2022-01-16', 2, 5, 2, 5, 1, 'B012', '00000042', '2022-01-16', '15:25:20', '82.60', '82.60', 2, '0.00', 14, NULL, NULL, NULL, NULL, NULL, '70.00', '0.00', '0.00', '82.60', '70.00', '12.60', '', '', '', '', '', '', 'comprobantes/2022/01/2/20293185060-09-G002-00000022.pdf', '', NULL, '2022-01-16 20:25:20', '2022-01-16 20:14:02'),
(2, 5, '2022-01-16 18:45:57', NULL, '10471343736', 'BITERA EIRL', '44444444', 'GEAN CARLOS BAILA LAURENTE', '2022-01-16', 2, 5, 2, 7, 2, 'F012', '00000030', '2022-01-16', '18:48:11', '159.30', '159.30', 2, '0.00', 27, NULL, NULL, NULL, NULL, NULL, '135.00', '0.00', '0.00', '159.30', '135.00', '24.30', '', '', '', '', '', '', 'comprobantes/2022/01/5/20293185060-09-G002-00000023.pdf', '', NULL, '2022-01-16 23:48:11', '2022-01-16 23:45:57');

-- --------------------------------------------------------

--
-- Table structure for table `guia_transportista_detalle`
--

CREATE TABLE `guia_transportista_detalle` (
  `id` int(11) NOT NULL,
  `encargo_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `codigo_producto` varchar(4) NOT NULL,
  `descripcion` varchar(60) NOT NULL,
  `cantidad_item` int(11) NOT NULL DEFAULT 0,
  `peso` int(11) NOT NULL DEFAULT 1,
  `valor_unitario` decimal(12,2) NOT NULL,
  `valor_venta` decimal(12,2) NOT NULL,
  `valor_base_igv` decimal(12,2) NOT NULL,
  `porcentaje_igv` decimal(12,2) NOT NULL,
  `igv_venta` decimal(12,2) NOT NULL,
  `tipo_afectacion` varchar(2) NOT NULL,
  `precio_unitario` decimal(12,2) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `guia_transportista_detalle`
--

INSERT INTO `guia_transportista_detalle` (`id`, `encargo_id`, `item_id`, `codigo_producto`, `descripcion`, `cantidad_item`, `peso`, `valor_unitario`, `valor_venta`, `valor_base_igv`, `porcentaje_igv`, `igv_venta`, `tipo_afectacion`, `precio_unitario`, `updated_at`, `created_at`) VALUES
(1, 2, 7, 'P007', 'BOLETO', 14, 1, '5.00', '70.00', '70.00', '18.00', '12.60', '10', '5.90', '2022-01-16 20:14:02', '2022-01-16 20:14:02'),
(2, 5, 4, 'P004', 'BATERÍA', 10, 1, '5.00', '50.00', '50.00', '18.00', '9.00', '10', '5.90', '2022-01-16 23:45:57', '2022-01-16 23:45:57'),
(3, 5, 6, 'P006', 'BOBINAS', 13, 1, '5.00', '65.00', '65.00', '18.00', '11.70', '10', '5.90', '2022-01-16 23:45:57', '2022-01-16 23:45:57'),
(4, 5, 9, 'P009', 'CAJA', 4, 1, '5.00', '20.00', '20.00', '18.00', '3.60', '10', '5.90', '2022-01-16 23:45:57', '2022-01-16 23:45:57');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `valor_unitario` decimal(12,2) NOT NULL,
  `igv_unitario` decimal(12,2) NOT NULL,
  `precio_unitario` decimal(12,2) NOT NULL,
  `codigo_producto` varchar(4) NOT NULL,
  `tipo_afectacion_id` int(11) NOT NULL,
  `estado` char(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `nombre`, `valor_unitario`, `igv_unitario`, `precio_unitario`, `codigo_producto`, `tipo_afectacion_id`, `estado`) VALUES
(1, 'OTROS', '13.00', '2.34', '15.34', 'P001', 1, '1'),
(2, 'BALDES', '10.00', '1.80', '11.80', 'P002', 1, '1'),
(3, 'BALÓN', '10.00', '1.80', '11.80', 'P003', 1, '1'),
(4, 'BATERÍA', '5.00', '0.90', '5.90', 'P004', 1, '1'),
(5, 'BIDÓN', '5.00', '0.90', '5.90', 'P005', 1, '1'),
(6, 'BOBINAS', '5.00', '0.90', '5.90', 'P006', 1, '1'),
(7, 'BOLETO', '5.00', '0.90', '5.90', 'P007', 1, '1'),
(8, 'BULTO', '5.00', '0.90', '5.90', 'P008', 1, '1'),
(9, 'CAJA', '5.00', '0.90', '5.90', 'P009', 1, '1'),
(10, 'CAJITA', '5.00', '0.90', '5.90', 'P010', 1, '1'),
(11, 'CAMBIO DE FACTURA', '5.00', '0.90', '5.90', 'P011', 1, '1'),
(12, 'CAMILLA', '5.00', '0.90', '5.90', 'P012', 1, '1'),
(13, 'CANASTA', '5.00', '0.90', '5.90', 'P013', 1, '1'),
(14, 'CILINDRO', '5.00', '0.90', '5.90', 'P014', 1, '1'),
(15, 'COCINA', '5.00', '0.90', '5.90', 'P015', 1, '1'),
(16, 'COLCHON', '5.00', '0.90', '5.90', 'P016', 1, '1'),
(17, 'COLCHONETA', '5.00', '0.90', '5.90', 'P017', 1, '1'),
(18, 'COSTAL', '5.00', '0.90', '5.90', 'P018', 1, '1'),
(19, 'COSTALILLO', '5.00', '0.90', '5.90', 'P019', 1, '1'),
(20, 'COULERS', '5.00', '0.90', '5.90', 'P020', 1, '1'),
(21, 'DEVOLUCIÓN DE ENVÍOS', '5.00', '0.90', '5.90', 'P021', 1, '1'),
(22, 'DEVOLUCIÓN DE PASAJES', '5.00', '0.90', '5.90', 'P022', 1, '1'),
(23, 'EXCESO', '5.00', '0.90', '5.90', 'P023', 1, '1'),
(24, 'FARDO', '5.00', '0.90', '5.90', 'P024', 1, '1'),
(25, 'FORRADO', '5.00', '0.90', '5.90', 'P025', 1, '1'),
(26, 'GALÓN', '5.00', '0.90', '5.90', 'P026', 1, '1'),
(27, 'GALONERAS', '5.00', '0.90', '5.90', 'P027', 1, '1'),
(28, 'GIRO', '5.00', '0.90', '5.90', 'P028', 1, '1'),
(29, 'JABA', '5.00', '0.90', '5.90', 'P029', 1, '1'),
(30, 'JAULA', '5.00', '0.90', '5.90', 'P030', 1, '1'),
(31, 'LLANTAS', '5.00', '0.90', '5.90', 'P031', 1, '1'),
(32, 'MALETA', '5.00', '0.90', '5.90', 'P032', 1, '1'),
(33, 'MALETIN', '5.00', '0.90', '5.90', 'P033', 1, '1'),
(34, 'MAQUINA', '5.00', '0.90', '5.90', 'P034', 1, '1'),
(35, 'PAQUETE', '5.00', '0.90', '5.90', 'P035', 1, '1'),
(36, 'PAQUETITO', '5.00', '0.90', '5.90', 'P036', 1, '1'),
(37, 'REINTEGRO', '5.00', '0.90', '5.90', 'P037', 1, '1'),
(38, 'ROLLO', '5.00', '0.90', '5.90', 'P038', 1, '1'),
(39, 'SACO', '5.00', '0.90', '5.90', 'P039', 1, '1'),
(40, 'SAQUILLO', '5.00', '0.90', '5.90', 'P040', 1, '1'),
(41, 'SERVICIO', '5.00', '0.90', '5.90', 'P041', 1, '1'),
(42, 'SERVICIO DE PASAJES', '5.00', '0.90', '5.90', 'P042', 1, '1'),
(43, 'SILLAS', '5.00', '0.90', '5.90', 'P043', 1, '1'),
(44, 'SILLÓN', '5.00', '0.90', '5.90', 'P044', 1, '1'),
(45, 'SOBRE', '5.00', '0.90', '5.90', 'P045', 1, '1'),
(46, 'SOBRE - PAQUETE', '5.00', '0.90', '5.90', 'P046', 1, '1'),
(47, 'TUBOS', '5.00', '0.90', '5.90', 'P047', 1, '1'),
(48, 'VALIJA', '5.00', '0.90', '5.90', 'P048', 1, '1'),
(49, 'VARILLA', '5.00', '0.90', '5.90', 'P049', 1, '1'),
(50, 'VEHICULO', '5.00', '0.90', '5.90', 'P050', 1, '1');

-- --------------------------------------------------------

--
-- Table structure for table `manifiesto`
--

CREATE TABLE `manifiesto` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `ruta` varchar(20) DEFAULT NULL,
  `origen_nombre` varchar(20) DEFAULT NULL,
  `destino_nombre` varchar(20) DEFAULT NULL,
  `cantidad_item` int(11) NOT NULL DEFAULT 0,
  `subtotal_pagado` decimal(12,2) NOT NULL DEFAULT 0.00,
  `subtotal_por_pagar` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_general` decimal(12,2) NOT NULL DEFAULT 0.00,
  `nombre_archivo` varchar(60) NOT NULL,
  `url_documento_pdf` varchar(200) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `manifiesto`
--

INSERT INTO `manifiesto` (`id`, `fecha`, `hora`, `ruta`, `origen_nombre`, `destino_nombre`, `cantidad_item`, `subtotal_pagado`, `subtotal_por_pagar`, `total_general`, `nombre_archivo`, `url_documento_pdf`, `updated_at`, `created_at`) VALUES
(1, '2022-01-16', '15:13:07', 'AREQUIPA - LIMA', 'AREQUIPA', 'LIMA', 27, '236.00', '0.00', '236.00', 'manifiesto.pdf', 'resources/manifiesto/2022/01/16/manifiesto.pdf', '2022-01-16 20:13:07', '2022-01-16 20:13:07'),
(2, '2022-01-16', '15:14:57', 'AREQUIPA - LIMA', 'AREQUIPA', 'LIMA', 14, '82.60', '0.00', '82.60', 'manifiesto.pdf', 'resources/manifiesto/2022/01/16/manifiesto.pdf', '2022-01-16 20:14:57', '2022-01-16 20:14:57'),
(3, '2022-01-16', '15:16:14', 'AREQUIPA - LIMA', 'AREQUIPA', 'LIMA', 100, '1180.00', '0.00', '1180.00', 'manifiesto.pdf', 'resources/manifiesto/2022/01/16/manifiesto.pdf', '2022-01-16 20:16:14', '2022-01-16 20:16:14'),
(4, '2022-01-16', '15:19:05', 'AREQUIPA - LIMA', 'AREQUIPA', 'LIMA', 127, '1416.00', '0.00', '1416.00', 'manifiesto.pdf', 'resources/manifiesto/2022/01/16/manifiesto.pdf', '2022-01-16 20:19:05', '2022-01-16 20:19:05'),
(5, '2022-01-16', '15:20:02', 'AREQUIPA - LIMA', 'AREQUIPA', 'LIMA', 127, '1416.00', '0.00', '1416.00', 'manifiesto.pdf', 'resources/manifiesto/2022/01/16/manifiesto.pdf', '2022-01-16 20:20:02', '2022-01-16 20:20:02'),
(6, '2022-01-16', '15:21:17', 'AREQUIPA - LIMA', 'AREQUIPA', 'LIMA', 127, '1416.00', '0.00', '1416.00', 'manifiesto.pdf', 'resources/manifiesto/2022/01/16/manifiesto.pdf', '2022-01-16 20:21:17', '2022-01-16 20:21:17'),
(7, '2022-01-16', '18:46:45', 'AREQUIPA - LIMA', 'AREQUIPA', 'LIMA', 57, '430.70', '0.00', '430.70', 'manifiesto.pdf', 'resources/manifiesto/2022/01/16/manifiesto.pdf', '2022-01-16 23:46:45', '2022-01-16 23:46:45'),
(8, '2022-01-16', '18:49:11', 'AREQUIPA - LIMA', 'AREQUIPA', 'LIMA', 27, '159.30', '0.00', '159.30', 'manifiesto.pdf', 'resources/manifiesto/2022/01/16/manifiesto.pdf', '2022-01-16 23:49:11', '2022-01-16 23:49:11'),
(9, '2022-01-16', '18:53:16', 'LIMA - AREQUIPA', 'LIMA', 'AREQUIPA', 214, '1262.60', '0.00', '1262.60', 'manifiesto.pdf', 'resources/manifiesto/2022/01/16/manifiesto.pdf', '2022-01-16 23:53:16', '2022-01-16 23:53:16'),
(10, '2022-01-16', '18:58:55', 'AREQUIPA - LIMA', 'AREQUIPA', 'LIMA', 250, '1770.00', '0.00', '1770.00', 'manifiesto.pdf', 'resources/manifiesto/2022/01/16/manifiesto.pdf', '2022-01-16 23:58:55', '2022-01-16 23:58:55'),
(11, '2022-03-28', '20:30:57', 'AREQUIPA - LIMA', 'AREQUIPA', 'LIMA', 53, '365.80', '0.00', '365.80', 'manifiesto.pdf', 'resources/manifiesto/2022/03/28/manifiesto.pdf', '2022-03-29 01:30:57', '2022-03-29 01:30:57');

-- --------------------------------------------------------

--
-- Table structure for table `manifiesto_detalle`
--

CREATE TABLE `manifiesto_detalle` (
  `id` int(11) NOT NULL,
  `manifiesto_id` int(11) NOT NULL,
  `encargo_id` int(11) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `oferta` decimal(12,2) NOT NULL DEFAULT 0.00,
  `cantidad_item` int(11) NOT NULL DEFAULT 0,
  `agencia_origen` int(11) NOT NULL,
  `agencia_destino` int(11) NOT NULL,
  `documento_serie` varchar(4) NOT NULL,
  `documento_correlativo` varchar(8) NOT NULL,
  `pagado` decimal(12,2) DEFAULT 0.00,
  `por_pagar` decimal(12,2) DEFAULT 0.00,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `manifiesto_detalle`
--

INSERT INTO `manifiesto_detalle` (`id`, `manifiesto_id`, `encargo_id`, `subtotal`, `oferta`, `cantidad_item`, `agencia_origen`, `agencia_destino`, `documento_serie`, `documento_correlativo`, `pagado`, `por_pagar`, `updated_at`, `created_at`) VALUES
(1, 1, 1, '236.00', '236.00', 27, 1, 4, 'B011', '00000017', '236.00', '0.00', '2022-01-16 20:13:07', '2022-01-16 20:13:07'),
(2, 2, 2, '82.60', '82.60', 14, 2, 5, 'G002', '00000022', '0.00', '82.60', '2022-01-16 20:14:57', '2022-01-16 20:14:57'),
(3, 3, 3, '1180.00', '1180.00', 100, 1, 4, 'F011', '00000036', '1180.00', '0.00', '2022-01-16 20:16:14', '2022-01-16 20:16:14'),
(4, 4, 1, '236.00', '236.00', 27, 1, 4, 'B011', '00000017', '236.00', '0.00', '2022-01-16 20:19:05', '2022-01-16 20:19:05'),
(5, 4, 3, '1180.00', '1180.00', 100, 1, 4, 'F011', '00000036', '1180.00', '0.00', '2022-01-16 20:19:05', '2022-01-16 20:19:05'),
(6, 5, 1, '236.00', '236.00', 27, 1, 4, 'B011', '00000017', '236.00', '0.00', '2022-01-16 20:20:02', '2022-01-16 20:20:02'),
(7, 5, 3, '1180.00', '1180.00', 100, 1, 4, 'F011', '00000036', '1180.00', '0.00', '2022-01-16 20:20:02', '2022-01-16 20:20:02'),
(8, 6, 1, '236.00', '236.00', 27, 1, 4, 'B011', '00000017', '236.00', '0.00', '2022-01-16 20:21:17', '2022-01-16 20:21:17'),
(9, 6, 3, '1180.00', '1180.00', 100, 1, 4, 'F011', '00000036', '1180.00', '0.00', '2022-01-16 20:21:17', '2022-01-16 20:21:17'),
(10, 7, 2, '82.60', '82.60', 14, 2, 5, 'B012', '00000042', '82.60', '0.00', '2022-01-16 23:46:45', '2022-01-16 23:46:45'),
(11, 7, 4, '188.80', '188.80', 16, 2, 5, 'B012', '00000041', '188.80', '0.00', '2022-01-16 23:46:45', '2022-01-16 23:46:45'),
(12, 7, 5, '159.30', '159.30', 27, 2, 5, 'G002', '00000023', '0.00', '159.30', '2022-01-16 23:46:45', '2022-01-16 23:46:45'),
(13, 8, 5, '159.30', '159.30', 27, 2, 5, 'F012', '00000030', '159.30', '0.00', '2022-01-16 23:49:11', '2022-01-16 23:49:11'),
(14, 9, 6, '885.00', '885.00', 150, 4, 3, 'B001', '00000005', '885.00', '0.00', '2022-01-16 23:53:16', '2022-01-16 23:53:16'),
(15, 9, 7, '377.60', '377.60', 64, 4, 3, 'F001', '00000005', '377.60', '0.00', '2022-01-16 23:53:16', '2022-01-16 23:53:16'),
(16, 10, 8, '1180.00', '1180.00', 150, 3, 4, 'B013', '00000019', '1180.00', '0.00', '2022-01-16 23:58:55', '2022-01-16 23:58:55'),
(17, 10, 9, '590.00', '590.00', 100, 3, 5, 'F013', '00000010', '590.00', '0.00', '2022-01-16 23:58:55', '2022-01-16 23:58:55'),
(18, 11, 10, '365.80', '365.80', 53, 1, 5, 'B011', '00000018', '365.80', '0.00', '2022-03-29 01:30:57', '2022-03-29 01:30:57');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(7, '2014_10_12_000000_create_users_table', 1),
(8, '2014_10_12_100000_create_password_resets_table', 1),
(9, '2019_08_19_000000_create_failed_jobs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sede`
--

CREATE TABLE `sede` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `estado` char(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sede`
--

INSERT INTO `sede` (`id`, `nombre`, `estado`) VALUES
(1, 'CUSCO', '0'),
(2, 'AREQUIPA', '1'),
(3, 'LIMA', '1');

-- --------------------------------------------------------

--
-- Table structure for table `serie`
--

CREATE TABLE `serie` (
  `id` int(11) NOT NULL,
  `documento_id` int(11) NOT NULL,
  `agencia_id` int(11) NOT NULL,
  `serie` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `serie`
--

INSERT INTO `serie` (`id`, `documento_id`, `agencia_id`, `serie`) VALUES
(1, 1, 4, 'B001'),
(2, 1, 5, 'B002'),
(3, 1, 1, 'B011'),
(4, 1, 2, 'B012'),
(5, 1, 3, 'B013'),
(6, 2, 4, 'F001'),
(7, 2, 5, 'F002'),
(8, 2, 1, 'F011'),
(33, 2, 2, 'F012'),
(34, 2, 3, 'F013'),
(35, 3, 4, 'G001'),
(36, 3, 5, 'G002'),
(37, 3, 1, 'G011'),
(38, 3, 2, 'G012'),
(39, 3, 3, 'G013');

-- --------------------------------------------------------

--
-- Table structure for table `tipo_afectacion`
--

CREATE TABLE `tipo_afectacion` (
  `id` int(11) NOT NULL,
  `codigo` varchar(2) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `descripcion` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tipo_afectacion`
--

INSERT INTO `tipo_afectacion` (`id`, `codigo`, `nombre`, `descripcion`) VALUES
(1, '10', 'gravado', ''),
(2, '20', 'exonerado', ''),
(3, '30', 'inafecto', ''),
(4, '13', 'gravado gratuito ', ''),
(5, '32', 'inafecto gratuito', '');

-- --------------------------------------------------------

--
-- Table structure for table `ubigeo2016`
--

CREATE TABLE `ubigeo2016` (
  `id` int(11) NOT NULL,
  `codigo_departamento` char(2) DEFAULT 'XX',
  `departamento` varchar(200) DEFAULT NULL,
  `codigo_provincia` char(2) DEFAULT 'XX',
  `provincia` varchar(200) DEFAULT NULL,
  `codigo_distrito` char(2) DEFAULT 'XX',
  `distrito` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ubigeo2016`
--

INSERT INTO `ubigeo2016` (`id`, `codigo_departamento`, `departamento`, `codigo_provincia`, `provincia`, `codigo_distrito`, `distrito`) VALUES
(1, '01', '01 Amazonas', '00', ' ', '00', ' '),
(2, '01', '01 Amazonas', '01', '01 Chachapoyas', '00', ' '),
(3, '01', '01 Amazonas', '01', '01 Chachapoyas', '01', '01 Chachapoyas'),
(4, '01', '01 Amazonas', '01', '01 Chachapoyas', '02', '02 Asunción'),
(5, '01', '01 Amazonas', '01', '01 Chachapoyas', '03', '03 Balsas'),
(6, '01', '01 Amazonas', '01', '01 Chachapoyas', '04', '04 Cheto'),
(7, '01', '01 Amazonas', '01', '01 Chachapoyas', '05', '05 Chiliquin'),
(8, '01', '01 Amazonas', '01', '01 Chachapoyas', '06', '06 Chuquibamba'),
(9, '01', '01 Amazonas', '01', '01 Chachapoyas', '07', '07 Granada'),
(10, '01', '01 Amazonas', '01', '01 Chachapoyas', '08', '08 Huancas'),
(11, '01', '01 Amazonas', '01', '01 Chachapoyas', '09', '09 La Jalca'),
(12, '01', '01 Amazonas', '01', '01 Chachapoyas', '10', '10 Leimebamba'),
(13, '01', '01 Amazonas', '01', '01 Chachapoyas', '11', '11 Levanto'),
(14, '01', '01 Amazonas', '01', '01 Chachapoyas', '12', '12 Magdalena'),
(15, '01', '01 Amazonas', '01', '01 Chachapoyas', '13', '13 Mariscal Castilla'),
(16, '01', '01 Amazonas', '01', '01 Chachapoyas', '14', '14 Molinopampa'),
(17, '01', '01 Amazonas', '01', '01 Chachapoyas', '15', '15 Montevideo'),
(18, '01', '01 Amazonas', '01', '01 Chachapoyas', '16', '16 Olleros'),
(19, '01', '01 Amazonas', '01', '01 Chachapoyas', '17', '17 Quinjalca'),
(20, '01', '01 Amazonas', '01', '01 Chachapoyas', '18', '18 San Francisco de Daguas'),
(21, '01', '01 Amazonas', '01', '01 Chachapoyas', '19', '19 San Isidro de Maino'),
(22, '01', '01 Amazonas', '01', '01 Chachapoyas', '20', '20 Soloco'),
(23, '01', '01 Amazonas', '01', '01 Chachapoyas', '21', '21 Sonche'),
(24, '01', '01 Amazonas', '02', '02 Bagua', '00', ' '),
(25, '01', '01 Amazonas', '02', '02 Bagua', '01', '01 Bagua'),
(26, '01', '01 Amazonas', '02', '02 Bagua', '02', '02 Aramango'),
(27, '01', '01 Amazonas', '02', '02 Bagua', '03', '03 Copallin'),
(28, '01', '01 Amazonas', '02', '02 Bagua', '04', '04 El Parco'),
(29, '01', '01 Amazonas', '02', '02 Bagua', '05', '05 Imaza'),
(30, '01', '01 Amazonas', '02', '02 Bagua', '06', '06 La Peca'),
(31, '01', '01 Amazonas', '03', '03 Bongará', '00', ' '),
(32, '01', '01 Amazonas', '03', '03 Bongará', '01', '01 Jumbilla'),
(33, '01', '01 Amazonas', '03', '03 Bongará', '02', '02 Chisquilla'),
(34, '01', '01 Amazonas', '03', '03 Bongará', '03', '03 Churuja'),
(35, '01', '01 Amazonas', '03', '03 Bongará', '04', '04 Corosha'),
(36, '01', '01 Amazonas', '03', '03 Bongará', '05', '05 Cuispes'),
(37, '01', '01 Amazonas', '03', '03 Bongará', '06', '06 Florida'),
(38, '01', '01 Amazonas', '03', '03 Bongará', '07', '07 Jazan'),
(39, '01', '01 Amazonas', '03', '03 Bongará', '08', '08 Recta'),
(40, '01', '01 Amazonas', '03', '03 Bongará', '09', '09 San Carlos'),
(41, '01', '01 Amazonas', '03', '03 Bongará', '10', '10 Shipasbamba'),
(42, '01', '01 Amazonas', '03', '03 Bongará', '11', '11 Valera'),
(43, '01', '01 Amazonas', '03', '03 Bongará', '12', '12 Yambrasbamba'),
(44, '01', '01 Amazonas', '04', '04 Condorcanqui', '00', ' '),
(45, '01', '01 Amazonas', '04', '04 Condorcanqui', '01', '01 Nieva'),
(46, '01', '01 Amazonas', '04', '04 Condorcanqui', '02', '02 El Cenepa'),
(47, '01', '01 Amazonas', '04', '04 Condorcanqui', '03', '03 Río Santiago'),
(48, '01', '01 Amazonas', '05', '05 Luya', '00', ' '),
(49, '01', '01 Amazonas', '05', '05 Luya', '01', '01 Lamud'),
(51, '01', '01 Amazonas', '05', '05 Luya', '02', '02 Camporredondo'),
(52, '01', '01 Amazonas', '05', '05 Luya', '03', '03 Cocabamba'),
(53, '01', '01 Amazonas', '05', '05 Luya', '04', '04 Colcamar'),
(54, '01', '01 Amazonas', '05', '05 Luya', '05', '05 Conila'),
(55, '01', '01 Amazonas', '05', '05 Luya', '06', '06 Inguilpata'),
(56, '01', '01 Amazonas', '05', '05 Luya', '07', '07 Longuita'),
(57, '01', '01 Amazonas', '05', '05 Luya', '08', '08 Lonya Chico'),
(58, '01', '01 Amazonas', '05', '05 Luya', '09', '09 Luya'),
(59, '01', '01 Amazonas', '05', '05 Luya', '10', '10 Luya Viejo'),
(60, '01', '01 Amazonas', '05', '05 Luya', '11', '11 María'),
(61, '01', '01 Amazonas', '05', '05 Luya', '12', '12 Ocalli'),
(62, '01', '01 Amazonas', '05', '05 Luya', '13', '13 Ocumal'),
(63, '01', '01 Amazonas', '05', '05 Luya', '14', '14 Pisuquia'),
(64, '01', '01 Amazonas', '05', '05 Luya', '15', '15 Providencia'),
(65, '01', '01 Amazonas', '05', '05 Luya', '16', '16 San Cristóbal'),
(66, '01', '01 Amazonas', '05', '05 Luya', '17', '17 San Francisco de Yeso'),
(67, '01', '01 Amazonas', '05', '05 Luya', '18', '18 San Jerónimo'),
(68, '01', '01 Amazonas', '05', '05 Luya', '19', '19 San Juan de Lopecancha'),
(69, '01', '01 Amazonas', '05', '05 Luya', '20', '20 Santa Catalina'),
(70, '01', '01 Amazonas', '05', '05 Luya', '21', '21 Santo Tomas'),
(71, '01', '01 Amazonas', '05', '05 Luya', '22', '22 Tingo'),
(72, '01', '01 Amazonas', '05', '05 Luya', '23', '23 Trita'),
(73, '01', '01 Amazonas', '06', '06 Rodríguez de Mendoza', '00', ' '),
(74, '01', '01 Amazonas', '06', '06 Rodríguez de Mendoza', '01', '01 San Nicolás'),
(75, '01', '01 Amazonas', '06', '06 Rodríguez de Mendoza', '02', '02 Chirimoto'),
(76, '01', '01 Amazonas', '06', '06 Rodríguez de Mendoza', '03', '03 Cochamal'),
(77, '01', '01 Amazonas', '06', '06 Rodríguez de Mendoza', '04', '04 Huambo'),
(78, '01', '01 Amazonas', '06', '06 Rodríguez de Mendoza', '05', '05 Limabamba'),
(79, '01', '01 Amazonas', '06', '06 Rodríguez de Mendoza', '06', '06 Longar'),
(80, '01', '01 Amazonas', '06', '06 Rodríguez de Mendoza', '07', '07 Mariscal Benavides'),
(81, '01', '01 Amazonas', '06', '06 Rodríguez de Mendoza', '08', '08 Milpuc'),
(82, '01', '01 Amazonas', '06', '06 Rodríguez de Mendoza', '09', '09 Omia'),
(83, '01', '01 Amazonas', '06', '06 Rodríguez de Mendoza', '10', '10 Santa Rosa'),
(84, '01', '01 Amazonas', '06', '06 Rodríguez de Mendoza', '11', '11 Totora'),
(85, '01', '01 Amazonas', '06', '06 Rodríguez de Mendoza', '12', '12 Vista Alegre'),
(86, '01', '01 Amazonas', '07', '07 Utcubamba', '00', ' '),
(87, '01', '01 Amazonas', '07', '07 Utcubamba', '01', '01 Bagua Grande'),
(88, '01', '01 Amazonas', '07', '07 Utcubamba', '02', '02 Cajaruro'),
(89, '01', '01 Amazonas', '07', '07 Utcubamba', '03', '03 Cumba'),
(90, '01', '01 Amazonas', '07', '07 Utcubamba', '04', '04 El Milagro'),
(91, '01', '01 Amazonas', '07', '07 Utcubamba', '05', '05 Jamalca'),
(92, '01', '01 Amazonas', '07', '07 Utcubamba', '06', '06 Lonya Grande'),
(93, '01', '01 Amazonas', '07', '07 Utcubamba', '07', '07 Yamon'),
(94, '02', '02 Áncash', '00', ' ', '00', ' '),
(95, '02', '02 Áncash', '01', '01 Huaraz', '00', ' '),
(96, '02', '02 Áncash', '01', '01 Huaraz', '01', '01 Huaraz'),
(97, '02', '02 Áncash', '01', '01 Huaraz', '02', '02 Cochabamba'),
(98, '02', '02 Áncash', '01', '01 Huaraz', '03', '03 Colcabamba'),
(99, '02', '02 Áncash', '01', '01 Huaraz', '04', '04 Huanchay'),
(100, '02', '02 Áncash', '01', '01 Huaraz', '05', '05 Independencia'),
(101, '02', '02 Áncash', '01', '01 Huaraz', '06', '06 Jangas'),
(103, '02', '02 Áncash', '01', '01 Huaraz', '07', '07 La Libertad'),
(104, '02', '02 Áncash', '01', '01 Huaraz', '08', '08 Olleros'),
(105, '02', '02 Áncash', '01', '01 Huaraz', '09', '09 Pampas Grande'),
(106, '02', '02 Áncash', '01', '01 Huaraz', '10', '10 Pariacoto'),
(107, '02', '02 Áncash', '01', '01 Huaraz', '11', '11 Pira'),
(108, '02', '02 Áncash', '01', '01 Huaraz', '12', '12 Tarica'),
(109, '02', '02 Áncash', '02', '02 Aija', '00', ' '),
(110, '02', '02 Áncash', '02', '02 Aija', '01', '01 Aija'),
(111, '02', '02 Áncash', '02', '02 Aija', '02', '02 Coris'),
(112, '02', '02 Áncash', '02', '02 Aija', '03', '03 Huacllan'),
(113, '02', '02 Áncash', '02', '02 Aija', '04', '04 La Merced'),
(114, '02', '02 Áncash', '02', '02 Aija', '05', '05 Succha'),
(115, '02', '02 Áncash', '03', '03 Antonio Raymondi', '00', ' '),
(116, '02', '02 Áncash', '03', '03 Antonio Raymondi', '01', '01 Llamellin'),
(117, '02', '02 Áncash', '03', '03 Antonio Raymondi', '02', '02 Aczo'),
(118, '02', '02 Áncash', '03', '03 Antonio Raymondi', '03', '03 Chaccho'),
(119, '02', '02 Áncash', '03', '03 Antonio Raymondi', '04', '04 Chingas'),
(120, '02', '02 Áncash', '03', '03 Antonio Raymondi', '05', '05 Mirgas'),
(121, '02', '02 Áncash', '03', '03 Antonio Raymondi', '06', '06 San Juan de Rontoy'),
(122, '02', '02 Áncash', '04', '04 Asunción', '00', ' '),
(123, '02', '02 Áncash', '04', '04 Asunción', '01', '01 Chacas'),
(124, '02', '02 Áncash', '04', '04 Asunción', '02', '02 Acochaca'),
(125, '02', '02 Áncash', '05', '05 Bolognesi', '00', ' '),
(126, '02', '02 Áncash', '05', '05 Bolognesi', '01', '01 Chiquian'),
(127, '02', '02 Áncash', '05', '05 Bolognesi', '02', '02 Abelardo Pardo Lezameta'),
(128, '02', '02 Áncash', '05', '05 Bolognesi', '03', '03 Antonio Raymondi'),
(129, '02', '02 Áncash', '05', '05 Bolognesi', '04', '04 Aquia'),
(130, '02', '02 Áncash', '05', '05 Bolognesi', '05', '05 Cajacay'),
(131, '02', '02 Áncash', '05', '05 Bolognesi', '06', '06 Canis'),
(132, '02', '02 Áncash', '05', '05 Bolognesi', '07', '07 Colquioc'),
(133, '02', '02 Áncash', '05', '05 Bolognesi', '08', '08 Huallanca'),
(134, '02', '02 Áncash', '05', '05 Bolognesi', '09', '09 Huasta'),
(135, '02', '02 Áncash', '05', '05 Bolognesi', '10', '10 Huayllacayan'),
(136, '02', '02 Áncash', '05', '05 Bolognesi', '11', '11 La Primavera'),
(137, '02', '02 Áncash', '05', '05 Bolognesi', '12', '12 Mangas'),
(138, '02', '02 Áncash', '05', '05 Bolognesi', '13', '13 Pacllon'),
(139, '02', '02 Áncash', '05', '05 Bolognesi', '14', '14 San Miguel de Corpanqui'),
(140, '02', '02 Áncash', '05', '05 Bolognesi', '15', '15 Ticllos'),
(141, '02', '02 Áncash', '06', '06 Carhuaz', '00', ' '),
(142, '02', '02 Áncash', '06', '06 Carhuaz', '01', '01 Carhuaz'),
(143, '02', '02 Áncash', '06', '06 Carhuaz', '02', '02 Acopampa'),
(144, '02', '02 Áncash', '06', '06 Carhuaz', '03', '03 Amashca'),
(145, '02', '02 Áncash', '06', '06 Carhuaz', '04', '04 Anta'),
(146, '02', '02 Áncash', '06', '06 Carhuaz', '05', '05 Ataquero'),
(147, '02', '02 Áncash', '06', '06 Carhuaz', '06', '06 Marcara'),
(148, '02', '02 Áncash', '06', '06 Carhuaz', '07', '07 Pariahuanca'),
(149, '02', '02 Áncash', '06', '06 Carhuaz', '08', '08 San Miguel de Aco'),
(150, '02', '02 Áncash', '06', '06 Carhuaz', '09', '09 Shilla'),
(151, '02', '02 Áncash', '06', '06 Carhuaz', '10', '10 Tinco'),
(152, '02', '02 Áncash', '06', '06 Carhuaz', '11', '11 Yungar'),
(153, '02', '02 Áncash', '07', '07 Carlos Fermín Fitzcarrald', '00', ' '),
(155, '02', '02 Áncash', '07', '07 Carlos Fermín Fitzcarrald', '01', '01 San Luis'),
(156, '02', '02 Áncash', '07', '07 Carlos Fermín Fitzcarrald', '02', '02 San Nicolás'),
(157, '02', '02 Áncash', '07', '07 Carlos Fermín Fitzcarrald', '03', '03 Yauya'),
(158, '02', '02 Áncash', '08', '08 Casma', '00', ' '),
(159, '02', '02 Áncash', '08', '08 Casma', '01', '01 Casma'),
(160, '02', '02 Áncash', '08', '08 Casma', '02', '02 Buena Vista Alta'),
(161, '02', '02 Áncash', '08', '08 Casma', '03', '03 Comandante Noel'),
(162, '02', '02 Áncash', '08', '08 Casma', '04', '04 Yautan'),
(163, '02', '02 Áncash', '09', '09 Corongo', '00', ' '),
(164, '02', '02 Áncash', '09', '09 Corongo', '01', '01 Corongo'),
(165, '02', '02 Áncash', '09', '09 Corongo', '02', '02 Aco'),
(166, '02', '02 Áncash', '09', '09 Corongo', '03', '03 Bambas'),
(167, '02', '02 Áncash', '09', '09 Corongo', '04', '04 Cusca'),
(168, '02', '02 Áncash', '09', '09 Corongo', '05', '05 La Pampa'),
(169, '02', '02 Áncash', '09', '09 Corongo', '06', '06 Yanac'),
(170, '02', '02 Áncash', '09', '09 Corongo', '07', '07 Yupan'),
(171, '02', '02 Áncash', '10', '10 Huari', '00', ' '),
(172, '02', '02 Áncash', '10', '10 Huari', '01', '01 Huari'),
(173, '02', '02 Áncash', '10', '10 Huari', '02', '02 Anra'),
(174, '02', '02 Áncash', '10', '10 Huari', '03', '03 Cajay'),
(175, '02', '02 Áncash', '10', '10 Huari', '04', '04 Chavin de Huantar'),
(176, '02', '02 Áncash', '10', '10 Huari', '05', '05 Huacachi'),
(177, '02', '02 Áncash', '10', '10 Huari', '06', '06 Huacchis'),
(178, '02', '02 Áncash', '10', '10 Huari', '07', '07 Huachis'),
(179, '02', '02 Áncash', '10', '10 Huari', '08', '08 Huantar'),
(180, '02', '02 Áncash', '10', '10 Huari', '09', '09 Masin'),
(181, '02', '02 Áncash', '10', '10 Huari', '10', '10 Paucas'),
(182, '02', '02 Áncash', '10', '10 Huari', '11', '11 Ponto'),
(183, '02', '02 Áncash', '10', '10 Huari', '12', '12 Rahuapampa'),
(184, '02', '02 Áncash', '10', '10 Huari', '13', '13 Rapayan'),
(185, '02', '02 Áncash', '10', '10 Huari', '14', '14 San Marcos'),
(186, '02', '02 Áncash', '10', '10 Huari', '15', '15 San Pedro de Chana'),
(187, '02', '02 Áncash', '10', '10 Huari', '16', '16 Uco'),
(188, '02', '02 Áncash', '11', '11 Huarmey', '00', ' '),
(189, '02', '02 Áncash', '11', '11 Huarmey', '01', '01 Huarmey'),
(190, '02', '02 Áncash', '11', '11 Huarmey', '02', '02 Cochapeti'),
(191, '02', '02 Áncash', '11', '11 Huarmey', '03', '03 Culebras'),
(192, '02', '02 Áncash', '11', '11 Huarmey', '04', '04 Huayan'),
(193, '02', '02 Áncash', '11', '11 Huarmey', '05', '05 Malvas'),
(194, '02', '02 Áncash', '12', '12 Huaylas', '00', ' '),
(195, '02', '02 Áncash', '12', '12 Huaylas', '01', '01 Caraz'),
(196, '02', '02 Áncash', '12', '12 Huaylas', '02', '02 Huallanca'),
(197, '02', '02 Áncash', '12', '12 Huaylas', '03', '03 Huata'),
(198, '02', '02 Áncash', '12', '12 Huaylas', '04', '04 Huaylas'),
(199, '02', '02 Áncash', '12', '12 Huaylas', '05', '05 Mato'),
(200, '02', '02 Áncash', '12', '12 Huaylas', '06', '06 Pamparomas'),
(201, '02', '02 Áncash', '12', '12 Huaylas', '07', '07 Pueblo Libre'),
(202, '02', '02 Áncash', '12', '12 Huaylas', '08', '08 Santa Cruz'),
(203, '02', '02 Áncash', '12', '12 Huaylas', '09', '09 Santo Toribio'),
(204, '02', '02 Áncash', '12', '12 Huaylas', '10', '10 Yuracmarca'),
(205, '02', '02 Áncash', '13', '13 Mariscal Luzuriaga', '00', ' '),
(207, '02', '02 Áncash', '13', '13 Mariscal Luzuriaga', '01', '01 Piscobamba'),
(208, '02', '02 Áncash', '13', '13 Mariscal Luzuriaga', '02', '02 Casca'),
(209, '02', '02 Áncash', '13', '13 Mariscal Luzuriaga', '03', '03 Eleazar Guzmán Barron'),
(210, '02', '02 Áncash', '13', '13 Mariscal Luzuriaga', '04', '04 Fidel Olivas Escudero'),
(211, '02', '02 Áncash', '13', '13 Mariscal Luzuriaga', '05', '05 Llama'),
(212, '02', '02 Áncash', '13', '13 Mariscal Luzuriaga', '06', '06 Llumpa'),
(213, '02', '02 Áncash', '13', '13 Mariscal Luzuriaga', '07', '07 Lucma'),
(214, '02', '02 Áncash', '13', '13 Mariscal Luzuriaga', '08', '08 Musga'),
(215, '02', '02 Áncash', '14', '14 Ocros', '00', ' '),
(216, '02', '02 Áncash', '14', '14 Ocros', '01', '01 Ocros'),
(217, '02', '02 Áncash', '14', '14 Ocros', '02', '02 Acas'),
(218, '02', '02 Áncash', '14', '14 Ocros', '03', '03 Cajamarquilla'),
(219, '02', '02 Áncash', '14', '14 Ocros', '04', '04 Carhuapampa'),
(220, '02', '02 Áncash', '14', '14 Ocros', '05', '05 Cochas'),
(221, '02', '02 Áncash', '14', '14 Ocros', '06', '06 Congas'),
(222, '02', '02 Áncash', '14', '14 Ocros', '07', '07 Llipa'),
(223, '02', '02 Áncash', '14', '14 Ocros', '08', '08 San Cristóbal de Rajan'),
(224, '02', '02 Áncash', '14', '14 Ocros', '09', '09 San Pedro'),
(225, '02', '02 Áncash', '14', '14 Ocros', '10', '10 Santiago de Chilcas'),
(226, '02', '02 Áncash', '15', '15 Pallasca', '00', ' '),
(227, '02', '02 Áncash', '15', '15 Pallasca', '01', '01 Cabana'),
(228, '02', '02 Áncash', '15', '15 Pallasca', '02', '02 Bolognesi'),
(229, '02', '02 Áncash', '15', '15 Pallasca', '03', '03 Conchucos'),
(230, '02', '02 Áncash', '15', '15 Pallasca', '04', '04 Huacaschuque'),
(231, '02', '02 Áncash', '15', '15 Pallasca', '05', '05 Huandoval'),
(232, '02', '02 Áncash', '15', '15 Pallasca', '06', '06 Lacabamba'),
(233, '02', '02 Áncash', '15', '15 Pallasca', '07', '07 Llapo'),
(234, '02', '02 Áncash', '15', '15 Pallasca', '08', '08 Pallasca'),
(235, '02', '02 Áncash', '15', '15 Pallasca', '09', '09 Pampas'),
(236, '02', '02 Áncash', '15', '15 Pallasca', '10', '10 Santa Rosa'),
(237, '02', '02 Áncash', '15', '15 Pallasca', '11', '11 Tauca'),
(238, '02', '02 Áncash', '16', '16 Pomabamba', '00', ' '),
(239, '02', '02 Áncash', '16', '16 Pomabamba', '01', '01 Pomabamba'),
(240, '02', '02 Áncash', '16', '16 Pomabamba', '02', '02 Huayllan'),
(241, '02', '02 Áncash', '16', '16 Pomabamba', '03', '03 Parobamba'),
(242, '02', '02 Áncash', '16', '16 Pomabamba', '04', '04 Quinuabamba'),
(243, '02', '02 Áncash', '17', '17 Recuay', '00', ' '),
(244, '02', '02 Áncash', '17', '17 Recuay', '01', '01 Recuay'),
(245, '02', '02 Áncash', '17', '17 Recuay', '02', '02 Catac'),
(246, '02', '02 Áncash', '17', '17 Recuay', '03', '03 Cotaparaco'),
(247, '02', '02 Áncash', '17', '17 Recuay', '04', '04 Huayllapampa'),
(248, '02', '02 Áncash', '17', '17 Recuay', '05', '05 Llacllin'),
(249, '02', '02 Áncash', '17', '17 Recuay', '06', '06 Marca'),
(250, '02', '02 Áncash', '17', '17 Recuay', '07', '07 Pampas Chico'),
(251, '02', '02 Áncash', '17', '17 Recuay', '08', '08 Pararin'),
(252, '02', '02 Áncash', '17', '17 Recuay', '09', '09 Tapacocha'),
(253, '02', '02 Áncash', '17', '17 Recuay', '10', '10 Ticapampa'),
(254, '02', '02 Áncash', '18', '18 Santa', '00', ' '),
(255, '02', '02 Áncash', '18', '18 Santa', '01', '01 Chimbote'),
(256, '02', '02 Áncash', '18', '18 Santa', '02', '02 Cáceres del Perú'),
(257, '02', '02 Áncash', '18', '18 Santa', '03', '03 Coishco'),
(259, '02', '02 Áncash', '18', '18 Santa', '04', '04 Macate'),
(260, '02', '02 Áncash', '18', '18 Santa', '05', '05 Moro'),
(261, '02', '02 Áncash', '18', '18 Santa', '06', '06 Nepeña'),
(262, '02', '02 Áncash', '18', '18 Santa', '07', '07 Samanco'),
(263, '02', '02 Áncash', '18', '18 Santa', '08', '08 Santa'),
(264, '02', '02 Áncash', '18', '18 Santa', '09', '09 Nuevo Chimbote'),
(265, '02', '02 Áncash', '19', '19 Sihuas', '00', ' '),
(266, '02', '02 Áncash', '19', '19 Sihuas', '01', '01 Sihuas'),
(267, '02', '02 Áncash', '19', '19 Sihuas', '02', '02 Acobamba'),
(268, '02', '02 Áncash', '19', '19 Sihuas', '03', '03 Alfonso Ugarte'),
(269, '02', '02 Áncash', '19', '19 Sihuas', '04', '04 Cashapampa'),
(270, '02', '02 Áncash', '19', '19 Sihuas', '05', '05 Chingalpo'),
(271, '02', '02 Áncash', '19', '19 Sihuas', '06', '06 Huayllabamba'),
(272, '02', '02 Áncash', '19', '19 Sihuas', '07', '07 Quiches'),
(273, '02', '02 Áncash', '19', '19 Sihuas', '08', '08 Ragash'),
(274, '02', '02 Áncash', '19', '19 Sihuas', '09', '09 San Juan'),
(275, '02', '02 Áncash', '19', '19 Sihuas', '10', '10 Sicsibamba'),
(276, '02', '02 Áncash', '20', '20 Yungay', '00', ' '),
(277, '02', '02 Áncash', '20', '20 Yungay', '01', '01 Yungay'),
(278, '02', '02 Áncash', '20', '20 Yungay', '02', '02 Cascapara'),
(279, '02', '02 Áncash', '20', '20 Yungay', '03', '03 Mancos'),
(280, '02', '02 Áncash', '20', '20 Yungay', '04', '04 Matacoto'),
(281, '02', '02 Áncash', '20', '20 Yungay', '05', '05 Quillo'),
(282, '02', '02 Áncash', '20', '20 Yungay', '06', '06 Ranrahirca'),
(283, '02', '02 Áncash', '20', '20 Yungay', '07', '07 Shupluy'),
(284, '02', '02 Áncash', '20', '20 Yungay', '08', '08 Yanama'),
(285, '03', '03 Apurímac', '00', ' ', '00', ' '),
(286, '03', '03 Apurímac', '01', '01 Abancay', '00', ' '),
(287, '03', '03 Apurímac', '01', '01 Abancay', '01', '01 Abancay'),
(288, '03', '03 Apurímac', '01', '01 Abancay', '02', '02 Chacoche'),
(289, '03', '03 Apurímac', '01', '01 Abancay', '03', '03 Circa'),
(290, '03', '03 Apurímac', '01', '01 Abancay', '04', '04 Curahuasi'),
(291, '03', '03 Apurímac', '01', '01 Abancay', '05', '05 Huanipaca'),
(292, '03', '03 Apurímac', '01', '01 Abancay', '06', '06 Lambrama'),
(293, '03', '03 Apurímac', '01', '01 Abancay', '07', '07 Pichirhua'),
(294, '03', '03 Apurímac', '01', '01 Abancay', '08', '08 San Pedro de Cachora'),
(295, '03', '03 Apurímac', '01', '01 Abancay', '09', '09 Tamburco'),
(296, '03', '03 Apurímac', '02', '02 Andahuaylas', '00', ' '),
(297, '03', '03 Apurímac', '02', '02 Andahuaylas', '01', '01 Andahuaylas'),
(298, '03', '03 Apurímac', '02', '02 Andahuaylas', '02', '02 Andarapa'),
(299, '03', '03 Apurímac', '02', '02 Andahuaylas', '03', '03 Chiara'),
(300, '03', '03 Apurímac', '02', '02 Andahuaylas', '04', '04 Huancarama'),
(301, '03', '03 Apurímac', '02', '02 Andahuaylas', '05', '05 Huancaray'),
(302, '03', '03 Apurímac', '02', '02 Andahuaylas', '06', '06 Huayana'),
(303, '03', '03 Apurímac', '02', '02 Andahuaylas', '07', '07 Kishuara'),
(304, '03', '03 Apurímac', '02', '02 Andahuaylas', '08', '08 Pacobamba'),
(305, '03', '03 Apurímac', '02', '02 Andahuaylas', '09', '09 Pacucha'),
(306, '03', '03 Apurímac', '02', '02 Andahuaylas', '10', '10 Pampachiri'),
(307, '03', '03 Apurímac', '02', '02 Andahuaylas', '11', '11 Pomacocha'),
(308, '03', '03 Apurímac', '02', '02 Andahuaylas', '12', '12 San Antonio de Cachi'),
(309, '03', '03 Apurímac', '02', '02 Andahuaylas', '13', '13 San Jerónimo'),
(311, '03', '03 Apurímac', '02', '02 Andahuaylas', '14', '14 San Miguel de Chaccrampa'),
(312, '03', '03 Apurímac', '02', '02 Andahuaylas', '15', '15 Santa María de Chicmo'),
(313, '03', '03 Apurímac', '02', '02 Andahuaylas', '16', '16 Talavera'),
(314, '03', '03 Apurímac', '02', '02 Andahuaylas', '17', '17 Tumay Huaraca'),
(315, '03', '03 Apurímac', '02', '02 Andahuaylas', '18', '18 Turpo'),
(316, '03', '03 Apurímac', '02', '02 Andahuaylas', '19', '19 Kaquiabamba'),
(317, '03', '03 Apurímac', '02', '02 Andahuaylas', '20', '20 José María Arguedas'),
(318, '03', '03 Apurímac', '03', '03 Antabamba', '00', ' '),
(319, '03', '03 Apurímac', '03', '03 Antabamba', '01', '01 Antabamba'),
(320, '03', '03 Apurímac', '03', '03 Antabamba', '02', '02 El Oro'),
(321, '03', '03 Apurímac', '03', '03 Antabamba', '03', '03 Huaquirca'),
(322, '03', '03 Apurímac', '03', '03 Antabamba', '04', '04 Juan Espinoza Medrano'),
(323, '03', '03 Apurímac', '03', '03 Antabamba', '05', '05 Oropesa'),
(324, '03', '03 Apurímac', '03', '03 Antabamba', '06', '06 Pachaconas'),
(325, '03', '03 Apurímac', '03', '03 Antabamba', '07', '07 Sabaino'),
(326, '03', '03 Apurímac', '04', '04 Aymaraes', '00', ' '),
(327, '03', '03 Apurímac', '04', '04 Aymaraes', '01', '01 Chalhuanca'),
(328, '03', '03 Apurímac', '04', '04 Aymaraes', '02', '02 Capaya'),
(329, '03', '03 Apurímac', '04', '04 Aymaraes', '03', '03 Caraybamba'),
(330, '03', '03 Apurímac', '04', '04 Aymaraes', '04', '04 Chapimarca'),
(331, '03', '03 Apurímac', '04', '04 Aymaraes', '05', '05 Colcabamba'),
(332, '03', '03 Apurímac', '04', '04 Aymaraes', '06', '06 Cotaruse'),
(333, '03', '03 Apurímac', '04', '04 Aymaraes', '07', '07 Ihuayllo'),
(334, '03', '03 Apurímac', '04', '04 Aymaraes', '08', '08 Justo Apu Sahuaraura'),
(335, '03', '03 Apurímac', '04', '04 Aymaraes', '09', '09 Lucre'),
(336, '03', '03 Apurímac', '04', '04 Aymaraes', '10', '10 Pocohuanca'),
(337, '03', '03 Apurímac', '04', '04 Aymaraes', '11', '11 San Juan de Chacña'),
(338, '03', '03 Apurímac', '04', '04 Aymaraes', '12', '12 Sañayca'),
(339, '03', '03 Apurímac', '04', '04 Aymaraes', '13', '13 Soraya'),
(340, '03', '03 Apurímac', '04', '04 Aymaraes', '14', '14 Tapairihua'),
(341, '03', '03 Apurímac', '04', '04 Aymaraes', '15', '15 Tintay'),
(342, '03', '03 Apurímac', '04', '04 Aymaraes', '16', '16 Toraya'),
(343, '03', '03 Apurímac', '04', '04 Aymaraes', '17', '17 Yanaca'),
(344, '03', '03 Apurímac', '05', '05 Cotabambas', '00', ' '),
(345, '03', '03 Apurímac', '05', '05 Cotabambas', '01', '01 Tambobamba'),
(346, '03', '03 Apurímac', '05', '05 Cotabambas', '02', '02 Cotabambas'),
(347, '03', '03 Apurímac', '05', '05 Cotabambas', '03', '03 Coyllurqui'),
(348, '03', '03 Apurímac', '05', '05 Cotabambas', '04', '04 Haquira'),
(349, '03', '03 Apurímac', '05', '05 Cotabambas', '05', '05 Mara'),
(350, '03', '03 Apurímac', '05', '05 Cotabambas', '06', '06 Challhuahuacho'),
(351, '03', '03 Apurímac', '06', '06 Chincheros', '00', ' '),
(352, '03', '03 Apurímac', '06', '06 Chincheros', '01', '01 Chincheros'),
(353, '03', '03 Apurímac', '06', '06 Chincheros', '02', '02 Anco_Huallo'),
(354, '03', '03 Apurímac', '06', '06 Chincheros', '03', '03 Cocharcas'),
(355, '03', '03 Apurímac', '06', '06 Chincheros', '04', '04 Huaccana'),
(356, '03', '03 Apurímac', '06', '06 Chincheros', '05', '05 Ocobamba'),
(357, '03', '03 Apurímac', '06', '06 Chincheros', '06', '06 Ongoy'),
(358, '03', '03 Apurímac', '06', '06 Chincheros', '07', '07 Uranmarca'),
(359, '03', '03 Apurímac', '06', '06 Chincheros', '08', '08 Ranracancha'),
(360, '03', '03 Apurímac', '06', '06 Chincheros', '09', '09 Rocchacc'),
(361, '03', '03 Apurímac', '06', '06 Chincheros', '10', '10 El Porvenir'),
(363, '03', '03 Apurímac', '06', '06 Chincheros', '11', '11 Los Chankas'),
(364, '03', '03 Apurímac', '07', '07 Grau', '00', ' '),
(365, '03', '03 Apurímac', '07', '07 Grau', '01', '01 Chuquibambilla'),
(366, '03', '03 Apurímac', '07', '07 Grau', '02', '02 Curpahuasi'),
(367, '03', '03 Apurímac', '07', '07 Grau', '03', '03 Gamarra'),
(368, '03', '03 Apurímac', '07', '07 Grau', '04', '04 Huayllati'),
(369, '03', '03 Apurímac', '07', '07 Grau', '05', '05 Mamara'),
(370, '03', '03 Apurímac', '07', '07 Grau', '06', '06 Micaela Bastidas'),
(371, '03', '03 Apurímac', '07', '07 Grau', '07', '07 Pataypampa'),
(372, '03', '03 Apurímac', '07', '07 Grau', '08', '08 Progreso'),
(373, '03', '03 Apurímac', '07', '07 Grau', '09', '09 San Antonio'),
(374, '03', '03 Apurímac', '07', '07 Grau', '10', '10 Santa Rosa'),
(375, '03', '03 Apurímac', '07', '07 Grau', '11', '11 Turpay'),
(376, '03', '03 Apurímac', '07', '07 Grau', '12', '12 Vilcabamba'),
(377, '03', '03 Apurímac', '07', '07 Grau', '13', '13 Virundo'),
(378, '03', '03 Apurímac', '07', '07 Grau', '14', '14 Curasco'),
(379, '04', '04 Arequipa', '00', ' ', '00', ' '),
(380, '04', '04 Arequipa', '01', '01 Arequipa', '00', ' '),
(381, '04', '04 Arequipa', '01', '01 Arequipa', '01', '01 Arequipa'),
(382, '04', '04 Arequipa', '01', '01 Arequipa', '02', '02 Alto Selva Alegre'),
(383, '04', '04 Arequipa', '01', '01 Arequipa', '03', '03 Cayma'),
(384, '04', '04 Arequipa', '01', '01 Arequipa', '04', '04 Cerro Colorado'),
(385, '04', '04 Arequipa', '01', '01 Arequipa', '05', '05 Characato'),
(386, '04', '04 Arequipa', '01', '01 Arequipa', '06', '06 Chiguata'),
(387, '04', '04 Arequipa', '01', '01 Arequipa', '07', '07 Jacobo Hunter'),
(388, '04', '04 Arequipa', '01', '01 Arequipa', '08', '08 La Joya'),
(389, '04', '04 Arequipa', '01', '01 Arequipa', '09', '09 Mariano Melgar'),
(390, '04', '04 Arequipa', '01', '01 Arequipa', '10', '10 Miraflores'),
(391, '04', '04 Arequipa', '01', '01 Arequipa', '11', '11 Mollebaya'),
(392, '04', '04 Arequipa', '01', '01 Arequipa', '12', '12 Paucarpata'),
(393, '04', '04 Arequipa', '01', '01 Arequipa', '13', '13 Pocsi'),
(394, '04', '04 Arequipa', '01', '01 Arequipa', '14', '14 Polobaya'),
(395, '04', '04 Arequipa', '01', '01 Arequipa', '15', '15 Quequeña'),
(396, '04', '04 Arequipa', '01', '01 Arequipa', '16', '16 Sabandia'),
(397, '04', '04 Arequipa', '01', '01 Arequipa', '17', '17 Sachaca'),
(398, '04', '04 Arequipa', '01', '01 Arequipa', '18', '18 San Juan de Siguas'),
(399, '04', '04 Arequipa', '01', '01 Arequipa', '19', '19 San Juan de Tarucani'),
(400, '04', '04 Arequipa', '01', '01 Arequipa', '20', '20 Santa Isabel de Siguas'),
(401, '04', '04 Arequipa', '01', '01 Arequipa', '21', '21 Santa Rita de Siguas'),
(402, '04', '04 Arequipa', '01', '01 Arequipa', '22', '22 Socabaya'),
(403, '04', '04 Arequipa', '01', '01 Arequipa', '23', '23 Tiabaya'),
(404, '04', '04 Arequipa', '01', '01 Arequipa', '24', '24 Uchumayo'),
(405, '04', '04 Arequipa', '01', '01 Arequipa', '25', '25 Vitor'),
(406, '04', '04 Arequipa', '01', '01 Arequipa', '26', '26 Yanahuara'),
(407, '04', '04 Arequipa', '01', '01 Arequipa', '27', '27 Yarabamba'),
(408, '04', '04 Arequipa', '01', '01 Arequipa', '28', '28 Yura'),
(409, '04', '04 Arequipa', '01', '01 Arequipa', '29', '29 José Luis Bustamante Y Rivero'),
(410, '04', '04 Arequipa', '02', '02 Camaná', '00', ' '),
(411, '04', '04 Arequipa', '02', '02 Camaná', '01', '01 Camaná'),
(412, '04', '04 Arequipa', '02', '02 Camaná', '02', '02 José María Quimper'),
(413, '04', '04 Arequipa', '02', '02 Camaná', '03', '03 Mariano Nicolás Valcárcel'),
(415, '04', '04 Arequipa', '02', '02 Camaná', '04', '04 Mariscal Cáceres'),
(416, '04', '04 Arequipa', '02', '02 Camaná', '05', '05 Nicolás de Pierola'),
(417, '04', '04 Arequipa', '02', '02 Camaná', '06', '06 Ocoña'),
(418, '04', '04 Arequipa', '02', '02 Camaná', '07', '07 Quilca'),
(419, '04', '04 Arequipa', '02', '02 Camaná', '08', '08 Samuel Pastor'),
(420, '04', '04 Arequipa', '03', '03 Caravelí', '00', ' '),
(421, '04', '04 Arequipa', '03', '03 Caravelí', '01', '01 Caravelí'),
(422, '04', '04 Arequipa', '03', '03 Caravelí', '02', '02 Acarí'),
(423, '04', '04 Arequipa', '03', '03 Caravelí', '03', '03 Atico'),
(424, '04', '04 Arequipa', '03', '03 Caravelí', '04', '04 Atiquipa'),
(425, '04', '04 Arequipa', '03', '03 Caravelí', '05', '05 Bella Unión'),
(426, '04', '04 Arequipa', '03', '03 Caravelí', '06', '06 Cahuacho'),
(427, '04', '04 Arequipa', '03', '03 Caravelí', '07', '07 Chala'),
(428, '04', '04 Arequipa', '03', '03 Caravelí', '08', '08 Chaparra'),
(429, '04', '04 Arequipa', '03', '03 Caravelí', '09', '09 Huanuhuanu'),
(430, '04', '04 Arequipa', '03', '03 Caravelí', '10', '10 Jaqui'),
(431, '04', '04 Arequipa', '03', '03 Caravelí', '11', '11 Lomas'),
(432, '04', '04 Arequipa', '03', '03 Caravelí', '12', '12 Quicacha'),
(433, '04', '04 Arequipa', '03', '03 Caravelí', '13', '13 Yauca'),
(434, '04', '04 Arequipa', '04', '04 Castilla', '00', ' '),
(435, '04', '04 Arequipa', '04', '04 Castilla', '01', '01 Aplao'),
(436, '04', '04 Arequipa', '04', '04 Castilla', '02', '02 Andagua'),
(437, '04', '04 Arequipa', '04', '04 Castilla', '03', '03 Ayo'),
(438, '04', '04 Arequipa', '04', '04 Castilla', '04', '04 Chachas'),
(439, '04', '04 Arequipa', '04', '04 Castilla', '05', '05 Chilcaymarca'),
(440, '04', '04 Arequipa', '04', '04 Castilla', '06', '06 Choco'),
(441, '04', '04 Arequipa', '04', '04 Castilla', '07', '07 Huancarqui'),
(442, '04', '04 Arequipa', '04', '04 Castilla', '08', '08 Machaguay'),
(443, '04', '04 Arequipa', '04', '04 Castilla', '09', '09 Orcopampa'),
(444, '04', '04 Arequipa', '04', '04 Castilla', '10', '10 Pampacolca'),
(445, '04', '04 Arequipa', '04', '04 Castilla', '11', '11 Tipan'),
(446, '04', '04 Arequipa', '04', '04 Castilla', '12', '12 Uñon'),
(447, '04', '04 Arequipa', '04', '04 Castilla', '13', '13 Uraca'),
(448, '04', '04 Arequipa', '04', '04 Castilla', '14', '14 Viraco'),
(449, '04', '04 Arequipa', '05', '05 Caylloma', '00', ' '),
(450, '04', '04 Arequipa', '05', '05 Caylloma', '01', '01 Chivay'),
(451, '04', '04 Arequipa', '05', '05 Caylloma', '02', '02 Achoma'),
(452, '04', '04 Arequipa', '05', '05 Caylloma', '03', '03 Cabanaconde'),
(453, '04', '04 Arequipa', '05', '05 Caylloma', '04', '04 Callalli'),
(454, '04', '04 Arequipa', '05', '05 Caylloma', '05', '05 Caylloma'),
(455, '04', '04 Arequipa', '05', '05 Caylloma', '06', '06 Coporaque'),
(456, '04', '04 Arequipa', '05', '05 Caylloma', '07', '07 Huambo'),
(457, '04', '04 Arequipa', '05', '05 Caylloma', '08', '08 Huanca'),
(458, '04', '04 Arequipa', '05', '05 Caylloma', '09', '09 Ichupampa'),
(459, '04', '04 Arequipa', '05', '05 Caylloma', '10', '10 Lari'),
(460, '04', '04 Arequipa', '05', '05 Caylloma', '11', '11 Lluta'),
(461, '04', '04 Arequipa', '05', '05 Caylloma', '12', '12 Maca'),
(462, '04', '04 Arequipa', '05', '05 Caylloma', '13', '13 Madrigal'),
(463, '04', '04 Arequipa', '05', '05 Caylloma', '14', '14 San Antonio de Chuca'),
(464, '04', '04 Arequipa', '05', '05 Caylloma', '15', '15 Sibayo'),
(465, '04', '04 Arequipa', '05', '05 Caylloma', '16', '16 Tapay'),
(467, '04', '04 Arequipa', '05', '05 Caylloma', '17', '17 Tisco'),
(468, '04', '04 Arequipa', '05', '05 Caylloma', '18', '18 Tuti'),
(469, '04', '04 Arequipa', '05', '05 Caylloma', '19', '19 Yanque'),
(470, '04', '04 Arequipa', '05', '05 Caylloma', '20', '20 Majes'),
(471, '04', '04 Arequipa', '06', '06 Condesuyos', '00', ' '),
(472, '04', '04 Arequipa', '06', '06 Condesuyos', '01', '01 Chuquibamba'),
(473, '04', '04 Arequipa', '06', '06 Condesuyos', '02', '02 Andaray'),
(474, '04', '04 Arequipa', '06', '06 Condesuyos', '03', '03 Cayarani'),
(475, '04', '04 Arequipa', '06', '06 Condesuyos', '04', '04 Chichas'),
(476, '04', '04 Arequipa', '06', '06 Condesuyos', '05', '05 Iray'),
(477, '04', '04 Arequipa', '06', '06 Condesuyos', '06', '06 Río Grande'),
(478, '04', '04 Arequipa', '06', '06 Condesuyos', '07', '07 Salamanca'),
(479, '04', '04 Arequipa', '06', '06 Condesuyos', '08', '08 Yanaquihua'),
(480, '04', '04 Arequipa', '07', '07 Islay', '00', ' '),
(481, '04', '04 Arequipa', '07', '07 Islay', '01', '01 Mollendo'),
(482, '04', '04 Arequipa', '07', '07 Islay', '02', '02 Cocachacra'),
(483, '04', '04 Arequipa', '07', '07 Islay', '03', '03 Dean Valdivia'),
(484, '04', '04 Arequipa', '07', '07 Islay', '04', '04 Islay'),
(485, '04', '04 Arequipa', '07', '07 Islay', '05', '05 Mejia'),
(486, '04', '04 Arequipa', '07', '07 Islay', '06', '06 Punta de Bombón'),
(487, '04', '04 Arequipa', '08', '08 La Uniòn', '00', ' '),
(488, '04', '04 Arequipa', '08', '08 La Uniòn', '01', '01 Cotahuasi'),
(489, '04', '04 Arequipa', '08', '08 La Uniòn', '02', '02 Alca'),
(490, '04', '04 Arequipa', '08', '08 La Uniòn', '03', '03 Charcana'),
(491, '04', '04 Arequipa', '08', '08 La Uniòn', '04', '04 Huaynacotas'),
(492, '04', '04 Arequipa', '08', '08 La Uniòn', '05', '05 Pampamarca'),
(493, '04', '04 Arequipa', '08', '08 La Uniòn', '06', '06 Puyca'),
(494, '04', '04 Arequipa', '08', '08 La Uniòn', '07', '07 Quechualla'),
(495, '04', '04 Arequipa', '08', '08 La Uniòn', '08', '08 Sayla'),
(496, '04', '04 Arequipa', '08', '08 La Uniòn', '09', '09 Tauria'),
(497, '04', '04 Arequipa', '08', '08 La Uniòn', '10', '10 Tomepampa'),
(498, '04', '04 Arequipa', '08', '08 La Uniòn', '11', '11 Toro'),
(499, '05', '05 Ayacucho', '00', ' ', '00', ' '),
(500, '05', '05 Ayacucho', '01', '01 Huamanga', '00', ' '),
(501, '05', '05 Ayacucho', '01', '01 Huamanga', '01', '01 Ayacucho'),
(502, '05', '05 Ayacucho', '01', '01 Huamanga', '02', '02 Acocro'),
(503, '05', '05 Ayacucho', '01', '01 Huamanga', '03', '03 Acos Vinchos'),
(504, '05', '05 Ayacucho', '01', '01 Huamanga', '04', '04 Carmen Alto'),
(505, '05', '05 Ayacucho', '01', '01 Huamanga', '05', '05 Chiara'),
(506, '05', '05 Ayacucho', '01', '01 Huamanga', '06', '06 Ocros'),
(507, '05', '05 Ayacucho', '01', '01 Huamanga', '07', '07 Pacaycasa'),
(508, '05', '05 Ayacucho', '01', '01 Huamanga', '08', '08 Quinua'),
(509, '05', '05 Ayacucho', '01', '01 Huamanga', '09', '09 San José de Ticllas'),
(510, '05', '05 Ayacucho', '01', '01 Huamanga', '10', '10 San Juan Bautista'),
(511, '05', '05 Ayacucho', '01', '01 Huamanga', '11', '11 Santiago de Pischa'),
(512, '05', '05 Ayacucho', '01', '01 Huamanga', '12', '12 Socos'),
(513, '05', '05 Ayacucho', '01', '01 Huamanga', '13', '13 Tambillo'),
(514, '05', '05 Ayacucho', '01', '01 Huamanga', '14', '14 Vinchos'),
(515, '05', '05 Ayacucho', '01', '01 Huamanga', '15', '15 Jesús Nazareno'),
(516, '05', '05 Ayacucho', '01', '01 Huamanga', '16', '16 Andrés Avelino Cáceres Dorregaray'),
(517, '05', '05 Ayacucho', '02', '02 Cangallo', '00', ' '),
(519, '05', '05 Ayacucho', '02', '02 Cangallo', '01', '01 Cangallo'),
(520, '05', '05 Ayacucho', '02', '02 Cangallo', '02', '02 Chuschi'),
(521, '05', '05 Ayacucho', '02', '02 Cangallo', '03', '03 Los Morochucos'),
(522, '05', '05 Ayacucho', '02', '02 Cangallo', '04', '04 María Parado de Bellido'),
(523, '05', '05 Ayacucho', '02', '02 Cangallo', '05', '05 Paras'),
(524, '05', '05 Ayacucho', '02', '02 Cangallo', '06', '06 Totos'),
(525, '05', '05 Ayacucho', '03', '03 Huanca Sancos', '00', ' '),
(526, '05', '05 Ayacucho', '03', '03 Huanca Sancos', '01', '01 Sancos'),
(527, '05', '05 Ayacucho', '03', '03 Huanca Sancos', '02', '02 Carapo'),
(528, '05', '05 Ayacucho', '03', '03 Huanca Sancos', '03', '03 Sacsamarca'),
(529, '05', '05 Ayacucho', '03', '03 Huanca Sancos', '04', '04 Santiago de Lucanamarca'),
(530, '05', '05 Ayacucho', '04', '04 Huanta', '00', ' '),
(531, '05', '05 Ayacucho', '04', '04 Huanta', '01', '01 Huanta'),
(532, '05', '05 Ayacucho', '04', '04 Huanta', '02', '02 Ayahuanco'),
(533, '05', '05 Ayacucho', '04', '04 Huanta', '03', '03 Huamanguilla'),
(534, '05', '05 Ayacucho', '04', '04 Huanta', '04', '04 Iguain'),
(535, '05', '05 Ayacucho', '04', '04 Huanta', '05', '05 Luricocha'),
(536, '05', '05 Ayacucho', '04', '04 Huanta', '06', '06 Santillana'),
(537, '05', '05 Ayacucho', '04', '04 Huanta', '07', '07 Sivia'),
(538, '05', '05 Ayacucho', '04', '04 Huanta', '08', '08 Llochegua'),
(539, '05', '05 Ayacucho', '04', '04 Huanta', '09', '09 Canayre'),
(540, '05', '05 Ayacucho', '04', '04 Huanta', '10', '10 Uchuraccay'),
(541, '05', '05 Ayacucho', '04', '04 Huanta', '11', '11 Pucacolpa'),
(542, '05', '05 Ayacucho', '04', '04 Huanta', '12', '12 Chaca'),
(543, '05', '05 Ayacucho', '05', '05 La Mar', '00', ' '),
(544, '05', '05 Ayacucho', '05', '05 La Mar', '01', '01 San Miguel'),
(545, '05', '05 Ayacucho', '05', '05 La Mar', '02', '02 Anco'),
(546, '05', '05 Ayacucho', '05', '05 La Mar', '03', '03 Ayna'),
(547, '05', '05 Ayacucho', '05', '05 La Mar', '04', '04 Chilcas'),
(548, '05', '05 Ayacucho', '05', '05 La Mar', '05', '05 Chungui'),
(549, '05', '05 Ayacucho', '05', '05 La Mar', '06', '06 Luis Carranza'),
(550, '05', '05 Ayacucho', '05', '05 La Mar', '07', '07 Santa Rosa'),
(551, '05', '05 Ayacucho', '05', '05 La Mar', '08', '08 Tambo'),
(552, '05', '05 Ayacucho', '05', '05 La Mar', '09', '09 Samugari'),
(553, '05', '05 Ayacucho', '05', '05 La Mar', '10', '10 Anchihuay'),
(554, '05', '05 Ayacucho', '05', '05 La Mar', '11', '11 Oronccoy'),
(555, '05', '05 Ayacucho', '06', '06 Lucanas', '00', ' '),
(556, '05', '05 Ayacucho', '06', '06 Lucanas', '01', '01 Puquio'),
(557, '05', '05 Ayacucho', '06', '06 Lucanas', '02', '02 Aucara'),
(558, '05', '05 Ayacucho', '06', '06 Lucanas', '03', '03 Cabana'),
(559, '05', '05 Ayacucho', '06', '06 Lucanas', '04', '04 Carmen Salcedo'),
(560, '05', '05 Ayacucho', '06', '06 Lucanas', '05', '05 Chaviña'),
(561, '05', '05 Ayacucho', '06', '06 Lucanas', '06', '06 Chipao'),
(562, '05', '05 Ayacucho', '06', '06 Lucanas', '07', '07 Huac-Huas'),
(563, '05', '05 Ayacucho', '06', '06 Lucanas', '08', '08 Laramate'),
(564, '05', '05 Ayacucho', '06', '06 Lucanas', '09', '09 Leoncio Prado'),
(565, '05', '05 Ayacucho', '06', '06 Lucanas', '10', '10 Llauta'),
(566, '05', '05 Ayacucho', '06', '06 Lucanas', '11', '11 Lucanas'),
(567, '05', '05 Ayacucho', '06', '06 Lucanas', '12', '12 Ocaña'),
(568, '05', '05 Ayacucho', '06', '06 Lucanas', '13', '13 Otoca'),
(569, '05', '05 Ayacucho', '06', '06 Lucanas', '14', '14 Saisa'),
(571, '05', '05 Ayacucho', '06', '06 Lucanas', '15', '15 San Cristóbal'),
(572, '05', '05 Ayacucho', '06', '06 Lucanas', '16', '16 San Juan'),
(573, '05', '05 Ayacucho', '06', '06 Lucanas', '17', '17 San Pedro'),
(574, '05', '05 Ayacucho', '06', '06 Lucanas', '18', '18 San Pedro de Palco'),
(575, '05', '05 Ayacucho', '06', '06 Lucanas', '19', '19 Sancos'),
(576, '05', '05 Ayacucho', '06', '06 Lucanas', '20', '20 Santa Ana de Huaycahuacho'),
(577, '05', '05 Ayacucho', '06', '06 Lucanas', '21', '21 Santa Lucia'),
(578, '05', '05 Ayacucho', '07', '07 Parinacochas', '00', ' '),
(579, '05', '05 Ayacucho', '07', '07 Parinacochas', '01', '01 Coracora'),
(580, '05', '05 Ayacucho', '07', '07 Parinacochas', '02', '02 Chumpi'),
(581, '05', '05 Ayacucho', '07', '07 Parinacochas', '03', '03 Coronel Castañeda'),
(582, '05', '05 Ayacucho', '07', '07 Parinacochas', '04', '04 Pacapausa'),
(583, '05', '05 Ayacucho', '07', '07 Parinacochas', '05', '05 Pullo'),
(584, '05', '05 Ayacucho', '07', '07 Parinacochas', '06', '06 Puyusca'),
(585, '05', '05 Ayacucho', '07', '07 Parinacochas', '07', '07 San Francisco de Ravacayco'),
(586, '05', '05 Ayacucho', '07', '07 Parinacochas', '08', '08 Upahuacho'),
(587, '05', '05 Ayacucho', '08', '08 Pàucar del Sara Sara', '00', ' '),
(588, '05', '05 Ayacucho', '08', '08 Pàucar del Sara Sara', '01', '01 Pausa'),
(589, '05', '05 Ayacucho', '08', '08 Pàucar del Sara Sara', '02', '02 Colta'),
(590, '05', '05 Ayacucho', '08', '08 Pàucar del Sara Sara', '03', '03 Corculla'),
(591, '05', '05 Ayacucho', '08', '08 Pàucar del Sara Sara', '04', '04 Lampa'),
(592, '05', '05 Ayacucho', '08', '08 Pàucar del Sara Sara', '05', '05 Marcabamba'),
(593, '05', '05 Ayacucho', '08', '08 Pàucar del Sara Sara', '06', '06 Oyolo'),
(594, '05', '05 Ayacucho', '08', '08 Pàucar del Sara Sara', '07', '07 Pararca'),
(595, '05', '05 Ayacucho', '08', '08 Pàucar del Sara Sara', '08', '08 San Javier de Alpabamba'),
(596, '05', '05 Ayacucho', '08', '08 Pàucar del Sara Sara', '09', '09 San José de Ushua'),
(597, '05', '05 Ayacucho', '08', '08 Pàucar del Sara Sara', '10', '10 Sara Sara'),
(598, '05', '05 Ayacucho', '09', '09 Sucre', '00', ' '),
(599, '05', '05 Ayacucho', '09', '09 Sucre', '01', '01 Querobamba'),
(600, '05', '05 Ayacucho', '09', '09 Sucre', '02', '02 Belén'),
(601, '05', '05 Ayacucho', '09', '09 Sucre', '03', '03 Chalcos'),
(602, '05', '05 Ayacucho', '09', '09 Sucre', '04', '04 Chilcayoc'),
(603, '05', '05 Ayacucho', '09', '09 Sucre', '05', '05 Huacaña'),
(604, '05', '05 Ayacucho', '09', '09 Sucre', '06', '06 Morcolla'),
(605, '05', '05 Ayacucho', '09', '09 Sucre', '07', '07 Paico'),
(606, '05', '05 Ayacucho', '09', '09 Sucre', '08', '08 San Pedro de Larcay'),
(607, '05', '05 Ayacucho', '09', '09 Sucre', '09', '09 San Salvador de Quije'),
(608, '05', '05 Ayacucho', '09', '09 Sucre', '10', '10 Santiago de Paucaray'),
(609, '05', '05 Ayacucho', '09', '09 Sucre', '11', '11 Soras'),
(610, '05', '05 Ayacucho', '10', '10 Víctor Fajardo', '00', ' '),
(611, '05', '05 Ayacucho', '10', '10 Víctor Fajardo', '01', '01 Huancapi'),
(612, '05', '05 Ayacucho', '10', '10 Víctor Fajardo', '02', '02 Alcamenca'),
(613, '05', '05 Ayacucho', '10', '10 Víctor Fajardo', '03', '03 Apongo'),
(614, '05', '05 Ayacucho', '10', '10 Víctor Fajardo', '04', '04 Asquipata'),
(615, '05', '05 Ayacucho', '10', '10 Víctor Fajardo', '05', '05 Canaria'),
(616, '05', '05 Ayacucho', '10', '10 Víctor Fajardo', '06', '06 Cayara'),
(617, '05', '05 Ayacucho', '10', '10 Víctor Fajardo', '07', '07 Colca'),
(618, '05', '05 Ayacucho', '10', '10 Víctor Fajardo', '08', '08 Huamanquiquia'),
(619, '05', '05 Ayacucho', '10', '10 Víctor Fajardo', '09', '09 Huancaraylla'),
(620, '05', '05 Ayacucho', '10', '10 Víctor Fajardo', '10', '10 Hualla'),
(621, '05', '05 Ayacucho', '10', '10 Víctor Fajardo', '11', '11 Sarhua'),
(623, '05', '05 Ayacucho', '10', '10 Víctor Fajardo', '12', '12 Vilcanchos'),
(624, '05', '05 Ayacucho', '11', '11 Vilcas Huamán', '00', ' '),
(625, '05', '05 Ayacucho', '11', '11 Vilcas Huamán', '01', '01 Vilcas Huaman'),
(626, '05', '05 Ayacucho', '11', '11 Vilcas Huamán', '02', '02 Accomarca'),
(627, '05', '05 Ayacucho', '11', '11 Vilcas Huamán', '03', '03 Carhuanca'),
(628, '05', '05 Ayacucho', '11', '11 Vilcas Huamán', '04', '04 Concepción'),
(629, '05', '05 Ayacucho', '11', '11 Vilcas Huamán', '05', '05 Huambalpa'),
(630, '05', '05 Ayacucho', '11', '11 Vilcas Huamán', '06', '06 Independencia'),
(631, '05', '05 Ayacucho', '11', '11 Vilcas Huamán', '07', '07 Saurama'),
(632, '05', '05 Ayacucho', '11', '11 Vilcas Huamán', '08', '08 Vischongo'),
(633, '06', '06 Cajamarca', '00', ' ', '00', ' '),
(634, '06', '06 Cajamarca', '01', '01 Cajamarca', '00', ' '),
(635, '06', '06 Cajamarca', '01', '01 Cajamarca', '01', '01 Cajamarca'),
(636, '06', '06 Cajamarca', '01', '01 Cajamarca', '02', '02 Asunción'),
(637, '06', '06 Cajamarca', '01', '01 Cajamarca', '03', '03 Chetilla'),
(638, '06', '06 Cajamarca', '01', '01 Cajamarca', '04', '04 Cospan'),
(639, '06', '06 Cajamarca', '01', '01 Cajamarca', '05', '05 Encañada'),
(640, '06', '06 Cajamarca', '01', '01 Cajamarca', '06', '06 Jesús'),
(641, '06', '06 Cajamarca', '01', '01 Cajamarca', '07', '07 Llacanora'),
(642, '06', '06 Cajamarca', '01', '01 Cajamarca', '08', '08 Los Baños del Inca'),
(643, '06', '06 Cajamarca', '01', '01 Cajamarca', '09', '09 Magdalena'),
(644, '06', '06 Cajamarca', '01', '01 Cajamarca', '10', '10 Matara'),
(645, '06', '06 Cajamarca', '01', '01 Cajamarca', '11', '11 Namora'),
(646, '06', '06 Cajamarca', '01', '01 Cajamarca', '12', '12 San Juan'),
(647, '06', '06 Cajamarca', '02', '02 Cajabamba', '00', ' '),
(648, '06', '06 Cajamarca', '02', '02 Cajabamba', '01', '01 Cajabamba'),
(649, '06', '06 Cajamarca', '02', '02 Cajabamba', '02', '02 Cachachi'),
(650, '06', '06 Cajamarca', '02', '02 Cajabamba', '03', '03 Condebamba'),
(651, '06', '06 Cajamarca', '02', '02 Cajabamba', '04', '04 Sitacocha'),
(652, '06', '06 Cajamarca', '03', '03 Celendín', '00', ' '),
(653, '06', '06 Cajamarca', '03', '03 Celendín', '01', '01 Celendín'),
(654, '06', '06 Cajamarca', '03', '03 Celendín', '02', '02 Chumuch'),
(655, '06', '06 Cajamarca', '03', '03 Celendín', '03', '03 Cortegana'),
(656, '06', '06 Cajamarca', '03', '03 Celendín', '04', '04 Huasmin'),
(657, '06', '06 Cajamarca', '03', '03 Celendín', '05', '05 Jorge Chávez'),
(658, '06', '06 Cajamarca', '03', '03 Celendín', '06', '06 José Gálvez'),
(659, '06', '06 Cajamarca', '03', '03 Celendín', '07', '07 Miguel Iglesias'),
(660, '06', '06 Cajamarca', '03', '03 Celendín', '08', '08 Oxamarca'),
(661, '06', '06 Cajamarca', '03', '03 Celendín', '09', '09 Sorochuco'),
(662, '06', '06 Cajamarca', '03', '03 Celendín', '10', '10 Sucre'),
(663, '06', '06 Cajamarca', '03', '03 Celendín', '11', '11 Utco'),
(664, '06', '06 Cajamarca', '03', '03 Celendín', '12', '12 La Libertad de Pallan'),
(665, '06', '06 Cajamarca', '04', '04 Chota', '00', ' '),
(666, '06', '06 Cajamarca', '04', '04 Chota', '01', '01 Chota'),
(667, '06', '06 Cajamarca', '04', '04 Chota', '02', '02 Anguia'),
(668, '06', '06 Cajamarca', '04', '04 Chota', '03', '03 Chadin'),
(669, '06', '06 Cajamarca', '04', '04 Chota', '04', '04 Chiguirip'),
(670, '06', '06 Cajamarca', '04', '04 Chota', '05', '05 Chimban'),
(671, '06', '06 Cajamarca', '04', '04 Chota', '06', '06 Choropampa'),
(672, '06', '06 Cajamarca', '04', '04 Chota', '07', '07 Cochabamba'),
(673, '06', '06 Cajamarca', '04', '04 Chota', '08', '08 Conchan'),
(675, '06', '06 Cajamarca', '04', '04 Chota', '09', '09 Huambos'),
(676, '06', '06 Cajamarca', '04', '04 Chota', '10', '10 Lajas'),
(677, '06', '06 Cajamarca', '04', '04 Chota', '11', '11 Llama'),
(678, '06', '06 Cajamarca', '04', '04 Chota', '12', '12 Miracosta'),
(679, '06', '06 Cajamarca', '04', '04 Chota', '13', '13 Paccha'),
(680, '06', '06 Cajamarca', '04', '04 Chota', '14', '14 Pion'),
(681, '06', '06 Cajamarca', '04', '04 Chota', '15', '15 Querocoto'),
(682, '06', '06 Cajamarca', '04', '04 Chota', '16', '16 San Juan de Licupis'),
(683, '06', '06 Cajamarca', '04', '04 Chota', '17', '17 Tacabamba'),
(684, '06', '06 Cajamarca', '04', '04 Chota', '18', '18 Tocmoche'),
(685, '06', '06 Cajamarca', '04', '04 Chota', '19', '19 Chalamarca'),
(686, '06', '06 Cajamarca', '05', '05 Contumazá', '00', ' '),
(687, '06', '06 Cajamarca', '05', '05 Contumazá', '01', '01 Contumaza'),
(688, '06', '06 Cajamarca', '05', '05 Contumazá', '02', '02 Chilete'),
(689, '06', '06 Cajamarca', '05', '05 Contumazá', '03', '03 Cupisnique'),
(690, '06', '06 Cajamarca', '05', '05 Contumazá', '04', '04 Guzmango'),
(691, '06', '06 Cajamarca', '05', '05 Contumazá', '05', '05 San Benito'),
(692, '06', '06 Cajamarca', '05', '05 Contumazá', '06', '06 Santa Cruz de Toledo'),
(693, '06', '06 Cajamarca', '05', '05 Contumazá', '07', '07 Tantarica'),
(694, '06', '06 Cajamarca', '05', '05 Contumazá', '08', '08 Yonan'),
(695, '06', '06 Cajamarca', '06', '06 Cutervo', '00', ' '),
(696, '06', '06 Cajamarca', '06', '06 Cutervo', '01', '01 Cutervo'),
(697, '06', '06 Cajamarca', '06', '06 Cutervo', '02', '02 Callayuc'),
(698, '06', '06 Cajamarca', '06', '06 Cutervo', '03', '03 Choros'),
(699, '06', '06 Cajamarca', '06', '06 Cutervo', '04', '04 Cujillo'),
(700, '06', '06 Cajamarca', '06', '06 Cutervo', '05', '05 La Ramada'),
(701, '06', '06 Cajamarca', '06', '06 Cutervo', '06', '06 Pimpingos'),
(702, '06', '06 Cajamarca', '06', '06 Cutervo', '07', '07 Querocotillo'),
(703, '06', '06 Cajamarca', '06', '06 Cutervo', '08', '08 San Andrés de Cutervo'),
(704, '06', '06 Cajamarca', '06', '06 Cutervo', '09', '09 San Juan de Cutervo'),
(705, '06', '06 Cajamarca', '06', '06 Cutervo', '10', '10 San Luis de Lucma'),
(706, '06', '06 Cajamarca', '06', '06 Cutervo', '11', '11 Santa Cruz'),
(707, '06', '06 Cajamarca', '06', '06 Cutervo', '12', '12 Santo Domingo de la Capilla'),
(708, '06', '06 Cajamarca', '06', '06 Cutervo', '13', '13 Santo Tomas'),
(709, '06', '06 Cajamarca', '06', '06 Cutervo', '14', '14 Socota'),
(710, '06', '06 Cajamarca', '06', '06 Cutervo', '15', '15 Toribio Casanova'),
(711, '06', '06 Cajamarca', '07', '07 Hualgayoc', '00', ' '),
(712, '06', '06 Cajamarca', '07', '07 Hualgayoc', '01', '01 Bambamarca'),
(713, '06', '06 Cajamarca', '07', '07 Hualgayoc', '02', '02 Chugur'),
(714, '06', '06 Cajamarca', '07', '07 Hualgayoc', '03', '03 Hualgayoc'),
(715, '06', '06 Cajamarca', '08', '08 Jaén', '00', ' '),
(716, '06', '06 Cajamarca', '08', '08 Jaén', '01', '01 Jaén'),
(717, '06', '06 Cajamarca', '08', '08 Jaén', '02', '02 Bellavista'),
(718, '06', '06 Cajamarca', '08', '08 Jaén', '03', '03 Chontali'),
(719, '06', '06 Cajamarca', '08', '08 Jaén', '04', '04 Colasay'),
(720, '06', '06 Cajamarca', '08', '08 Jaén', '05', '05 Huabal'),
(721, '06', '06 Cajamarca', '08', '08 Jaén', '06', '06 Las Pirias'),
(722, '06', '06 Cajamarca', '08', '08 Jaén', '07', '07 Pomahuaca'),
(723, '06', '06 Cajamarca', '08', '08 Jaén', '08', '08 Pucara'),
(724, '06', '06 Cajamarca', '08', '08 Jaén', '09', '09 Sallique'),
(725, '06', '06 Cajamarca', '08', '08 Jaén', '10', '10 San Felipe'),
(727, '06', '06 Cajamarca', '08', '08 Jaén', '11', '11 San José del Alto'),
(728, '06', '06 Cajamarca', '08', '08 Jaén', '12', '12 Santa Rosa'),
(729, '06', '06 Cajamarca', '09', '09 San Ignacio', '00', ' '),
(730, '06', '06 Cajamarca', '09', '09 San Ignacio', '01', '01 San Ignacio'),
(731, '06', '06 Cajamarca', '09', '09 San Ignacio', '02', '02 Chirinos'),
(732, '06', '06 Cajamarca', '09', '09 San Ignacio', '03', '03 Huarango'),
(733, '06', '06 Cajamarca', '09', '09 San Ignacio', '04', '04 La Coipa'),
(734, '06', '06 Cajamarca', '09', '09 San Ignacio', '05', '05 Namballe'),
(735, '06', '06 Cajamarca', '09', '09 San Ignacio', '06', '06 San José de Lourdes'),
(736, '06', '06 Cajamarca', '09', '09 San Ignacio', '07', '07 Tabaconas'),
(737, '06', '06 Cajamarca', '10', '10 San Marcos', '00', ' '),
(738, '06', '06 Cajamarca', '10', '10 San Marcos', '01', '01 Pedro Gálvez'),
(739, '06', '06 Cajamarca', '10', '10 San Marcos', '02', '02 Chancay'),
(740, '06', '06 Cajamarca', '10', '10 San Marcos', '03', '03 Eduardo Villanueva'),
(741, '06', '06 Cajamarca', '10', '10 San Marcos', '04', '04 Gregorio Pita'),
(742, '06', '06 Cajamarca', '10', '10 San Marcos', '05', '05 Ichocan'),
(743, '06', '06 Cajamarca', '10', '10 San Marcos', '06', '06 José Manuel Quiroz'),
(744, '06', '06 Cajamarca', '10', '10 San Marcos', '07', '07 José Sabogal'),
(745, '06', '06 Cajamarca', '11', '11 San Miguel', '00', ' '),
(746, '06', '06 Cajamarca', '11', '11 San Miguel', '01', '01 San Miguel'),
(747, '06', '06 Cajamarca', '11', '11 San Miguel', '02', '02 Bolívar'),
(748, '06', '06 Cajamarca', '11', '11 San Miguel', '03', '03 Calquis'),
(749, '06', '06 Cajamarca', '11', '11 San Miguel', '04', '04 Catilluc');
INSERT INTO `ubigeo2016` (`id`, `codigo_departamento`, `departamento`, `codigo_provincia`, `provincia`, `codigo_distrito`, `distrito`) VALUES
(750, '06', '06 Cajamarca', '11', '11 San Miguel', '05', '05 El Prado'),
(751, '06', '06 Cajamarca', '11', '11 San Miguel', '06', '06 La Florida'),
(752, '06', '06 Cajamarca', '11', '11 San Miguel', '07', '07 Llapa'),
(753, '06', '06 Cajamarca', '11', '11 San Miguel', '08', '08 Nanchoc'),
(754, '06', '06 Cajamarca', '11', '11 San Miguel', '09', '09 Niepos'),
(755, '06', '06 Cajamarca', '11', '11 San Miguel', '10', '10 San Gregorio'),
(756, '06', '06 Cajamarca', '11', '11 San Miguel', '11', '11 San Silvestre de Cochan'),
(757, '06', '06 Cajamarca', '11', '11 San Miguel', '12', '12 Tongod'),
(758, '06', '06 Cajamarca', '11', '11 San Miguel', '13', '13 Unión Agua Blanca'),
(759, '06', '06 Cajamarca', '12', '12 San Pablo', '00', ' '),
(760, '06', '06 Cajamarca', '12', '12 San Pablo', '01', '01 San Pablo'),
(761, '06', '06 Cajamarca', '12', '12 San Pablo', '02', '02 San Bernardino'),
(762, '06', '06 Cajamarca', '12', '12 San Pablo', '03', '03 San Luis'),
(763, '06', '06 Cajamarca', '12', '12 San Pablo', '04', '04 Tumbaden'),
(764, '06', '06 Cajamarca', '13', '13 Santa Cruz', '00', ' '),
(765, '06', '06 Cajamarca', '13', '13 Santa Cruz', '01', '01 Santa Cruz'),
(766, '06', '06 Cajamarca', '13', '13 Santa Cruz', '02', '02 Andabamba'),
(767, '06', '06 Cajamarca', '13', '13 Santa Cruz', '03', '03 Catache'),
(768, '06', '06 Cajamarca', '13', '13 Santa Cruz', '04', '04 Chancaybaños'),
(769, '06', '06 Cajamarca', '13', '13 Santa Cruz', '05', '05 La Esperanza'),
(770, '06', '06 Cajamarca', '13', '13 Santa Cruz', '06', '06 Ninabamba'),
(771, '06', '06 Cajamarca', '13', '13 Santa Cruz', '07', '07 Pulan'),
(772, '06', '06 Cajamarca', '13', '13 Santa Cruz', '08', '08 Saucepampa'),
(773, '06', '06 Cajamarca', '13', '13 Santa Cruz', '09', '09 Sexi'),
(774, '06', '06 Cajamarca', '13', '13 Santa Cruz', '10', '10 Uticyacu'),
(775, '06', '06 Cajamarca', '13', '13 Santa Cruz', '11', '11 Yauyucan'),
(776, '07', '07 Callao', '00', ' ', '00', ' '),
(777, '07', '07 Callao', '01', '01 Prov. Const. del Callao', '00', ' '),
(779, '07', '07 Callao', '01', '01 Prov. Const. del Callao', '01', '01 Callao'),
(780, '07', '07 Callao', '01', '01 Prov. Const. del Callao', '02', '02 Bellavista'),
(781, '07', '07 Callao', '01', '01 Prov. Const. del Callao', '03', '03 Carmen de la Legua Reynoso'),
(782, '07', '07 Callao', '01', '01 Prov. Const. del Callao', '04', '04 La Perla'),
(783, '07', '07 Callao', '01', '01 Prov. Const. del Callao', '05', '05 La Punta'),
(784, '07', '07 Callao', '01', '01 Prov. Const. del Callao', '06', '06 Ventanilla'),
(785, '07', '07 Callao', '01', '01 Prov. Const. del Callao', '07', '07 Mi Perú'),
(786, '08', '08 Cusco', '00', ' ', '00', ' '),
(787, '08', '08 Cusco', '01', '01 Cusco', '00', ' '),
(788, '08', '08 Cusco', '01', '01 Cusco', '01', '01 Cusco'),
(789, '08', '08 Cusco', '01', '01 Cusco', '02', '02 Ccorca'),
(790, '08', '08 Cusco', '01', '01 Cusco', '03', '03 Poroy'),
(791, '08', '08 Cusco', '01', '01 Cusco', '04', '04 San Jerónimo'),
(792, '08', '08 Cusco', '01', '01 Cusco', '05', '05 San Sebastian'),
(793, '08', '08 Cusco', '01', '01 Cusco', '06', '06 Santiago'),
(794, '08', '08 Cusco', '01', '01 Cusco', '07', '07 Saylla'),
(795, '08', '08 Cusco', '01', '01 Cusco', '08', '08 Wanchaq'),
(796, '08', '08 Cusco', '02', '02 Acomayo', '00', ' '),
(797, '08', '08 Cusco', '02', '02 Acomayo', '01', '01 Acomayo'),
(798, '08', '08 Cusco', '02', '02 Acomayo', '02', '02 Acopia'),
(799, '08', '08 Cusco', '02', '02 Acomayo', '03', '03 Acos'),
(800, '08', '08 Cusco', '02', '02 Acomayo', '04', '04 Mosoc Llacta'),
(801, '08', '08 Cusco', '02', '02 Acomayo', '05', '05 Pomacanchi'),
(802, '08', '08 Cusco', '02', '02 Acomayo', '06', '06 Rondocan'),
(803, '08', '08 Cusco', '02', '02 Acomayo', '07', '07 Sangarara'),
(804, '08', '08 Cusco', '03', '03 Anta', '00', ' '),
(805, '08', '08 Cusco', '03', '03 Anta', '01', '01 Anta'),
(806, '08', '08 Cusco', '03', '03 Anta', '02', '02 Ancahuasi'),
(807, '08', '08 Cusco', '03', '03 Anta', '03', '03 Cachimayo'),
(808, '08', '08 Cusco', '03', '03 Anta', '04', '04 Chinchaypujio'),
(809, '08', '08 Cusco', '03', '03 Anta', '05', '05 Huarocondo'),
(810, '08', '08 Cusco', '03', '03 Anta', '06', '06 Limatambo'),
(811, '08', '08 Cusco', '03', '03 Anta', '07', '07 Mollepata'),
(812, '08', '08 Cusco', '03', '03 Anta', '08', '08 Pucyura'),
(813, '08', '08 Cusco', '03', '03 Anta', '09', '09 Zurite'),
(814, '08', '08 Cusco', '04', '04 Calca', '00', ' '),
(815, '08', '08 Cusco', '04', '04 Calca', '01', '01 Calca'),
(816, '08', '08 Cusco', '04', '04 Calca', '02', '02 Coya'),
(817, '08', '08 Cusco', '04', '04 Calca', '03', '03 Lamay'),
(818, '08', '08 Cusco', '04', '04 Calca', '04', '04 Lares'),
(819, '08', '08 Cusco', '04', '04 Calca', '05', '05 Pisac'),
(820, '08', '08 Cusco', '04', '04 Calca', '06', '06 San Salvador'),
(821, '08', '08 Cusco', '04', '04 Calca', '07', '07 Taray'),
(822, '08', '08 Cusco', '04', '04 Calca', '08', '08 Yanatile'),
(823, '08', '08 Cusco', '05', '05 Canas', '00', ' '),
(824, '08', '08 Cusco', '05', '05 Canas', '01', '01 Yanaoca'),
(825, '08', '08 Cusco', '05', '05 Canas', '02', '02 Checca'),
(826, '08', '08 Cusco', '05', '05 Canas', '03', '03 Kunturkanki'),
(827, '08', '08 Cusco', '05', '05 Canas', '04', '04 Langui'),
(828, '08', '08 Cusco', '05', '05 Canas', '05', '05 Layo'),
(829, '08', '08 Cusco', '05', '05 Canas', '06', '06 Pampamarca'),
(831, '08', '08 Cusco', '05', '05 Canas', '07', '07 Quehue'),
(832, '08', '08 Cusco', '05', '05 Canas', '08', '08 Tupac Amaru'),
(833, '08', '08 Cusco', '06', '06 Canchis', '00', ' '),
(834, '08', '08 Cusco', '06', '06 Canchis', '01', '01 Sicuani'),
(835, '08', '08 Cusco', '06', '06 Canchis', '02', '02 Checacupe'),
(836, '08', '08 Cusco', '06', '06 Canchis', '03', '03 Combapata'),
(837, '08', '08 Cusco', '06', '06 Canchis', '04', '04 Marangani'),
(838, '08', '08 Cusco', '06', '06 Canchis', '05', '05 Pitumarca'),
(839, '08', '08 Cusco', '06', '06 Canchis', '06', '06 San Pablo'),
(840, '08', '08 Cusco', '06', '06 Canchis', '07', '07 San Pedro'),
(841, '08', '08 Cusco', '06', '06 Canchis', '08', '08 Tinta'),
(842, '08', '08 Cusco', '07', '07 Chumbivilcas', '00', ' '),
(843, '08', '08 Cusco', '07', '07 Chumbivilcas', '01', '01 Santo Tomas'),
(844, '08', '08 Cusco', '07', '07 Chumbivilcas', '02', '02 Capacmarca'),
(845, '08', '08 Cusco', '07', '07 Chumbivilcas', '03', '03 Chamaca'),
(846, '08', '08 Cusco', '07', '07 Chumbivilcas', '04', '04 Colquemarca'),
(847, '08', '08 Cusco', '07', '07 Chumbivilcas', '05', '05 Livitaca'),
(848, '08', '08 Cusco', '07', '07 Chumbivilcas', '06', '06 Llusco'),
(849, '08', '08 Cusco', '07', '07 Chumbivilcas', '07', '07 Quiñota'),
(850, '08', '08 Cusco', '07', '07 Chumbivilcas', '08', '08 Velille'),
(851, '08', '08 Cusco', '08', '08 Espinar', '00', ' '),
(852, '08', '08 Cusco', '08', '08 Espinar', '01', '01 Espinar'),
(853, '08', '08 Cusco', '08', '08 Espinar', '02', '02 Condoroma'),
(854, '08', '08 Cusco', '08', '08 Espinar', '03', '03 Coporaque'),
(855, '08', '08 Cusco', '08', '08 Espinar', '04', '04 Ocoruro'),
(856, '08', '08 Cusco', '08', '08 Espinar', '05', '05 Pallpata'),
(857, '08', '08 Cusco', '08', '08 Espinar', '06', '06 Pichigua'),
(858, '08', '08 Cusco', '08', '08 Espinar', '07', '07 Suyckutambo'),
(859, '08', '08 Cusco', '08', '08 Espinar', '08', '08 Alto Pichigua'),
(860, '08', '08 Cusco', '09', '09 La Convención', '00', ' '),
(861, '08', '08 Cusco', '09', '09 La Convención', '01', '01 Santa Ana'),
(862, '08', '08 Cusco', '09', '09 La Convención', '02', '02 Echarate'),
(863, '08', '08 Cusco', '09', '09 La Convención', '03', '03 Huayopata'),
(864, '08', '08 Cusco', '09', '09 La Convención', '04', '04 Maranura'),
(865, '08', '08 Cusco', '09', '09 La Convención', '05', '05 Ocobamba'),
(866, '08', '08 Cusco', '09', '09 La Convención', '06', '06 Quellouno'),
(867, '08', '08 Cusco', '09', '09 La Convención', '07', '07 Kimbiri'),
(868, '08', '08 Cusco', '09', '09 La Convención', '08', '08 Santa Teresa'),
(869, '08', '08 Cusco', '09', '09 La Convención', '09', '09 Vilcabamba'),
(870, '08', '08 Cusco', '09', '09 La Convención', '10', '10 Pichari'),
(871, '08', '08 Cusco', '09', '09 La Convención', '11', '11 Inkawasi'),
(872, '08', '08 Cusco', '09', '09 La Convención', '12', '12 Villa Virgen'),
(873, '08', '08 Cusco', '09', '09 La Convención', '13', '13 Villa Kintiarina'),
(874, '08', '08 Cusco', '09', '09 La Convención', '14', '14 Megantoni'),
(875, '08', '08 Cusco', '10', '10 Paruro', '00', ' '),
(876, '08', '08 Cusco', '10', '10 Paruro', '01', '01 Paruro'),
(877, '08', '08 Cusco', '10', '10 Paruro', '02', '02 Accha'),
(878, '08', '08 Cusco', '10', '10 Paruro', '03', '03 Ccapi'),
(879, '08', '08 Cusco', '10', '10 Paruro', '04', '04 Colcha'),
(880, '08', '08 Cusco', '10', '10 Paruro', '05', '05 Huanoquite'),
(881, '08', '08 Cusco', '10', '10 Paruro', '06', '06 Omacha'),
(883, '08', '08 Cusco', '10', '10 Paruro', '07', '07 Paccaritambo'),
(884, '08', '08 Cusco', '10', '10 Paruro', '08', '08 Pillpinto'),
(885, '08', '08 Cusco', '10', '10 Paruro', '09', '09 Yaurisque'),
(886, '08', '08 Cusco', '11', '11 Paucartambo', '00', ' '),
(887, '08', '08 Cusco', '11', '11 Paucartambo', '01', '01 Paucartambo'),
(888, '08', '08 Cusco', '11', '11 Paucartambo', '02', '02 Caicay'),
(889, '08', '08 Cusco', '11', '11 Paucartambo', '03', '03 Challabamba'),
(890, '08', '08 Cusco', '11', '11 Paucartambo', '04', '04 Colquepata'),
(891, '08', '08 Cusco', '11', '11 Paucartambo', '05', '05 Huancarani'),
(892, '08', '08 Cusco', '11', '11 Paucartambo', '06', '06 Kosñipata'),
(893, '08', '08 Cusco', '12', '12 Quispicanchi', '00', ' '),
(894, '08', '08 Cusco', '12', '12 Quispicanchi', '01', '01 Urcos'),
(895, '08', '08 Cusco', '12', '12 Quispicanchi', '02', '02 Andahuaylillas'),
(896, '08', '08 Cusco', '12', '12 Quispicanchi', '03', '03 Camanti'),
(897, '08', '08 Cusco', '12', '12 Quispicanchi', '04', '04 Ccarhuayo'),
(898, '08', '08 Cusco', '12', '12 Quispicanchi', '05', '05 Ccatca'),
(899, '08', '08 Cusco', '12', '12 Quispicanchi', '06', '06 Cusipata'),
(900, '08', '08 Cusco', '12', '12 Quispicanchi', '07', '07 Huaro'),
(901, '08', '08 Cusco', '12', '12 Quispicanchi', '08', '08 Lucre'),
(902, '08', '08 Cusco', '12', '12 Quispicanchi', '09', '09 Marcapata'),
(903, '08', '08 Cusco', '12', '12 Quispicanchi', '10', '10 Ocongate'),
(904, '08', '08 Cusco', '12', '12 Quispicanchi', '11', '11 Oropesa'),
(905, '08', '08 Cusco', '12', '12 Quispicanchi', '12', '12 Quiquijana'),
(906, '08', '08 Cusco', '13', '13 Urubamba', '00', ' '),
(907, '08', '08 Cusco', '13', '13 Urubamba', '01', '01 Urubamba'),
(908, '08', '08 Cusco', '13', '13 Urubamba', '02', '02 Chinchero'),
(909, '08', '08 Cusco', '13', '13 Urubamba', '03', '03 Huayllabamba'),
(910, '08', '08 Cusco', '13', '13 Urubamba', '04', '04 Machupicchu'),
(911, '08', '08 Cusco', '13', '13 Urubamba', '05', '05 Maras'),
(912, '08', '08 Cusco', '13', '13 Urubamba', '06', '06 Ollantaytambo'),
(913, '08', '08 Cusco', '13', '13 Urubamba', '07', '07 Yucay'),
(914, '09', '09 Huancavelica', '00', ' ', '00', ' '),
(915, '09', '09 Huancavelica', '01', '01 Huancavelica', '00', ' '),
(916, '09', '09 Huancavelica', '01', '01 Huancavelica', '01', '01 Huancavelica'),
(917, '09', '09 Huancavelica', '01', '01 Huancavelica', '02', '02 Acobambilla'),
(918, '09', '09 Huancavelica', '01', '01 Huancavelica', '03', '03 Acoria'),
(919, '09', '09 Huancavelica', '01', '01 Huancavelica', '04', '04 Conayca'),
(920, '09', '09 Huancavelica', '01', '01 Huancavelica', '05', '05 Cuenca'),
(921, '09', '09 Huancavelica', '01', '01 Huancavelica', '06', '06 Huachocolpa'),
(922, '09', '09 Huancavelica', '01', '01 Huancavelica', '07', '07 Huayllahuara'),
(923, '09', '09 Huancavelica', '01', '01 Huancavelica', '08', '08 Izcuchaca'),
(924, '09', '09 Huancavelica', '01', '01 Huancavelica', '09', '09 Laria'),
(925, '09', '09 Huancavelica', '01', '01 Huancavelica', '10', '10 Manta'),
(926, '09', '09 Huancavelica', '01', '01 Huancavelica', '11', '11 Mariscal Cáceres'),
(927, '09', '09 Huancavelica', '01', '01 Huancavelica', '12', '12 Moya'),
(928, '09', '09 Huancavelica', '01', '01 Huancavelica', '13', '13 Nuevo Occoro'),
(929, '09', '09 Huancavelica', '01', '01 Huancavelica', '14', '14 Palca'),
(930, '09', '09 Huancavelica', '01', '01 Huancavelica', '15', '15 Pilchaca'),
(931, '09', '09 Huancavelica', '01', '01 Huancavelica', '16', '16 Vilca'),
(932, '09', '09 Huancavelica', '01', '01 Huancavelica', '17', '17 Yauli'),
(933, '09', '09 Huancavelica', '01', '01 Huancavelica', '18', '18 Ascensión'),
(935, '09', '09 Huancavelica', '01', '01 Huancavelica', '19', '19 Huando'),
(936, '09', '09 Huancavelica', '02', '02 Acobamba', '00', ' '),
(937, '09', '09 Huancavelica', '02', '02 Acobamba', '01', '01 Acobamba'),
(938, '09', '09 Huancavelica', '02', '02 Acobamba', '02', '02 Andabamba'),
(939, '09', '09 Huancavelica', '02', '02 Acobamba', '03', '03 Anta'),
(940, '09', '09 Huancavelica', '02', '02 Acobamba', '04', '04 Caja'),
(941, '09', '09 Huancavelica', '02', '02 Acobamba', '05', '05 Marcas'),
(942, '09', '09 Huancavelica', '02', '02 Acobamba', '06', '06 Paucara'),
(943, '09', '09 Huancavelica', '02', '02 Acobamba', '07', '07 Pomacocha'),
(944, '09', '09 Huancavelica', '02', '02 Acobamba', '08', '08 Rosario'),
(945, '09', '09 Huancavelica', '03', '03 Angaraes', '00', ' '),
(946, '09', '09 Huancavelica', '03', '03 Angaraes', '01', '01 Lircay'),
(947, '09', '09 Huancavelica', '03', '03 Angaraes', '02', '02 Anchonga'),
(948, '09', '09 Huancavelica', '03', '03 Angaraes', '03', '03 Callanmarca'),
(949, '09', '09 Huancavelica', '03', '03 Angaraes', '04', '04 Ccochaccasa'),
(950, '09', '09 Huancavelica', '03', '03 Angaraes', '05', '05 Chincho'),
(951, '09', '09 Huancavelica', '03', '03 Angaraes', '06', '06 Congalla'),
(952, '09', '09 Huancavelica', '03', '03 Angaraes', '07', '07 Huanca-Huanca'),
(953, '09', '09 Huancavelica', '03', '03 Angaraes', '08', '08 Huayllay Grande'),
(954, '09', '09 Huancavelica', '03', '03 Angaraes', '09', '09 Julcamarca'),
(955, '09', '09 Huancavelica', '03', '03 Angaraes', '10', '10 San Antonio de Antaparco'),
(956, '09', '09 Huancavelica', '03', '03 Angaraes', '11', '11 Santo Tomas de Pata'),
(957, '09', '09 Huancavelica', '03', '03 Angaraes', '12', '12 Secclla'),
(958, '09', '09 Huancavelica', '04', '04 Castrovirreyna', '00', ' '),
(959, '09', '09 Huancavelica', '04', '04 Castrovirreyna', '01', '01 Castrovirreyna'),
(960, '09', '09 Huancavelica', '04', '04 Castrovirreyna', '02', '02 Arma'),
(961, '09', '09 Huancavelica', '04', '04 Castrovirreyna', '03', '03 Aurahua'),
(962, '09', '09 Huancavelica', '04', '04 Castrovirreyna', '04', '04 Capillas'),
(963, '09', '09 Huancavelica', '04', '04 Castrovirreyna', '05', '05 Chupamarca'),
(964, '09', '09 Huancavelica', '04', '04 Castrovirreyna', '06', '06 Cocas'),
(965, '09', '09 Huancavelica', '04', '04 Castrovirreyna', '07', '07 Huachos'),
(966, '09', '09 Huancavelica', '04', '04 Castrovirreyna', '08', '08 Huamatambo'),
(967, '09', '09 Huancavelica', '04', '04 Castrovirreyna', '09', '09 Mollepampa'),
(968, '09', '09 Huancavelica', '04', '04 Castrovirreyna', '10', '10 San Juan'),
(969, '09', '09 Huancavelica', '04', '04 Castrovirreyna', '11', '11 Santa Ana'),
(970, '09', '09 Huancavelica', '04', '04 Castrovirreyna', '12', '12 Tantara'),
(971, '09', '09 Huancavelica', '04', '04 Castrovirreyna', '13', '13 Ticrapo'),
(972, '09', '09 Huancavelica', '05', '05 Churcampa', '00', ' '),
(973, '09', '09 Huancavelica', '05', '05 Churcampa', '01', '01 Churcampa'),
(974, '09', '09 Huancavelica', '05', '05 Churcampa', '02', '02 Anco'),
(975, '09', '09 Huancavelica', '05', '05 Churcampa', '03', '03 Chinchihuasi'),
(976, '09', '09 Huancavelica', '05', '05 Churcampa', '04', '04 El Carmen'),
(977, '09', '09 Huancavelica', '05', '05 Churcampa', '05', '05 La Merced'),
(978, '09', '09 Huancavelica', '05', '05 Churcampa', '06', '06 Locroja'),
(979, '09', '09 Huancavelica', '05', '05 Churcampa', '07', '07 Paucarbamba'),
(980, '09', '09 Huancavelica', '05', '05 Churcampa', '08', '08 San Miguel de Mayocc'),
(981, '09', '09 Huancavelica', '05', '05 Churcampa', '09', '09 San Pedro de Coris'),
(982, '09', '09 Huancavelica', '05', '05 Churcampa', '10', '10 Pachamarca'),
(983, '09', '09 Huancavelica', '05', '05 Churcampa', '11', '11 Cosme'),
(984, '09', '09 Huancavelica', '06', '06 Huaytará', '00', ' '),
(985, '09', '09 Huancavelica', '06', '06 Huaytará', '01', '01 Huaytara'),
(987, '09', '09 Huancavelica', '06', '06 Huaytará', '02', '02 Ayavi'),
(988, '09', '09 Huancavelica', '06', '06 Huaytará', '03', '03 Córdova'),
(989, '09', '09 Huancavelica', '06', '06 Huaytará', '04', '04 Huayacundo Arma'),
(990, '09', '09 Huancavelica', '06', '06 Huaytará', '05', '05 Laramarca'),
(991, '09', '09 Huancavelica', '06', '06 Huaytará', '06', '06 Ocoyo'),
(992, '09', '09 Huancavelica', '06', '06 Huaytará', '07', '07 Pilpichaca'),
(993, '09', '09 Huancavelica', '06', '06 Huaytará', '08', '08 Querco'),
(994, '09', '09 Huancavelica', '06', '06 Huaytará', '09', '09 Quito-Arma'),
(995, '09', '09 Huancavelica', '06', '06 Huaytará', '10', '10 San Antonio de Cusicancha'),
(996, '09', '09 Huancavelica', '06', '06 Huaytará', '11', '11 San Francisco de Sangayaico'),
(997, '09', '09 Huancavelica', '06', '06 Huaytará', '12', '12 San Isidro'),
(998, '09', '09 Huancavelica', '06', '06 Huaytará', '13', '13 Santiago de Chocorvos'),
(999, '09', '09 Huancavelica', '06', '06 Huaytará', '14', '14 Santiago de Quirahuara'),
(1000, '09', '09 Huancavelica', '06', '06 Huaytará', '15', '15 Santo Domingo de Capillas'),
(1001, '09', '09 Huancavelica', '06', '06 Huaytará', '16', '16 Tambo'),
(1002, '09', '09 Huancavelica', '07', '07 Tayacaja', '00', ' '),
(1003, '09', '09 Huancavelica', '07', '07 Tayacaja', '01', '01 Pampas'),
(1004, '09', '09 Huancavelica', '07', '07 Tayacaja', '02', '02 Acostambo'),
(1005, '09', '09 Huancavelica', '07', '07 Tayacaja', '03', '03 Acraquia'),
(1006, '09', '09 Huancavelica', '07', '07 Tayacaja', '04', '04 Ahuaycha'),
(1007, '09', '09 Huancavelica', '07', '07 Tayacaja', '05', '05 Colcabamba'),
(1008, '09', '09 Huancavelica', '07', '07 Tayacaja', '06', '06 Daniel Hernández'),
(1009, '09', '09 Huancavelica', '07', '07 Tayacaja', '07', '07 Huachocolpa'),
(1010, '09', '09 Huancavelica', '07', '07 Tayacaja', '09', '09 Huaribamba'),
(1011, '09', '09 Huancavelica', '07', '07 Tayacaja', '10', '10 Ñahuimpuquio'),
(1012, '09', '09 Huancavelica', '07', '07 Tayacaja', '11', '11 Pazos'),
(1013, '09', '09 Huancavelica', '07', '07 Tayacaja', '13', '13 Quishuar'),
(1014, '09', '09 Huancavelica', '07', '07 Tayacaja', '14', '14 Salcabamba'),
(1015, '09', '09 Huancavelica', '07', '07 Tayacaja', '15', '15 Salcahuasi'),
(1016, '09', '09 Huancavelica', '07', '07 Tayacaja', '16', '16 San Marcos de Rocchac'),
(1017, '09', '09 Huancavelica', '07', '07 Tayacaja', '17', '17 Surcubamba'),
(1018, '09', '09 Huancavelica', '07', '07 Tayacaja', '18', '18 Tintay Puncu'),
(1019, '09', '09 Huancavelica', '07', '07 Tayacaja', '19', '19 Quichuas'),
(1020, '09', '09 Huancavelica', '07', '07 Tayacaja', '20', '20 Andaymarca'),
(1021, '09', '09 Huancavelica', '07', '07 Tayacaja', '21', '21 Roble'),
(1022, '09', '09 Huancavelica', '07', '07 Tayacaja', '22', '22 Pichos'),
(1023, '09', '09 Huancavelica', '07', '07 Tayacaja', '23', '23 Santiago de Tucuma'),
(1024, '10', '10 Huánuco', '00', ' ', '00', ' '),
(1025, '10', '10 Huánuco', '01', '01 Huánuco', '00', ' '),
(1026, '10', '10 Huánuco', '01', '01 Huánuco', '01', '01 Huanuco'),
(1027, '10', '10 Huánuco', '01', '01 Huánuco', '02', '02 Amarilis'),
(1028, '10', '10 Huánuco', '01', '01 Huánuco', '03', '03 Chinchao'),
(1029, '10', '10 Huánuco', '01', '01 Huánuco', '04', '04 Churubamba'),
(1030, '10', '10 Huánuco', '01', '01 Huánuco', '05', '05 Margos'),
(1031, '10', '10 Huánuco', '01', '01 Huánuco', '06', '06 Quisqui (Kichki)'),
(1032, '10', '10 Huánuco', '01', '01 Huánuco', '07', '07 San Francisco de Cayran'),
(1033, '10', '10 Huánuco', '01', '01 Huánuco', '08', '08 San Pedro de Chaulan'),
(1034, '10', '10 Huánuco', '01', '01 Huánuco', '09', '09 Santa María del Valle'),
(1035, '10', '10 Huánuco', '01', '01 Huánuco', '10', '10 Yarumayo'),
(1036, '10', '10 Huánuco', '01', '01 Huánuco', '11', '11 Pillco Marca'),
(1037, '10', '10 Huánuco', '01', '01 Huánuco', '12', '12 Yacus'),
(1039, '10', '10 Huánuco', '01', '01 Huánuco', '13', '13 San Pablo de Pillao'),
(1040, '10', '10 Huánuco', '02', '02 Ambo', '00', ' '),
(1041, '10', '10 Huánuco', '02', '02 Ambo', '01', '01 Ambo'),
(1042, '10', '10 Huánuco', '02', '02 Ambo', '02', '02 Cayna'),
(1043, '10', '10 Huánuco', '02', '02 Ambo', '03', '03 Colpas'),
(1044, '10', '10 Huánuco', '02', '02 Ambo', '04', '04 Conchamarca'),
(1045, '10', '10 Huánuco', '02', '02 Ambo', '05', '05 Huacar'),
(1046, '10', '10 Huánuco', '02', '02 Ambo', '06', '06 San Francisco'),
(1047, '10', '10 Huánuco', '02', '02 Ambo', '07', '07 San Rafael'),
(1048, '10', '10 Huánuco', '02', '02 Ambo', '08', '08 Tomay Kichwa'),
(1049, '10', '10 Huánuco', '03', '03 Dos de Mayo', '00', ' '),
(1050, '10', '10 Huánuco', '03', '03 Dos de Mayo', '01', '01 La Unión'),
(1051, '10', '10 Huánuco', '03', '03 Dos de Mayo', '07', '07 Chuquis'),
(1052, '10', '10 Huánuco', '03', '03 Dos de Mayo', '11', '11 Marías'),
(1053, '10', '10 Huánuco', '03', '03 Dos de Mayo', '13', '13 Pachas'),
(1054, '10', '10 Huánuco', '03', '03 Dos de Mayo', '16', '16 Quivilla'),
(1055, '10', '10 Huánuco', '03', '03 Dos de Mayo', '17', '17 Ripan'),
(1056, '10', '10 Huánuco', '03', '03 Dos de Mayo', '21', '21 Shunqui'),
(1057, '10', '10 Huánuco', '03', '03 Dos de Mayo', '22', '22 Sillapata'),
(1058, '10', '10 Huánuco', '03', '03 Dos de Mayo', '23', '23 Yanas'),
(1059, '10', '10 Huánuco', '04', '04 Huacaybamba', '00', ' '),
(1060, '10', '10 Huánuco', '04', '04 Huacaybamba', '01', '01 Huacaybamba'),
(1061, '10', '10 Huánuco', '04', '04 Huacaybamba', '02', '02 Canchabamba'),
(1062, '10', '10 Huánuco', '04', '04 Huacaybamba', '03', '03 Cochabamba'),
(1063, '10', '10 Huánuco', '04', '04 Huacaybamba', '04', '04 Pinra'),
(1064, '10', '10 Huánuco', '05', '05 Huamalíes', '00', ' '),
(1065, '10', '10 Huánuco', '05', '05 Huamalíes', '01', '01 Llata'),
(1066, '10', '10 Huánuco', '05', '05 Huamalíes', '02', '02 Arancay'),
(1067, '10', '10 Huánuco', '05', '05 Huamalíes', '03', '03 Chavín de Pariarca'),
(1068, '10', '10 Huánuco', '05', '05 Huamalíes', '04', '04 Jacas Grande'),
(1069, '10', '10 Huánuco', '05', '05 Huamalíes', '05', '05 Jircan'),
(1070, '10', '10 Huánuco', '05', '05 Huamalíes', '06', '06 Miraflores'),
(1071, '10', '10 Huánuco', '05', '05 Huamalíes', '07', '07 Monzón'),
(1072, '10', '10 Huánuco', '05', '05 Huamalíes', '08', '08 Punchao'),
(1073, '10', '10 Huánuco', '05', '05 Huamalíes', '09', '09 Puños'),
(1074, '10', '10 Huánuco', '05', '05 Huamalíes', '10', '10 Singa'),
(1075, '10', '10 Huánuco', '05', '05 Huamalíes', '11', '11 Tantamayo'),
(1076, '10', '10 Huánuco', '06', '06 Leoncio Prado', '00', ' '),
(1077, '10', '10 Huánuco', '06', '06 Leoncio Prado', '01', '01 Rupa-Rupa'),
(1078, '10', '10 Huánuco', '06', '06 Leoncio Prado', '02', '02 Daniel Alomía Robles'),
(1079, '10', '10 Huánuco', '06', '06 Leoncio Prado', '03', '03 Hermílio Valdizan'),
(1080, '10', '10 Huánuco', '06', '06 Leoncio Prado', '04', '04 José Crespo y Castillo'),
(1081, '10', '10 Huánuco', '06', '06 Leoncio Prado', '05', '05 Luyando'),
(1082, '10', '10 Huánuco', '06', '06 Leoncio Prado', '06', '06 Mariano Damaso Beraun'),
(1083, '10', '10 Huánuco', '06', '06 Leoncio Prado', '07', '07 Pucayacu'),
(1084, '10', '10 Huánuco', '06', '06 Leoncio Prado', '08', '08 Castillo Grande'),
(1085, '10', '10 Huánuco', '06', '06 Leoncio Prado', '09', '09 Pueblo Nuevo'),
(1086, '10', '10 Huánuco', '06', '06 Leoncio Prado', '10', '10 Santo Domingo de Anda'),
(1087, '10', '10 Huánuco', '07', '07 Marañón', '00', ' '),
(1088, '10', '10 Huánuco', '07', '07 Marañón', '01', '01 Huacrachuco'),
(1089, '10', '10 Huánuco', '07', '07 Marañón', '02', '02 Cholon'),
(1091, '10', '10 Huánuco', '07', '07 Marañón', '03', '03 San Buenaventura'),
(1092, '10', '10 Huánuco', '07', '07 Marañón', '04', '04 La Morada'),
(1093, '10', '10 Huánuco', '07', '07 Marañón', '05', '05 Santa Rosa de Alto Yanajanca'),
(1094, '10', '10 Huánuco', '08', '08 Pachitea', '00', ' '),
(1095, '10', '10 Huánuco', '08', '08 Pachitea', '01', '01 Panao'),
(1096, '10', '10 Huánuco', '08', '08 Pachitea', '02', '02 Chaglla'),
(1097, '10', '10 Huánuco', '08', '08 Pachitea', '03', '03 Molino'),
(1098, '10', '10 Huánuco', '08', '08 Pachitea', '04', '04 Umari'),
(1099, '10', '10 Huánuco', '09', '09 Puerto Inca', '00', ' '),
(1100, '10', '10 Huánuco', '09', '09 Puerto Inca', '01', '01 Puerto Inca'),
(1101, '10', '10 Huánuco', '09', '09 Puerto Inca', '02', '02 Codo del Pozuzo'),
(1102, '10', '10 Huánuco', '09', '09 Puerto Inca', '03', '03 Honoria'),
(1103, '10', '10 Huánuco', '09', '09 Puerto Inca', '04', '04 Tournavista'),
(1104, '10', '10 Huánuco', '09', '09 Puerto Inca', '05', '05 Yuyapichis'),
(1105, '10', '10 Huánuco', '10', '10 Lauricocha ', '00', ' '),
(1106, '10', '10 Huánuco', '10', '10 Lauricocha ', '01', '01 Jesús'),
(1107, '10', '10 Huánuco', '10', '10 Lauricocha ', '02', '02 Baños'),
(1108, '10', '10 Huánuco', '10', '10 Lauricocha ', '03', '03 Jivia'),
(1109, '10', '10 Huánuco', '10', '10 Lauricocha ', '04', '04 Queropalca'),
(1110, '10', '10 Huánuco', '10', '10 Lauricocha ', '05', '05 Rondos'),
(1111, '10', '10 Huánuco', '10', '10 Lauricocha ', '06', '06 San Francisco de Asís'),
(1112, '10', '10 Huánuco', '10', '10 Lauricocha ', '07', '07 San Miguel de Cauri'),
(1113, '10', '10 Huánuco', '11', '11 Yarowilca ', '00', ' '),
(1114, '10', '10 Huánuco', '11', '11 Yarowilca ', '01', '01 Chavinillo'),
(1115, '10', '10 Huánuco', '11', '11 Yarowilca ', '02', '02 Cahuac'),
(1116, '10', '10 Huánuco', '11', '11 Yarowilca ', '03', '03 Chacabamba'),
(1117, '10', '10 Huánuco', '11', '11 Yarowilca ', '04', '04 Aparicio Pomares'),
(1118, '10', '10 Huánuco', '11', '11 Yarowilca ', '05', '05 Jacas Chico'),
(1119, '10', '10 Huánuco', '11', '11 Yarowilca ', '06', '06 Obas'),
(1120, '10', '10 Huánuco', '11', '11 Yarowilca ', '07', '07 Pampamarca'),
(1121, '10', '10 Huánuco', '11', '11 Yarowilca ', '08', '08 Choras'),
(1122, '11', '11 Ica', '00', ' ', '00', ' '),
(1123, '11', '11 Ica', '01', '01 Ica ', '00', ' '),
(1124, '11', '11 Ica', '01', '01 Ica ', '01', '01 Ica'),
(1125, '11', '11 Ica', '01', '01 Ica ', '02', '02 La Tinguiña'),
(1126, '11', '11 Ica', '01', '01 Ica ', '03', '03 Los Aquijes'),
(1127, '11', '11 Ica', '01', '01 Ica ', '04', '04 Ocucaje'),
(1128, '11', '11 Ica', '01', '01 Ica ', '05', '05 Pachacutec'),
(1129, '11', '11 Ica', '01', '01 Ica ', '06', '06 Parcona'),
(1130, '11', '11 Ica', '01', '01 Ica ', '07', '07 Pueblo Nuevo'),
(1131, '11', '11 Ica', '01', '01 Ica ', '08', '08 Salas'),
(1132, '11', '11 Ica', '01', '01 Ica ', '09', '09 San José de Los Molinos'),
(1133, '11', '11 Ica', '01', '01 Ica ', '10', '10 San Juan Bautista'),
(1134, '11', '11 Ica', '01', '01 Ica ', '11', '11 Santiago'),
(1135, '11', '11 Ica', '01', '01 Ica ', '12', '12 Subtanjalla'),
(1136, '11', '11 Ica', '01', '01 Ica ', '13', '13 Tate'),
(1137, '11', '11 Ica', '01', '01 Ica ', '14', '14 Yauca del Rosario'),
(1138, '11', '11 Ica', '02', '02 Chincha ', '00', ' '),
(1139, '11', '11 Ica', '02', '02 Chincha ', '01', '01 Chincha Alta'),
(1140, '11', '11 Ica', '02', '02 Chincha ', '02', '02 Alto Laran'),
(1141, '11', '11 Ica', '02', '02 Chincha ', '03', '03 Chavin'),
(1143, '11', '11 Ica', '02', '02 Chincha ', '04', '04 Chincha Baja'),
(1144, '11', '11 Ica', '02', '02 Chincha ', '05', '05 El Carmen'),
(1145, '11', '11 Ica', '02', '02 Chincha ', '06', '06 Grocio Prado'),
(1146, '11', '11 Ica', '02', '02 Chincha ', '07', '07 Pueblo Nuevo'),
(1147, '11', '11 Ica', '02', '02 Chincha ', '08', '08 San Juan de Yanac'),
(1148, '11', '11 Ica', '02', '02 Chincha ', '09', '09 San Pedro de Huacarpana'),
(1149, '11', '11 Ica', '02', '02 Chincha ', '10', '10 Sunampe'),
(1150, '11', '11 Ica', '02', '02 Chincha ', '11', '11 Tambo de Mora'),
(1151, '11', '11 Ica', '03', '03 Nasca ', '00', ' '),
(1152, '11', '11 Ica', '03', '03 Nasca ', '01', '01 Nasca'),
(1153, '11', '11 Ica', '03', '03 Nasca ', '02', '02 Changuillo'),
(1154, '11', '11 Ica', '03', '03 Nasca ', '03', '03 El Ingenio'),
(1155, '11', '11 Ica', '03', '03 Nasca ', '04', '04 Marcona'),
(1156, '11', '11 Ica', '03', '03 Nasca ', '05', '05 Vista Alegre'),
(1157, '11', '11 Ica', '04', '04 Palpa ', '00', ' '),
(1158, '11', '11 Ica', '04', '04 Palpa ', '01', '01 Palpa'),
(1159, '11', '11 Ica', '04', '04 Palpa ', '02', '02 Llipata'),
(1160, '11', '11 Ica', '04', '04 Palpa ', '03', '03 Río Grande'),
(1161, '11', '11 Ica', '04', '04 Palpa ', '04', '04 Santa Cruz'),
(1162, '11', '11 Ica', '04', '04 Palpa ', '05', '05 Tibillo'),
(1163, '11', '11 Ica', '05', '05 Pisco ', '00', ' '),
(1164, '11', '11 Ica', '05', '05 Pisco ', '01', '01 Pisco'),
(1165, '11', '11 Ica', '05', '05 Pisco ', '02', '02 Huancano'),
(1166, '11', '11 Ica', '05', '05 Pisco ', '03', '03 Humay'),
(1167, '11', '11 Ica', '05', '05 Pisco ', '04', '04 Independencia'),
(1168, '11', '11 Ica', '05', '05 Pisco ', '05', '05 Paracas'),
(1169, '11', '11 Ica', '05', '05 Pisco ', '06', '06 San Andrés'),
(1170, '11', '11 Ica', '05', '05 Pisco ', '07', '07 San Clemente'),
(1171, '11', '11 Ica', '05', '05 Pisco ', '08', '08 Tupac Amaru Inca'),
(1172, '12', '12 Junín', '00', ' ', '00', ' '),
(1173, '12', '12 Junín', '01', '01 Huancayo ', '00', ' '),
(1174, '12', '12 Junín', '01', '01 Huancayo ', '01', '01 Huancayo'),
(1175, '12', '12 Junín', '01', '01 Huancayo ', '04', '04 Carhuacallanga'),
(1176, '12', '12 Junín', '01', '01 Huancayo ', '05', '05 Chacapampa'),
(1177, '12', '12 Junín', '01', '01 Huancayo ', '06', '06 Chicche'),
(1178, '12', '12 Junín', '01', '01 Huancayo ', '07', '07 Chilca'),
(1179, '12', '12 Junín', '01', '01 Huancayo ', '08', '08 Chongos Alto'),
(1180, '12', '12 Junín', '01', '01 Huancayo ', '11', '11 Chupuro'),
(1181, '12', '12 Junín', '01', '01 Huancayo ', '12', '12 Colca'),
(1182, '12', '12 Junín', '01', '01 Huancayo ', '13', '13 Cullhuas'),
(1183, '12', '12 Junín', '01', '01 Huancayo ', '14', '14 El Tambo'),
(1184, '12', '12 Junín', '01', '01 Huancayo ', '16', '16 Huacrapuquio'),
(1185, '12', '12 Junín', '01', '01 Huancayo ', '17', '17 Hualhuas'),
(1186, '12', '12 Junín', '01', '01 Huancayo ', '19', '19 Huancan'),
(1187, '12', '12 Junín', '01', '01 Huancayo ', '20', '20 Huasicancha'),
(1188, '12', '12 Junín', '01', '01 Huancayo ', '21', '21 Huayucachi'),
(1189, '12', '12 Junín', '01', '01 Huancayo ', '22', '22 Ingenio'),
(1190, '12', '12 Junín', '01', '01 Huancayo ', '24', '24 Pariahuanca'),
(1191, '12', '12 Junín', '01', '01 Huancayo ', '25', '25 Pilcomayo'),
(1192, '12', '12 Junín', '01', '01 Huancayo ', '26', '26 Pucara'),
(1193, '12', '12 Junín', '01', '01 Huancayo ', '27', '27 Quichuay'),
(1195, '12', '12 Junín', '01', '01 Huancayo ', '28', '28 Quilcas'),
(1196, '12', '12 Junín', '01', '01 Huancayo ', '29', '29 San Agustín'),
(1197, '12', '12 Junín', '01', '01 Huancayo ', '30', '30 San Jerónimo de Tunan'),
(1198, '12', '12 Junín', '01', '01 Huancayo ', '32', '32 Saño'),
(1199, '12', '12 Junín', '01', '01 Huancayo ', '33', '33 Sapallanga'),
(1200, '12', '12 Junín', '01', '01 Huancayo ', '34', '34 Sicaya'),
(1201, '12', '12 Junín', '01', '01 Huancayo ', '35', '35 Santo Domingo de Acobamba'),
(1202, '12', '12 Junín', '01', '01 Huancayo ', '36', '36 Viques'),
(1203, '12', '12 Junín', '02', '02 Concepción ', '00', ' '),
(1204, '12', '12 Junín', '02', '02 Concepción ', '01', '01 Concepción'),
(1205, '12', '12 Junín', '02', '02 Concepción ', '02', '02 Aco'),
(1206, '12', '12 Junín', '02', '02 Concepción ', '03', '03 Andamarca'),
(1207, '12', '12 Junín', '02', '02 Concepción ', '04', '04 Chambara'),
(1208, '12', '12 Junín', '02', '02 Concepción ', '05', '05 Cochas'),
(1209, '12', '12 Junín', '02', '02 Concepción ', '06', '06 Comas'),
(1210, '12', '12 Junín', '02', '02 Concepción ', '07', '07 Heroínas Toledo'),
(1211, '12', '12 Junín', '02', '02 Concepción ', '08', '08 Manzanares'),
(1212, '12', '12 Junín', '02', '02 Concepción ', '09', '09 Mariscal Castilla'),
(1213, '12', '12 Junín', '02', '02 Concepción ', '10', '10 Matahuasi'),
(1214, '12', '12 Junín', '02', '02 Concepción ', '11', '11 Mito'),
(1215, '12', '12 Junín', '02', '02 Concepción ', '12', '12 Nueve de Julio'),
(1216, '12', '12 Junín', '02', '02 Concepción ', '13', '13 Orcotuna'),
(1217, '12', '12 Junín', '02', '02 Concepción ', '14', '14 San José de Quero'),
(1218, '12', '12 Junín', '02', '02 Concepción ', '15', '15 Santa Rosa de Ocopa'),
(1219, '12', '12 Junín', '03', '03 Chanchamayo ', '00', ' '),
(1220, '12', '12 Junín', '03', '03 Chanchamayo ', '01', '01 Chanchamayo'),
(1221, '12', '12 Junín', '03', '03 Chanchamayo ', '02', '02 Perene'),
(1222, '12', '12 Junín', '03', '03 Chanchamayo ', '03', '03 Pichanaqui'),
(1223, '12', '12 Junín', '03', '03 Chanchamayo ', '04', '04 San Luis de Shuaro'),
(1224, '12', '12 Junín', '03', '03 Chanchamayo ', '05', '05 San Ramón'),
(1225, '12', '12 Junín', '03', '03 Chanchamayo ', '06', '06 Vitoc'),
(1226, '12', '12 Junín', '04', '04 Jauja ', '00', ' '),
(1227, '12', '12 Junín', '04', '04 Jauja ', '01', '01 Jauja'),
(1228, '12', '12 Junín', '04', '04 Jauja ', '02', '02 Acolla'),
(1229, '12', '12 Junín', '04', '04 Jauja ', '03', '03 Apata'),
(1230, '12', '12 Junín', '04', '04 Jauja ', '04', '04 Ataura'),
(1231, '12', '12 Junín', '04', '04 Jauja ', '05', '05 Canchayllo'),
(1232, '12', '12 Junín', '04', '04 Jauja ', '06', '06 Curicaca'),
(1233, '12', '12 Junín', '04', '04 Jauja ', '07', '07 El Mantaro'),
(1234, '12', '12 Junín', '04', '04 Jauja ', '08', '08 Huamali'),
(1235, '12', '12 Junín', '04', '04 Jauja ', '09', '09 Huaripampa'),
(1236, '12', '12 Junín', '04', '04 Jauja ', '10', '10 Huertas'),
(1237, '12', '12 Junín', '04', '04 Jauja ', '11', '11 Janjaillo'),
(1238, '12', '12 Junín', '04', '04 Jauja ', '12', '12 Julcán'),
(1239, '12', '12 Junín', '04', '04 Jauja ', '13', '13 Leonor Ordóñez'),
(1240, '12', '12 Junín', '04', '04 Jauja ', '14', '14 Llocllapampa'),
(1241, '12', '12 Junín', '04', '04 Jauja ', '15', '15 Marco'),
(1242, '12', '12 Junín', '04', '04 Jauja ', '16', '16 Masma'),
(1243, '12', '12 Junín', '04', '04 Jauja ', '17', '17 Masma Chicche'),
(1244, '12', '12 Junín', '04', '04 Jauja ', '18', '18 Molinos'),
(1245, '12', '12 Junín', '04', '04 Jauja ', '19', '19 Monobamba'),
(1247, '12', '12 Junín', '04', '04 Jauja ', '20', '20 Muqui'),
(1248, '12', '12 Junín', '04', '04 Jauja ', '21', '21 Muquiyauyo'),
(1249, '12', '12 Junín', '04', '04 Jauja ', '22', '22 Paca'),
(1250, '12', '12 Junín', '04', '04 Jauja ', '23', '23 Paccha'),
(1251, '12', '12 Junín', '04', '04 Jauja ', '24', '24 Pancan'),
(1252, '12', '12 Junín', '04', '04 Jauja ', '25', '25 Parco'),
(1253, '12', '12 Junín', '04', '04 Jauja ', '26', '26 Pomacancha'),
(1254, '12', '12 Junín', '04', '04 Jauja ', '27', '27 Ricran'),
(1255, '12', '12 Junín', '04', '04 Jauja ', '28', '28 San Lorenzo'),
(1256, '12', '12 Junín', '04', '04 Jauja ', '29', '29 San Pedro de Chunan'),
(1257, '12', '12 Junín', '04', '04 Jauja ', '30', '30 Sausa'),
(1258, '12', '12 Junín', '04', '04 Jauja ', '31', '31 Sincos'),
(1259, '12', '12 Junín', '04', '04 Jauja ', '32', '32 Tunan Marca'),
(1260, '12', '12 Junín', '04', '04 Jauja ', '33', '33 Yauli'),
(1261, '12', '12 Junín', '04', '04 Jauja ', '34', '34 Yauyos'),
(1262, '12', '12 Junín', '05', '05 Junín ', '00', ' '),
(1263, '12', '12 Junín', '05', '05 Junín ', '01', '01 Junin'),
(1264, '12', '12 Junín', '05', '05 Junín ', '02', '02 Carhuamayo'),
(1265, '12', '12 Junín', '05', '05 Junín ', '03', '03 Ondores'),
(1266, '12', '12 Junín', '05', '05 Junín ', '04', '04 Ulcumayo'),
(1267, '12', '12 Junín', '06', '06 Satipo ', '00', ' '),
(1268, '12', '12 Junín', '06', '06 Satipo ', '01', '01 Satipo'),
(1269, '12', '12 Junín', '06', '06 Satipo ', '02', '02 Coviriali'),
(1270, '12', '12 Junín', '06', '06 Satipo ', '03', '03 Llaylla'),
(1271, '12', '12 Junín', '06', '06 Satipo ', '04', '04 Mazamari'),
(1272, '12', '12 Junín', '06', '06 Satipo ', '05', '05 Pampa Hermosa'),
(1273, '12', '12 Junín', '06', '06 Satipo ', '06', '06 Pangoa'),
(1274, '12', '12 Junín', '06', '06 Satipo ', '07', '07 Río Negro'),
(1275, '12', '12 Junín', '06', '06 Satipo ', '08', '08 Río Tambo'),
(1276, '12', '12 Junín', '06', '06 Satipo ', '09', '09 Vizcatan del Ene'),
(1277, '12', '12 Junín', '07', '07 Tarma ', '00', ' '),
(1278, '12', '12 Junín', '07', '07 Tarma ', '01', '01 Tarma'),
(1279, '12', '12 Junín', '07', '07 Tarma ', '02', '02 Acobamba'),
(1280, '12', '12 Junín', '07', '07 Tarma ', '03', '03 Huaricolca'),
(1281, '12', '12 Junín', '07', '07 Tarma ', '04', '04 Huasahuasi'),
(1282, '12', '12 Junín', '07', '07 Tarma ', '05', '05 La Unión'),
(1283, '12', '12 Junín', '07', '07 Tarma ', '06', '06 Palca'),
(1284, '12', '12 Junín', '07', '07 Tarma ', '07', '07 Palcamayo'),
(1285, '12', '12 Junín', '07', '07 Tarma ', '08', '08 San Pedro de Cajas'),
(1286, '12', '12 Junín', '07', '07 Tarma ', '09', '09 Tapo'),
(1287, '12', '12 Junín', '08', '08 Yauli ', '00', ' '),
(1288, '12', '12 Junín', '08', '08 Yauli ', '01', '01 La Oroya'),
(1289, '12', '12 Junín', '08', '08 Yauli ', '02', '02 Chacapalpa'),
(1290, '12', '12 Junín', '08', '08 Yauli ', '03', '03 Huay-Huay'),
(1291, '12', '12 Junín', '08', '08 Yauli ', '04', '04 Marcapomacocha'),
(1292, '12', '12 Junín', '08', '08 Yauli ', '05', '05 Morococha'),
(1293, '12', '12 Junín', '08', '08 Yauli ', '06', '06 Paccha'),
(1294, '12', '12 Junín', '08', '08 Yauli ', '07', '07 Santa Bárbara de Carhuacayan'),
(1295, '12', '12 Junín', '08', '08 Yauli ', '08', '08 Santa Rosa de Sacco'),
(1296, '12', '12 Junín', '08', '08 Yauli ', '09', '09 Suitucancha'),
(1297, '12', '12 Junín', '08', '08 Yauli ', '10', '10 Yauli'),
(1299, '12', '12 Junín', '09', '09 Chupaca ', '00', ' '),
(1300, '12', '12 Junín', '09', '09 Chupaca ', '01', '01 Chupaca'),
(1301, '12', '12 Junín', '09', '09 Chupaca ', '02', '02 Ahuac'),
(1302, '12', '12 Junín', '09', '09 Chupaca ', '03', '03 Chongos Bajo'),
(1303, '12', '12 Junín', '09', '09 Chupaca ', '04', '04 Huachac'),
(1304, '12', '12 Junín', '09', '09 Chupaca ', '05', '05 Huamancaca Chico'),
(1305, '12', '12 Junín', '09', '09 Chupaca ', '06', '06 San Juan de Iscos'),
(1306, '12', '12 Junín', '09', '09 Chupaca ', '07', '07 San Juan de Jarpa'),
(1307, '12', '12 Junín', '09', '09 Chupaca ', '08', '08 Tres de Diciembre'),
(1308, '12', '12 Junín', '09', '09 Chupaca ', '09', '09 Yanacancha'),
(1309, '13', '13 La Libertad', '00', ' ', '00', ' '),
(1310, '13', '13 La Libertad', '01', '01 Trujillo ', '00', ' '),
(1311, '13', '13 La Libertad', '01', '01 Trujillo ', '01', '01 Trujillo'),
(1312, '13', '13 La Libertad', '01', '01 Trujillo ', '02', '02 El Porvenir'),
(1313, '13', '13 La Libertad', '01', '01 Trujillo ', '03', '03 Florencia de Mora'),
(1314, '13', '13 La Libertad', '01', '01 Trujillo ', '04', '04 Huanchaco'),
(1315, '13', '13 La Libertad', '01', '01 Trujillo ', '05', '05 La Esperanza'),
(1316, '13', '13 La Libertad', '01', '01 Trujillo ', '06', '06 Laredo'),
(1317, '13', '13 La Libertad', '01', '01 Trujillo ', '07', '07 Moche'),
(1318, '13', '13 La Libertad', '01', '01 Trujillo ', '08', '08 Poroto'),
(1319, '13', '13 La Libertad', '01', '01 Trujillo ', '09', '09 Salaverry'),
(1320, '13', '13 La Libertad', '01', '01 Trujillo ', '10', '10 Simbal'),
(1321, '13', '13 La Libertad', '01', '01 Trujillo ', '11', '11 Victor Larco Herrera'),
(1322, '13', '13 La Libertad', '02', '02 Ascope ', '00', ' '),
(1323, '13', '13 La Libertad', '02', '02 Ascope ', '01', '01 Ascope'),
(1324, '13', '13 La Libertad', '02', '02 Ascope ', '02', '02 Chicama'),
(1325, '13', '13 La Libertad', '02', '02 Ascope ', '03', '03 Chocope'),
(1326, '13', '13 La Libertad', '02', '02 Ascope ', '04', '04 Magdalena de Cao'),
(1327, '13', '13 La Libertad', '02', '02 Ascope ', '05', '05 Paijan'),
(1328, '13', '13 La Libertad', '02', '02 Ascope ', '06', '06 Rázuri'),
(1329, '13', '13 La Libertad', '02', '02 Ascope ', '07', '07 Santiago de Cao'),
(1330, '13', '13 La Libertad', '02', '02 Ascope ', '08', '08 Casa Grande'),
(1331, '13', '13 La Libertad', '03', '03 Bolívar ', '00', ' '),
(1332, '13', '13 La Libertad', '03', '03 Bolívar ', '01', '01 Bolívar'),
(1333, '13', '13 La Libertad', '03', '03 Bolívar ', '02', '02 Bambamarca'),
(1334, '13', '13 La Libertad', '03', '03 Bolívar ', '03', '03 Condormarca'),
(1335, '13', '13 La Libertad', '03', '03 Bolívar ', '04', '04 Longotea'),
(1336, '13', '13 La Libertad', '03', '03 Bolívar ', '05', '05 Uchumarca'),
(1337, '13', '13 La Libertad', '03', '03 Bolívar ', '06', '06 Ucuncha'),
(1338, '13', '13 La Libertad', '04', '04 Chepén ', '00', ' '),
(1339, '13', '13 La Libertad', '04', '04 Chepén ', '01', '01 Chepen'),
(1340, '13', '13 La Libertad', '04', '04 Chepén ', '02', '02 Pacanga'),
(1341, '13', '13 La Libertad', '04', '04 Chepén ', '03', '03 Pueblo Nuevo'),
(1342, '13', '13 La Libertad', '05', '05 Julcán ', '00', ' '),
(1343, '13', '13 La Libertad', '05', '05 Julcán ', '01', '01 Julcan'),
(1344, '13', '13 La Libertad', '05', '05 Julcán ', '02', '02 Calamarca'),
(1345, '13', '13 La Libertad', '05', '05 Julcán ', '03', '03 Carabamba'),
(1346, '13', '13 La Libertad', '05', '05 Julcán ', '04', '04 Huaso'),
(1347, '13', '13 La Libertad', '06', '06 Otuzco ', '00', ' '),
(1348, '13', '13 La Libertad', '06', '06 Otuzco ', '01', '01 Otuzco'),
(1349, '13', '13 La Libertad', '06', '06 Otuzco ', '02', '02 Agallpampa'),
(1351, '13', '13 La Libertad', '06', '06 Otuzco ', '04', '04 Charat'),
(1352, '13', '13 La Libertad', '06', '06 Otuzco ', '05', '05 Huaranchal'),
(1353, '13', '13 La Libertad', '06', '06 Otuzco ', '06', '06 La Cuesta'),
(1354, '13', '13 La Libertad', '06', '06 Otuzco ', '08', '08 Mache'),
(1355, '13', '13 La Libertad', '06', '06 Otuzco ', '10', '10 Paranday'),
(1356, '13', '13 La Libertad', '06', '06 Otuzco ', '11', '11 Salpo'),
(1357, '13', '13 La Libertad', '06', '06 Otuzco ', '13', '13 Sinsicap'),
(1358, '13', '13 La Libertad', '06', '06 Otuzco ', '14', '14 Usquil'),
(1359, '13', '13 La Libertad', '07', '07 Pacasmayo ', '00', ' '),
(1360, '13', '13 La Libertad', '07', '07 Pacasmayo ', '01', '01 San Pedro de Lloc'),
(1361, '13', '13 La Libertad', '07', '07 Pacasmayo ', '02', '02 Guadalupe'),
(1362, '13', '13 La Libertad', '07', '07 Pacasmayo ', '03', '03 Jequetepeque'),
(1363, '13', '13 La Libertad', '07', '07 Pacasmayo ', '04', '04 Pacasmayo'),
(1364, '13', '13 La Libertad', '07', '07 Pacasmayo ', '05', '05 San José'),
(1365, '13', '13 La Libertad', '08', '08 Pataz ', '00', ' '),
(1366, '13', '13 La Libertad', '08', '08 Pataz ', '01', '01 Tayabamba'),
(1367, '13', '13 La Libertad', '08', '08 Pataz ', '02', '02 Buldibuyo'),
(1368, '13', '13 La Libertad', '08', '08 Pataz ', '03', '03 Chillia'),
(1369, '13', '13 La Libertad', '08', '08 Pataz ', '04', '04 Huancaspata'),
(1370, '13', '13 La Libertad', '08', '08 Pataz ', '05', '05 Huaylillas'),
(1371, '13', '13 La Libertad', '08', '08 Pataz ', '06', '06 Huayo'),
(1372, '13', '13 La Libertad', '08', '08 Pataz ', '07', '07 Ongon'),
(1373, '13', '13 La Libertad', '08', '08 Pataz ', '08', '08 Parcoy'),
(1374, '13', '13 La Libertad', '08', '08 Pataz ', '09', '09 Pataz'),
(1375, '13', '13 La Libertad', '08', '08 Pataz ', '10', '10 Pias'),
(1376, '13', '13 La Libertad', '08', '08 Pataz ', '11', '11 Santiago de Challas'),
(1377, '13', '13 La Libertad', '08', '08 Pataz ', '12', '12 Taurija'),
(1378, '13', '13 La Libertad', '08', '08 Pataz ', '13', '13 Urpay'),
(1379, '13', '13 La Libertad', '09', '09 Sánchez Carrión ', '00', ' '),
(1380, '13', '13 La Libertad', '09', '09 Sánchez Carrión ', '01', '01 Huamachuco'),
(1381, '13', '13 La Libertad', '09', '09 Sánchez Carrión ', '02', '02 Chugay'),
(1382, '13', '13 La Libertad', '09', '09 Sánchez Carrión ', '03', '03 Cochorco'),
(1383, '13', '13 La Libertad', '09', '09 Sánchez Carrión ', '04', '04 Curgos'),
(1384, '13', '13 La Libertad', '09', '09 Sánchez Carrión ', '05', '05 Marcabal'),
(1385, '13', '13 La Libertad', '09', '09 Sánchez Carrión ', '06', '06 Sanagoran'),
(1386, '13', '13 La Libertad', '09', '09 Sánchez Carrión ', '07', '07 Sarin'),
(1387, '13', '13 La Libertad', '09', '09 Sánchez Carrión ', '08', '08 Sartimbamba'),
(1388, '13', '13 La Libertad', '10', '10 Santiago de Chuco ', '00', ' '),
(1389, '13', '13 La Libertad', '10', '10 Santiago de Chuco ', '01', '01 Santiago de Chuco'),
(1390, '13', '13 La Libertad', '10', '10 Santiago de Chuco ', '02', '02 Angasmarca'),
(1391, '13', '13 La Libertad', '10', '10 Santiago de Chuco ', '03', '03 Cachicadan'),
(1392, '13', '13 La Libertad', '10', '10 Santiago de Chuco ', '04', '04 Mollebamba'),
(1393, '13', '13 La Libertad', '10', '10 Santiago de Chuco ', '05', '05 Mollepata'),
(1394, '13', '13 La Libertad', '10', '10 Santiago de Chuco ', '06', '06 Quiruvilca'),
(1395, '13', '13 La Libertad', '10', '10 Santiago de Chuco ', '07', '07 Santa Cruz de Chuca'),
(1396, '13', '13 La Libertad', '10', '10 Santiago de Chuco ', '08', '08 Sitabamba'),
(1397, '13', '13 La Libertad', '11', '11 Gran Chimú ', '00', ' '),
(1398, '13', '13 La Libertad', '11', '11 Gran Chimú ', '01', '01 Cascas'),
(1399, '13', '13 La Libertad', '11', '11 Gran Chimú ', '02', '02 Lucma'),
(1400, '13', '13 La Libertad', '11', '11 Gran Chimú ', '03', '03 Marmot'),
(1401, '13', '13 La Libertad', '11', '11 Gran Chimú ', '04', '04 Sayapullo'),
(1403, '13', '13 La Libertad', '12', '12 Virú ', '00', ' '),
(1404, '13', '13 La Libertad', '12', '12 Virú ', '01', '01 Viru'),
(1405, '13', '13 La Libertad', '12', '12 Virú ', '02', '02 Chao'),
(1406, '13', '13 La Libertad', '12', '12 Virú ', '03', '03 Guadalupito'),
(1407, '14', '14 Lambayeque', '00', ' ', '00', ' '),
(1408, '14', '14 Lambayeque', '01', '01 Chiclayo ', '00', ' '),
(1409, '14', '14 Lambayeque', '01', '01 Chiclayo ', '01', '01 Chiclayo'),
(1410, '14', '14 Lambayeque', '01', '01 Chiclayo ', '02', '02 Chongoyape'),
(1411, '14', '14 Lambayeque', '01', '01 Chiclayo ', '03', '03 Eten'),
(1412, '14', '14 Lambayeque', '01', '01 Chiclayo ', '04', '04 Eten Puerto'),
(1413, '14', '14 Lambayeque', '01', '01 Chiclayo ', '05', '05 José Leonardo Ortiz'),
(1414, '14', '14 Lambayeque', '01', '01 Chiclayo ', '06', '06 La Victoria'),
(1415, '14', '14 Lambayeque', '01', '01 Chiclayo ', '07', '07 Lagunas'),
(1416, '14', '14 Lambayeque', '01', '01 Chiclayo ', '08', '08 Monsefu'),
(1417, '14', '14 Lambayeque', '01', '01 Chiclayo ', '09', '09 Nueva Arica'),
(1418, '14', '14 Lambayeque', '01', '01 Chiclayo ', '10', '10 Oyotun'),
(1419, '14', '14 Lambayeque', '01', '01 Chiclayo ', '11', '11 Picsi'),
(1420, '14', '14 Lambayeque', '01', '01 Chiclayo ', '12', '12 Pimentel'),
(1421, '14', '14 Lambayeque', '01', '01 Chiclayo ', '13', '13 Reque'),
(1422, '14', '14 Lambayeque', '01', '01 Chiclayo ', '14', '14 Santa Rosa'),
(1423, '14', '14 Lambayeque', '01', '01 Chiclayo ', '15', '15 Saña'),
(1424, '14', '14 Lambayeque', '01', '01 Chiclayo ', '16', '16 Cayalti'),
(1425, '14', '14 Lambayeque', '01', '01 Chiclayo ', '17', '17 Patapo'),
(1426, '14', '14 Lambayeque', '01', '01 Chiclayo ', '18', '18 Pomalca'),
(1427, '14', '14 Lambayeque', '01', '01 Chiclayo ', '19', '19 Pucala'),
(1428, '14', '14 Lambayeque', '01', '01 Chiclayo ', '20', '20 Tuman'),
(1429, '14', '14 Lambayeque', '02', '02 Ferreñafe ', '00', ' '),
(1430, '14', '14 Lambayeque', '02', '02 Ferreñafe ', '01', '01 Ferreñafe'),
(1431, '14', '14 Lambayeque', '02', '02 Ferreñafe ', '02', '02 Cañaris'),
(1432, '14', '14 Lambayeque', '02', '02 Ferreñafe ', '03', '03 Incahuasi'),
(1433, '14', '14 Lambayeque', '02', '02 Ferreñafe ', '04', '04 Manuel Antonio Mesones Muro'),
(1434, '14', '14 Lambayeque', '02', '02 Ferreñafe ', '05', '05 Pitipo'),
(1435, '14', '14 Lambayeque', '02', '02 Ferreñafe ', '06', '06 Pueblo Nuevo'),
(1436, '14', '14 Lambayeque', '03', '03 Lambayeque ', '00', ' '),
(1437, '14', '14 Lambayeque', '03', '03 Lambayeque ', '01', '01 Lambayeque'),
(1438, '14', '14 Lambayeque', '03', '03 Lambayeque ', '02', '02 Chochope'),
(1439, '14', '14 Lambayeque', '03', '03 Lambayeque ', '03', '03 Illimo'),
(1440, '14', '14 Lambayeque', '03', '03 Lambayeque ', '04', '04 Jayanca'),
(1441, '14', '14 Lambayeque', '03', '03 Lambayeque ', '05', '05 Mochumi'),
(1442, '14', '14 Lambayeque', '03', '03 Lambayeque ', '06', '06 Morrope'),
(1443, '14', '14 Lambayeque', '03', '03 Lambayeque ', '07', '07 Motupe'),
(1444, '14', '14 Lambayeque', '03', '03 Lambayeque ', '08', '08 Olmos'),
(1445, '14', '14 Lambayeque', '03', '03 Lambayeque ', '09', '09 Pacora'),
(1446, '14', '14 Lambayeque', '03', '03 Lambayeque ', '10', '10 Salas'),
(1447, '14', '14 Lambayeque', '03', '03 Lambayeque ', '11', '11 San José'),
(1448, '14', '14 Lambayeque', '03', '03 Lambayeque ', '12', '12 Tucume'),
(1449, '15', '15 Lima', '00', ' ', '00', ' '),
(1450, '15', '15 Lima', '01', '01 Lima ', '00', ' '),
(1451, '15', '15 Lima', '01', '01 Lima ', '01', '01 Lima'),
(1452, '15', '15 Lima', '01', '01 Lima ', '02', '02 Ancón'),
(1453, '15', '15 Lima', '01', '01 Lima ', '03', '03 Ate'),
(1455, '15', '15 Lima', '01', '01 Lima ', '04', '04 Barranco'),
(1456, '15', '15 Lima', '01', '01 Lima ', '05', '05 Breña'),
(1457, '15', '15 Lima', '01', '01 Lima ', '06', '06 Carabayllo'),
(1458, '15', '15 Lima', '01', '01 Lima ', '07', '07 Chaclacayo'),
(1459, '15', '15 Lima', '01', '01 Lima ', '08', '08 Chorrillos'),
(1460, '15', '15 Lima', '01', '01 Lima ', '09', '09 Cieneguilla'),
(1461, '15', '15 Lima', '01', '01 Lima ', '10', '10 Comas'),
(1462, '15', '15 Lima', '01', '01 Lima ', '11', '11 El Agustino'),
(1463, '15', '15 Lima', '01', '01 Lima ', '12', '12 Independencia'),
(1464, '15', '15 Lima', '01', '01 Lima ', '13', '13 Jesús María'),
(1465, '15', '15 Lima', '01', '01 Lima ', '14', '14 La Molina'),
(1466, '15', '15 Lima', '01', '01 Lima ', '15', '15 La Victoria'),
(1467, '15', '15 Lima', '01', '01 Lima ', '16', '16 Lince'),
(1468, '15', '15 Lima', '01', '01 Lima ', '17', '17 Los Olivos'),
(1469, '15', '15 Lima', '01', '01 Lima ', '18', '18 Lurigancho'),
(1470, '15', '15 Lima', '01', '01 Lima ', '19', '19 Lurin'),
(1471, '15', '15 Lima', '01', '01 Lima ', '20', '20 Magdalena del Mar'),
(1472, '15', '15 Lima', '01', '01 Lima ', '21', '21 Pueblo Libre'),
(1473, '15', '15 Lima', '01', '01 Lima ', '22', '22 Miraflores'),
(1474, '15', '15 Lima', '01', '01 Lima ', '23', '23 Pachacamac'),
(1475, '15', '15 Lima', '01', '01 Lima ', '24', '24 Pucusana'),
(1476, '15', '15 Lima', '01', '01 Lima ', '25', '25 Puente Piedra'),
(1477, '15', '15 Lima', '01', '01 Lima ', '26', '26 Punta Hermosa'),
(1478, '15', '15 Lima', '01', '01 Lima ', '27', '27 Punta Negra'),
(1479, '15', '15 Lima', '01', '01 Lima ', '28', '28 Rímac'),
(1480, '15', '15 Lima', '01', '01 Lima ', '29', '29 San Bartolo'),
(1481, '15', '15 Lima', '01', '01 Lima ', '30', '30 San Borja'),
(1482, '15', '15 Lima', '01', '01 Lima ', '31', '31 San Isidro'),
(1483, '15', '15 Lima', '01', '01 Lima ', '32', '32 San Juan de Lurigancho'),
(1484, '15', '15 Lima', '01', '01 Lima ', '33', '33 San Juan de Miraflores'),
(1485, '15', '15 Lima', '01', '01 Lima ', '34', '34 San Luis'),
(1486, '15', '15 Lima', '01', '01 Lima ', '35', '35 San Martín de Porres'),
(1487, '15', '15 Lima', '01', '01 Lima ', '36', '36 San Miguel'),
(1488, '15', '15 Lima', '01', '01 Lima ', '37', '37 Santa Anita');
INSERT INTO `ubigeo2016` (`id`, `codigo_departamento`, `departamento`, `codigo_provincia`, `provincia`, `codigo_distrito`, `distrito`) VALUES
(1489, '15', '15 Lima', '01', '01 Lima ', '38', '38 Santa María del Mar'),
(1490, '15', '15 Lima', '01', '01 Lima ', '39', '39 Santa Rosa'),
(1491, '15', '15 Lima', '01', '01 Lima ', '40', '40 Santiago de Surco'),
(1492, '15', '15 Lima', '01', '01 Lima ', '41', '41 Surquillo'),
(1493, '15', '15 Lima', '01', '01 Lima ', '42', '42 Villa El Salvador'),
(1494, '15', '15 Lima', '01', '01 Lima ', '43', '43 Villa María del Triunfo'),
(1495, '15', '15 Lima', '02', '02 Barranca ', '00', ' '),
(1496, '15', '15 Lima', '02', '02 Barranca ', '01', '01 Barranca'),
(1497, '15', '15 Lima', '02', '02 Barranca ', '02', '02 Paramonga'),
(1498, '15', '15 Lima', '02', '02 Barranca ', '03', '03 Pativilca'),
(1499, '15', '15 Lima', '02', '02 Barranca ', '04', '04 Supe'),
(1500, '15', '15 Lima', '02', '02 Barranca ', '05', '05 Supe Puerto'),
(1501, '15', '15 Lima', '03', '03 Cajatambo ', '00', ' '),
(1502, '15', '15 Lima', '03', '03 Cajatambo ', '01', '01 Cajatambo'),
(1503, '15', '15 Lima', '03', '03 Cajatambo ', '02', '02 Copa'),
(1504, '15', '15 Lima', '03', '03 Cajatambo ', '03', '03 Gorgor'),
(1505, '15', '15 Lima', '03', '03 Cajatambo ', '04', '04 Huancapon'),
(1507, '15', '15 Lima', '03', '03 Cajatambo ', '05', '05 Manas'),
(1508, '15', '15 Lima', '04', '04 Canta ', '00', ' '),
(1509, '15', '15 Lima', '04', '04 Canta ', '01', '01 Canta'),
(1510, '15', '15 Lima', '04', '04 Canta ', '02', '02 Arahuay'),
(1511, '15', '15 Lima', '04', '04 Canta ', '03', '03 Huamantanga'),
(1512, '15', '15 Lima', '04', '04 Canta ', '04', '04 Huaros'),
(1513, '15', '15 Lima', '04', '04 Canta ', '05', '05 Lachaqui'),
(1514, '15', '15 Lima', '04', '04 Canta ', '06', '06 San Buenaventura'),
(1515, '15', '15 Lima', '04', '04 Canta ', '07', '07 Santa Rosa de Quives'),
(1516, '15', '15 Lima', '05', '05 Cañete ', '00', ' '),
(1517, '15', '15 Lima', '05', '05 Cañete ', '01', '01 San Vicente de Cañete'),
(1518, '15', '15 Lima', '05', '05 Cañete ', '02', '02 Asia'),
(1519, '15', '15 Lima', '05', '05 Cañete ', '03', '03 Calango'),
(1520, '15', '15 Lima', '05', '05 Cañete ', '04', '04 Cerro Azul'),
(1521, '15', '15 Lima', '05', '05 Cañete ', '05', '05 Chilca'),
(1522, '15', '15 Lima', '05', '05 Cañete ', '06', '06 Coayllo'),
(1523, '15', '15 Lima', '05', '05 Cañete ', '07', '07 Imperial'),
(1524, '15', '15 Lima', '05', '05 Cañete ', '08', '08 Lunahuana'),
(1525, '15', '15 Lima', '05', '05 Cañete ', '09', '09 Mala'),
(1526, '15', '15 Lima', '05', '05 Cañete ', '10', '10 Nuevo Imperial'),
(1527, '15', '15 Lima', '05', '05 Cañete ', '11', '11 Pacaran'),
(1528, '15', '15 Lima', '05', '05 Cañete ', '12', '12 Quilmana'),
(1529, '15', '15 Lima', '05', '05 Cañete ', '13', '13 San Antonio'),
(1530, '15', '15 Lima', '05', '05 Cañete ', '14', '14 San Luis'),
(1531, '15', '15 Lima', '05', '05 Cañete ', '15', '15 Santa Cruz de Flores'),
(1532, '15', '15 Lima', '05', '05 Cañete ', '16', '16 Zúñiga'),
(1533, '15', '15 Lima', '06', '06 Huaral ', '00', ' '),
(1534, '15', '15 Lima', '06', '06 Huaral ', '01', '01 Huaral'),
(1535, '15', '15 Lima', '06', '06 Huaral ', '02', '02 Atavillos Alto'),
(1536, '15', '15 Lima', '06', '06 Huaral ', '03', '03 Atavillos Bajo'),
(1537, '15', '15 Lima', '06', '06 Huaral ', '04', '04 Aucallama'),
(1538, '15', '15 Lima', '06', '06 Huaral ', '05', '05 Chancay'),
(1539, '15', '15 Lima', '06', '06 Huaral ', '06', '06 Ihuari'),
(1540, '15', '15 Lima', '06', '06 Huaral ', '07', '07 Lampian'),
(1541, '15', '15 Lima', '06', '06 Huaral ', '08', '08 Pacaraos'),
(1542, '15', '15 Lima', '06', '06 Huaral ', '09', '09 San Miguel de Acos'),
(1543, '15', '15 Lima', '06', '06 Huaral ', '10', '10 Santa Cruz de Andamarca'),
(1544, '15', '15 Lima', '06', '06 Huaral ', '11', '11 Sumbilca'),
(1545, '15', '15 Lima', '06', '06 Huaral ', '12', '12 Veintisiete de Noviembre'),
(1546, '15', '15 Lima', '07', '07 Huarochirí ', '00', ' '),
(1547, '15', '15 Lima', '07', '07 Huarochirí ', '01', '01 Matucana'),
(1548, '15', '15 Lima', '07', '07 Huarochirí ', '02', '02 Antioquia'),
(1549, '15', '15 Lima', '07', '07 Huarochirí ', '03', '03 Callahuanca'),
(1550, '15', '15 Lima', '07', '07 Huarochirí ', '04', '04 Carampoma'),
(1551, '15', '15 Lima', '07', '07 Huarochirí ', '05', '05 Chicla'),
(1552, '15', '15 Lima', '07', '07 Huarochirí ', '06', '06 Cuenca'),
(1553, '15', '15 Lima', '07', '07 Huarochirí ', '07', '07 Huachupampa'),
(1554, '15', '15 Lima', '07', '07 Huarochirí ', '08', '08 Huanza'),
(1555, '15', '15 Lima', '07', '07 Huarochirí ', '09', '09 Huarochiri'),
(1556, '15', '15 Lima', '07', '07 Huarochirí ', '10', '10 Lahuaytambo'),
(1557, '15', '15 Lima', '07', '07 Huarochirí ', '11', '11 Langa'),
(1559, '15', '15 Lima', '07', '07 Huarochirí ', '12', '12 Laraos'),
(1560, '15', '15 Lima', '07', '07 Huarochirí ', '13', '13 Mariatana'),
(1561, '15', '15 Lima', '07', '07 Huarochirí ', '14', '14 Ricardo Palma'),
(1562, '15', '15 Lima', '07', '07 Huarochirí ', '15', '15 San Andrés de Tupicocha'),
(1563, '15', '15 Lima', '07', '07 Huarochirí ', '16', '16 San Antonio'),
(1564, '15', '15 Lima', '07', '07 Huarochirí ', '17', '17 San Bartolomé'),
(1565, '15', '15 Lima', '07', '07 Huarochirí ', '18', '18 San Damian'),
(1566, '15', '15 Lima', '07', '07 Huarochirí ', '19', '19 San Juan de Iris'),
(1567, '15', '15 Lima', '07', '07 Huarochirí ', '20', '20 San Juan de Tantaranche'),
(1568, '15', '15 Lima', '07', '07 Huarochirí ', '21', '21 San Lorenzo de Quinti'),
(1569, '15', '15 Lima', '07', '07 Huarochirí ', '22', '22 San Mateo'),
(1570, '15', '15 Lima', '07', '07 Huarochirí ', '23', '23 San Mateo de Otao'),
(1571, '15', '15 Lima', '07', '07 Huarochirí ', '24', '24 San Pedro de Casta'),
(1572, '15', '15 Lima', '07', '07 Huarochirí ', '25', '25 San Pedro de Huancayre'),
(1573, '15', '15 Lima', '07', '07 Huarochirí ', '26', '26 Sangallaya'),
(1574, '15', '15 Lima', '07', '07 Huarochirí ', '27', '27 Santa Cruz de Cocachacra'),
(1575, '15', '15 Lima', '07', '07 Huarochirí ', '28', '28 Santa Eulalia'),
(1576, '15', '15 Lima', '07', '07 Huarochirí ', '29', '29 Santiago de Anchucaya'),
(1577, '15', '15 Lima', '07', '07 Huarochirí ', '30', '30 Santiago de Tuna'),
(1578, '15', '15 Lima', '07', '07 Huarochirí ', '31', '31 Santo Domingo de Los Olleros'),
(1579, '15', '15 Lima', '07', '07 Huarochirí ', '32', '32 Surco'),
(1580, '15', '15 Lima', '08', '08 Huaura ', '00', ' '),
(1581, '15', '15 Lima', '08', '08 Huaura ', '01', '01 Huacho'),
(1582, '15', '15 Lima', '08', '08 Huaura ', '02', '02 Ambar'),
(1583, '15', '15 Lima', '08', '08 Huaura ', '03', '03 Caleta de Carquin'),
(1584, '15', '15 Lima', '08', '08 Huaura ', '04', '04 Checras'),
(1585, '15', '15 Lima', '08', '08 Huaura ', '05', '05 Hualmay'),
(1586, '15', '15 Lima', '08', '08 Huaura ', '06', '06 Huaura'),
(1587, '15', '15 Lima', '08', '08 Huaura ', '07', '07 Leoncio Prado'),
(1588, '15', '15 Lima', '08', '08 Huaura ', '08', '08 Paccho'),
(1589, '15', '15 Lima', '08', '08 Huaura ', '09', '09 Santa Leonor'),
(1590, '15', '15 Lima', '08', '08 Huaura ', '10', '10 Santa María'),
(1591, '15', '15 Lima', '08', '08 Huaura ', '11', '11 Sayan'),
(1592, '15', '15 Lima', '08', '08 Huaura ', '12', '12 Vegueta'),
(1593, '15', '15 Lima', '09', '09 Oyón ', '00', ' '),
(1594, '15', '15 Lima', '09', '09 Oyón ', '01', '01 Oyon'),
(1595, '15', '15 Lima', '09', '09 Oyón ', '02', '02 Andajes'),
(1596, '15', '15 Lima', '09', '09 Oyón ', '03', '03 Caujul'),
(1597, '15', '15 Lima', '09', '09 Oyón ', '04', '04 Cochamarca'),
(1598, '15', '15 Lima', '09', '09 Oyón ', '05', '05 Navan'),
(1599, '15', '15 Lima', '09', '09 Oyón ', '06', '06 Pachangara'),
(1600, '15', '15 Lima', '10', '10 Yauyos ', '00', ' '),
(1601, '15', '15 Lima', '10', '10 Yauyos ', '01', '01 Yauyos'),
(1602, '15', '15 Lima', '10', '10 Yauyos ', '02', '02 Alis'),
(1603, '15', '15 Lima', '10', '10 Yauyos ', '03', '03 Allauca'),
(1604, '15', '15 Lima', '10', '10 Yauyos ', '04', '04 Ayaviri'),
(1605, '15', '15 Lima', '10', '10 Yauyos ', '05', '05 Azángaro'),
(1606, '15', '15 Lima', '10', '10 Yauyos ', '06', '06 Cacra'),
(1607, '15', '15 Lima', '10', '10 Yauyos ', '07', '07 Carania'),
(1608, '15', '15 Lima', '10', '10 Yauyos ', '08', '08 Catahuasi'),
(1609, '15', '15 Lima', '10', '10 Yauyos ', '09', '09 Chocos'),
(1611, '15', '15 Lima', '10', '10 Yauyos ', '10', '10 Cochas'),
(1612, '15', '15 Lima', '10', '10 Yauyos ', '11', '11 Colonia'),
(1613, '15', '15 Lima', '10', '10 Yauyos ', '12', '12 Hongos'),
(1614, '15', '15 Lima', '10', '10 Yauyos ', '13', '13 Huampara'),
(1615, '15', '15 Lima', '10', '10 Yauyos ', '14', '14 Huancaya'),
(1616, '15', '15 Lima', '10', '10 Yauyos ', '15', '15 Huangascar'),
(1617, '15', '15 Lima', '10', '10 Yauyos ', '16', '16 Huantan'),
(1618, '15', '15 Lima', '10', '10 Yauyos ', '17', '17 Huañec'),
(1619, '15', '15 Lima', '10', '10 Yauyos ', '18', '18 Laraos'),
(1620, '15', '15 Lima', '10', '10 Yauyos ', '19', '19 Lincha'),
(1621, '15', '15 Lima', '10', '10 Yauyos ', '20', '20 Madean'),
(1622, '15', '15 Lima', '10', '10 Yauyos ', '21', '21 Miraflores'),
(1623, '15', '15 Lima', '10', '10 Yauyos ', '22', '22 Omas'),
(1624, '15', '15 Lima', '10', '10 Yauyos ', '23', '23 Putinza'),
(1625, '15', '15 Lima', '10', '10 Yauyos ', '24', '24 Quinches'),
(1626, '15', '15 Lima', '10', '10 Yauyos ', '25', '25 Quinocay'),
(1627, '15', '15 Lima', '10', '10 Yauyos ', '26', '26 San Joaquín'),
(1628, '15', '15 Lima', '10', '10 Yauyos ', '27', '27 San Pedro de Pilas'),
(1629, '15', '15 Lima', '10', '10 Yauyos ', '28', '28 Tanta'),
(1630, '15', '15 Lima', '10', '10 Yauyos ', '29', '29 Tauripampa'),
(1631, '15', '15 Lima', '10', '10 Yauyos ', '30', '30 Tomas'),
(1632, '15', '15 Lima', '10', '10 Yauyos ', '31', '31 Tupe'),
(1633, '15', '15 Lima', '10', '10 Yauyos ', '32', '32 Viñac'),
(1634, '15', '15 Lima', '10', '10 Yauyos ', '33', '33 Vitis'),
(1635, '16', '16 Loreto', '00', ' ', '00', ' '),
(1636, '16', '16 Loreto', '01', '01 Maynas ', '00', ' '),
(1637, '16', '16 Loreto', '01', '01 Maynas ', '01', '01 Iquitos'),
(1638, '16', '16 Loreto', '01', '01 Maynas ', '02', '02 Alto Nanay'),
(1639, '16', '16 Loreto', '01', '01 Maynas ', '03', '03 Fernando Lores'),
(1640, '16', '16 Loreto', '01', '01 Maynas ', '04', '04 Indiana'),
(1641, '16', '16 Loreto', '01', '01 Maynas ', '05', '05 Las Amazonas'),
(1642, '16', '16 Loreto', '01', '01 Maynas ', '06', '06 Mazan'),
(1643, '16', '16 Loreto', '01', '01 Maynas ', '07', '07 Napo'),
(1644, '16', '16 Loreto', '01', '01 Maynas ', '08', '08 Punchana'),
(1645, '16', '16 Loreto', '01', '01 Maynas ', '10', '10 Torres Causana'),
(1646, '16', '16 Loreto', '01', '01 Maynas ', '12', '12 Belén'),
(1647, '16', '16 Loreto', '01', '01 Maynas ', '13', '13 San Juan Bautista'),
(1648, '16', '16 Loreto', '02', '02 Alto Amazonas ', '00', ' '),
(1649, '16', '16 Loreto', '02', '02 Alto Amazonas ', '01', '01 Yurimaguas'),
(1650, '16', '16 Loreto', '02', '02 Alto Amazonas ', '02', '02 Balsapuerto'),
(1651, '16', '16 Loreto', '02', '02 Alto Amazonas ', '05', '05 Jeberos'),
(1652, '16', '16 Loreto', '02', '02 Alto Amazonas ', '06', '06 Lagunas'),
(1653, '16', '16 Loreto', '02', '02 Alto Amazonas ', '10', '10 Santa Cruz'),
(1654, '16', '16 Loreto', '02', '02 Alto Amazonas ', '11', '11 Teniente Cesar López Rojas'),
(1655, '16', '16 Loreto', '03', '03 Loreto ', '00', ' '),
(1656, '16', '16 Loreto', '03', '03 Loreto ', '01', '01 Nauta'),
(1657, '16', '16 Loreto', '03', '03 Loreto ', '02', '02 Parinari'),
(1658, '16', '16 Loreto', '03', '03 Loreto ', '03', '03 Tigre'),
(1659, '16', '16 Loreto', '03', '03 Loreto ', '04', '04 Trompeteros'),
(1660, '16', '16 Loreto', '03', '03 Loreto ', '05', '05 Urarinas'),
(1661, '16', '16 Loreto', '04', '04 Mariscal Ramón Castilla ', '00', ' '),
(1663, '16', '16 Loreto', '04', '04 Mariscal Ramón Castilla ', '01', '01 Ramón Castilla'),
(1664, '16', '16 Loreto', '04', '04 Mariscal Ramón Castilla ', '02', '02 Pebas'),
(1665, '16', '16 Loreto', '04', '04 Mariscal Ramón Castilla ', '03', '03 Yavari'),
(1666, '16', '16 Loreto', '04', '04 Mariscal Ramón Castilla ', '04', '04 San Pablo'),
(1667, '16', '16 Loreto', '05', '05 Requena ', '00', ' '),
(1668, '16', '16 Loreto', '05', '05 Requena ', '01', '01 Requena'),
(1669, '16', '16 Loreto', '05', '05 Requena ', '02', '02 Alto Tapiche'),
(1670, '16', '16 Loreto', '05', '05 Requena ', '03', '03 Capelo'),
(1671, '16', '16 Loreto', '05', '05 Requena ', '04', '04 Emilio San Martín'),
(1672, '16', '16 Loreto', '05', '05 Requena ', '05', '05 Maquia'),
(1673, '16', '16 Loreto', '05', '05 Requena ', '06', '06 Puinahua'),
(1674, '16', '16 Loreto', '05', '05 Requena ', '07', '07 Saquena'),
(1675, '16', '16 Loreto', '05', '05 Requena ', '08', '08 Soplin'),
(1676, '16', '16 Loreto', '05', '05 Requena ', '09', '09 Tapiche'),
(1677, '16', '16 Loreto', '05', '05 Requena ', '10', '10 Jenaro Herrera'),
(1678, '16', '16 Loreto', '05', '05 Requena ', '11', '11 Yaquerana'),
(1679, '16', '16 Loreto', '06', '06 Ucayali ', '00', ' '),
(1680, '16', '16 Loreto', '06', '06 Ucayali ', '01', '01 Contamana'),
(1681, '16', '16 Loreto', '06', '06 Ucayali ', '02', '02 Inahuaya'),
(1682, '16', '16 Loreto', '06', '06 Ucayali ', '03', '03 Padre Márquez'),
(1683, '16', '16 Loreto', '06', '06 Ucayali ', '04', '04 Pampa Hermosa'),
(1684, '16', '16 Loreto', '06', '06 Ucayali ', '05', '05 Sarayacu'),
(1685, '16', '16 Loreto', '06', '06 Ucayali ', '06', '06 Vargas Guerra'),
(1686, '16', '16 Loreto', '07', '07 Datem del Marañón ', '00', ' '),
(1687, '16', '16 Loreto', '07', '07 Datem del Marañón ', '01', '01 Barranca'),
(1688, '16', '16 Loreto', '07', '07 Datem del Marañón ', '02', '02 Cahuapanas'),
(1689, '16', '16 Loreto', '07', '07 Datem del Marañón ', '03', '03 Manseriche'),
(1690, '16', '16 Loreto', '07', '07 Datem del Marañón ', '04', '04 Morona'),
(1691, '16', '16 Loreto', '07', '07 Datem del Marañón ', '05', '05 Pastaza'),
(1692, '16', '16 Loreto', '07', '07 Datem del Marañón ', '06', '06 Andoas'),
(1693, '16', '16 Loreto', '08', '08 Putumayo', '00', ' '),
(1694, '16', '16 Loreto', '08', '08 Putumayo', '01', '01 Putumayo'),
(1695, '16', '16 Loreto', '08', '08 Putumayo', '02', '02 Rosa Panduro'),
(1696, '16', '16 Loreto', '08', '08 Putumayo', '03', '03 Teniente Manuel Clavero'),
(1697, '16', '16 Loreto', '08', '08 Putumayo', '04', '04 Yaguas'),
(1698, '17', '17 Madre de Dios', '00', ' ', '00', ' '),
(1699, '17', '17 Madre de Dios', '01', '01 Tambopata ', '00', ' '),
(1700, '17', '17 Madre de Dios', '01', '01 Tambopata ', '01', '01 Tambopata'),
(1701, '17', '17 Madre de Dios', '01', '01 Tambopata ', '02', '02 Inambari'),
(1702, '17', '17 Madre de Dios', '01', '01 Tambopata ', '03', '03 Las Piedras'),
(1703, '17', '17 Madre de Dios', '01', '01 Tambopata ', '04', '04 Laberinto'),
(1704, '17', '17 Madre de Dios', '02', '02 Manu ', '00', ' '),
(1705, '17', '17 Madre de Dios', '02', '02 Manu ', '01', '01 Manu'),
(1706, '17', '17 Madre de Dios', '02', '02 Manu ', '02', '02 Fitzcarrald'),
(1707, '17', '17 Madre de Dios', '02', '02 Manu ', '03', '03 Madre de Dios'),
(1708, '17', '17 Madre de Dios', '02', '02 Manu ', '04', '04 Huepetuhe'),
(1709, '17', '17 Madre de Dios', '03', '03 Tahuamanu ', '00', ' '),
(1710, '17', '17 Madre de Dios', '03', '03 Tahuamanu ', '01', '01 Iñapari'),
(1711, '17', '17 Madre de Dios', '03', '03 Tahuamanu ', '02', '02 Iberia'),
(1712, '17', '17 Madre de Dios', '03', '03 Tahuamanu ', '03', '03 Tahuamanu'),
(1713, '18', '18 Moquegua', '00', ' ', '00', ' '),
(1715, '18', '18 Moquegua', '01', '01 Mariscal Nieto ', '00', ' '),
(1716, '18', '18 Moquegua', '01', '01 Mariscal Nieto ', '01', '01 Moquegua'),
(1717, '18', '18 Moquegua', '01', '01 Mariscal Nieto ', '02', '02 Carumas'),
(1718, '18', '18 Moquegua', '01', '01 Mariscal Nieto ', '03', '03 Cuchumbaya'),
(1719, '18', '18 Moquegua', '01', '01 Mariscal Nieto ', '04', '04 Samegua'),
(1720, '18', '18 Moquegua', '01', '01 Mariscal Nieto ', '05', '05 San Cristóbal'),
(1721, '18', '18 Moquegua', '01', '01 Mariscal Nieto ', '06', '06 Torata'),
(1722, '18', '18 Moquegua', '02', '02 General Sánchez Cerro ', '00', ' '),
(1723, '18', '18 Moquegua', '02', '02 General Sánchez Cerro ', '01', '01 Omate'),
(1724, '18', '18 Moquegua', '02', '02 General Sánchez Cerro ', '02', '02 Chojata'),
(1725, '18', '18 Moquegua', '02', '02 General Sánchez Cerro ', '03', '03 Coalaque'),
(1726, '18', '18 Moquegua', '02', '02 General Sánchez Cerro ', '04', '04 Ichuña'),
(1727, '18', '18 Moquegua', '02', '02 General Sánchez Cerro ', '05', '05 La Capilla'),
(1728, '18', '18 Moquegua', '02', '02 General Sánchez Cerro ', '06', '06 Lloque'),
(1729, '18', '18 Moquegua', '02', '02 General Sánchez Cerro ', '07', '07 Matalaque'),
(1730, '18', '18 Moquegua', '02', '02 General Sánchez Cerro ', '08', '08 Puquina'),
(1731, '18', '18 Moquegua', '02', '02 General Sánchez Cerro ', '09', '09 Quinistaquillas'),
(1732, '18', '18 Moquegua', '02', '02 General Sánchez Cerro ', '10', '10 Ubinas'),
(1733, '18', '18 Moquegua', '02', '02 General Sánchez Cerro ', '11', '11 Yunga'),
(1734, '18', '18 Moquegua', '03', '03 Ilo ', '00', ' '),
(1735, '18', '18 Moquegua', '03', '03 Ilo ', '01', '01 Ilo'),
(1736, '18', '18 Moquegua', '03', '03 Ilo ', '02', '02 El Algarrobal'),
(1737, '18', '18 Moquegua', '03', '03 Ilo ', '03', '03 Pacocha'),
(1738, '19', '19 Pasco', '00', ' ', '00', ' '),
(1739, '19', '19 Pasco', '01', '01 Pasco ', '00', ' '),
(1740, '19', '19 Pasco', '01', '01 Pasco ', '01', '01 Chaupimarca'),
(1741, '19', '19 Pasco', '01', '01 Pasco ', '02', '02 Huachon'),
(1742, '19', '19 Pasco', '01', '01 Pasco ', '03', '03 Huariaca'),
(1743, '19', '19 Pasco', '01', '01 Pasco ', '04', '04 Huayllay'),
(1744, '19', '19 Pasco', '01', '01 Pasco ', '05', '05 Ninacaca'),
(1745, '19', '19 Pasco', '01', '01 Pasco ', '06', '06 Pallanchacra'),
(1746, '19', '19 Pasco', '01', '01 Pasco ', '07', '07 Paucartambo'),
(1747, '19', '19 Pasco', '01', '01 Pasco ', '08', '08 San Francisco de Asís de Yarusyacan'),
(1748, '19', '19 Pasco', '01', '01 Pasco ', '09', '09 Simon Bolívar'),
(1749, '19', '19 Pasco', '01', '01 Pasco ', '10', '10 Ticlacayan'),
(1750, '19', '19 Pasco', '01', '01 Pasco ', '11', '11 Tinyahuarco'),
(1751, '19', '19 Pasco', '01', '01 Pasco ', '12', '12 Vicco'),
(1752, '19', '19 Pasco', '01', '01 Pasco ', '13', '13 Yanacancha'),
(1753, '19', '19 Pasco', '02', '02 Daniel Alcides Carrión ', '00', ' '),
(1754, '19', '19 Pasco', '02', '02 Daniel Alcides Carrión ', '01', '01 Yanahuanca'),
(1755, '19', '19 Pasco', '02', '02 Daniel Alcides Carrión ', '02', '02 Chacayan'),
(1756, '19', '19 Pasco', '02', '02 Daniel Alcides Carrión ', '03', '03 Goyllarisquizga'),
(1757, '19', '19 Pasco', '02', '02 Daniel Alcides Carrión ', '04', '04 Paucar'),
(1758, '19', '19 Pasco', '02', '02 Daniel Alcides Carrión ', '05', '05 San Pedro de Pillao'),
(1759, '19', '19 Pasco', '02', '02 Daniel Alcides Carrión ', '06', '06 Santa Ana de Tusi'),
(1760, '19', '19 Pasco', '02', '02 Daniel Alcides Carrión ', '07', '07 Tapuc'),
(1761, '19', '19 Pasco', '02', '02 Daniel Alcides Carrión ', '08', '08 Vilcabamba'),
(1762, '19', '19 Pasco', '03', '03 Oxapampa ', '00', ' '),
(1763, '19', '19 Pasco', '03', '03 Oxapampa ', '01', '01 Oxapampa'),
(1764, '19', '19 Pasco', '03', '03 Oxapampa ', '02', '02 Chontabamba'),
(1765, '19', '19 Pasco', '03', '03 Oxapampa ', '03', '03 Huancabamba'),
(1767, '19', '19 Pasco', '03', '03 Oxapampa ', '04', '04 Palcazu'),
(1768, '19', '19 Pasco', '03', '03 Oxapampa ', '05', '05 Pozuzo'),
(1769, '19', '19 Pasco', '03', '03 Oxapampa ', '06', '06 Puerto Bermúdez'),
(1770, '19', '19 Pasco', '03', '03 Oxapampa ', '07', '07 Villa Rica'),
(1771, '19', '19 Pasco', '03', '03 Oxapampa ', '08', '08 Constitución'),
(1772, '20', '20 Piura', '00', ' ', '00', ' '),
(1773, '20', '20 Piura', '01', '01 Piura ', '00', ' '),
(1774, '20', '20 Piura', '01', '01 Piura ', '01', '01 Piura'),
(1775, '20', '20 Piura', '01', '01 Piura ', '04', '04 Castilla'),
(1776, '20', '20 Piura', '01', '01 Piura ', '05', '05 Catacaos'),
(1777, '20', '20 Piura', '01', '01 Piura ', '07', '07 Cura Mori'),
(1778, '20', '20 Piura', '01', '01 Piura ', '08', '08 El Tallan'),
(1779, '20', '20 Piura', '01', '01 Piura ', '09', '09 La Arena'),
(1780, '20', '20 Piura', '01', '01 Piura ', '10', '10 La Unión'),
(1781, '20', '20 Piura', '01', '01 Piura ', '11', '11 Las Lomas'),
(1782, '20', '20 Piura', '01', '01 Piura ', '14', '14 Tambo Grande'),
(1783, '20', '20 Piura', '01', '01 Piura ', '15', '15 Veintiseis de Octubre'),
(1784, '20', '20 Piura', '02', '02 Ayabaca ', '00', ' '),
(1785, '20', '20 Piura', '02', '02 Ayabaca ', '01', '01 Ayabaca'),
(1786, '20', '20 Piura', '02', '02 Ayabaca ', '02', '02 Frias'),
(1787, '20', '20 Piura', '02', '02 Ayabaca ', '03', '03 Jilili'),
(1788, '20', '20 Piura', '02', '02 Ayabaca ', '04', '04 Lagunas'),
(1789, '20', '20 Piura', '02', '02 Ayabaca ', '05', '05 Montero'),
(1790, '20', '20 Piura', '02', '02 Ayabaca ', '06', '06 Pacaipampa'),
(1791, '20', '20 Piura', '02', '02 Ayabaca ', '07', '07 Paimas'),
(1792, '20', '20 Piura', '02', '02 Ayabaca ', '08', '08 Sapillica'),
(1793, '20', '20 Piura', '02', '02 Ayabaca ', '09', '09 Sicchez'),
(1794, '20', '20 Piura', '02', '02 Ayabaca ', '10', '10 Suyo'),
(1795, '20', '20 Piura', '03', '03 Huancabamba ', '00', ' '),
(1796, '20', '20 Piura', '03', '03 Huancabamba ', '01', '01 Huancabamba'),
(1797, '20', '20 Piura', '03', '03 Huancabamba ', '02', '02 Canchaque'),
(1798, '20', '20 Piura', '03', '03 Huancabamba ', '03', '03 El Carmen de la Frontera'),
(1799, '20', '20 Piura', '03', '03 Huancabamba ', '04', '04 Huarmaca'),
(1800, '20', '20 Piura', '03', '03 Huancabamba ', '05', '05 Lalaquiz'),
(1801, '20', '20 Piura', '03', '03 Huancabamba ', '06', '06 San Miguel de El Faique'),
(1802, '20', '20 Piura', '03', '03 Huancabamba ', '07', '07 Sondor'),
(1803, '20', '20 Piura', '03', '03 Huancabamba ', '08', '08 Sondorillo'),
(1804, '20', '20 Piura', '04', '04 Morropón ', '00', ' '),
(1805, '20', '20 Piura', '04', '04 Morropón ', '01', '01 Chulucanas'),
(1806, '20', '20 Piura', '04', '04 Morropón ', '02', '02 Buenos Aires'),
(1807, '20', '20 Piura', '04', '04 Morropón ', '03', '03 Chalaco'),
(1808, '20', '20 Piura', '04', '04 Morropón ', '04', '04 La Matanza'),
(1809, '20', '20 Piura', '04', '04 Morropón ', '05', '05 Morropon'),
(1810, '20', '20 Piura', '04', '04 Morropón ', '06', '06 Salitral'),
(1811, '20', '20 Piura', '04', '04 Morropón ', '07', '07 San Juan de Bigote'),
(1812, '20', '20 Piura', '04', '04 Morropón ', '08', '08 Santa Catalina de Mossa'),
(1813, '20', '20 Piura', '04', '04 Morropón ', '09', '09 Santo Domingo'),
(1814, '20', '20 Piura', '04', '04 Morropón ', '10', '10 Yamango'),
(1815, '20', '20 Piura', '05', '05 Paita ', '00', ' '),
(1816, '20', '20 Piura', '05', '05 Paita ', '01', '01 Paita'),
(1817, '20', '20 Piura', '05', '05 Paita ', '02', '02 Amotape'),
(1819, '20', '20 Piura', '05', '05 Paita ', '03', '03 Arenal'),
(1820, '20', '20 Piura', '05', '05 Paita ', '04', '04 Colan'),
(1821, '20', '20 Piura', '05', '05 Paita ', '05', '05 La Huaca'),
(1822, '20', '20 Piura', '05', '05 Paita ', '06', '06 Tamarindo'),
(1823, '20', '20 Piura', '05', '05 Paita ', '07', '07 Vichayal'),
(1824, '20', '20 Piura', '06', '06 Sullana ', '00', ' '),
(1825, '20', '20 Piura', '06', '06 Sullana ', '01', '01 Sullana'),
(1826, '20', '20 Piura', '06', '06 Sullana ', '02', '02 Bellavista'),
(1827, '20', '20 Piura', '06', '06 Sullana ', '03', '03 Ignacio Escudero'),
(1828, '20', '20 Piura', '06', '06 Sullana ', '04', '04 Lancones'),
(1829, '20', '20 Piura', '06', '06 Sullana ', '05', '05 Marcavelica'),
(1830, '20', '20 Piura', '06', '06 Sullana ', '06', '06 Miguel Checa'),
(1831, '20', '20 Piura', '06', '06 Sullana ', '07', '07 Querecotillo'),
(1832, '20', '20 Piura', '06', '06 Sullana ', '08', '08 Salitral'),
(1833, '20', '20 Piura', '07', '07 Talara ', '00', ' '),
(1834, '20', '20 Piura', '07', '07 Talara ', '01', '01 Pariñas'),
(1835, '20', '20 Piura', '07', '07 Talara ', '02', '02 El Alto'),
(1836, '20', '20 Piura', '07', '07 Talara ', '03', '03 La Brea'),
(1837, '20', '20 Piura', '07', '07 Talara ', '04', '04 Lobitos'),
(1838, '20', '20 Piura', '07', '07 Talara ', '05', '05 Los Organos'),
(1839, '20', '20 Piura', '07', '07 Talara ', '06', '06 Mancora'),
(1840, '20', '20 Piura', '08', '08 Sechura ', '00', ' '),
(1841, '20', '20 Piura', '08', '08 Sechura ', '01', '01 Sechura'),
(1842, '20', '20 Piura', '08', '08 Sechura ', '02', '02 Bellavista de la Unión'),
(1843, '20', '20 Piura', '08', '08 Sechura ', '03', '03 Bernal'),
(1844, '20', '20 Piura', '08', '08 Sechura ', '04', '04 Cristo Nos Valga'),
(1845, '20', '20 Piura', '08', '08 Sechura ', '05', '05 Vice'),
(1846, '20', '20 Piura', '08', '08 Sechura ', '06', '06 Rinconada Llicuar'),
(1847, '21', '21 Puno', '00', ' ', '00', ' '),
(1848, '21', '21 Puno', '01', '01 Puno ', '00', ' '),
(1849, '21', '21 Puno', '01', '01 Puno ', '01', '01 Puno'),
(1850, '21', '21 Puno', '01', '01 Puno ', '02', '02 Acora'),
(1851, '21', '21 Puno', '01', '01 Puno ', '03', '03 Amantani'),
(1852, '21', '21 Puno', '01', '01 Puno ', '04', '04 Atuncolla'),
(1853, '21', '21 Puno', '01', '01 Puno ', '05', '05 Capachica'),
(1854, '21', '21 Puno', '01', '01 Puno ', '06', '06 Chucuito'),
(1855, '21', '21 Puno', '01', '01 Puno ', '07', '07 Coata'),
(1856, '21', '21 Puno', '01', '01 Puno ', '08', '08 Huata'),
(1857, '21', '21 Puno', '01', '01 Puno ', '09', '09 Mañazo'),
(1858, '21', '21 Puno', '01', '01 Puno ', '10', '10 Paucarcolla'),
(1859, '21', '21 Puno', '01', '01 Puno ', '11', '11 Pichacani'),
(1860, '21', '21 Puno', '01', '01 Puno ', '12', '12 Plateria'),
(1861, '21', '21 Puno', '01', '01 Puno ', '13', '13 San Antonio'),
(1862, '21', '21 Puno', '01', '01 Puno ', '14', '14 Tiquillaca'),
(1863, '21', '21 Puno', '01', '01 Puno ', '15', '15 Vilque'),
(1864, '21', '21 Puno', '02', '02 Azángaro ', '00', ' '),
(1865, '21', '21 Puno', '02', '02 Azángaro ', '01', '01 Azángaro'),
(1866, '21', '21 Puno', '02', '02 Azángaro ', '02', '02 Achaya'),
(1867, '21', '21 Puno', '02', '02 Azángaro ', '03', '03 Arapa'),
(1868, '21', '21 Puno', '02', '02 Azángaro ', '04', '04 Asillo'),
(1869, '21', '21 Puno', '02', '02 Azángaro ', '05', '05 Caminaca'),
(1871, '21', '21 Puno', '02', '02 Azángaro ', '06', '06 Chupa'),
(1872, '21', '21 Puno', '02', '02 Azángaro ', '07', '07 José Domingo Choquehuanca'),
(1873, '21', '21 Puno', '02', '02 Azángaro ', '08', '08 Muñani'),
(1874, '21', '21 Puno', '02', '02 Azángaro ', '09', '09 Potoni'),
(1875, '21', '21 Puno', '02', '02 Azángaro ', '10', '10 Saman'),
(1876, '21', '21 Puno', '02', '02 Azángaro ', '11', '11 San Anton'),
(1877, '21', '21 Puno', '02', '02 Azángaro ', '12', '12 San José'),
(1878, '21', '21 Puno', '02', '02 Azángaro ', '13', '13 San Juan de Salinas'),
(1879, '21', '21 Puno', '02', '02 Azángaro ', '14', '14 Santiago de Pupuja'),
(1880, '21', '21 Puno', '02', '02 Azángaro ', '15', '15 Tirapata'),
(1881, '21', '21 Puno', '03', '03 Carabaya ', '00', ' '),
(1882, '21', '21 Puno', '03', '03 Carabaya ', '01', '01 Macusani'),
(1883, '21', '21 Puno', '03', '03 Carabaya ', '02', '02 Ajoyani'),
(1884, '21', '21 Puno', '03', '03 Carabaya ', '03', '03 Ayapata'),
(1885, '21', '21 Puno', '03', '03 Carabaya ', '04', '04 Coasa'),
(1886, '21', '21 Puno', '03', '03 Carabaya ', '05', '05 Corani'),
(1887, '21', '21 Puno', '03', '03 Carabaya ', '06', '06 Crucero'),
(1888, '21', '21 Puno', '03', '03 Carabaya ', '07', '07 Ituata'),
(1889, '21', '21 Puno', '03', '03 Carabaya ', '08', '08 Ollachea'),
(1890, '21', '21 Puno', '03', '03 Carabaya ', '09', '09 San Gaban'),
(1891, '21', '21 Puno', '03', '03 Carabaya ', '10', '10 Usicayos'),
(1892, '21', '21 Puno', '04', '04 Chucuito ', '00', ' '),
(1893, '21', '21 Puno', '04', '04 Chucuito ', '01', '01 Juli'),
(1894, '21', '21 Puno', '04', '04 Chucuito ', '02', '02 Desaguadero'),
(1895, '21', '21 Puno', '04', '04 Chucuito ', '03', '03 Huacullani'),
(1896, '21', '21 Puno', '04', '04 Chucuito ', '04', '04 Kelluyo'),
(1897, '21', '21 Puno', '04', '04 Chucuito ', '05', '05 Pisacoma'),
(1898, '21', '21 Puno', '04', '04 Chucuito ', '06', '06 Pomata'),
(1899, '21', '21 Puno', '04', '04 Chucuito ', '07', '07 Zepita'),
(1900, '21', '21 Puno', '05', '05 El Collao ', '00', ' '),
(1901, '21', '21 Puno', '05', '05 El Collao ', '01', '01 Ilave'),
(1902, '21', '21 Puno', '05', '05 El Collao ', '02', '02 Capazo'),
(1903, '21', '21 Puno', '05', '05 El Collao ', '03', '03 Pilcuyo'),
(1904, '21', '21 Puno', '05', '05 El Collao ', '04', '04 Santa Rosa'),
(1905, '21', '21 Puno', '05', '05 El Collao ', '05', '05 Conduriri'),
(1906, '21', '21 Puno', '06', '06 Huancané ', '00', ' '),
(1907, '21', '21 Puno', '06', '06 Huancané ', '01', '01 Huancane'),
(1908, '21', '21 Puno', '06', '06 Huancané ', '02', '02 Cojata'),
(1909, '21', '21 Puno', '06', '06 Huancané ', '03', '03 Huatasani'),
(1910, '21', '21 Puno', '06', '06 Huancané ', '04', '04 Inchupalla'),
(1911, '21', '21 Puno', '06', '06 Huancané ', '05', '05 Pusi'),
(1912, '21', '21 Puno', '06', '06 Huancané ', '06', '06 Rosaspata'),
(1913, '21', '21 Puno', '06', '06 Huancané ', '07', '07 Taraco'),
(1914, '21', '21 Puno', '06', '06 Huancané ', '08', '08 Vilque Chico'),
(1915, '21', '21 Puno', '07', '07 Lampa ', '00', ' '),
(1916, '21', '21 Puno', '07', '07 Lampa ', '01', '01 Lampa'),
(1917, '21', '21 Puno', '07', '07 Lampa ', '02', '02 Cabanilla'),
(1918, '21', '21 Puno', '07', '07 Lampa ', '03', '03 Calapuja'),
(1919, '21', '21 Puno', '07', '07 Lampa ', '04', '04 Nicasio'),
(1920, '21', '21 Puno', '07', '07 Lampa ', '05', '05 Ocuviri'),
(1921, '21', '21 Puno', '07', '07 Lampa ', '06', '06 Palca'),
(1923, '21', '21 Puno', '07', '07 Lampa ', '07', '07 Paratia'),
(1924, '21', '21 Puno', '07', '07 Lampa ', '08', '08 Pucara'),
(1925, '21', '21 Puno', '07', '07 Lampa ', '09', '09 Santa Lucia'),
(1926, '21', '21 Puno', '07', '07 Lampa ', '10', '10 Vilavila'),
(1927, '21', '21 Puno', '08', '08 Melgar ', '00', ' '),
(1928, '21', '21 Puno', '08', '08 Melgar ', '01', '01 Ayaviri'),
(1929, '21', '21 Puno', '08', '08 Melgar ', '02', '02 Antauta'),
(1930, '21', '21 Puno', '08', '08 Melgar ', '03', '03 Cupi'),
(1931, '21', '21 Puno', '08', '08 Melgar ', '04', '04 Llalli'),
(1932, '21', '21 Puno', '08', '08 Melgar ', '05', '05 Macari'),
(1933, '21', '21 Puno', '08', '08 Melgar ', '06', '06 Nuñoa'),
(1934, '21', '21 Puno', '08', '08 Melgar ', '07', '07 Orurillo'),
(1935, '21', '21 Puno', '08', '08 Melgar ', '08', '08 Santa Rosa'),
(1936, '21', '21 Puno', '08', '08 Melgar ', '09', '09 Umachiri'),
(1937, '21', '21 Puno', '09', '09 Moho ', '00', ' '),
(1938, '21', '21 Puno', '09', '09 Moho ', '01', '01 Moho'),
(1939, '21', '21 Puno', '09', '09 Moho ', '02', '02 Conima'),
(1940, '21', '21 Puno', '09', '09 Moho ', '03', '03 Huayrapata'),
(1941, '21', '21 Puno', '09', '09 Moho ', '04', '04 Tilali'),
(1942, '21', '21 Puno', '10', '10 San Antonio de Putina ', '00', ' '),
(1943, '21', '21 Puno', '10', '10 San Antonio de Putina ', '01', '01 Putina'),
(1944, '21', '21 Puno', '10', '10 San Antonio de Putina ', '02', '02 Ananea'),
(1945, '21', '21 Puno', '10', '10 San Antonio de Putina ', '03', '03 Pedro Vilca Apaza'),
(1946, '21', '21 Puno', '10', '10 San Antonio de Putina ', '04', '04 Quilcapuncu'),
(1947, '21', '21 Puno', '10', '10 San Antonio de Putina ', '05', '05 Sina'),
(1948, '21', '21 Puno', '11', '11 San Román ', '00', ' '),
(1949, '21', '21 Puno', '11', '11 San Román ', '01', '01 Juliaca'),
(1950, '21', '21 Puno', '11', '11 San Román ', '02', '02 Cabana'),
(1951, '21', '21 Puno', '11', '11 San Román ', '03', '03 Cabanillas'),
(1952, '21', '21 Puno', '11', '11 San Román ', '04', '04 Caracoto'),
(1953, '21', '21 Puno', '11', '11 San Román ', '05', '05 San Miguel'),
(1954, '21', '21 Puno', '12', '12 Sandia ', '00', ' '),
(1955, '21', '21 Puno', '12', '12 Sandia ', '01', '01 Sandia'),
(1956, '21', '21 Puno', '12', '12 Sandia ', '02', '02 Cuyocuyo'),
(1957, '21', '21 Puno', '12', '12 Sandia ', '03', '03 Limbani'),
(1958, '21', '21 Puno', '12', '12 Sandia ', '04', '04 Patambuco'),
(1959, '21', '21 Puno', '12', '12 Sandia ', '05', '05 Phara'),
(1960, '21', '21 Puno', '12', '12 Sandia ', '06', '06 Quiaca'),
(1961, '21', '21 Puno', '12', '12 Sandia ', '07', '07 San Juan del Oro'),
(1962, '21', '21 Puno', '12', '12 Sandia ', '08', '08 Yanahuaya'),
(1963, '21', '21 Puno', '12', '12 Sandia ', '09', '09 Alto Inambari'),
(1964, '21', '21 Puno', '12', '12 Sandia ', '10', '10 San Pedro de Putina Punco'),
(1965, '21', '21 Puno', '13', '13 Yunguyo ', '00', ' '),
(1966, '21', '21 Puno', '13', '13 Yunguyo ', '01', '01 Yunguyo'),
(1967, '21', '21 Puno', '13', '13 Yunguyo ', '02', '02 Anapia'),
(1968, '21', '21 Puno', '13', '13 Yunguyo ', '03', '03 Copani'),
(1969, '21', '21 Puno', '13', '13 Yunguyo ', '04', '04 Cuturapi'),
(1970, '21', '21 Puno', '13', '13 Yunguyo ', '05', '05 Ollaraya'),
(1971, '21', '21 Puno', '13', '13 Yunguyo ', '06', '06 Tinicachi'),
(1972, '21', '21 Puno', '13', '13 Yunguyo ', '07', '07 Unicachi'),
(1973, '22', '22 San Martín', '00', ' ', '00', ' '),
(1975, '22', '22 San Martín', '01', '01 Moyobamba ', '00', ' '),
(1976, '22', '22 San Martín', '01', '01 Moyobamba ', '01', '01 Moyobamba'),
(1977, '22', '22 San Martín', '01', '01 Moyobamba ', '02', '02 Calzada'),
(1978, '22', '22 San Martín', '01', '01 Moyobamba ', '03', '03 Habana'),
(1979, '22', '22 San Martín', '01', '01 Moyobamba ', '04', '04 Jepelacio'),
(1980, '22', '22 San Martín', '01', '01 Moyobamba ', '05', '05 Soritor'),
(1981, '22', '22 San Martín', '01', '01 Moyobamba ', '06', '06 Yantalo'),
(1982, '22', '22 San Martín', '02', '02 Bellavista ', '00', ' '),
(1983, '22', '22 San Martín', '02', '02 Bellavista ', '01', '01 Bellavista'),
(1984, '22', '22 San Martín', '02', '02 Bellavista ', '02', '02 Alto Biavo'),
(1985, '22', '22 San Martín', '02', '02 Bellavista ', '03', '03 Bajo Biavo'),
(1986, '22', '22 San Martín', '02', '02 Bellavista ', '04', '04 Huallaga'),
(1987, '22', '22 San Martín', '02', '02 Bellavista ', '05', '05 San Pablo'),
(1988, '22', '22 San Martín', '02', '02 Bellavista ', '06', '06 San Rafael'),
(1989, '22', '22 San Martín', '03', '03 El Dorado ', '00', ' '),
(1990, '22', '22 San Martín', '03', '03 El Dorado ', '01', '01 San José de Sisa'),
(1991, '22', '22 San Martín', '03', '03 El Dorado ', '02', '02 Agua Blanca'),
(1992, '22', '22 San Martín', '03', '03 El Dorado ', '03', '03 San Martín'),
(1993, '22', '22 San Martín', '03', '03 El Dorado ', '04', '04 Santa Rosa'),
(1994, '22', '22 San Martín', '03', '03 El Dorado ', '05', '05 Shatoja'),
(1995, '22', '22 San Martín', '04', '04 Huallaga ', '00', ' '),
(1996, '22', '22 San Martín', '04', '04 Huallaga ', '01', '01 Saposoa'),
(1997, '22', '22 San Martín', '04', '04 Huallaga ', '02', '02 Alto Saposoa'),
(1998, '22', '22 San Martín', '04', '04 Huallaga ', '03', '03 El Eslabón'),
(1999, '22', '22 San Martín', '04', '04 Huallaga ', '04', '04 Piscoyacu'),
(2000, '22', '22 San Martín', '04', '04 Huallaga ', '05', '05 Sacanche'),
(2001, '22', '22 San Martín', '04', '04 Huallaga ', '06', '06 Tingo de Saposoa'),
(2002, '22', '22 San Martín', '05', '05 Lamas ', '00', ' '),
(2003, '22', '22 San Martín', '05', '05 Lamas ', '01', '01 Lamas'),
(2004, '22', '22 San Martín', '05', '05 Lamas ', '02', '02 Alonso de Alvarado'),
(2005, '22', '22 San Martín', '05', '05 Lamas ', '03', '03 Barranquita'),
(2006, '22', '22 San Martín', '05', '05 Lamas ', '04', '04 Caynarachi'),
(2007, '22', '22 San Martín', '05', '05 Lamas ', '05', '05 Cuñumbuqui'),
(2008, '22', '22 San Martín', '05', '05 Lamas ', '06', '06 Pinto Recodo'),
(2009, '22', '22 San Martín', '05', '05 Lamas ', '07', '07 Rumisapa'),
(2010, '22', '22 San Martín', '05', '05 Lamas ', '08', '08 San Roque de Cumbaza'),
(2011, '22', '22 San Martín', '05', '05 Lamas ', '09', '09 Shanao'),
(2012, '22', '22 San Martín', '05', '05 Lamas ', '10', '10 Tabalosos'),
(2013, '22', '22 San Martín', '05', '05 Lamas ', '11', '11 Zapatero'),
(2014, '22', '22 San Martín', '06', '06 Mariscal Cáceres ', '00', ' '),
(2015, '22', '22 San Martín', '06', '06 Mariscal Cáceres ', '01', '01 Juanjuí'),
(2016, '22', '22 San Martín', '06', '06 Mariscal Cáceres ', '02', '02 Campanilla'),
(2017, '22', '22 San Martín', '06', '06 Mariscal Cáceres ', '03', '03 Huicungo'),
(2018, '22', '22 San Martín', '06', '06 Mariscal Cáceres ', '04', '04 Pachiza'),
(2019, '22', '22 San Martín', '06', '06 Mariscal Cáceres ', '05', '05 Pajarillo'),
(2020, '22', '22 San Martín', '07', '07 Picota ', '00', ' '),
(2021, '22', '22 San Martín', '07', '07 Picota ', '01', '01 Picota'),
(2022, '22', '22 San Martín', '07', '07 Picota ', '02', '02 Buenos Aires'),
(2023, '22', '22 San Martín', '07', '07 Picota ', '03', '03 Caspisapa'),
(2024, '22', '22 San Martín', '07', '07 Picota ', '04', '04 Pilluana'),
(2025, '22', '22 San Martín', '07', '07 Picota ', '05', '05 Pucacaca'),
(2027, '22', '22 San Martín', '07', '07 Picota ', '06', '06 San Cristóbal'),
(2028, '22', '22 San Martín', '07', '07 Picota ', '07', '07 San Hilarión'),
(2029, '22', '22 San Martín', '07', '07 Picota ', '08', '08 Shamboyacu'),
(2030, '22', '22 San Martín', '07', '07 Picota ', '09', '09 Tingo de Ponasa'),
(2031, '22', '22 San Martín', '07', '07 Picota ', '10', '10 Tres Unidos'),
(2032, '22', '22 San Martín', '08', '08 Rioja ', '00', ' '),
(2033, '22', '22 San Martín', '08', '08 Rioja ', '01', '01 Rioja'),
(2034, '22', '22 San Martín', '08', '08 Rioja ', '02', '02 Awajun'),
(2035, '22', '22 San Martín', '08', '08 Rioja ', '03', '03 Elías Soplin Vargas'),
(2036, '22', '22 San Martín', '08', '08 Rioja ', '04', '04 Nueva Cajamarca'),
(2037, '22', '22 San Martín', '08', '08 Rioja ', '05', '05 Pardo Miguel'),
(2038, '22', '22 San Martín', '08', '08 Rioja ', '06', '06 Posic'),
(2039, '22', '22 San Martín', '08', '08 Rioja ', '07', '07 San Fernando'),
(2040, '22', '22 San Martín', '08', '08 Rioja ', '08', '08 Yorongos'),
(2041, '22', '22 San Martín', '08', '08 Rioja ', '09', '09 Yuracyacu'),
(2042, '22', '22 San Martín', '09', '09 San Martín ', '00', ' '),
(2043, '22', '22 San Martín', '09', '09 San Martín ', '01', '01 Tarapoto'),
(2044, '22', '22 San Martín', '09', '09 San Martín ', '02', '02 Alberto Leveau'),
(2045, '22', '22 San Martín', '09', '09 San Martín ', '03', '03 Cacatachi'),
(2046, '22', '22 San Martín', '09', '09 San Martín ', '04', '04 Chazuta'),
(2047, '22', '22 San Martín', '09', '09 San Martín ', '05', '05 Chipurana'),
(2048, '22', '22 San Martín', '09', '09 San Martín ', '06', '06 El Porvenir'),
(2049, '22', '22 San Martín', '09', '09 San Martín ', '07', '07 Huimbayoc'),
(2050, '22', '22 San Martín', '09', '09 San Martín ', '08', '08 Juan Guerra'),
(2051, '22', '22 San Martín', '09', '09 San Martín ', '09', '09 La Banda de Shilcayo'),
(2052, '22', '22 San Martín', '09', '09 San Martín ', '10', '10 Morales'),
(2053, '22', '22 San Martín', '09', '09 San Martín ', '11', '11 Papaplaya'),
(2054, '22', '22 San Martín', '09', '09 San Martín ', '12', '12 San Antonio'),
(2055, '22', '22 San Martín', '09', '09 San Martín ', '13', '13 Sauce'),
(2056, '22', '22 San Martín', '09', '09 San Martín ', '14', '14 Shapaja'),
(2057, '22', '22 San Martín', '10', '10 Tocache ', '00', ' '),
(2058, '22', '22 San Martín', '10', '10 Tocache ', '01', '01 Tocache'),
(2059, '22', '22 San Martín', '10', '10 Tocache ', '02', '02 Nuevo Progreso'),
(2060, '22', '22 San Martín', '10', '10 Tocache ', '03', '03 Polvora'),
(2061, '22', '22 San Martín', '10', '10 Tocache ', '04', '04 Shunte'),
(2062, '22', '22 San Martín', '10', '10 Tocache ', '05', '05 Uchiza'),
(2063, '23', '23 Tacna', '00', ' ', '00', ' '),
(2064, '23', '23 Tacna', '01', '01 Tacna ', '00', ' '),
(2065, '23', '23 Tacna', '01', '01 Tacna ', '01', '01 Tacna'),
(2066, '23', '23 Tacna', '01', '01 Tacna ', '02', '02 Alto de la Alianza'),
(2067, '23', '23 Tacna', '01', '01 Tacna ', '03', '03 Calana'),
(2068, '23', '23 Tacna', '01', '01 Tacna ', '04', '04 Ciudad Nueva'),
(2069, '23', '23 Tacna', '01', '01 Tacna ', '05', '05 Inclan'),
(2070, '23', '23 Tacna', '01', '01 Tacna ', '06', '06 Pachia'),
(2071, '23', '23 Tacna', '01', '01 Tacna ', '07', '07 Palca'),
(2072, '23', '23 Tacna', '01', '01 Tacna ', '08', '08 Pocollay'),
(2073, '23', '23 Tacna', '01', '01 Tacna ', '09', '09 Sama'),
(2074, '23', '23 Tacna', '01', '01 Tacna ', '10', '10 Coronel Gregorio Albarracín Lanchipa'),
(2075, '23', '23 Tacna', '01', '01 Tacna ', '11', '11 La Yarada los Palos'),
(2076, '23', '23 Tacna', '02', '02 Candarave ', '00', ' '),
(2077, '23', '23 Tacna', '02', '02 Candarave ', '01', '01 Candarave'),
(2079, '23', '23 Tacna', '02', '02 Candarave ', '02', '02 Cairani'),
(2080, '23', '23 Tacna', '02', '02 Candarave ', '03', '03 Camilaca'),
(2081, '23', '23 Tacna', '02', '02 Candarave ', '04', '04 Curibaya'),
(2082, '23', '23 Tacna', '02', '02 Candarave ', '05', '05 Huanuara'),
(2083, '23', '23 Tacna', '02', '02 Candarave ', '06', '06 Quilahuani'),
(2084, '23', '23 Tacna', '03', '03 Jorge Basadre ', '00', ' '),
(2085, '23', '23 Tacna', '03', '03 Jorge Basadre ', '01', '01 Locumba'),
(2086, '23', '23 Tacna', '03', '03 Jorge Basadre ', '02', '02 Ilabaya'),
(2087, '23', '23 Tacna', '03', '03 Jorge Basadre ', '03', '03 Ite'),
(2088, '23', '23 Tacna', '04', '04 Tarata ', '00', ' '),
(2089, '23', '23 Tacna', '04', '04 Tarata ', '01', '01 Tarata'),
(2090, '23', '23 Tacna', '04', '04 Tarata ', '02', '02 Héroes Albarracín'),
(2091, '23', '23 Tacna', '04', '04 Tarata ', '03', '03 Estique'),
(2092, '23', '23 Tacna', '04', '04 Tarata ', '04', '04 Estique-Pampa'),
(2093, '23', '23 Tacna', '04', '04 Tarata ', '05', '05 Sitajara'),
(2094, '23', '23 Tacna', '04', '04 Tarata ', '06', '06 Susapaya'),
(2095, '23', '23 Tacna', '04', '04 Tarata ', '07', '07 Tarucachi'),
(2096, '23', '23 Tacna', '04', '04 Tarata ', '08', '08 Ticaco'),
(2097, '24', '24 Tumbes', '00', ' ', '00', ' '),
(2098, '24', '24 Tumbes', '01', '01 Tumbes ', '00', ' '),
(2099, '24', '24 Tumbes', '01', '01 Tumbes ', '01', '01 Tumbes'),
(2100, '24', '24 Tumbes', '01', '01 Tumbes ', '02', '02 Corrales'),
(2101, '24', '24 Tumbes', '01', '01 Tumbes ', '03', '03 La Cruz'),
(2102, '24', '24 Tumbes', '01', '01 Tumbes ', '04', '04 Pampas de Hospital'),
(2103, '24', '24 Tumbes', '01', '01 Tumbes ', '05', '05 San Jacinto'),
(2104, '24', '24 Tumbes', '01', '01 Tumbes ', '06', '06 San Juan de la Virgen'),
(2105, '24', '24 Tumbes', '02', '02 Contralmirante Villar ', '00', ' '),
(2106, '24', '24 Tumbes', '02', '02 Contralmirante Villar ', '01', '01 Zorritos'),
(2107, '24', '24 Tumbes', '02', '02 Contralmirante Villar ', '02', '02 Casitas'),
(2108, '24', '24 Tumbes', '02', '02 Contralmirante Villar ', '03', '03 Canoas de Punta Sal'),
(2109, '24', '24 Tumbes', '03', '03 Zarumilla ', '00', ' '),
(2110, '24', '24 Tumbes', '03', '03 Zarumilla ', '01', '01 Zarumilla'),
(2111, '24', '24 Tumbes', '03', '03 Zarumilla ', '02', '02 Aguas Verdes'),
(2112, '24', '24 Tumbes', '03', '03 Zarumilla ', '03', '03 Matapalo'),
(2113, '24', '24 Tumbes', '03', '03 Zarumilla ', '04', '04 Papayal'),
(2114, '25', '25 Ucayali', '00', ' ', '00', ' '),
(2115, '25', '25 Ucayali', '01', '01 Coronel Portillo ', '00', ' '),
(2116, '25', '25 Ucayali', '01', '01 Coronel Portillo ', '01', '01 Calleria'),
(2117, '25', '25 Ucayali', '01', '01 Coronel Portillo ', '02', '02 Campoverde'),
(2118, '25', '25 Ucayali', '01', '01 Coronel Portillo ', '03', '03 Iparia'),
(2119, '25', '25 Ucayali', '01', '01 Coronel Portillo ', '04', '04 Masisea'),
(2120, '25', '25 Ucayali', '01', '01 Coronel Portillo ', '05', '05 Yarinacocha'),
(2121, '25', '25 Ucayali', '01', '01 Coronel Portillo ', '06', '06 Nueva Requena'),
(2122, '25', '25 Ucayali', '01', '01 Coronel Portillo ', '07', '07 Manantay'),
(2123, '25', '25 Ucayali', '02', '02 Atalaya ', '00', ' '),
(2124, '25', '25 Ucayali', '02', '02 Atalaya ', '01', '01 Raymondi'),
(2125, '25', '25 Ucayali', '02', '02 Atalaya ', '02', '02 Sepahua'),
(2126, '25', '25 Ucayali', '02', '02 Atalaya ', '03', '03 Tahuania'),
(2127, '25', '25 Ucayali', '02', '02 Atalaya ', '04', '04 Yurua'),
(2128, '25', '25 Ucayali', '03', '03 Padre Abad ', '00', ' '),
(2129, '25', '25 Ucayali', '03', '03 Padre Abad ', '01', '01 Padre Abad'),
(2131, '25', '25 Ucayali', '03', '03 Padre Abad ', '02', '02 Irazola'),
(2132, '25', '25 Ucayali', '03', '03 Padre Abad ', '03', '03 Curimana'),
(2133, '25', '25 Ucayali', '03', '03 Padre Abad ', '04', '04 Neshuya'),
(2134, '25', '25 Ucayali', '03', '03 Padre Abad ', '05', '05 Alexander Von Humboldt'),
(2135, '25', '25 Ucayali', '04', '04 Purús', '00', ' '),
(2136, '25', '25 Ucayali', '04', '04 Purús', '01', '01 Purus');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'geanbaila', 'Gean Baila', 'geanbaila@gmail.com', NULL, '$2y$10$T4ScUC7Zolehg47BoNKCCOvF0X5cZD8AZagLqSz.F0ZhLbbkbS7wO', 'cMvUXueNx1cYKy2TLePMmYWzTjwue1J43Dg6JLaXKKPuCvh2sAqy7K1l3D0V', '2021-12-27 05:30:25', '2021-12-28 04:32:02'),
(2, 'gbaila', 'Test', 'geanbaila@gmail.com', NULL, '$2y$10$izshBki1gGZlq09aBL1hb.nZ3r6a8XXZ4S/u.Y8XUPo33CzjhE5iu', NULL, '2022-01-16 14:01:12', '2022-01-16 14:01:12'),
(3, 'geanbaila@gmail.com', 'Gean Baila', 'geanbaila@gmail.com', NULL, '$2y$10$kAMikb4eZQbgrb9eU1zDGuWTCXFbHbVDsSjQQSt4iaxpDvaxAF6a.', NULL, '2022-03-29 01:16:30', '2022-03-29 01:16:30');

-- --------------------------------------------------------

--
-- Table structure for table `z_b001`
--

CREATE TABLE `z_b001` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `z_b001`
--

INSERT INTO `z_b001` (`id`) VALUES
(1),
(2),
(3),
(4),
(5);

-- --------------------------------------------------------

--
-- Table structure for table `z_b002`
--

CREATE TABLE `z_b002` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `z_b011`
--

CREATE TABLE `z_b011` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `z_b011`
--

INSERT INTO `z_b011` (`id`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13),
(14),
(15),
(16),
(17),
(18);

-- --------------------------------------------------------

--
-- Table structure for table `z_b012`
--

CREATE TABLE `z_b012` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `z_b012`
--

INSERT INTO `z_b012` (`id`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13),
(14),
(15),
(16),
(17),
(18),
(19),
(20),
(21),
(22),
(23),
(24),
(25),
(26),
(27),
(28),
(29),
(30),
(31),
(32),
(33),
(34),
(35),
(36),
(37),
(38),
(39),
(40),
(41),
(42);

-- --------------------------------------------------------

--
-- Table structure for table `z_b013`
--

CREATE TABLE `z_b013` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `z_b013`
--

INSERT INTO `z_b013` (`id`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13),
(14),
(15),
(16),
(17),
(18),
(19);

-- --------------------------------------------------------

--
-- Table structure for table `z_f001`
--

CREATE TABLE `z_f001` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `z_f001`
--

INSERT INTO `z_f001` (`id`) VALUES
(1),
(2),
(3),
(4),
(5);

-- --------------------------------------------------------

--
-- Table structure for table `z_f002`
--

CREATE TABLE `z_f002` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `z_f002`
--

INSERT INTO `z_f002` (`id`) VALUES
(1);

-- --------------------------------------------------------

--
-- Table structure for table `z_f011`
--

CREATE TABLE `z_f011` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `z_f011`
--

INSERT INTO `z_f011` (`id`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13),
(14),
(15),
(16),
(17),
(18),
(19),
(20),
(21),
(22),
(23),
(24),
(25),
(26),
(27),
(28),
(29),
(30),
(31),
(32),
(33),
(34),
(35),
(36);

-- --------------------------------------------------------

--
-- Table structure for table `z_f012`
--

CREATE TABLE `z_f012` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `z_f012`
--

INSERT INTO `z_f012` (`id`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13),
(14),
(15),
(16),
(17),
(18),
(19),
(20),
(21),
(22),
(23),
(24),
(25),
(26),
(27),
(28),
(29),
(30);

-- --------------------------------------------------------

--
-- Table structure for table `z_f013`
--

CREATE TABLE `z_f013` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `z_f013`
--

INSERT INTO `z_f013` (`id`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10);

-- --------------------------------------------------------

--
-- Table structure for table `z_g001`
--

CREATE TABLE `z_g001` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `z_g001`
--

INSERT INTO `z_g001` (`id`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13),
(14),
(15),
(16),
(17),
(18),
(19),
(20);

-- --------------------------------------------------------

--
-- Table structure for table `z_g002`
--

CREATE TABLE `z_g002` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `z_g002`
--

INSERT INTO `z_g002` (`id`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13),
(14),
(15),
(16),
(18),
(19),
(20),
(21),
(22),
(23);

-- --------------------------------------------------------

--
-- Table structure for table `z_g011`
--

CREATE TABLE `z_g011` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `z_g012`
--

CREATE TABLE `z_g012` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `z_g012`
--

INSERT INTO `z_g012` (`id`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7);

-- --------------------------------------------------------

--
-- Table structure for table `z_g013`
--

CREATE TABLE `z_g013` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `z_g013`
--

INSERT INTO `z_g013` (`id`) VALUES
(1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adquiriente`
--
ALTER TABLE `adquiriente`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agencia`
--
ALTER TABLE `agencia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documento`
--
ALTER TABLE `documento`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `encargo`
--
ALTER TABLE `encargo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `encargo_detalle`
--
ALTER TABLE `encargo_detalle`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `encargo_estado`
--
ALTER TABLE `encargo_estado`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guia_transportista`
--
ALTER TABLE `guia_transportista`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guia_transportista_detalle`
--
ALTER TABLE `guia_transportista_detalle`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manifiesto`
--
ALTER TABLE `manifiesto`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manifiesto_detalle`
--
ALTER TABLE `manifiesto_detalle`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `sede`
--
ALTER TABLE `sede`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `serie`
--
ALTER TABLE `serie`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tipo_afectacion`
--
ALTER TABLE `tipo_afectacion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ubigeo2016`
--
ALTER TABLE `ubigeo2016`
  ADD PRIMARY KEY (`id`),
  ADD KEY `codigo_departamento` (`codigo_departamento`),
  ADD KEY `codigo_provincia` (`codigo_provincia`),
  ADD KEY `codigo_distrito` (`codigo_distrito`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- Indexes for table `z_b001`
--
ALTER TABLE `z_b001`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `z_b002`
--
ALTER TABLE `z_b002`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `z_b011`
--
ALTER TABLE `z_b011`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `z_b012`
--
ALTER TABLE `z_b012`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `z_b013`
--
ALTER TABLE `z_b013`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `z_f001`
--
ALTER TABLE `z_f001`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `z_f002`
--
ALTER TABLE `z_f002`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `z_f011`
--
ALTER TABLE `z_f011`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `z_f012`
--
ALTER TABLE `z_f012`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `z_f013`
--
ALTER TABLE `z_f013`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `z_g001`
--
ALTER TABLE `z_g001`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `z_g002`
--
ALTER TABLE `z_g002`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `z_g011`
--
ALTER TABLE `z_g011`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `z_g012`
--
ALTER TABLE `z_g012`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `z_g013`
--
ALTER TABLE `z_g013`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adquiriente`
--
ALTER TABLE `adquiriente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `agencia`
--
ALTER TABLE `agencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `documento`
--
ALTER TABLE `documento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `encargo`
--
ALTER TABLE `encargo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `encargo_detalle`
--
ALTER TABLE `encargo_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `encargo_estado`
--
ALTER TABLE `encargo_estado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guia_transportista`
--
ALTER TABLE `guia_transportista`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `guia_transportista_detalle`
--
ALTER TABLE `guia_transportista_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `manifiesto`
--
ALTER TABLE `manifiesto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `manifiesto_detalle`
--
ALTER TABLE `manifiesto_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sede`
--
ALTER TABLE `sede`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `serie`
--
ALTER TABLE `serie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `tipo_afectacion`
--
ALTER TABLE `tipo_afectacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ubigeo2016`
--
ALTER TABLE `ubigeo2016`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2137;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `z_b001`
--
ALTER TABLE `z_b001`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `z_b002`
--
ALTER TABLE `z_b002`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `z_b011`
--
ALTER TABLE `z_b011`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `z_b012`
--
ALTER TABLE `z_b012`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `z_b013`
--
ALTER TABLE `z_b013`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `z_f001`
--
ALTER TABLE `z_f001`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `z_f002`
--
ALTER TABLE `z_f002`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `z_f011`
--
ALTER TABLE `z_f011`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `z_f012`
--
ALTER TABLE `z_f012`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `z_f013`
--
ALTER TABLE `z_f013`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `z_g001`
--
ALTER TABLE `z_g001`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `z_g002`
--
ALTER TABLE `z_g002`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `z_g011`
--
ALTER TABLE `z_g011`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `z_g012`
--
ALTER TABLE `z_g012`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `z_g013`
--
ALTER TABLE `z_g013`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
