-- MySQL dump 10.13  Distrib 8.0.35, for Linux (x86_64)
--
-- Host: localhost    Database: kodepo
-- ------------------------------------------------------
-- Server version	8.0.35

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
-- Table structure for table `AutoSystem`
--

DROP TABLE IF EXISTS `AutoSystem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `AutoSystem` (
  `AUID` bigint unsigned NOT NULL AUTO_INCREMENT,
  `AUPID` mediumint unsigned NOT NULL,
  `AUNAME` varchar(30) DEFAULT NULL,
  `AULONGNAME` varchar(60) DEFAULT NULL,
  `AUURL` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`AUID`),
  KEY `ELID` (`AUPID`),
  FULLTEXT KEY `SNAME` (`AUNAME`),
  FULLTEXT KEY `AULONGNAME` (`AULONGNAME`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AutoSystem`
--

LOCK TABLES `AutoSystem` WRITE;
/*!40000 ALTER TABLE `AutoSystem` DISABLE KEYS */;
INSERT INTO `AutoSystem` VALUES (0,0,'Icon','Slavnem','http://local-ip-adress:8080/asset/logo/slavnem/slavnem.ico'),(1,1,'Logo','Slavnem','http://local-ip-adress:8080/asset/logo/slavnem/slavnem.png'),(2,2,'SignBackground','Columns Lamps Architecture','http://local-ip-adress:8080/asset/image/background/columns-lamps-architecture-1080p.jpg'),(3,2,'SignBackground','Mountains Slope Grass','http://local-ip-adress:8080/asset/image/background/mountains-slope-grass-1080p.jpg'),(4,2,'SignBackground','Rocks Sea Landspace','http://local-ip-adress:8080/asset/image/background/rocks-sea-landspace-1080p.jpg'),(5,2,'SignBackground','Sea Bay Bottom','http://local-ip-adress:8080/asset/image/background/sea-bay-bottom-1080p.jpg'),(6,2,'SignBackground','Sea Bay Trees','http://local-ip-adress:8080/asset/image/background/sea-bay-trees-1080p.jpg'),(9,3,'Background','Tiger','http://local-ip-adress:8080/asset/image/background/pexels-mehmet-turgut-kirkgoz-19287530-1080p.jpg'),(10,3,'Background','Nature','http://local-ip-adress:8080/asset/image/background/pexels-serhat-tug-19284791-1080p.jpg'),(11,3,'Background','Autumn','http://local-ip-adress:8080/asset/image/background/pexels-andy-kuzma-1653354-1080p.jpg'),(12,3,'Background','Mountain And Sky','http://local-ip-adress:8080/asset/image/background/pexels-mathew-thomas-19248350-1080p.jpg'),(13,4,'CustomArtDesign','Cartoon Art','http://local-ip-adress:8080/asset/image/custom/cartoon-art.jpg'),(14,4,'CustomArtDesign','Cartoon Art','http://local-ip-adress:8080/asset/image/custom/cartoon-art-1.jpg'),(15,4,'CustomArtDesign','Cartoon Art','http://local-ip-adress:8080/asset/image/custom/cartoon-art-2.jpg'),(16,4,'CustomArtDesign','Cartoon Art','http://local-ip-adress:8080/asset/image/custom/cartoon-art-3.jpg'),(17,4,'CustomArtDesign','Cartoon Art','http://local-ip-adress:8080/asset/image/custom/cartoon-art-4.jpg'),(18,4,'CustomArtDesign','Cartoon Art','http://local-ip-adress:8080/asset/image/custom/cartoon-art-5.jpg'),(19,4,'CustomArtDesign','Cartoon Art','http://local-ip-adress:8080/asset/image/custom/cartoon-art-6.jpg'),(20,4,'CustomArtDesign','Cartoon Art','http://local-ip-adress:8080/asset/image/custom/cartoon-art-7.jpg'),(21,4,'CustomArtDesign','Cartoon Art','http://local-ip-adress:8080/asset/image/custom/cartoon-art-8.jpg'),(23,4,'CustomArtDesign','Doodle Art','http://local-ip-adress:8080/asset/image/custom/doodle-art.jpg'),(24,4,'CustomArtDesign','Doodle Art','http://local-ip-adress:8080/asset/image/custom/doodle-art-1.jpg'),(25,4,'CustomArtDesign','Doodle Art','http://local-ip-adress:8080/asset/image/custom/doodle-art-2.jpg');
/*!40000 ALTER TABLE `AutoSystem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Codes`
--

DROP TABLE IF EXISTS `Codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Codes` (
  `CODEID` bigint unsigned NOT NULL AUTO_INCREMENT,
  `CPROJECTID` bigint unsigned NOT NULL,
  `CNAME` varchar(30) DEFAULT NULL,
  `CCOMMENT` varchar(255) DEFAULT NULL,
  `CCODE` enum('asm','wasm','c','cpp','h','hpp','java','py','js','jsx','html','htm','css','rb','swift','m','mm','cs','php','go','rs','ts','tsx','kt','kotlin','pl','sh','sql','json','xml','md','yaml','yml','dart','r') DEFAULT NULL,
  `CSECURE` enum('***','**','*','!','?') DEFAULT '?',
  `CLIKE` int unsigned DEFAULT '0',
  `CDISLIKE` int unsigned DEFAULT '0',
  `CSIZE` int unsigned DEFAULT '0',
  `CURL` varchar(255) DEFAULT NULL,
  `CDOWNLOADCOUNT` bigint unsigned DEFAULT '0',
  `CTIME` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `CUPDATE` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `CBACKUP` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`CODEID`),
  KEY `GUVENLIK` (`CSECURE`),
  KEY `BEGENI` (`CLIKE`),
  KEY `BEGENMEME` (`CDISLIKE`),
  KEY `BOYUT` (`CSIZE`),
  KEY `INDIRME` (`CDOWNLOADCOUNT`),
  KEY `TARIH` (`CTIME`),
  KEY `GUNCELLEME` (`CUPDATE`),
  KEY `KOD` (`CCODE`),
  KEY `CBACKUP` (`CBACKUP`),
  KEY `FK_CPROJECTID` (`CPROJECTID`),
  FULLTEXT KEY `AD` (`CNAME`),
  FULLTEXT KEY `CCOMMENT` (`CCOMMENT`),
  CONSTRAINT `FK_CPROJECTID` FOREIGN KEY (`CPROJECTID`) REFERENCES `Projects` (`PROJECTID`)
) ENGINE=InnoDB AUTO_INCREMENT=237 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Codes`
--

LOCK TABLES `Codes` WRITE;
/*!40000 ALTER TABLE `Codes` DISABLE KEYS */;
INSERT INTO `Codes` VALUES (236,80,'Point3D','Java ile Point2D\'ye kalıtım ile Point3D oluşturma.','java','?',0,0,454,NULL,0,'2023-12-24 18:18:24','2023-12-24 18:18:24','0');
/*!40000 ALTER TABLE `Codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Languages`
--

DROP TABLE IF EXISTS `Languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Languages` (
  `LANGID` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `LANGNAME` varchar(24) NOT NULL,
  `LANGSHORT` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`LANGID`),
  FULLTEXT KEY `LNAME` (`LANGNAME`),
  FULLTEXT KEY `LANGSHORT` (`LANGSHORT`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Languages`
--

LOCK TABLES `Languages` WRITE;
/*!40000 ALTER TABLE `Languages` DISABLE KEYS */;
INSERT INTO `Languages` VALUES (1,'Türkçe','TR'),(2,'English','EN'),(3,'Русский','RU');
/*!40000 ALTER TABLE `Languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Projects`
--

DROP TABLE IF EXISTS `Projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Projects` (
  `PROJECTID` bigint unsigned NOT NULL AUTO_INCREMENT,
  `POWNERID` bigint unsigned NOT NULL,
  `PNAME` varchar(50) DEFAULT NULL,
  `PCOMMENT` varchar(255) DEFAULT NULL,
  `PLIKE` bigint unsigned DEFAULT '0',
  `PDISLIKE` bigint unsigned DEFAULT '0',
  `PSECURE` enum('!','?','*','**','***') DEFAULT '?',
  `PDOWNLOADCOUNT` bigint unsigned DEFAULT '0',
  `PSIZE` bigint unsigned DEFAULT '0',
  `PURL` varchar(255) DEFAULT NULL,
  `PTIME` datetime DEFAULT CURRENT_TIMESTAMP,
  `PUPDATE` datetime DEFAULT CURRENT_TIMESTAMP,
  `PBACKUP` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`PROJECTID`),
  KEY `PLIKE` (`PLIKE`),
  KEY `PDISLIKE` (`PDISLIKE`),
  KEY `PSECURE` (`PSECURE`),
  KEY `PDOWNLOADCOUNT` (`PDOWNLOADCOUNT`),
  KEY `PSIZE` (`PSIZE`),
  KEY `PTIME` (`PTIME`),
  KEY `PUPDATE` (`PUPDATE`),
  KEY `PBACKUP` (`PBACKUP`),
  KEY `FK_POWNERID` (`POWNERID`),
  FULLTEXT KEY `PNAME` (`PNAME`),
  FULLTEXT KEY `PCOMMENT` (`PCOMMENT`),
  CONSTRAINT `FK_POWNERID` FOREIGN KEY (`POWNERID`) REFERENCES `Users` (`USERID`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Projects`
--

LOCK TABLES `Projects` WRITE;
/*!40000 ALTER TABLE `Projects` DISABLE KEYS */;
INSERT INTO `Projects` VALUES (80,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,'http://192.168.1.109:7600/store/code/upload/2/1/','2023-12-13 12:20:57','2023-12-13 12:20:57','1'),(84,2,'test 41','tabwfa',0,0,'?',0,0,'http://192.168.1.109:7600/store/code/upload/2/81/','2023-12-17 03:00:47','2023-12-17 03:00:47','1'),(85,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-24 22:31:14','2023-12-24 22:31:14','0'),(86,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-24 22:33:08','2023-12-24 22:33:08','1'),(87,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-24 22:33:08','2023-12-24 22:33:08','0'),(88,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-24 22:33:09','2023-12-24 22:33:09','1'),(89,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-24 22:33:10','2023-12-24 22:33:10','0'),(90,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-24 22:33:10','2023-12-24 22:33:10','1'),(91,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-24 22:33:11','2023-12-24 22:33:11','0'),(92,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-24 22:33:29','2023-12-24 22:33:29','1'),(93,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-24 22:33:30','2023-12-24 22:33:30','0'),(94,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-24 22:33:30','2023-12-24 22:33:30','1'),(95,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-24 22:33:31','2023-12-24 22:33:31','0'),(96,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-25 00:50:01','2023-12-25 00:50:01','1'),(97,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-25 00:50:01','2023-12-25 00:50:01','0'),(98,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-25 00:50:02','2023-12-25 00:50:02','1'),(99,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-25 00:50:02','2023-12-25 00:50:02','0'),(100,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-25 00:50:02','2023-12-25 00:50:02','1'),(101,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-25 00:50:03','2023-12-25 00:50:03','0'),(102,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-25 00:50:03','2023-12-25 00:50:03','1'),(103,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-25 00:50:03','2023-12-25 00:50:03','0'),(104,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-25 00:50:04','2023-12-25 00:50:04','1'),(105,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-25 00:50:04','2023-12-25 00:50:04','0'),(106,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-25 00:50:04','2023-12-25 00:50:04','1'),(107,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-25 00:50:05','2023-12-25 00:50:05','0'),(108,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-25 00:50:05','2023-12-25 00:50:05','1'),(109,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-25 00:50:05','2023-12-25 00:50:05','0'),(110,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-25 00:50:06','2023-12-25 00:50:06','1'),(111,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-25 00:50:06','2023-12-25 00:50:06','0'),(112,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-25 00:50:06','2023-12-25 00:50:06','1'),(113,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-25 00:50:07','2023-12-25 00:50:07','0'),(114,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-25 00:50:07','2023-12-25 00:50:07','1'),(115,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-25 00:50:07','2023-12-25 00:50:07','0'),(116,2,'Acu BP Sınıf 2 Java Hafta 11 Kalıtım Örnek','11. hafta için kalıtım ile 10. haftada yaptığımız vizedeki Point2D kullanarak Point3D yaptık',0,0,'?',0,0,NULL,'2023-12-25 00:50:08','2023-12-25 00:50:08','1');
/*!40000 ALTER TABLE `Projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `UserCustomMod`
--

DROP TABLE IF EXISTS `UserCustomMod`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `UserCustomMod` (
  `UCMID` bigint unsigned NOT NULL AUTO_INCREMENT,
  `UCMUSERID` bigint unsigned NOT NULL,
  `UCMMODID` tinyint unsigned DEFAULT '0',
  PRIMARY KEY (`UCMID`),
  KEY `UCMUSERID` (`UCMUSERID`),
  KEY `UCMMODID` (`UCMMODID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `UserCustomMod`
--

LOCK TABLES `UserCustomMod` WRITE;
/*!40000 ALTER TABLE `UserCustomMod` DISABLE KEYS */;
INSERT INTO `UserCustomMod` VALUES (1,0,0);
/*!40000 ALTER TABLE `UserCustomMod` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `UserCustomizations`
--

DROP TABLE IF EXISTS `UserCustomizations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `UserCustomizations` (
  `UCID` bigint unsigned NOT NULL AUTO_INCREMENT,
  `UCUSERID` bigint unsigned NOT NULL,
  `UCNAME` varchar(50) NOT NULL,
  `UCPID` mediumint unsigned NOT NULL,
  `UCMODID` tinyint unsigned NOT NULL,
  `UCSTATUS` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`UCID`),
  KEY `UCUSERID` (`UCUSERID`),
  KEY `UCPID` (`UCPID`),
  KEY `UCSTATUS` (`UCSTATUS`),
  KEY `UCMODID` (`UCMODID`),
  FULLTEXT KEY `UCNAME` (`UCNAME`),
  CONSTRAINT `UCUSERID` FOREIGN KEY (`UCUSERID`) REFERENCES `Users` (`USERID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `UserCustomizations`
--

LOCK TABLES `UserCustomizations` WRITE;
/*!40000 ALTER TABLE `UserCustomizations` DISABLE KEYS */;
INSERT INTO `UserCustomizations` VALUES (1,0,'Message Ask',1,0,'1'),(2,0,'Message Information',2,0,'1');
/*!40000 ALTER TABLE `UserCustomizations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Users` (
  `USERID` bigint unsigned NOT NULL AUTO_INCREMENT,
  `UNAME` tinytext NOT NULL,
  `UPASSWORD` varchar(255) NOT NULL,
  `UEMAIL` varchar(255) NOT NULL,
  `UFIRSTNAME` varchar(30) DEFAULT NULL,
  `ULASTNAME` varchar(30) DEFAULT NULL,
  `URANK` enum('0','1','2','3','4','5','6','7') NOT NULL DEFAULT '0',
  `UREGISTER` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ULANGUAGE` tinyint unsigned NOT NULL DEFAULT '2',
  `UMOD` tinyint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`USERID`),
  KEY `EMAIL` (`UEMAIL`),
  KEY `SEVIYE` (`URANK`),
  KEY `KAYIT` (`UREGISTER`),
  KEY `UMOD` (`UMOD`),
  KEY `FK_ULANGUAGE` (`ULANGUAGE`),
  KEY `UCMUSERID` (`USERID`),
  KEY `USERID` (`USERID`),
  FULLTEXT KEY `AD` (`UFIRSTNAME`),
  FULLTEXT KEY `SOYAD` (`ULASTNAME`),
  FULLTEXT KEY `UNAME` (`UNAME`),
  CONSTRAINT `FK_ULANGUAGE` FOREIGN KEY (`ULANGUAGE`) REFERENCES `Languages` (`LANGID`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
INSERT INTO `Users` VALUES (0,'kodepo','kodepo@safe>password@kodepo-nologin!','kodepo@kodepo.com','Kodepo','Kodepo','7','2023-12-08 17:03:08',1,0),(1,'admin','$2y$10$O69swJ4y7Dswkp8FhZ//vOeLO2c3scn3EEaUrdwkLrG56Q.zJZgkW','admin@kodepo.org','Admin','Kodepo','6','2023-11-21 16:40:31',1,0),(2,'bookworm','$2y$10$15JlslSeluzSP9MVUn1Rcu3qlvL7uNMPzJKKuvnbKZBUeCZ3qrczq','bookworm@email.com','Bookworm First','Bookworm Last','5','2023-11-21 17:50:49',1,1),(3,'test','$2y$10$7Mz/N.UHBforLAe9bquQNuPqOImh2Is8St1H7g13XUDynNQj9gWjO','test@test.test','Test','Test','4','2023-11-21 20:54:16',1,0),(15,'testuser','$2y$10$ee0Z2KlEXO/J7a/9WsiZhOnP9Z7x9CwiZlAw146zCUCs7juiCac22','testuser@testuser.email','Testuser Ad','User Soy','3','2023-12-07 00:20:18',1,0),(16,'deneme','$2y$10$.0rnPhMlDwe6FMB/0R.qseG/wFY2vW7.lYnJpoLifAWnLAaURsA6K','deneme@kodepo.com','Deneme22','Deneme','2','2023-12-07 00:21:50',1,0),(20,'BLA','$2y$10$itIE8WYBaLsdu.pf8mA2DO5INSIhY74mzb9lGNAFG0oSvkOCVzyyy','BLA@bla.com','Bla','Blabla','1','2023-12-09 16:39:01',2,0),(21,'noneusername','$2y$10$mxOpDVlqUA1hwiIeMnq0qO3iVAODzS2peV1E4uJ/tZTVPcT0hh.Yy','none@none.email','None firstname','None lastname','0','2023-12-23 13:45:34',2,0);
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-12-27  3:16:45
