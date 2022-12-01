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

-- Listage des données de la table MUSE.cart : ~10 rows (environ)
INSERT INTO `cart` (`id`, `user_id`, `billing_address_id`, `delivery_address_id`, `client_order_id`, `validated`, `order_date`, `shipped`, `shipment_date`, `carrier`, `carrier_shipment_id`, `total`, `additional_discount_rate`, `invoice`) VALUES
	(1, 1, NULL, NULL, 'MUSE::63807EDC0CB50', 0, NULL, 0, NULL, NULL, NULL, NULL, 0.000, NULL),
	(2, 4, 1, 1, 'MUSE::638113B285855', 1, '2022-11-25 19:15:15', 0, NULL, NULL, NULL, 44627.00, 0.000, 'INVOICE-MUSE::638113B285855.pdf'),
	(3, 4, 1, 1, 'MUSE::6381144F77A74', 1, '2022-11-26 12:22:38', 0, NULL, NULL, NULL, 7430.39, 0.000, 'INVOICE-MUSE::6381144F77A74.pdf'),
	(4, 5, 2, 2, 'MUSE::6381154008A40', 1, '2022-11-25 19:21:44', 0, NULL, NULL, NULL, 1401.90, 0.000, 'INVOICE-MUSE::6381154008A40.pdf'),
	(5, 5, 2, 2, 'MUSE::638115D17D613', 1, '2022-11-26 12:20:03', 0, NULL, NULL, NULL, 9622.80, 0.000, 'INVOICE-MUSE::638115D17D613.pdf'),
	(6, 5, 2, 2, 'MUSE::6382047C8F147', 1, '2022-11-27 10:47:40', 0, NULL, NULL, NULL, 1486.42, 0.000, 'INVOICE-MUSE::6382047C8F147.pdf'),
	(7, 4, 1, 1, 'MUSE::638205FC73298', 1, '2022-11-27 10:53:36', 0, NULL, NULL, NULL, 1234.28, 0.000, 'INVOICE-MUSE::638205FC73298.pdf'),
	(8, 5, 2, 2, 'MUSE::6384649B52E7F', 1, '2022-11-30 09:34:17', 0, NULL, NULL, NULL, 7645.26, 0.000, 'INVOICE-MUSE::6384649B52E7F.pdf'),
	(9, 5, NULL, NULL, 'MUSE::6387239A79421', 0, NULL, 0, NULL, NULL, NULL, NULL, 0.000, NULL),
	(10, 6, NULL, NULL, 'MUSE::638733F5B5C1A', 0, NULL, 0, NULL, NULL, NULL, NULL, 0.000, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
