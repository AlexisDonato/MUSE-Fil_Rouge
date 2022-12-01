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

-- Listage des données de la table MUSE.user : ~7 rows (environ)
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `user_name`, `user_lastname`, `birthdate`, `phone_number`, `is_verified`, `register_date`, `vat`, `pro`, `pro_company_name`, `pro_duns`, `pro_job_position`, `agree_terms`) VALUES
	(1, 'admin@muse.com', '["ROLE_ADMIN","ROLE_SALES","ROLE_SHIP","ROLE_PRO","ROLE_CLIENT","ROLE_USER"]', '$2y$13$ccySVe3LypY0lq.nZKB1ROQbpKUXCH2fmueDtSsTI.GebmJUmWfkG', 'admin', 'admin', '2022-12-12 00:00:00', '0999999999', 1, '2022-12-12 00:00:00', 0.10, 1, NULL, NULL, NULL, 1),
	(2, 'sales@muse.com', '["ROLE_SALES","ROLE_SHIP","ROLE_PRO","ROLE_CLIENT","ROLE_USER"]', '$2y$13$tvAgwTJWbvYBUOpiyYzXyOsBg4X1jZocwCq5AichRjsHmACQxro3S', 'sales', 'sales', '2022-12-12 00:00:00', '0999999999', 1, '2022-12-12 00:00:00', 0.10, 1, NULL, NULL, NULL, 1),
	(3, 'ship@muse.com', '["ROLE_SHIP","ROLE_PRO","ROLE_CLIENT","ROLE_USER"]', '$2y$13$wZto0kfDzPviiOga/q5a2uKPXxFZ9G0hOmPKZFlMpvNPcGfd059Iu', 'ship', 'ship', '2022-12-12 00:00:00', '0999999999', 1, '2022-12-12 00:00:00', 0.10, 1, NULL, NULL, NULL, 1),
	(4, 'pro@muse.com', '["ROLE_PRO","ROLE_CLIENT","ROLE_USER"]', '$2y$13$qFENhSxKWFGae4l/tAApe.bttMTnQatCgEhdpXhFxFW1nUhONYJxe', 'pro', 'pro', '2022-12-12 00:00:00', '0999999999', 1, '2022-12-12 00:00:00', 0.10, 1, NULL, NULL, NULL, 1),
	(5, 'client@muse.com', '["ROLE_CLIENT","ROLE_USER"]', '$2y$13$cGv5KqaPxeM6BWZHE9224./Wbc/fUaJ7ilYiKZcnZ2aC0DrDg14ee', 'client', 'client', '2022-12-12 00:00:00', '0999999999', 1, '2022-12-12 00:00:00', 0.20, 0, NULL, NULL, NULL, 1),
	(6, '123@123.com', '["ROLE_CLIENT","ROLE_USER"]', '$2y$13$aXlVF7hE8rT.1tjmUF3eXe2PdoI8j3/YoOkWaI5MrzvF.I.1Hvs/O', 'po', 'po', '2022-11-17 00:00:00', '0999999999', 1, '2022-11-30 10:20:51', 0.20, 0, NULL, NULL, NULL, 1),
	(9, 'client@client.com', '["ROLE_CLIENT","ROLE_USER"]', '$2y$13$jnHByAAmk7kbw9344XaeVeZuiKNsrDhgmcSSCed1ue/5Oz3EwhV0q', 'Jean-Michel', 'De la Rue', '2022-11-13 00:00:00', '0999999999', 0, '2022-11-30 11:34:43', 0.20, 0, NULL, NULL, NULL, 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
