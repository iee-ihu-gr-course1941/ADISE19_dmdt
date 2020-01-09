-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 09, 2020 at 11:49 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `triliza`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_piece` (IN `x1` TINYINT, IN `y1` TINYINT, IN `p` ENUM('X','O'))  BEGIN


UPDATE board
SET piece=p
WHERE x=x1 AND y=y1;

update game_status set p_turn=if(p='X','O','X');
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkW` ()  NO SQL
BEGIN

CALL draw();
Call check_win();

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `check_win` ()  BEGIN
	declare  c11,c12,c13,c21,c22,c23,c31,c32,c33 char;
    
    select  piece into c11 FROM `board` WHERE X=1 AND Y=1;
    select  piece into c12 FROM `board` WHERE X=1 AND Y=2;
    select  piece into c13 FROM `board` WHERE X=1 AND Y=3;
    select  piece into c21 FROM `board` WHERE X=2 AND Y=1;
    select  piece into c22 FROM `board` WHERE X=2 AND Y=2;
    select  piece into c23 FROM `board` WHERE X=2 AND Y=3;
    select  piece into c31 FROM `board` WHERE X=3 AND Y=1;
    select  piece into c32 FROM `board` WHERE X=3 AND Y=2;
    select  piece into c33 FROM `board` WHERE X=3 AND Y=3;
    
    IF (c11=c12 AND c12=c13)
    OR (c21=c22 AND c22=c23)
    OR (c31=c32 AND c32=c33)
    OR (c11=c21 AND c21=c31)
    OR (c12=c22 AND c22=c32)
    OR (c13=c23 AND c23=c33)
    OR (c11=c22 AND c22=c33)
    OR (c31=c22 AND c22=c13)    
    THEN
    UPDATE game_status
    set status = 'ended';
    UPDATE game_status
    set result=if(p_turn='X','X','O');
        
    end if; 
    
    
    
    
    
    
    
    
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `clean_board` ()  BEGIN

replace into board select * from board_empty;
	update `players` set username=null, token=null;
	update `game_status` set `status`='not active', `p_turn`=null, `result`=null;
    TRUNCATE chat;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `draw` ()  BEGIN
	declare c int;
    select count(*) into c from board where piece IS NULL;
    
    if c=0 THEN
    UPDATE game_status
    set result='D';
    UPDATE game_status
    set status='ended';
    end if;
    
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `rematch` ()  NO SQL
BEGIN

replace into board select * from board_empty;
	update `game_status` set `status`='started', `p_turn`='X', `result`=null;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `board`
--

CREATE TABLE `board` (
  `x` tinyint(1) NOT NULL,
  `y` tinyint(1) NOT NULL,
  `piece` enum('X','O') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `board`
--

INSERT INTO `board` (`x`, `y`, `piece`) VALUES
(1, 1, NULL),
(1, 2, NULL),
(1, 3, 'X'),
(2, 1, NULL),
(2, 2, NULL),
(2, 3, NULL),
(3, 1, NULL),
(3, 2, NULL),
(3, 3, NULL);

--
-- Triggers `board`
--
DELIMITER $$
CREATE TRIGGER `winning` AFTER UPDATE ON `board` FOR EACH ROW call checkW()
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `board_empty`
--

CREATE TABLE `board_empty` (
  `x` tinyint(1) NOT NULL,
  `y` tinyint(1) NOT NULL,
  `piece` enum('X','O') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `board_empty`
--

INSERT INTO `board_empty` (`x`, `y`, `piece`) VALUES
(1, 1, NULL),
(2, 1, NULL),
(3, 1, NULL),
(1, 2, NULL),
(2, 2, NULL),
(3, 2, NULL),
(1, 3, NULL),
(2, 3, NULL),
(3, 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `username` varchar(20) DEFAULT NULL,
  `msg` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `game_status`
--

CREATE TABLE `game_status` (
  `status` enum('not active','initialized','started','ended','aborded') NOT NULL DEFAULT 'not active',
  `p_turn` enum('X','O') DEFAULT NULL,
  `result` enum('X','O','D') DEFAULT NULL,
  `last_change` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `game_status`
--

INSERT INTO `game_status` (`status`, `p_turn`, `result`, `last_change`) VALUES
('started', 'O', NULL, '2020-01-09 22:48:20');

--
-- Triggers `game_status`
--
DELIMITER $$
CREATE TRIGGER `game_status_update` BEFORE UPDATE ON `game_status` FOR EACH ROW BEGIN

SET NEW.last_change = NOW();

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `username` varchar(20) DEFAULT NULL,
  `piece` enum('X','O') NOT NULL,
  `token` varchar(32) DEFAULT NULL,
  `last_action` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`username`, `piece`, `token`, `last_action`) VALUES
('das', 'X', '2206eb7fb7a3cc7a13fc5fd72c92ed47', '2020-01-09 22:41:33'),
('das', 'O', 'cfe4948cc6d0da68f93bc6b9b6a2956c', '2020-01-09 22:41:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `board`
--
ALTER TABLE `board`
  ADD PRIMARY KEY (`x`,`y`);

--
-- Indexes for table `board_empty`
--
ALTER TABLE `board_empty`
  ADD PRIMARY KEY (`y`,`x`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`piece`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
