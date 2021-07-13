-- MySQL dump 10.13  Distrib 5.5.49, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: thedepartureclub
-- ------------------------------------------------------
-- Server version	5.5.49-0ubuntu0.14.04.1

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
-- Table structure for table `aircrafts_images`
--

DROP TABLE IF EXISTS `aircrafts_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aircrafts_images` (
  `image_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aircraft_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `default` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`image_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aircrafts_images`
--

LOCK TABLES `aircrafts_images` WRITE;
/*!40000 ALTER TABLE `aircrafts_images` DISABLE KEYS */;
INSERT INTO `aircrafts_images` VALUES (1,1,'test-plane.jpg','data/images/aircrafts/c4ca4238a0b923820dcc509a6f75849b/',1,1,'2018-07-18 12:57:53','2018-07-18 12:57:53'),(2,2,'hawker-400.jpg','data/images/aircrafts/c81e728d9d4c2f636f067f89cc14862c/',1,1,'2018-07-19 03:35:55','2018-07-19 03:35:55'),(3,3,'xls.jpg','data/images/aircrafts/eccbc87e4b5ce2fe28308fd9f2a7baf3/',1,1,'2018-07-19 03:37:36','2018-07-19 03:37:36'),(4,4,'hawker-400.jpg','data/images/aircrafts/a87ff679a2f3e71d9181a67b7542122c/',1,1,'2018-07-19 03:58:51','2018-07-19 03:58:51'),(5,5,'vail.jpg','data/images/aircrafts/e4da3b7fbbce2345d7772b0674a318d5/',1,1,'2018-07-19 04:07:09','2018-07-19 04:07:09'),(6,6,'jackson-hole.jpg','data/images/aircrafts/1679091c5a880faf6fb5e6087eb1b2dc/',1,1,'2018-07-19 04:13:50','2018-07-19 04:13:50'),(7,7,'vancouver.jpg','data/images/aircrafts/8f14e45fceea167a5a36dedd4bea2543/',1,1,'2018-07-19 04:18:17','2018-07-19 04:18:17'),(8,8,'m2.jpg','data/images/aircrafts/c9f0f895fb98ab9159f51fd0297e236d/',1,1,'2018-09-10 22:28:27','2018-09-10 22:28:27'),(9,9,'eclipse.jpg','data/images/aircrafts/45c48cce2e2d7fbdea1afc51c7c6ad26/',1,1,'2018-09-11 00:13:55','2018-09-11 00:13:55'),(10,10,'lear.jpg','data/images/aircrafts/d3d9446802a44259755d38e6d163e820/',1,1,'2018-09-11 00:17:44','2018-09-11 00:17:44'),(11,11,'king-air-200.jpg','data/images/aircrafts/6512bd43d9caa6e02c990b0a82652dca/',1,1,'2018-09-11 00:25:48','2018-09-11 00:25:48'),(12,12,'lear-45.jpg','data/images/aircrafts/c20ad4d76fe97759aa27a0c99bff6710/',1,1,'2018-09-11 00:38:24','2018-09-11 00:38:24'),(13,15,'g-iv.jpg','data/images/aircrafts/c51ce410c124a10e0db5e4b97fc2af39/',1,1,'2018-09-11 00:44:35','2018-09-11 00:44:35'),(14,16,'g150.jpg','data/images/aircrafts/aab3238922bcc25a6f606eb525ffdc56/',1,1,'2018-09-17 21:27:55','2018-09-17 21:27:55'),(15,17,'phenom-100.jpg','data/images/aircrafts/9bf31c7ff062936a96d3c8bd1f8f2ff3/',1,1,'2018-09-17 21:31:26','2018-09-17 21:31:26'),(16,20,'light-jet.jpg','data/images/aircrafts/c74d97b01eae257e44aa9d5bade97baf/',1,1,'2018-10-06 02:44:24','2018-10-06 02:44:24'),(17,21,'very-light-jet.jpg','data/images/aircrafts/70efdf2ec9b086079795c442636b55fb/',1,1,'2018-10-06 02:50:58','2018-10-06 02:50:58'),(18,22,'piston.jpg','data/images/aircrafts/6f4922f45568161a8cdf4ad2299f6d23/',1,1,'2018-10-11 00:42:45','2018-10-11 00:42:45'),(19,23,'g200.jpg','data/images/aircrafts/1f0e3dad99908345f7439f8ffabdffc4/',1,1,'2019-02-20 22:08:58','2019-02-20 22:08:58'),(20,24,'g200.jpg','data/images/aircrafts/98f13708210194c475687be6106a3b84/',1,1,'2019-02-20 22:10:27','2019-02-20 22:10:27'),(21,25,'g-200.jpg','data/images/aircrafts/3c59dc048e8850243be8079a5c74d079/',1,1,'2019-02-20 22:11:44','2019-02-20 22:11:44'),(22,23,'g2002.jpg','data/images/aircrafts/b6d767d2f8ed5d21a44b0e5886680cb9/',0,1,'2019-02-20 22:12:09','2019-02-20 22:12:09'),(23,26,'sr-22.jpg','data/images/aircrafts/37693cfc748049e45d87b8c7d8b9aacd/',1,1,'2019-04-23 00:33:50','2019-04-23 00:33:50'),(24,27,'super-mid.jpg','data/images/aircrafts/1ff1de774005f8da13f42943881c655f/',1,1,'2019-07-30 07:35:20','2019-07-30 07:35:20'),(25,28,'super-mid.jpg','data/images/aircrafts/8e296a067a37563370ded05f5a3bf3ec/',1,1,'2019-07-30 07:36:29','2019-07-30 07:36:29');
/*!40000 ALTER TABLE `aircrafts_images` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-05-31 19:18:49
