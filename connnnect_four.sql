-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2022. Nov 21. 17:47
-- Kiszolgáló verziója: 10.4.22-MariaDB
-- PHP verzió: 8.1.2

CREATE DATABASE IF NOT EXISTS `connnnect_four`;

DROP USER IF EXISTS 't224'@'localhost';

CREATE USER 't224'@'localhost' IDENTIFIED BY 't224';

GRANT USAGE ON *.* TO 't224'@'localhost' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;

GRANT ALL PRIVILEGES ON `t224`.* TO 't224'@'localhost';

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `connnnect_four`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `cf_cells`
--

CREATE TABLE `cf_cells` (
  `cell_state_id` int(11) NOT NULL,
  `game_state` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `cf_moves`
--

CREATE TABLE `cf_moves` (
  `move_index` int(11) NOT NULL,
  `player` varchar(10) NOT NULL,
  `move_column` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `cf_winners`
--

CREATE TABLE `cf_winners` (
  `winner_id` int(11) NOT NULL,
  `player` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `cf_cells`
--
ALTER TABLE `cf_cells`
  ADD PRIMARY KEY (`cell_state_id`);

--
-- A tábla indexei `cf_moves`
--
ALTER TABLE `cf_moves`
  ADD PRIMARY KEY (`move_index`);

--
-- A tábla indexei `cf_winners`
--
ALTER TABLE `cf_winners`
  ADD PRIMARY KEY (`winner_id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `cf_cells`
--
ALTER TABLE `cf_cells`
  MODIFY `cell_state_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `cf_moves`
--
ALTER TABLE `cf_moves`
  MODIFY `move_index` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `cf_winners`
--
ALTER TABLE `cf_winners`
  MODIFY `winner_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
