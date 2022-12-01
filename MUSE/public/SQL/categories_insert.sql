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

-- Listage des données de la table MUSE.category : ~19 rows (environ)
INSERT INTO `category` (`id`, `parent_category_id`, `name`, `image`) VALUES
	(1, NULL, 'Guitares', 'Guitares.jpg'),
	(2, 1, 'Guitares Electriques', 'Guitares Electriques.jpg'),
	(3, 1, 'Guitares accoustiques', 'Guitares accoustiques.jpg'),
	(4, NULL, 'Guitares basses', 'Guitares basses.jpg'),
	(5, 4, 'Basses accoustiques', 'Basses accoustiques.jpg'),
	(6, 4, 'Basses électriques', 'Basses électriques.jpg'),
	(7, NULL, 'Batteries & Percussions', 'Batteries & Percussions.jpg'),
	(8, 7, 'Batteries', 'Batteries.jpg'),
	(9, 7, 'Percussions', 'Percussions.jpg'),
	(10, NULL, 'Pianos & Claviers', 'Pianos & Claviers.jpg'),
	(11, 10, 'Claviers', 'Claviers.jpg'),
	(12, 10, 'Pianos', 'Pianos.jpg'),
	(13, NULL, 'Instruments à vent', 'Instruments à vent.jpg'),
	(14, NULL, 'Instruments trad.', 'Instruments trad..jpg'),
	(15, NULL, 'Matériel DJ', 'Matériel DJ.jpg'),
	(16, NULL, 'Microphones', 'Microphones.jpg'),
	(17, NULL, 'Sonorisation', 'Sonorisation.jpg'),
	(18, NULL, 'Accessoires', 'Accessoires.jpg'),
	(19, NULL, 'Autres instruments', 'Autres instruments.jpg');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
