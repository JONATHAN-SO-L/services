-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: veco_sims_devecchi
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
-- Table structure for table `usuario_sis`
--

DROP TABLE IF EXISTS `usuario_sis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario_sis` (
  `id_usuario` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_completo` text NOT NULL,
  `usuario` text NOT NULL,
  `clave` text NOT NULL,
  `email` text NOT NULL,
  `razon_social` text DEFAULT NULL,
  `tipo_usuario` varchar(1) NOT NULL,
  `registra_data` text NOT NULL,
  `fecha_hora_registro` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `modifica_data` text DEFAULT NULL,
  `fecha_hora_modificacion` text DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `UNIQUE` (`usuario`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empresas`
--

DROP TABLE IF EXISTS `empresas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empresas` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rfc` varchar(20) NOT NULL,
  `razon_social` varchar(100) NOT NULL,
  `nombre_corto` varchar(20) DEFAULT NULL,
  `calle` varchar(80) NOT NULL,
  `numero_exterior` varchar(10) NOT NULL,
  `numero_interior` varchar(15) DEFAULT NULL,
  `colonia` varchar(40) NOT NULL,
  `municipio` varchar(40) NOT NULL,
  `entidad_federativa` varchar(40) NOT NULL,
  `codigo_postal` varchar(10) NOT NULL,
  `pais` varchar(20) DEFAULT NULL,
  `direccion_gps` varchar(40) DEFAULT NULL,
  `contacto_nombre` varchar(30) NOT NULL,
  `contacto_apellido` varchar(30) DEFAULT NULL,
  `contacto_puesto` varchar(30) NOT NULL,
  `contacto_email` varchar(100) NOT NULL,
  `contacto_telefono` varchar(50) NOT NULL,
  `fecha` text NOT NULL,
  `vendedor` text DEFAULT NULL,
  `squad` text DEFAULT NULL,
  `tecnico` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rfc` (`rfc`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edificio`
--

DROP TABLE IF EXISTS `edificio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `edificio` (
  `id_edificio` int(10) NOT NULL AUTO_INCREMENT,
  `empresa_id` int(10) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `calle` varchar(80) NOT NULL,
  `numero_exterior` varchar(10) NOT NULL,
  `numero_interior` varchar(15) DEFAULT NULL,
  `colonia` varchar(40) NOT NULL,
  `municipio` varchar(40) NOT NULL,
  `entidad_federativa` varchar(40) NOT NULL,
  `codigo_postal` varchar(10) NOT NULL,
  `pais` varchar(20) DEFAULT NULL,
  `direccion_gps` varchar(40) DEFAULT NULL,
  `contacto_nombre` varchar(30) NOT NULL,
  `contacto_apellido` varchar(30) DEFAULT NULL,
  `contacto_puesto` varchar(30) NOT NULL,
  `contacto_email` varchar(100) NOT NULL,
  `contacto_telefono` varchar(50) NOT NULL,
  `requisitos_acceso` text DEFAULT NULL,
  `fecha` text NOT NULL DEFAULT current_timestamp(),
  `vendedor` text DEFAULT NULL,
  `squad` text DEFAULT NULL,
  `tecnico` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `serie_contador` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_edificio`),
  KEY `empresa_id` (`empresa_id`),
  CONSTRAINT `edificio_ibfk_1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-10 17:56:59
