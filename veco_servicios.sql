-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: veco_servicios
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `contadores`
--

DROP TABLE IF EXISTS `contadores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contadores` (
  `id_contador` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
  `fecha_hora_modificacion` text DEFAULT NULL,
  PRIMARY KEY (`id_contador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contadores`
--

LOCK TABLES `contadores` WRITE;
/*!40000 ALTER TABLE `contadores` DISABLE KEYS */;
/*!40000 ALTER TABLE `contadores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fdv-s-032`
--

DROP TABLE IF EXISTS `fdv-s-032`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fdv-s-032` (
  `id_folio` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `empresa` text NOT NULL,
  `direccion` text NOT NULL,
  `modelo_contador` text NOT NULL,
  `intervalo_calibracion` text NOT NULL,
  `id_documento` text NOT NULL,
  `condicion_recepcion` text NOT NULL,
  `condicion_calibracion` text NOT NULL,
  `condicion_calibracion_final` text NOT NULL,
  `comentarios` text DEFAULT NULL,
  `modelo_ci` text NOT NULL,
  `numero_serie` text NOT NULL,
  `fecha_calibracion` text NOT NULL,
  `identificacion_cliente` text NOT NULL,
  `presion_barometrica` text NOT NULL,
  `temperatura` text NOT NULL,
  `humedad_relativa` text NOT NULL,
  `vl_esperado` text NOT NULL,
  `vl_tolerancia` text NOT NULL,
  `vl_condicion_encontrada` text NOT NULL,
  `vl_pasa` text NOT NULL,
  `vl_condicion_final` text NOT NULL,
  `fa_esperado` text NOT NULL,
  `fa_tolerancia` text NOT NULL,
  `fa_condicion_encontrada` text NOT NULL,
  `fa_pasa` text NOT NULL,
  `fa_condicion_final` text NOT NULL,
  `rm_esperado` text NOT NULL,
  `rm_tolerancia` text NOT NULL,
  `rm_condicion_encontrada` text NOT NULL,
  `rm_pasa` text NOT NULL,
  `rm_condicion_final` text NOT NULL,
  `flujo_volumetrico` text NOT NULL,
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
  `fecha_documento` text NOT NULL,
  `dmm_activo` text NOT NULL,
  `dmm_modelo` text NOT NULL,
  `dmm_numero_serie` text NOT NULL,
  `dmm_numero_control` text NOT NULL,
  `dmm_fecha_calibracion` text NOT NULL,
  `dmm_proxima_calibracion` text NOT NULL,
  `pha_activo` text NOT NULL,
  `pha_modelo` text NOT NULL,
  `pha_numero_serie` text NOT NULL,
  `pha_numero_control` text NOT NULL,
  `pha_fecha_calibracion` text NOT NULL,
  `pha_proxima_calibracion` text NOT NULL,
  `mfm_activo` text NOT NULL,
  `mfm_modelo` text NOT NULL,
  `mfm_numero_serie` text NOT NULL,
  `mfm_numero_control` text NOT NULL,
  `mfm_fecha_calibracion` text NOT NULL,
  `mfm_proxima_calibracion` text NOT NULL,
  `rh_activo` text NOT NULL,
  `rh_modelo` text NOT NULL,
  `rh_numero_serie` text NOT NULL,
  `rh_numero_control` text NOT NULL,
  `rh_fecha_calibracion` text NOT NULL,
  `rh_proxima_calibracion` text NOT NULL,
  `balometro_activo` text NOT NULL,
  `balometro_modelo` text NOT NULL,
  `balometro_numero_serie` text NOT NULL,
  `balometro_numero_control` text NOT NULL,
  `balometro_fecha_calibracion` text NOT NULL,
  `balometro_proxima_calibracion` text NOT NULL,
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
  `tecnico` text NOT NULL,
  `fecha_hora_registro` text NOT NULL,
  `modifica_data` text DEFAULT NULL,
  `fecha_hora_modificacion` text DEFAULT NULL,
  PRIMARY KEY (`id_folio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fdv-s-032`
--

LOCK TABLES `fdv-s-032` WRITE;
/*!40000 ALTER TABLE `fdv-s-032` DISABLE KEYS */;
/*!40000 ALTER TABLE `fdv-s-032` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `formatos`
--

DROP TABLE IF EXISTS `formatos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `formatos` (
  `id_movimiento` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `formato` text NOT NULL,
  `nombre_formato` text NOT NULL,
  `revision_formato` text NOT NULL,
  `url` text NOT NULL,
  `registra_data` text NOT NULL,
  `fecha_hora_registro` text NOT NULL,
  `modifica_data` text DEFAULT NULL,
  `fecha_hora_modificacion` text DEFAULT NULL,
  PRIMARY KEY (`id_movimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `formatos`
--

LOCK TABLES `formatos` WRITE;
/*!40000 ALTER TABLE `formatos` DISABLE KEYS */;
/*!40000 ALTER TABLE `formatos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instrumentos`
--

DROP TABLE IF EXISTS `instrumentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `instrumentos` (
  `id_instrumento` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
  `fecha_hora_modificacion` text DEFAULT NULL,
  PRIMARY KEY (`id_instrumento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instrumentos`
--

LOCK TABLES `instrumentos` WRITE;
/*!40000 ALTER TABLE `instrumentos` DISABLE KEYS */;
/*!40000 ALTER TABLE `instrumentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `particulas`
--

DROP TABLE IF EXISTS `particulas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `particulas` (
  `id_particula` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
  `fecha_hora_modificacion` text DEFAULT NULL,
  PRIMARY KEY (`id_particula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `particulas`
--

LOCK TABLES `particulas` WRITE;
/*!40000 ALTER TABLE `particulas` DISABLE KEYS */;
/*!40000 ALTER TABLE `particulas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'veco_servicios'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-22  9:16:41
