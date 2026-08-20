-- ham-map.com database schema (tables only, no data)
-- Data is populated automatically by the cron/synchro*.php scripts from
-- public WWFF/POTA/SOTA/GMA/WWBOTA sources.


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `bota_area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `bota_area` (
  `scheme` varchar(10) NOT NULL,
  `reference` varchar(50) NOT NULL,
  `name` longblob NOT NULL,
  `type` varchar(50) NOT NULL,
  `latitude` varchar(50) NOT NULL,
  `longitude` varchar(50) NOT NULL,
  PRIMARY KEY (`reference`),
  UNIQUE KEY `reference` (`reference`),
  KEY `latitude_longitude` (`latitude`,`longitude`),
  KEY `longitude_latitude` (`longitude`,`latitude`),
  KEY `scheme` (`scheme`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `country` (
  `name` varchar(100) NOT NULL,
  `code` varchar(2) NOT NULL,
  `wwff_code` varchar(200) NOT NULL,
  `pota_code` varchar(200) NOT NULL,
  `sota_code` varchar(200) DEFAULT NULL,
  `gma_code` varchar(200) DEFAULT NULL,
  `bota_code` varchar(200) DEFAULT NULL,
  `center_longitude` varchar(20) DEFAULT NULL,
  `center_latitude` varchar(20) DEFAULT NULL,
  KEY `name` (`name`),
  KEY `name_code` (`name`,`code`),
  KEY `code` (`code`),
  KEY `code_name` (`code`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `gma_area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `gma_area` (
  `reference` varchar(50) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `altitude` varchar(50) DEFAULT NULL,
  `activation` int(11) DEFAULT NULL,
  `lastact` date DEFAULT NULL,
  KEY `reference` (`reference`),
  KEY `name` (`name`),
  KEY `reference_name` (`reference`,`name`),
  KEY `name_reference` (`name`,`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pota_area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pota_area` (
  `reference` varchar(50) NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `latitude` varchar(20) DEFAULT NULL,
  `longitude` varchar(20) DEFAULT NULL,
  `altitude` int(11) DEFAULT NULL,
  `activation` int(11) DEFAULT NULL,
  KEY `reference` (`reference`),
  KEY `name` (`name`),
  KEY `reference_name` (`reference`,`name`),
  KEY `name_reference` (`name`,`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sota_area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sota_area` (
  `reference` varchar(50) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `latitude` varchar(20) DEFAULT NULL,
  `longitude` varchar(20) DEFAULT NULL,
  `altitude` int(11) DEFAULT NULL,
  `activation` int(11) DEFAULT NULL,
  `lastact` date DEFAULT NULL,
  KEY `reference` (`reference`),
  KEY `name` (`name`),
  KEY `reference_name` (`reference`,`name`),
  KEY `name_reference` (`name`,`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `wwff_area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wwff_area` (
  `reference` varchar(50) NOT NULL,
  `status` varchar(10) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `program` varchar(10) NOT NULL,
  `dxcc` varchar(10) DEFAULT NULL,
  `latitude` varchar(20) DEFAULT NULL,
  `longitude` varchar(20) DEFAULT NULL,
  `qsoCount` int(10) DEFAULT 0,
  `lastAct` date NOT NULL DEFAULT '1980-01-01',
  KEY `reference` (`reference`),
  KEY `qsoCount_status_lastAct` (`qsoCount`,`status`,`lastAct`),
  KEY `reference_status` (`reference`,`status`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

