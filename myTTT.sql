CREATE DATABASE  IF NOT EXISTS `myTTT` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `myTTT`;

DROP TABLE IF EXISTS `Game`;
CREATE TABLE `Game`
(
    `ID` int(11) NOT NULL AUTO_INCREMENT,
    `status` enum('not active','initialized','started','ended','aborded') NOT NULL DEFAULT 'not active',
    `playerx` varchar(100),
    `playery` varchar(100),
    `result` enum('X','O','D') DEFAULT NULL,
    `last_change` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
	`turn`  enum('X','O') DEFAULT 'X',
	PRIMARY KEY (`ID`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Players`;
CREATE TABLE `Players` (
    id int NOT NULL AUTO_INCREMENT,
    name varchar(100) DEFAULT NULL,
    won int NOT NULL DEFAULT 0,
    lost int NOT NULL DEFAULT 0,
    PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Moves`;
CREATE TABLE `Moves` (
	id int NOT NULL AUTO_INCREMENT,
	game int,
    player varchar(100),
	posX int,
	posY int,
    PRIMARY KEY (`ID`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;
