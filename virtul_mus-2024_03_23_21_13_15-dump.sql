-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 23 2024 г., 21:12
-- Версия сервера: 8.0.30
-- Версия PHP: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `vm`
--

-- --------------------------------------------------------

--
-- Структура таблицы `event`
--

DROP TABLE IF EXISTS `event`;
CREATE TABLE `event` (
                         `id` int NOT NULL,
                         `create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                         `create_user` int NOT NULL,
                         `Name` varchar(30) DEFAULT NULL,
                         `DateN` varchar(30) DEFAULT NULL,
                         `DateK` varchar(30) DEFAULT NULL,
                         `Desc_short` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'Краткое описание',
                         `Desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci COMMENT 'полное описание',
                         `importance` int DEFAULT NULL COMMENT 'Важность (1-9)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Очистить таблицу перед добавлением данных `event`
--

TRUNCATE TABLE `event`;
-- --------------------------------------------------------

--
-- Структура таблицы `file`
--

DROP TABLE IF EXISTS `file`;
CREATE TABLE `file` (
                        `id` int NOT NULL,
                        `name` text NOT NULL,
                        `path` text NOT NULL,
                        `type` int NOT NULL COMMENT 'тип(? возможно тип фото, документ и проч.)',
                        `disc` varchar(500) NOT NULL COMMENT 'описание (500 символов?)',
                        `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        `create_user` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Очистить таблицу перед добавлением данных `file`
--

TRUNCATE TABLE `file`;
-- --------------------------------------------------------

--
-- Структура таблицы `person`
--

DROP TABLE IF EXISTS `person`;
CREATE TABLE `person` (
                          `id` int NOT NULL,
                          `F` varchar(45) DEFAULT NULL,
                          `I` varchar(45) DEFAULT NULL,
                          `O` varchar(45) DEFAULT NULL,
                          `comment` text COMMENT 'Аннотация',
                          `dol` text COMMENT 'должность'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Очистить таблицу перед добавлением данных `person`
--

TRUNCATE TABLE `person`;
--
-- Дамп данных таблицы `person`
--

INSERT INTO `person` (`id`, `F`, `I`, `O`, `comment`, `dol`) VALUES
    (1, 'Кочев', 'Алексей', 'Михайлович', ' Алексей Михайлович (2.03.1915, с. Пыелдино Усть-Сысольского у. Вологодской губ. – ?), фотограф. С 1929 г. начал работать учеником фотографа в Сыктывкаре, с 1932 г. – фотограф-ретушер государственного фотоателье. В годы Великой Отечественной войны служил на фронте техником по аэрофотосъемке, награжден тремя медалями. В 1945-1946 гг. секретарь партбюро при горисполкоме. 1 июня 1946 г. зачислен сотрудником фотолаборатории Базы Академии наук СССР. Освобожден от занимаемой должности 15 сентября 1952 г. в связи с переводом в редакцию газеты «За новый Север»', 'фотограф');

-- --------------------------------------------------------

--
-- Структура таблицы `sci_field`
--

DROP TABLE IF EXISTS `sci_field`;
CREATE TABLE `sci_field` (
                             `id` int NOT NULL,
                             `Name` varchar(100) NOT NULL,
                             `create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                             `create_user` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Научная тематика';

--
-- Очистить таблицу перед добавлением данных `sci_field`
--

TRUNCATE TABLE `sci_field`;
--
-- Дамп данных таблицы `sci_field`
--

INSERT INTO `sci_field` (`id`, `Name`, `create_date`, `create_user`) VALUES
    (1, '1233', '2024-03-23 18:02:01', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `sci_theme`
--

DROP TABLE IF EXISTS `sci_theme`;
CREATE TABLE `sci_theme` (
                             `id` int NOT NULL,
                             `Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Очистить таблицу перед добавлением данных `sci_theme`
--

TRUNCATE TABLE `sci_theme`;
-- --------------------------------------------------------

--
-- Структура таблицы `sci_theme_event`
--

DROP TABLE IF EXISTS `sci_theme_event`;
CREATE TABLE `sci_theme_event` (
                                   `id` int NOT NULL,
                                   `idTheme` int NOT NULL,
                                   `idEvent` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Очистить таблицу перед добавлением данных `sci_theme_event`
--

TRUNCATE TABLE `sci_theme_event`;
-- --------------------------------------------------------

--
-- Структура таблицы `sci_theme_pers`
--

DROP TABLE IF EXISTS `sci_theme_pers`;
CREATE TABLE `sci_theme_pers` (
                                  `id` int NOT NULL,
                                  `idTheme` int NOT NULL,
                                  `idPers` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Очистить таблицу перед добавлением данных `sci_theme_pers`
--

TRUNCATE TABLE `sci_theme_pers`;
-- --------------------------------------------------------

--
-- Структура таблицы `tag`
--

DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
                       `id` int NOT NULL,
                       `Name` varchar(20) DEFAULT NULL,
                       `create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                       `create_user` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Очистить таблицу перед добавлением данных `tag`
--

TRUNCATE TABLE `tag`;
--
-- Дамп данных таблицы `tag`
--

INSERT INTO `tag` (`id`, `Name`, `create_date`, `create_user`) VALUES
                                                                   (1, 'физика', '2024-03-23 16:41:44', 1),
                                                                   (2, 'химия', '2024-03-23 16:42:17', 1),
                                                                   (3, 'математика', '2024-03-23 16:47:32', 1),
                                                                   (4, 'история', '2024-03-23 16:49:55', 1),
                                                                   (5, 'литература', '2024-03-23 16:55:07', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `tag_event`
--

DROP TABLE IF EXISTS `tag_event`;
CREATE TABLE `tag_event` (
                             `id` int NOT NULL,
                             `idTag` int NOT NULL,
                             `idEvent` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Очистить таблицу перед добавлением данных `tag_event`
--

TRUNCATE TABLE `tag_event`;
-- --------------------------------------------------------

--
-- Структура таблицы `tag_file`
--

DROP TABLE IF EXISTS `tag_file`;
CREATE TABLE `tag_file` (
                            `id` int NOT NULL,
                            `idTag` int NOT NULL,
                            `idFile` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Очистить таблицу перед добавлением данных `tag_file`
--

TRUNCATE TABLE `tag_file`;
-- --------------------------------------------------------

--
-- Структура таблицы `tag_person`
--

DROP TABLE IF EXISTS `tag_person`;
CREATE TABLE `tag_person` (
                              `id` int NOT NULL,
                              `idTag` int NOT NULL,
                              `idPerson` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Очистить таблицу перед добавлением данных `tag_person`
--

TRUNCATE TABLE `tag_person`;
-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
                        `id` int NOT NULL,
                        `role` int DEFAULT NULL,
                        `FIO` varchar(200) DEFAULT NULL,
                        `login` varchar(20) DEFAULT NULL,
                        `pass` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Очистить таблицу перед добавлением данных `user`
--

TRUNCATE TABLE `user`;
--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `role`, `FIO`, `login`, `pass`) VALUES
    (1, 1, 'Хохлов Роман Николаевич', 'hohlov', 'fdc7024e6ac8917bc1eb2a5878009c8047f805e28aef2acfb73b1a68165171482910cd186a9a47d1487eaeb03e98939c954e1c77aece4e02c459726199ac2f67');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `event`
--
ALTER TABLE `event`
    ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `file`
--
ALTER TABLE `file`
    ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `person`
--
ALTER TABLE `person`
    ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `sci_field`
--
ALTER TABLE `sci_field`
    ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `sci_theme`
--
ALTER TABLE `sci_theme`
    ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `sci_theme_event`
--
ALTER TABLE `sci_theme_event`
    ADD PRIMARY KEY (`id`),
  ADD KEY `idTheme` (`idTheme`),
  ADD KEY `idEvent` (`idEvent`);

--
-- Индексы таблицы `sci_theme_pers`
--
ALTER TABLE `sci_theme_pers`
    ADD PRIMARY KEY (`id`),
  ADD KEY `idPers` (`idPers`),
  ADD KEY `idTheme` (`idTheme`);

--
-- Индексы таблицы `tag`
--
ALTER TABLE `tag`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tag_Name_uindex` (`Name`);

--
-- Индексы таблицы `tag_event`
--
ALTER TABLE `tag_event`
    ADD PRIMARY KEY (`id`),
  ADD KEY `idTag` (`idTag`),
  ADD KEY `idEvent` (`idEvent`);

--
-- Индексы таблицы `tag_file`
--
ALTER TABLE `tag_file`
    ADD PRIMARY KEY (`id`),
  ADD KEY `idTag` (`idTag`),
  ADD KEY `idFile` (`idFile`);

--
-- Индексы таблицы `tag_person`
--
ALTER TABLE `tag_person`
    ADD PRIMARY KEY (`id`),
  ADD KEY `idTag` (`idTag`),
  ADD KEY `idUser` (`idPerson`),
  ADD KEY `idPerson` (`idPerson`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `event`
--
ALTER TABLE `event`
    MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `file`
--
ALTER TABLE `file`
    MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `person`
--
ALTER TABLE `person`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `sci_field`
--
ALTER TABLE `sci_field`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `sci_theme`
--
ALTER TABLE `sci_theme`
    MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `sci_theme_event`
--
ALTER TABLE `sci_theme_event`
    MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `sci_theme_pers`
--
ALTER TABLE `sci_theme_pers`
    MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `tag`
--
ALTER TABLE `tag`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `tag_event`
--
ALTER TABLE `tag_event`
    MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `tag_file`
--
ALTER TABLE `tag_file`
    MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `tag_person`
--
ALTER TABLE `tag_person`
    MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `sci_theme_event`
--
ALTER TABLE `sci_theme_event`
    ADD CONSTRAINT `sci_theme_event_ibfk_2` FOREIGN KEY (`idEvent`) REFERENCES `event` (`id`),
  ADD CONSTRAINT `sci_theme_event_ibfk_3` FOREIGN KEY (`idTheme`) REFERENCES `sci_theme` (`id`);

--
-- Ограничения внешнего ключа таблицы `sci_theme_pers`
--
ALTER TABLE `sci_theme_pers`
    ADD CONSTRAINT `sci_theme_pers_ibfk_2` FOREIGN KEY (`idPers`) REFERENCES `person` (`id`),
  ADD CONSTRAINT `sci_theme_pers_ibfk_3` FOREIGN KEY (`idTheme`) REFERENCES `sci_theme` (`id`);

--
-- Ограничения внешнего ключа таблицы `tag_event`
--
ALTER TABLE `tag_event`
    ADD CONSTRAINT `tag_event_ibfk_1` FOREIGN KEY (`idEvent`) REFERENCES `event` (`id`),
  ADD CONSTRAINT `tag_event_ibfk_2` FOREIGN KEY (`idTag`) REFERENCES `tag` (`id`);

--
-- Ограничения внешнего ключа таблицы `tag_file`
--
ALTER TABLE `tag_file`
    ADD CONSTRAINT `tag_file_ibfk_1` FOREIGN KEY (`idTag`) REFERENCES `tag` (`id`),
  ADD CONSTRAINT `tag_file_ibfk_2` FOREIGN KEY (`idFile`) REFERENCES `file` (`id`);

--
-- Ограничения внешнего ключа таблицы `tag_person`
--
ALTER TABLE `tag_person`
    ADD CONSTRAINT `tag_person_ibfk_1` FOREIGN KEY (`idPerson`) REFERENCES `person` (`id`),
  ADD CONSTRAINT `tag_person_ibfk_2` FOREIGN KEY (`idTag`) REFERENCES `tag` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
