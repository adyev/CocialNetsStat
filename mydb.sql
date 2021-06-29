-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Июн 04 2021 г., 05:12
-- Версия сервера: 10.4.14-MariaDB
-- Версия PHP: 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `mydb`
--

-- --------------------------------------------------------

--
-- Структура таблицы `owners`
--

CREATE TABLE `owners` (
  `Last_Post_Date` int(11) DEFAULT NULL,
  `Owner_Id` int(11) NOT NULL,
  `Owner_Name` varchar(30) NOT NULL,
  `Vk_Link` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `owners`
--

INSERT INTO `owners` (`Last_Post_Date`, `Owner_Id`, `Owner_Name`, `Vk_Link`) VALUES
(1620121231, 214555857, 'Адыев Дмитрий', 'https://vk.com/marinerdemonkiller2013'),
(1622715473, -203956587, 'test_groop', 'https://vk.com/public203956587'),
(1621688319, -204656959, 'test group 2', 'https://vk.com/club204656959');

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE `posts` (
  `Owner_Id` int(11) NOT NULL,
  `Owner_Name` varchar(50) NOT NULL,
  `Vk_Link` varchar(50) NOT NULL,
  `Is_Posted` tinyint(4) NOT NULL DEFAULT 0,
  `Post_Id` int(11) NOT NULL,
  `Photo_Srcs` varchar(500) NOT NULL,
  `Publish_Date` date NOT NULL DEFAULT '2021-06-03',
  `Likes` int(11) NOT NULL,
  `Comments` int(11) NOT NULL,
  `Reposts` int(11) NOT NULL,
  `Text` varchar(10000) NOT NULL,
  `Last_Bitrix_Publish` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `posts`
--

INSERT INTO `posts` (`Owner_Id`, `Owner_Name`, `Vk_Link`, `Is_Posted`, `Post_Id`, `Photo_Srcs`, `Publish_Date`, `Likes`, `Comments`, `Reposts`, `Text`, `Last_Bitrix_Publish`) VALUES
(214555857, 'Адыев Дмитрий', ' http://vk.com/wall214555857_736', 1, 736, '', '2021-05-04', 0, 0, 0, '', '2021-06-04'),
(214555857, 'Адыев Дмитрий', ' http://vk.com/wall214555857_734', 1, 734, 'https://sun9-50.userapi.com/impg/7Y6qcE6WZMmWbvSBeuUG2kX1tHZw9KbbC17weg/QMpX6w43Bao.jpg?size=510x340&quality=96&crop=150,0,1620,1080&sign=47d32aed0069168add20381805998038&c_uniq_tag=pqDCjCD1ytS2kvQ5l0LtQdyXRvVNjeVLuhKDnwnF1x4&type=album ', '2020-10-30', 2, 0, 0, '', '2021-06-04'),
(214555857, 'Адыев Дмитрий', ' http://vk.com/wall214555857_704', 1, 704, 'https://sun9-6.userapi.com/impf/c855636/v855636876/10d6f2/-i04ljYKn0I.jpg?size=510x340&quality=96&crop=150,0,1620,1080&sign=89f26a9c97b70b12ee4d325c18e61c61&c_uniq_tag=CRv4nSRGuKQmlYj5dUOLbSqHbo5vvXBBQ8nKCgg3_qk&type=album ', '2019-10-02', 5, 1, 0, '', '2021-06-04'),
(214555857, 'Адыев Дмитрий', ' http://vk.com/wall214555857_702', 0, 702, 'https://sun9-70.userapi.com/impf/c850520/v850520769/1cc96c/LwVOcv9B1f0.jpg?size=510x340&quality=96&crop=150,0,1620,1080&sign=198c7514fc65bf936359ed717dabf6fb&c_uniq_tag=oAk8GNS4Ef1pdv5QeBGdlVF8YL7HUuQ707XdfV_W1pc&type=album ', '2019-10-01', 1, 0, 0, '', NULL),
(214555857, 'Адыев Дмитрий', ' http://vk.com/wall214555857_699', 0, 699, 'https://sun9-48.userapi.com/impf/c854520/v854520243/c2857/gH2nc44EhQY.jpg?size=510x382&quality=96&sign=0f88d4ce32aab2af8ecd57c48e9a1eba&c_uniq_tag=dRVujwmalwsadQlCbMnEqFoisKJgoFhxX3aFHj8HMVg&type=album ', '2019-08-17', 2, 0, 1, 'Вид из балкона отеля)', NULL),
(-203956587, 'test_groop', ' http://vk.com/wall-203956587_13', 1, 13, '', '2021-06-03', 0, 0, 0, '213', '2021-06-04'),
(-203956587, 'test_groop', ' http://vk.com/wall-203956587_6', 1, 6, 'https://sun9-50.userapi.com/impg/7Y6qcE6WZMmWbvSBeuUG2kX1tHZw9KbbC17weg/QMpX6w43Bao.jpg?size=510x340&quality=96&crop=150,0,1620,1080&sign=47d32aed0069168add20381805998038&c_uniq_tag=pqDCjCD1ytS2kvQ5l0LtQdyXRvVNjeVLuhKDnwnF1x4&type=album ', '2021-05-22', 1, 6, 0, '', '2021-06-04'),
(-203956587, 'test_groop', ' http://vk.com/wall-203956587_5', 0, 5, '', '2021-04-15', 1, 0, 1, 'test2', NULL),
(-203956587, 'test_groop', ' http://vk.com/wall-203956587_1', 0, 1, 'https://sun9-10.userapi.com/impf/c626831/v626831857/3f735/ypZBmwQvS-Q.jpg?size=200x200&quality=96&sign=35286604d8efd1101a5cfb84a699de03&c_uniq_tag=OiAYeb1UmRwX2QOXWZN1mlyx9Ty3-FrdzFLvfSWUcs4&type=album ', '2021-04-13', 1, 2, 0, 'test', NULL),
(-204656959, 'test group 2', ' http://vk.com/wall-204656959_4', 1, 4, 'https://sun9-50.userapi.com/impg/7Y6qcE6WZMmWbvSBeuUG2kX1tHZw9KbbC17weg/QMpX6w43Bao.jpg?size=510x340&quality=96&crop=150,0,1620,1080&sign=47d32aed0069168add20381805998038&c_uniq_tag=pqDCjCD1ytS2kvQ5l0LtQdyXRvVNjeVLuhKDnwnF1x4&type=album https://sun9-6.userapi.com/impf/c855636/v855636876/10d6f2/-i04ljYKn0I.jpg?size=510x340&quality=96&crop=150,0,1620,1080&sign=89f26a9c97b70b12ee4d325c18e61c61&c_uniq_tag=CRv4nSRGuKQmlYj5dUOLbSqHbo5vvXBBQ8nKCgg3_qk&type=album ', '2021-05-22', 1, 2, 0, 'test post 2', '2021-06-04'),
(-204656959, 'test group 2', ' http://vk.com/wall-204656959_1', 0, 1, 'https://sun9-63.userapi.com/impf/c855636/v855636028/59b65/hAyzB7lYdRk.jpg?size=510x340&quality=96&crop=150,0,1620,1080&sign=d2c68f414a9c2a5d30ac81173a20aaf9&c_uniq_tag=wP4GFZ402mK_Cd1cWVhElLTen7SvfZHNsdjbO24dAZQ&type=album https://sun9-60.userapi.com/impf/c851236/v851236134/187417/xZyzBirYBz0.jpg?size=510x340&quality=96&crop=150,0,1620,1080&sign=4de27cf3de06ba46069a0eb7e78eeac2&c_uniq_tag=u85s5QBlqAVxUKViyb6b0TuWbF9ukquy8WegOlpmxqU&type=album ', '2021-05-19', 1, 2, 0, 'test post 1', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
