-- MySQL dump 10.13  Distrib 8.0.36, for Linux (x86_64)
--
-- Host: 172.19.1.7    Database: virtul_mus
-- ------------------------------------------------------
-- Server version	5.5.68-MariaDB

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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(210) DEFAULT NULL,
  `DateN` varchar(30) DEFAULT NULL,
  `DateK` varchar(30) DEFAULT NULL,
  `Desc_short` varchar(300) DEFAULT NULL COMMENT 'DERICATED\nКраткое описание',
  `Desc` text COMMENT 'полное описание',
  `Doc` text COMMENT 'Ссылка на архивный документ',
  `importance` int(11) DEFAULT NULL COMMENT 'Важность (1-9)',
  `latitude` text COMMENT 'Координаты Ю.Ш.',
  `longitude` text COMMENT 'Координаты В.Д.',
  `create_user` int(11) NOT NULL,
  `create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event`
--

LOCK TABLES `event` WRITE;
/*!40000 ALTER TABLE `event` DISABLE KEYS */;
INSERT INTO `event` (`id`, `Name`, `DateN`, `DateK`, `Desc_short`, `Desc`, `Doc`, `importance`, `latitude`, `longitude`, `create_user`, `create_date`) VALUES (2,'Создание отдела языка Институт','1970','','На момент формирования Института языка, литературы и истории отдел языка состоял из 8 человек: кандидаты филологических наук с.н.с. В.А. Сорвачева (1945–1977), Т.И. Жилина (1946 –1982), Е.С. Гуляев (1954–1977), м.н.с. Н.Н. Сельков (1945–1976) и ст. лаборанты Н.И. Лоскутова (1958–1991), Э.К. Павлова ','','',2,'','',2,'2024-03-29 10:24:44'),(3,'Вышел в свет «Краткий этимолог','1970','','«Краткий этимологический словарь коми языка» - это словарь, который даёт систематизированные сведения о происхождении коми слов, реконструируются древние формы слов и их значения. Авторы: В.И. Лыткин, Е.С. Гуляев. Работа внесла существенный вклад в развитие исторической лексикологии финно-угорских я','','',2,'','',2,'2024-04-01 08:46:37'),(4,'Вышел в свет «Краткий этимолог','1970','','«Краткий этимологический словарь коми языка» - это словарь, который даёт систематизированные сведения о происхождении коми слов, реконструируются древние формы слов и их значения. Авторы: В.И. Лыткин, Е.С. Гуляев. Работа внесла существенный вклад в развитие исторической лексикологии финно-угорских я','','',2,'','',2,'2024-04-01 10:01:48'),(5,'Образован Коми научно-исследов','1931.07.20','','При Президиуме АН СССР образована комиссия во главе с вице-президентом АН В.Л.Комаровым для подготовки проекта по созданию стационарных академических учреждений в регионах.','В 1931 г. Президиум АН СССР создана комиссия во главе с вице-президентом АН В.Л.Комаровым для подготовки проекта по формированию стационарных академических учреждений в регионах. 20 июля 1931 г. Президиум ВЦИК постановил организовать научно-исследовательский институт при Коми облисполкоме. В том же году было организовано оргбюро по созданию НИИ, но организационный период затянулся [12; 14, с. 23]. В 1933 г. в Сыктывкаре побывал президент Академии наук СССР А.П.Карпинский, убедившийся в больших потенциальных возможностях развития науки в Коми. После этого вопрос о Коми НИИ был решен окончательно. 17 апреля 1934 г. в Сыктывкаре был создан Коми научно-исследовательский институт краеведения (КНИИК).','',1,'','',2,'2024-04-02 06:21:47'),(6,'Образован Коми научно-исследов','1931.07.20','','При Президиуме АН СССР образована комиссия во главе с вице-президентом АН В.Л.Комаровым для подготовки проекта по созданию стационарных академических учреждений в регионах.','В 1931 г. Президиум АН СССР создана комиссия во главе с вице-президентом АН В.Л.Комаровым для подготовки проекта по формированию стационарных академических учреждений в регионах. 20 июля 1931 г. Президиум ВЦИК постановил организовать научно-исследовательский институт при Коми облисполкоме. В том же году было организовано оргбюро по созданию НИИ, но организационный период затянулся [12; 14, с. 23]. В 1933 г. в Сыктывкаре побывал президент Академии наук СССР А.П.Карпинский, убедившийся в больших потенциальных возможностях развития науки в Коми. После этого вопрос о Коми НИИ был решен окончательно. 17 апреля 1934 г. в Сыктывкаре был создан Коми научно-исследовательский институт краеведения (КНИИК).','',1,'','',2,'2024-04-02 06:22:11'),(7,'Образован Коми научно-исследов','1934.04.17','','Образован Коми научно-исследовательский институт краеведения','В составе института было создано 2 отдела: отдел истории, культуры и быта и отдел языка, литературы и искусства. Его сотрудники изучали коми язык, литературу, историю, экономику, природные ресурсы региона. Основной задачей КНИИК являлась «координация и обобщение научно-исследовательской работы, проводимой разными областными и внеобластными учреждениями и организациями, организация исследований по истории, культуре и экономике Коми области в соответствии с задачами народнохозяйственного развития, а также составление первого тома Коми советской энциклопедии». Президиум АН СССР оказал помощь в комплектовании библиотеки института. Директором стал Д.И.Шулепов, ученым секретарем – А.И.Бабушкин (в августе 1934 г. «в связи с загруженностью его по основной работе» А.И.Бабушкин был освобожден от обязанностей ученого секретаря Коми НИИК, но продолжал участвовать в деятельности института). Институт вначале состоял из отделов национальной культуры, промышленности, сельского хозяйства. КНИИК подчинялся Управлению университетов и научно-исследовательских учреждений Наркомпроса РСФСР, а финансировался из местного бюджета. При создании КНИИК принимался во внимание разработанный А.С.Сидоровым проект, и сам он (как и ряд его коллег по ОИКК) сотрудничал в этом институте до своего отъезда в Ленинград. ','',1,'','',2,'2024-04-02 06:39:43'),(11,'test','1234.1','',NULL,'','',1,'','',1,'2024-04-02 09:07:47');
/*!40000 ALTER TABLE `event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `file`
--

DROP TABLE IF EXISTS `file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `name` text NOT NULL,
  `disc` varchar(500) NOT NULL COMMENT 'описание (500 символов?)',
  `doc` text COMMENT 'Ссылки на архивный докумен',
  `pathServ` text NOT NULL,
  `pathWeb` text,
  `type` int(11) NOT NULL COMMENT 'тип(? возможно тип фото, документ и проч.)',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_user` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `file`
--

LOCK TABLES `file` WRITE;
/*!40000 ALTER TABLE `file` DISABLE KEYS */;
INSERT INTO `file` (`id`, `date`, `name`, `disc`, `doc`, `pathServ`, `pathWeb`, `type`, `create_date`, `create_user`) VALUES (8,'1922-02-23 00:00:00','sorvasheva.jpg','кандидат филологических наук, специалист в области диалектологии и лексикографии коми языка','','/var/www/html/vm/img/sorvasheva-211.jpg','/vm/img/sorvasheva-211.jpg',0,'2024-03-29 08:53:02',2),(9,'1922-02-23 00:00:00','zhilina.jpg','кандидат филологических наук, специалист в области диалектологии и лексикографии коми языка','','/var/www/html/vm/img/zhilina-342.jpg','/vm/img/zhilina-342.jpg',0,'2024-03-29 09:06:40',2),(10,'1928-03-04 00:00:00','qulyaev.jpg','кандидат филологических наук, старший научный сотрудник','','/var/www/html/vm/img/gulyaev-300.jpg','/vm/img/gulyaev-300.jpg',0,'2024-03-29 09:38:06',2),(11,'1911-08-15 00:00:00','selkov.jpg','Сельков Николай Никитич (1911–1992), специалист в области лексикографии, синтаксиса, диалектологии коми языка.','','/var/www/html/vm/img/selkov-432.jpg','/vm/img/selkov-432.jpg',0,'2024-03-29 09:49:41',2),(12,'1934-09-30 00:00:00','loskutova.jpg','Лоскутова Нина Ильинична (1934–2023), специалист в области диалектологии и лексикографии коми языка.','','/var/www/html/vm/img/loskutova-751.jpg','/vm/img/loskutova-751.jpg',0,'2024-03-29 09:58:15',2),(13,'1933-07-29 00:00:00','pavlova.jpg','Павлова Эльба Кирилловна (1933–2013), старший лаборант сектора языка.','','/var/www/html/vm/img/pavlova-875.jpg','/vm/img/pavlova-875.jpg',0,'2024-03-29 10:23:25',2),(14,'1944-03-27 00:00:00','Костромина И.Н.','','','/var/www/html/vm/img/kostromina-443.jpg','/vm/img/kostromina-443.jpg',0,'2024-04-01 08:54:15',2),(15,'1994-03-08 00:00:00','Бараксанов Г.Г.','','','/var/www/html/vm/img/baraksanov-162.jpg','/vm/img/baraksanov-162.jpg',0,'2024-04-02 06:59:26',2);
/*!40000 ALTER TABLE `file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `file_event`
--

DROP TABLE IF EXISTS `file_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `file_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idEvent` int(11) NOT NULL,
  `idFile` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idEvent` (`idEvent`),
  KEY `idFile` (`idFile`),
  CONSTRAINT `file_event_ibfk_2` FOREIGN KEY (`idEvent`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `file_event_ibfk_1` FOREIGN KEY (`idFile`) REFERENCES `file` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `file_event`
--

LOCK TABLES `file_event` WRITE;
/*!40000 ALTER TABLE `file_event` DISABLE KEYS */;
INSERT INTO `file_event` (`id`, `idEvent`, `idFile`) VALUES (3,11,9);
/*!40000 ALTER TABLE `file_event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `file_person`
--

DROP TABLE IF EXISTS `file_person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `file_person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idFile` int(11) NOT NULL,
  `idPerson` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idFile` (`idFile`),
  KEY `idPerson` (`idPerson`),
  CONSTRAINT `file_person_ibfk_2` FOREIGN KEY (`idPerson`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `file_person_ibfk_1` FOREIGN KEY (`idFile`) REFERENCES `file` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `file_person`
--

LOCK TABLES `file_person` WRITE;
/*!40000 ALTER TABLE `file_person` DISABLE KEYS */;
INSERT INTO `file_person` (`id`, `idFile`, `idPerson`) VALUES (5,8,5),(7,12,15),(8,14,17),(9,15,20);
/*!40000 ALTER TABLE `file_person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person`
--

DROP TABLE IF EXISTS `person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `F` varchar(45) DEFAULT NULL,
  `I` varchar(45) DEFAULT NULL,
  `O` varchar(45) DEFAULT NULL,
  `comment` text COMMENT 'Аннотация',
  `dol` text COMMENT 'должность',
  `dayN` date DEFAULT NULL COMMENT 'дата рождения',
  `dayD` date DEFAULT NULL COMMENT 'дата смерти',
  `create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `create_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `person`
--

LOCK TABLES `person` WRITE;
/*!40000 ALTER TABLE `person` DISABLE KEYS */;
INSERT INTO `person` (`id`, `F`, `I`, `O`, `comment`, `dol`, `dayN`, `dayD`, `create_date`, `create_user`) VALUES (5,'Сорвачева ','Валентина ','Александровна ','Специалист в области диалектологии и лексикографии коми языка, внесла большой вклад в развитие коми диалектологии. Долгое время руководила плановой темой «Изучение и монографическое описание диалектов коми языка». В многочисленных экспедициях в разные районы Коми АССР ею был собран богатейший языковой материал, хранящийся в настоящее время в научном архиве Коми НЦ УрО РАН, изучена языковая специфика верхневычегодского, нижневычегодского, удорского диалектов. Собранные ученым языковые материалы легли в основу фундаментальных работ по коми языкознанию: «Сравнительный словарь коми-зырянских диалектов» (1961), «Верхневычегодский диалект коми языка» (1966), «Нижневычегодский диалект коми языка» (1978), «Удорский диалект коми языка» (1990), «Образцы коми-зырянской речи» (1971). В.А. Сорвачева участвовала также в создании учебников для вузов «Современный коми язык. Ч. I. Фонетика. Лексика. Морфология» (1955), «Современный коми язык. Ч. II. Синтаксис» (1967), составила карту','кандидат филологических наук, ','1923-02-23','1977-06-12','2024-03-29 08:40:13',2),(12,'Жилина','Татьяна','Ивановна','Специалист в области коми диалектологии и лексикографии, внесла большой вклад в развитие коми диалектологии. Собранные ею в многочисленных экспедициях в бассейнах рек Выми, Вычегды, Сысолы, Лузы, Летки, а также в Зауралье и Прикамье полевые материалы составляют богатейший диалектологический фонд Научного архива Коми НЦ УрО РАН. Многолетние исследования в области диалектологии Т.И. Жилина обобщила в ряде крупных фундаментальных работ: ею подготовлены описательные монографии по присыктывкарскому, верхнесысольскому, лузско-летскому и вымскому диалектам коми языка, составлены словари диалектной лексики, образцы диалектной речи. Заслуживает признания деятельность Т.И. Жилиной как лексикографа: она является одним из авторов-составителей и редакторов «Русско-коми словаря» (1966) и «Сравнительного словаря коми-зырянских диалектов» (1961). Татьяна Ивановна активно участвовала в создании учебников для вузов «Современный коми язык.Ч I. Фонетика. Лексика. Морфология» (1955), «Современный коми язык','кандидат филологических наук, старший научный сотрудник','1923-12-14',NULL,'2024-03-29 09:30:30',2),(13,'Гуляев ','Евгений ','Семенович ','Специалист в области истории языка, исторической и описательной лексикологии, диалектологии, грамматики коми языка, куратоведения. Е.С. Гуляев исследовал семантику и историческое развитие группы падежей (элатива, эгрессива, аблатива, компаратива) коми языка, подробно рассмотрел проблемы формирования словарного состава коми языка, выявив этимологии и происхождение слов, основные этапы становления коми-зырянского литературного языка. Он является соавтором краткого коми этимологического словаря. Е.С. Гуляев принимал участие в сборе, обработке диалектного материала, описании особенностей верхневычегодского и верхнесысольского диалектов. Занимался изучением поэтического наследия И.А. Куратова, подготовил к изданию полное собрание художественных произведений поэта (И. Куратов. Менам муза. Сыктывкар, 1979). Участвовал в работе III и IV Международных конгрессов финно-угроведов (Таллин, 1970, Будапешт, 1975). Е.С. Гуляев читал в Коми государственном педагогическом институте курсы истории коми языка и диалектологии, был научным руководителем при написании нескольких диссертаций. Исследователь являлся членом Советского комитета финно-угроведов, членом редколлегии журнала “Советское финно-угроведение”, участвовал в подготовке и проведении XI Всесоюзной конференции по финно-угроведению (Сыктывкар, 1965), III Международного конгресса финно-угроведов (Таллин, 1970). ','кандидат филологических наук, старший научный сотрудник','1928-03-28','1977-04-05','2024-03-29 09:38:50',2),(14,'Сельков ','Николай ','Никитич ','Специалист в области лексикографии, синтаксиса, диалектологии коми языка. Ученый собрал большой диалектный материал во время многочисленных экспедиций, что позволило затем написать подробные описания ижемского и печорского диалектов, быть одними из составителей сравнительного словаря коми-зырянских диалектов. Он был соавтором орфографического (1976, 1985) и большого русско-коми словаря (1966). С 1961 г. ученый работал в качестве составителя и редактора крупного коллективного труда по синтаксису коми языка, который до сих пор является единственным обобщающим учебником для вузов по синтаксису коми языка. Н.Н. Сельков внес значительный вклад в организацию преподавательской работы в школах и вузах. Он автор учебников коми языка для 6–7 и 7–8 классов, учебника для 6–8 классов; им составлены правила коми орфоэпии, программы по коми языку для начальной школы и для 5–7 классов, методические разработки по коми грамматике. Награжден медалями «За оборону Ленинграда» (1942), «За победу над Германией в Великой Отечественной войне» (1945), Почетными грамотами Коми филиала АН СССР (1970), Президиума Верховного Совета Коми АССР (1971). ','научный сотрудник','1911-08-15','1992-11-11','2024-03-29 09:49:50',2),(15,'Лоскутова ','Нина ','Ильинична ','Специалист в области диалектологии и лексикографии коми языка. Внесла весомый вклад в развитие коми филологической науки. Нина Ильинична была участником многочисленных экспедиций по сбору диалектного материала, совершила более полутора десятков выездов в Печорский, Усть-Куломский, Корткеросский, Сыктывдинский, Сысольский, Прилузский районы Коми АССР, Ненецкий автономный округ. Лингвистический материал, собранный, обработанный и систематизированный при участии Н.И. Лоскутовой, лег в основу монографий по верхневычегодскому, присыктывкарскому, верхнесысольскому, печорскому, среднесысольскому и лузско-летскому диалектам коми языка.За многолетний труд, большие заслуги в развитии коми языкознания награждена медалями «За трудовое отличие» и «Ветеран труда», Почетными грамотами Президиума Академии наук СССР в связи с 250-летием АН СССР, Президиума Коми филиала АН СССР.','научный сотрудник','1934-09-30','2023-07-10','2024-03-29 09:56:26',2),(16,'Павлова  ','Эльба','Кирилловна ','Старший лаборант сектора языка, принимала участие в экспедициях по сбору фольклорного и диалектного материала, в обработке и систематизации материала для монографий по верхневычегодскому, печорскому, нижневычегодскому, удорскому диалектам.','Старший лаборант','1933-07-29','2013-01-01','2024-03-29 10:20:49',2),(17,'Костромина ','Ия','Николаевна','Костромина Ия Николаевна - специалист в области лексикологии, истории коми языкознания. Она ввела в научный оборот архивные материалы о жизни и творчестве исследователей коми языка XIX в., написав биографические очерки о Г.С. Лыткине, П.И. Савваитове, А.Е. Попове, Н.П. Попове, А.В. Красове, П. Михайлове, А.В. Журавском, И.И. Разманове, А.Ф. Флёрове, А.М. Шёгрене. В области лексикологии ею исследован словарный состав коми языка с точки зрения активного и пассивного запаса (неологизмы и архаизмы). И.Н. Костромина участвовала в сборе материала для “Краткого этимологического словаря коми языка” (авторы В.И.Лыткин, Е.С.Гуляев), монографии «Нижневычегодский диалект коми языка» (автор В.А.Сорвачева), собрания сочинений И.А. Куратова «Менам муза» (1979). Она является одним из авторов коллективной монографии “Современный коми язык. Лексикология” (1985). ','лаборант','1931-12-04','2012-04-03','2024-04-01 08:51:39',2),(19,'Коснырева ','Раиса ','Ивановна ','Область научных интересов Косныревой Р.И.  включает изучение диалектной и литературной коми лексики, составление словарей различных типов, рассмотрение имен собственных и языка произведений некоторых коми писателей в научно-популярных статьях. Р.И. Коснырева неоднократно выезжала в полевые экспедиции в разные районы республики, собрала богатый диалектный материал, который в виде многотомных отчетов хранится в научном архиве Коми НЦ УрО РАН. Совместно с известным ученым-фольклористом Ф.В. Плесовским она работала над изданием книг «Коми фразеологизмы», «Коми пословицы и поговорки», «Коми народные загадки», привлекая богатый материал собственных полевых записей.','лаборант','1935-06-18',NULL,'2024-04-01 08:57:18',2),(20,'Бараксанов ','Геннадий ','Григорьевич ','Бараксанов Геннадий Григорьевич, специалист в области коми диалектологии, исследования норм литературного языка, автор многих учебников коми языка для средней школы. Ученый внес существенный вклад в изучение и описание коми диалектов. Он неоднократно выезжал в диалектологические экспедиции в различные районы республики Коми, на Кольский полуостров, в Омскую область. В соавторстве с коллегами ученый подготовил описательные монографии по присыктывкарскому и среднесысольскому диалектам. Г.Г. Бараксанов был одним из организаторов нескольких крупных научных форумов в Сыктывкаре: ХI и ХVI Всесоюзных конференций финно-угроведов, VI Международного конгресса финно-угроведов и др. Большое внимание исследователь уделял преподаванию коми языка в школе и вузе. Он – автор учебников для средних школ, учебных программ. В 1972 г. в ходе создания Сыктывкарского государственного университета Г.Г. Бараксанов большие усилия приложил для организации кафедры коми языка и литературы, разрабатывал и читал основные курсы по коми языку (фонетика, лексикология, введение в финно-угроведение и др.), руководил курсовыми и дипломными работами студентов.','кандидат филологических наук, старший научный сотрудник','1934-09-12','1997-03-16','2024-04-02 06:57:28',2);
/*!40000 ALTER TABLE `person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person_event`
--

DROP TABLE IF EXISTS `person_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `person_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idEvent` int(11) NOT NULL,
  `idPerson` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idEvent` (`idEvent`),
  KEY `idPerson` (`idPerson`),
  CONSTRAINT `person_event_ibfk_2` FOREIGN KEY (`idPerson`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `person_event_ibfk_1` FOREIGN KEY (`idEvent`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `person_event`
--

LOCK TABLES `person_event` WRITE;
/*!40000 ALTER TABLE `person_event` DISABLE KEYS */;
INSERT INTO `person_event` (`id`, `idEvent`, `idPerson`) VALUES (3,4,13),(4,11,5),(5,11,12);
/*!40000 ALTER TABLE `person_event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sci_department`
--

DROP TABLE IF EXISTS `sci_department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sci_department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `owner` varchar(10) DEFAULT NULL COMMENT 'Поле ID владельца (допускается значение индексов через запятую) (т.к. та или иная структура существовала определённый период)',
  `Date_create` date NOT NULL COMMENT 'дата создания',
  `Date_dectroy` date DEFAULT NULL COMMENT 'дата ликвидации',
  `create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'системная дата и время СОЗДАНИЯ ЗАПИСИ!',
  `create_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COMMENT='Научная тематика';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sci_department`
--

LOCK TABLES `sci_department` WRITE;
/*!40000 ALTER TABLE `sci_department` DISABLE KEYS */;
INSERT INTO `sci_department` (`id`, `Name`, `owner`, `Date_create`, `Date_dectroy`, `create_date`, `create_user`) VALUES (17,'ИЯЛИ Коми НЦ УрО РАН',NULL,'1970-04-01',NULL,'2024-03-29 08:34:44',2);
/*!40000 ALTER TABLE `sci_department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sci_department_event`
--

DROP TABLE IF EXISTS `sci_department_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sci_department_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idSciDepartment` int(11) NOT NULL,
  `idEvent` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idSciDepartment` (`idSciDepartment`),
  KEY `idEvent` (`idEvent`),
  CONSTRAINT `sci_department_event_ibfk_2` FOREIGN KEY (`idEvent`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sci_department_event_ibfk_1` FOREIGN KEY (`idSciDepartment`) REFERENCES `sci_department` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sci_department_event`
--

LOCK TABLES `sci_department_event` WRITE;
/*!40000 ALTER TABLE `sci_department_event` DISABLE KEYS */;
INSERT INTO `sci_department_event` (`id`, `idSciDepartment`, `idEvent`) VALUES (3,17,3),(4,17,4),(5,17,5),(6,17,6),(7,17,7),(8,17,11);
/*!40000 ALTER TABLE `sci_department_event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sci_department_person`
--

DROP TABLE IF EXISTS `sci_department_person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sci_department_person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idSciDepartment` int(11) NOT NULL,
  `idPerson` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idSciDepartment` (`idSciDepartment`),
  KEY `idPerson` (`idPerson`),
  CONSTRAINT `sci_department_person_ibfk_2` FOREIGN KEY (`idPerson`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sci_department_person_ibfk_1` FOREIGN KEY (`idSciDepartment`) REFERENCES `sci_department` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sci_department_person`
--

LOCK TABLES `sci_department_person` WRITE;
/*!40000 ALTER TABLE `sci_department_person` DISABLE KEYS */;
INSERT INTO `sci_department_person` (`id`, `idSciDepartment`, `idPerson`) VALUES (4,17,17),(6,17,19),(7,17,20);
/*!40000 ALTER TABLE `sci_department_person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sci_theme`
--

DROP TABLE IF EXISTS `sci_theme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sci_theme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_user` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COMMENT='Научная тематика';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sci_theme`
--

LOCK TABLES `sci_theme` WRITE;
/*!40000 ALTER TABLE `sci_theme` DISABLE KEYS */;
INSERT INTO `sci_theme` (`id`, `Name`, `create_date`, `create_user`) VALUES (1,'Химические науки','2024-03-25 13:58:11',2),(2,'Физические науки','2024-03-25 13:58:18',2),(3,'Геологические науки','2024-03-25 13:58:31',2),(4,'Биологические науки','2024-03-25 13:58:37',2),(7,'Исторические науки','2024-03-27 07:24:42',2),(8,'Филологические науки','2024-03-27 07:25:12',2);
/*!40000 ALTER TABLE `sci_theme` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sci_theme_event`
--

DROP TABLE IF EXISTS `sci_theme_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sci_theme_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idTheme` int(11) NOT NULL,
  `idEvent` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idTheme` (`idTheme`),
  KEY `idEvent` (`idEvent`),
  CONSTRAINT `sci_theme_event_ibfk_3` FOREIGN KEY (`idTheme`) REFERENCES `sci_theme` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sci_theme_event_ibfk_2` FOREIGN KEY (`idEvent`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sci_theme_event`
--

LOCK TABLES `sci_theme_event` WRITE;
/*!40000 ALTER TABLE `sci_theme_event` DISABLE KEYS */;
INSERT INTO `sci_theme_event` (`id`, `idTheme`, `idEvent`) VALUES (3,8,2),(4,8,3),(5,8,4),(6,1,11),(7,2,11);
/*!40000 ALTER TABLE `sci_theme_event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sci_theme_file`
--

DROP TABLE IF EXISTS `sci_theme_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sci_theme_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idFile` int(11) NOT NULL,
  `idSciTheme` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idFile` (`idFile`),
  KEY `idSciTheme` (`idSciTheme`),
  CONSTRAINT `sci_theme_file_ibfk_1` FOREIGN KEY (`idFile`) REFERENCES `file` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sci_theme_file_ibfk_2` FOREIGN KEY (`idSciTheme`) REFERENCES `sci_theme` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sci_theme_file`
--

LOCK TABLES `sci_theme_file` WRITE;
/*!40000 ALTER TABLE `sci_theme_file` DISABLE KEYS */;
INSERT INTO `sci_theme_file` (`id`, `idFile`, `idSciTheme`) VALUES (6,8,8),(7,9,8),(8,10,8),(9,11,8),(10,12,8),(11,13,8),(12,14,8);
/*!40000 ALTER TABLE `sci_theme_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sci_theme_pers`
--

DROP TABLE IF EXISTS `sci_theme_pers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sci_theme_pers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idTheme` int(11) NOT NULL,
  `idPers` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idPers` (`idPers`),
  KEY `idTheme` (`idTheme`),
  CONSTRAINT `sci_theme_pers_ibfk_3` FOREIGN KEY (`idTheme`) REFERENCES `sci_theme` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sci_theme_pers_ibfk_2` FOREIGN KEY (`idPers`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sci_theme_pers`
--

LOCK TABLES `sci_theme_pers` WRITE;
/*!40000 ALTER TABLE `sci_theme_pers` DISABLE KEYS */;
INSERT INTO `sci_theme_pers` (`id`, `idTheme`, `idPers`) VALUES (4,8,5),(11,8,12),(12,8,13),(13,8,14),(14,8,15),(15,8,16),(16,8,17),(18,8,19),(19,8,20);
/*!40000 ALTER TABLE `sci_theme_pers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tag`
--

DROP TABLE IF EXISTS `tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(20) DEFAULT NULL,
  `create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `create_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag_Name_uindex` (`Name`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tag`
--

LOCK TABLES `tag` WRITE;
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;
INSERT INTO `tag` (`id`, `Name`, `create_date`, `create_user`) VALUES (1,'физика','2024-03-23 16:41:44',1),(2,'химия','2024-03-23 16:42:17',1),(3,'математика','2024-03-23 16:47:32',1),(7,'биология','2024-03-25 20:52:11',1),(9,'история','2024-03-27 07:25:24',2),(10,'финно-угорские языки','2024-03-27 07:25:45',2),(11,'литература','2024-03-29 08:19:53',2),(12,'фольклор','2024-03-29 08:19:58',2),(13,'археология','2024-03-29 08:20:04',2),(14,'этнография','2024-03-29 08:20:11',2);
/*!40000 ALTER TABLE `tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tag_event`
--

DROP TABLE IF EXISTS `tag_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tag_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idTag` int(11) NOT NULL,
  `idEvent` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idTag` (`idTag`),
  KEY `idEvent` (`idEvent`),
  CONSTRAINT `tag_event_ibfk_2` FOREIGN KEY (`idTag`) REFERENCES `tag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tag_event_ibfk_1` FOREIGN KEY (`idEvent`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tag_event`
--

LOCK TABLES `tag_event` WRITE;
/*!40000 ALTER TABLE `tag_event` DISABLE KEYS */;
INSERT INTO `tag_event` (`id`, `idTag`, `idEvent`) VALUES (2,10,2),(3,9,6),(4,9,7),(5,11,11),(6,3,11);
/*!40000 ALTER TABLE `tag_event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tag_file`
--

DROP TABLE IF EXISTS `tag_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tag_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idTag` int(11) NOT NULL,
  `idFile` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idTag` (`idTag`),
  KEY `idFile` (`idFile`),
  CONSTRAINT `tag_file_ibfk_2` FOREIGN KEY (`idFile`) REFERENCES `file` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tag_file_ibfk_1` FOREIGN KEY (`idTag`) REFERENCES `tag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tag_file`
--

LOCK TABLES `tag_file` WRITE;
/*!40000 ALTER TABLE `tag_file` DISABLE KEYS */;
INSERT INTO `tag_file` (`id`, `idTag`, `idFile`) VALUES (6,10,8),(7,10,9),(8,10,10),(9,10,11),(10,10,12),(11,10,13),(12,10,14);
/*!40000 ALTER TABLE `tag_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tag_person`
--

DROP TABLE IF EXISTS `tag_person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tag_person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idTag` int(11) NOT NULL,
  `idPerson` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idTag` (`idTag`),
  KEY `idUser` (`idPerson`),
  KEY `idPerson` (`idPerson`),
  CONSTRAINT `tag_person_ibfk_2` FOREIGN KEY (`idTag`) REFERENCES `tag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tag_person_ibfk_1` FOREIGN KEY (`idPerson`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tag_person`
--

LOCK TABLES `tag_person` WRITE;
/*!40000 ALTER TABLE `tag_person` DISABLE KEYS */;
INSERT INTO `tag_person` (`id`, `idTag`, `idPerson`) VALUES (5,10,5),(12,10,12),(13,10,13),(14,10,14),(15,10,15),(16,10,16),(17,10,17),(19,10,19),(20,11,20),(21,10,20);
/*!40000 ALTER TABLE `tag_person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` int(11) DEFAULT NULL,
  `FIO` varchar(200) DEFAULT NULL,
  `login` varchar(20) DEFAULT NULL,
  `pass` varchar(200) DEFAULT NULL,
  `online` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `role`, `FIO`, `login`, `pass`, `online`) VALUES (1,1,'Хохлов Роман Николаевич','hohlov','fdc7024e6ac8917bc1eb2a5878009c8047f805e28aef2acfb73b1a68165171482910cd186a9a47d1487eaeb03e98939c954e1c77aece4e02c459726199ac2f67','2024-04-02 14:05:47'),(2,2,'Мусанов Алексей','musanov','1b753e317766273dfef78e53d68e40462273b5d4679745add554c9b581d6e2dc19eee9713d69ad812061c72a45ac1a530b226246ba05c50506c5b742f296b9e1','2024-04-02 07:47:06'),(3,2,'Кирпичёв Алексей Николаевич','kirpichev','77734b923e3274df222894c6e03817d9873f59afc069b7471f8a259752457969b37880fa4bfa726f2861ea16bf1a32b040c2f1bf0c715f0069ae2bed08539a3e','0000-00-00 00:00:00');
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

-- Dump completed on 2024-04-02 17:05:52