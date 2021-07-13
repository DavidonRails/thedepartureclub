-- MySQL dump 10.13  Distrib 5.7.30, for Linux (x86_64)
--
-- Host: localhost    Database: thedepartureclub
-- ------------------------------------------------------
-- Server version	5.7.30-0ubuntu0.18.04.1

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
-- Table structure for table `aircrafts`
--

DROP TABLE IF EXISTS `aircrafts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aircrafts` (
  `aircraft_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `manufacturer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `seats` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `flight_price` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`aircraft_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aircrafts`
--

LOCK TABLES `aircrafts` WRITE;
/*!40000 ALTER TABLE `aircrafts` DISABLE KEYS */;
INSERT INTO `aircrafts` VALUES (3,'Midsize','','8',1,'2018-07-19 03:37:36','2019-07-30 07:28:38',0,'0'),(10,'Midsize','','8',1,'2018-09-11 00:17:43','2019-07-30 07:28:34',0,'0'),(11,'Turbo Prop','','7',1,'2018-09-11 00:25:48','2019-06-23 11:12:42',0,'0'),(13,'G-IV','Gulfstream','10',1,'2018-09-11 00:42:08','2018-09-11 00:42:08',0,'0'),(14,'G-IV','Gulfstream','10',1,'2018-09-11 00:43:38','2018-09-11 00:43:38',0,'0'),(15,'Heavy Jet','','10',1,'2018-09-11 00:44:35','2019-06-23 11:13:32',0,'0'),(16,'Super-Mid','','8',1,'2018-09-17 21:27:55','2019-07-30 07:29:23',0,'0'),(17,'Very Light Jet','','4',1,'2018-09-17 21:31:26','2019-07-30 07:29:03',0,'0'),(18,'San Diego','Light Jet','6',1,'2018-10-06 02:38:50','2018-10-06 02:38:50',0,'0'),(19,'San Diego','Light Jet','6',1,'2018-10-06 02:42:10','2018-10-06 02:42:10',0,'0'),(20,'Light Jet','Hawker','6',1,'2018-10-06 02:44:23','2019-06-23 11:07:57',0,'0'),(22,'Piston','Cirrus','3',1,'2018-10-11 00:42:45','2018-10-11 00:42:45',0,'0'),(23,'Test','Test','14',1,'2020-06-05 00:36:04','2020-06-05 00:36:04',0,'0');
/*!40000 ALTER TABLE `aircrafts` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aircrafts_images`
--

LOCK TABLES `aircrafts_images` WRITE;
/*!40000 ALTER TABLE `aircrafts_images` DISABLE KEYS */;
INSERT INTO `aircrafts_images` VALUES (1,1,'test-plane.jpg','data/images/aircrafts/c4ca4238a0b923820dcc509a6f75849b/',1,1,'2018-07-18 12:57:53','2018-07-18 12:57:53'),(2,2,'hawker-400.jpg','data/images/aircrafts/c81e728d9d4c2f636f067f89cc14862c/',1,1,'2018-07-19 03:35:55','2018-07-19 03:35:55'),(3,3,'xls.jpg','data/images/aircrafts/eccbc87e4b5ce2fe28308fd9f2a7baf3/',1,1,'2018-07-19 03:37:36','2018-07-19 03:37:36'),(4,4,'hawker-400.jpg','data/images/aircrafts/a87ff679a2f3e71d9181a67b7542122c/',1,1,'2018-07-19 03:58:51','2018-07-19 03:58:51'),(5,5,'vail.jpg','data/images/aircrafts/e4da3b7fbbce2345d7772b0674a318d5/',1,1,'2018-07-19 04:07:09','2018-07-19 04:07:09'),(6,6,'jackson-hole.jpg','data/images/aircrafts/1679091c5a880faf6fb5e6087eb1b2dc/',1,1,'2018-07-19 04:13:50','2018-07-19 04:13:50'),(7,7,'vancouver.jpg','data/images/aircrafts/8f14e45fceea167a5a36dedd4bea2543/',1,1,'2018-07-19 04:18:17','2018-07-19 04:18:17'),(8,8,'m2.jpg','data/images/aircrafts/c9f0f895fb98ab9159f51fd0297e236d/',1,1,'2018-09-10 22:28:27','2018-09-10 22:28:27'),(9,9,'eclipse.jpg','data/images/aircrafts/45c48cce2e2d7fbdea1afc51c7c6ad26/',1,1,'2018-09-11 00:13:55','2018-09-11 00:13:55'),(10,10,'lear.jpg','data/images/aircrafts/d3d9446802a44259755d38e6d163e820/',1,1,'2018-09-11 00:17:44','2018-09-11 00:17:44'),(11,11,'king-air-200.jpg','data/images/aircrafts/6512bd43d9caa6e02c990b0a82652dca/',1,1,'2018-09-11 00:25:48','2018-09-11 00:25:48'),(12,12,'lear-45.jpg','data/images/aircrafts/c20ad4d76fe97759aa27a0c99bff6710/',1,1,'2018-09-11 00:38:24','2018-09-11 00:38:24'),(13,15,'g-iv.jpg','data/images/aircrafts/c51ce410c124a10e0db5e4b97fc2af39/',1,1,'2018-09-11 00:44:35','2018-09-11 00:44:35'),(14,16,'g150.jpg','data/images/aircrafts/aab3238922bcc25a6f606eb525ffdc56/',1,1,'2018-09-17 21:27:55','2018-09-17 21:27:55'),(15,17,'phenom-100.jpg','data/images/aircrafts/9bf31c7ff062936a96d3c8bd1f8f2ff3/',1,1,'2018-09-17 21:31:26','2018-09-17 21:31:26'),(16,20,'light-jet.jpg','data/images/aircrafts/c74d97b01eae257e44aa9d5bade97baf/',1,1,'2018-10-06 02:44:24','2018-10-06 02:44:24'),(17,21,'very-light-jet.jpg','data/images/aircrafts/70efdf2ec9b086079795c442636b55fb/',1,1,'2018-10-06 02:50:58','2018-10-06 02:50:58'),(18,22,'piston.jpg','data/images/aircrafts/6f4922f45568161a8cdf4ad2299f6d23/',1,1,'2018-10-11 00:42:45','2018-10-11 00:42:45'),(19,23,'g200.jpg','data/images/aircrafts/1f0e3dad99908345f7439f8ffabdffc4/',0,1,'2019-02-20 22:08:58','2020-06-05 00:36:04'),(20,24,'g200.jpg','data/images/aircrafts/98f13708210194c475687be6106a3b84/',1,1,'2019-02-20 22:10:27','2019-02-20 22:10:27'),(21,25,'g-200.jpg','data/images/aircrafts/3c59dc048e8850243be8079a5c74d079/',1,1,'2019-02-20 22:11:44','2019-02-20 22:11:44'),(22,23,'g2002.jpg','data/images/aircrafts/b6d767d2f8ed5d21a44b0e5886680cb9/',0,1,'2019-02-20 22:12:09','2020-06-05 00:36:04'),(23,26,'sr-22.jpg','data/images/aircrafts/37693cfc748049e45d87b8c7d8b9aacd/',1,1,'2019-04-23 00:33:50','2019-04-23 00:33:50'),(24,27,'super-mid.jpg','data/images/aircrafts/1ff1de774005f8da13f42943881c655f/',1,1,'2019-07-30 07:35:20','2019-07-30 07:35:20'),(25,28,'super-mid.jpg','data/images/aircrafts/8e296a067a37563370ded05f5a3bf3ec/',1,1,'2019-07-30 07:36:29','2019-07-30 07:36:29'),(26,23,'test.jpg','data/images/aircrafts/4e732ced3463d06de0ca9a15b6153677/',1,1,'2020-06-05 00:36:04','2020-06-05 00:36:04');
/*!40000 ALTER TABLE `aircrafts_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `airports`
--

DROP TABLE IF EXISTS `airports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `airports` (
  `airport_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `icao` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `iata` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`airport_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `airports`
--

LOCK TABLES `airports` WRITE;
/*!40000 ALTER TABLE `airports` DISABLE KEYS */;
INSERT INTO `airports` VALUES (1,40.824917,-115.791694,1,'2019-06-04 09:21:22','2019-06-04 09:21:22','KEKO','EKO','Elko Regional Airport','Elko, NV','United States','US'),(2,34.426194,-119.8415,1,'2019-06-04 09:21:22','2019-06-04 09:21:22','KSBA','SBA','Santa Barbara Municipal Airport','Santa Barbara, CA','United States','US'),(3,40.824917,-115.791694,1,'2019-06-04 09:21:22','2019-06-04 09:21:22','KEKO','EKO','Elko Regional Airport','Elko, NV','United States','US'),(4,33.307833,-111.655472,1,'2019-06-04 09:21:22','2019-06-04 09:21:22','KIWA','AZA','Phoenix-mesa Gateway Airport','Phoenix, AZ','United States','US'),(5,35.155972,-114.559444,1,'2019-06-04 09:21:22','2019-06-04 09:21:22','KIFP','IFP','Laughlin/bullhead International Airport','Bullhead City, AZ','United States','US'),(6,39.499111,-119.768111,1,'2019-06-04 09:21:22','2019-06-04 09:21:22','KRNO','RNO','Reno/tahoe International Airport','Reno, NV','United States','US'),(7,35.155972,-114.559444,1,'2019-06-04 09:21:22','2019-06-04 09:21:22','KIFP','IFP','Laughlin/bullhead International Airport','Bullhead City, AZ','United States','US'),(8,36.587,-121.842944,1,'2019-06-04 09:21:23','2019-06-04 09:21:23','KMRY','MRY','Monterey Regional Airport','Monterey, CA','United States','US'),(9,33.307833,-111.655472,1,'2019-06-04 09:21:23','2019-06-04 09:21:23','KIWA','AZA','Phoenix-mesa Gateway Airport','Phoenix, AZ','United States','US'),(10,33.12825,-117.280083,1,'2019-06-04 09:21:23','2019-06-04 09:21:23','KCRQ','CLD','Mc Clellan-palomar Airport','Carlsbad, CA','United States','US'),(11,33.12825,-117.280083,1,'2019-06-04 09:21:23','2019-06-04 09:21:23','KCRQ','CLD','Mc Clellan-palomar Airport','Carlsbad, CA','United States','US'),(12,33.307833,-111.655472,1,'2019-06-04 09:21:23','2019-06-04 09:21:23','KIWA','AZA','Phoenix-mesa Gateway Airport','Phoenix, AZ','United States','US'),(13,40.824917,-115.791694,1,'2019-06-04 09:21:23','2019-06-04 09:21:23','KEKO','EKO','Elko Regional Airport','Elko, NV','United States','US'),(14,33.12825,-117.280083,1,'2019-06-04 09:21:23','2019-06-04 09:21:23','KCRQ','CLD','Mc Clellan-palomar Airport','Carlsbad, CA','United States','US'),(15,36.587,-121.842944,1,'2019-06-04 09:21:24','2019-06-04 09:21:24','KMRY','MRY','Monterey Regional Airport','Monterey, CA','United States','US'),(16,35.155972,-114.559444,1,'2019-06-04 09:21:24','2019-06-04 09:21:24','KIFP','IFP','Laughlin/bullhead International Airport','Bullhead City, AZ','United States','US'),(17,33.12825,-117.280083,1,'2019-06-04 09:21:24','2019-06-04 09:21:24','KCRQ','CLD','Mc Clellan-palomar Airport','Carlsbad, CA','United States','US'),(18,33.307833,-111.655472,1,'2019-06-04 09:21:24','2019-06-04 09:21:24','KIWA','AZA','Phoenix-mesa Gateway Airport','Phoenix, AZ','United States','US'),(19,33.307833,-111.655472,1,'2019-06-04 09:21:24','2019-06-04 09:21:24','KIWA','AZA','Phoenix-mesa Gateway Airport','Phoenix, AZ','United States','US'),(20,36.587,-121.842944,1,'2019-06-04 09:21:24','2019-06-04 09:21:24','KMRY','MRY','Monterey Regional Airport','Monterey, CA','United States','US'),(21,35.393074,-97.600762,1,'2019-06-17 13:31:59','2019-06-17 13:31:59','KOKC','OKC','Will Rogers World Airport','Oklahoma City, OK','United States','US'),(22,33.622889,-111.910528,1,'2019-06-17 13:32:01','2019-06-17 13:32:01','KSDL','SCF','Scottsdale Airport','Scottsdale, AZ','United States','US'),(23,33.307833,-111.655472,1,'2019-06-18 02:27:03','2019-06-18 02:27:03','KIWA','AZA','Phoenix-mesa Gateway Airport','Phoenix, AZ','United States','US'),(24,35.155972,-114.559444,1,'2019-06-18 02:27:04','2019-06-18 02:27:04','KIFP','IFP','Laughlin/bullhead International Airport','Bullhead City, AZ','United States','US'),(25,35.155972,-114.559444,1,'2019-06-18 02:27:04','2019-06-18 02:27:04','KIFP','IFP','Laughlin/bullhead International Airport','Bullhead City, AZ','United States','US'),(26,33.307833,-111.655472,1,'2019-06-18 02:27:04','2019-06-18 02:27:04','KIWA','AZA','Phoenix-mesa Gateway Airport','Phoenix, AZ','United States','US'),(27,40.824917,-115.791694,1,'2019-06-18 02:27:04','2019-06-18 02:27:04','KEKO','EKO','Elko Regional Airport','Elko, NV','United States','US'),(28,33.307833,-111.655472,1,'2019-06-18 02:27:04','2019-06-18 02:27:04','KIWA','AZA','Phoenix-mesa Gateway Airport','Phoenix, AZ','United States','US'),(29,40.824917,-115.791694,1,'2019-06-18 02:27:04','2019-06-18 02:27:04','KEKO','EKO','Elko Regional Airport','Elko, NV','United States','US'),(30,35.155972,-114.559444,1,'2019-06-18 02:27:04','2019-06-18 02:27:04','KIFP','IFP','Laughlin/bullhead International Airport','Bullhead City, AZ','United States','US'),(31,36.587,-121.842944,1,'2019-06-18 02:27:05','2019-06-18 02:27:05','KMRY','MRY','Monterey Regional Airport','Monterey, CA','United States','US'),(32,33.12825,-117.280083,1,'2019-06-18 02:27:05','2019-06-18 02:27:05','KCRQ','CLD','Mc Clellan-palomar Airport','Carlsbad, CA','United States','US'),(33,35.155972,-114.559444,1,'2019-06-18 02:27:05','2019-06-18 02:27:05','KIFP','IFP','Laughlin/bullhead International Airport','Bullhead City, AZ','United States','US'),(34,36.587,-121.842944,1,'2019-06-18 02:27:05','2019-06-18 02:27:05','KMRY','MRY','Monterey Regional Airport','Monterey, CA','United States','US'),(35,33.12825,-117.280083,1,'2019-06-18 02:27:05','2019-06-18 02:27:05','KCRQ','CLD','Mc Clellan-palomar Airport','Carlsbad, CA','United States','US'),(36,40.824917,-115.791694,1,'2019-06-18 02:27:05','2019-06-18 02:27:05','KEKO','EKO','Elko Regional Airport','Elko, NV','United States','US'),(37,36.587,-121.842944,1,'2019-06-18 02:27:05','2019-06-18 02:27:05','KMRY','MRY','Monterey Regional Airport','Monterey, CA','United States','US'),(38,40.824917,-115.791694,1,'2019-06-18 02:27:06','2019-06-18 02:27:06','KEKO','EKO','Elko Regional Airport','Elko, NV','United States','US'),(39,40.824917,-115.791694,1,'2019-06-18 02:27:06','2019-06-18 02:27:06','KEKO','EKO','Elko Regional Airport','Elko, NV','United States','US'),(40,33.12825,-117.280083,1,'2019-06-18 02:27:06','2019-06-18 02:27:06','KCRQ','CLD','Mc Clellan-palomar Airport','Carlsbad, CA','United States','US'),(41,35.155972,-114.559444,1,'2019-06-18 02:27:06','2019-06-18 02:27:06','KIFP','IFP','Laughlin/bullhead International Airport','Bullhead City, AZ','United States','US'),(42,33.307833,-111.655472,1,'2019-06-18 02:27:06','2019-06-18 02:27:06','KIWA','AZA','Phoenix-mesa Gateway Airport','Phoenix, AZ','United States','US');
/*!40000 ALTER TABLE `airports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `alerts`
--

DROP TABLE IF EXISTS `alerts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alerts` (
  `alert_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `origin` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `destination` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` smallint(6) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `origin_longitude` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `origin_latitude` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `destination_longitude` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `destination_latitude` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`alert_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alerts`
--

LOCK TABLES `alerts` WRITE;
/*!40000 ALTER TABLE `alerts` DISABLE KEYS */;
INSERT INTO `alerts` VALUES (1,1,'San Diego International Airport (SAN), 3225 N Harbor Dr, San Diego, CA 92101, USA','Phoenix, AZ, USA',1,'2020-05-31 04:58:40','2020-05-31 04:58:40','-117.1933038','32.7338006','-112.0740373','33.4483771');
/*!40000 ALTER TABLE `alerts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `billing_history`
--

DROP TABLE IF EXISTS `billing_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `billing_history` (
  `history_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `paypal_agrement_id` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`history_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `billing_history`
--

LOCK TABLES `billing_history` WRITE;
/*!40000 ALTER TABLE `billing_history` DISABLE KEYS */;
INSERT INTO `billing_history` VALUES (1,1,1,1,'2019-06-04 11:33:17','2019-06-04 11:33:17',''),(2,2,1,1,'2020-06-01 01:04:25','2020-06-01 01:04:25','');
/*!40000 ALTER TABLE `billing_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `billing_package_types`
--

DROP TABLE IF EXISTS `billing_package_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `billing_package_types` (
  `int` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`int`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `billing_package_types`
--

LOCK TABLES `billing_package_types` WRITE;
/*!40000 ALTER TABLE `billing_package_types` DISABLE KEYS */;
INSERT INTO `billing_package_types` VALUES (1,'Trial'),(2,'Paid');
/*!40000 ALTER TABLE `billing_package_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `billing_packages`
--

DROP TABLE IF EXISTS `billing_packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `billing_packages` (
  `package_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `custom` tinyint(1) NOT NULL DEFAULT '0',
  `custom_user_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `featured` int(11) NOT NULL,
  `flight_price` int(11) NOT NULL,
  `discount` decimal(5,2) DEFAULT '0.00',
  `billing_interval` int(11) DEFAULT '30',
  PRIMARY KEY (`package_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `billing_packages`
--

LOCK TABLES `billing_packages` WRITE;
/*!40000 ALTER TABLE `billing_packages` DISABLE KEYS */;
INSERT INTO `billing_packages` VALUES (1,'Hobo Tier',0,0,0,1,'2019-06-18 02:27:03','2019-06-18 02:27:03','<ul><li>$199 per month membership fee</li><li>Details will be provided in a description field ....</li></ul>',1,0,0,0.00,7),(2,'Founder Tier',5000,0,0,1,'2019-06-04 09:21:21','2019-06-04 09:21:21','<ul><li>$5000 per month membership fee</li><li>Details will be provided in a description field ....</li></ul>',2,0,0,0.50,365);
/*!40000 ALTER TABLE `billing_packages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookings` (
  `booking_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `flight_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `time_requested` datetime NOT NULL,
  `time_approved` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `departure_time_start` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `departure_time_end` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `departure_time_final` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tail_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_id` int(11) NOT NULL,
  `fbo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `arriving_date_time` datetime NOT NULL,
  `passengers_count` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `return_flight` int(11) NOT NULL,
  PRIMARY KEY (`booking_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (1,1,1,0,'2019-06-04 09:21:24','0000-00-00 00:00:00','2019-06-04 09:21:24','2019-06-04 09:21:24','09:21','11:21','','','833',0,'','0000-00-00 00:00:00',1,0,0,0),(2,1,2,0,'2019-06-04 09:21:24','0000-00-00 00:00:00','2019-06-04 09:21:24','2019-06-04 09:21:24','09:21','15:21','','','929',0,'','0000-00-00 00:00:00',1,0,0,0),(3,1,3,0,'2019-06-04 09:21:24','0000-00-00 00:00:00','2019-06-04 09:21:24','2019-06-04 09:21:24','09:21','14:21','','','1692',0,'','0000-00-00 00:00:00',1,0,0,0),(4,1,4,0,'2019-06-04 09:21:24','0000-00-00 00:00:00','2019-06-04 09:21:24','2019-06-04 09:21:24','09:21','11:21','','','1017',0,'','0000-00-00 00:00:00',1,0,0,0),(5,1,5,0,'2019-06-04 09:21:24','0000-00-00 00:00:00','2019-06-04 09:21:24','2019-06-04 09:21:24','09:21','14:21','','','622',0,'','0000-00-00 00:00:00',1,0,0,0),(6,1,3,0,'2019-06-18 02:27:06','0000-00-00 00:00:00','2019-06-18 02:27:06','2019-06-18 02:27:06','02:27','07:27','','','1692',0,'','0000-00-00 00:00:00',1,0,0,0),(7,1,4,0,'2019-06-18 02:27:06','0000-00-00 00:00:00','2019-06-18 02:27:06','2019-06-18 02:27:06','02:27','07:27','','','1017',0,'','0000-00-00 00:00:00',1,0,0,0),(8,1,6,0,'2019-06-18 02:27:06','0000-00-00 00:00:00','2019-06-18 02:27:06','2019-06-18 02:27:06','02:27','08:27','','','1837',0,'','0000-00-00 00:00:00',1,0,0,0),(9,1,7,0,'2019-06-18 02:27:06','0000-00-00 00:00:00','2019-06-18 02:27:06','2019-06-18 02:27:06','02:27','04:27','','','1094',0,'','0000-00-00 00:00:00',1,0,0,0),(10,1,8,0,'2019-06-18 02:27:06','0000-00-00 00:00:00','2019-06-18 02:27:06','2019-06-18 02:27:06','02:27','06:27','','','662',0,'','0000-00-00 00:00:00',1,0,0,0);
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings_payments`
--

DROP TABLE IF EXISTS `bookings_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookings_payments` (
  `booking_payment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`booking_payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings_payments`
--

LOCK TABLES `bookings_payments` WRITE;
/*!40000 ALTER TABLE `bookings_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `bookings_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings_pending`
--

DROP TABLE IF EXISTS `bookings_pending`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookings_pending` (
  `booking_pending_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `flight_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `departure_start` datetime NOT NULL,
  `departure_end` datetime NOT NULL,
  `passengers` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`booking_pending_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings_pending`
--

LOCK TABLES `bookings_pending` WRITE;
/*!40000 ALTER TABLE `bookings_pending` DISABLE KEYS */;
INSERT INTO `bookings_pending` VALUES (1,1,3,2,'2019-06-14 15:38:19','2019-06-14 15:38:21','2019-06-14 09:26:00','2019-06-14 13:05:00','[{\"first_name\":\"23\",\"last_name\":\"23\",\"weight\":\"323\",\"birth_date\":\"01\\/01\\/2019\"}]'),(2,1,3,1,'2019-06-14 15:38:21','2019-06-14 15:38:21','2019-06-14 09:26:00','2019-06-14 13:05:00','[{\"first_name\":\"23\",\"last_name\":\"23\",\"weight\":\"323\",\"birth_date\":\"01\\/01\\/2019\"}]');
/*!40000 ALTER TABLE `bookings_pending` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `event_image` varchar(255) DEFAULT NULL,
  `event_image_id` int(11) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `seats` int(11) DEFAULT NULL,
  `event_at` timestamp NULL DEFAULT NULL,
  `event_ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,'Test','Visit us today at our new office.','https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcRR9gjbb1IvWEjU1BtGTSkWPWLFWbpWCpZUZ6Djme17EQFmCVHY&usqp=CAU',NULL,'Test',16,'2020-05-31 21:21:09',NULL,NULL,NULL),(2,'Test 3','We are finally moved in.','https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcRR9gjbb1IvWEjU1BtGTSkWPWLFWbpWCpZUZ6Djme17EQFmCVHY&usqp=CAU',NULL,'Test2',32,'2020-05-31 21:21:09',NULL,NULL,NULL),(3,'Test4','Test',NULL,NULL,'Test3',64,'2020-05-31 21:21:09',NULL,NULL,NULL),(5,'test',NULL,NULL,NULL,'Test4',128,NULL,NULL,'2020-06-04 04:21:05','2020-06-04 04:21:05'),(26,'sadgsdag','Test',NULL,5,'sadgsdg',16,NULL,NULL,'2020-06-05 05:55:26','2020-06-05 05:55:30'),(27,'radsgsda','asdgasdgsadg',NULL,NULL,'sdagsdg',16,'2020-10-03 10:35:00','2020-10-03 14:40:00','2020-06-05 06:02:11','2020-06-05 06:02:11'),(28,'radsgsda','asdgasdgsadg',NULL,6,'sdagsdg',16,'2020-10-03 10:35:00','2020-10-03 14:40:00','2020-06-05 06:02:16','2020-06-05 06:02:22');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events_images`
--

DROP TABLE IF EXISTS `events_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events_images` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `default` tinyint(4) DEFAULT NULL,
  `active` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`image_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events_images`
--

LOCK TABLES `events_images` WRITE;
/*!40000 ALTER TABLE `events_images` DISABLE KEYS */;
INSERT INTO `events_images` VALUES (1,24,'100699809-10207814830526259-2905245861904646144-n.jpg',NULL,0,3,'2020-06-05 01:00:19','2020-06-05 01:00:19'),(2,24,'100699809-10207814830526259-2905245861904646144-n.jpg',NULL,0,3,'2020-06-05 01:01:36','2020-06-05 01:01:36'),(3,24,'100699809-10207814830526259-2905245861904646144-n.jpg','data/images/aircrafts/eccbc87e4b5ce2fe28308fd9f2a7baf3/',0,1,'2020-06-05 01:02:15','2020-06-05 01:02:15'),(4,24,'100699809-10207814830526259-2905245861904646144-n.jpg','data/images/events/a87ff679a2f3e71d9181a67b7542122c/',0,1,'2020-06-05 01:02:31','2020-06-05 01:02:31'),(5,26,'images.jpg','data/images/events/e4da3b7fbbce2345d7772b0674a318d5/',0,1,'2020-06-05 05:55:30','2020-06-05 05:55:30'),(6,28,'lavender-flowers-super-912501430760203.jpg','data/images/events/1679091c5a880faf6fb5e6087eb1b2dc/',0,1,'2020-06-05 06:02:22','2020-06-05 06:02:22');
/*!40000 ALTER TABLE `events_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8_unicode_ci NOT NULL,
  `queue` text COLLATE utf8_unicode_ci NOT NULL,
  `payload` text COLLATE utf8_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `flight_passengers`
--

DROP TABLE IF EXISTS `flight_passengers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `flight_passengers` (
  `passenger_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `flight_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `birth_date` date NOT NULL,
  `weight` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`passenger_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flight_passengers`
--

LOCK TABLES `flight_passengers` WRITE;
/*!40000 ALTER TABLE `flight_passengers` DISABLE KEYS */;
INSERT INTO `flight_passengers` VALUES (1,1,1,0,0,'2019-06-04 09:21:24','2019-06-04 09:21:24','HoboAdmin','Admin','1973-08-22','107'),(2,2,2,0,0,'2019-06-04 09:21:24','2019-06-04 09:21:24','HoboAdmin','Admin','1984-01-14','123'),(3,3,3,0,0,'2019-06-04 09:21:24','2019-06-04 09:21:24','HoboAdmin','Admin','1976-08-10','85'),(4,4,4,0,0,'2019-06-04 09:21:24','2019-06-04 09:21:24','HoboAdmin','Admin','1971-04-03','150'),(5,5,5,0,0,'2019-06-04 09:21:24','2019-06-04 09:21:24','HoboAdmin','Admin','1971-03-06','119'),(6,3,6,0,0,'2019-06-18 02:27:06','2019-06-18 02:27:06','HoboAdmin','Admin','1992-07-27','135'),(7,4,7,0,0,'2019-06-18 02:27:06','2019-06-18 02:27:06','HoboAdmin','Admin','1969-11-09','88'),(8,6,8,0,0,'2019-06-18 02:27:06','2019-06-18 02:27:06','HoboAdmin','Admin','1980-08-22','110'),(9,7,9,0,0,'2019-06-18 02:27:06','2019-06-18 02:27:06','HoboAdmin','Admin','1981-07-20','135'),(10,8,10,0,0,'2019-06-18 02:27:06','2019-06-18 02:27:06','HoboAdmin','Admin','1968-11-11','150');
/*!40000 ALTER TABLE `flight_passengers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `flights`
--

DROP TABLE IF EXISTS `flights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `flights` (
  `flight_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `flight_identification` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` double DEFAULT NULL,
  `seats` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `flight_start` datetime NOT NULL,
  `flight_end` datetime NOT NULL,
  `flight_time` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `aircraft_id` int(11) NOT NULL,
  `aircraft_image_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `origin_location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `destination_location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `origin_lon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `origin_lat` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `destination_lat` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `destination_lon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `origin_airport_iata` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `destination_airport_iata` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `origin_airport_info` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `destination_airport_info` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `operator_id` int(11) NOT NULL,
  PRIMARY KEY (`flight_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1608 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flights`
--

LOCK TABLES `flights` WRITE;
/*!40000 ALTER TABLE `flights` DISABLE KEYS */;
INSERT INTO `flights` VALUES (1579,'',1500,'8','2020-05-29 10:00:00','2020-05-29 20:00:00','10',11,11,0,4,'','2020-05-18 20:43:13','2020-05-29 20:41:17','Farmington, NM','Scottsdale, AZ','-108.229944','36.74125','33.622889','-111.910528','FMN','SCF','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"FMN\",\"icao\":\"KFMN\",\"latitude\":\"36.74125\",\"longitude\":\"-108.229944\",\"location\":\"Farmington, NM\",\"name\":\"Four Corners Regional Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}',0,1),(1580,'',5000,'12','2020-06-01 12:00:00','2020-06-01 19:00:00','7',15,13,0,1,'','2020-05-18 20:43:56','2020-05-18 20:43:56','Van Nuys, CA','Scottsdale, AZ','-118.489972','34.209806','33.622889','-111.910528','VNY','SCF','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"VNY\",\"icao\":\"KVNY\",\"latitude\":\"34.209806\",\"longitude\":\"-118.489972\",\"location\":\"Van Nuys, CA\",\"name\":\"Van Nuys Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}',0,1),(1581,'',4900,'6','2020-06-05 12:00:00','2020-06-05 19:00:00','7',20,16,0,1,'','2020-05-18 20:44:41','2020-05-18 20:44:41','Coeur D\'alene, ID','Scottsdale, AZ','-116.819583','47.774306','33.622889','-111.910528','COE','SCF','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"COE\",\"icao\":\"KCOE\",\"latitude\":\"47.774306\",\"longitude\":\"-116.819583\",\"location\":\"Coeur D\'alene, ID\",\"name\":\"Coeur D\'alene - Pappy Boyington Field Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}',0,1),(1582,'',4900,'8','2020-05-22 09:00:00','2020-05-22 19:00:00','10',16,14,0,4,'','2020-05-18 20:45:20','2020-05-22 23:36:54','Eagle, CO','Scottsdale, AZ','-106.915944','39.64275','33.622889','-111.910528','EGE','SCF','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"EGE\",\"icao\":\"KEGE\",\"latitude\":\"39.64275\",\"longitude\":\"-106.915944\",\"location\":\"Eagle, CO\",\"name\":\"Eagle County Regional Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}',0,1),(1583,'',2900,'6','2020-05-22 12:00:00','2020-05-22 20:00:00','8',20,16,0,4,'','2020-05-18 20:45:51','2020-05-22 23:36:54','Santa Rosa, CA','Scottsdale, AZ','-122.812889','38.509','33.622889','-111.910528','STS','SCF','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"STS\",\"icao\":\"KSTS\",\"latitude\":\"38.509\",\"longitude\":\"-122.812889\",\"location\":\"Santa Rosa, CA\",\"name\":\"Charles M. Schulz - Sonoma County Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}',0,1),(1584,'',900,'6','2020-05-19 07:00:00','2020-05-19 19:00:00','12',20,16,0,4,'','2020-05-18 20:46:48','2020-05-19 22:55:08','Denver, CO','Scottsdale, AZ','-104.849306','39.570111','33.622889','-111.910528','APA','SCF','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"APA\",\"icao\":\"KAPA\",\"latitude\":\"39.570111\",\"longitude\":\"-104.849306\",\"location\":\"Denver, CO\",\"name\":\"Centennial Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}',0,1),(1585,'',3900,'8','2020-05-21 09:00:00','2020-05-21 19:00:00','10',3,3,0,4,'','2020-05-18 20:47:47','2020-05-21 22:48:22','Denver, CO','Scottsdale, AZ','-104.849306','39.570111','33.622889','-111.910528','APA','SCF','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"APA\",\"icao\":\"KAPA\",\"latitude\":\"39.570111\",\"longitude\":\"-104.849306\",\"location\":\"Denver, CO\",\"name\":\"Centennial Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}',0,1),(1586,'',999,'6','2020-05-21 19:00:00','2020-05-21 20:00:00','1',20,16,0,4,'','2020-05-21 04:08:23','2020-05-22 01:52:26','Santa Ana, CA','Scottsdale, AZ','-117.868222','33.675667','33.622889','-111.910528','SNA','SCF','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SNA\",\"icao\":\"KSNA\",\"latitude\":\"33.675667\",\"longitude\":\"-117.868222\",\"location\":\"Santa Ana, CA\",\"name\":\"John Wayne Airport-orange County Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}',0,1),(1587,'',999,'6','2020-05-21 19:00:00','2020-05-21 20:00:00','1',20,16,0,4,'','2020-05-21 04:09:14','2020-05-22 01:52:26','Denver, CO','Scottsdale, AZ','-104.849306','39.570111','33.622889','-111.910528','APA','SCF','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"APA\",\"icao\":\"KAPA\",\"latitude\":\"39.570111\",\"longitude\":\"-104.849306\",\"location\":\"Denver, CO\",\"name\":\"Centennial Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}',0,1),(1588,'',1999,'6','2020-05-24 07:00:00','2020-05-24 08:00:00','1',10,10,0,4,'','2020-05-21 04:10:05','2020-05-27 01:29:23','Omaha, NE','Scottsdale, AZ','-95.894056','41.303167','33.622889','-111.910528','OMA','SCF','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"OMA\",\"icao\":\"KOMA\",\"latitude\":\"41.303167\",\"longitude\":\"-95.894056\",\"location\":\"Omaha, NE\",\"name\":\"Eppley Airfield Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}',0,1),(1589,'',7900,'8','2020-05-24 08:00:00','2020-05-24 19:00:00','11',16,14,0,4,'','2020-05-21 22:50:57','2020-05-27 01:29:23','Scottsdale, AZ','Coeur D\'alene, ID','-111.910528','33.622889','47.774306','-116.819583','SCF','COE','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"COE\",\"icao\":\"KCOE\",\"latitude\":\"47.774306\",\"longitude\":\"-116.819583\",\"location\":\"Coeur D\'alene, ID\",\"name\":\"Coeur D\'alene - Pappy Boyington Field Airport\"}',0,1),(1590,'',3999,'8','2020-05-23 17:00:00','2020-05-23 20:00:00','3',3,3,0,4,'','2020-05-22 01:52:25','2020-05-27 01:29:23','Vancouver, British Columbia','Scottsdale, AZ','-123.184444','49.193889','33.622889','-111.910528','YVR','SCF','{\"country\":\"Canada\",\"country_code\":\"CA\",\"iata\":\"YVR\",\"icao\":\"CYVR\",\"latitude\":\"49.193889\",\"longitude\":\"-123.184444\",\"location\":\"Vancouver, British Columbia\",\"name\":\"Vancouver International Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}',0,1),(1591,'',1999,'6','2020-05-29 09:00:00','2020-05-29 11:00:00','2',20,16,0,4,'','2020-05-22 02:07:36','2020-05-29 20:41:17','Scottsdale, AZ','Monterey, CA','-111.910528','33.622889','36.587','-121.842944','SCF','MRY','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"MRY\",\"icao\":\"KMRY\",\"latitude\":\"36.587\",\"longitude\":\"-121.842944\",\"location\":\"Monterey, CA\",\"name\":\"Monterey Regional Airport\"}',0,1),(1592,'',1999,'6','2020-05-29 06:00:00','2020-05-29 09:00:00','3.0',20,16,0,4,'','2020-05-22 23:36:54','2020-05-29 20:41:17','Scottsdale, AZ','Carlsbad, CA','-111.910528','33.622889','33.12825','-117.280083','SCF','CLD','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"CLD\",\"icao\":\"KCRQ\",\"latitude\":\"33.12825\",\"longitude\":\"-117.280083\",\"location\":\"Carlsbad, CA\",\"name\":\"Mc Clellan-palomar Airport\"}',0,1),(1593,'',1999,'6','2020-06-03 14:00:00','2020-06-03 20:00:00','6',20,16,0,1,'','2020-05-22 23:37:24','2020-05-22 23:37:24','Carlsbad, CA','Scottsdale, AZ','-117.280083','33.12825','33.622889','-111.910528','CLD','SCF','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"CLD\",\"icao\":\"KCRQ\",\"latitude\":\"33.12825\",\"longitude\":\"-117.280083\",\"location\":\"Carlsbad, CA\",\"name\":\"Mc Clellan-palomar Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}',0,1),(1594,'',999,'6','2020-06-02 15:00:00','2020-06-02 18:00:00','3',20,16,0,1,'','2020-05-22 23:38:14','2020-05-22 23:38:14','Fort Collins/loveland, CO','Scottsdale, AZ','-105.011336','40.451827','33.622889','-111.910528','FNL','SCF','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"FNL\",\"icao\":\"KFNL\",\"latitude\":\"40.451827\",\"longitude\":\"-105.011336\",\"location\":\"Fort Collins\\/loveland, CO\",\"name\":\"Fort Collins-loveland Municipal Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}',0,1),(1595,'',299,'6','2020-05-28 05:00:00','2020-05-28 06:15:00','1',20,16,0,4,'','2020-05-27 01:31:58','2020-05-28 22:55:27','Scottsdale, AZ','Tucson, AZ','-111.910528','33.622889','32.116083','-110.941028','SCF','TUS','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"TUS\",\"icao\":\"KTUS\",\"latitude\":\"32.116083\",\"longitude\":\"-110.941028\",\"location\":\"Tucson, AZ\",\"name\":\"Tucson International Airport\"}',0,1),(1596,'',1599,'6','2020-06-08 16:00:00','2020-06-08 17:00:00','1',20,16,0,1,'','2020-05-27 01:36:49','2020-05-27 01:36:49','Eagle, CO','Scottsdale, AZ','-106.915944','39.64275','33.622889','-111.910528','EGE','SCF','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"EGE\",\"icao\":\"KEGE\",\"latitude\":\"39.64275\",\"longitude\":\"-106.915944\",\"location\":\"Eagle, CO\",\"name\":\"Eagle County Regional Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}',0,1),(1597,'',3900,'8','2020-05-27 12:00:00','2020-05-27 19:00:00','7',16,14,0,4,'','2020-05-27 01:40:28','2020-05-27 21:28:17','San Diego, CA','Scottsdale, AZ','-117.189667','32.733556','33.622889','-111.910528','SAN','SCF','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SAN\",\"icao\":\"KSAN\",\"latitude\":\"32.733556\",\"longitude\":\"-117.189667\",\"location\":\"San Diego, CA\",\"name\":\"San Diego International Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}',0,1),(1598,'',900,'8','2020-06-05 12:00:00','2020-06-05 19:00:00','7',3,3,0,1,'','2020-05-27 01:42:12','2020-05-27 01:42:12','Flagstaff, AZ','Scottsdale, AZ','-111.66925','35.140306','33.622889','-111.910528','FLG','SCF','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"FLG\",\"icao\":\"KFLG\",\"latitude\":\"35.140306\",\"longitude\":\"-111.66925\",\"location\":\"Flagstaff, AZ\",\"name\":\"Flagstaff Pulliam Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}',0,1),(1599,'',2500,'6','2020-06-04 08:00:00','2020-06-04 19:00:00','11',20,16,0,1,'','2020-05-27 21:28:17','2020-05-27 21:28:17','Scottsdale, AZ','Santa Ana, CA','-111.910528','33.622889','33.675667','-117.868222','SCF','SNA','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SNA\",\"icao\":\"KSNA\",\"latitude\":\"33.675667\",\"longitude\":\"-117.868222\",\"location\":\"Santa Ana, CA\",\"name\":\"John Wayne Airport-orange County Airport\"}',0,1),(1600,'',1999,'6','2020-05-29 08:00:00','2020-05-29 19:00:00','11',20,16,0,4,'','2020-05-27 21:30:44','2020-05-29 20:41:17','Jackson, WY','Scottsdale, AZ','-110.73775','43.607333','33.622889','-111.910528','JAC','SCF','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"JAC\",\"icao\":\"KJAC\",\"latitude\":\"43.607333\",\"longitude\":\"-110.73775\",\"location\":\"Jackson, WY\",\"name\":\"Jackson Hole Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}',0,1),(1601,'',1250,'8','2020-05-30 07:00:00','2020-05-30 08:00:00','1',10,10,0,4,'','2020-05-27 21:56:27','2020-05-31 04:47:45','Scottsdale, AZ','Las Vegas, NV','-111.910528','33.622889','35.972861','-115.134444','SCF','HND','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"HND\",\"icao\":\"KHND\",\"latitude\":\"35.972861\",\"longitude\":\"-115.134444\",\"location\":\"Las Vegas, NV\",\"name\":\"Henderson Executive Airport\"}',0,1),(1602,'',999,'6','2020-06-03 11:00:00','2020-06-03 14:00:00','3',20,16,0,1,'','2020-05-29 20:41:40','2020-05-29 20:41:40','Montrose, CO','Scottsdale, AZ','-107.89425','38.509806','33.622889','-111.910528','MTJ','SCF','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"MTJ\",\"icao\":\"KMTJ\",\"latitude\":\"38.509806\",\"longitude\":\"-107.89425\",\"location\":\"Montrose, CO\",\"name\":\"Montrose Regional Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}',0,1),(1603,'',999,'6','2020-05-31 12:00:00','2020-05-31 15:00:00','3',20,16,0,5,'','2020-05-29 20:42:09','2020-05-31 04:47:50','Scottsdale, AZ','Carlsbad, CA','-111.910528','33.622889','33.12825','-117.280083','SCF','CLD','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"CLD\",\"icao\":\"KCRQ\",\"latitude\":\"33.12825\",\"longitude\":\"-117.280083\",\"location\":\"Carlsbad, CA\",\"name\":\"Mc Clellan-palomar Airport\"}',0,1),(1604,'',1900,'6','2020-05-31 08:00:00','2020-05-31 19:00:00','11',20,16,0,1,'','2020-05-31 05:00:38','2020-05-31 05:00:38','Santa Ana, CA','Scottsdale, AZ','-117.868222','33.675667','33.622889','-111.910528','SNA','SCF','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SNA\",\"icao\":\"KSNA\",\"latitude\":\"33.675667\",\"longitude\":\"-117.868222\",\"location\":\"Santa Ana, CA\",\"name\":\"John Wayne Airport-orange County Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}',0,1),(1605,'',900,'6','2020-05-31 08:00:00','2020-05-31 13:00:00','5',3,3,0,1,'','2020-05-31 05:01:26','2020-05-31 05:01:26','Scottsdale, AZ','Flagstaff, AZ','-111.910528','33.622889','35.140306','-111.66925','SCF','FLG','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"FLG\",\"icao\":\"KFLG\",\"latitude\":\"35.140306\",\"longitude\":\"-111.66925\",\"location\":\"Flagstaff, AZ\",\"name\":\"Flagstaff Pulliam Airport\"}',0,1),(1606,'',1599,'6','2020-06-12 12:00:00','2020-06-12 19:00:00','7',20,16,0,1,'','2020-05-31 05:23:34','2020-05-31 05:23:34','Jackson, WY','Scottsdale, AZ','-110.73775','43.607333','33.622889','-111.910528','JAC','SCF','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"JAC\",\"icao\":\"KJAC\",\"latitude\":\"43.607333\",\"longitude\":\"-110.73775\",\"location\":\"Jackson, WY\",\"name\":\"Jackson Hole Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":\"33.622889\",\"longitude\":\"-111.910528\",\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}',0,1),(1607,'',997,'12','2018-06-04 17:32:00','2018-06-04 18:10:00','0',10,10,0,1,'','2020-06-05 00:34:58','2020-06-05 00:34:58','Scottsdale, AZ','Scottsdale, AZ','-111.910528','33.622889','33.622889','-111.910528','SCF','SCF','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":33.622889,\"longitude\":-111.910528,\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}','{\"country\":\"United States\",\"country_code\":\"US\",\"iata\":\"SCF\",\"icao\":\"KSDL\",\"latitude\":33.622889,\"longitude\":-111.910528,\"location\":\"Scottsdale, AZ\",\"name\":\"Scottsdale Airport\"}',0,1);
/*!40000 ALTER TABLE `flights` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `installations`
--

DROP TABLE IF EXISTS `installations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `installations` (
  `installation_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `ios_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `device_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `send_notifications` int(11) NOT NULL DEFAULT '1',
  `status` int(11) NOT NULL DEFAULT '1',
  `hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`installation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `installations`
--

LOCK TABLES `installations` WRITE;
/*!40000 ALTER TABLE `installations` DISABLE KEYS */;
/*!40000 ALTER TABLE `installations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payload` text COLLATE utf8_unicode_ci NOT NULL,
  `attempts` tinyint(1) NOT NULL,
  `reserved` tinyint(1) NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_reserved_reserved_at_index` (`queue`,`reserved`,`reserved_at`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (1,'default','{\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"data\":{\"command\":\"O:22:\\\"App\\\\Jobs\\\\FlightsAlerts\\\":4:{s:7:\\\"\\u0000*\\u0000data\\\";a:12:{s:8:\\\"alert_id\\\";i:1;s:7:\\\"user_id\\\";i:1;s:6:\\\"origin\\\";s:81:\\\"San Diego International Airport (SAN), 3225 N Harbor Dr, San Diego, CA 92101, USA\\\";s:11:\\\"destination\\\";s:16:\\\"Phoenix, AZ, USA\\\";s:6:\\\"active\\\";i:1;s:10:\\\"created_at\\\";s:19:\\\"2020-05-31 04:58:40\\\";s:10:\\\"updated_at\\\";s:19:\\\"2020-05-31 04:58:40\\\";s:16:\\\"origin_longitude\\\";s:12:\\\"-117.1933038\\\";s:15:\\\"origin_latitude\\\";s:10:\\\"32.7338006\\\";s:21:\\\"destination_longitude\\\";s:12:\\\"-112.0740373\\\";s:20:\\\"destination_latitude\\\";s:10:\\\"33.4483771\\\";s:9:\\\"flight_id\\\";i:1607;}s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;}\"}}',0,0,NULL,1591317298,1591317298);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES ('2016_03_15_115404_create_aircrafts_images_table',1),('2016_03_15_115404_create_aircrafts_table',1),('2016_03_15_115404_create_airports_table',1),('2016_03_15_115404_create_alerts_table',1),('2016_03_15_115404_create_billing_history_table',1),('2016_03_15_115404_create_billing_packages_table',1),('2016_03_15_115404_create_bookings_payments_table',1),('2016_03_15_115404_create_bookings_table',1),('2016_03_15_115404_create_failed_jobs_table',1),('2016_03_15_115404_create_flight_passengers_table',1),('2016_03_15_115404_create_flights_table',1),('2016_03_15_115404_create_installations_table',1),('2016_03_15_115404_create_jobs_table',1),('2016_03_15_115404_create_notes_table',1),('2016_03_15_115404_create_notifications_relations_table',1),('2016_03_15_115404_create_notifications_table',1),('2016_03_15_115404_create_pack_table',1),('2016_03_15_115404_create_pack_user_rel_table',1),('2016_03_15_115404_create_payments_table',1),('2016_03_15_115404_create_promo_table',1),('2016_03_15_115404_create_users_fb_table',1),('2016_03_15_115404_create_users_password_resets_table',1),('2016_03_15_115404_create_users_table',1),('2016_03_15_115404_create_users_tokens_table',1),('2016_03_17_084530_UsersUpdate_addAvatar',1),('2016_03_22_074038_UserTokensUpdate_addIP_userAgent',1),('2016_03_23_092120_AirportsUpdate',1),('2016_03_23_092614_AirportsUpdate_addColumns',1),('2016_03_30_120527_FlightsUpdate_addType',1),('2016_03_31_121615_BookingsUpdate_addPassengersCount',1),('2016_04_01_074151_BookingsUpdate_addParentId',1),('2016_04_01_083541_BookingsUpdate_addTotalPrice',1),('2016_04_06_110958_addOperatorId',1),('2016_04_07_064257_BookingsAddReturnStatus',1),('2016_04_13_085119_BillingPackages_Upadate',1),('2016_04_14_123431_newBookingsPending',1),('2016_04_18_053506_BillingPackages_update_addType',1),('2016_04_18_123050_RemovePayments',1),('2016_04_20_060954_BillingPackages_add_featured',1),('2016_04_20_084652_BillingHistory_add_paypal_agrement_id',1),('2016_04_25_104505_BillingPackages_flight_price',1),('2018_07_27_124334_PasswordLoginAuth',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `new_table`
--

DROP TABLE IF EXISTS `new_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `new_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `new_table`
--

LOCK TABLES `new_table` WRITE;
/*!40000 ALTER TABLE `new_table` DISABLE KEYS */;
/*!40000 ALTER TABLE `new_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notes` (
  `note_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `note` text COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `parent_type` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`note_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notes`
--

LOCK TABLES `notes` WRITE;
/*!40000 ALTER TABLE `notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `notification_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sender_user_id` int(11) NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `date_readed` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` int(11) NOT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`notification_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications_relations`
--

DROP TABLE IF EXISTS `notifications_relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications_relations` (
  `notification_rel_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `notification_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `device_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`notification_rel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications_relations`
--

LOCK TABLES `notifications_relations` WRITE;
/*!40000 ALTER TABLE `notifications_relations` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications_relations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pack`
--

DROP TABLE IF EXISTS `pack`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pack` (
  `pack_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`pack_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pack`
--

LOCK TABLES `pack` WRITE;
/*!40000 ALTER TABLE `pack` DISABLE KEYS */;
INSERT INTO `pack` VALUES (1,1,1,'Hobo Squad','2019-06-04 11:33:17','2019-06-04 11:33:17');
/*!40000 ALTER TABLE `pack` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pack_user_rel`
--

DROP TABLE IF EXISTS `pack_user_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pack_user_rel` (
  `pack_user_rel_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `owner` tinyint(1) NOT NULL DEFAULT '0',
  `pack_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`pack_user_rel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pack_user_rel`
--

LOCK TABLES `pack_user_rel` WRITE;
/*!40000 ALTER TABLE `pack_user_rel` DISABLE KEYS */;
INSERT INTO `pack_user_rel` VALUES (1,1,1,1,1,'2019-06-04 11:33:17','2019-06-04 11:33:17');
/*!40000 ALTER TABLE `pack_user_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_login`
--

DROP TABLE IF EXISTS `password_login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_login` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_login`
--

LOCK TABLES `password_login` WRITE;
/*!40000 ALTER TABLE `password_login` DISABLE KEYS */;
INSERT INTO `password_login` VALUES (1,'d9b2e6e56baef51d45daf761c0b5ac3e','0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `password_login` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promo`
--

DROP TABLE IF EXISTS `promo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promo` (
  `promo_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `position` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`promo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promo`
--

LOCK TABLES `promo` WRITE;
/*!40000 ALTER TABLE `promo` DISABLE KEYS */;
/*!40000 ALTER TABLE `promo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `company` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '2',
  `role` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `confirmation_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook_id` bigint(20) unsigned DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pending_data` text COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timezone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_facebook_id_index` (`facebook_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'HoboAdmin','Admin',NULL,'admin@hobojet.com','$2y$10$C5ySYAklF6Xnv5YXGQlFo.MCCNGy/KeaehFSRScaXzz.cvbXk5FMS',1,'admin','web',NULL,NULL,'FkhFU1Q8D8t7aSlZSKK5oG8fKlzs37HnxMvGC8xAbKcKJpAKZFVawWWtfmk7','2019-06-04 09:21:21','2020-06-06 06:08:45','','','','','','','',''),(2,'Stephan','Shere',NULL,'sshere@exqsd.com','$2y$10$Nqh/wSgxS.P5UmvGgE4u/uoHMHz9UEQ/pvFA2PGWPJONhGfzzYmEq',2,'admin','web',NULL,NULL,NULL,'2020-06-01 01:04:25','2020-06-01 01:04:25','','','','','','','',''),(29,'Stephan','Shere2',NULL,'sshere1985@gmail.com','$2y$10$JiXPu/yL9H0bY4pZCYfzCuHSNusTFg2XG4feFprubKI/1A78rcvS2',2,'user',NULL,NULL,NULL,'Q8RaiSoRCGIQRyOWhAbYNK18nLGGFWwmfJIeBADioLRmJdxfVmGrKIXORYwE','2020-06-06 00:08:46','2020-06-06 00:14:19','','4805555555','','','','','',''),(30,'dagsdgdsg','asdgsadg',NULL,'asgdsgsdg@aol.com','$2y$10$/e5KKVnkVe8J.SBnCNuNbe6/XRf6cNLS2FYlVqdedU67baif5JsE6',2,'user',NULL,NULL,NULL,'XIKF28iYN4aH7aGA2JkvcR0ibBGVMlQJ7vlhdHdEcobAN0uKETj3WPFvajOp','2020-06-06 00:14:55','2020-06-06 00:16:42','','4805314564','','','','','',''),(31,'ddgasg','asdgsadg',NULL,'asdgsdag@aol.com','$2y$10$uxVCcUFrWrL6ofhe99D76OZB/JMssA5ByiUqVtwueRG6dECn5EYZ.',2,'user',NULL,NULL,NULL,'NVU4rF2cbqJiQr7UTpWiHyyYYR04fny5RDo11slqguV4RnbIpYmMfB7CgI8Z','2020-06-06 00:17:06','2020-06-06 00:20:21','','2354923095239059035','','','','','','');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_fb`
--

DROP TABLE IF EXISTS `users_fb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_fb` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `facebook_id` bigint(20) unsigned DEFAULT NULL,
  `token` text COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `users_fb_facebook_id_index` (`facebook_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_fb`
--

LOCK TABLES `users_fb` WRITE;
/*!40000 ALTER TABLE `users_fb` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_fb` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_password_resets`
--

DROP TABLE IF EXISTS `users_password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `users_password_resets_email_index` (`email`),
  KEY `users_password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_password_resets`
--

LOCK TABLES `users_password_resets` WRITE;
/*!40000 ALTER TABLE `users_password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_tokens`
--

DROP TABLE IF EXISTS `users_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_tokens` (
  `token_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_by` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'login',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ip_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`token_id`),
  UNIQUE KEY `users_tokens_token_unique` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_tokens`
--

LOCK TABLES `users_tokens` WRITE;
/*!40000 ALTER TABLE `users_tokens` DISABLE KEYS */;
INSERT INTO `users_tokens` VALUES (4,1,'0559f5b0-86fd-11e9-bfaf-f99297bfc3b8','web','login',1,'2019-06-04 19:14:48','2019-06-04 19:14:48','173.244.44.74','Mozilla/5.0 (Macintosh; Intel Mac OS X 10.14; rv:67.0) Gecko/20100101 Firefox/67.0'),(6,1,'40ce5ea0-87cb-11e9-94c3-0be3cf8c94ac','web','login',1,'2019-06-05 19:51:04','2019-06-05 19:51:04','24.251.224.125','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/11.1.2 Safari/605.1.15'),(7,1,'9eb7fa40-87cb-11e9-8421-3bdff7429da7','web','login',1,'2019-06-05 19:53:42','2019-06-05 19:53:42','71.238.24.37','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36'),(10,1,'83ee3340-91e0-11e9-a25e-af63f3c91a89','web','login',1,'2019-06-18 15:48:28','2019-06-18 15:48:28','147.75.92.244','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36'),(11,1,'effcc640-9988-11ea-bfeb-5d4a9e9e738a','web','login',1,'2020-05-19 04:26:40','2020-05-19 04:26:40','70.162.92.179','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36'),(12,1,'11c0fc60-9989-11ea-929c-4d6dad2247c5','web','login',1,'2020-05-19 04:27:37','2020-05-19 04:27:37','70.162.92.179','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36'),(13,1,'5b2b8360-998a-11ea-bd75-e72b890f3fda','web','login',1,'2020-05-19 04:36:50','2020-05-19 04:36:50','70.162.92.179','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36'),(14,1,'62da7d40-998a-11ea-b60c-fd0dd39d0732','web','login',1,'2020-05-19 04:37:03','2020-05-19 04:37:03','70.162.92.179','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36'),(15,1,'3100c340-998d-11ea-8580-3952d7553c9b','web','login',1,'2020-05-19 04:57:07','2020-05-19 04:57:07','70.162.92.179','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36'),(16,1,'09bcdac0-998e-11ea-8acd-138a0ea2e51a','web','login',1,'2020-05-19 05:03:11','2020-05-19 05:03:11','70.162.92.179','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36'),(17,1,'0c7ffe70-998e-11ea-8a1b-a96093ee0b44','web','login',1,'2020-05-19 05:03:16','2020-05-19 05:03:16','70.162.92.179','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36'),(18,1,'357eb5b0-998e-11ea-b9ba-ffdf88b420f3','web','login',1,'2020-05-19 05:04:25','2020-05-19 05:04:25','70.162.92.179','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36'),(19,1,'370c2870-998e-11ea-9978-05191de07248','web','login',1,'2020-05-19 05:04:27','2020-05-19 05:04:27','70.162.92.179','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36'),(20,1,'3a5d8050-998e-11ea-b140-29783c4087af','web','login',1,'2020-05-19 05:04:33','2020-05-19 05:04:33','70.162.92.179','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36'),(21,1,'7d317850-998e-11ea-b74f-7b7d6e928628','web','login',1,'2020-05-19 05:06:25','2020-05-19 05:06:25','70.162.92.179','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36'),(22,1,'f773b040-9c8f-11ea-ac1f-6b3b7d3d62fe','web','login',1,'2020-05-23 00:54:33','2020-05-23 00:54:33','72.216.170.9','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36'),(23,1,'f772ada0-9c8f-11ea-a5d9-2d3e817f2245','web','login',1,'2020-05-23 00:54:33','2020-05-23 00:54:33','72.216.170.9','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36'),(24,1,'fa192a20-9c8f-11ea-bd50-bbd9f4b20fb2','web','login',1,'2020-05-23 00:54:37','2020-05-23 00:54:37','72.216.170.9','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36'),(25,1,'e20fcae0-a2b4-11ea-9a74-8b122556be19','web','login',1,'2020-05-30 20:33:55','2020-05-30 20:33:55','72.216.170.9','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.61 Safari/537.36'),(29,1,'82c10130-a38a-11ea-ae19-dd67593d1a21','web','login',1,'2020-05-31 22:03:08','2020-05-31 22:03:08','72.216.170.9','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.61 Safari/537.36'),(30,1,'da823020-a38a-11ea-a829-137ec626e058','web','login',1,'2020-05-31 22:05:35','2020-05-31 22:05:35','72.216.170.9','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.61 Safari/537.36'),(31,1,'7c9deaa0-a38c-11ea-9e5e-8b610a00b86e','web','login',1,'2020-05-31 22:17:16','2020-05-31 22:17:16','72.216.170.9','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.61 Safari/537.36'),(32,1,'dba67980-a6c3-11ea-8332-9b34be61eec7','web','login',1,'2020-06-05 00:31:12','2020-06-05 00:31:12','70.190.131.166','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.61 Safari/537.36'),(36,1,'0279f8c0-a78e-11ea-ab17-715031868670','web','login',1,'2020-06-06 00:38:15','2020-06-06 00:38:15','70.190.131.166','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.61 Safari/537.36'),(38,1,'7ec11d80-a7e0-11ea-b31c-d122da5ce37b','web','login',1,'2020-06-06 10:28:42','2020-06-06 10:28:42','45.41.181.194','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.61 Safari/537.36'),(39,1,'8f2a19b0-a7f3-11ea-a7b6-db53aad18cc9','web','login',1,'2020-06-06 12:45:10','2020-06-06 12:45:10','45.41.181.194','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.61 Safari/537.36');
/*!40000 ALTER TABLE `users_tokens` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-06-07 18:25:53