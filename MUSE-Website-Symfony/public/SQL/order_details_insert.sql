-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           10.6.11-MariaDB-0ubuntu0.22.04.1 - Ubuntu 22.04
-- SE du serveur:                debian-linux-gnu
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Listage des données de la table MUSE.order_details : ~41 rows (environ)
INSERT INTO `order_details` (`id`, `product_id`, `cart_id`, `quantity`, `sub_total`) VALUES
	(1, 27, 2, 10, 748),
	(2, 14, 2, 1, 43879),
	(3, 24, 4, 1, 916.98),
	(4, 40, 4, 1, 484.92),
	(5, 47, 5, 1, 9598.8),
	(6, 58, 5, 5, 24),
	(7, 38, 3, 1, 1507.88),
	(8, 37, 3, 1, 5478),
	(9, 40, 3, 1, 444.51),
	(10, 88, 6, 1, 900),
	(11, 58, 6, 1, 4.8),
	(12, 59, 6, 1, 9.6),
	(13, 60, 6, 1, 7.056),
	(14, 61, 6, 1, 11.76),
	(15, 62, 6, 1, 2.4),
	(16, 63, 6, 1, 6),
	(17, 89, 6, 1, 544.8),
	(18, 50, 7, 1, 103.455),
	(19, 84, 7, 1, 1130.822),
	(20, 1, 8, 3, 1249.2),
	(21, 2, 8, 1, 1252.86),
	(22, 3, 8, 1, 718.8),
	(23, 5, 8, 3, 4424.4),
	(24, 2, 14, 1, 1148.455),
	(25, 6, 15, 1, 482.9),
	(26, 2, 16, 1, 1148.455),
	(30, 5, 19, 1, 1474.8),
	(31, 9, 19, 1, 799.2),
	(32, 58, 19, 1, 4.8),
	(33, 62, 19, 1, 2.4),
	(34, 61, 19, 1, 11.76),
	(35, 2, 9, 1, 1252.86),
	(36, 2, 20, 1, 1252.86),
	(37, 38, 20, 1, 1644.96),
	(38, 40, 20, 1, 484.92),
	(39, 23, 1, 1, 2629),
	(40, 1, 23, 2, 791.16),
	(41, 2, 18, 1, 1148.455),
	(42, 3, 24, 1, 658.9),
	(43, 82, 1, 1, 1175.9),
	(44, 2, 25, 1, 1252.86);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
