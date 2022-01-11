SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- CREATE DATABASE
CREATE DATABASE IF NOT EXISTS `hhc_main` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `hhc_main`;

-- CREATE doctor_info TABLE
CREATE TABLE `doctor_info` (
  `doctor_id` varchar(50) NOT NULL,
  `doctor_name` varchar(100) NOT NULL,
  `doctor_speciality` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- CREATE patient_info TABLE
CREATE TABLE `patient_info` (
  `reg_no` varchar(50) NOT NULL,
  `p_fullname` varchar(100) NOT NULL,
  `p_gender` char(1) NOT NULL,
  `p_pno` varchar(10) NOT NULL,
  `p_address` text NOT NULL,
  `p_age` int(11) NOT NULL,
  `p_regdate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- CREATE queue_info TABLE
CREATE TABLE `queue_info` (
  `queue_id` int(10) UNSIGNED NOT NULL,
  `reg_no` varchar(50) NOT NULL,
  `visit_id` varchar(50) NOT NULL,
  `doctor_id` varchar(50) NOT NULL,
  `visit_date` date NOT NULL,
  `visit_time` time NOT NULL,
  `visit_status` enum('VISITED','NOTVISITED') NOT NULL DEFAULT 'NOTVISITED'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- CREATE visit_details TABLE
CREATE TABLE `visit_details` (
  `visit_id` varchar(50) NOT NULL,
  `reg_no` varchar(50) NOT NULL,
  `date_of_visit` date NOT NULL,
  `doctor_id` varchar(50) NOT NULL,
  `consultation_fees` decimal(10,0) NOT NULL,
  `consultation_mode` enum('OFFLINE','ONLINE','LAB_WORK') NOT NULL,
  `payment_method` enum('CARD','CASH','UPI') NOT NULL,
  `payment_status` enum('FULL','PARTIAL','NONE') NOT NULL,
  `payment_date` date NOT NULL,
  `remarks` text NOT NULL,
  `entry_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ADD INDEX TO doctor_info TABLE
ALTER TABLE `doctor_info`
  ADD PRIMARY KEY (`doctor_id`);

-- ADD INDEX TO patient_info TABLE
ALTER TABLE `patient_info`
  ADD PRIMARY KEY (`reg_no`);

-- ADD INDEX TO queue_info TABLE
ALTER TABLE `queue_info`
  ADD PRIMARY KEY (`queue_id`),
  ADD KEY `queue_info_FK` (`doctor_id`),
  ADD KEY `queue_info_FK_1` (`reg_no`),
  ADD KEY `queue_info_FK_2` (`visit_id`);

-- ADD INDEX TO visit_details TABLE
ALTER TABLE `visit_details`
  ADD PRIMARY KEY (`visit_id`),
  ADD KEY `visit_details_FK` (`reg_no`),
  ADD KEY `visit_details_FK_1` (`doctor_id`);


-- ADD AUTO_INCREMENT TO queue_info TABLE
ALTER TABLE `queue_info`
  MODIFY `queue_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

-- ADD INDEX TO queue_info TABLE
ALTER TABLE `queue_info`
  ADD CONSTRAINT `queue_info_FK` FOREIGN KEY (`doctor_id`) REFERENCES `doctor_info` (`doctor_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `queue_info_FK_1` FOREIGN KEY (`reg_no`) REFERENCES `patient_info` (`reg_no`),
  ADD CONSTRAINT `queue_info_FK_2` FOREIGN KEY (`visit_id`) REFERENCES `visit_details` (`visit_id`);

-- ADD INDEX TO visit_details TABLE
ALTER TABLE `visit_details`
  ADD CONSTRAINT `visit_details_FK` FOREIGN KEY (`reg_no`) REFERENCES `patient_info` (`reg_no`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `visit_details_FK_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctor_info` (`doctor_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- CREATE EVENT TO CLEAR queue_info TABLE DAILY AT A SPECIFIED TIME - SAY 00:00
DELIMITER $$
CREATE DEFINER=`root`@`localhost` EVENT `queue_truncator` ON SCHEDULE EVERY 1 DAY STARTS '2022-01-03 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO TRUNCATE TABLE queue_info$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
