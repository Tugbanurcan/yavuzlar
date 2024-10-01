-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: localhost    Database: yemekdb
-- ------------------------------------------------------
-- Server version	8.0.39

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
-- Table structure for table `basket`
--

DROP TABLE IF EXISTS `basket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `basket` (
  `food_id` int NOT NULL,
  `user_id` int NOT NULL,
  `quantity` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`food_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `basket_ibfk_1` FOREIGN KEY (`food_id`) REFERENCES `food` (`id`),
  CONSTRAINT `basket_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `basket`
--

LOCK TABLES `basket` WRITE;
/*!40000 ALTER TABLE `basket` DISABLE KEYS */;
/*!40000 ALTER TABLE `basket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `restaurant_id` int DEFAULT NULL,
  `surname` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `score` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `restaurant_id` (`restaurant_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `company` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `logo_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company`
--

LOCK TABLES `company` WRITE;
/*!40000 ALTER TABLE `company` DISABLE KEYS */;
INSERT INTO `company` VALUES (1,'Pizzalicious','Pizza alanında faaliyet gösteren bir şirket.','images.png','2024-09-18 19:15:23',NULL),(2,'Gourmet Burgers','Hamburger alanında faaliyet gösteren bir şirket.','indir.png','2024-09-18 19:25:37',NULL),(3,'Pasta Bliss','Makarna alanında faaliyet gösteren bir şirket.','pasta-1181189_1280.jpg','2024-09-18 19:49:55',NULL),(4,'Izgara Ustaları','Tavuk ve kebap konusunda uzmanlaşmış bir şirket.','smoke-1568953_1280.jpg','2024-09-18 20:12:10',NULL),(5,'Blue Wave Seafood','Deniz ürünleri alanında faaliyet gösteren bir şirket.','','2024-09-21 15:33:01',NULL),(6,'The Dessert Corner','Tatlı alanında faaliyet gösteren bir şirket.','','2024-09-25 00:04:56',NULL),(7,'jjjjj oooooooooooooo','jjhjhjhjhjj',NULL,'2024-09-27 22:30:50','2024-09-28 08:44:01'),(8,'sswsw fggdgdtgtdg','sadde','','2024-09-27 22:54:25','2024-09-28 08:44:07');
/*!40000 ALTER TABLE `company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupon`
--

DROP TABLE IF EXISTS `coupon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupon` (
  `id` int NOT NULL AUTO_INCREMENT,
  `restaurant_id` int DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `discount` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `restaurant_id` (`restaurant_id`),
  CONSTRAINT `coupon_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupon`
--

LOCK TABLES `coupon` WRITE;
/*!40000 ALTER TABLE `coupon` DISABLE KEYS */;
/*!40000 ALTER TABLE `coupon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `food`
--

DROP TABLE IF EXISTS `food`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `food` (
  `id` int NOT NULL AUTO_INCREMENT,
  `restaurant_id` int DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `image_path` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `discount` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `restaurant_id` (`restaurant_id`),
  CONSTRAINT `food_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `food`
--

LOCK TABLES `food` WRITE;
/*!40000 ALTER TABLE `food` DISABLE KEYS */;
INSERT INTO `food` VALUES (2,1,'Büyük Boy Karışık Pizza','Lezzetli ve doyurucu bir pizza.','pizza-6664791_1280.jpg',350.00,250.00,'2024-09-18 19:19:04',NULL,'İndirimli'),(3,2,'Double Cheese Burger','İki kat peynirli, leziz burger.','cheeseburger-8721189_1280.png',490.00,450.00,'2024-09-18 19:37:08',NULL,'İndirimli'),(4,3,'Al Dente Pasta','Kremalı sosla hazırlanmış taze makarna.','pasta-1854245_1280.jpg',220.00,50.00,'2024-09-18 20:00:13',NULL,'İndirimli'),(5,3,'Akdeniz Salatası','Özel sosla hazırlanmış salata.','slider-img4.png',150.00,50.00,'2024-09-18 20:06:44',NULL,'İndirimli'),(6,4,'Izgara Tavuk','Lezzetli baharatlarla marine edilmiş ve ızgarada pişirilmiş tavuk.','ai-generated-8701491_1280.png',250.00,75.00,'2024-09-18 20:15:53',NULL,'İndirimli'),(8,4,'Kuzu Tandır','Uzun süre pişirilmiş, yumuşak ve lezzetli kuzu tandır.','food-2456084_1280.jpg',750.00,100.00,'2024-09-18 20:18:18',NULL,'İndirimli'),(9,4,'Adana Kebap','Türk mutfağının popüler lezzetidir.','kebab-meat-sandwich-7414529_1280.jpg',500.00,0.00,'2024-09-21 11:52:09',NULL,NULL),(10,4,'Adana Kebap','Türk mutfağının popüler lezzetidir.','kebab-meat-sandwich-7414529_1280.jpg',500.00,0.00,'2024-09-21 15:05:38',NULL,'famous'),(11,4,'Karışık Izgara','Türk mutfağının popüler lezzetidir.','kebab-2505237_1280.jpg',780.00,0.00,'2024-09-21 15:15:17',NULL,'famous'),(12,4,'Et Bonfile','Türk mutfağının popüler lezzetidir.','food-2456084_1280.jpg',780.00,0.00,'2024-09-21 15:19:45',NULL,'famous'),(15,5,'Somon','Izgarada hazırlanarak en iyi şekilde servis edilir.','food-712665_1280.jpg',780.00,0.00,'2024-09-21 15:41:49',NULL,'famous'),(16,2,'Hamburger','Sulu ve lezzetli et ile hazırlanan klasik bir Amerikan lezzeti. Taze sebzeler ve soslarla servis edilir.','burger-7221436_1280.jpg',430.00,0.00,'2024-09-21 15:52:59',NULL,'famous'),(17,4,'Lahmacun','Türk mutfağının sevilen lezzetidir.','pita-6235506_1280.jpg',200.00,0.00,'2024-09-21 16:01:16',NULL,'famous'),(18,6,'Mercimek çorbası','','slider-img2.png',85.00,0.00,'2024-09-24 20:25:32',NULL,'corba'),(19,6,'Kelle Paça','','soup-2730120_1280.jpg',150.00,0.00,'2024-09-24 20:28:28',NULL,'corba'),(20,6,'Brokoli çorbası','','soup-570922_1280.jpg',80.00,0.00,'2024-09-24 20:33:23',NULL,'corba'),(21,1,'Karışık tost','','club-sandwich-3538455_1280.jpg',185.00,0.00,'2024-09-24 21:18:24',NULL,'yemekler'),(22,2,'Sarımsaklı Parmesan Patates','','french-fries-1846083_1280.jpg',200.00,0.00,'2024-09-24 21:26:57',NULL,'yemekler'),(23,2,'Fırında Baharatlı Tavuk Butu','','fried-3949947_1280.jpg',350.00,0.00,'2024-09-24 21:37:35',NULL,'yemekler'),(24,2,'Mini ikili hamburger','','hamburger-494706_1280.jpg',350.00,0.00,'2024-09-24 21:40:31',NULL,'yemekler'),(25,2,'Meksika Soslu Sosisli','','hot-dog-4081683_1280.jpg',270.00,0.00,'2024-09-24 21:43:56',NULL,'yemekler'),(26,4,'Tombik Ekmekte Et Döner','.','kebab-2451112_1280.jpg',257.00,0.00,'2024-09-24 21:45:41',NULL,'yemekler'),(27,3,'Soslu Tavuklu Karışık Salata','','n1.jpg',438.00,0.00,'2024-09-24 21:49:25',NULL,'yemekler'),(28,3,'Kremalı Tavuklu Makarna Salatası','','n2.jpg',484.00,0.00,'2024-09-24 21:50:53',NULL,'yemekler'),(29,1,'Büyük Boy Mozzarella Sucuk Pizza','','pizza-7863713_1280.jpg',530.00,0.00,'2024-09-24 21:54:32',NULL,'yemekler'),(30,8,'Baklava','','baklava-with-pistachios-4183180_1280.jpg',570.00,0.00,'2024-09-25 00:12:24',NULL,'tatli'),(31,8,'Saray Sarması','','food-5158707_1280.jpg',530.00,0.00,'2024-09-25 00:14:26',NULL,'tatli'),(32,8,'Karışık Kaymaklı Lokum','','sweet-4506016_1280.jpg',367.00,0.00,'2024-09-25 00:18:00',NULL,'tatli'),(33,8,'Meyve Şöleni Waffle','','waffles-3828345_1280.jpg',248.00,0.00,'2024-09-25 00:20:01',NULL,'tatli'),(34,8,'Kiraz Rüyası','','sweet-4310269_1280.jpg',248.00,0.00,'2024-09-25 00:21:01',NULL,'tatli'),(35,8,'Fırın Sütlaç','','rice-pudding-6594064_1280.jpg',200.00,0.00,'2024-09-25 00:22:03',NULL,'tatli'),(36,8,'Un Kurabiyesi','','homemade-6593128_1280.jpg',200.00,0.00,'2024-09-25 00:23:01',NULL,'tatli'),(37,8,'Napolyon Tatlısı','','eclair-3366430_1280.jpg',230.00,0.00,'2024-09-25 00:28:04',NULL,'tatli'),(38,8,'Churros','','churros-2188871_1280.jpg',230.00,0.00,'2024-09-25 00:33:54',NULL,'tatli'),(39,3,'ff','fwfwf',NULL,159.00,13.00,'2024-09-28 16:57:36','2024-09-28 16:59:26',NULL);
/*!40000 ALTER TABLE `food` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `order_status` varchar(50) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order`
--

LOCK TABLES `order` WRITE;
/*!40000 ALTER TABLE `order` DISABLE KEYS */;
INSERT INTO `order` VALUES (1,18,7732.00,'yolda',7732.00,'2024-09-26 17:46:18');
/*!40000 ALTER TABLE `order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `order_id` int NOT NULL,
  `food_id` int NOT NULL,
  `quantity` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`order_id`,`food_id`),
  KEY `food_id` (`food_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`),
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`food_id`) REFERENCES `food` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,2,3,350.00),(1,3,2,490.00),(1,4,2,220.00),(1,5,4,150.00),(1,8,2,750.00),(1,10,3,500.00),(1,11,1,780.00),(1,17,1,200.00),(1,18,1,85.00),(1,32,1,367.00),(1,38,1,230.00);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant`
--

DROP TABLE IF EXISTS `restaurant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `restaurant` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `restaurant_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant`
--

LOCK TABLES `restaurant` WRITE;
/*!40000 ALTER TABLE `restaurant` DISABLE KEYS */;
INSERT INTO `restaurant` VALUES (1,1,'Pizza Restoranı','Lezzetli pizzalar yapan bir restoran.','pizza-8784578_1280.jpg','2024-09-18 19:18:10'),(2,2,'Burger Town ','Lezzetli hamburgerler yapan bir restoran.','burger9_01.jpg','2024-09-18 19:33:49'),(3,3,'Al Dente Pasta','Lezzetli makarnalar yapan bir restoran.','pizza-8784578_1280.jpg','2024-09-18 19:52:42'),(4,4,'Tavuk & Kebap Evi','Lezzetli tavuk ve kebap çeşitleri sunan bir restoran.','','2024-09-18 20:13:25'),(5,5,'Mercan Marine','Lezzetli deniz ürünleri satan bir restoran.','','2024-09-21 15:35:15'),(6,3,'Çorbacı Mehmet Usta','Lezzetli çorbalar yapan bir restoran.','','2024-09-24 20:21:31'),(8,6,'Tatlıcılar Diyarı','','','2024-09-25 00:08:46');
/*!40000 ALTER TABLE `restaurant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `surname` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,NULL,NULL,'Ayşe ela','Durgun','ayse@com','$argon2id$v=19$m=65536,t=4,p=1$RDNXR05uSjRjeDh6QmJlMw$1Qq/qcWT/1mCEC4TO7bFRQN2kYfe6Bl545NeA53aH68',0.00,'2024-09-16 12:50:08',NULL),(2,NULL,NULL,'Ahmet','Atılgan','Ahmet@com','$argon2id$v=19$m=65536,t=4,p=1$NUhsajVqWVg1bVNaUS9SQQ$G6T8dB0ScdRbIMlP/X51ySYsH3RY78RrxQHce5+7XEQ',0.00,'2024-09-16 12:56:24',NULL),(3,NULL,NULL,'Arif','Şen','arrif@com','$argon2id$v=19$m=65536,t=4,p=1$T2l5a2VLWk9pc0xqTmZsdw$5yCkIwTrha4ahyKMxZRBFsnQmYmpbs7+Rd8dgd6CnEk',0.00,'2024-09-16 13:15:58',NULL),(4,NULL,NULL,'Arif','Şen','arrif@com','$argon2id$v=19$m=65536,t=4,p=1$Lm01ZnhJbHNZbUhyeWR0SA$ov7fAcPExvFuZXzpyc+smZQFEBHgrkgi0LzCcz1P7GA',0.00,'2024-09-16 13:17:20',NULL),(5,NULL,NULL,'e','b','b@com','$argon2id$v=19$m=65536,t=4,p=1$cXM4Y3JOaU1wZjFvZmdJaQ$9pk/MUuZH/mjc6dF/gE6oW06tbwUdv/AiY4joU4eeAg',0.00,'2024-09-16 13:19:50',NULL),(6,NULL,NULL,'a','ac','a@com','$argon2id$v=19$m=65536,t=4,p=1$TWFBUjNHR2FhN1l0VG9vYw$UXrkq/nBFQr/Vdecibu7VP1aHTwSB33HLm/bcBF4XDA',0.00,'2024-09-16 13:22:27','2024-09-27 21:03:08'),(7,NULL,NULL,'a','b','a@com','$argon2id$v=19$m=65536,t=4,p=1$OHRnN2VhcFZDbEhlaGQ3Ng$34adTKt6qOZCQNlE3NGxkobqbISUVwN9+puLxrM0So8',0.00,'2024-09-16 13:27:50','2024-09-27 21:05:04'),(8,NULL,NULL,'c','cc','c@com','$argon2id$v=19$m=65536,t=4,p=1$MG9vLnhZY2hlbG1WeUJqZQ$bcziJj2srftfKwCUim+XDGk8xilh+s24CZ+IAmzlO3M',0.00,'2024-09-16 13:33:26','2024-09-27 21:06:05'),(9,NULL,NULL,'aa','bbb','aaa@cfcfc','$argon2id$v=19$m=65536,t=4,p=1$cXJoQ1hZVTFJd0VsZFVScg$74s2enDtmx911SatD8nGwSrBz/gheReeOY32TvRTBLk',0.00,'2024-09-16 13:34:26','2024-09-27 21:06:11'),(10,NULL,NULL,'sd','adasad','adda@com','$argon2id$v=19$m=65536,t=4,p=1$SW40UnZEeG5DVjJzRVhubw$69QLGJTmtSDGnWcKU57D6jS3rWVbq//AAxukpca7bRA',0.00,'2024-09-16 14:03:58','2024-09-27 21:08:15'),(11,NULL,NULL,'sd','adasad','adda@com','$argon2id$v=19$m=65536,t=4,p=1$bk50cEdXWFJHNDlZYXpZWg$TOWxkUp6r5JWtMXTCZbchq+IIjR6i9XdvEyzFODvJJw',0.00,'2024-09-16 14:04:04','2024-09-27 21:08:23'),(12,NULL,NULL,'dssd','ddsd','dd@com','$argon2id$v=19$m=65536,t=4,p=1$RHNDTEl3V2Z5TnFVUmFVeQ$FSjvZKsLKX6+cCnIX7Z3K07qn2OMy4WQSIGUSed9clY',0.00,'2024-09-16 14:05:47','2024-09-27 21:56:43'),(13,NULL,NULL,'sdsd','sdasd','sds@com','$argon2id$v=19$m=65536,t=4,p=1$aURrS2FjLjB2Z3VSZ0UwcQ$M+At738axDliZTvQQS0l200D9s/N3HBvPWSBgyapcNU',0.00,'2024-09-16 14:06:05',NULL),(14,NULL,NULL,'ded','frfer','dwedw@com','$argon2id$v=19$m=65536,t=4,p=1$QnlaRFZVSHRSTjFVRkk1Tw$dmuQJrHx04KpSggGwam43ci8NI+w87xQsO3txtoNLwo',0.00,'2024-09-16 14:09:19',NULL),(15,NULL,NULL,'sdd','dsds','sdddd@cd','$argon2id$v=19$m=65536,t=4,p=1$MUtqOHZFQ3d4eFBiUUlSMg$G1zreZ7B4sz8UOOAm4qrmSCwGwN8fnCCzwB2Vzo7kNo',0.00,'2024-09-16 14:10:42',NULL),(16,NULL,NULL,'jhjkll','unklş','kddkk@ddk','$argon2id$v=19$m=65536,t=4,p=1$MWRKaURoZHlna2hrYjBrRQ$FrhUgMDBMNuOsrpl+Grh92emQldFn9K/68kEEAVrrSY',0.00,'2024-09-16 14:12:34',NULL),(17,NULL,NULL,'dff','grge','dada@ldflf','$argon2id$v=19$m=65536,t=4,p=1$c2Fkem1GdWE5QzRreE1TdA$WydDVqujaqltnI1vuexq3+O7NikWeLPWPiv/lnFxwBw',0.00,'2024-09-16 14:13:58',NULL),(18,NULL,NULL,'a','bbb','aa@com','$argon2id$v=19$m=65536,t=4,p=1$WUFwTkF1VGNlUGZXclJLMg$vBVGHbfJMTILAKpZjFYcK+siIc3mv1kW6BZ1ELP2fPg',0.00,'2024-09-16 14:15:06',NULL),(19,NULL,NULL,'de','d','de@com','$argon2id$v=19$m=65536,t=4,p=1$bk9RVjY1QUpMSXZlVXo4aw$vyJj7SWwiw9wZkkjIZ3qlavZU2Kg7J78iAdZtWTM/3I',0.00,'2024-09-18 16:35:29',NULL),(20,NULL,'admin',NULL,NULL,'admin','$argon2id$v=19$m=65536,t=4,p=1$dXdDZ0pvQUtGTHZWSkJCSg$AZVKGYWyntC283ZRb4Mc+UAgn36TR2VPLhCciYWfDGg',0.00,'2024-09-27 07:53:00',NULL),(48,1,'firma','Güneş','Çelik','gunes@com','$argon2id$v=19$m=65536,t=4,p=1$SkxMT0JqcE4vTkxERWRnOQ$ubL/6JuEpRnLDtD0IHhvRmthaBE8GvlTbmZC9EneL/g',0.00,'2024-09-28 14:40:45',NULL),(49,2,'firma','Çınar','Aydın','aydın@com','$argon2id$v=19$m=65536,t=4,p=1$aDBQTVhEZnJ6Y3dnQTRIZA$fyxK0kUhLde0stasOHWR2PHjCzF1Ubjk7Y5hVG1q934',0.00,'2024-09-28 14:42:03',NULL),(50,3,'firma','Cemre','Tekin','tekin@com','$argon2id$v=19$m=65536,t=4,p=1$UVFUVWc2WXIuTUkvVU5XVQ$WquipjDoFfxwLtL94mvRVRaEIt1ItRfbdoxcqULWnGQ',0.00,'2024-09-28 14:49:27',NULL),(51,4,'firma','Simay ','Yıldız','simay@com','$argon2id$v=19$m=65536,t=4,p=1$NlJ4QVphcmIzUTBDaENwQg$+zBpMw+2l382DmUbOIg3pi50mdi+xn5mF6YxfxvQpgk',0.00,'2024-09-28 14:55:12',NULL),(52,5,'firma','Kuzey ','Kara','kuzey@com','$argon2id$v=19$m=65536,t=4,p=1$ak41cEo1dFdlREh4WkFMbQ$SnxfJB9DmFiAaKI7xAhJ9sjSB5ZtvsyfXpwsBDy/Z4E',0.00,'2024-09-28 14:58:28',NULL),(53,6,'firma','Yaman','Aydın','yaman@com','$argon2id$v=19$m=65536,t=4,p=1$N2c5Lnl2RkFVQ2cxSS9CSA$kCPZZJu5BRF6Ec7sPVv445UwRJ0n66Xz7A1EHrGRRnI',0.00,'2024-09-28 15:01:16',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-09-29 14:21:31
