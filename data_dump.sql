SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `vaccnotifier`
--
CREATE DATABASE IF NOT EXISTS `vaccnotifier` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `vaccnotifier`;

-- --------------------------------------------------------

--
-- Table structure for table `notifybydistrict`
--

CREATE TABLE `notifybydistrict` (
  `d_id` int(11) NOT NULL,
  `district` int(4) NOT NULL,
  `email` varchar(30) NOT NULL,
  `date` varchar(12) NOT NULL,
  `mailSent` tinyint(1) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `notifybypin`
--

CREATE TABLE `notifybypin` (
  `p_id` int(11) NOT NULL,
  `pincode` int(8) NOT NULL,
  `email` varchar(30) NOT NULL,
  `date` varchar(12) NOT NULL,
  `mailSent` tinyint(1) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for table `notifybydistrict`
--
ALTER TABLE `notifybydistrict`
  ADD PRIMARY KEY (`d_id`),
  ADD KEY `date` (`date`);

--
-- Indexes for table `notifybypin`
--
ALTER TABLE `notifybypin`
  ADD PRIMARY KEY (`p_id`),
  ADD KEY `date` (`date`);

--
-- AUTO_INCREMENT for table `notifybydistrict`
--
ALTER TABLE `notifybydistrict`
  MODIFY `d_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifybypin`
--
ALTER TABLE `notifybypin`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT;
