-- MySQL dump 10.13  Distrib 5.7.9, for Win32 (AMD64)
--
-- Host: 127.0.0.1    Database: reminder
-- ------------------------------------------------------
-- Server version	5.0.24a-community-nt

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Not dumping tablespaces as no INFORMATION_SCHEMA.FILES table on this server
--

--
-- Table structure for table `actividades`
--

DROP TABLE IF EXISTS `actividades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `actividades` (
  `actividades_id` int(11) NOT NULL auto_increment,
  `actividades_nombre` varchar(45) NOT NULL,
  `actividades_fecha` date NOT NULL,
  `actividades_estado` enum('A','F') default 'A',
  `actividades_responsable` int(11) default NULL,
  `actividades_area` int(11) default NULL,
  `actividades_reporta` varchar(45) default NULL,
  `actividades_fecha_fin` date default NULL,
  PRIMARY KEY  (`actividades_id`),
  KEY `actividades_responsable_fk_idx` (`actividades_responsable`),
  KEY `actividades_area_fk_idx` (`actividades_area`),
  CONSTRAINT `actividades_area_fk` FOREIGN KEY (`actividades_area`) REFERENCES `areas` (`areas_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `actividades_responsable_fk` FOREIGN KEY (`actividades_responsable`) REFERENCES `usuarios` (`usuarios_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `areas`
--

DROP TABLE IF EXISTS `areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `areas` (
  `areas_id` int(11) NOT NULL auto_increment,
  `areas_nombre` varchar(45) NOT NULL,
  `areas_estado` enum('A','I') default NULL,
  PRIMARY KEY  (`areas_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `etiquetas`
--

DROP TABLE IF EXISTS `etiquetas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etiquetas` (
  `etiquetas_id` int(11) NOT NULL auto_increment,
  `etiquetas_nombre` varchar(45) NOT NULL,
  PRIMARY KEY  (`etiquetas_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `objetos`
--

DROP TABLE IF EXISTS `objetos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `objetos` (
  `objetos_actividad_id` int(11) NOT NULL,
  `objetos_id` int(11) NOT NULL auto_increment,
  `objetos_nombre` varchar(45) NOT NULL,
  `objetos_tipo` int(11) NOT NULL,
  PRIMARY KEY  (`objetos_id`,`objetos_actividad_id`),
  KEY `objetos_actividad_id_idx` (`objetos_actividad_id`),
  KEY `objetos_tipo_id_idx` (`objetos_tipo`),
  CONSTRAINT `objetos_actividad_id` FOREIGN KEY (`objetos_actividad_id`) REFERENCES `actividades` (`actividades_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `objetos_etiquetas`
--

DROP TABLE IF EXISTS `objetos_etiquetas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `objetos_etiquetas` (
  `objetos_etiquetas_id` int(11) NOT NULL auto_increment,
  `objetos_id` int(11) NOT NULL,
  `etiquetas_id` int(11) NOT NULL,
  PRIMARY KEY  (`objetos_etiquetas_id`),
  KEY `fk_objetos_id_idx` (`objetos_id`),
  KEY `fk_etiquetas_id_idx` (`etiquetas_id`),
  CONSTRAINT `fk_etiquetas_id` FOREIGN KEY (`etiquetas_id`) REFERENCES `etiquetas` (`etiquetas_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_objetos_id` FOREIGN KEY (`objetos_id`) REFERENCES `objetos` (`objetos_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipo_objeto`
--

DROP TABLE IF EXISTS `tipo_objeto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_objeto` (
  `tipo_objeto_id` int(11) NOT NULL auto_increment,
  `tipo_objeto_nombre` varchar(45) NOT NULL,
  `tipo_objeto_estado` enum('A','I') NOT NULL,
  `tipo_objeto_icono` varchar(45) default NULL,
  PRIMARY KEY  (`tipo_objeto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `usuarios_id` int(11) NOT NULL auto_increment,
  `usuarios_username` varchar(45) NOT NULL,
  `usuarios_nombres` varchar(100) NOT NULL,
  `usuarios_password` varchar(45) default NULL,
  `usuarios_estado` enum('A','I') default NULL,
  PRIMARY KEY  (`usuarios_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-02-05 16:09:52
