-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2016 at 10:36 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `weather_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE IF NOT EXISTS `city` (
  `c_id` int(11) NOT NULL AUTO_INCREMENT,
  `c_city_name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`c_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`c_id`, `c_city_name`, `created_at`, `updated_at`) VALUES
(8, 'Tel Aviv', '2016-04-26 10:52:57', '2016-04-26 11:28:47'),
(9, 'London', '2016-04-26 10:55:39', '2016-04-26 10:55:39');

-- --------------------------------------------------------

--
-- Table structure for table `temp`
--

CREATE TABLE IF NOT EXISTS `temp` (
  `t_id` int(11) NOT NULL AUTO_INCREMENT,
  `t_temp_max` float NOT NULL,
  `t_temp_min` float NOT NULL,
  `t_date_time` datetime NOT NULL,
  `t_city_id` int(11) NOT NULL,
  PRIMARY KEY (`t_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `temp`
--

INSERT INTO `temp` (`t_id`, `t_temp_max`, `t_temp_min`, `t_date_time`, `t_city_id`) VALUES
(1, 34.44, 25.11, '2016-04-26 09:00:00', 8),
(2, 34.44, 25.11, '2016-04-26 09:00:00', 9);

-- --------------------------------------------------------

--
-- Table structure for table `wind`
--

CREATE TABLE IF NOT EXISTS `wind` (
  `w_id` int(11) NOT NULL AUTO_INCREMENT,
  `w_speed` float NOT NULL,
  `w_city_id` int(11) NOT NULL,
  PRIMARY KEY (`w_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `wind`
--

INSERT INTO `wind` (`w_id`, `w_speed`, `w_city_id`) VALUES
(8, 1.61, 8),
(9, 1.61, 9);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
