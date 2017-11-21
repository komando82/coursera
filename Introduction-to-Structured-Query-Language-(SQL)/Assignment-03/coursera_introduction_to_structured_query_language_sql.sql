-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 20, 2017 at 08:35 AM
-- Server version: 5.6.35
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coursera_introduction_to_structured_query_language_sql`
--

-- --------------------------------------------------------

--
-- Table structure for table `ages`
--

CREATE TABLE `ages` (
  `name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ages`
--

INSERT INTO `ages` (`name`, `age`) VALUES
('Mira', 17),
('Krystal', 23),
('Susan', 26),
('Cambell', 35),
('Aleeyah', 19),
('Leaya', 35);

-- --------------------------------------------------------

--
-- Table structure for table `album`
--

CREATE TABLE `album` (
  `album_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `artist_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `album`
--

INSERT INTO `album` (`album_id`, `title`, `artist_id`) VALUES
(1, 'Confessions on a Dance Floor', 1),
(2, '18 Months', 2),
(3, 'Motion', 2),
(4, 'Beyonce', 3),
(5, 'Lemonade', 3);

-- --------------------------------------------------------

--
-- Table structure for table `artist`
--

CREATE TABLE `artist` (
  `artist_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `artist`
--

INSERT INTO `artist` (`artist_id`, `name`) VALUES
(1, 'Madonna'),
(2, 'Calvin Harris'),
(3, 'Beyonce');

-- --------------------------------------------------------

--
-- Table structure for table `genre`
--

CREATE TABLE `genre` (
  `genre_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `genre`
--

INSERT INTO `genre` (`genre_id`, `name`) VALUES
(2, 'Electro house'),
(1, 'Pop'),
(3, 'R&B');

-- --------------------------------------------------------

--
-- Table structure for table `track`
--

CREATE TABLE `track` (
  `track_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `len` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `album_id` int(11) DEFAULT NULL,
  `genre_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `track`
--

INSERT INTO `track` (`track_id`, `title`, `len`, `rating`, `count`, `album_id`, `genre_id`) VALUES
(1, 'Hung Up', 336, 5, 0, 1, 1),
(2, 'Sorry', 283, 5, 0, 1, 1),
(3, 'Get Together', 330, 5, 0, 1, 1),
(4, 'Jump', 226, 5, 0, 1, 1),
(5, 'Feel So Close', 206, 5, 0, 2, 2),
(6, 'I Need Your Love', 236, 5, 0, 2, 2),
(7, 'Lets Go', 237, 5, 0, 2, 2),
(8, 'Thinking About You', 247, 5, 0, 2, 2),
(9, 'Under Control', 184, 5, 0, 3, 2),
(10, 'Summer', 222, 5, 0, 3, 2),
(11, 'Blame', 212, 5, 0, 3, 2),
(12, 'Outside', 227, 5, 0, 3, 2),
(13, 'XO', 215, 5, 0, 4, 3),
(14, 'Drunk in Love', 323, 5, 0, 4, 3),
(15, 'Partition', 319, 5, 0, 4, 3),
(16, 'Pretty Hurts', 257, 5, 0, 4, 3),
(17, 'Formation', 206, 5, 0, 5, 3),
(18, 'Sorry', 232, 5, 0, 5, 3),
(19, 'Hold Up', 221, 5, 0, 5, 3),
(20, 'Freedom', 249, 5, 0, 5, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`album_id`),
  ADD KEY `title` (`title`) USING BTREE,
  ADD KEY `artist_id` (`artist_id`);

--
-- Indexes for table `artist`
--
ALTER TABLE `artist`
  ADD PRIMARY KEY (`artist_id`);

--
-- Indexes for table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`genre_id`),
  ADD KEY `name` (`name`) USING BTREE;

--
-- Indexes for table `track`
--
ALTER TABLE `track`
  ADD PRIMARY KEY (`track_id`),
  ADD KEY `title` (`title`) USING BTREE,
  ADD KEY `album_id` (`album_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `album`
--
ALTER TABLE `album`
  MODIFY `album_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `artist`
--
ALTER TABLE `artist`
  MODIFY `artist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `genre`
--
ALTER TABLE `genre`
  MODIFY `genre_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `track`
--
ALTER TABLE `track`
  MODIFY `track_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
