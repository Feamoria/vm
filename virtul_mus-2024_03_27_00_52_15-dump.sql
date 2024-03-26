-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: vm
-- ------------------------------------------------------
-- Server version	8.0.30

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
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `event` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(30) DEFAULT NULL,
  `DateN` varchar(30) DEFAULT NULL,
  `DateK` varchar(30) DEFAULT NULL,
  `Desc_short` varchar(300) DEFAULT NULL COMMENT 'Краткое описание',
  `Desc` text COMMENT 'полное описание',
  `Doc` text COMMENT 'Ссылка на архивный документ',
  `importance` int DEFAULT NULL COMMENT 'Важность (1-9)',
  `latitude` text COMMENT 'Координаты Ю.Ш.',
  `longitude` text COMMENT 'Координаты В.Д.',
  `create_user` int NOT NULL,
  `create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event`
--

LOCK TABLES `event` WRITE;
/*!40000 ALTER TABLE `event` DISABLE KEYS */;
INSERT INTO `event` VALUES (1,'Название','1991.01.31.12.1999','1991','агагагаг','гыгыгыгы','12312',1,'123','123',1,'2024-03-26 21:41:28');
/*!40000 ALTER TABLE `event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `file`
--

DROP TABLE IF EXISTS `file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `file` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `name` text NOT NULL,
  `disc` varchar(500) NOT NULL COMMENT 'описание (500 символов?)',
  `doc` text COMMENT 'Ссылки на архивный докумен',
  `pathServ` text NOT NULL,
  `pathWeb` text,
  `type` int NOT NULL COMMENT 'тип(? возможно тип фото, документ и проч.)',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_user` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `file`
--

LOCK TABLES `file` WRITE;
/*!40000 ALTER TABLE `file` DISABLE KEYS */;
INSERT INTO `file` VALUES (1,'2024-03-26 00:00:00','Имя файла прикольное','Тут должно быть описание','123','/var/www/html/vm/img/1.jpg','/vm/img/1.jpg',0,'2024-03-26 13:17:39',1),(2,'2024-03-26 00:00:00','Файл большой ','Тут должно быть описание Тут должно быть описание Тут должно быть описание Тут должно быть описание','123','/var/www/html/vm/img/2.jpg','/vm/img/2.jpg',0,'2024-03-26 13:18:07',1),(3,'2024-03-26 00:00:00','Файл большой ','Тут должно быть описание Тут должно быть описание Тут должно быть описание Тут должно быть описание','123','/var/www/html/vm/img/3.jpg','/vm/img/3.jpg',0,'2024-03-26 13:18:07',1),(4,'2024-03-26 00:00:00','Имя файла прикольное','Тут должно быть описание','123','/var/www/html/vm/img/1.jpg','/vm/img/1.jpg',0,'2024-03-26 13:17:39',1),(5,'2024-03-26 00:00:00','Файл большой ','Тут должно быть описание Тут должно быть описание Тут должно быть описание Тут должно быть описание','123','/var/www/html/vm/img/2.jpg','/vm/img/2.jpg',0,'2024-03-26 13:18:07',1),(6,'2024-03-26 00:00:00','Файл большой ','Тут должно быть описание Тут должно быть описание Тут должно быть описание Тут должно быть описание','123','/var/www/html/vm/img/3.jpg','/vm/img/3.jpg',0,'2024-03-26 13:18:07',1);
/*!40000 ALTER TABLE `file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `file_event`
--

DROP TABLE IF EXISTS `file_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `file_event` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idEvent` int NOT NULL,
  `idFile` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idEvent` (`idEvent`),
  KEY `idFile` (`idFile`),
  CONSTRAINT `file_event_ibfk_1` FOREIGN KEY (`idFile`) REFERENCES `file` (`id`),
  CONSTRAINT `file_event_ibfk_2` FOREIGN KEY (`idEvent`) REFERENCES `event` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `file_event`
--

LOCK TABLES `file_event` WRITE;
/*!40000 ALTER TABLE `file_event` DISABLE KEYS */;
INSERT INTO `file_event` VALUES (1,1,1);
/*!40000 ALTER TABLE `file_event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `file_person`
--

DROP TABLE IF EXISTS `file_person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `file_person` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idFile` int NOT NULL,
  `idPerson` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idFile` (`idFile`),
  KEY `idPerson` (`idPerson`),
  CONSTRAINT `file_person_ibfk_1` FOREIGN KEY (`idFile`) REFERENCES `file` (`id`) ON DELETE CASCADE,
  CONSTRAINT `file_person_ibfk_2` FOREIGN KEY (`idPerson`) REFERENCES `person` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `file_person`
--

LOCK TABLES `file_person` WRITE;
/*!40000 ALTER TABLE `file_person` DISABLE KEYS */;
INSERT INTO `file_person` VALUES (1,1,1),(2,1,4),(3,2,1),(4,2,4);
/*!40000 ALTER TABLE `file_person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person`
--

DROP TABLE IF EXISTS `person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `person` (
  `id` int NOT NULL AUTO_INCREMENT,
  `F` varchar(45) DEFAULT NULL,
  `I` varchar(45) DEFAULT NULL,
  `O` varchar(45) DEFAULT NULL,
  `comment` text COMMENT 'Аннотация',
  `dol` text COMMENT 'должность',
  `dayN` date DEFAULT NULL COMMENT 'дата рождения',
  `dayD` date DEFAULT NULL COMMENT 'дата смерти',
  `create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `create_user` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `person`
--

LOCK TABLES `person` WRITE;
/*!40000 ALTER TABLE `person` DISABLE KEYS */;
INSERT INTO `person` VALUES (1,'Кочев','Алексей','Михайлович',' Алексей Михайлович (2.03.1915, с. Пыелдино Усть-Сысольского у. Вологодской губ. – ?), фотограф. С 1929 г. начал работать учеником фотографа в Сыктывкаре, с 1932 г. – фотограф-ретушер государственного фотоателье. В годы Великой Отечественной войны служил на фронте техником по аэрофотосъемке, награжден тремя медалями. В 1945-1946 гг. секретарь партбюро при горисполкоме. 1 июня 1946 г. зачислен сотрудником фотолаборатории Базы Академии наук СССР. Освобожден от занимаемой должности 15 сентября 1952 г. в связи с переводом в редакцию газеты «За новый Север»','фотограф',NULL,NULL,NULL,NULL),(4,'Дёгтева','Светлана','Владимировна','Специалист в области геоботаники, лесной типологии, охраны и рационального использования природных ресурсов.\r\nВместе с с коллегами проводит исследования растительного покрова на территориях Печоро-Илычского государственного природного биосферного заповедника и национального парка «Югыд ва».\r\nПровела классификацию растительного покрова в ландшафтах Северного (бассейн верхней и средней Печоры) и Приполярного (бассейн р. Косью) Урала, выявила особенности естественного восстановления растительных сообществ при промышленной деятельности на Приполярном Урале, закономерности смен лесных фитоценозов на вырубках и гарях в подзонах южной и средней тайги Республики Коми.\r\nАвтор и соавтор свыше 260 научных работ, в том числе 20 монографий, основные публикации посвящены проблеме трансформации растительного покрова под воздействием деятельности человека, вопросам формирования региональной системы объектов природно-заповедного фонда.','Главный научный сотрудник, доктор биологических наук ','2024-09-21',NULL,'2024-03-26 06:34:07',1);
/*!40000 ALTER TABLE `person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person_event`
--

DROP TABLE IF EXISTS `person_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `person_event` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idEvent` int NOT NULL,
  `idPerson` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idEvent` (`idEvent`),
  KEY `idPerson` (`idPerson`),
  CONSTRAINT `person_event_ibfk_1` FOREIGN KEY (`idEvent`) REFERENCES `event` (`id`) ON DELETE CASCADE,
  CONSTRAINT `person_event_ibfk_2` FOREIGN KEY (`idPerson`) REFERENCES `person` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `person_event`
--

LOCK TABLES `person_event` WRITE;
/*!40000 ALTER TABLE `person_event` DISABLE KEYS */;
INSERT INTO `person_event` VALUES (1,1,1),(2,1,4);
/*!40000 ALTER TABLE `person_event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sci_department`
--

DROP TABLE IF EXISTS `sci_department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sci_department` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `owner` varchar(10) DEFAULT NULL COMMENT 'Поле ID владельца (допускается значение индексов через запятую) (т.к. та или иная структура существовала определённый период)',
  `Date_create` date NOT NULL COMMENT 'дата создания',
  `Date_dectroy` date DEFAULT NULL COMMENT 'дата ликвидации',
  `create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'системная дата и время СОЗДАНИЯ ЗАПИСИ!',
  `create_user` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Научная тематика';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sci_department`
--

LOCK TABLES `sci_department` WRITE;
/*!40000 ALTER TABLE `sci_department` DISABLE KEYS */;
INSERT INTO `sci_department` VALUES (6,'ИБ КОМИ НЦ УРО РАН',NULL,'2024-03-01',NULL,'2024-03-25 19:38:39',1),(11,'ИХ КОМИ НЦ УРО РАН',NULL,'2024-03-25',NULL,'2024-03-25 19:57:42',1),(12,'ИФ КОМИ НЦ УРО РАН',NULL,'2024-03-25',NULL,'2024-03-25 19:57:54',1);
/*!40000 ALTER TABLE `sci_department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sci_department_event`
--

DROP TABLE IF EXISTS `sci_department_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sci_department_event` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idSciDepartment` int NOT NULL,
  `idEvent` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idSciDepartment` (`idSciDepartment`),
  KEY `idEvent` (`idEvent`),
  CONSTRAINT `sci_department_event_ibfk_1` FOREIGN KEY (`idSciDepartment`) REFERENCES `sci_department` (`id`),
  CONSTRAINT `sci_department_event_ibfk_2` FOREIGN KEY (`idEvent`) REFERENCES `event` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sci_department_event`
--

LOCK TABLES `sci_department_event` WRITE;
/*!40000 ALTER TABLE `sci_department_event` DISABLE KEYS */;
INSERT INTO `sci_department_event` VALUES (1,11,1),(2,12,1);
/*!40000 ALTER TABLE `sci_department_event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sci_department_person`
--

DROP TABLE IF EXISTS `sci_department_person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sci_department_person` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idSciDepartment` int NOT NULL,
  `idPerson` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idSciDepartment` (`idSciDepartment`),
  KEY `idPerson` (`idPerson`),
  CONSTRAINT `sci_department_person_ibfk_1` FOREIGN KEY (`idSciDepartment`) REFERENCES `sci_department` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sci_department_person_ibfk_2` FOREIGN KEY (`idPerson`) REFERENCES `person` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sci_department_person`
--

LOCK TABLES `sci_department_person` WRITE;
/*!40000 ALTER TABLE `sci_department_person` DISABLE KEYS */;
INSERT INTO `sci_department_person` VALUES (2,6,4),(3,11,4);
/*!40000 ALTER TABLE `sci_department_person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sci_theme`
--

DROP TABLE IF EXISTS `sci_theme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sci_theme` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_user` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Научная тематика';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sci_theme`
--

LOCK TABLES `sci_theme` WRITE;
/*!40000 ALTER TABLE `sci_theme` DISABLE KEYS */;
INSERT INTO `sci_theme` VALUES (1,'Химические науки','2024-03-25 13:58:11',2),(2,'Физические науки','2024-03-25 13:58:18',2),(3,'Геологические науки','2024-03-25 13:58:31',2),(4,'Биологические науки','2024-03-25 13:58:37',2);
/*!40000 ALTER TABLE `sci_theme` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sci_theme_event`
--

DROP TABLE IF EXISTS `sci_theme_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sci_theme_event` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idTheme` int NOT NULL,
  `idEvent` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idTheme` (`idTheme`),
  KEY `idEvent` (`idEvent`),
  CONSTRAINT `sci_theme_event_ibfk_2` FOREIGN KEY (`idEvent`) REFERENCES `event` (`id`),
  CONSTRAINT `sci_theme_event_ibfk_3` FOREIGN KEY (`idTheme`) REFERENCES `sci_theme` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sci_theme_event`
--

LOCK TABLES `sci_theme_event` WRITE;
/*!40000 ALTER TABLE `sci_theme_event` DISABLE KEYS */;
INSERT INTO `sci_theme_event` VALUES (1,3,1),(2,4,1);
/*!40000 ALTER TABLE `sci_theme_event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sci_theme_file`
--

DROP TABLE IF EXISTS `sci_theme_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sci_theme_file` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idFile` int NOT NULL,
  `idSciTheme` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idFile` (`idFile`),
  KEY `idSciTheme` (`idSciTheme`),
  CONSTRAINT `sci_theme_file_ibfk_1` FOREIGN KEY (`idFile`) REFERENCES `file` (`id`),
  CONSTRAINT `sci_theme_file_ibfk_2` FOREIGN KEY (`idSciTheme`) REFERENCES `sci_theme` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sci_theme_file`
--

LOCK TABLES `sci_theme_file` WRITE;
/*!40000 ALTER TABLE `sci_theme_file` DISABLE KEYS */;
INSERT INTO `sci_theme_file` VALUES (1,1,1),(2,1,2),(3,2,1),(4,2,2);
/*!40000 ALTER TABLE `sci_theme_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sci_theme_pers`
--

DROP TABLE IF EXISTS `sci_theme_pers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sci_theme_pers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idTheme` int NOT NULL,
  `idPers` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idPers` (`idPers`),
  KEY `idTheme` (`idTheme`),
  CONSTRAINT `sci_theme_pers_ibfk_2` FOREIGN KEY (`idPers`) REFERENCES `person` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sci_theme_pers_ibfk_3` FOREIGN KEY (`idTheme`) REFERENCES `sci_theme` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sci_theme_pers`
--

LOCK TABLES `sci_theme_pers` WRITE;
/*!40000 ALTER TABLE `sci_theme_pers` DISABLE KEYS */;
INSERT INTO `sci_theme_pers` VALUES (2,3,4),(3,4,4);
/*!40000 ALTER TABLE `sci_theme_pers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tag`
--

DROP TABLE IF EXISTS `tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tag` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(20) DEFAULT NULL,
  `create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `create_user` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag_Name_uindex` (`Name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tag`
--

LOCK TABLES `tag` WRITE;
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;
INSERT INTO `tag` VALUES (1,'физика','2024-03-23 16:41:44',1),(2,'химия','2024-03-23 16:42:17',1),(3,'математика','2024-03-23 16:47:32',1),(4,'история','2024-03-23 16:49:55',1),(5,'литература','2024-03-23 16:55:07',1),(7,'биология','2024-03-25 20:52:11',1);
/*!40000 ALTER TABLE `tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tag_event`
--

DROP TABLE IF EXISTS `tag_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tag_event` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idTag` int NOT NULL,
  `idEvent` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idTag` (`idTag`),
  KEY `idEvent` (`idEvent`),
  CONSTRAINT `tag_event_ibfk_1` FOREIGN KEY (`idEvent`) REFERENCES `event` (`id`),
  CONSTRAINT `tag_event_ibfk_2` FOREIGN KEY (`idTag`) REFERENCES `tag` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tag_event`
--

LOCK TABLES `tag_event` WRITE;
/*!40000 ALTER TABLE `tag_event` DISABLE KEYS */;
INSERT INTO `tag_event` VALUES (1,3,1);
/*!40000 ALTER TABLE `tag_event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tag_file`
--

DROP TABLE IF EXISTS `tag_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tag_file` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idTag` int NOT NULL,
  `idFile` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idTag` (`idTag`),
  KEY `idFile` (`idFile`),
  CONSTRAINT `tag_file_ibfk_1` FOREIGN KEY (`idTag`) REFERENCES `tag` (`id`),
  CONSTRAINT `tag_file_ibfk_2` FOREIGN KEY (`idFile`) REFERENCES `file` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tag_file`
--

LOCK TABLES `tag_file` WRITE;
/*!40000 ALTER TABLE `tag_file` DISABLE KEYS */;
INSERT INTO `tag_file` VALUES (1,3,1),(2,1,1),(3,3,2),(4,1,2);
/*!40000 ALTER TABLE `tag_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tag_person`
--

DROP TABLE IF EXISTS `tag_person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tag_person` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idTag` int NOT NULL,
  `idPerson` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idTag` (`idTag`),
  KEY `idUser` (`idPerson`),
  KEY `idPerson` (`idPerson`),
  CONSTRAINT `tag_person_ibfk_1` FOREIGN KEY (`idPerson`) REFERENCES `person` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tag_person_ibfk_2` FOREIGN KEY (`idTag`) REFERENCES `tag` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tag_person`
--

LOCK TABLES `tag_person` WRITE;
/*!40000 ALTER TABLE `tag_person` DISABLE KEYS */;
INSERT INTO `tag_person` VALUES (3,7,4),(4,1,4);
/*!40000 ALTER TABLE `tag_person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role` int DEFAULT NULL,
  `FIO` varchar(200) DEFAULT NULL,
  `login` varchar(20) DEFAULT NULL,
  `pass` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,1,'Хохлов Роман Николаевич','hohlov','fdc7024e6ac8917bc1eb2a5878009c8047f805e28aef2acfb73b1a68165171482910cd186a9a47d1487eaeb03e98939c954e1c77aece4e02c459726199ac2f67');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-03-27  0:52:15
