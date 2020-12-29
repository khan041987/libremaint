-- MySQL dump 10.13  Distrib 5.7.32, for Linux (x86_64)
--
-- Host: localhost    Database: libremaint
-- ------------------------------------------------------
-- Server version	5.7.32-0ubuntu0.18.04.1

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
-- Table structure for table `assets`
--

DROP TABLE IF EXISTS `assets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assets` (
  `asset_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `grouped_asset_id` int(10) unsigned DEFAULT NULL,
  `entry_point` tinyint(1) unsigned DEFAULT NULL,
  `grouped_asset` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `asset_importance` tinyint(1) unsigned DEFAULT NULL,
  `asset_name_en` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `asset_parent_id` int(11) DEFAULT '0',
  `asset_category_id` int(10) unsigned DEFAULT NULL,
  `asset_location` smallint(5) unsigned DEFAULT NULL,
  `asset_subcategory_id` smallint(5) unsigned DEFAULT NULL,
  `asset_product_id` smallint(5) unsigned DEFAULT NULL,
  `asset_article` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `info_file_id1` smallint(6) unsigned DEFAULT NULL,
  `info_file_id2` smallint(6) unsigned DEFAULT NULL,
  `info_file_id3` smallint(6) unsigned DEFAULT NULL,
  `info_file_id4` smallint(6) unsigned DEFAULT NULL,
  `info_file_id5` smallint(6) unsigned DEFAULT NULL,
  `info_file_id6` smallint(6) unsigned DEFAULT NULL,
  `connection_id1` smallint(3) unsigned DEFAULT NULL,
  `connection_type1` smallint(3) unsigned DEFAULT NULL,
  `connection_id2` smallint(3) unsigned DEFAULT NULL,
  `connection_type2` smallint(3) unsigned DEFAULT NULL,
  `main_asset_category_id` smallint(3) unsigned DEFAULT NULL,
  `asset_note` varchar(500) COLLATE utf8_hungarian_ci DEFAULT NULL,
  `asset_note_conf` varchar(200) COLLATE utf8_hungarian_ci DEFAULT NULL,
  `assets_entry_users` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `assets_users` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `main_part` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`asset_id`),
  KEY `info_file_id1` (`info_file_id1`),
  KEY `info_file_id2` (`info_file_id2`),
  KEY `info_file_id3` (`info_file_id3`),
  KEY `info_file_id4` (`info_file_id4`),
  KEY `info_file_id5` (`info_file_id5`),
  KEY `info_file_id6` (`info_file_id6`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assets`
--

LOCK TABLES `assets` WRITE;
/*!40000 ALTER TABLE `assets` DISABLE KEYS */;
/*!40000 ALTER TABLE `assets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_name_en` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `category_parent_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_id_UNIQUE` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Electric motor',0),(2,NULL,1),(3,'Sensor',0),(4,'proximity',3),(5,'Roll',0),(6,'Bearing',0),(7,'deep groove ball',6),(8,'Pressure sensor',3),(9,'pressure switch',3),(10,'linear transducer',3),(11,'3 phase motor',1),(12,'dc',1),(13,'Linear actuator',0),(15,'Pneumatic',13),(16,'Cylindrical roller',6),(17,'Pin roller',6),(18,'Hydraulic',13),(19,'Coupling',0),(20,'Bolt, screw',0),(21,'Hexagon socket head cap',20),(22,'Hexagon head',20),(23,'Wheel',0),(24,'Pneumatic',0),(25,'valve',24),(26,'pressure regulator',24),(27,'fitting',24),(28,'Timing pulley',0),(29,'Belt',0),(30,'metric rubber timing belt',29),(31,'metric PU timing belt',29),(32,'imperial rubber timing belt',29),(33,'Taper lock bush',0),(34,'Bracket',0),(35,'bell housing',34),(36,'Lifter',0),(37,'Inverter',0),(38,'Pump',0),(39,'hydraulic',38),(40,'Filter',0),(41,'house',40),(42,'element',40),(43,'encoder',3),(44,'with gearbox',1),(45,'buffer ring',19),(46,'Gearbox',0),(47,'Seal',0),(48,'Rotary seal',47),(49,'Fan',0),(50,'Controller',0),(51,'water pump',38),(52,'Flow',3),(53,'safety chuck',34),(54,'o-ring',47),(55,'screw drive',13),(56,'System enclosure',0),(57,'Switch',0),(58,'Main switch',57),(59,'Air compressor',0),(60,'Refrigerator',0),(61,'engraved',5),(62,'chrome plaited',5),(63,'polymer coated',5),(64,'moiton sensor',3),(65,'HMI',0),(66,'photoelectric',3),(67,'repait kit',13),(68,'gas-steam-water',0),(69,'pressure valve',68),(70,'Chain',0),(71,'Electric part',0),(72,'variable resistor',71),(73,'Tank',0),(74,'pressure tank',73),(75,'angle',3),(76,'roller chain',70),(77,'PLC',50),(78,'Burner controller',50),(79,'transformer',71),(80,'Lighting fittings',0),(81,'other',47),(82,'operating unit',57),(83,'distance',3),(84,'fitting',68),(85,'holder',3),(86,'self aligning',6),(87,'Hydraulic',0),(88,'cylinder',87),(89,'level',73),(90,'adhesive pump',38),(91,'E27 socket',80),(92,'energy',70),(93,'shaft repair sleeve',47),(94,'V-belt',29),(95,'tube t5',80),(96,'solid state relay',57),(97,'linear',6),(98,'Cable',0),(99,'connector',98),(100,'Y bearing unit',6),(101,'E14 socket',80),(102,'connector',71),(103,'GR10q compact tube',80),(104,'pneumatic actuator',68),(105,'rotary joint',68),(106,'cylinder',24),(107,'accumulator',87),(108,'spiral',5),(109,'steel',5),(110,'silicon pump',38),(111,'flat belt',29),(112,'Angular contact ball',6),(113,'other',24),(114,'expansion shaft',5),(115,'force sensor',3),(116,'safety',57);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `connection_categories`
--

DROP TABLE IF EXISTS `connection_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `connection_categories` (
  `connection_category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `connection_category_en` varchar(45) NOT NULL,
  PRIMARY KEY (`connection_category_id`),
  UNIQUE KEY `connection_category_id_UNIQUE` (`connection_category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `connection_categories`
--

LOCK TABLES `connection_categories` WRITE;
/*!40000 ALTER TABLE `connection_categories` DISABLE KEYS */;
INSERT INTO `connection_categories` VALUES (1,'Roll'),(2,'pump'),(3,'light source'),(4,'electric connector'),(5,'elektric motor');
/*!40000 ALTER TABLE `connection_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `connections`
--

DROP TABLE IF EXISTS `connections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `connections` (
  `connection_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `connection_name_en` varchar(45) NOT NULL,
  `connection_type` tinyint(1) unsigned NOT NULL,
  `connection_category_id` smallint(3) unsigned NOT NULL,
  `connection_review_en` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`connection_id`),
  UNIQUE KEY `connection_id_UNIQUE` (`connection_id`),
  UNIQUE KEY `connection_name_UNIQUE` (`connection_name_en`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `connections`
--

LOCK TABLES `connections` WRITE;
/*!40000 ALTER TABLE `connections` DISABLE KEYS */;
/*!40000 ALTER TABLE `connections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `counter_values`
--

DROP TABLE IF EXISTS `counter_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `counter_values` (
  `counter_value_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `counter_value` mediumint(8) NOT NULL,
  `counter_value_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `counter_id` smallint(3) unsigned NOT NULL,
  `workorder_id` int(10) unsigned DEFAULT NULL,
  `workrequest_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`counter_value_id`),
  UNIQUE KEY `counter_value_id_UNIQUE` (`counter_value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `counter_values`
--

LOCK TABLES `counter_values` WRITE;
/*!40000 ALTER TABLE `counter_values` DISABLE KEYS */;
/*!40000 ALTER TABLE `counter_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `counters`
--

DROP TABLE IF EXISTS `counters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `counters` (
  `counter_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `main_asset_id` smallint(4) unsigned NOT NULL,
  `asset_id` smallint(4) unsigned NOT NULL,
  `counter_unit` varchar(5) NOT NULL,
  PRIMARY KEY (`counter_id`),
  UNIQUE KEY `counter_id_UNIQUE` (`counter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `counters`
--

LOCK TABLES `counters` WRITE;
/*!40000 ALTER TABLE `counters` DISABLE KEYS */;
/*!40000 ALTER TABLE `counters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `finished_workrequests`
--

DROP TABLE IF EXISTS `finished_workrequests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `finished_workrequests` (
  `finished_workrequest_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `workrequest_id` int(10) unsigned NOT NULL,
  `workorder_id` int(10) unsigned NOT NULL,
  `counter_value` int(10) unsigned DEFAULT NULL,
  `counter_id` int(10) unsigned DEFAULT NULL,
  `finish_time` datetime NOT NULL,
  PRIMARY KEY (`finished_workrequest_id`),
  UNIQUE KEY `finished_workrequest_id_UNIQUE` (`finished_workrequest_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `finished_workrequests`
--

LOCK TABLES `finished_workrequests` WRITE;
/*!40000 ALTER TABLE `finished_workrequests` DISABLE KEYS */;
/*!40000 ALTER TABLE `finished_workrequests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `info_files`
--

DROP TABLE IF EXISTS `info_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `info_files` (
  `info_file_id` int(11) NOT NULL AUTO_INCREMENT,
  `info_file_name` varchar(45) COLLATE utf8_hungarian_ci NOT NULL,
  `info_file_review_en` varchar(45) COLLATE utf8_hungarian_ci DEFAULT NULL,
  `req_user_level` tinyint(3) unsigned NOT NULL,
  `info_file_sha` varchar(255) COLLATE utf8_hungarian_ci NOT NULL,
  `uploaded_by` tinyint(3) unsigned DEFAULT NULL,
  `upload_time` datetime DEFAULT NULL,
  `confidential` bit(1) DEFAULT NULL,
  PRIMARY KEY (`info_file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `info_files`
--

LOCK TABLES `info_files` WRITE;
/*!40000 ALTER TABLE `info_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `info_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `iot_sensors`
--

DROP TABLE IF EXISTS `iot_sensors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iot_sensors` (
  `sensor_id` int(10) NOT NULL AUTO_INCREMENT,
  `asset_id` smallint(4) unsigned NOT NULL,
  `unit_id` tinyint(2) unsigned DEFAULT NULL,
  `max_sensor_value` float(6,2) DEFAULT NULL,
  `max_sensor_value_time` datetime DEFAULT NULL,
  `min_sensor_value` float(6,2) DEFAULT NULL,
  `min_sensor_value_time` datetime DEFAULT NULL,
  `main_asset_id` smallint(4) unsigned NOT NULL,
  PRIMARY KEY (`sensor_id`),
  UNIQUE KEY `sensor_id_UNIQUE` (`sensor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `iot_sensors`
--

LOCK TABLES `iot_sensors` WRITE;
/*!40000 ALTER TABLE `iot_sensors` DISABLE KEYS */;
/*!40000 ALTER TABLE `iot_sensors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `locations` (
  `location_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `location_parent_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `location_name_en` varchar(45) COLLATE utf8_hungarian_ci DEFAULT NULL,
  `info_file_id1` smallint(6) unsigned DEFAULT NULL,
  `set_as_stock` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`location_id`),
  UNIQUE KEY `location_id_UNIQUE` (`location_id`),
  KEY `info_file_id1` (`info_file_id1`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `main_asset_categories`
--

DROP TABLE IF EXISTS `main_asset_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `main_asset_categories` (
  `main_asset_category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `main_asset_category_en` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`main_asset_category_id`),
  UNIQUE KEY `asset_category_id_UNIQUE` (`main_asset_category_id`),
  UNIQUE KEY `asset_category_en_UNIQUE` (`main_asset_category_en`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `main_asset_categories`
--

LOCK TABLES `main_asset_categories` WRITE;
/*!40000 ALTER TABLE `main_asset_categories` DISABLE KEYS */;
INSERT INTO `main_asset_categories` VALUES (3,'Auxiliary machines'),(5,'Estates'),(2,'Lifting equipments'),(1,'Production machines'),(4,'Vehicles');
/*!40000 ALTER TABLE `main_asset_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `manufacturers`
--

DROP TABLE IF EXISTS `manufacturers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manufacturers` (
  `manufacturer_id` int(11) NOT NULL AUTO_INCREMENT,
  `manufacturer_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`manufacturer_id`),
  UNIQUE KEY `manufacturer_name_UNIQUE` (`manufacturer_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `manufacturers`
--

LOCK TABLES `manufacturers` WRITE;
/*!40000 ALTER TABLE `manufacturers` DISABLE KEYS */;
/*!40000 ALTER TABLE `manufacturers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `message_en` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_id` tinyint(2) unsigned DEFAULT NULL,
  PRIMARY KEY (`message_id`),
  UNIQUE KEY `message_id_UNIQUE` (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `main_asset_id` smallint(5) unsigned NOT NULL,
  `asset_id` smallint(5) unsigned NOT NULL,
  `user_id` tinyint(3) unsigned NOT NULL,
  `priority` tinyint(3) unsigned NOT NULL,
  `notification_status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `notification_short` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notification` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notification_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `notification_closing_time` datetime DEFAULT NULL,
  `notification_type` tinyint(1) NOT NULL,
  PRIMARY KEY (`notification_id`),
  UNIQUE KEY `demand_id_UNIQUE` (`notification_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operator_works`
--

DROP TABLE IF EXISTS `operator_works`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `operator_works` (
  `operator_work_id` int(11) NOT NULL AUTO_INCREMENT,
  `operator_work_time` datetime NOT NULL,
  `operator_work` varchar(350) DEFAULT NULL,
  `main_asset_id` smallint(3) unsigned NOT NULL,
  `asset_id` smallint(3) unsigned NOT NULL,
  `operator_user_id` smallint(3) unsigned NOT NULL,
  `workrequest_id` int(10) unsigned NOT NULL,
  `deleted` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`operator_work_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operator_works`
--

LOCK TABLES `operator_works` WRITE;
/*!40000 ALTER TABLE `operator_works` DISABLE KEYS */;
/*!40000 ALTER TABLE `operator_works` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partners`
--

DROP TABLE IF EXISTS `partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partners` (
  `partner_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `partner_name` varchar(45) NOT NULL,
  `partner_address` varchar(150) DEFAULT NULL,
  `contact1_surname` varchar(45) DEFAULT NULL,
  `contact1_firstname` varchar(45) DEFAULT NULL,
  `contact1_firstname_is_first` tinyint(1) unsigned DEFAULT NULL,
  `contact1_title` tinyint(1) DEFAULT NULL,
  `contact1_email` varchar(45) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `contact1_phone` varchar(15) DEFAULT NULL,
  `contact1_position` varchar(45) DEFAULT NULL,
  `partner_created` datetime NOT NULL,
  PRIMARY KEY (`partner_id`),
  UNIQUE KEY `partner_id_UNIQUE` (`partner_id`),
  UNIQUE KEY `partner_name_UNIQUE` (`partner_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partners`
--

LOCK TABLES `partners` WRITE;
/*!40000 ALTER TABLE `partners` DISABLE KEYS */;
/*!40000 ALTER TABLE `partners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` tinyint(3) unsigned NOT NULL,
  `subcategory_id` tinyint(3) unsigned DEFAULT '0',
  `product_type_en` varchar(45) NOT NULL,
  `product_properties_en` varchar(45) DEFAULT NULL,
  `quantity_unit` tinyint(2) unsigned NOT NULL,
  `manufacturer_id` tinyint(3) unsigned DEFAULT NULL,
  `info_file_id1` smallint(6) unsigned DEFAULT NULL,
  `product_stockable` tinyint(1) unsigned NOT NULL COMMENT '1 yes\n2 yes, but the prodct is uniq (only one can be at stock)\n3 no',
  `default_stock_location_id` smallint(3) unsigned DEFAULT NULL,
  `connection_id1` smallint(3) unsigned DEFAULT NULL,
  `connection_type1` smallint(3) unsigned DEFAULT NULL,
  `connection_id2` smallint(3) unsigned DEFAULT NULL,
  `connection_type2` smallint(3) unsigned DEFAULT NULL,
  `info_file_id2` smallint(6) unsigned DEFAULT NULL,
  `info_file_id3` smallint(6) unsigned DEFAULT NULL,
  `info_file_id4` smallint(6) unsigned DEFAULT NULL,
  `info_file_id5` smallint(6) unsigned DEFAULT NULL,
  `info_file_id6` smallint(6) unsigned DEFAULT NULL,
  `display` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`product_id`),
  KEY `info_file_id1` (`info_file_id1`),
  KEY `info_file_id2` (`info_file_id2`),
  KEY `info_file_id3` (`info_file_id3`),
  KEY `info_file_id4` (`info_file_id4`),
  KEY `info_file_id5` (`info_file_id5`),
  KEY `info_file_id6` (`info_file_id6`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `received_messages`
--

DROP TABLE IF EXISTS `received_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `received_messages` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `message_type` tinyint(2) unsigned NOT NULL,
  `received_message` smallint(4) unsigned NOT NULL,
  `sensor_id` smallint(3) unsigned NOT NULL,
  `message_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` smallint(3) unsigned NOT NULL,
  `user_id_who_checked` smallint(3) unsigned NOT NULL DEFAULT '0',
  `checking_time` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sensor_value` float(6,2) DEFAULT NULL,
  PRIMARY KEY (`message_id`),
  UNIQUE KEY `messages_id_UNIQUE` (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `received_messages`
--

LOCK TABLES `received_messages` WRITE;
/*!40000 ALTER TABLE `received_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `received_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock`
--

DROP TABLE IF EXISTS `stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock` (
  `stock_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` smallint(4) unsigned NOT NULL,
  `product_category_id` smallint(4) unsigned NOT NULL,
  `product_subcategory_id` smallint(4) NOT NULL,
  `stock_location_id` smallint(4) unsigned NOT NULL,
  `stock_location_asset_id` smallint(4) unsigned DEFAULT NULL,
  `stock_location_partner_id` smallint(4) unsigned DEFAULT NULL,
  `stock_quantity` float(8,2) unsigned NOT NULL,
  `stock_place` varchar(45) DEFAULT NULL,
  `item_created` datetime NOT NULL,
  `min_stock_quantity` float(8,2) unsigned DEFAULT '0.00',
  PRIMARY KEY (`stock_id`),
  UNIQUE KEY `stock_id_UNIQUE` (`stock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock`
--

LOCK TABLES `stock` WRITE;
/*!40000 ALTER TABLE `stock` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_movements`
--

DROP TABLE IF EXISTS `stock_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_movements` (
  `stock_movement_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `to_partner_id` smallint(3) unsigned DEFAULT '0',
  `workorder_id` smallint(6) unsigned DEFAULT '0',
  `stock_movement_quantity` float(7,2) NOT NULL,
  `product_id` smallint(6) unsigned NOT NULL,
  `to_asset_id` smallint(4) unsigned DEFAULT '0',
  `from_asset_id` smallint(4) unsigned DEFAULT NULL,
  `stock_movement_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `from_partner_id` smallint(3) unsigned DEFAULT '0',
  `from_stock_location_id` smallint(3) unsigned DEFAULT '0',
  `to_stock_location_id` smallint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`stock_movement_id`),
  UNIQUE KEY `stock_movements_id_UNIQUE` (`stock_movement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_movements`
--

LOCK TABLES `stock_movements` WRITE;
/*!40000 ALTER TABLE `stock_movements` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_movements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `telegram_messages`
--

DROP TABLE IF EXISTS `telegram_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `telegram_messages` (
  `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_id` smallint(3) unsigned NOT NULL,
  `sensor_id` smallint(4) unsigned NOT NULL,
  `sending_time` datetime DEFAULT NULL,
  `received_message` smallint(3) NOT NULL,
  `received_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sensor_value` float(6,2) DEFAULT NULL,
  `notification_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`message_id`),
  UNIQUE KEY `message_id_UNIQUE` (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `telegram_messages`
--

LOCK TABLES `telegram_messages` WRITE;
/*!40000 ALTER TABLE `telegram_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `telegram_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `units` (
  `unit_id` int(11) NOT NULL AUTO_INCREMENT,
  `unit_en` varchar(15) NOT NULL,
  `unit_hu` varchar(15) NOT NULL,
  `unit_datatype` varchar(5) NOT NULL,
  PRIMARY KEY (`unit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units`
--

LOCK TABLES `units` WRITE;
/*!40000 ALTER TABLE `units` DISABLE KEYS */;
INSERT INTO `units` VALUES (1,'pcs','db','int'),(2,'m','m','float'),(3,'kg','kg','float'),(4,'mm','mm','float'),(5,'h','óra','float'),(6,'°C','°C','float');
/*!40000 ALTER TABLE `units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_levels`
--

DROP TABLE IF EXISTS `user_levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_levels` (
  `user_level_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_level_en` varchar(45) CHARACTER SET utf8 NOT NULL,
  `user_level_hu` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`user_level_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_levels`
--

LOCK TABLES `user_levels` WRITE;
/*!40000 ALTER TABLE `user_levels` DISABLE KEYS */;
INSERT INTO `user_levels` VALUES (1,'system admin','rendszeradminisztrátor'),(2,'manager','vezető'),(3,'technician','karbantartó'),(4,'operator','gépkezelő');
/*!40000 ALTER TABLE `user_levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE utf8_hungarian_ci NOT NULL,
  `firstname` varchar(45) COLLATE utf8_hungarian_ci NOT NULL,
  `surname` varchar(45) COLLATE utf8_hungarian_ci NOT NULL,
  `firstname_is_first` tinyint(1) unsigned DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8 NOT NULL,
  `user_level` tinyint(3) unsigned NOT NULL COMMENT '1: operator can create a work request\n',
  `user_email` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `user_phone` varchar(14) COLLATE utf8_hungarian_ci DEFAULT NULL,
  `user_parent_id` tinyint(3) unsigned DEFAULT '0',
  `user_created` datetime NOT NULL DEFAULT '2000-01-01 12:00:00',
  `last_login` datetime NOT NULL DEFAULT '2000-01-01 12:00:00',
  `active` tinyint(1) unsigned NOT NULL,
  `ADD_WORKORDER` bit(1) NOT NULL DEFAULT b'0',
  `SEE_WORKORDERS` bit(1) NOT NULL DEFAULT b'0',
  `SEE_WORKORDER_DETAIL` bit(1) NOT NULL DEFAULT b'0',
  `MODIFY_WORKORDER` bit(1) NOT NULL DEFAULT b'0',
  `DELETE_WORKORDER` bit(1) NOT NULL DEFAULT b'0',
  `ADD_FILE_TO_WORKORDER` bit(1) NOT NULL DEFAULT b'0',
  `SEE_FILE_OF_WORKORDER` bit(1) NOT NULL DEFAULT b'0',
  `SEE_CONF_FILE_OF_WORKORDER` bit(1) NOT NULL DEFAULT b'0',
  `DELETE_FILE_OF_WORKORDER` bit(1) NOT NULL DEFAULT b'0',
  `SEE_STATS_OF_WORKORDERS` bit(1) NOT NULL DEFAULT b'0',
  `ADD_WORK` bit(1) NOT NULL DEFAULT b'0',
  `SEE_WORKS` bit(1) NOT NULL DEFAULT b'0',
  `SEE_WORK_DETAIL` bit(1) NOT NULL DEFAULT b'0',
  `MODIFY_WORK` bit(1) NOT NULL DEFAULT b'0',
  `DELETE_WORK` bit(1) NOT NULL DEFAULT b'0',
  `ADD_FILE_TO_WORK` bit(1) NOT NULL DEFAULT b'0',
  `SEE_FILE_OF_WORK` bit(1) NOT NULL DEFAULT b'0',
  `SEE_CONF_FILE_OF_WORK` bit(1) NOT NULL DEFAULT b'0',
  `DELETE_FILE_OF_WORK` bit(1) NOT NULL DEFAULT b'0',
  `SEE_STATS_OF_WORKS` bit(1) NOT NULL DEFAULT b'0',
  `SEE_OPERATORS_WORKS` bit(1) NOT NULL DEFAULT b'0',
  `RECORD_OPERATOR_WORK` bit(1) NOT NULL DEFAULT b'0',
  `MODIFY_OPERATOR_WORK` bit(1) NOT NULL DEFAULT b'0',
  `DELETE_OPERATOR_WORK` bit(1) NOT NULL DEFAULT b'0',
  `ADD_WORKREQUEST` bit(1) NOT NULL DEFAULT b'0',
  `SEE_WORKREQUESTS` bit(1) NOT NULL DEFAULT b'0',
  `SEE_WORKREQUEST_DETAIL` bit(1) NOT NULL DEFAULT b'0',
  `MODIFY_WORKREQUEST` bit(1) NOT NULL DEFAULT b'0',
  `DELETE_WORKREQUEST` bit(1) NOT NULL DEFAULT b'0',
  `ADD_FILE_TO_WORKREQUEST` bit(1) NOT NULL DEFAULT b'0',
  `SEE_FILE_OF_WORKREQUEST` bit(1) NOT NULL DEFAULT b'0',
  `SEE_CONF_FILE_OF_WORKREQUEST` bit(1) NOT NULL DEFAULT b'0',
  `DELETE_FILE_OF_WORKREQUEST` bit(1) NOT NULL DEFAULT b'0',
  `SEE_STATS_OF_WORKREQUESTS` bit(1) NOT NULL DEFAULT b'0',
  `ADD_ASSET` bit(1) NOT NULL DEFAULT b'0',
  `SEE_ASSETS` bit(1) NOT NULL DEFAULT b'0',
  `SEE_ASSET_DETAIL` bit(1) NOT NULL DEFAULT b'0',
  `MODIFY_ASSET` bit(1) NOT NULL DEFAULT b'0',
  `DELETE_ASSET` bit(1) NOT NULL DEFAULT b'0',
  `ADD_FILE_TO_ASSET` bit(1) NOT NULL DEFAULT b'0',
  `SEE_FILE_OF_ASSET` bit(1) NOT NULL DEFAULT b'0',
  `SEE_CONF_FILE_OF_ASSET` bit(1) NOT NULL DEFAULT b'0',
  `DELETE_FILE_OF_ASSET` bit(1) NOT NULL DEFAULT b'0',
  `ADD_LOCATION` bit(1) NOT NULL DEFAULT b'0',
  `SEE_LOCATIONS` bit(1) NOT NULL DEFAULT b'0',
  `SEE_LOCATION_DETAIL` bit(1) NOT NULL DEFAULT b'0',
  `MODIFY_LOCATION` bit(1) NOT NULL DEFAULT b'0',
  `DELETE_LOCATION` bit(1) NOT NULL DEFAULT b'0',
  `ADD_FILE_TO_LOCATION` bit(1) NOT NULL DEFAULT b'0',
  `SEE_FILE_OF_LOCATION` bit(1) NOT NULL DEFAULT b'0',
  `SEE_CONF_FILE_OF_LOCATION` bit(1) NOT NULL DEFAULT b'0',
  `DELETE_FILE_OF_LOCATION` bit(1) NOT NULL DEFAULT b'0',
  `ADD_USER` bit(1) NOT NULL DEFAULT b'0',
  `SEE_USERS` bit(1) NOT NULL DEFAULT b'0',
  `SEE_USER_DETAIL` bit(1) NOT NULL DEFAULT b'0',
  `MODIFY_USER` bit(1) NOT NULL DEFAULT b'0',
  `DELETE_USER` bit(1) NOT NULL DEFAULT b'0',
  `ADD_FILE_TO_USER` bit(1) NOT NULL DEFAULT b'0',
  `SEE_FILE_OF_USER` bit(1) NOT NULL DEFAULT b'0',
  `SEE_CONF_FILE_OF_USER` bit(1) NOT NULL DEFAULT b'0',
  `DELETE_FILE_OF_USER` bit(1) NOT NULL DEFAULT b'0',
  `SEE_STATS_OF_USERS` bit(1) NOT NULL DEFAULT b'0',
  `PUT_PRODUCT_INTO_STOCK` bit(1) NOT NULL DEFAULT b'0',
  `TAKE_PRODUCT_FROM_STOCK` bit(1) NOT NULL DEFAULT b'0',
  `DELETE_PRODUCT` bit(1) NOT NULL DEFAULT b'0',
  `SEE_STOCK` bit(1) NOT NULL DEFAULT b'0',
  `STOCK-TAKING` bit(1) NOT NULL DEFAULT b'0',
  `SEE_PRODUCT_MOVING` bit(1) NOT NULL DEFAULT b'0',
  `ADD_CATEGORY` bit(1) NOT NULL DEFAULT b'0',
  `SEE_CATEGORY` bit(1) NOT NULL DEFAULT b'0',
  `MODIFY_CATEGORY` bit(1) NOT NULL DEFAULT b'0',
  `DELETE_CATEGORY` bit(1) NOT NULL DEFAULT b'0',
  `ADD_PRODUCT` bit(1) NOT NULL DEFAULT b'0',
  `SEE_PRODUCTS` bit(1) NOT NULL DEFAULT b'0',
  `SEE_PRODUCT_DETAIL` bit(1) NOT NULL DEFAULT b'0',
  `MODIFY_PRODUCT` bit(1) NOT NULL DEFAULT b'0',
  `ADD_FILE_TO_PRODUCT` bit(1) NOT NULL DEFAULT b'0',
  `SEE_FILE_OF_PRODUCT` bit(1) NOT NULL DEFAULT b'0',
  `SEE_CONF_FILE_OF_PRODUCT` bit(1) NOT NULL DEFAULT b'0',
  `DELETE_FILE_OF_PRODUCT` bit(1) NOT NULL DEFAULT b'0',
  `SEE_PRICES` bit(1) NOT NULL DEFAULT b'0',
  `ADD_PARTNER` bit(1) NOT NULL DEFAULT b'0',
  `SEE_PARTNERS` bit(1) NOT NULL DEFAULT b'0',
  `SEE_PARTNER_DETAIL` bit(1) NOT NULL DEFAULT b'0',
  `MODIFY_PARTNER` bit(1) NOT NULL DEFAULT b'0',
  `DELETE_PARTNER` bit(1) NOT NULL DEFAULT b'0',
  `SEE_FILE_OF_PARTNER` bit(1) NOT NULL DEFAULT b'0',
  `SEE_CONF_FILE_OF_PARTNER` bit(1) NOT NULL DEFAULT b'0',
  `DELETE_FILE_OF_PARTNER` bit(1) NOT NULL DEFAULT b'0',
  `ADD_COUNTER` bit(1) NOT NULL DEFAULT b'0',
  `SEE_COUNTER` bit(1) NOT NULL DEFAULT b'0',
  `ADD_COUNTER_VALUE` bit(1) NOT NULL DEFAULT b'0',
  `MODIFY_COUNTER_VALUE` bit(1) NOT NULL DEFAULT b'0',
  `DELETE_COUNTER` bit(1) NOT NULL DEFAULT b'0',
  `DELETE_COUNTER_VALUE` bit(1) NOT NULL DEFAULT b'0',
  `ADD_PRODUCT_WORKORDER` bit(1) NOT NULL DEFAULT b'0',
  `WRITE_MESSAGE` bit(1) NOT NULL DEFAULT b'0',
  `SEE_MESSAGE` bit(1) NOT NULL DEFAULT b'0',
  `CHECK_MESSAGE` bit(1) NOT NULL DEFAULT b'0',
  `ADD_MESSAGE_TEXT` bit(1) NOT NULL DEFAULT b'0',
  `ADD_FILE_TO_MESSAGE` bit(1) NOT NULL DEFAULT b'0',
  `SEE_FILE_OF_PRODUCT_MOVING` bit(1) NOT NULL DEFAULT b'0',
  `ADD_FILE_TO_PRODUCT_MOVING` bit(1) NOT NULL DEFAULT b'0',
  `ADD_CONNECTION_TO_ASSET` bit(1) NOT NULL DEFAULT b'0',
  `ADD_NEW_CONNECTION_TYPE` bit(1) NOT NULL DEFAULT b'0',
  `SEE_CONNECTION_TYPE` bit(1) NOT NULL DEFAULT b'0',
  `SEE_CONNECTION_OF_ASSET` bit(1) NOT NULL DEFAULT b'0',
  `SEE_CONNECTION_OF_PRODUCT` bit(1) NOT NULL DEFAULT b'0',
  `ADD_CONNECTION_TO_PRODUCT` bit(1) NOT NULL DEFAULT b'0',
  `ADD_NOTIFICATION` bit(1) NOT NULL DEFAULT b'0',
  `SEE_NOTIFICATIONS` bit(1) NOT NULL DEFAULT b'0',
  `SEE_NOTIFICATION_DETAILS` bit(1) NOT NULL DEFAULT b'0',
  `MODIFY_NOTIFICATION` bit(1) NOT NULL DEFAULT b'0',
  `DELETE_NOTIFICATION` bit(1) NOT NULL DEFAULT b'0',
  `SEE_STATS_OF_NOTIFICATIONS` bit(1) NOT NULL DEFAULT b'0',
  `users_assets` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin COMMENT 'it contains all assets user responsible for. json array which consist of asset_id-s from assets table',
  `users_entry_points` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin COMMENT 'it contains all doors where user can enter. json array which consist of asset_id-s from assets table',
  `telegram_chat_id` bigint(20) unsigned DEFAULT NULL,
  `lang` varchar(4) COLLATE utf8_hungarian_ci NOT NULL,
  `Monday_start` time(4) DEFAULT '06:00:00.0000',
  `Monday_end` time(4) DEFAULT '14:00:00.0000',
  `Tuesday_start` time(4) DEFAULT '06:00:00.0000',
  `Tuesday_end` time(4) DEFAULT '14:00:00.0000',
  `Wednesday_start` time(4) DEFAULT '06:00:00.0000',
  `Wednesday_end` time(4) DEFAULT '14:00:00.0000',
  `Thursday_start` time(4) DEFAULT '06:00:00.0000',
  `Thursday_end` time(4) DEFAULT '14:00:00.0000',
  `Friday_start` time(4) DEFAULT '06:00:00.0000',
  `Friday_end` time(4) DEFAULT '14:00:00.0000',
  `Saturday_start` time(4) DEFAULT '06:00:00.0000',
  `Saturday_end` time(4) DEFAULT '14:00:00.0000',
  `Sunday_start` time(4) DEFAULT '06:00:00.0000',
  `Sunday_end` time(4) DEFAULT '14:00:00.0000',
  `users_card_id` varchar(15) COLLATE utf8_hungarian_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `userid_UNIQUE` (`user_id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `telegram_chat_id_UNIQUE` (`telegram_chat_id`),
  UNIQUE KEY `users_card_id_UNIQUE` (`users_card_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin','admin',NULL,'$2y$10$zP4Km/pRg6Z69uLtKcLPseSW.F//jBV0QcPu2YHRpVKoDP1xQUW82',1,'','',0,'2020-12-29 16:09:33','2000-01-01 12:00:00',1,_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',_binary '',NULL,NULL,NULL,'','06:00:00.0000','14:00:00.0000','06:00:00.0000','14:00:00.0000','06:00:00.0000','14:00:00.0000','06:00:00.0000','14:00:00.0000','06:00:00.0000','14:00:00.0000','06:00:00.0000','14:00:00.0000','06:00:00.0000','14:00:00.0000',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workorder_works`
--

DROP TABLE IF EXISTS `workorder_works`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workorder_works` (
  `workorder_work_id` int(11) NOT NULL AUTO_INCREMENT,
  `workorder_work_start_time` datetime NOT NULL,
  `workorder_work_end_time` datetime NOT NULL,
  `workorder_worktime` time(6) NOT NULL,
  `workorder_work` varchar(350) DEFAULT NULL,
  `main_asset_id` smallint(3) unsigned NOT NULL,
  `workorder_status` tinyint(2) unsigned NOT NULL,
  `workorder_user_id` smallint(3) unsigned NOT NULL,
  `workorder_id` int(10) unsigned NOT NULL,
  `workorder_partner_id` smallint(3) unsigned DEFAULT NULL,
  `asset_id` smallint(3) unsigned NOT NULL,
  `deleted` bit(1) NOT NULL DEFAULT b'0',
  `unplanned_shutdown` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`workorder_work_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workorder_works`
--

LOCK TABLES `workorder_works` WRITE;
/*!40000 ALTER TABLE `workorder_works` DISABLE KEYS */;
/*!40000 ALTER TABLE `workorder_works` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workorders`
--

DROP TABLE IF EXISTS `workorders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workorders` (
  `workorder_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `main_asset_id` smallint(3) unsigned DEFAULT NULL,
  `asset_id` smallint(3) unsigned DEFAULT NULL,
  `user_id` tinyint(3) unsigned DEFAULT NULL,
  `workorder_short` varchar(45) DEFAULT NULL,
  `workorder` varchar(500) DEFAULT NULL,
  `priority` tinyint(1) unsigned NOT NULL,
  `request_type` tinyint(2) unsigned NOT NULL,
  `workorder_time` datetime NOT NULL,
  `workorder_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT 'workrequest_status:\n0. new \n1. assigned to an employee\n2.ongoing \n3. the work has been done',
  `workrequest_id` int(11) unsigned NOT NULL,
  `notification_id` int(11) unsigned NOT NULL,
  `employee_id1` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `employee_id2` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `employee_id3` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `employee_id4` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `employee_id5` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `location_id` smallint(3) unsigned DEFAULT NULL,
  `main_location_id` smallint(3) DEFAULT NULL,
  `replace_to_product_id` smallint(5) unsigned DEFAULT NULL,
  `workorder_partner_id` smallint(3) unsigned DEFAULT '0',
  `workorder_partner_supervisor_user_id` smallint(3) unsigned DEFAULT NULL,
  `orig_asset_product_id` smallint(5) unsigned DEFAULT NULL,
  `orig_stock_location_id` smallint(3) unsigned DEFAULT NULL,
  `product_id_to_refurbish` smallint(5) unsigned DEFAULT NULL,
  `workorder_deadline` date DEFAULT NULL,
  `work_details_required` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`workorder_id`),
  UNIQUE KEY `workorder_id_UNIQUE` (`workorder_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workorders`
--

LOCK TABLES `workorders` WRITE;
/*!40000 ALTER TABLE `workorders` DISABLE KEYS */;
/*!40000 ALTER TABLE `workorders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workrequests`
--

DROP TABLE IF EXISTS `workrequests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workrequests` (
  `workrequest_id` int(11) NOT NULL AUTO_INCREMENT,
  `sub_workrequest_id` tinyint(3) unsigned DEFAULT NULL,
  `asset_id` smallint(5) unsigned DEFAULT NULL,
  `main_asset_id` smallint(5) unsigned DEFAULT NULL,
  `workrequest_time` datetime NOT NULL,
  `priority` tinyint(3) unsigned DEFAULT NULL,
  `user_id` tinyint(3) unsigned NOT NULL,
  `workrequest` varchar(500) NOT NULL,
  `workrequest_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'workrequest_status:\n1. new or a repetitive maintenance which is necessary again\n2. a workorder has already created\n3. the work has been done',
  `service_interval_date` smallint(5) unsigned DEFAULT NULL,
  `service_interval_hours` smallint(5) unsigned DEFAULT NULL,
  `service_interval_mileage` smallint(5) unsigned DEFAULT NULL,
  `workrequest_short` varchar(30) NOT NULL,
  `labour_norm` time(6) NOT NULL DEFAULT '00:00:00.000000',
  `repetitive` tinyint(3) unsigned NOT NULL,
  `request_type` tinyint(1) unsigned NOT NULL,
  `last_ready_date` date DEFAULT NULL,
  `last_workorder_id` tinyint(4) unsigned DEFAULT NULL,
  `last_ready_user_id` tinyint(4) unsigned DEFAULT NULL,
  `location_id` tinyint(4) unsigned DEFAULT NULL,
  `main_location_id` tinyint(4) unsigned DEFAULT NULL,
  `replace_to_product_id` smallint(5) unsigned DEFAULT NULL,
  `counter_id` smallint(3) unsigned DEFAULT NULL,
  `product_id_to_refurbish` smallint(5) unsigned DEFAULT NULL,
  `for_operators` bit(1) NOT NULL DEFAULT b'0',
  `on_the first_weekday` bit(1) NOT NULL DEFAULT b'0',
  `on_the first_monthday` bit(1) NOT NULL DEFAULT b'0',
  `auto_workorder` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`workrequest_id`),
  UNIQUE KEY `workrequest_id_UNIQUE` (`workrequest_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workrequests`
--

LOCK TABLES `workrequests` WRITE;
/*!40000 ALTER TABLE `workrequests` DISABLE KEYS */;
/*!40000 ALTER TABLE `workrequests` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-12-29 16:11:07
