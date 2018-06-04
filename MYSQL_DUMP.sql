-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 04 2018 г., 14:57
-- Версия сервера: 5.7.16-log
-- Версия PHP: 7.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `dd`
--

-- --------------------------------------------------------

--
-- Структура таблицы `aliases`
--

CREATE TABLE `aliases` (
  `id` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `link` longtext,
  `uri` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `date_create` varchar(255) DEFAULT NULL,
  `date_follow` varchar(255) DEFAULT NULL,
  `folow` varchar(255) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `aliases`
--

INSERT INTO `aliases` (`id`, `user_id`, `name`, `link`, `uri`, `module`, `action`, `date_create`, `date_follow`, `folow`) VALUES
('15280198059080', '15280193584031', 'admin', 'http://d.d/admin', 'bqOtjkGwPg', 'Main', 'Get', '1528019805', '1528019805', '0'),
('15279620599361', '2', 'files_bl', 'http://d.d/bwRYCWJtqg', 'bRKiVZHuHmk', 'Main', 'Get', '1527962059', '1527962059', '0'),
('15280236325289', '1', 'ghjghj', 'http://d.d/admin', 'bVKElzPJkTA', 'Main', 'Get', '1528023632', '1528023632', '0'),
('15280237476773', '2', 'admin', 'http://d.d/bRKiVZHuHmk', 'bwRYCWJtqg', 'Main', 'Get', '1528023747', '1528023747', '0'),
('15280325356786', '1', NULL, 'http://d.d/admin', 'bwUIcomGmw', 'Main', 'Get', '1528032535', '1528032535', '0'),
('1527854322318', '15275184814672', NULL, 'http://d.d/admin', 'bXQGVbyi', 'Main', 'Get', '1527546686', '1527546686', '0');

-- --------------------------------------------------------

--
-- Структура таблицы `menu`
--

CREATE TABLE `menu` (
  `id` varchar(255) DEFAULT NULL,
  `link_uri` varchar(255) DEFAULT NULL,
  `link_name` varchar(255) DEFAULT NULL,
  `optios` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `stat`
--

CREATE TABLE `stat` (
  `id` varchar(255) NOT NULL,
  `link_id` varchar(255) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `date_follow` varchar(255) NOT NULL,
  `date` varchar(255) DEFAULT NULL,
  `count` varchar(255) NOT NULL DEFAULT '0',
  `link_name` varchar(255) DEFAULT NULL,
  `link_uri` varchar(255) DEFAULT NULL,
  `link_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `stat`
--

INSERT INTO `stat` (`id`, `link_id`, `user_id`, `date_follow`, `date`, `count`, `link_name`, `link_uri`, `link_link`) VALUES
('15278783435757', '1527854322318', '15275184814672', '1527825600', '1527878343', '52', NULL, 'bXQGVbyi', 'http://d.d/admin'),
('15278794793674', '1527854322318', '15275184814672', '1', '1527879479', '6', NULL, 'bXQGVbyi', 'http://d.d/admin'),
('15279491826494', '1527854322318', '15275184814672', '1527912000', '1527949182', '1', NULL, 'bXQGVbyi', 'http://d.d/admin'),
('15279814623225', '15279620599361', '1', '1527973200', '1527981462', '368', 'files_bl', 'bRKiVZHuHmk', 'http://d.d/admin-ko'),
('15280202569741', '15279620599361', '1', '1527998400', '1528020256', '3', 'files_bl', 'bRKiVZHuHmk', 'http://d.d/bIatuljxrhSE'),
('15280360666561', '15280237476773', '1', '1527973200', '1528036066', '369', 'admin', 'bwRYCWJtqg', 'http://d.d/bRKiVZHuHmk'),
('15280596144951', '15279620599361', '2', '1528059600', '1528059614', '755', 'files_bl', 'bRKiVZHuHmk', 'http://d.d/bwRYCWJtqg'),
('15280596465472', '15280237476773', '2', '1528059600', '1528059646', '751', 'admin', 'bwRYCWJtqg', 'http://d.d/bRKiVZHuHmk');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` varchar(255) NOT NULL,
  `login` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` longtext NOT NULL,
  `admin` int(1) DEFAULT NULL,
  `deleted` int(1) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `f_name` varchar(255) DEFAULT NULL,
  `l_name` varchar(255) DEFAULT NULL,
  `date_log` varchar(255) DEFAULT NULL,
  `date_reg` varchar(255) DEFAULT NULL,
  `lang` varchar(11) NOT NULL DEFAULT 'ru_RU',
  `time_zone` varchar(255) NOT NULL DEFAULT 'Europe/Kiev'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `email`, `password`, `admin`, `deleted`, `alias`, `f_name`, `l_name`, `date_log`, `date_reg`, `lang`, `time_zone`) VALUES
('1', 'admin', 'admin@br.r', 'a98d9ea2e198ab0733d1e28ae673e0c6', 1, NULL, 'admin', 'admin', 'petrovich', '1528112813', '1526763317', 'ru_RU', 'America/Indiana/Marengo'),
('15280193584031', 'admin', 'admin@br.ry', 'a98d9ea2e198ab0733d1e28ae673e0c6', NULL, NULL, NULL, 'qwerty', 'qwertovich', '1528019358', '1528019358', 'ru_RU', 'Europe/Kiev');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `aliases`
--
ALTER TABLE `aliases`
  ADD UNIQUE KEY `uri` (`uri`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `stat`
--
ALTER TABLE `stat`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `link_id` (`link_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `alias` (`alias`),
  ADD UNIQUE KEY `email` (`email`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
