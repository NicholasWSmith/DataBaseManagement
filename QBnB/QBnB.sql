-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 25, 2016 at 10:05 PM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `QBnB`
--

-- --------------------------------------------------------

--
-- Table structure for table `Booking`
--

CREATE TABLE `Booking` (
  `b_status` varchar(20) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `member_id` int(8) NOT NULL,
  `property_id` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Booking`
--

INSERT INTO `Booking` (`b_status`, `start_date`, `end_date`, `member_id`, `property_id`) VALUES
('rejected', '2015-12-01', '2015-12-08', 3, 1),
('confirmed', '2016-01-01', '2016-01-08', 1, 1),
('requested', '2016-03-01', '2016-03-08', 9, 1),
('confirmed', '2016-04-01', '2016-04-07', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Comments`
--

CREATE TABLE `Comments` (
  `comment_text` text NOT NULL,
  `rating` int(1) DEFAULT NULL,
  `comment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Comments`
--

INSERT INTO `Comments` (`comment_text`, `rating`, `comment_id`) VALUES
('Staying at Kristian''s villa was a pleasure! The house was comfortable, cleanly, and stylish.\r\n10/10 would recommend', 5, 1),
('I''m glad you enjoyed your stay.', NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `Districts`
--

CREATE TABLE `Districts` (
  `district_name` varchar(50) NOT NULL,
  `district_id` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Districts`
--

INSERT INTO `Districts` (`district_name`, `district_id`) VALUES
('Midtown', 1),
('Chinatown', 2),
('Entertainment District', 3),
('Kensington Market', 4),
('University', 5);

-- --------------------------------------------------------

--
-- Table structure for table `Features`
--

CREATE TABLE `Features` (
  `feature` varchar(50) NOT NULL,
  `property_id` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Features`
--

INSERT INTO `Features` (`feature`, `property_id`) VALUES
('Close to downtown', 1),
('Close to resteraunts', 1);

-- --------------------------------------------------------

--
-- Table structure for table `is_in`
--

CREATE TABLE `is_in` (
  `district_id` int(3) NOT NULL,
  `property_id` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `is_in`
--

INSERT INTO `is_in` (`district_id`, `property_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `parent_comment`
--

CREATE TABLE `parent_comment` (
  `parent_comment` int(11) NOT NULL,
  `child_comment` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `parent_comment`
--

INSERT INTO `parent_comment` (`parent_comment`, `child_comment`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `PointsOfInterest`
--

CREATE TABLE `PointsOfInterest` (
  `point` varchar(50) NOT NULL,
  `district_id` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PointsOfInterest`
--

INSERT INTO `PointsOfInterest` (`point`, `district_id`) VALUES
('Mount Pleasant Village', 1),
('Eglington Park', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Properties`
--

CREATE TABLE `Properties` (
  `address` varchar(100) NOT NULL,
  `district_name` varchar(100) NOT NULL,
  `bedrooms` int(11) NOT NULL,
  `lodging` varchar(50) NOT NULL,
  `price` float NOT NULL,
  `property_id` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Properties`
--

INSERT INTO `Properties` (`address`, `district_name`, `bedrooms`, `lodging`, `price`, `property_id`) VALUES
('58 Saguenay Ave, Toronto', 'Midtown', 3, 'House', 150, 1);

-- --------------------------------------------------------

--
-- Table structure for table `property_comment`
--

CREATE TABLE `property_comment` (
  `member_id` int(8) NOT NULL,
  `property_id` int(8) NOT NULL,
  `comment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `property_comment`
--

INSERT INTO `property_comment` (`member_id`, `property_id`, `comment_id`) VALUES
(1, 1, 1),
(2, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `rents_out`
--

CREATE TABLE `rents_out` (
  `member_id` int(8) NOT NULL,
  `property_id` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rents_out`
--

INSERT INTO `rents_out` (`member_id`, `property_id`) VALUES
(2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ServiceMembers`
--

CREATE TABLE `ServiceMembers` (
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` char(10) NOT NULL,
  `year` int(4) NOT NULL,
  `faculty` varchar(20) NOT NULL,
  `degree` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `member_id` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ServiceMembers`
--

INSERT INTO `ServiceMembers` (`first_name`, `last_name`, `email`, `phone_number`, `year`, `faculty`, `degree`, `password`, `member_id`) VALUES
('Dan', 'Lawrence', 'dlawrence@hotmail.com', '4161112223', 2017, 'Computing', 'BSc', 'password', 1),
('Kristian', 'Ludig', 'kludig@hotmail.com', '6131111111', 2017, 'Computing', 'BSc', 'password', 2),
('John', 'Smith', 'jsmith@hotmail.com', '6133333333', 2008, 'Engineering', 'BEng', 'password', 3),
('Rachel', 'Smith', 'rsmith@hotmail.com', '6133333333', 2009, 'Nursing', 'BNSc', 'password', 4),
('Ben', 'Thomas', 'bthomas@hotmail.com', '6134444444', 2011, 'Commerce', 'BComm', 'password', 9),
('Bob', 'Loblaw', 'bloblaw@hotmail.com', '6131234567', 2017, 'Computing', 'BSc', 'password', 10);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Booking`
--
ALTER TABLE `Booking`
  ADD PRIMARY KEY (`start_date`,`end_date`,`member_id`,`property_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `Comments`
--
ALTER TABLE `Comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `Districts`
--
ALTER TABLE `Districts`
  ADD PRIMARY KEY (`district_id`);

--
-- Indexes for table `Features`
--
ALTER TABLE `Features`
  ADD PRIMARY KEY (`feature`,`property_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `is_in`
--
ALTER TABLE `is_in`
  ADD PRIMARY KEY (`district_id`,`property_id`),
  ADD KEY `district_id` (`district_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `parent_comment`
--
ALTER TABLE `parent_comment`
  ADD PRIMARY KEY (`parent_comment`),
  ADD KEY `parent_comment` (`parent_comment`),
  ADD KEY `child_comment` (`child_comment`);

--
-- Indexes for table `PointsOfInterest`
--
ALTER TABLE `PointsOfInterest`
  ADD KEY `district_id` (`district_id`);

--
-- Indexes for table `Properties`
--
ALTER TABLE `Properties`
  ADD PRIMARY KEY (`property_id`);

--
-- Indexes for table `property_comment`
--
ALTER TABLE `property_comment`
  ADD PRIMARY KEY (`member_id`,`property_id`,`comment_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `comment_id` (`comment_id`);

--
-- Indexes for table `rents_out`
--
ALTER TABLE `rents_out`
  ADD PRIMARY KEY (`member_id`,`property_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `ServiceMembers`
--
ALTER TABLE `ServiceMembers`
  ADD PRIMARY KEY (`member_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Comments`
--
ALTER TABLE `Comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Districts`
--
ALTER TABLE `Districts`
  MODIFY `district_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `Properties`
--
ALTER TABLE `Properties`
  MODIFY `property_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ServiceMembers`
--
ALTER TABLE `ServiceMembers`
  MODIFY `member_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `Booking`
--
ALTER TABLE `Booking`
  ADD CONSTRAINT `Booking_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `ServiceMembers` (`member_id`),
  ADD CONSTRAINT `Booking_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `Properties` (`property_id`);

--
-- Constraints for table `Features`
--
ALTER TABLE `Features`
  ADD CONSTRAINT `Features_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `Properties` (`property_id`);

--
-- Constraints for table `is_in`
--
ALTER TABLE `is_in`
  ADD CONSTRAINT `is_in_ibfk_1` FOREIGN KEY (`district_id`) REFERENCES `Districts` (`district_id`),
  ADD CONSTRAINT `is_in_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `Properties` (`property_id`);

--
-- Constraints for table `parent_comment`
--
ALTER TABLE `parent_comment`
  ADD CONSTRAINT `parent_comment_ibfk_1` FOREIGN KEY (`parent_comment`) REFERENCES `Comments` (`comment_id`),
  ADD CONSTRAINT `parent_comment_ibfk_2` FOREIGN KEY (`child_comment`) REFERENCES `Comments` (`comment_id`);

--
-- Constraints for table `PointsOfInterest`
--
ALTER TABLE `PointsOfInterest`
  ADD CONSTRAINT `PointsOfInterest_ibfk_1` FOREIGN KEY (`district_id`) REFERENCES `Districts` (`district_id`);

--
-- Constraints for table `property_comment`
--
ALTER TABLE `property_comment`
  ADD CONSTRAINT `property_comment_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `ServiceMembers` (`member_id`),
  ADD CONSTRAINT `property_comment_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `Properties` (`property_id`),
  ADD CONSTRAINT `property_comment_ibfk_3` FOREIGN KEY (`comment_id`) REFERENCES `Comments` (`comment_id`);

--
-- Constraints for table `rents_out`
--
ALTER TABLE `rents_out`
  ADD CONSTRAINT `rents_out_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `ServiceMembers` (`member_id`),
  ADD CONSTRAINT `rents_out_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `Properties` (`property_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
