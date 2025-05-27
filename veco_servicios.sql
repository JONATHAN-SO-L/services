-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-05-2025 a las 23:33:07
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
-- Base de datos: `veco_servicios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contadores`
--

CREATE TABLE `contadores` (
  `id_contador` int(10) UNSIGNED NOT NULL,
  `descripcion_nombre` text NOT NULL,
  `modelo_ci` text NOT NULL,
  `numero_serie` text NOT NULL,
  `identificacion_cliente` text NOT NULL,
  `numero_control` text NOT NULL,
  `rango` text NOT NULL,
  `frecuencia_calibracion` text NOT NULL,
  `vigencia_inicial` text NOT NULL,
  `vigencia_final` text NOT NULL,
  `estado` text NOT NULL,
  `area_asignada` text NOT NULL,
  `comentarios` text DEFAULT NULL,
  `registra_data` text NOT NULL,
  `fecha_hora_registro` text NOT NULL,
  `modifica_data` text DEFAULT NULL,
  `fecha_hora_modificacion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fdv_s_032`
--

CREATE TABLE `fdv_s_032` (
  `id_folio` int(10) UNSIGNED NOT NULL,
  `empresa` text NOT NULL,
  `direccion` text NOT NULL,
  `modelo_contador` text DEFAULT NULL,
  `intervalo_calibracion` text DEFAULT NULL,
  `id_documento` text DEFAULT NULL,
  `condicion_recepcion` text DEFAULT NULL,
  `condicion_calibracion` text DEFAULT NULL,
  `condicion_calibracion_final` text DEFAULT NULL,
  `comentarios` text DEFAULT NULL,
  `modelo_ci` text DEFAULT NULL,
  `numero_serie` text DEFAULT NULL,
  `fecha_calibracion` text DEFAULT NULL,
  `identificacion_cliente` text DEFAULT NULL,
  `presion_barometrica` text DEFAULT NULL,
  `temperatura` text DEFAULT NULL,
  `humedad_relativa` text DEFAULT NULL,
  `vl_esperado` text DEFAULT NULL,
  `vl_tolerancia` text DEFAULT NULL,
  `vl_condicion_encontrada` text DEFAULT NULL,
  `vl_pasa` text DEFAULT NULL,
  `vl_condicion_final` text DEFAULT NULL,
  `fa_esperado` text DEFAULT NULL,
  `fa_tolerancia` text DEFAULT NULL,
  `fa_condicion_encontrada` text DEFAULT NULL,
  `fa_pasa` text DEFAULT NULL,
  `fa_condicion_final` text DEFAULT NULL,
  `rm_esperado` text DEFAULT NULL,
  `rm_tolerancia` text DEFAULT NULL,
  `rm_condicion_encontrada` text DEFAULT NULL,
  `rm_pasa` text DEFAULT NULL,
  `rm_condicion_final` text DEFAULT NULL,
  `flujo_volumetrico` text DEFAULT NULL,
  `amplitud_esperada_03` text DEFAULT NULL,
  `tolerancia_03` text DEFAULT NULL,
  `como_encuentra_03` text DEFAULT NULL,
  `pasa_03` varchar(100) DEFAULT NULL,
  `condicion_final_03` text DEFAULT NULL,
  `amplitud_esperada_05` text DEFAULT NULL,
  `tolerancia_05` text DEFAULT NULL,
  `como_encuentra_05` text DEFAULT NULL,
  `pasa_05` text DEFAULT NULL,
  `condicion_final_05` text DEFAULT NULL,
  `amplitud_esperada_10` text DEFAULT NULL,
  `tolerancia_10` text DEFAULT NULL,
  `como_encuentra_10` text DEFAULT NULL,
  `pasa_10` text DEFAULT NULL,
  `condicion_final_10` text DEFAULT NULL,
  `amplitud_esperada_50` text DEFAULT NULL,
  `tolerancia_50` text DEFAULT NULL,
  `como_encuentra_50` text DEFAULT NULL,
  `pasa_50` text DEFAULT NULL,
  `condicion_final_50` text DEFAULT NULL,
  `amplitud_esperada_05_100` text DEFAULT NULL,
  `tolerancia_05_100` text DEFAULT NULL,
  `como_encuentra_05_100` text DEFAULT NULL,
  `pasa_05_100` text DEFAULT NULL,
  `condicion_final_05_100` text DEFAULT NULL,
  `amplitud_esperada_10_100` text DEFAULT NULL,
  `tolerancia_10_100` text DEFAULT NULL,
  `como_encuentra_10_100` text DEFAULT NULL,
  `pasa_10_100` text DEFAULT NULL,
  `condicion_final_10_100` text DEFAULT NULL,
  `amplitud_esperada_30_100` text DEFAULT NULL,
  `tolerancia_30_100` text DEFAULT NULL,
  `pasa_30_100` text DEFAULT NULL,
  `condicion_final_30_100` text DEFAULT NULL,
  `amplitud_esperada_50_100` text DEFAULT NULL,
  `tolerancia_50_100` text DEFAULT NULL,
  `como_encuentra_50_100` text DEFAULT NULL,
  `pasa_50_100` text DEFAULT NULL,
  `condicion_final_50_100` text DEFAULT NULL,
  `fecha_documento` text DEFAULT NULL,
  `dmm_activo` text DEFAULT NULL,
  `dmm_modelo` text DEFAULT NULL,
  `dmm_numero_serie` text DEFAULT NULL,
  `dmm_numero_control` text DEFAULT NULL,
  `dmm_fecha_calibracion` text DEFAULT NULL,
  `dmm_proxima_calibracion` text DEFAULT NULL,
  `pha_activo` text DEFAULT NULL,
  `pha_modelo` text DEFAULT NULL,
  `pha_numero_serie` text DEFAULT NULL,
  `pha_numero_control` text DEFAULT NULL,
  `pha_fecha_calibracion` text DEFAULT NULL,
  `pha_proxima_calibracion` text DEFAULT NULL,
  `mfm_activo` text DEFAULT NULL,
  `mfm_modelo` text DEFAULT NULL,
  `mfm_numero_serie` text DEFAULT NULL,
  `mfm_numero_control` text DEFAULT NULL,
  `mfm_fecha_calibracion` text DEFAULT NULL,
  `mfm_proxima_calibracion` text DEFAULT NULL,
  `rh_activo` text DEFAULT NULL,
  `rh_modelo` text DEFAULT NULL,
  `rh_numero_serie` text DEFAULT NULL,
  `rh_numero_control` text DEFAULT NULL,
  `rh_fecha_calibracion` text DEFAULT NULL,
  `rh_proxima_calibracion` text DEFAULT NULL,
  `balometro_activo` text DEFAULT NULL,
  `balometro_modelo` text DEFAULT NULL,
  `balometro_numero_serie` text DEFAULT NULL,
  `balometro_numero_control` text DEFAULT NULL,
  `balometro_fecha_calibracion` text DEFAULT NULL,
  `balometro_proxima_calibracion` text DEFAULT NULL,
  `tamano_real_03` text DEFAULT NULL,
  `desviacion_tamano_03` text DEFAULT NULL,
  `no_lote_03` text DEFAULT NULL,
  `exp_fecha_03` text DEFAULT NULL,
  `tamano_real_04` text DEFAULT NULL,
  `desviacion_tamano_04` text DEFAULT NULL,
  `no_lote_04` text DEFAULT NULL,
  `exp_fecha_04` text DEFAULT NULL,
  `tamano_real_05` text DEFAULT NULL,
  `desviacion_tamano_05` text DEFAULT NULL,
  `no_lote_05` text DEFAULT NULL,
  `exp_fecha_05` text DEFAULT NULL,
  `tamano_real_06` text DEFAULT NULL,
  `desviacion_tamano_06` text DEFAULT NULL,
  `no_lote_06` text DEFAULT NULL,
  `exp_fecha_06` text DEFAULT NULL,
  `tamano_real_08` text DEFAULT NULL,
  `desviacion_tamano_08` text DEFAULT NULL,
  `no_lote_08` text DEFAULT NULL,
  `exp_fecha_08` text DEFAULT NULL,
  `tamano_real_10` text DEFAULT NULL,
  `desviacion_tamano_10` text DEFAULT NULL,
  `no_lote_10` text DEFAULT NULL,
  `exp_fecha_10` text DEFAULT NULL,
  `tamano_real_30` text DEFAULT NULL,
  `desviacion_tamano_30` text DEFAULT NULL,
  `no_lote_30` text DEFAULT NULL,
  `exp_fecha_30` text DEFAULT NULL,
  `tamano_real_50` text DEFAULT NULL,
  `desviacion_tamano_50` text DEFAULT NULL,
  `no_lote_50` text DEFAULT NULL,
  `exp_fecha_50` text DEFAULT NULL,
  `tecnico` text DEFAULT NULL,
  `fecha_hora_registro` text DEFAULT NULL,
  `modifica_data` text DEFAULT NULL,
  `fecha_hora_modificacion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formatos`
--

CREATE TABLE `formatos` (
  `id_movimiento` int(10) UNSIGNED NOT NULL,
  `formato` text NOT NULL,
  `nombre_formato` text NOT NULL,
  `revision_formato` text NOT NULL,
  `url` text NOT NULL,
  `registra_data` text NOT NULL,
  `fecha_hora_registro` text NOT NULL,
  `modifica_data` text DEFAULT NULL,
  `fecha_hora_modificacion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instrumentos`
--

CREATE TABLE `instrumentos` (
  `id_instrumento` int(10) UNSIGNED NOT NULL,
  `activo` text NOT NULL,
  `modelo` text NOT NULL,
  `numero_serie` text NOT NULL,
  `numero_control` text NOT NULL,
  `fecha_calibracion` text NOT NULL,
  `fecha_proxima_calibracion` text NOT NULL,
  `tipo_instrumento` text NOT NULL,
  `registra_data` text NOT NULL,
  `fecha_hora_registro` text NOT NULL,
  `estado` text NOT NULL,
  `modifica_data` text DEFAULT NULL,
  `fecha_hora_modificacion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `particulas`
--

CREATE TABLE `particulas` (
  `id_particula` int(10) UNSIGNED NOT NULL,
  `tamano_real_03` text NOT NULL,
  `desviacion_tamano_03` text NOT NULL,
  `no_lote_03` text NOT NULL,
  `exp_fecha_03` text NOT NULL,
  `tamano_real_04` text NOT NULL,
  `desviacion_tamano_04` text NOT NULL,
  `no_lote_04` text NOT NULL,
  `exp_fecha_04` text NOT NULL,
  `tamano_real_05` text NOT NULL,
  `desviacion_tamano_05` text NOT NULL,
  `no_lote_05` text NOT NULL,
  `exp_fecha_05` text NOT NULL,
  `tamano_real_06` text NOT NULL,
  `desviacion_tamano_06` text NOT NULL,
  `no_lote_06` text NOT NULL,
  `exp_fecha_06` text NOT NULL,
  `tamano_real_08` text NOT NULL,
  `desviacion_tamano_08` text NOT NULL,
  `no_lote_08` text NOT NULL,
  `exp_fecha_08` text NOT NULL,
  `tamano_real_10` text NOT NULL,
  `desviacion_tamano_10` text NOT NULL,
  `no_lote_10` text NOT NULL,
  `exp_fecha_10` text NOT NULL,
  `tamano_real_30` text NOT NULL,
  `desviacion_tamano_30` text NOT NULL,
  `no_lote_30` text NOT NULL,
  `exp_fecha_30` text NOT NULL,
  `tamano_real_50` text NOT NULL,
  `desviacion_tamano_50` text NOT NULL,
  `no_lote_50` text NOT NULL,
  `exp_fecha_50` text NOT NULL,
  `registra_data` text NOT NULL,
  `fecha_hora_registro` text NOT NULL,
  `estado` text NOT NULL,
  `modifica_data` text DEFAULT NULL,
  `fecha_hora_modificacion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `contadores`
--
ALTER TABLE `contadores`
  ADD PRIMARY KEY (`id_contador`);

--
-- Indices de la tabla `fdv_s_032`
--
ALTER TABLE `fdv_s_032`
  ADD PRIMARY KEY (`id_folio`);

--
-- Indices de la tabla `formatos`
--
ALTER TABLE `formatos`
  ADD PRIMARY KEY (`id_movimiento`);

--
-- Indices de la tabla `instrumentos`
--
ALTER TABLE `instrumentos`
  ADD PRIMARY KEY (`id_instrumento`);

--
-- Indices de la tabla `particulas`
--
ALTER TABLE `particulas`
  ADD PRIMARY KEY (`id_particula`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `contadores`
--
ALTER TABLE `contadores`
  MODIFY `id_contador` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fdv_s_032`
--
ALTER TABLE `fdv_s_032`
  MODIFY `id_folio` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `formatos`
--
ALTER TABLE `formatos`
  MODIFY `id_movimiento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `instrumentos`
--
ALTER TABLE `instrumentos`
  MODIFY `id_instrumento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `particulas`
--
ALTER TABLE `particulas`
  MODIFY `id_particula` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
