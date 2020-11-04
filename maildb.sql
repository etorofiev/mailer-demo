-- MySQL dump 10.13  Distrib 5.7.32, for Linux (x86_64)
--
-- Host: localhost    Database: mailer
-- ------------------------------------------------------
-- Server version	5.7.32-0ubuntu0.16.04.1

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

--
-- Table structure for table `fields`
--

DROP TABLE IF EXISTS `fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('date','number','string','boolean') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fields`
--

LOCK TABLES `fields` WRITE;
/*!40000 ALTER TABLE `fields` DISABLE KEYS */;
INSERT INTO `fields` VALUES (1,'company','string'),(2,'country','string'),(3,'hubspot_id','number'),(4,'premium','boolean'),(5,'premium_since','date');
/*!40000 ALTER TABLE `fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscribers`
--

DROP TABLE IF EXISTS `subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` enum('active','unsubscribed','junk','bounced','unconfirmed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscribers_email_uindex` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscribers`
--

LOCK TABLES `subscribers` WRITE;
/*!40000 ALTER TABLE `subscribers` DISABLE KEYS */;
INSERT INTO `subscribers` VALUES (1,'John Wick','active','john.wick@example.com'),(2,'Laurence Fishburne','active','laurence.fishburne@example.com'),(3,'Hugo Weaving','unsubscribed','hugo.weaving@example.com'),(4,'Alex Høgh Andersen','active','alex.andersen@example.com'),(5,'Viggo Mortensen','bounced','viggo.mortensen@examplee.com'),(6,'Matt Damon','active','matt.damon@example.com'),(7,'Sean Bean','active','sean.bean@example.com'),(8,'Peter Dinklage','active','peter.dinklage@example.com'),(9,'Travis Fimmel','unsubscribed','travis.fimmel@example.com'),(10,'Gustaf Skarsgård','bounced','gustaf.skarsgard@example.com'),(11,'Christian Bale','active','christian.bale@example.com'),(12,'Katheryn Winnick','unsubscribed','katheryn.winnick@example.com'),(13,'Tom Riley','bounced','tom.riley@example.com'),(14,'Laura Haddock','unsubscribed','laura.haddock@example.com'),(15,'Alexander Siddig','active','alexander.siddig@example.com'),(16,'Jonathan Rhys Meyers','bounced','johathan.meyers@example.com'),(17,'Anya Chalotra','unsubscribed','anya.chalotra@example.com'),(18,'Henry Cavill','unsubscribed','henry.cavill@example.com'),(19,'Ashley Johnson','bounced','ashley.johnson@exampke.com'),(20,'Matt Mercer','active','matt.mercer@example.com');
/*!40000 ALTER TABLE `subscribers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscribers_fields`
--

DROP TABLE IF EXISTS `subscribers_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscribers_fields` (
  `id` int(11) NOT NULL,
  `subscriber_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `value` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `subscribers_fields_fields_id_fk` (`field_id`),
  KEY `subscribers_fields_subscribers_id_fk` (`subscriber_id`),
  CONSTRAINT `subscribers_fields_fields_id_fk` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `subscribers_fields_subscribers_id_fk` FOREIGN KEY (`subscriber_id`) REFERENCES `subscribers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscribers_fields`
--

LOCK TABLES `subscribers_fields` WRITE;
/*!40000 ALTER TABLE `subscribers_fields` DISABLE KEYS */;
INSERT INTO `subscribers_fields` VALUES (1,1,1,'John Wick','2020-11-04 21:07:18','2020-11-04 21:07:18'),(2,1,2,'USA','2020-11-04 21:07:25','2020-11-04 21:07:25'),(3,1,3,'10','2020-11-04 21:07:38','2020-11-04 21:07:38'),(4,1,4,'true','2020-11-04 21:07:57','2020-11-04 21:07:57'),(5,1,5,'2020-11-04 21:08:21','2020-11-04 21:08:21','2020-11-04 21:08:21'),(6,2,1,'John Wick','2020-11-04 21:07:18','2020-11-04 21:07:18'),(7,2,2,'USA','2020-11-04 21:07:25','2020-11-04 21:07:25'),(8,2,3,'12','2020-11-04 21:07:38','2020-11-04 21:07:38'),(9,2,4,'false','2020-11-04 21:07:57','2020-11-04 21:07:57'),(10,2,5,NULL,'2020-11-04 21:08:21','2020-11-04 21:08:21'),(11,3,1,'V for Vendetta','2020-11-04 21:07:18','2020-11-04 21:07:18'),(12,3,2,'USA','2020-11-04 21:07:25','2020-11-04 21:07:25'),(13,3,3,'15','2020-11-04 21:07:38','2020-11-04 21:07:38'),(14,3,4,'false','2020-11-04 21:07:57','2020-11-04 21:07:57'),(15,3,5,NULL,'2020-11-04 21:08:21','2020-11-04 21:08:21'),(16,4,1,'Vikings','2020-11-04 21:07:18','2020-11-04 21:07:18'),(17,4,2,'Denmark','2020-11-04 21:07:25','2020-11-04 21:07:25'),(18,4,3,'21','2020-11-04 21:07:38','2020-11-04 21:07:38'),(19,4,4,'true','2020-11-04 21:07:57','2020-11-04 21:07:57'),(20,4,5,'2020-11-04 21:08:21','2020-11-04 21:08:21','2020-11-04 21:08:21'),(21,5,1,'Lord of the Rings','2020-11-04 21:07:18','2020-11-04 21:07:18'),(22,5,2,'Denmark','2020-11-04 21:07:25','2020-11-04 21:07:25'),(23,5,3,'23','2020-11-04 21:07:38','2020-11-04 21:07:38'),(24,5,4,'true','2020-11-04 21:07:57','2020-11-04 21:07:57'),(25,5,5,'2020-11-04 21:08:21','2020-11-04 21:08:21','2020-11-04 21:08:21'),(26,6,1,'The Martian','2020-11-04 21:07:18','2020-11-04 21:07:18'),(27,6,2,'USA','2020-11-04 21:07:25','2020-11-04 21:07:25'),(28,6,3,'25','2020-11-04 21:07:38','2020-11-04 21:07:38'),(29,6,4,'true','2020-11-04 21:07:57','2020-11-04 21:07:57'),(30,6,5,'2020-11-04 21:08:21','2020-11-04 21:08:21','2020-11-04 21:08:21'),(31,7,1,'Lord of the Rings','2020-11-04 21:07:18','2020-11-04 21:07:18'),(32,7,2,'USA','2020-11-04 21:07:25','2020-11-04 21:07:25'),(33,7,3,'27','2020-11-04 21:07:38','2020-11-04 21:07:38'),(34,7,4,'false','2020-11-04 21:07:57','2020-11-04 21:07:57'),(35,7,5,NULL,'2020-11-04 21:08:21','2020-11-04 21:08:21'),(36,8,1,'Game of Thrones','2020-11-04 21:07:18','2020-11-04 21:07:18'),(37,8,2,'USA','2020-11-04 21:07:25','2020-11-04 21:07:25'),(38,8,3,'29','2020-11-04 21:07:38','2020-11-04 21:07:38'),(39,8,4,'false','2020-11-04 21:07:57','2020-11-04 21:07:57'),(40,8,5,NULL,'2020-11-04 21:08:21','2020-11-04 21:08:21'),(41,9,1,'Vikings','2020-11-04 21:07:18','2020-11-04 21:07:18'),(42,9,2,'Denmark','2020-11-04 21:07:25','2020-11-04 21:07:25'),(43,9,3,'30','2020-11-04 21:07:38','2020-11-04 21:07:38'),(44,9,4,'true','2020-11-04 21:07:57','2020-11-04 21:07:57'),(45,9,5,'2020-11-04 21:08:21','2020-11-04 21:08:21','2020-11-04 21:08:21'),(46,10,1,'Vikings','2020-11-04 21:07:18','2020-11-04 21:07:18'),(47,10,2,'Denmark','2020-11-04 21:07:25','2020-11-04 21:07:25'),(48,10,3,'31','2020-11-04 21:07:38','2020-11-04 21:07:38'),(49,10,4,'true','2020-11-04 21:07:57','2020-11-04 21:07:57'),(50,10,5,'2020-11-04 21:08:21','2020-11-04 21:08:21','2020-11-04 21:08:21'),(51,11,1,'Equilibrium','2020-11-04 21:07:18','2020-11-04 21:07:18'),(52,11,2,'USA','2020-11-04 21:07:25','2020-11-04 21:07:25'),(53,11,3,'32','2020-11-04 21:07:38','2020-11-04 21:07:38'),(54,11,4,'true','2020-11-04 21:07:57','2020-11-04 21:07:57'),(55,11,5,'2020-11-04 21:08:21','2020-11-04 21:08:21','2020-11-04 21:08:21'),(56,12,1,'Vikings','2020-11-04 21:07:18','2020-11-04 21:07:18'),(57,12,2,'Denmark','2020-11-04 21:07:25','2020-11-04 21:07:25'),(58,12,3,'33','2020-11-04 21:07:38','2020-11-04 21:07:38'),(59,12,4,'false','2020-11-04 21:07:57','2020-11-04 21:07:57'),(60,12,5,NULL,'2020-11-04 21:08:21','2020-11-04 21:08:21'),(61,13,1,'Da Vinci\'s Demons','2020-11-04 21:07:18','2020-11-04 21:07:18'),(62,13,2,'USA','2020-11-04 21:07:25','2020-11-04 21:07:25'),(63,13,3,'35','2020-11-04 21:07:38','2020-11-04 21:07:38'),(64,13,4,'false','2020-11-04 21:07:57','2020-11-04 21:07:57'),(65,13,5,NULL,'2020-11-04 21:08:21','2020-11-04 21:08:21'),(66,14,1,'Da Vinci\'s Demons','2020-11-04 21:07:18','2020-11-04 21:07:18'),(67,14,2,'USA','2020-11-04 21:07:25','2020-11-04 21:07:25'),(68,14,3,'38','2020-11-04 21:07:38','2020-11-04 21:07:38'),(69,14,4,'true','2020-11-04 21:07:57','2020-11-04 21:07:57'),(70,14,5,'2020-11-04 21:08:21','2020-11-04 21:08:21','2020-11-04 21:08:21'),(71,15,1,'Da Vinci\'s Demons','2020-11-04 21:07:18','2020-11-04 21:07:18'),(72,15,2,'USA','2020-11-04 21:07:25','2020-11-04 21:07:25'),(73,15,3,'45','2020-11-04 21:07:38','2020-11-04 21:07:38'),(74,15,4,'true','2020-11-04 21:07:57','2020-11-04 21:07:57'),(75,15,5,'2020-11-04 21:08:21','2020-11-04 21:08:21','2020-11-04 21:08:21'),(76,16,1,'The Tudors','2020-11-04 21:07:18','2020-11-04 21:07:18'),(77,16,2,'USA','2020-11-04 21:07:25','2020-11-04 21:07:25'),(78,16,3,'51','2020-11-04 21:07:38','2020-11-04 21:07:38'),(79,16,4,'true','2020-11-04 21:07:57','2020-11-04 21:07:57'),(80,16,5,'2020-11-04 21:08:21','2020-11-04 21:08:21','2020-11-04 21:08:21'),(81,17,1,'The Witcher','2020-11-04 21:07:18','2020-11-04 21:07:18'),(82,17,2,'USA','2020-11-04 21:07:25','2020-11-04 21:07:25'),(83,17,3,'53','2020-11-04 21:07:38','2020-11-04 21:07:38'),(84,17,4,'false','2020-11-04 21:07:57','2020-11-04 21:07:57'),(85,17,5,NULL,'2020-11-04 21:08:21','2020-11-04 21:08:21'),(86,18,1,'The Witcher','2020-11-04 21:07:18','2020-11-04 21:07:18'),(87,18,2,'USA','2020-11-04 21:07:25','2020-11-04 21:07:25'),(88,18,3,'54','2020-11-04 21:07:38','2020-11-04 21:07:38'),(89,18,4,'false','2020-11-04 21:07:57','2020-11-04 21:07:57'),(90,18,5,NULL,'2020-11-04 21:08:21','2020-11-04 21:08:21'),(91,19,1,'Critical Role','2020-11-04 21:07:18','2020-11-04 21:07:18'),(92,19,2,'USA','2020-11-04 21:07:25','2020-11-04 21:07:25'),(93,19,3,'57','2020-11-04 21:07:38','2020-11-04 21:07:38'),(94,19,4,'true','2020-11-04 21:07:57','2020-11-04 21:07:57'),(95,19,5,'2020-11-04 21:08:21','2020-11-04 21:08:21','2020-11-04 21:08:21'),(96,20,1,'Critical Role','2020-11-04 21:07:18','2020-11-04 21:07:18'),(97,20,2,'USA','2020-11-04 21:07:25','2020-11-04 21:07:25'),(98,20,3,'59','2020-11-04 21:07:38','2020-11-04 21:07:38'),(99,20,4,'true','2020-11-04 21:07:57','2020-11-04 21:07:57'),(100,20,5,'2020-11-04 21:08:21','2020-11-04 21:08:21','2020-11-04 21:08:21');
/*!40000 ALTER TABLE `subscribers_fields` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-11-04 21:43:32
