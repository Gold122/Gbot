/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Zrzut struktury tabela ts_gbot.newuserToday
CREATE TABLE IF NOT EXISTS `newuserToday` (
  `dbid` int(11) NOT NULL,
  `client_nickname` varchar(200) NOT NULL,
  `client_unique_identifier` varchar(200) NOT NULL,
  `time` int(11) DEFAULT NULL,
  PRIMARY KEY (`dbid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.
-- Zrzut struktury tabela ts_gbot.record
CREATE TABLE IF NOT EXISTS `record` (
  `uid` varchar(150) NOT NULL,
  `online` int(11) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `record` (`online`, `time`) VALUES ('0', '0');

-- Data exporting was unselected.
-- Zrzut struktury tabela ts_gbot.static
CREATE TABLE IF NOT EXISTS `static` (
  `id` int(11) NOT NULL,
  `nick` varchar(200) NOT NULL,
  `mem` varchar(200) NOT NULL,
  `online` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.
-- Zrzut struktury tabela ts_gbot.tops
CREATE TABLE IF NOT EXISTS `tops` (
  `client_database_id` int(100) NOT NULL,
  `client_unique_identifier` varchar(50) NOT NULL,
  `client_nickname` varchar(100) DEFAULT NULL,
  `clientConnections` int(200) DEFAULT NULL,
  `clientTime` int(200) DEFAULT NULL,
  `clientTimeSpent` int(200) DEFAULT NULL,
  `clientAFK` int(200) DEFAULT NULL,
  PRIMARY KEY (`client_database_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
