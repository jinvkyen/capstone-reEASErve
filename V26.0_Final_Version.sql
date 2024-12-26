-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: reeaserve_final
-- ------------------------------------------------------
-- Server version	8.0.36

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `account_status`
--

DROP TABLE IF EXISTS `account_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `account_status` (
  `acct_status_id` int NOT NULL,
  `acct_status` varchar(75) NOT NULL,
  PRIMARY KEY (`acct_status_id`),
  UNIQUE KEY `acct_status_UNIQUE` (`acct_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_status`
--

LOCK TABLES `account_status` WRITE;
/*!40000 ALTER TABLE `account_status` DISABLE KEYS */;
INSERT INTO `account_status` VALUES (1,'Activated'),(0,'Deactivated');
/*!40000 ALTER TABLE `account_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `approve_reject_analytics`
--

DROP TABLE IF EXISTS `approve_reject_analytics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `approve_reject_analytics` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `request_id` int NOT NULL,
  `status` varchar(255) NOT NULL,
  `changed_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `approve_reject_analytics`
--

LOCK TABLES `approve_reject_analytics` WRITE;
/*!40000 ALTER TABLE `approve_reject_analytics` DISABLE KEYS */;
INSERT INTO `approve_reject_analytics` VALUES (1,30633,'3','2024-08-24 10:21:23','2024-08-24 10:21:23','2024-08-24 10:21:23'),(2,55401,'3','2024-08-24 10:22:38','2024-08-24 10:22:38','2024-08-24 10:22:38'),(3,81037,'4','2024-08-24 10:26:47','2024-08-24 10:26:47','2024-08-24 10:26:47'),(5,59555,'3','2024-08-24 11:28:38','2024-08-24 11:28:38','2024-08-24 11:28:38'),(7,59555,'3','2024-08-24 11:33:44','2024-08-24 11:33:44','2024-08-24 11:33:44'),(8,97164,'4','2024-08-24 11:34:21','2024-08-24 11:34:21','2024-08-24 11:34:21'),(9,37432,'3','2024-08-25 08:57:56','2024-08-25 08:57:56','2024-08-25 08:57:56'),(10,24993,'3','2024-08-26 02:49:37','2024-08-26 02:49:37','2024-08-26 02:49:37'),(11,56431,'3','2024-08-26 03:26:52','2024-08-26 03:26:52','2024-08-26 03:26:52'),(12,25857,'3','2024-08-28 01:18:01','2024-08-28 01:18:01','2024-08-28 01:18:01'),(13,5521,'3','2024-08-28 01:22:59','2024-08-28 01:22:59','2024-08-28 01:22:59'),(14,14613,'3','2024-08-28 01:33:23','2024-08-28 01:33:23','2024-08-28 01:33:23'),(15,78477,'3','2024-08-28 06:04:17','2024-08-28 06:04:17','2024-08-28 06:04:17'),(16,37432,'4','2024-08-28 06:12:22','2024-08-28 06:12:22','2024-08-28 06:12:22'),(17,98722,'3','2024-08-28 06:24:57','2024-08-28 06:24:57','2024-08-28 06:24:57'),(18,15361,'3','2024-08-28 06:34:55','2024-08-28 06:34:55','2024-08-28 06:34:55'),(19,15361,'3','2024-08-28 06:36:58','2024-08-28 06:36:58','2024-08-28 06:36:58'),(20,15361,'3','2024-08-28 06:56:57','2024-08-28 06:56:57','2024-08-28 06:56:57'),(21,15361,'3','2024-08-28 07:02:25','2024-08-28 07:02:25','2024-08-28 07:02:25'),(22,15361,'3','2024-08-28 07:03:01','2024-08-28 07:03:01','2024-08-28 07:03:01'),(23,15361,'3','2024-08-28 07:04:36','2024-08-28 07:04:36','2024-08-28 07:04:36'),(24,15361,'3','2024-08-28 07:05:16','2024-08-28 07:05:16','2024-08-28 07:05:16'),(25,15361,'3','2024-08-28 07:06:59','2024-08-28 07:06:59','2024-08-28 07:06:59'),(26,15361,'3','2024-08-28 07:07:25','2024-08-28 07:07:25','2024-08-28 07:07:25'),(27,15361,'3','2024-08-28 07:07:52','2024-08-28 07:07:52','2024-08-28 07:07:52'),(28,15361,'3','2024-08-28 07:08:09','2024-08-28 07:08:09','2024-08-28 07:08:09'),(29,15361,'3','2024-08-28 07:08:17','2024-08-28 07:08:17','2024-08-28 07:08:17'),(30,15361,'3','2024-08-28 07:14:55','2024-08-28 07:14:55','2024-08-28 07:14:55'),(31,15361,'3','2024-08-28 07:16:13','2024-08-28 07:16:13','2024-08-28 07:16:13'),(32,15361,'3','2024-08-28 07:19:46','2024-08-28 07:19:46','2024-08-28 07:19:46'),(33,20920,'3','2024-08-28 07:26:25','2024-08-28 07:26:25','2024-08-28 07:26:25'),(34,20920,'3','2024-08-28 07:40:12','2024-08-28 07:40:12','2024-08-28 07:40:12'),(35,20920,'3','2024-08-28 07:42:38','2024-08-28 07:42:38','2024-08-28 07:42:38'),(36,20920,'3','2024-08-28 07:44:28','2024-08-28 07:44:28','2024-08-28 07:44:28'),(37,20920,'3','2024-08-28 07:44:41','2024-08-28 07:44:41','2024-08-28 07:44:41'),(38,20920,'3','2024-08-28 07:45:50','2024-08-28 07:45:50','2024-08-28 07:45:50'),(39,20920,'3','2024-08-28 07:53:14','2024-08-28 07:53:14','2024-08-28 07:53:14'),(40,20920,'3','2024-08-28 07:53:25','2024-08-28 07:53:25','2024-08-28 07:53:25'),(41,20920,'3','2024-08-28 07:56:32','2024-08-28 07:56:32','2024-08-28 07:56:32'),(42,20920,'3','2024-08-28 07:57:04','2024-08-28 07:57:04','2024-08-28 07:57:04'),(43,20920,'3','2024-08-28 08:02:48','2024-08-28 08:02:48','2024-08-28 08:02:48'),(44,20920,'3','2024-08-28 08:02:56','2024-08-28 08:02:56','2024-08-28 08:02:56'),(45,20920,'3','2024-08-28 08:06:07','2024-08-28 08:06:07','2024-08-28 08:06:07'),(46,20920,'3','2024-08-28 08:13:57','2024-08-28 08:13:57','2024-08-28 08:13:57'),(47,20920,'3','2024-08-28 08:15:13','2024-08-28 08:15:13','2024-08-28 08:15:13'),(48,20920,'3','2024-08-28 08:15:25','2024-08-28 08:15:25','2024-08-28 08:15:25'),(49,20920,'3','2024-08-28 08:18:06','2024-08-28 08:18:06','2024-08-28 08:18:06'),(50,20920,'3','2024-08-28 08:18:21','2024-08-28 08:18:21','2024-08-28 08:18:21'),(51,20920,'3','2024-08-28 08:18:29','2024-08-28 08:18:29','2024-08-28 08:18:29'),(52,63821,'3','2024-08-28 09:02:45','2024-08-28 09:02:45','2024-08-28 09:02:45'),(53,63821,'3','2024-08-28 09:14:33','2024-08-28 09:14:33','2024-08-28 09:14:33'),(54,1960,'3','2024-09-05 09:14:22','2024-09-05 09:14:22','2024-09-05 09:14:22'),(55,27408,'3','2024-09-24 21:45:59','2024-09-24 21:45:59','2024-09-24 21:45:59'),(56,27408,'3','2024-09-30 07:20:55','2024-09-30 07:20:55','2024-09-30 07:20:55'),(57,68904,'3','2024-09-30 07:35:54','2024-09-30 07:35:54','2024-09-30 07:35:54'),(58,68904,'3','2024-09-30 17:31:35','2024-09-30 17:31:35','2024-09-30 17:31:35'),(59,43801,'4','2024-10-02 16:48:12','2024-10-02 16:48:12','2024-10-02 16:48:12'),(60,21786,'3','2024-10-03 11:09:18','2024-10-03 11:09:18','2024-10-03 11:09:18'),(61,12079,'4','2024-10-03 11:28:40','2024-10-03 11:28:40','2024-10-03 11:28:40'),(62,73312,'3','2024-10-03 16:18:28','2024-10-03 16:18:28','2024-10-03 16:18:28'),(63,73312,'3','2024-10-03 16:19:06','2024-10-03 16:19:06','2024-10-03 16:19:06'),(64,60024,'3','2024-10-03 16:22:27','2024-10-03 16:22:27','2024-10-03 16:22:27'),(65,36474,'3','2024-10-05 16:09:58','2024-10-05 16:09:58','2024-10-05 16:09:58'),(66,99046,'4','2024-10-06 14:08:06','2024-10-06 14:08:06','2024-10-06 14:08:06'),(67,56126,'4','2024-10-06 14:08:26','2024-10-06 14:08:26','2024-10-06 14:08:26'),(68,39461,'3','2024-10-06 15:56:50','2024-10-06 15:56:50','2024-10-06 15:56:50'),(69,66301,'3','2024-10-06 15:57:37','2024-10-06 15:57:37','2024-10-06 15:57:37'),(70,90639,'3','2024-10-06 15:59:24','2024-10-06 15:59:24','2024-10-06 15:59:24'),(71,90639,'3','2024-10-07 10:56:35','2024-10-07 10:56:35','2024-10-07 10:56:35'),(72,85711,'3','2024-10-07 11:08:26','2024-10-07 11:08:26','2024-10-07 11:08:26'),(73,18123,'3','2024-10-08 03:30:10','2024-10-08 03:30:10','2024-10-08 03:30:10'),(74,17098,'3','2024-10-08 03:35:07','2024-10-08 03:35:07','2024-10-08 03:35:07'),(75,9528,'3','2024-10-08 07:55:33','2024-10-08 07:55:33','2024-10-08 07:55:33'),(76,47163,'3','2024-10-08 08:03:36','2024-10-08 08:03:36','2024-10-08 08:03:36'),(77,92425,'3','2024-10-08 08:22:52','2024-10-08 08:22:52','2024-10-08 08:22:52'),(78,10104,'3','2024-10-08 08:31:36','2024-10-08 08:31:36','2024-10-08 08:31:36'),(79,68489,'3','2024-10-08 16:47:38','2024-10-08 16:47:38','2024-10-08 16:47:38'),(80,68489,'3','2024-10-08 16:54:40','2024-10-08 16:54:40','2024-10-08 16:54:40'),(81,68489,'3','2024-10-08 17:00:08','2024-10-08 17:00:08','2024-10-08 17:00:08'),(82,3032,'3','2024-10-09 01:01:02','2024-10-09 01:01:02','2024-10-09 01:01:02'),(83,21999,'3','2024-10-09 02:00:53','2024-10-09 02:00:53','2024-10-09 02:00:53'),(84,21006,'3','2024-10-09 08:05:54','2024-10-09 08:05:54','2024-10-09 08:05:54'),(85,68904,'3','2024-10-10 05:17:37','2024-10-10 05:17:37','2024-10-10 05:17:37'),(86,68358,'3','2024-10-10 05:52:26','2024-10-10 05:52:26','2024-10-10 05:52:26'),(87,18960,'3','2024-10-10 07:08:04','2024-10-10 07:08:04','2024-10-10 07:08:04'),(88,2342,'3','2024-10-11 05:29:58','2024-10-11 05:29:58','2024-10-11 05:29:58'),(89,55033,'3','2024-10-11 08:19:00','2024-10-11 08:19:00','2024-10-11 08:19:00'),(90,89561,'3','2024-10-11 10:42:59','2024-10-11 10:42:59','2024-10-11 10:42:59'),(91,99335,'3','2024-10-11 11:39:39','2024-10-11 11:39:39','2024-10-11 11:39:39'),(92,84814,'4','2024-10-11 11:51:44','2024-10-11 11:51:44','2024-10-11 11:51:44'),(93,30393,'3','2024-10-11 16:43:17','2024-10-11 16:43:17','2024-10-11 16:43:17'),(94,31962,'3','2024-10-30 17:00:40','2024-10-30 17:00:40','2024-10-30 17:00:40'),(95,58695,'4','2024-11-10 18:33:26','2024-11-10 18:33:26','2024-11-10 18:33:26'),(96,84738,'4','2024-11-12 06:10:38','2024-11-12 06:10:38','2024-11-12 06:10:38'),(97,31962,'3','2024-11-15 16:16:22','2024-11-15 16:16:22','2024-11-15 16:16:22'),(98,52803,'3','2024-11-16 14:01:15','2024-11-16 14:01:15','2024-11-16 14:01:15'),(99,27811,'3','2024-11-18 17:01:42','2024-11-18 17:01:42','2024-11-18 17:01:42'),(100,27811,'3','2024-11-18 17:07:55','2024-11-18 17:07:55','2024-11-18 17:07:55'),(101,52803,'3','2024-11-18 17:14:13','2024-11-18 17:14:13','2024-11-18 17:14:13'),(102,2384,'3','2024-11-18 17:35:50','2024-11-18 17:35:50','2024-11-18 17:35:50'),(103,52803,'4','2024-11-19 03:59:18','2024-11-19 03:59:18','2024-11-19 03:59:18'),(104,94009,'4','2024-11-19 04:49:05','2024-11-19 04:49:05','2024-11-19 04:49:05'),(105,52803,'3','2024-11-19 04:52:11','2024-11-19 04:52:11','2024-11-19 04:52:11'),(106,50042,'3','2024-11-19 05:00:02','2024-11-19 05:00:02','2024-11-19 05:00:02'),(107,27811,'3','2024-11-19 06:04:26','2024-11-19 06:04:26','2024-11-19 06:04:26'),(108,59544,'3','2024-11-19 06:28:26','2024-11-19 06:28:26','2024-11-19 06:28:26'),(109,52709,'3','2024-11-19 06:32:21','2024-11-19 06:32:21','2024-11-19 06:32:21'),(110,6797,'3','2024-11-19 06:42:29','2024-11-19 06:42:29','2024-11-19 06:42:29'),(111,17018,'3','2024-11-19 06:51:33','2024-11-19 06:51:33','2024-11-19 06:51:33'),(112,55046,'3','2024-11-22 07:14:47','2024-11-22 07:14:47','2024-11-22 07:14:47'),(113,55046,'3','2024-11-22 07:31:03','2024-11-22 07:31:03','2024-11-22 07:31:03'),(114,55046,'','2024-11-22 07:50:53','2024-11-22 07:50:53','2024-11-22 07:50:53'),(115,55046,'','2024-11-22 07:51:44','2024-11-22 07:51:44','2024-11-22 07:51:44'),(116,11676,'','2024-11-22 10:28:35','2024-11-22 10:28:35','2024-11-22 10:28:35'),(117,11676,'','2024-11-23 06:22:30','2024-11-23 06:22:30','2024-11-23 06:22:30'),(118,11676,'','2024-11-23 06:22:52','2024-11-23 06:22:52','2024-11-23 06:22:52'),(119,3293,'','2024-11-23 06:23:45','2024-11-23 06:23:45','2024-11-23 06:23:45'),(120,3293,'','2024-11-23 06:27:32','2024-11-23 06:27:32','2024-11-23 06:27:32'),(121,93549,'4','2024-11-23 06:42:56','2024-11-23 06:42:56','2024-11-23 06:42:56'),(122,62210,'3','2024-11-23 06:46:51','2024-11-23 06:46:51','2024-11-23 06:46:51');
/*!40000 ALTER TABLE `approve_reject_analytics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `archive_feedbacks`
--

DROP TABLE IF EXISTS `archive_feedbacks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `archive_feedbacks` (
  `feedback_id` int NOT NULL,
  `username` int NOT NULL,
  `feedback` longtext,
  `resource_id` int NOT NULL,
  `rating` double NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`feedback_id`),
  KEY `item_id_idx` (`resource_id`),
  KEY `fk_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `archive_feedbacks`
--

LOCK TABLES `archive_feedbacks` WRITE;
/*!40000 ALTER TABLE `archive_feedbacks` DISABLE KEYS */;
INSERT INTO `archive_feedbacks` VALUES (1,202118402,'Testing comment',64691,3,'2024-06-15 14:51:22','2024-06-15 14:51:22'),(3,202118402,'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source.',64691,2,'2024-06-15 15:00:30','2024-06-15 15:00:30');
/*!40000 ALTER TABLE `archive_feedbacks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audits`
--

DROP TABLE IF EXISTS `audits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audits` (
  `id` int NOT NULL,
  `action` varchar(512) NOT NULL,
  `made_by` varchar(45) NOT NULL,
  `user_type` varchar(45) NOT NULL,
  `user_id` int NOT NULL,
  `action_type` varchar(75) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `department` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audits`
--

LOCK TABLES `audits` WRITE;
/*!40000 ALTER TABLE `audits` DISABLE KEYS */;
INSERT INTO `audits` VALUES (157471,'Resource Transaction No. 52709 Status Update(HDMI)','Rejean Sepuntos','Admin',202118555,'Claimed','2024-11-19 06:33:22','IT and IS'),(169571,'Resource Transaction No. 6797 Status Update(HDMI)','Rejean Sepuntos','Admin',202118555,'Claimed','2024-11-19 06:42:45','IT and IS'),(243879,'Updated Resource MacBook Pro M2 (266)','Mary Madelaine Escarlan','Admin',202112862,'Resource Update','2024-11-18 17:01:19','IT and IS'),(437121,'Resource Transaction No. 17018 Status Update(HDMI)','Mary Madelaine Escarlan','Admin',202112862,'Late','2024-11-23 06:53:34','IT and IS'),(517431,'Returned Resource Transaction No. 59544 (MacBook Pro M2)','Daniel Valdez','Master Admin',202118402,' No Remark Added','2024-11-23 08:30:01','IT and IS'),(579917,'Resource Transaction No. 2384 Status Update(6 Set Precision Screwdriver)','Rejean Sepuntos','Admin',202118555,'Returned','2024-11-19 06:00:55','IT and IS'),(689711,'Resource Transaction No. 17018 Status Update(HDMI)','Mary Madelaine Escarlan','Admin',202112862,'Claimed','2024-11-23 06:53:43','IT and IS'),(1095702,'Resource Transaction No. 52803 Status Update(MacBook Pro M2)','Daniel Valdez','Master Admin',202118402,'Accept','2024-11-16 14:01:23','IT and IS'),(1383286,'Resource Transaction No. 17018 Status Update(HDMI)','Daniel Valdez','Master Admin',202118402,'Returned','2024-11-23 08:29:09','IT and IS'),(1563888,'Resource Transaction No. 50042 Status Update(HDMI)','Rejean Sepuntos','Admin',202118555,'Claimed','2024-11-19 05:00:26','IT and IS'),(1583028,'Facility Transaction No. 11676 Status Update(Conference Room)','Daniel Valdez','Master Admin',202118402,'Pending','2024-11-23 06:21:27','IT and IS'),(1666304,'Resource Transaction No. 62210 Status Update(MacBook Pro M2)','Mary Madelaine Escarlan','Admin',202112862,'Cancelled','2024-11-23 06:48:53','IT and IS'),(1834884,'Reset Image to Default','Daniel Valdez','Master Admin',202118402,'Account Credential Update','2024-11-18 13:33:34','IT and IS'),(1870179,'Facility Transaction No. 55046 Status Update(Conference Room)','Daniel Valdez','Master Admin',202118402,'Pending','2024-11-22 07:16:19','IT and IS'),(1963514,'Resource Transaction No. 59544 Status Update(MacBook Pro M2)','Rejean Sepuntos','Admin',202118555,'Accept','2024-11-19 06:28:29','IT and IS'),(2015673,'Added remarks for Facility Transaction No. 11676','Mary Madelaine Escarlan','Admin',202112862,'Remark Added','2024-11-23 06:23:24','IT and IS'),(2146300,'Returned Resource Transaction No. 52709 (HDMI)','Rejean Sepuntos','Admin',202118555,' No Remark Added','2024-11-19 06:41:40','IT and IS'),(2157761,'Facility Transaction No. 55046 Status Update(Conference Room)','Daniel Valdez','Master Admin',202118402,'Accept','2024-11-22 07:31:03','IT and IS'),(2406927,'Resource Transaction No. 2384 Status Update(6 Set Precision Screwdriver)','Mary Madelaine Escarlan','Admin',202112862,'Accept','2024-11-18 17:35:54','IT and IS'),(2511451,'Approved Request','Rejean Sepuntos','Admin Faculty',202118555,'Noted By','2024-11-22 06:53:11','IT and IS'),(2614149,'Resource Transaction No. 52709 Status Update(HDMI)','Rejean Sepuntos','Admin',202118555,'Returned','2024-11-19 06:41:40','IT and IS'),(2666813,'Changed Image','Rejean Sepuntos','Admin',202118555,'Account Credential Update','2024-11-18 13:40:24','IT and IS'),(2940996,'Resource Transaction No. 17018 Status Update(HDMI)','Mary Madelaine Escarlan','Admin',202112862,'Late','2024-11-23 06:54:31','IT and IS'),(3118528,'Update Facility Conference Room(10)','Mary Madelaine Escarlan','Admin',202112862,'Facility Update','2024-11-23 06:39:47','IT and IS'),(3184298,'Resource Transaction No. 17018 Status Update(HDMI)','Rejean Sepuntos','Admin',202118555,'Accept','2024-11-19 06:51:37','IT and IS'),(3348453,'Facility Transaction No. 3293 Status Update(Conference Room)','Mary Madelaine Escarlan','Admin',202112862,'On-going','2024-11-23 06:31:57','IT and IS'),(3415037,'Added Resource Mouse (63042)','Rejean Sepuntos','Admin',202118555,'Resource Addition','2024-11-19 05:54:06','IT and IS'),(3474988,'Returned Resource Transaction No. 27811 (MacBook Pro M2)','Rejean Sepuntos','Admin',202118555,' No Remark Added','2024-11-19 06:28:00','IT and IS'),(3492484,'Facility Transaction No. 55046 Status Update(Conference Room)','Mary Madelaine Escarlan','Admin',202112862,'Accept','2024-11-22 07:51:44','IT and IS'),(3511830,'Resource Transaction No. 27811 Status Update(MacBook Pro M2)','Mary Madelaine Escarlan','Admin',202112862,'Accept','2024-11-18 17:08:03','IT and IS'),(3618848,'Facility Transaction No. 3293 Status Update(Conference Room)','Mary Madelaine Escarlan','Admin',202112862,'Pending','2024-11-23 06:24:09','IT and IS'),(3697416,'Resource Transaction No. 52803 Status Update(MacBook Pro M2)','Rejean Sepuntos','Admin',202118555,'Accept','2024-11-19 04:52:16','IT and IS'),(3704612,'Facility Transaction No. 11676 Status Update(Conference Room)','Mary Madelaine Escarlan','Admin',202112862,'Accept','2024-11-23 06:22:52','IT and IS'),(3721335,'Resource Transaction No. 62210 Status Update(MacBook Pro M2)','Mary Madelaine Escarlan','Admin',202112862,'Accept','2024-11-23 06:46:54','IT and IS'),(3734143,'Changed Image','Daniel Valdez','Master Admin',202118402,'Account Credential Update','2024-11-18 13:34:08','IT and IS'),(3811582,'Resource Transaction No. 27811 Status Update(MacBook Pro M2)','Rejean Sepuntos','Admin',202118555,'Returned','2024-11-19 06:28:00','IT and IS'),(3833552,'Resource Transaction No. 93549 Status Update(Mouse)','Mary Madelaine Escarlan','Admin',202112862,'Reject','2024-11-23 06:42:56','IT and IS'),(3953251,'Approved Request','Ervin Lacuarta','User Faculty',202118123,'Noted By','2024-11-16 10:59:59','IT and IS'),(4022755,'Facility Transaction No. 3293 Status Update(Conference Room)','Mary Madelaine Escarlan','Admin',202112862,'Completed','2024-11-23 06:38:58','IT and IS'),(4048661,'Changed Name','Daniels Valdez','System Admin',5,'Account Information Update','2024-11-19 07:20:49','System Maintenance'),(4094197,'Resource Transaction No. 6797 Status Update(HDMI)','Rejean Sepuntos','Admin',202118555,'Accept','2024-11-19 06:42:32','IT and IS'),(4392445,'Resource Transaction No. 2384 Status Update(6 Set Precision Screwdriver)','Rejean Sepuntos','Admin',202118555,'Claimed','2024-11-19 03:51:11','IT and IS'),(4423606,'Resource Transaction No. 27811 Status Update(MacBook Pro M2)','Rejean Sepuntos','Admin',202118555,'Claimed','2024-11-19 06:23:14','IT and IS'),(4481377,'Facility Transaction No. 3293 Status Update(Conference Room)','Mary Madelaine Escarlan','Admin',202112862,'Accept','2024-11-23 06:23:45','IT and IS'),(4715864,'Resource Transaction No. 17018 Status Update(HDMI)','Rejean Sepuntos','Admin',202118555,'Claimed','2024-11-19 06:51:54','IT and IS'),(4997540,'Resource Transaction No. 52803 Status Update(MacBook Pro M2)','Mary Madelaine Escarlan','Admin',202112862,'Pending','2024-11-18 17:14:33','IT and IS'),(5218337,'Update Facility Conference Room(10)','Mary Madelaine Escarlan','Admin',202112862,'Facility Update','2024-11-23 06:32:25','IT and IS'),(5391099,'Update Facility Conference Room(10)','Mary Madelaine Escarlan','Admin',202112862,'Facility Update','2024-11-23 06:33:00','IT and IS'),(5737603,'Resource Transaction No. 27811 Status Update(MacBook Pro M2)','Rejean Sepuntos','Admin',202118555,'Accept','2024-11-19 06:04:30','IT and IS'),(5997577,'Resource Transaction No. 27811 Status Update(MacBook Pro M2)','Mary Madelaine Escarlan','Admin',202112862,'Accept','2024-11-18 17:01:50','IT and IS'),(6061519,'Updated General Policy Equipment Policy','Mary Madelaine Escarlan','Admin',202112862,'Update General Policy','2024-11-22 10:50:59','IT and IS'),(6072089,'Returned Resource Transaction No. 50042 (HDMI)','Rejean Sepuntos','Admin',202118555,' No Remark Added','2024-11-19 05:59:19','IT and IS'),(6100974,'Resource Transaction No. 52803 Status Update(MacBook Pro M2)','Mary Madelaine Escarlan','Admin',202112862,'Accept','2024-11-18 17:14:17','IT and IS'),(6152774,'User: 202112862 Status: Inactive to Active','Daniel Valdez','Master Admin',202118402,'Update User Credential','2024-11-29 16:43:21','IT and IS'),(6307301,'Resource Transaction No. 6797 Status Update(HDMI)','Rejean Sepuntos','Admin',202118555,'Returned','2024-11-19 06:46:18','IT and IS'),(6479245,'Resource Transaction No. 50042 Status Update(HDMI)','Rejean Sepuntos','Admin',202118555,'Returned','2024-11-19 05:59:19','IT and IS'),(6522269,'Facility Transaction No. 11676 Status Update(Conference Room)','Mary Madelaine Escarlan','Admin',202112862,'Pending','2024-11-23 06:22:37','IT and IS'),(6548078,'Resource Transaction No. 59544 Status Update(MacBook Pro M2)','Rejean Sepuntos','Admin',202118555,'Claimed','2024-11-19 06:28:56','IT and IS'),(6681423,'Added remarks for Facility Transaction No. 55046','Mary Madelaine Escarlan','Admin',202112862,'Remark Added','2024-11-22 11:07:06','IT and IS'),(6692640,'Facility Transaction No. 55046 Status Update(Conference Room)','Mary Madelaine Escarlan','Admin',202112862,'Pending','2024-11-22 07:33:12','IT and IS'),(6850302,'Reset Image to Default','Daniel Valdez','Master Admin',202118402,'Account Credential Update','2024-11-18 13:34:21','IT and IS'),(6862858,'Update Facility Conference Room(10)','Mary Madelaine Escarlan','Admin',202112862,'Facility Update','2024-11-23 06:39:35','IT and IS'),(6951928,'Facility Transaction No. 55046 Status Update(Conference Room)','Mary Madelaine Escarlan','Admin',202112862,'Completed','2024-11-22 11:08:22','IT and IS'),(7300868,'Facility Transaction No. 11676 Status Update(Conference Room)','Mary Madelaine Escarlan','Admin',202112862,'On-going','2024-11-23 06:23:01','IT and IS'),(7597065,'Facility Transaction No. 55046 Status Update(Conference Room)','Daniel Valdez','Master Admin',202118402,'Accept','2024-11-22 07:14:47','IT and IS'),(7619341,'Rejected Request','Rejean Sepuntos','Admin Faculty',202118555,'Noted By','2024-11-22 06:54:03','IT and IS'),(7663743,'Returned Resource Transaction No. 52803 (MacBook Pro M2)','Rejean Sepuntos','Admin',202118555,' No Remark Added','2024-11-19 05:58:17','IT and IS'),(7725294,'Facility Transaction No. 11676 Status Update(Conference Room)','Mary Madelaine Escarlan','Admin',202112862,'Accept','2024-11-22 10:28:35','IT and IS'),(8095833,'Resource Transaction No. 94009 Status Update(MacBook Pro M2)','Rejean Sepuntos','Admin',202118555,'Reject','2024-11-19 04:49:05','IT and IS'),(8168950,'Resource Transaction No. 52803 Status Update(MacBook Pro M2)','Rejean Sepuntos','Admin',202118555,'Reject','2024-11-19 03:59:18','IT and IS'),(8175806,'Added remarks for Facility Transaction No. 55046','Mary Madelaine Escarlan','Admin',202112862,'Remark Added','2024-11-22 11:08:22','IT and IS'),(8538522,'Returned Resource Transaction No. 2384 (6 Set Precision Screwdriver)','Rejean Sepuntos','Admin',202118555,' No Remark Added','2024-11-19 06:00:55','IT and IS'),(8574128,'Resource Transaction No. 52803 Status Update(MacBook Pro M2)','Rejean Sepuntos','Admin',202118555,'Claimed','2024-11-19 04:52:33','IT and IS'),(8646688,'Returned Resource Transaction No. 6797 (HDMI)','Rejean Sepuntos','Admin',202118555,' No Remark Added','2024-11-19 06:46:18','IT and IS'),(8671937,'Facility Transaction No. 3293 Status Update(Conference Room)','Mary Madelaine Escarlan','Admin',202112862,'Accept','2024-11-23 06:27:32','IT and IS'),(8744649,'Updated Resource MacBook Pro M2 (266)','Mary Madelaine Escarlan','Admin',202112862,'Resource Update','2024-11-18 17:03:14','IT and IS'),(8760376,'Update Facility Conference Room(10)','Mary Madelaine Escarlan','Admin',202112862,'Facility Update','2024-11-23 06:33:25','IT and IS'),(8849960,'Resource Transaction No. 59544 Status Update(MacBook Pro M2)','Daniel Valdez','Master Admin',202118402,'Returned','2024-11-23 08:30:01','IT and IS'),(8908771,'Facility Transaction No. 11676 Status Update(Conference Room)','Mary Madelaine Escarlan','Admin',202112862,'Accept','2024-11-23 06:22:30','IT and IS'),(8945582,'Returned Resource Transaction No. 17018 (HDMI) - Remarks: Returned with Care <3','Daniel Valdez','Master Admin',202118402,'Remark Added','2024-11-23 08:29:09','IT and IS'),(9281639,'Updated General Policy Laboratory Items Policy','Mary Madelaine Escarlan','Admin',202112862,'Update General Policy','2024-11-23 07:01:06','IT and IS'),(9552724,'Changed Name','Daniel Valdez','System Admin',5,'Account Information Update','2024-11-19 07:20:32','System Maintenance'),(9628265,'Facility Transaction No. 55046 Status Update(Conference Room)','Mary Madelaine Escarlan','Admin',202112862,'Accept','2024-11-22 07:50:53','IT and IS'),(9637024,'Resource Transaction No. 52803 Status Update(MacBook Pro M2)','Rejean Sepuntos','Admin',202118555,'Returned','2024-11-19 05:58:17','IT and IS'),(9782838,'Facility Transaction No. 11676 Status Update(Conference Room)','Mary Madelaine Escarlan','Admin',202112862,'Completed','2024-11-23 06:23:24','IT and IS'),(9838081,'Resource Transaction No. 50042 Status Update(HDMI)','Rejean Sepuntos','Admin',202118555,'Accept','2024-11-19 05:00:07','IT and IS'),(9895285,'Resource Transaction No. 52709 Status Update(HDMI)','Rejean Sepuntos','Admin',202118555,'Accept','2024-11-19 06:32:25','IT and IS');
/*!40000 ALTER TABLE `audits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audits_archive`
--

DROP TABLE IF EXISTS `audits_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audits_archive` (
  `id` int NOT NULL,
  `action` varchar(512) NOT NULL,
  `made_by` varchar(45) NOT NULL,
  `user_type` varchar(45) NOT NULL,
  `user_id` int NOT NULL,
  `action_type` varchar(45) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `department` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audits_archive`
--

LOCK TABLES `audits_archive` WRITE;
/*!40000 ALTER TABLE `audits_archive` DISABLE KEYS */;
INSERT INTO `audits_archive` VALUES (180,'User: 202118402 Position: Student to Faculty, User: 202118402 User Type: 2 to 1','Rejean Sepuntos','Master Admin',202118555,'Update User Credential','2024-09-03 08:56:05','IT and IS'),(181,'Updated Policy Breadboard Policy','Rejean Sepuntos','Master Admin',202118555,'Update Policy','2024-09-04 08:41:58','IT and IS'),(182,'Updated Policy Breadboard Policy','Rejean Sepuntos','Master Admin',202118555,'Update Policy','2024-09-04 09:03:07','IT and IS'),(183,'Updated Policy Breadboard Policy','Rejean Sepuntos','Master Admin',202118555,'Update Policy','2024-09-04 09:03:18','IT and IS'),(184,'Updated Policy Breadboard Policy','Rejean Sepuntos','Master Admin',202118555,'Update Policy','2024-09-04 09:03:37','IT and IS'),(185,'Changed Image','Rejean Sepuntos','Admin',202118555,'Account Credential Update','2024-09-04 18:02:38','IT and IS'),(186,'Updated Resource MacBook M2 (266)','Rejean Sepuntos','Admin',202118555,'Resource Update','2024-09-05 11:57:52','IT and IS'),(187,'Updated Policy General Laptop Policy','Rejean Sepuntos','Admin',202118555,'Update Policy','2024-09-05 11:58:17','IT and IS'),(188,'Updated Policy No Policy','Rejean Sepuntos','Admin',202118555,'Update Policy','2024-09-05 12:02:57','IT and IS'),(189,'Updated Policy No Policy sndw','Rejean Sepuntos','Admin',202118555,'Update Policy','2024-09-05 12:07:05','IT and IS'),(190,'Updated Policy No Policy','Rejean Sepuntos','Admin',202118555,'Update Policy','2024-09-05 12:07:31','IT and IS'),(191,'Updated Policy No Policy s','Rejean Sepuntos','Admin',202118555,'Update Policy','2024-09-05 12:08:51','IT and IS'),(192,'Updated Policy General Laptop Policy','Rejean Sepuntos','Admin',202118555,'Update Policy','2024-09-05 12:09:06','IT and IS'),(193,'Deleted Policy General Laptop Policy','Rejean Sepuntos','Admin',202118555,'Delete Policy','2024-09-05 12:09:26','IT and IS'),(195,'Updated Policy Conference Room Policy 2','Rejean Sepuntos','Admin',202118555,'Update Policy','2024-09-05 12:19:23','IT and IS'),(196,'Updated Policy Conference Room Policy 3','Rejean Sepuntos','Admin',202118555,'Update Policy','2024-09-05 12:19:37','IT and IS'),(197,'Updated Policy Conference Room Polic 4','Rejean Sepuntos','Admin',202118555,'Update Policy','2024-09-05 12:19:45','IT and IS'),(198,'Updated Policy Conference Room Policy 4','Rejean Sepuntos','Admin',202118555,'Update Policy','2024-09-05 12:19:55','IT and IS'),(199,'Updated Policy General Laptop Policy','Rejean Sepuntos','Admin',202118555,'Update Policy','2024-09-05 12:20:04','IT and IS'),(200,'Deleted Policy Laptop Policy','Rejean Sepuntos','Admin',202118555,'Delete Policy','2024-09-05 12:20:16','IT and IS'),(201,'Updated Policy Conference Room Policy 5','Rejean Sepuntos','Admin',202118555,'Update Policy','2024-09-05 12:20:27','IT and IS'),(202,'Resource Transaction No. 1960 Status Update(MacBook M2)','Daniel Valdez','Master Admin',202118402,'Accept','2024-09-05 17:14:27','IT and IS'),(203,'Added Resource HDM (50042)','Daniel Valdez','Master Admin',202118402,'Resource Addition','2024-09-06 07:41:40','IT and IS'),(204,'Resource Transaction No. 1960 Status Update(MacBook M2)','Daniel Valdez','Master Admin',202118402,'Cancelled','2024-09-06 07:51:48','IT and IS'),(205,'Added Resource Macbook M2 (45356)','Daniel Valdez','Master Admin',202118402,'Resource Addition','2024-09-06 10:26:53','IT and IS'),(206,'Changed Image','Daniel Valdez','Master Admin',202118402,'Account Credential Update','2024-06-25 01:40:15','IT and IS'),(207,'Changed Image','Rejean Sepuntos','Admin',202118555,'Account Credential Update','2024-06-25 01:43:11','IT and IS'),(208,'Changed Image','Daniel Valdez','Master Admin',202118402,'Account Credential Update','2024-09-25 03:20:07','IT and IS'),(209,'Resource Transaction No. 27408 Status Update(MacBook M2)','Rejean Sepuntos','Admin',202118555,'Accept','2024-09-25 05:46:04','IT and IS'),(210,'Resource Transaction No. 27408 Status Update(MacBook M2)','Rejean Sepuntos','Admin',202118555,'Pending','2024-09-25 05:51:46','IT and IS'),(211,'User: 202112862 User Type: 1 to 2','Daniel Valdez','Master Admin',202118402,'Update User Credential','2024-09-25 05:59:08','IT and IS'),(212,'Changed Image','Daniel Valdez','Master Admin',202118402,'Account Credential Update','2024-09-25 06:04:47','IT and IS'),(213,'Updated Resource Mouse (98997)','Daniel Valdez','Master Admin',202118402,'Resource Update','2024-09-29 08:02:59','IT and IS'),(214,'Updated Resource Mouse (98997)','Daniel Valdez','Master Admin',202118402,'Resource Update','2024-09-29 08:03:31','IT and IS'),(215,'Changed Password','Daniel Valdez','System Admin',3,'Account Credential Update','2024-09-29 10:46:54','System Maintenance'),(216,'Changed Password','Daniel Valdez','System Admin',3,'Account Credential Update','2024-09-29 11:32:46','System Maintenance'),(217,'Updated Resource MacBook M2 (266)','Daniel Valdez','Master Admin',202118402,'Resource Update','2024-09-30 07:05:43','IT and IS'),(218,'Update Facility Conference Room 2(9)','Daniel Valdez','Master Admin',202118402,'Facility Update','2024-09-30 07:08:58','IT and IS'),(219,'Updated Resource MacBook M2 (266)','Daniel Valdez','Master Admin',202118402,'Resource Update','2024-09-30 07:16:31','IT and IS'),(220,'Updated Resource MacBook M2 (266)','Daniel Valdez','Master Admin',202118402,'Resource Update','2024-09-30 07:16:44','IT and IS'),(221,'Resource Transaction No. 27408 Status Update(MacBook M2)','Daniel Valdez','Master Admin',202118402,'Accept','2024-09-30 07:21:03','IT and IS'),(222,'Resource Transaction No. 27408 Status Update(MacBook M2)','Daniel Valdez','Master Admin',202118402,'Claimed','2024-09-30 07:21:19','IT and IS'),(223,'Resource Transaction No. 68904 Status Update(HDM)','Daniel Valdez','Master Admin',202118402,'Accept','2024-09-30 07:35:57','IT and IS'),(76963,'Deleted Policy Conference Room Policy','Rejean Sepuntos','Admin',202118555,'Delete Policy','2024-10-09 16:01:37','IT and IS'),(88497,'Facility Transaction No. 47163 Status Update(Conference Room 2)','Daniel Valdez','Master Admin',202118402,'Accept','2024-10-08 08:03:36','IT and IS'),(102188,'Updated Resource MacBook Pro M2 (266)','Daniel Valdez','Master Admin',202118402,'Resource Update','2024-10-30 16:33:49','IT and IS'),(125840,'Edited IT and IS Into IT and IS','Daniel Valdez','System Admin',3,'System Update','2024-10-08 07:56:10','System Maintenance'),(151423,'Resource Transaction No. 31962 Status Update(HDMI)','Daniel Valdez','Master Admin',202118402,'Accept','2024-10-30 17:00:49','IT and IS'),(185639,'Changed Image','Jamelah Ayens Tipon','Master Admin',202110278,'Account Credential Update','2024-10-07 11:32:34','Computer Science'),(205238,'Added remarks for Facility Transaction No. 17098','Rejean Sepuntos','Admin',202118555,'Remark Added','2024-10-08 03:35:41','IT and IS'),(207927,'Added Policy Projector','Jamelah Ayens Tipon','Master Admin',202110278,'Add Policy','2024-10-09 16:12:19','Computer Science'),(250090,'Updated General Policy Computer Science Lab Reservation Policy','Jamelah Ayen Tipon','Admin',202110278,'Update General Policy','2024-10-12 14:28:23','Computer Science'),(463874,'Added remarks for Resource Transaction No. 90639 (Testing Resource)','Rejean Sepuntos','Admin',202118555,'Remark Added','2024-10-07 12:22:44','IT and IS'),(472030,'Added Computer Engineering','Daniel Valdez','System Admin',5,'System Update','2024-11-15 05:23:04','System Maintenance'),(477986,'Changed Password','Daniel Valdez','System Admin',3,'Account Credential Update','2024-10-08 07:52:11','System Maintenance'),(502523,'Resource Transaction No. 21999 Status Update(6 Sets Precision Screwdriver)','Daniel Valdez','Master Admin',202118402,'Replacement','2024-10-09 02:03:44','IT and IS'),(530558,'Added General Policy Laboratory Policy','Mary Madelaine Escarlan','Admin',202112862,'Added General Policy','2024-11-22 10:51:30','IT and IS'),(581663,'Updated Policy Conference Room Policy','Rejean Sepuntos','Admin',202118555,'Update Policy','2024-10-09 16:00:18','IT and IS'),(645561,'Resource Transaction No. 55033 Status Update(HDMI)','Daniel Valdez','Master Admin',202118402,'Accept','2024-10-11 08:19:05','IT and IS'),(660642,'Approved Request','Mary Madelaine Escarlan','Admin',202112862,'Noted By','2024-11-10 18:26:03','IT and IS'),(708740,'Resource Transaction No. 68904 Status Update(HDMI)','Daniel Valdez','Master Admin',202118402,'Claimed','2024-10-10 05:19:38','IT and IS'),(716845,'Deleted Policy Conference Room Policy 4','Rejean Sepuntos','Admin',202118555,'Delete Policy','2024-10-09 16:00:26','IT and IS'),(818413,'Resource Transaction No. 89561 Status Update(MacBook Pro M2)','Daniel Valdez','Master Admin',202118402,'Accept','2024-10-11 10:43:05','IT and IS'),(893801,'Facility Transaction No. 47163 Status Update(Conference Room 2)','Daniel Valdez','Master Admin',202118402,'On-going','2024-10-08 08:04:58','IT and IS'),(911880,'Added Policy scanefoef','Luis Victor Jimenez','Master Admin',202111043,'Add Policy','2024-10-13 03:14:30','Physical Educations'),(926210,'Updated Resource 6 Set Precision Screwdriver (65243)','Daniel Valdez','Master Admin',202118402,'Resource Update','2024-10-09 17:28:10','IT and IS'),(1000460,'Resource Transaction No. 9528 Status Update(MacBook M2)','Daniel Valdez','Master Admin',202118402,'Accept','2024-10-08 07:55:37','IT and IS'),(1029264,'Resource Transaction No. 68358 Status Update(MacBook Pro M2)','Daniel Valdez','Master Admin',202118402,'Accept','2024-10-10 05:52:33','IT and IS'),(1133708,'Added remarks for Resource Transaction No. 68904 (HDMI)','Daniel Valdez','Master Admin',202118402,'Remark Added','2024-10-10 05:20:15','IT and IS'),(1159247,'Deleted Resource HDM (64691)','Rejean Sepuntos','Admin',202118555,'Delete Resource','2024-10-09 16:36:43','IT and IS'),(1213541,'Facility Transaction No. 73312 Status Update(Conference Room 2)','Rejean Sepuntos','Admin',202118555,'On-going','2024-10-03 16:19:23','IT and IS'),(1221926,'Facility Transaction No. 92425 Status Update(Conference Room)','Daniel Valdez','Master Admin',202118402,'Completed','2024-10-08 08:26:53','IT and IS'),(1246413,'Resource Transaction No. 68358 Status Update(MacBook Pro M2)','Daniel Valdez','Master Admin',202118402,'Claimed','2024-10-10 05:53:28','IT and IS'),(1349746,'Resource Transaction No. 84738 Status Update(HDMI)','Mary Madelaine Escarlan','Admin',202112862,'Reject','2024-11-12 06:10:38','IT and IS'),(1417404,'Resource Transaction No. 43801 Status Update(Macbook M2)','Daniel Valdez','Master Admin',202118402,'Reject','2024-10-02 16:48:12','IT and IS'),(1502962,'Deleted Resource Testing Resource (50866)','Daniel Valdez','Master Admin',202118402,'Delete Resource','2024-10-09 17:24:46','IT and IS'),(1512016,'Resource Transaction No. 55033 Status Update(HDMI)','Daniel Valdez','Master Admin',202118402,'Claimed','2024-10-11 08:19:24','IT and IS'),(1570802,'Resource Transaction No. 68904 Status Update(HDM Edited)','Rejean Sepuntos','Admin',202118555,'Pending','2024-10-07 10:56:10','IT and IS'),(1585556,'Resource Transaction No. 21786 Status Update(HDM)','Rejean Sepuntos','Admin',202118555,'Accept','2024-10-03 11:09:27','IT and IS'),(1603167,'Resource Transaction No. 55033 Status Update(HDMI)','Daniel Valdez','Master Admin',202118402,'Returned','2024-10-11 08:22:13','IT and IS'),(1622124,'Edited College of Education and Liberal Arts','Daniel Valdez','System Admin',5,'System Update','2024-10-13 02:27:57','System Maintenance'),(1673011,'Resource Transaction No. 68489 Status Update(MacBook M2)','Daniel Valdez','Master Admin',202118402,'Returned','2024-10-09 00:30:46','IT and IS'),(1704927,'Facility Transaction No. 73312 Status Update(Conference Room 2)','Rejean Sepuntos','Admin',202118555,'Accept','2024-10-03 16:18:28','IT and IS'),(1879660,'Updated Policy Precision Screwdriver Policy','Rejean Sepuntos','Admin',202118555,'Update Policy','2024-10-09 16:03:51','IT and IS'),(1889512,'Updated General Policy Conference Room','Rejean Sepuntos','Admin',202118555,'Update General Policy','2024-10-11 02:56:20','IT and IS'),(1972241,'Changed Image','Jamelah Ayens Tipon','Master Admin',202110278,'Account Credential Update','2024-10-10 17:15:04','Computer Science'),(2058473,'Added remarks for Resource Transaction No. 31962 (HDMI)','Daniel Valdez','Master Admin',202118402,'Remark Added','2024-11-15 16:14:18','IT and IS'),(2081397,'Facility Transaction No. 10104 Status Update(Conference Room 2)','Daniel Valdez','Master Admin',202118402,'Accept','2024-10-08 08:31:36','IT and IS'),(2123934,'Resource Transaction No. 68904 Status Update(HDM)','Rejean Sepuntos','Admin',202118555,'Pending','2024-09-30 17:31:15','IT and IS'),(2296102,'Updated General Policy Equipment Policy','Rejean Sepuntos','Admin',202118555,'Update General Policy','2024-10-12 16:26:40','IT and IS'),(2323895,'Updated Resource Mouse (98997)','Daniel Valdez','Master Admin',202118402,'Resource Update','2024-10-19 12:19:32','IT and IS'),(2324281,'Updated Resource MacBook Pro M2 (266)','Rejean Sepuntos','Admin',202118555,'Resource Update','2024-10-09 16:56:45','IT and IS'),(2348398,'Changed Password','Daniel Valdez','System Admin',3,'Account Credential Update','2024-10-10 18:43:28','System Maintenance'),(2384794,'Deleted Resource Macbook M2 (45356)','Daniel Valdez','Master Admin',202118402,'Delete Resource','2024-10-10 05:12:16','IT and IS'),(2389578,'Updated Resource MacBook Pro M2 (266)','Daniel Valdez','Master Admin',202118402,'Resource Update','2024-11-15 17:07:51','IT and IS'),(2426596,'Resource Transaction No. 21006 Status Update(Breadboard)','Jamelah Ayens Tipon','Master Admin',202110278,'Accept','2024-10-09 08:06:01','Computer Science'),(2471246,'Resource Transaction No. 18960 Status Update(MacBook Pro M2)','Rejean Sepuntos','Admin',202118555,'Returned','2024-10-10 07:10:17','IT and IS'),(2510031,'Resource Transaction No. 68904 Status Update(HDMI)','Daniel Valdez','Master Admin',202118402,'Accept','2024-10-10 05:18:04','IT and IS'),(2510116,'Updated Policy CS Conference Room Policy','Jamelah Ayens Tipon','Master Admin',202110278,'Update Policy','2024-10-09 15:57:18','Computer Science'),(2523588,'Resource Transaction No. 2342 Status Update(Mouse)','Daniel Valdez','Master Admin',202118402,'Returned','2024-10-11 05:34:40','IT and IS'),(2563881,'Updated Policy Breadboard Policy','Jamelah Ayens Tipon','Master Admin',202110278,'Update Policy','2024-10-09 15:56:31','Computer Science'),(2568177,'Updated Resource MacBook Pro M2 (266)','Daniel Valdez','Master Admin',202118402,'Resource Update','2024-10-30 16:36:37','IT and IS'),(2569018,'Changed Password','Daniel Valdez','System Admin',3,'Account Credential Update','2024-10-10 18:13:38','System Maintenance'),(2573513,'Resource Transaction No. 36474 Status Update(HDM)','Rejean Sepuntos','Admin',202118555,'Returned','2024-10-05 16:12:04','IT and IS'),(2580350,'Resource Transaction No. 39461 Status Update(Testing Resource)','Daniel Valdez','Master Admin',202118402,'Accept','2024-10-06 15:56:53','IT and IS'),(2636987,'Resource Transaction No. 39461 Status Update(Testing Resource)','Daniel Valdez','Master Admin',202118402,'Cancelled','2024-10-06 15:57:28','IT and IS'),(2714679,'Edited Computer Sciences Into Computer Science','Daniel Valdez','System Admin',3,'System Update','2024-10-08 08:00:29','System Maintenance'),(2746437,'Resource Transaction No. 90639 Status Update(Testing Resource)','Daniel Valdez','Master Admin',202118402,'Accept','2024-10-06 15:59:27','IT and IS'),(2775371,'Facility Transaction No. 92425 Status Update(Conference Room)','Daniel Valdez','Master Admin',202118402,'On-going','2024-10-08 08:26:34','IT and IS'),(2780430,'Added Resource Testing Resource (50866)','Rejean Sepuntos','Admin',202118555,'Resource Addition','2024-10-03 11:22:11','IT and IS'),(2872525,'Update Facility Conference Rooms(10)','Daniel Valdez','Master Admin',202118402,'Facility Update','2024-10-19 12:19:46','IT and IS'),(2901203,'Updated Policy General Laptop Policy','Rejean Sepuntos','Admin',202118555,'Update Policy','2024-10-09 15:58:49','IT and IS'),(2907526,'Facility Transaction No. 60024 Status Update(Conference Room)','Daniel Valdez','Master Admin',202118402,'On-going','2024-10-05 10:26:55','IT and IS'),(2934615,'Resource Transaction No. 30393 Status Update(Mouse)','Daniel Valdez','Master Admin',202118402,'Claimed','2024-11-15 16:30:37','IT and IS'),(2939355,'Added remarks for Resource Transaction No. 27408 (MacBook M2)','Daniel Valdez','Master Admin',202118402,'Remark Added','2024-10-08 07:44:01','IT and IS'),(2956399,'Facility Transaction No. 17098 Status Update(Conference Room 2)','Rejean Sepuntos','Admin',202118555,'Accept','2024-10-08 03:35:07','IT and IS'),(2994871,'Updated Resource Macbook M2 (45356)','Rejean Sepuntos','Admin',202118555,'Resource Update','2024-10-09 16:55:15','IT and IS'),(3236900,'Changed Image','Daniel Valdez','Master Admin',202118402,'Account Credential Update','2024-10-07 16:50:33','IT and IS'),(3253322,'Resource Transaction No. 84814 Status Update(HDMI)','Mary Madelaine Escarlan','Admin',202112862,'Reject','2024-10-11 11:51:44','IT and IS'),(3279190,'Added remarks for Resource Transaction No. 3032 (HDM)','Rejean Sepuntos','Admin',202118555,'Remark Added','2024-10-09 16:17:53','IT and IS'),(3296348,'Facility Transaction No. 17098 Status Update(Conference Room 2)','Rejean Sepuntos','Admin',202118555,'Completed','2024-10-08 03:35:41','IT and IS'),(3341538,'Changed Password','Rejean Sepuntos','Admin',202118555,'Account Credential Update','2024-10-10 17:14:15','IT and IS'),(3447389,'Approved Request','Ervin Lacuarta','1 (Faculty)',202118123,'Noted By','2024-10-11 11:38:33','IT and IS'),(3462608,'Resource Transaction No. 18960 Status Update(MacBook Pro M2)','Rejean Sepuntos','Admin',202118555,'Accept','2024-10-10 07:08:14','IT and IS'),(3491926,'Deleted Policy Conference Room Policy 3','Rejean Sepuntos','Admin',202118555,'Delete Policy','2024-10-09 16:00:30','IT and IS'),(3493272,'Updated Policy scanefoefsssssss','Luis Victor Jimenez','Master Admin',202111043,'Update Policy','2024-10-13 03:15:54','Physical Educations'),(3558822,'Deleted Facility Conference Room 2 (9)','Rejean Sepuntos','Admin',202118555,'Delete Facility','2024-10-09 16:45:34','IT and IS'),(3559115,'Resource Transaction No. 3032 Status Update(HDM)','Rejean Sepuntos','Admin',202118555,'Returned','2024-10-09 16:17:53','IT and IS'),(3559815,'Resource Transaction No. 90639 Status Update(Testing Resource)','Rejean Sepuntos','Admin',202118555,'Claimed','2024-10-07 12:22:30','IT and IS'),(3674639,'Facility Transaction No. 18123 Status Update(Conference Room 2)','Rejean Sepuntos','Admin',202118555,'On-going','2024-10-08 03:31:09','IT and IS'),(3896874,'Resource Transaction No. 99046 Status Update(HDM)','Daniel Valdez','Master Admin',202118402,'Reject','2024-10-06 14:08:06','IT and IS'),(3954472,'Updated General Policy Equipment Policy','Rejean Sepuntos','Admin',202118555,'Update General Policy','2024-10-11 02:52:48','IT and IS'),(3956574,'Resource Transaction No. 21999 Status Update(6 Sets Precision Screwdriver)','Rejean Sepuntos','Admin',202118555,'Claimed','2024-10-09 02:03:30','IT and IS'),(3970236,'Updated Resource HDMI (50042)','Daniel Valdez','Master Admin',202118402,'Resource Update','2024-10-30 17:01:17','IT and IS'),(3987043,'Updated General Policy Equipment','Daniel Valdez','Master Admin',202118402,'Update General Policy','2024-10-06 17:43:32','IT and IS'),(4023056,'User: 202118555 User Type: 3 to 2','Daniel Valdez','Master Admin',202118402,'Update User Credential','2024-10-10 17:29:10','IT and IS'),(4033460,'Resource Transaction No. 68489 Status Update(MacBook M2)','Daniel Valdez','Master Admin',202118402,'Accept','2024-10-08 17:00:12','IT and IS'),(4042981,'Added remarks for Resource Transaction No. 99335 (Mouse)','Mary Madelaine Escarlan','Admin',202112862,'Remark Added','2024-10-11 11:46:30','IT and IS'),(4082510,'Updated Resource HDMI (50042)','Daniel Valdez','Master Admin',202118402,'Resource Update','2024-10-19 12:19:00','IT and IS'),(4137237,'Added remarks for Resource Transaction No. 68489 (MacBook M2)','Daniel Valdez','Master Admin',202118402,'Remark Added','2024-10-09 00:30:46','IT and IS'),(4148625,'Resource Transaction No. 36474 Status Update(HDM)','Daniel Valdez','Master Admin',202118402,'Claimed','2024-10-05 16:10:51','IT and IS'),(4189525,'Facility Transaction No. 92425 Status Update(Conference Room)','Daniel Valdez','Master Admin',202118402,'Accept','2024-10-08 08:22:52','IT and IS'),(4194084,'Added Resource MacBook Air M2 (46543)','Jamelah Ayens Tipon','Master Admin',202110278,'Resource Addition','2024-10-09 16:13:38','Computer Science'),(4242602,'Added remarks for Resource Transaction No. 85711 (HDM)','Daniel Valdez','Master Admin',202118402,'Remark Added','2024-10-08 07:52:05','IT and IS'),(4274863,'Edited Computer Science Into Computer Science','Daniel Valdez','System Admin',3,'System Update','2024-10-08 07:57:37','System Maintenance'),(4281560,'Resource Transaction No. 21786 Status Update(HDM)','Rejean Sepuntos','Admin',202118555,'Cancelled','2024-10-03 11:26:43','IT and IS'),(4290810,'Added Policy HDMI Policy','Jamelah Ayens Tipon','Master Admin',202110278,'Add Policy','2024-10-09 16:11:09','Computer Science'),(4298523,'Approved Request','Ervin Lacuarta','1 (Faculty)',202118123,'Noted By','2024-10-11 07:53:11','IT and IS'),(4325429,'Resource Transaction No. 68904 Status Update(HDMI)','Daniel Valdez','Master Admin',202118402,'Returned','2024-10-10 05:20:15','IT and IS'),(4334714,'Update Facility Conference Room(10)','Daniel Valdez','Master Admin',202118402,'Facility Update','2024-10-19 12:19:58','IT and IS'),(4393217,'Updated Resource MacBook Pro M2 (266)','Daniel Valdez','Master Admin',202118402,'Resource Update','2024-10-30 16:36:21','IT and IS'),(4409872,'Edited IT and ISs Into IT and ISs','Daniel Valdez','System Admin',3,'System Update','2024-10-08 07:56:00','System Maintenance'),(4506519,'Resource Transaction No. 17018 Status Update(HDMI)','Mary Madelaine Escarlan','Admin',202112862,'Claimed','2024-11-23 06:54:52','IT and IS'),(4554688,'Updated Resource 6 Set Precision Screwdriver (65243)','Daniel Valdez','Master Admin',202118402,'Resource Update','2024-10-09 17:28:34','IT and IS'),(4601164,'Added Policy Laptop Policy','KC Colango','Admin',202113240,'Add Policy','2024-10-09 16:14:46','Computer Science'),(4696116,'Edited College of Education and Liberal Arts','Daniel Valdez','System Admin',5,'System Update','2024-10-13 02:30:06','System Maintenance'),(4702313,'Resource Transaction No. 90639 Status Update(Testing Resource)','Rejean Sepuntos','Admin',202118555,'Returned','2024-10-07 12:22:44','IT and IS'),(5055530,'Deleted Policy No Policy','Rejean Sepuntos','Admin',202118555,'Delete Policy','2024-10-09 16:02:58','IT and IS'),(5130243,'Resource Transaction No. 27408 Status Update(MacBook M2)','Daniel Valdez','Master Admin',202118402,'Returned','2024-10-08 07:44:01','IT and IS'),(5211877,'Resource Transaction No. 99335 Status Update(Mouse)','Daniel Valdez','Master Admin',202118402,'Accept','2024-10-11 11:39:43','IT and IS'),(5217068,'Updated Resource Mouse (98997)','Daniel Valdez','Master Admin',202118402,'Resource Update','2024-10-09 17:27:33','IT and IS'),(5268566,'Update Facility Conference Room(10)','Rejean Sepuntos','Admin',202118555,'Facility Update','2024-10-13 03:28:39','IT and IS'),(5277457,'Resource Transaction No. 31962 Status Update(HDMI)','Daniel Valdez','Master Admin',202118402,'Cancelled','2024-11-15 16:14:18','IT and IS'),(5344678,'Facility Transaction No. 47163 Status Update(Conference Room 2)','Daniel Valdez','Master Admin',202118402,'Completed','2024-10-08 08:05:17','IT and IS'),(5369107,'Resource Transaction No. 90639 Status Update(Testing Resource)','Rejean Sepuntos','Admin',202118555,'Accept','2024-10-07 10:56:38','IT and IS'),(5393370,'User: 202112862 Status: Inactive to Active','Daniel Valdez','Master Admin',202118402,'Update User Credential','2024-11-12 15:51:32','IT and IS'),(5487680,'Resource Transaction No. 68489 Status Update(MacBook M2)','Daniel Valdez','Master Admin',202118402,'Accept','2024-10-08 16:47:41','IT and IS'),(5596274,'Facility Transaction No. 73312 Status Update(Conference Room 2)','Rejean Sepuntos','Admin',202118555,'Accept','2024-10-03 16:19:06','IT and IS'),(5610885,'Facility Transaction No. 73312 Status Update(Conference Room 2)','Rejean Sepuntos','Admin',202118555,'Pending','2024-10-03 16:18:46','IT and IS'),(5618111,'Update Facility Conference Room(10)','Daniel Valdez','Master Admin',202118402,'Facility Update','2024-10-19 12:08:27','IT and IS'),(5621322,'Resource Transaction No. 31962 Status Update(HDMI)','Daniel Valdez','Master Admin',202118402,'Cancelled','2024-11-15 16:19:55','IT and IS'),(5630035,'Added Policy Screwdrivers Policy','KC Colango','Admin',202113240,'Add Policy','2024-10-09 16:08:21','Computer Science'),(5670696,'Updated General Policy Equipment','Rejean Sepuntos','Admin',202118555,'Update General Policy','2024-09-30 16:52:52','IT and IS'),(5683515,'Resource Transaction No. 58695 Status Update(6 Set Precision Screwdriver)','Daniel Valdez','Master Admin',202118402,'Reject','2024-11-10 18:33:26','IT and IS'),(5702566,'Added Mechanical Engineering','Daniel Valdez','System Admin',5,'System Update','2024-11-15 08:22:24','System Maintenance'),(5722238,'Added Policy Mouse Policy','Rejean Sepuntos','Admin',202118555,'Add Policy','2024-10-09 17:27:13','IT and IS'),(5735336,'Updated General Policy Equipment','Daniel Valdez','Master Admin',202118402,'Update General Policy','2024-10-06 17:40:18','IT and IS'),(5735442,'Edited Computer Science Into Computer Sciences','Daniel Valdez','System Admin',3,'System Update','2024-10-08 08:00:22','System Maintenance'),(5775960,'Updated Policy Breadboard Policy','Jamelah Ayens Tipon','Master Admin',202110278,'Update Policy','2024-10-08 16:42:19','Computer Science'),(5799340,'Approved Request','Rejean Sepuntos','2 (Faculty)',202118555,'Noted By','2024-10-11 04:40:09','IT and IS'),(5828737,'Resource Transaction No. 68489 Status Update(MacBook M2)','Daniel Valdez','Master Admin',202118402,'Pending','2024-10-08 16:52:49','IT and IS'),(5828881,'Added Mechanical Engineering','Daniel Valdez','System Admin',5,'System Update','2024-11-15 08:29:01','System Maintenance'),(6128965,'Updated Resource HDMI (50042)','Rejean Sepuntos','Admin',202118555,'Resource Update','2024-10-09 16:56:21','IT and IS'),(6147379,'Added remarks for Resource Transaction No. 31962 (HDMI)','Daniel Valdez','Master Admin',202118402,'Remark Added','2024-11-15 16:19:55','IT and IS'),(6196217,'User: 202112862 First Name: Mary Madelaine to Mary Madelaines','Daniel Valdez','Master Admin',202118402,'Update User Credential','2024-10-19 14:55:36','IT and IS'),(6278298,'Updated Resource Mouse (98997)','Daniel Valdez','Master Admin',202118402,'Resource Update','2024-10-19 12:19:13','IT and IS'),(6432888,'Added remarks for Resource Transaction No. 2342 (Mouse)','Daniel Valdez','Master Admin',202118402,'Remark Added','2024-10-11 05:34:40','IT and IS'),(6513994,'Updated Resource HDMIZ (50042)','Daniel Valdez','Master Admin',202118402,'Resource Update','2024-10-19 12:18:50','IT and IS'),(6541732,'Resource Transaction No. 68489 Status Update(MacBook M2)','Daniel Valdez','Master Admin',202118402,'Pending','2024-10-08 16:56:03','IT and IS'),(6627161,'Resource Transaction No. 21999 Status Update(6 Sets Precision Screwdriver)','Daniel Valdez','Master Admin',202118402,'Accept','2024-10-09 02:00:58','IT and IS'),(6658241,'Resource Transaction No. 2342 Status Update(Mouse)','Daniel Valdez','Master Admin',202118402,'Claimed','2024-10-11 05:32:16','IT and IS'),(6697311,'Changed Password','Daniel Valdez','System Admin',3,'Account Credential Update','2024-10-09 00:38:27','System Maintenance'),(6752397,'User: 202112862 First Name: Mary Madelaines to Mary Madelaine, User: 202112862 Position: Student to Faculty','Daniel Valdez','Master Admin',202118402,'Update User Credential','2024-10-19 15:06:18','IT and IS'),(6786809,'Resource Transaction No. 85711 Status Update(HDM)','Rejean Sepuntos','Admin',202118555,'Claimed','2024-10-08 03:30:26','IT and IS'),(6801983,'Update Facility Conference Room 2(9)','Daniel Valdez','Master Admin',202118402,'Facility Update','2024-09-30 18:54:28','IT and IS'),(6818146,'Updated Resource HDMI (50042)','Daniel Valdez','Master Admin',202118402,'Resource Update','2024-10-08 16:35:51','IT and IS'),(6844052,'Facility Transaction No. 73312 Status Update(Conference Room 2)','Rejean Sepuntos','Admin',202118555,'Completed','2024-10-03 16:19:46','IT and IS'),(6946247,'Resource Transaction No. 68904 Status Update(HDM)','Rejean Sepuntos','Admin',202118555,'Accept','2024-09-30 17:31:40','IT and IS'),(6977668,'Update Facility Conference Room(10)','Daniel Valdez','Master Admin',202118402,'Facility Update','2024-10-19 12:10:21','IT and IS'),(6991835,'Resource Transaction No. 12079 Status Update(HDM)','Rejean Sepuntos','Admin',202118555,'Reject','2024-10-03 11:28:40','IT and IS'),(6992060,'Resource Transaction No. 85711 Status Update(HDM)','Rejean Sepuntos','Admin',202118555,'Accept','2024-10-07 11:08:30','IT and IS'),(7041296,'Resource Transaction No. 90639 Status Update(Testing Resource)','Rejean Sepuntos','Admin',202118555,'Pending','2024-10-07 10:55:48','IT and IS'),(7088314,'Added remarks for Resource Transaction No. 18960 (MacBook Pro M2)','Rejean Sepuntos','Admin',202118555,'Remark Added','2024-10-10 07:10:17','IT and IS'),(7143046,'Facility Transaction No. 18123 Status Update(Conference Room 2)','Rejean Sepuntos','Admin',202118555,'Accept','2024-10-08 03:30:10','IT and IS'),(7145607,'Added remarks for Resource Transaction No. 55033 (HDMI)','Daniel Valdez','Master Admin',202118402,'Remark Added','2024-10-11 08:22:13','IT and IS'),(7192362,'Deleted Policy Sample Title','Rejean Sepuntos','Admin',202118555,'Delete Policy','2024-10-09 16:02:35','IT and IS'),(7330962,'Facility Transaction No. 10104 Status Update(Conference Room 2)','Daniel Valdez','Master Admin',202118402,'Completed','2024-10-08 08:32:09','IT and IS'),(7338655,'Updated Resource Breadboard (67508)','Jamelah Ayens Tipon','Master Admin',202110278,'Resource Update','2024-10-09 15:55:17','Computer Science'),(7365113,'Updated Resource HDMI (50042)','Daniel Valdez','Master Admin',202118402,'Resource Update','2024-10-19 12:00:26','IT and IS'),(7556672,'Added Physical Education','Daniel Valdez','System Admin',5,'System Update','2024-10-13 01:54:28','System Maintenance'),(7592402,'Added remarks for Resource Transaction No. 68358 (MacBook Pro M2)','Daniel Valdez','Master Admin',202118402,'Remark Added','2024-10-10 05:53:55','IT and IS'),(7600007,'Update Facility CS AVR(11)','Jamelah Ayens Tipon','Master Admin',202110278,'Facility Update','2024-10-09 16:17:13','Computer Science'),(7612559,'Resource Transaction No. 3032 Status Update(HDM)','Rejean Sepuntos','Admin',202118555,'Claimed','2024-10-09 01:02:32','IT and IS'),(7678039,'Facility Transaction No. 60024 Status Update(Conference Room)','Daniel Valdez','Master Admin',202118402,'Completed','2024-10-05 10:27:15','IT and IS'),(7696725,'Changed Image','Rejean Sepuntos','Admin',202118555,'Account Credential Update','2024-10-07 16:55:42','IT and IS'),(7705810,'Resource Transaction No. 3032 Status Update(HDM)','Rejean Sepuntos','Admin',202118555,'Accept','2024-10-09 01:01:12','IT and IS'),(7725907,'Deleted Policy Breadboard Policy','Rejean Sepuntos','Admin',202118555,'Delete Policy','2024-10-09 16:02:28','IT and IS'),(7813190,'Added Robotics Engineering','Daniel Valdez','System Admin',5,'System Update','2024-11-15 08:35:51','System Maintenance'),(7817806,'Changed Password','Daniel Valdez','System Admin',3,'Account Credential Update','2024-10-08 17:48:57','System Maintenance'),(7820191,'Changed Image','Jamelah Ayen Tipon','Master Admin',202110278,'Account Credential Update','2024-10-10 17:49:33','Computer Science'),(7831742,'Facility Transaction No. 17098 Status Update(Conference Room 2)','Rejean Sepuntos','Admin',202118555,'On-going','2024-10-08 03:35:23','IT and IS'),(7841430,'Updated Policy General Laptop Policy','Rejean Sepuntos','Admin',202118555,'Update Policy','2024-10-07 11:10:12','IT and IS'),(7909798,'Resource Transaction No. 99335 Status Update(Mouse)','Mary Madelaine Escarlan','Admin',202112862,'Returned','2024-10-11 11:46:30','IT and IS'),(7923931,'Facility Transaction No. 10104 Status Update(Conference Room 2)','Daniel Valdez','Master Admin',202118402,'On-going','2024-10-08 08:31:50','IT and IS'),(7953915,'Updated Resource HDMI (50042)','Daniel Valdez','Master Admin',202118402,'Resource Update','2024-10-30 17:01:07','IT and IS'),(7991305,'Updated General Policy Equipment Borrowing Policy','Jamelah Ayen Tipon','Admin',202110278,'Update General Policy','2024-10-12 14:21:49','Computer Science'),(8021523,'Resource Transaction No. 9528 Status Update(MacBook M2)','Daniel Valdez','Master Admin',202118402,'Returned','2024-10-08 07:56:20','IT and IS'),(8057798,'Updated Resource MacBook Air M2 (46543)','Jamelah Ayens Tipon','Master Admin',202110278,'Resource Update','2024-10-09 16:15:10','Computer Science'),(8073598,'Resource Transaction No. 68489 Status Update(MacBook M2)','Daniel Valdez','Master Admin',202118402,'Claimed','2024-10-09 00:28:22','IT and IS'),(8182844,'Updated Resource MacBook Pro M2 (266)','Daniel Valdez','Master Admin',202118402,'Resource Update','2024-10-30 16:36:50','IT and IS'),(8281949,'Updated Resource HDM Edited (50042)','Rejean Sepuntos','Admin',202118555,'Resource Update','2024-10-03 11:27:29','IT and IS'),(8285445,'Update Facility Conference Room(10)','Daniel Valdez','Master Admin',202118402,'Facility Update','2024-10-19 12:07:33','IT and IS'),(8285957,'Added remarks for Resource Transaction No. 9528 (MacBook M2)','Daniel Valdez','Master Admin',202118402,'Remark Added','2024-10-08 07:56:20','IT and IS'),(8367610,'Added Resource 6 Set Precision Screwdriver (29779)','Jamelah Ayens Tipon','Master Admin',202110278,'Resource Addition','2024-10-09 16:08:53','Computer Science'),(8385634,'Resource Transaction No. 66301 Status Update(Testing Resource)','Daniel Valdez','Master Admin',202118402,'Accept','2024-10-06 15:57:44','IT and IS'),(8558663,'Resource Transaction No. 30393 Status Update(Mouse)','Daniel Valdez','Master Admin',202118402,'Accept','2024-10-11 16:43:24','IT and IS'),(8613553,'Deleted Policy Conference Room Policy 5','Rejean Sepuntos','Admin',202118555,'Delete Policy','2024-10-09 16:02:50','IT and IS'),(8663410,'Changed Password','Daniel Valdez','System Admin',3,'Account Credential Update','2024-10-08 17:40:47','System Maintenance'),(8675439,'Edited Computer Sciences Into Computer Sciences','Daniel Valdez','System Admin',3,'System Update','2024-10-08 07:57:31','System Maintenance'),(8733015,'Updated Policy Breadboard Policy','Rejean Sepuntos','Admin',202118555,'Update Policy','2024-10-09 15:57:48','IT and IS'),(8747928,'Updated Resource Mouse (98997)','Daniel Valdez','Master Admin',202118402,'Resource Update','2024-10-09 17:26:01','IT and IS'),(8800057,'Resource Transaction No. 56126 Status Update(HDM)','Daniel Valdez','Master Admin',202118402,'Reject','2024-10-06 14:08:26','IT and IS'),(8820096,'Changed Password','Daniel Valdez','System Admin',3,'Account Credential Update','2024-10-10 18:13:18','System Maintenance'),(8847086,'Edited Physical Education Into Physical Educations','Daniel Valdez','System Admin',5,'System Update','2024-10-13 02:30:31','System Maintenance'),(8871579,'User: 202118555 User Type: 2 to 3','Daniel Valdez','Master Admin',202118402,'Update User Credential','2024-10-10 17:29:00','IT and IS'),(8966717,'Facility Transaction No. 60024 Status Update(Conference Room)','Daniel Valdez','Master Admin',202118402,'Accept','2024-10-03 16:22:27','IT and IS'),(9050730,'Resource Transaction No. 31962 Status Update(HDMI)','Daniel Valdez','Master Admin',202118402,'Accept','2024-11-15 16:16:26','IT and IS'),(9070385,'Resource Transaction No. 89561 Status Update(MacBook Pro M2)','Daniel Valdez','Master Admin',202118402,'Claimed','2024-10-11 10:43:24','IT and IS'),(9097417,'Resource Transaction No. 89561 Status Update(MacBook Pro M2)','Daniel Valdez','Master Admin',202118402,'Replacement','2024-10-11 10:43:44','IT and IS'),(9152292,'Updated General Policy Equipment Policy','Rejean Sepuntos','Admin',202118555,'Update General Policy','2024-10-12 16:26:21','IT and IS'),(9200728,'User: 202112862 Status: 1 to 0','Daniel Valdez','Master Admin',202118402,'Update User Credential','2024-11-12 15:42:31','IT and IS'),(9340604,'Returned Resource Transaction No. 30393 (Mouse) - Remarks: Returned with Damage','Daniel Valdez','Master Admin',202118402,'Remark Added','2024-11-15 16:31:46','IT and IS'),(9393967,'Resource Transaction No. 68489 Status Update(MacBook M2)','Daniel Valdez','Master Admin',202118402,'Accept','2024-10-08 16:54:44','IT and IS'),(9414275,'Resource Transaction No. 2342 Status Update(Mouse)','Daniel Valdez','Master Admin',202118402,'Accept','2024-10-11 05:30:06','IT and IS'),(9455216,'Resource Transaction No. 68358 Status Update(MacBook Pro M2)','Daniel Valdez','Master Admin',202118402,'Returned','2024-10-10 05:53:55','IT and IS'),(9523892,'Added Policy Remote Policy','Jamelah Ayens Tipon','Master Admin',202110278,'Add Policy','2024-10-09 16:10:24','Computer Science'),(9534015,'Changed Registration Policy','Daniel Valdez','System Admin',5,'System Update','2024-10-12 21:24:04','System Maintenance'),(9546650,'Deleted Resource MacBook Pro M2 (266)','Daniel Valdez','Master Admin',202118402,'Delete Resource','2024-10-30 16:34:05','IT and IS'),(9547359,'Added remarks for Facility Transaction No. 10104','Daniel Valdez','Master Admin',202118402,'Remark Added','2024-10-08 08:32:09','IT and IS'),(9554545,'Resource Transaction No. 85711 Status Update(HDM)','Daniel Valdez','Master Admin',202118402,'Returned','2024-10-08 07:52:05','IT and IS'),(9556827,'Resource Transaction No. 99335 Status Update(Mouse)','Daniel Valdez','Master Admin',202118402,'Claimed','2024-10-11 11:40:15','IT and IS'),(9601059,'Updated Resource HDMI (50042)','Daniel Valdez','Master Admin',202118402,'Resource Update','2024-10-19 11:59:54','IT and IS'),(9649891,'Updated Resource HDMI (50042)','Daniel Valdez','Master Admin',202118402,'Resource Update','2024-11-15 17:07:41','IT and IS'),(9709436,'Facility Transaction No. 18123 Status Update(Conference Room 2)','Rejean Sepuntos','Admin',202118555,'Completed','2024-10-08 03:33:28','IT and IS'),(9748899,'Added Policy HDMI Policy','Rejean Sepuntos','Admin',202118555,'Add Policy','2024-10-09 16:20:30','IT and IS'),(9789460,'Returned Resource Transaction No. 30393 (Mouse) - Remarks: Returned with Damage','Daniel Valdez','Master Admin',202118402,'Remark Added','2024-11-15 16:31:01','IT and IS'),(9800456,'Updated General Policy Equipment Policy','Rejean Sepuntos','Admin',202118555,'Update General Policy','2024-10-12 16:26:15','IT and IS'),(9851367,'Resource Transaction No. 18960 Status Update(MacBook Pro M2)','Rejean Sepuntos','Admin',202118555,'Claimed','2024-10-10 07:09:21','IT and IS'),(9867212,'Resource Transaction No. 9528 Status Update(MacBook M2)','Daniel Valdez','Master Admin',202118402,'Claimed','2024-10-08 07:55:53','IT and IS'),(9915969,'Updated General Policy Equipment Borrowing Policy','Jamelah Ayen Tipon','Admin',202110278,'Update General Policy','2024-10-12 16:31:36','Computer Science');
/*!40000 ALTER TABLE `audits_archive` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms`
--

DROP TABLE IF EXISTS `cms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cms` (
  `cms_id` int NOT NULL,
  `color` varchar(45) NOT NULL,
  `dept_id` int NOT NULL,
  `bg_image` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `email` varchar(75) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edited_at` datetime DEFAULT NULL,
  `edited_by` int DEFAULT NULL,
  PRIMARY KEY (`cms_id`),
  KEY `fk_dept_id_idx` (`dept_id`),
  CONSTRAINT `fk_dept_id` FOREIGN KEY (`dept_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms`
--

LOCK TABLES `cms` WRITE;
/*!40000 ALTER TABLE `cms` DISABLE KEYS */;
INSERT INTO `cms` VALUES (0,'#E9F3FD',0,'assets/cosbg.png','assets/reserved_resources.png','@adamson.edu.ph','2024-06-16 18:44:42','2024-08-17 16:38:25',202118402),(1,'#e1efed',1,'IT_and_IS/background/202118402_Valdez_1728405440.png','assets/reserved_resources.png','','2024-06-16 18:44:42','2024-10-09 00:38:31',202118402),(2,'#e5f2fb',2,'Computer_Science/background/202110278_Tipon_1723920095.png','assets/reserved_resources.png','','2024-06-16 18:44:42','2024-08-17 18:44:52',202110278),(3,'#f4fced',3,'assets/cosbg.png','assets/reserved_resources.png','','2024-06-16 18:44:42',NULL,NULL),(4,'#ffedf1',4,'assets/cosbg.png','assets/reserved_resources.png','','2024-06-16 18:44:42',NULL,NULL),(5,'#feedff',5,'assets/cosbg.png','assets/reserved_resources.png','','2024-06-16 18:44:42',NULL,NULL),(6,'#f2fcf4',6,'assets/cosbg.png','assets/reserved_resources.png','','2024-06-16 18:44:42','2024-09-04 16:14:55',202118402),(18481,'#E9F3FD',28,'assets/cosbg.png','assets/reserved_resources.png',NULL,'2024-10-13 09:54:28',NULL,NULL),(41461,'#E9F3FD',29,'assets/cosbg.png','assets/reserved_resources.png',NULL,'2024-11-15 13:23:04',NULL,NULL),(70211,'#E9F3FD',34,'assets/cosbg.png','assets/reserved_resources.png',NULL,'2024-11-15 16:35:51',NULL,NULL);
/*!40000 ALTER TABLE `cms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_about`
--

DROP TABLE IF EXISTS `cms_about`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cms_about` (
  `id` int NOT NULL AUTO_INCREMENT,
  `header` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `department` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_cms_about_idx` (`department`),
  CONSTRAINT `fk_cms_about` FOREIGN KEY (`department`) REFERENCES `departments` (`department_name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_about`
--

LOCK TABLES `cms_about` WRITE;
/*!40000 ALTER TABLE `cms_about` DISABLE KEYS */;
INSERT INTO `cms_about` VALUES (1,'About IT and IS','We will be exploring Machine Learning further soon! Stay Updated.','IT and IS','2024-08-18 00:37:05','2024-08-18 00:40:28'),(2,'Computer Science','Journey with Innovations.','Computer Science','2024-09-03 00:40:28','2024-09-03 00:40:28'),(3,'Psych','Asylum','Psychology','2024-09-04 08:15:11','2024-09-04 08:15:11');
/*!40000 ALTER TABLE `cms_about` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_reservation_duration`
--

DROP TABLE IF EXISTS `cms_reservation_duration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cms_reservation_duration` (
  `id` int NOT NULL AUTO_INCREMENT,
  `duration` int NOT NULL,
  `department` varchar(50) NOT NULL,
  `cms_resource_type` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_dept_duration_idx` (`department`) /*!80000 INVISIBLE */,
  KEY `fk_cms_res_type_idx` (`cms_resource_type`),
  CONSTRAINT `fk_cms_res_type` FOREIGN KEY (`cms_resource_type`) REFERENCES `resource_type` (`resource_type`) ON UPDATE CASCADE,
  CONSTRAINT `fk_dept_duration` FOREIGN KEY (`department`) REFERENCES `departments` (`department_name`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_reservation_duration`
--

LOCK TABLES `cms_reservation_duration` WRITE;
/*!40000 ALTER TABLE `cms_reservation_duration` DISABLE KEYS */;
INSERT INTO `cms_reservation_duration` VALUES (1,12,'IT and IS','Equipment','2024-09-23 06:25:23','2024-09-23 06:25:23'),(2,12,'IT and IS','Laboratory','2024-08-17 08:54:31','2024-08-17 08:54:31'),(3,12,'IT and IS','Facility','2024-08-17 08:54:31','2024-08-17 08:54:31'),(5,12,'Computer Science','Equipment','2024-08-17 10:42:19','2024-08-17 10:42:19'),(6,12,'Computer Science','Laboratory','2024-08-17 10:42:19','2024-08-17 10:42:19'),(7,12,'Computer Science','Facility','2024-08-17 10:42:19','2024-08-17 10:42:19'),(8,12,'Psychology','Equipment','2024-09-04 08:14:55','2024-09-04 08:14:55'),(9,12,'Psychology','Laboratory','2024-09-04 08:14:55','2024-09-04 08:14:55'),(10,12,'Psychology','Facility','2024-09-04 08:14:55','2024-09-04 08:14:55'),(12,12,'Robotics Engineering','Equipment','2024-11-15 08:35:51','2024-11-15 08:35:51'),(13,12,'Robotics Engineering','Laboratory','2024-11-15 08:35:51','2024-11-15 08:35:51'),(14,12,'Robotics Engineering','Facility','2024-11-15 08:35:51','2024-11-15 08:35:51');
/*!40000 ALTER TABLE `cms_reservation_duration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_status_colors`
--

DROP TABLE IF EXISTS `cms_status_colors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cms_status_colors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `status_color` varchar(45) NOT NULL,
  `created_at` varchar(45) NOT NULL DEFAULT 'CURRENT_TIMESTAMP',
  `updated_at` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status_color_UNIQUE` (`status_color`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_status_colors`
--

LOCK TABLES `cms_status_colors` WRITE;
/*!40000 ALTER TABLE `cms_status_colors` DISABLE KEYS */;
INSERT INTO `cms_status_colors` VALUES (1,'text-green-500','2024-08-24 21:39:41',NULL),(2,'text-yellow-500','2024-08-24 21:39:41',NULL),(3,'text-gray-500','2024-08-24 21:39:41',NULL),(4,'text-red-500','2024-08-24 21:39:41',NULL),(5,'text-blue-500','2024-08-24 21:39:41',NULL),(6,'text-orange-600','2024-08-24 21:39:41',NULL),(7,'text-orange-700','2024-08-24 21:39:41',NULL),(8,'text-red-700','2024-08-24 21:39:41',NULL),(9,'text-green-700','2024-08-24 21:39:41',NULL),(10,'text-yellow-700','2024-08-24 21:39:41',NULL),(11,'text-red-400','2024-08-24 21:39:41',NULL),(12,'text-blue-700','2024-08-24 21:39:41',NULL),(13,'text-gray-700','2024-08-24 21:39:41',NULL),(14,'text-blue-400','2024-08-24 21:39:41',NULL),(15,'text-green-600','2024-08-24 21:39:41',NULL);
/*!40000 ALTER TABLE `cms_status_colors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `colleges`
--

DROP TABLE IF EXISTS `colleges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `colleges` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `emblem` varchar(512) NOT NULL,
  `created_at` varchar(75) NOT NULL,
  `updated_at` varchar(75) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colleges`
--

LOCK TABLES `colleges` WRITE;
/*!40000 ALTER TABLE `colleges` DISABLE KEYS */;
INSERT INTO `colleges` VALUES (0,'System Head','emblem/cos.png','2024-09-06 19:37:30',NULL),(5,'College of Computing and Information Technology','emblem/College of Computing and Information Technology_Emblem.png','2024-09-06 19:37:30','2024-09-22 19:41:25'),(6,'College of Science','emblem/College of Science_Emblem.png','2024-09-06 19:37:30','2024-09-22 19:43:32'),(7,'College of Engineering','emblem/College of Engineering_Emblem.jpg','2024-09-06 19:37:30','2024-09-22 19:44:39'),(11,'College of Education and Liberal Arts','emblem/College of Education and Liberal Arts_Emblem.jpg','2024-09-22 18:47:43','2024-10-13 10:30:06');
/*!40000 ALTER TABLE `colleges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `department_id` int NOT NULL AUTO_INCREMENT,
  `department_name` varchar(50) NOT NULL,
  `college` varchar(256) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edited_at` datetime DEFAULT NULL,
  `edited_by` int DEFAULT NULL,
  PRIMARY KEY (`department_id`),
  UNIQUE KEY `Department_Name_UNIQUE` (`department_name`),
  KEY `fk_college_idx` (`college`),
  CONSTRAINT `fk_college` FOREIGN KEY (`college`) REFERENCES `colleges` (`name`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (0,'System Admin','System Head','2024-06-16 18:43:22',NULL,NULL),(1,'IT and IS','College of Computing and Information Technology','2024-06-16 18:43:22','2024-10-08 15:56:10',3),(2,'Computer Science','College of Science','2024-06-16 18:43:22','2024-10-08 16:00:29',3),(3,'Biology','College of Science','2024-06-16 18:43:22','2024-10-08 15:52:59',3),(4,'Mathematics','College of Science','2024-06-16 18:43:22',NULL,NULL),(5,'Chemistry','College of Science','2024-06-16 18:43:22',NULL,NULL),(6,'Psychology','College of Science','2024-06-16 18:43:22',NULL,NULL),(28,'Physical Educations','College of Education and Liberal Arts','2024-10-13 09:54:28','2024-10-13 10:30:31',5),(29,'Computer Engineering','College of Engineering','2024-11-15 13:23:04',NULL,NULL),(34,'Robotics Engineering','College of Engineering','2024-11-15 16:35:51',NULL,NULL);
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facilities`
--

DROP TABLE IF EXISTS `facilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `facilities` (
  `facilities_id` int NOT NULL AUTO_INCREMENT,
  `facility_name` varchar(255) NOT NULL,
  `location` varchar(100) NOT NULL,
  `department_owner` varchar(45) NOT NULL,
  `image` varchar(512) NOT NULL,
  `is_available` int NOT NULL DEFAULT '1',
  `policy` int DEFAULT NULL,
  `status` int NOT NULL,
  `created_at` datetime NOT NULL,
  `edited_at` datetime DEFAULT NULL,
  `edited_by` int DEFAULT NULL,
  `added_by` int NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`facilities_id`),
  KEY `fk_dept_owner_idx` (`department_owner`),
  KEY `fk_status_fac` (`status`),
  KEY `fk_policy_idx` (`policy`),
  CONSTRAINT `fk_dept_owner` FOREIGN KEY (`department_owner`) REFERENCES `departments` (`department_name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_policy` FOREIGN KEY (`policy`) REFERENCES `policies` (`policy_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_status_fac` FOREIGN KEY (`status`) REFERENCES `reservation_status` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facilities`
--

LOCK TABLES `facilities` WRITE;
/*!40000 ALTER TABLE `facilities` DISABLE KEYS */;
INSERT INTO `facilities` VALUES (9,'Conference Room 2','IT and IS Department','IT and IS','IT_and_IS/facilities_image/Conference_Room_Valdez_1723296573.png',1,NULL,1,'2024-08-05 17:45:02','2024-10-01 02:54:28',202118402,202118555,'2024-10-10 00:45:34'),(10,'Conference Room','CT 404','IT and IS','IT_and_IS/facilities_image/Conference_Room_Sepuntos_1722880568.png',1,53143,1,'2024-08-05 17:56:08','2024-11-23 14:39:47',202112862,202118555,NULL),(11,'CS AVR','SV Near ACOMM Office','Computer Science','Computer_Science/facilities_image/CS_AVR_Tipon_1728490633.jpg',1,53698,1,'2024-08-10 08:28:08','2024-10-10 00:17:13',202110278,202110278,NULL);
/*!40000 ALTER TABLE `facilities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facility_reservation`
--

DROP TABLE IF EXISTS `facility_reservation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `facility_reservation` (
  `id` int NOT NULL,
  `facility_name` int NOT NULL,
  `user_id` int NOT NULL,
  `purpose` varchar(45) NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `status` int NOT NULL DEFAULT '2',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `remarks` longtext,
  `deleted_at` datetime DEFAULT NULL,
  `approved_by` int DEFAULT NULL,
  `released_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_id_idx` (`user_id`),
  KEY `fk_status_facility_idx` (`status`),
  CONSTRAINT `fk_id` FOREIGN KEY (`user_id`) REFERENCES `user_accounts` (`user_id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_status_facility` FOREIGN KEY (`status`) REFERENCES `reservation_status` (`status_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facility_reservation`
--

LOCK TABLES `facility_reservation` WRITE;
/*!40000 ALTER TABLE `facility_reservation` DISABLE KEYS */;
INSERT INTO `facility_reservation` VALUES (3293,10,202112862,'e','2024-11-25 12:00:00','2024-11-25 16:00:00',14,'2024-11-19 15:14:27','2024-11-23 14:38:58',NULL,NULL,202112862,NULL),(5955,10,202118123,'wdajdajkawd','2024-11-26 08:55:00','2024-11-26 09:56:00',8,'2024-11-22 15:56:25','2024-11-22 15:57:28',NULL,NULL,NULL,NULL),(11676,10,202118123,'acsasacsa','2024-11-22 19:21:00','2024-11-22 21:21:00',14,'2024-11-22 18:21:42','2024-11-23 14:23:24','Returned Clean',NULL,202112862,NULL),(55046,10,202118555,'For Consultation Purposes','2024-11-26 07:00:00','2024-11-26 11:00:00',14,'2024-10-13 05:13:22','2024-11-22 19:08:22','Oh my Gawd',NULL,202112862,NULL),(69066,10,202112862,'For Meeting','2024-11-23 17:19:00','2024-11-23 19:19:00',2,'2024-11-23 16:19:57','2024-11-23 16:19:57',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `facility_reservation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `general_policies`
--

DROP TABLE IF EXISTS `general_policies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `general_policies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dept_owner` varchar(50) NOT NULL,
  `policy_name` varchar(75) NOT NULL,
  `policy_content` longtext NOT NULL,
  `added_by` int DEFAULT NULL,
  `edited_by` int DEFAULT NULL,
  `updated_at` timestamp NOT NULL,
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_dept_owner_idx` (`dept_owner`),
  CONSTRAINT `fk_dept_owner_gen_policy` FOREIGN KEY (`dept_owner`) REFERENCES `departments` (`department_name`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `general_policies`
--

LOCK TABLES `general_policies` WRITE;
/*!40000 ALTER TABLE `general_policies` DISABLE KEYS */;
INSERT INTO `general_policies` VALUES (2,'IT and IS','Equipment Policy','Department-owned equipment is for academic and research purposes only. Faculty members and students may request permission from the department to use available equipment or facility. Personal use of department equipment is prohibited.\r\nAny damage or malfunction must be reported to IT support immediately. Users are responsible for the security and proper handling of borrowed equipment.',202118555,202112862,'2024-08-01 06:14:42','2024-08-01 06:14:42'),(4,'Computer Science','Equipment Borrowing Policy','Students and faculty can borrow department-owned equipment, such as laptops, projectors, and other devices, for academic use. Users may submit borrowing requests through the system and approvals are subject to availability. Equipment must be returned by the specified due date, and any delays may result in automated penalties through the system. Users are responsible for damages or loss, and failure to comply with the return policy may lead to restricted access to future borrowing.',202118402,202110278,'2024-08-07 07:06:27','2024-08-07 07:06:27'),(5,'Computer Science','Computer Science Lab Reservation Policy','Labs must be returned to their original condition after use. Any damages or issues during the reservation period will be logged in the system, and users may face penalties or restrictions for future reservations.',202110278,202110278,'2024-08-10 22:45:03','2024-08-10 22:45:03'),(6,'IT and IS','Conference Room','The IT and IS Department\'s conference room can be reserved in advance using the web-based system, and department members (faculty, staff, and students) are authorized to make reservations. The room is designated for academic, research, and departmental purposes only. All meetings should conclude on time to accommodate subsequent reservations, and users must ensure the room is left clean with all equipment powered off after use. Any technical issues with the conference room equipment should be reported immediately to the department.',202118402,202118555,'2024-08-18 08:19:11','2024-08-18 08:19:11'),(7,'IT and IS','Laboratory Items Policy','The department-owned laboratory is for academic and research purposes only. Faculty members and students may request permission from the department to use available laboratory items or facilities. Personal use of department lab items are prohibited. Any damage or malfunction must be reported to IT support immediately. Users are responsible for the security and proper handling of borrowed lab items.',202112862,202112862,'2024-11-22 10:51:29','2024-11-22 10:51:29');
/*!40000 ALTER TABLE `general_policies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `logs` (
  `log_id` int NOT NULL AUTO_INCREMENT,
  `logger` int NOT NULL,
  `log_time` datetime NOT NULL,
  `log_activity` varchar(75) NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `fk_user_id_idx` (`logger`),
  CONSTRAINT `fk_user_id` FOREIGN KEY (`logger`) REFERENCES `user_accounts` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `policies`
--

DROP TABLE IF EXISTS `policies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `policies` (
  `policy_id` int NOT NULL,
  `policy_name` varchar(45) NOT NULL,
  `policy_content` longtext NOT NULL,
  `inclusions` longtext NOT NULL,
  `department_owner` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`policy_id`),
  KEY `fk_dept` (`department_owner`),
  CONSTRAINT `fk_dept` FOREIGN KEY (`department_owner`) REFERENCES `departments` (`department_name`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `policies`
--

LOCK TABLES `policies` WRITE;
/*!40000 ALTER TABLE `policies` DISABLE KEYS */;
INSERT INTO `policies` VALUES (5866,'General Laptop Policy','Students and faculty may borrow laptops for academic use for a maximum period of 1 day. Each borrower is responsible for the care and security of the laptop during the borrowing period. Late returns will incur a fee per day. In case of loss or damage, the borrower will be responsible for the repair or replacement costs. All borrowing requests must be submitted online, and availability is subject to prior reservations.','Charger Type-C, Laptop Cover','IT and IS','2024-06-04 15:00:24','2024-06-04 15:00:24'),(9090,'Breadboard Policy','The university allows students and faculty to borrow breadboards for academic use. Borrowing is limited to 2 days, with possible extensions. Borrowers are responsible for the proper use and timely return of the breadboard, or they will incur late fees. Any loss or damage must be compensated by the borrower.','Pin 1 and Pin 4','IT and IS','2024-06-04 14:57:18','2024-06-04 14:57:18'),(15555,'HDMI Policy','Students and faculty can borrow HDMI cables for academic use, with a maximum borrowing period of 5 days. Borrowers are responsible for returning the cables in good condition; any loss or damage must be reported. All borrowing requests should be made in advance and are subject to availability.','N/A','Computer Science','2024-10-09 16:11:09','2024-10-09 16:11:09'),(17553,'Screwdrivers Policy','Students and faculty can borrow the 6-set precision screwdrivers for academic projects. The borrowing period is limited to 1 day, with a possible extension upon request. Borrowers must ensure that the screwdrivers are returned in good condition; any loss or damage will result in replacement costs. A borrowing request must be submitted in advance, and late returns will incur a fee per day.','Plastic Cover, 6 Precision Screwdrivers, Tool Case','Computer Science','2024-10-09 16:08:21','2024-10-09 16:08:21'),(51197,'Mouse Policy','Students and faculty may borrow mouse for academic use for a maximum period of 2 day. Each borrower is responsible for the care and security of the mouse during the borrowing period. Late returns will incur a fee per day. In case of loss or damage, the borrower will be responsible for the repair or replacement costs. All borrowing requests must be submitted online, and availability is subject to prior reservations.','2x Double A Battery','IT and IS','2024-10-09 17:27:13','2024-10-09 17:27:13'),(53143,'Conference Room Policy','Students and faculty may reserve conference rooms for academic purposes. Reservations can be made for up to 3 hours at a time. Users are responsible for maintaining the room\'s cleanliness and ensuring all equipment is returned in working order. Late arrivals will forfeit their reservation time, and repeated violations may result in the loss of booking privileges.','Long table, 4 Chairs, Flower Vase, Printer, Aircon','IT and IS','2024-06-15 10:33:55','2024-06-15 10:33:55'),(53404,'Projector','Students and faculty may borrow projectors for academic purposes for a maximum of 1 day. Borrowers are responsible for the proper use and care of the projector and must return it in good condition. Any loss or damage must be reported immediately. All borrowing requests should be submitted in advance and are subject to availability.','1x VGA Cable, 1x HDMI Cable, 1x Power Connector','Computer Science','2024-10-09 16:12:19','2024-10-09 16:12:19'),(53698,'CS Conference Room Policy','Students and faculty may reserve conference rooms for academic purposes. Reservations can be made for up to 3 hours at a time, with a maximum of two bookings per week. Users are responsible for maintaining the room\'s cleanliness and ensuring all equipment is returned in working order. Late arrivals will forfeit their reservation time, and repeated violations may result in the loss of booking privileges.','Chairs and Long Table with Miscellaneous','Computer Science','2024-08-10 01:02:35','2024-08-10 01:02:35'),(53945,'Precision Screwdriver Policy','Students and faculty can borrow the 6-set precision screwdrivers for academic projects. The borrowing period is limited to 2 days, with a possible extension upon request. Borrowers must ensure that the screwdrivers are returned in good condition; any loss or damage will result in replacement costs. A borrowing request must be submitted in advance, and late returns will incur a fee per day.','6 Precision Screwdrivers, Tool Case, Plastic Cover','IT and IS','2024-06-05 09:54:08','2024-06-05 09:54:08'),(55114,'Breadboard Policy','The university allows students and faculty to borrow breadboards for academic use. Borrowing is limited to 2 days, with possible extensions. Borrowers are responsible for the proper use and timely return of the breadboard, or they will incur late fees. Any loss or damage must be compensated by the borrower.','12 Pins, 10 Resistors','Computer Science','2024-08-08 00:44:01','2024-08-08 00:44:01'),(56962,'Laptop Policy','Students and faculty may borrow laptops for academic use for a maximum period of 1 day. Each borrower is responsible for the care and security of the laptop during the borrowing period.','1x Type-C Charger\r\n1x Laptop Case','Computer Science','2024-10-09 16:14:46','2024-10-09 16:14:46'),(63775,'Remote Policy','Students and faculty may borrow remote controls for academic purposes, with a maximum borrowing period of 2 days. Borrowers are responsible for ensuring the remote is returned in good condition; any loss or damage will incur replacement costs. Late returns will result in a fee per day. All borrowing requests must be made in advance and are subject to availability.','2 pcs. Double A Batteries','Computer Science','2024-10-09 16:10:24','2024-10-09 16:10:24'),(76146,'HDMI Policy','Students and faculty can borrow HDMI cables for academic use for a maximum of 2 days. Borrowers are responsible for ensuring the cables are returned in good condition and must report any loss or damage. All borrowing requests should be submitted in advance and are subject to availability.','N/A','IT and IS','2024-10-09 16:20:30','2024-10-09 16:20:30'),(84283,'Testing July 30, 2024','dwdddwq edited','N/A','Mathematics','2024-07-30 05:19:43','2024-07-30 05:19:43'),(84682,'scanefoefsssssss','cecere','werefr','Physical Educations','2024-10-13 03:14:30','2024-10-13 03:14:30');
/*!40000 ALTER TABLE `policies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `positions`
--

DROP TABLE IF EXISTS `positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `positions` (
  `position_id` int NOT NULL,
  `position_name` varchar(75) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edited_at` datetime DEFAULT NULL,
  `edited_by` int DEFAULT NULL,
  PRIMARY KEY (`position_id`),
  UNIQUE KEY `Position_Name_UNIQUE` (`position_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `positions`
--

LOCK TABLES `positions` WRITE;
/*!40000 ALTER TABLE `positions` DISABLE KEYS */;
INSERT INTO `positions` VALUES (1,'Student','2024-06-16 18:45:52',NULL,NULL),(2,'Faculty','2024-06-16 18:45:52',NULL,NULL),(3,'Chairperson','2024-06-16 18:45:52',NULL,NULL);
/*!40000 ALTER TABLE `positions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registration_policy`
--

DROP TABLE IF EXISTS `registration_policy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `registration_policy` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `content` longtext NOT NULL,
  `created_at` varchar(45) NOT NULL,
  `updated_at` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registration_policy`
--

LOCK TABLES `registration_policy` WRITE;
/*!40000 ALTER TABLE `registration_policy` DISABLE KEYS */;
INSERT INTO `registration_policy` VALUES (1,'Registration Policy','Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, fssrom a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.','2024-06-15 18:52:08','2024-10-13 05:24:04');
/*!40000 ALTER TABLE `registration_policy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservation_status`
--

DROP TABLE IF EXISTS `reservation_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservation_status` (
  `status_id` int NOT NULL,
  `status_state` varchar(45) NOT NULL,
  `status_color` varchar(45) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edited_at` datetime DEFAULT NULL,
  `edited_by` int DEFAULT NULL,
  PRIMARY KEY (`status_id`),
  UNIQUE KEY `status_state_UNIQUE` (`status_state`),
  KEY `fk_status_colors_idx` (`status_color`),
  CONSTRAINT `fk_status_colors` FOREIGN KEY (`status_color`) REFERENCES `cms_status_colors` (`status_color`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservation_status`
--

LOCK TABLES `reservation_status` WRITE;
/*!40000 ALTER TABLE `reservation_status` DISABLE KEYS */;
INSERT INTO `reservation_status` VALUES (1,'Available','text-green-500','2024-06-16 18:46:07',NULL,NULL),(2,'Pending','text-yellow-500','2024-06-16 18:46:07',NULL,NULL),(3,'Approved','text-green-600','2024-06-16 18:46:07',NULL,NULL),(4,'Rejected','text-red-500','2024-06-16 18:46:07',NULL,NULL),(5,'On-Going','text-blue-500','2024-06-16 18:46:07',NULL,NULL),(6,'Returned','text-gray-500','2024-06-16 18:46:07',NULL,NULL),(7,'For Replacement','text-orange-600','2024-06-16 18:46:07',NULL,NULL),(8,'Cancelled','text-red-500','2024-06-16 18:46:07',NULL,NULL),(9,'Late','text-yellow-500','2024-06-16 18:46:07',NULL,NULL),(10,'Out of Stock','text-gray-500','2024-06-16 18:46:07',NULL,NULL),(12,'Maintenance','text-orange-600','2024-06-16 18:46:07',NULL,NULL),(13,'Reserved','text-gray-500','2024-06-17 12:40:05',NULL,NULL),(14,'Completed','text-gray-500','2024-06-17 12:40:05',NULL,NULL),(15,'Unavailable','text-gray-500','2024-06-17 12:40:05',NULL,NULL);
/*!40000 ALTER TABLE `reservation_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resource_type`
--

DROP TABLE IF EXISTS `resource_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `resource_type` (
  `category_id` int NOT NULL,
  `resource_type` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edited_at` datetime DEFAULT NULL,
  `edited_by` int DEFAULT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `resource_type_UNIQUE` (`resource_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resource_type`
--

LOCK TABLES `resource_type` WRITE;
/*!40000 ALTER TABLE `resource_type` DISABLE KEYS */;
INSERT INTO `resource_type` VALUES (1,'Equipment','2024-06-16 18:46:18',NULL,NULL),(2,'Facility','2024-06-16 18:46:18',NULL,NULL),(3,'Laboratory','2024-06-16 18:46:18',NULL,NULL);
/*!40000 ALTER TABLE `resource_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resources`
--

DROP TABLE IF EXISTS `resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `resources` (
  `resource_id` int NOT NULL,
  `resource_name` varchar(45) NOT NULL,
  `resource_type` int NOT NULL,
  `serial_number` varchar(256) NOT NULL,
  `image` varchar(512) DEFAULT NULL,
  `rating` decimal(5,2) NOT NULL,
  `status` int NOT NULL,
  `department_owner` varchar(50) NOT NULL,
  `policy_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `edited_at` datetime DEFAULT NULL,
  `edited_by` int DEFAULT NULL,
  `added_by` int NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`resource_id`),
  UNIQUE KEY `serial_number_UNIQUE` (`serial_number`),
  KEY `item_type_idx` (`resource_type`),
  KEY `fk_department_owner` (`department_owner`),
  KEY `fk_status_resources` (`status`),
  KEY `fk_policy_id` (`policy_id`),
  CONSTRAINT `fk_department_owner` FOREIGN KEY (`department_owner`) REFERENCES `departments` (`department_name`) ON UPDATE CASCADE,
  CONSTRAINT `fk_policy_id` FOREIGN KEY (`policy_id`) REFERENCES `policies` (`policy_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_status_resources` FOREIGN KEY (`status`) REFERENCES `reservation_status` (`status_id`) ON UPDATE CASCADE,
  CONSTRAINT `item_type` FOREIGN KEY (`resource_type`) REFERENCES `resource_type` (`category_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resources`
--

LOCK TABLES `resources` WRITE;
/*!40000 ALTER TABLE `resources` DISABLE KEYS */;
INSERT INTO `resources` VALUES (266,'MacBook Pro M2',1,'xt56h58j3','IT_and_IS/resources_image/MacBook_Pro_M2_Valdez_1730306180.jpg',4.00,1,'IT and IS',5866,'2024-06-07 21:39:41','2024-11-19 01:03:14',202112862,202118555,NULL),(29779,'6 Set Precision Screwdriver',3,'G9-IOP-00OOAS-098','Computer_Science/resources_image/6_Set_Precision_Screwdriver_Tipon_1728490133.png',0.00,1,'Computer Science',17553,'2024-10-10 00:08:53',NULL,NULL,202110278,NULL),(45356,'Macbook M2',1,'39rh54b3','IT_and_IS/resources_image/Macbook_M2_Sepuntos_1728492915.jpg',0.00,1,'IT and IS',5866,'2024-09-06 10:26:53','2024-10-10 00:55:15',202118555,202118402,'2024-10-10 13:12:15'),(46543,'MacBook Air M2',1,'JK-PL34-IIU','Computer_Science/resources_image/MacBook_Air_M2_Tipon_1728490418.png',0.00,1,'Computer Science',56962,'2024-10-10 00:13:38','2024-10-10 00:15:10',202110278,202110278,NULL),(50042,'HDMI',1,'ennfkek3','IT_and_IS/resources_image/HDMI_Sepuntos_1728492980.jpg',0.00,1,'IT and IS',76146,'2024-09-06 07:41:40','2024-11-16 01:07:41',202118402,202118402,NULL),(50866,'Testing Resource',3,'zxdf435653','IT_and_IS/resources_image/Testing_Resource_Sepuntos_1727954530.png',0.00,1,'IT and IS',NULL,'2024-10-03 19:22:11',NULL,NULL,202118555,'2024-10-10 01:24:46'),(63042,'Mouse',1,'09ET31','IT_and_IS/resources_image/Mouse_Sepuntos_1731995645.png',0.00,1,'IT and IS',51197,'2024-11-19 13:54:06',NULL,NULL,202118555,NULL),(64691,'HDM',1,'ewg4h53','IT_and_IS/resources_image/HDMIedi_Sepuntos_1717962451.png',3.00,1,'IT and IS',NULL,'2024-06-10 03:32:49','2024-08-23 19:24:16',202118555,202118555,'2024-10-10 00:36:43'),(65243,'6 Set Precision Screwdriver',3,'YYU8-00OGXT-99','IT_and_IS/resources_image/6_Set_Precision_Screwdriver_Valdez_1728494890.jpg',0.00,1,'IT and IS',53945,'2024-06-06 01:54:08','2024-10-10 01:28:34',202118402,202118555,NULL),(67508,'Breadboard',3,'45t5r','Computer_Science/resources_image/Breadboard_Tipon_1728489317.jpg',0.00,13,'Computer Science',55114,'2024-08-08 08:44:53','2024-10-09 23:55:16',202110278,202118402,NULL),(85059,'Breadboard',3,'7665bht54','IT_and_IS/resources_image/Breadboard_Sepuntos_1717544758.png',0.00,1,'Psychology',NULL,'2024-06-05 07:45:58',NULL,NULL,202118555,NULL),(98997,'Mouse',3,'tgethrty5-09','IT_and_IS/resources_image/Mouse_Valdez_1728494761.jpg',0.00,1,'IT and IS',51197,'2024-08-05 18:14:29','2024-10-19 20:19:32',202118402,202118555,NULL);
/*!40000 ALTER TABLE `resources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` text,
  `last_activity` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('h15ztjIvlwPDioHfS8LrboFHxLCXTB2goQvaNT4n',202112862,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36','YTo2OntzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0OToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL21hL2Rhc2hib2FyZC9yYW5nZT9yYW5nZT0zMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NjoiX3Rva2VuIjtzOjQwOiJ1d3AzRGh0aGtxc2w3VTZQZ1R2N1pXVVBSNk5MU1IwTm0wcE0zN2owIjtzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyMDIxMTI4NjI7czoxMjoiY3VycmVudF9yb2xlIjtzOjE6IjIiO3M6OToiZGVwdF9uYW1lIjtzOjk6IklUIGFuZCBJUyI7fQ==',1732899046),('tmJlxiqRMZmw8TUl2CZF3upcZUd9lyZnpGvvJ06P',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0','YTo0OntzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3VzZXIvZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo2OiJfdG9rZW4iO3M6NDA6IlJQYlRWSVJXbFBHUmliSUk0c25kVmp3NkoxdExrb3BNWGJ4VGI0cUsiO3M6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC91c2VyL2Rhc2hib2FyZCI7fX0=',1732897095);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_admin_accounts`
--

DROP TABLE IF EXISTS `system_admin_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_admin_accounts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `email` varchar(75) NOT NULL,
  `password` varchar(150) NOT NULL,
  `created_at` varchar(45) NOT NULL,
  `updated_at` varchar(45) DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `profile_pic` varchar(512) DEFAULT 'storage/assets/default_image.png',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_admin_accounts`
--

LOCK TABLES `system_admin_accounts` WRITE;
/*!40000 ALTER TABLE `system_admin_accounts` DISABLE KEYS */;
INSERT INTO `system_admin_accounts` VALUES (5,'Daniels','Valdez','danielasanon54@gmail.com','$2y$12$z.MURD.x3PIfjseg5bdmpu4pbKpWv6soAbmV8VBj9eLQp1Jzqfq.2','2024-10-12 23:58:09','2024-11-19 15:20:48',1,'storage/assets/default_image.png');
/*!40000 ALTER TABLE `system_admin_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_accounts`
--

DROP TABLE IF EXISTS `user_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_accounts` (
  `user_id` int NOT NULL,
  `dept_name` varchar(50) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `email` varchar(75) NOT NULL,
  `password` varchar(150) NOT NULL,
  `position` varchar(75) NOT NULL,
  `profile_pic` varchar(512) DEFAULT 'storage/assets/default_image.png',
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int NOT NULL DEFAULT '0',
  `user_type` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `adu_mail_unique` (`email`),
  KEY `Positions_idx` (`position`),
  KEY `Department_Name_idx` (`dept_name`),
  KEY `fk_user_type` (`user_type`),
  KEY `fk_user_acct_status_idx` (`status`),
  CONSTRAINT `fk_dept_name` FOREIGN KEY (`dept_name`) REFERENCES `departments` (`department_name`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_position` FOREIGN KEY (`position`) REFERENCES `positions` (`position_name`) ON UPDATE CASCADE,
  CONSTRAINT `fk_user_acct_status` FOREIGN KEY (`status`) REFERENCES `account_status` (`acct_status_id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_user_type` FOREIGN KEY (`user_type`) REFERENCES `user_type` (`type_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_accounts`
--

LOCK TABLES `user_accounts` WRITE;
/*!40000 ALTER TABLE `user_accounts` DISABLE KEYS */;
INSERT INTO `user_accounts` VALUES (20288728,'Computer Science','Janica','Naldo','janica.naldo@adamson.edu.ph','Student@123','Chairperson','storage/assets/default_image.png','2024-10-12 10:26:00',0,3),(202110278,'Computer Science','Jamelah Ayen','Tipon','jamelah.ayen.tipon@adamson.edu.ph','$2y$12$l6vVWVP5x8Jtj2/bly5IMOoe8d2dC5GGaMTMDWLAXQcbEXVwQEZAq','Faculty','storage/Computer_Science/admin_profiles/202110278_Tipon_1728582573.png','2024-05-05 06:37:30',1,2),(202111043,'Physical Educations','Luis Victor','Jimenez','luis.victor.jimenez@adamson.edu.ph','$2y$12$Us046Kw//Gpc9R8v2uemxuMOwDTwgf.8WvFQb5wLR4/7u2vLoHxES','Chairperson','storage/assets/default_image.png','2024-10-13 02:38:10',1,3),(202112862,'IT and IS','Mary Madelaine','Escarlan','mary.madelaine.escarlan@adamson.edu.ph','$2y$12$tKhNvSb1NwULXmoQlXVYeOSOvUixn8zoK7kpXqy9YL2SW7h3APkjq','Faculty','storage/assets/default_image.png','2024-04-17 09:30:13',1,2),(202113240,'Computer Science','KC','Colango','kassandra.christina.colango@adamson.edu.ph','$2y$10$Xu7gKbceUAANIsv/VlwbfuQedGIeuGRwnYWBbpBr57qpMMpKQYohW','Student','storage/Computer_Science/user_profiles/202113240_Colango_1728364444.png','2024-05-05 02:25:45',1,2),(202117569,'Psychology','Elaisa Nicole','Evans','elaisa.nicole.evans@adamson.edu.ph','$2y$12$Yjnz1VhEsXaTT01BGvMWAe/etVqgAG8hZ17Qfx1ydqpx7G2ubaGN6','Student','storage/assets/default_image.png','2024-08-28 09:12:52',1,1),(202118009,'Computer Science','Boesp','Boss','onehehe0@gmail.com','$2y$12$9KmJ4/bsKetK/9ekElG60eQKmESh4FmWSiM.rR1n0C0h2Dz38UFWO','Student','storage/assets/default_image.png','2024-09-28 16:09:54',1,1),(202118111,'Mathematics','Daniel Math','Valdez','danielasanon54@gmail.com','$2y$12$eC9zFJiYdyyXUAT0rx39OOFT1/tsKZEQJSS5aMUecMmbYNNpg7eyy','Faculty','storage/assets/default_image.png','2024-06-11 12:22:43',1,1),(202118123,'IT and IS','Ervin','Lacuarta','ervin.lacuarta@adamson.edu.ph','$2y$10$Dn9zqCVhOSJUn0mQU1kTm.mFs8ftbjVv4OlALajH0E4QH07vI2hSy','Faculty','storage/assets/default_image.png','2024-05-05 02:07:17',1,1),(202118402,'IT and IS','Daniel','Valdez','daniel.valdez@adamson.edu.ph','$2y$12$4upbDnAgvn9.yIoeJdpqsOma1xG/sz9FDS5KFLHvsu.atVa5LiU/u','Chairperson','storage/assets/default_image.png','2024-05-05 01:45:24',1,3),(202118555,'IT and IS','Rejean','Sepuntos','rejean.sepuntssos@adamson.edu.ph','$2y$10$g4S9d4ZsueGzgjLF1lNi9.rQ.b2pQNq/RQHrhkFqPLyQGlmlHV0Ya','Faculty','storage/assets/default_image.png','2024-04-29 04:56:50',1,2),(202150903,'Computer Science','Ryl Angelo','Resullar','ryl.angelo.resullar@adamson.edu.ph','$2y$10$vnuwnScueeuOcRZSlVPDveIUiPRQfPHxMVl/pLdGzTap7yheUEt6i','Student','storage/assets/default_image.png','2024-05-05 02:03:51',1,2),(202888123,'Biology','Mr','Biology','mrbiology@adamson.edu.ph','Admin@123','Chairperson','storage/assets/default_image.png','2024-11-23 17:47:08',0,3);
/*!40000 ALTER TABLE `user_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_feedback`
--

DROP TABLE IF EXISTS `user_feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_feedback` (
  `feedback_id` int NOT NULL,
  `username` int NOT NULL,
  `feedback` longtext,
  `resource_id` int NOT NULL,
  `rating` double NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`feedback_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_feedback`
--

LOCK TABLES `user_feedback` WRITE;
/*!40000 ALTER TABLE `user_feedback` DISABLE KEYS */;
INSERT INTO `user_feedback` VALUES (3129136,202118555,'Clean and Nice',266,4,'2024-10-10 13:54:17','2024-10-10 13:54:17');
/*!40000 ALTER TABLE `user_feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_reservation_requests`
--

DROP TABLE IF EXISTS `user_reservation_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_reservation_requests` (
  `transaction_id` int NOT NULL,
  `user_id` int NOT NULL,
  `resource_type` int NOT NULL,
  `resource_id` int NOT NULL,
  `pickup_datetime` datetime DEFAULT NULL,
  `professor` int NOT NULL,
  `purpose` longtext NOT NULL,
  `status` int NOT NULL DEFAULT '2',
  `return_datetime` datetime DEFAULT NULL,
  `serial_number` varchar(256) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approved_by` int DEFAULT NULL,
  `returned_to` int DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `remarks` longtext,
  `deleted_at` datetime DEFAULT NULL,
  `section` varchar(100) NOT NULL,
  `schedule` varchar(100) DEFAULT NULL,
  `subject` varchar(100) NOT NULL,
  `group_members` longtext,
  `noted_by` int DEFAULT NULL,
  `released_by` int DEFAULT NULL,
  PRIMARY KEY (`transaction_id`),
  KEY `user_id_idx` (`user_id`),
  KEY `resource_type_idx` (`resource_type`),
  KEY `fk_status_idx` (`status`),
  KEY `fk_approved_by_idx` (`approved_by`),
  KEY `fk_returned_to_idx` (`returned_to`),
  KEY `fk_serial_idx` (`serial_number`),
  KEY `fk_resource_id` (`resource_id`),
  KEY `fk_noted_by_idx` (`noted_by`),
  CONSTRAINT `fk_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `user_accounts` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_noted_by` FOREIGN KEY (`noted_by`) REFERENCES `user_accounts` (`user_id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_resource_id` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`resource_id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_returned_to` FOREIGN KEY (`returned_to`) REFERENCES `user_accounts` (`user_id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_serial` FOREIGN KEY (`serial_number`) REFERENCES `resources` (`serial_number`) ON UPDATE CASCADE,
  CONSTRAINT `fk_status` FOREIGN KEY (`status`) REFERENCES `reservation_status` (`status_id`) ON UPDATE CASCADE,
  CONSTRAINT `resource_type` FOREIGN KEY (`resource_type`) REFERENCES `resources` (`resource_type`) ON UPDATE CASCADE,
  CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `user_accounts` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_reservation_requests`
--

LOCK TABLES `user_reservation_requests` WRITE;
/*!40000 ALTER TABLE `user_reservation_requests` DISABLE KEYS */;
INSERT INTO `user_reservation_requests` VALUES (2384,202112862,3,65243,'2024-11-16 19:59:00',202118123,'sa',6,'2024-11-16 22:59:00','YYU8-00OGXT-99','2024-11-16 18:59:25',202112862,202118555,'2024-11-19 14:00:55',NULL,NULL,'BSIT 401','M 12-3','App Dev',NULL,202118123,NULL),(6797,202112862,1,50042,'2024-11-23 11:00:00',202112862,'WW',6,'2024-11-23 12:00:00','ennfkek3','2024-11-19 14:41:56',202118555,202118555,'2024-11-19 14:46:18',NULL,NULL,'CS103','MWF 12-1','Animation',NULL,NULL,202118555),(9457,202112862,1,266,'2024-11-23 17:56:00',202112862,'For Activity',2,'2024-11-23 18:56:00','xt56h58j3','2024-11-23 16:56:22',NULL,NULL,'2024-11-23 16:56:22',NULL,NULL,'21788','Tuesday 2:00-3:00','App Dev',NULL,NULL,NULL),(14557,202118009,3,65243,'2024-11-23 18:13:00',202112862,'For Exam',2,'2024-11-23 21:13:00','YYU8-00OGXT-99','2024-11-23 16:14:00',NULL,NULL,'2024-11-23 16:14:00',NULL,NULL,'29889','Wednesday 2-5','PC Repair and Troubleshooting','(Luis Jimenez - 202111000), (Ayen Tipon - 202110278)',NULL,NULL),(17018,202112862,1,50042,'2024-11-20 12:00:00',202112862,'www',6,'2024-11-20 13:00:00','ennfkek3','2024-11-19 14:49:13',202118555,202118402,'2024-11-23 16:29:09','Returned with Care <3',NULL,'01100','MWF - 12:00 - 1:00','PC Repair',NULL,NULL,202112862),(27811,202118123,1,266,'2024-11-20 06:00:00',202110278,'a',6,'2024-11-20 19:00:00','xt56h58j3','2024-11-16 03:00:40',202118555,202118555,'2024-11-19 14:28:00',NULL,NULL,'a','a','a','a',NULL,NULL),(30393,202113240,3,98997,'2024-10-11 16:13:00',202118123,'Temporary mouse for not working mouse in Mac Lab',6,'2024-10-13 15:13:00','tgethrty5-09','2024-10-11 15:13:16',202118402,202118402,'2024-11-16 00:31:46','Returned with Damage',NULL,'BSIT 401','MW 12-1','Testing, Deployment, and Maintenance','(Valdez, Daniel - 202118402), (Sepuntos, Rejean - 202118555)',202118123,NULL),(31962,202118555,1,50042,'2024-10-31 01:59:00',202118123,'wqwqwdqwqd',8,'2024-11-01 00:59:00','ennfkek3','2024-10-31 00:59:57',202118402,202118402,'2024-11-16 00:19:55',NULL,NULL,'BSIT 401','M 12-3','App Dev',NULL,202118123,NULL),(47275,202118009,1,266,'2024-11-21 15:32:00',202118111,'sad',8,'2024-11-21 17:33:00','xt56h58j3','2024-11-21 14:33:09',NULL,NULL,'2024-11-21 14:33:28',NULL,NULL,'29002','MW 12-1','Testing','sadawdq',NULL,NULL),(50042,202112862,1,50042,'2024-11-20 14:00:00',202112862,'ww',6,'2024-11-20 17:00:00','ennfkek3','2024-11-19 12:59:16',202118555,202118555,'2024-11-19 13:59:19',NULL,NULL,'01100','MWF 7:00am-8:00am','Digital Design',NULL,NULL,NULL),(52709,202112862,1,50042,'2024-11-20 12:00:00',202112862,'EE',6,'2024-11-20 13:00:00','ennfkek3','2024-11-19 14:32:07',202118555,202118555,'2024-11-19 14:41:40',NULL,NULL,'00101','MWF 12:00 - 1:00 PM','English Proficiency',NULL,NULL,202118555),(52803,202112862,1,266,'2024-11-20 01:47:00',202118123,'a',6,'2024-11-20 14:47:00','xt56h58j3','2024-11-16 01:51:39',202118555,202118555,'2024-11-19 13:58:17',NULL,NULL,'a','a','sda','a',NULL,NULL),(58695,202118111,3,65243,'2024-11-11 02:43:00',202112862,'dweadsfdedae',4,'2024-11-12 01:43:00','YYU8-00OGXT-99','2024-11-11 01:44:03',NULL,NULL,'2024-11-11 02:33:26',NULL,NULL,'BSIT 401','Monday - 6-9PM','App Dev','mwmwmwm',202112862,NULL),(59544,202112862,1,266,'2024-11-21 11:00:00',202112862,'test',6,'2024-11-21 13:00:00','xt56h58j3','2024-11-19 14:22:16',202118555,202118402,'2024-11-23 16:30:01',NULL,NULL,'01101','MWF 12:00 - 1:00','Animation',NULL,NULL,202118555),(62210,202118009,1,266,'2024-11-21 15:33:00',202118111,'EAEF',8,'2024-11-21 17:33:00','xt56h58j3','2024-11-21 14:35:26',202112862,NULL,'2024-11-23 14:48:53','Conflict',NULL,'29292','eaf','Testing','wDWQ',NULL,NULL),(71281,202118555,1,50042,'2024-10-16 12:00:00',202118555,'For Lecture Purposes',8,'2024-10-16 13:00:00','ennfkek3','2024-10-13 05:07:19',NULL,NULL,'2024-10-31 00:59:37',NULL,NULL,'01100','MWF 12:00 - 1:00 PM','English Proficiency',NULL,NULL,NULL),(74523,202118111,3,65243,'2024-11-23 16:45:00',202118111,'For Activity',2,'2024-11-23 19:30:00','YYU8-00OGXT-99','2024-11-23 15:34:02',NULL,NULL,'2024-11-23 15:34:02',NULL,NULL,'29008','Tuesday 2:00-3:00','PC Repair',NULL,NULL,NULL),(79414,202118555,1,266,'2024-11-20 18:46:00',202118555,'werw',4,'2024-11-20 22:46:00','xt56h58j3','2024-11-20 16:46:23',NULL,NULL,'2024-11-22 14:54:03',NULL,NULL,'wewefw','wefwef','wefw','wer',NULL,NULL),(84738,202112862,1,50042,'2024-10-13 12:00:00',202118123,'For Lecture',4,'2024-10-13 14:15:00','ennfkek3','2024-10-13 11:19:25',NULL,NULL,'2024-11-12 14:10:38',NULL,NULL,'4th Year 08192','2:00PM-5:00PM','PC Repair and Trouble Shooting Lab',NULL,NULL,NULL),(89259,202118009,1,266,'2024-11-23 19:25:00',202118555,'For Activity #3',2,'2024-11-23 23:25:00','xt56h58j3','2024-11-23 18:25:17',NULL,NULL,'2024-11-23 18:25:17',NULL,NULL,'2829','Tuesday 2-3','App Dev',NULL,NULL,NULL),(93549,202118009,1,63042,'2024-11-22 14:40:00',202118555,'sadaw',4,'2024-11-22 15:40:00','09ET31','2024-11-22 13:43:49',NULL,NULL,'2024-11-23 14:42:56','Lost',NULL,'2902','Monday 383','Testing','we',202118555,NULL),(94009,202112862,1,266,'2024-11-20 14:00:00',202112862,'eee',4,'2024-11-20 16:00:00','xt56h58j3','2024-11-19 12:47:12',NULL,NULL,'2024-11-19 12:49:05','item is reserved',NULL,'01100','MWF 7:00am-8:00am','Digital Design',NULL,NULL,NULL),(99527,202112862,3,65243,'2024-11-11 03:34:00',202118111,'a',8,'2024-11-12 02:34:00','YYU8-00OGXT-99','2024-11-11 02:34:16',NULL,NULL,'2024-11-16 18:58:31',NULL,NULL,'q','q','q','q',NULL,NULL);
/*!40000 ALTER TABLE `user_reservation_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_type`
--

DROP TABLE IF EXISTS `user_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_type` (
  `type_id` int NOT NULL,
  `user_type` varchar(45) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `edited_by` int DEFAULT NULL,
  PRIMARY KEY (`type_id`),
  UNIQUE KEY `user_type_UNIQUE` (`user_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_type`
--

LOCK TABLES `user_type` WRITE;
/*!40000 ALTER TABLE `user_type` DISABLE KEYS */;
INSERT INTO `user_type` VALUES (1,'User','2024-06-16 18:46:43',NULL,NULL),(2,'Admin','2024-06-16 18:46:43',NULL,NULL),(3,'Master Admin','2024-06-16 18:46:43',NULL,NULL),(4,'System Admin','2024-09-06 19:37:30',NULL,NULL);
/*!40000 ALTER TABLE `user_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `verify_tokens`
--

DROP TABLE IF EXISTS `verify_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `verify_tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `token` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `is_activated` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=221 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `verify_tokens`
--

LOCK TABLES `verify_tokens` WRITE;
/*!40000 ALTER TABLE `verify_tokens` DISABLE KEYS */;
INSERT INTO `verify_tokens` VALUES (152,'8702024','danielasanon54@gmail.com',0,'2024-09-21 14:10:28'),(153,'5632024','onehehe01@gmail.com',0,'2024-09-24 16:14:13'),(156,'1242024','dan@adamson.edu.ph',0,'2024-09-25 05:36:41'),(157,'192024','danielasanon54@gmail.com',0,'2024-09-28 14:21:42'),(159,'6852024','danielasanon54@gmail.com',0,'2024-09-29 03:43:46'),(160,'2872024','danielasanon54@gmail.com',0,'2024-09-29 04:04:49'),(161,'7842024','danielasanon54@gmail.com',0,'2024-09-29 04:06:46'),(162,'1372024','danielasanon54@gmail.com',0,'2024-09-29 04:11:03'),(163,'7202024','danielasanon54@gmail.com',0,'2024-09-29 04:13:11'),(165,'9822024','danielasanon54@gmail.com',0,'2024-09-29 04:18:22'),(167,'402024','daniel.valdez@adamson.edu.ph',0,'2024-09-29 07:35:55'),(168,'9542024','daniel.valdez@adamson.edu.ph',0,'2024-09-29 07:36:41'),(169,'2882024','danielasanon54@gmail.com',0,'2024-09-29 07:53:32'),(170,'5762024','daniel.valdez@adamson.edu.ph',0,'2024-09-29 07:53:47'),(171,'9842024','danielasanon54@gmail.com',0,'2024-09-29 07:54:28'),(172,'3812024','danielasanon54@gmail.com',0,'2024-09-29 07:54:56'),(173,'6202024','danielasanon54@gmail.com',0,'2024-09-29 07:55:38'),(174,'2942024','daniel.valdez@adamson.edu.ph',0,'2024-09-29 07:55:49'),(175,'492024','danielasanon54@gmail.com',0,'2024-10-05 13:33:33'),(176,'712024','danielasanon54@gmail.com',0,'2024-10-06 12:06:43'),(177,'662024','daniel.valdez@adamson.edu.ph',0,'2024-10-06 12:08:24'),(178,'7052024','danielasanon54@gmail.com',0,'2024-10-07 10:14:59'),(179,'3112024','danielasanon54@gmail.com',0,'2024-10-07 10:28:06'),(180,'882024','danielasanon54@gmail.com',0,'2024-10-07 10:31:02'),(181,'322024','danielasanon54@gmail.com',0,'2024-10-07 10:42:15'),(182,'2132024','daniel.valdez@adamson.edu.ph',0,'2024-10-07 10:50:38'),(183,'3142024','rejean.sepuntos@adamson.edu.ph',0,'2024-10-07 10:57:28'),(184,'5412024','danielasanon54@gmail.com',0,'2024-10-07 10:58:21'),(185,'422024','danielasanon54@gmail.com',0,'2024-10-07 11:03:38'),(186,'3962024','jin@adamson.edu.ph',0,'2024-10-07 11:11:50'),(187,'4182024','daniel.valdez@adamson.edu.ph',0,'2024-10-07 11:17:02'),(188,'5282024','rejean.sepuntos@adamson.edu.ph',0,'2024-10-08 03:40:57'),(189,'5092024','rejean.sepuntos@adamson.edu.ph',0,'2024-10-08 05:43:51'),(190,'492024','daniel.val1323dez@adamson.edu.ph',0,'2024-10-08 16:29:39'),(192,'2422024','danielasanon54@gmail.com',0,'2024-10-09 02:16:13'),(193,'5022024','danielasanon54@gmail.com',0,'2024-10-09 02:27:36'),(194,'7282024','rejean.sepuntos@adamson.edu.ph',0,'2024-10-09 03:42:16'),(195,'3832024','daniel.valdez@adamson.edu.ph',0,'2024-10-09 17:46:41'),(196,'4912024','daniel.valdez@adamson.edu.ph',0,'2024-10-09 17:55:34'),(197,'6592024','daniel.valdezweqi@adamson.edu.ph',0,'2024-10-10 12:14:12'),(198,'4382024','rejean.sepuntos@adamson.edu.ph',0,'2024-10-10 12:33:01'),(199,'1602024','jamelah.ayen.tipon@adamson.edu.ph',0,'2024-10-10 18:47:06'),(200,'1602024','jamelah.ayen.tipon@adamson.edu.ph',0,'2024-10-10 18:50:02'),(201,'7092024','danielasanon54@gmail.com',0,'2024-10-10 19:04:02'),(202,'8962024','jamelah.ayen.tipon@adamson.edu.ph',0,'2024-10-12 13:57:51'),(203,'5972024','danielasanon54@gmail.com',0,'2024-10-12 14:50:30'),(204,'1622024','jamelah.ayen.tipon@adamson.edu.ph',0,'2024-10-12 15:02:27'),(205,'6042024','danielasanon54@gmail.com',0,'2024-10-12 15:24:11'),(206,'1642024','sam.vega@gmail.com',0,'2024-10-12 15:51:44'),(207,'7002024','danielasanon54@gmail.com',0,'2024-10-12 15:52:26'),(208,'7622024','danielasanon@gmail.com',0,'2024-10-12 16:10:51'),(209,'2852024','daniel.valdez@adamson.edu.ph',0,'2024-10-12 16:12:12'),(210,'7012024','danielasanon54@gmail.com',0,'2024-10-12 21:28:01'),(211,'7322024','luis.victor.jimenez@adamson.edu.ph',0,'2024-10-13 02:32:43'),(212,'9732024','luis.victor.jimenez@adamson.edu.ph',0,'2024-10-13 02:33:49'),(213,'2102024','luis.victor.jimenez@adamson.edu.ph',0,'2024-10-13 02:38:59'),(214,'5742024','violation_admin@gmail.com',0,'2024-11-09 19:23:42'),(215,'8402024','mary.madelaine.escarlan@adamson.edu.ph',0,'2024-11-09 19:24:35'),(216,'7592024','mary.madelaine.escarlan@adamson.edu.ph',0,'2024-11-09 19:25:08'),(217,'1642024','daniel.valdez@adamson.edu.ph',0,'2024-11-09 19:25:24'),(218,'1132024','danielasanon54@gmail.com',0,'2024-11-10 17:40:57'),(219,'3672024','daniel.valdez@adamson.edu.ph',0,'2024-11-23 17:47:33'),(220,'9272024','daniel.valdez@adamson.edu.ph',0,'2024-11-29 16:40:45');
/*!40000 ALTER TABLE `verify_tokens` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-11-30  1:01:30
