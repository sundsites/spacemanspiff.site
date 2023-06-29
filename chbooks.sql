-- phpMyAdmin SQL Dump
-- version 4.3.12
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 23, 2015 at 10:18 AM
-- Server version: 5.5.15
-- PHP Version: 5.4.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `emberne_main`
--

-- --------------------------------------------------------

--
-- Table structure for table `chbooks`
--

CREATE TABLE IF NOT EXISTS `chbooks` (
  `chb_id` char(1) NOT NULL DEFAULT '' COMMENT 'Book ID',
  `chb_date` date NOT NULL DEFAULT '0000-00-00' COMMENT 'Date book published',
  `chb_title` varchar(255) NOT NULL DEFAULT '' COMMENT 'Book title',
  `chb_link` varchar(255) NOT NULL DEFAULT '' COMMENT 'Link to purchase of book'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Calvin and Hobbes books';

--
-- Dumping data for table `chbooks`
--

INSERT INTO `chbooks` (`chb_id`, `chb_date`, `chb_title`, `chb_link`) VALUES
('a', '1992-04-01', 'Attack of the Deranged Mutant Killer Monster Snow Goons', 'http://www.amazon.com/gp/product/0836218833?ie=UTF8&amp;tag=michayingl-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=0836218833'),
('b', '1987-04-01', 'Calvin and Hobbes', 'http://www.amazon.com/gp/product/0836220889?ie=UTF8&amp;tag=michayingl-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=0836220889'),
('c', '1994-10-01', 'Homicidal Psycho Jungle Cat', 'http://www.amazon.com/gp/product/0836217691?ie=UTF8&amp;tag=michayingl-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=0836217691'),
('d', '1996-10-01', 'It''s A Magical World', 'http://www.amazon.com/gp/product/0836221362?ie=UTF8&amp;tag=michayingl-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=0836221362'),
('e', '1991-10-01', 'Scientific Progress Goes "Boink"', 'http://www.amazon.com/gp/product/0836218787?ie=UTF8&amp;tag=michayingl-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=0836218787'),
('f', '1988-04-01', 'Something Under the Bed Is Drooling', 'http://www.amazon.com/gp/product/0836218256?ie=UTF8&amp;tag=michayingl-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=0836218256'),
('g', '1990-10-01', 'The Authoritative Calvin and Hobbes', 'http://www.amazon.com/gp/product/0836218221?ie=UTF8&amp;tag=michayingl-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=0836218221'),
('h', '2005-10-01', 'The Complete Calvin and Hobbes', 'http://www.amazon.com/gp/product/0740748475?ie=UTF8&amp;tag=michayingl-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=0740748475'),
('i', '1993-10-01', 'The Days are Just Packed', 'http://www.amazon.com/gp/product/0836217357?ie=UTF8&amp;tag=michayingl-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=0836217357'),
('j', '1988-09-01', 'The Essential Calvin and Hobbes', 'http://www.amazon.com/gp/product/0836218051?ie=UTF8&amp;tag=michayingl-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=0836218051'),
('k', '1992-10-01', 'The Indispensable Calvin And Hobbes', 'http://www.amazon.com/gp/product/0836218981?ie=UTF8&amp;tag=michayingl-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=0836218981'),
('l', '1991-04-01', 'The Revenge of the Baby-Sat', 'http://www.amazon.com/gp/product/0836218663?ie=UTF8&amp;tag=michayingl-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=0836218663'),
('m', '1996-03-01', 'There''s Treasure Everywhere', 'http://www.amazon.com/gp/product/0836213122?ie=UTF8&amp;tag=michayingl-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=0836213122'),
('n', '1990-03-01', 'Weirdos from Another Planet!', 'http://www.amazon.com/gp/product/0836218620?ie=UTF8&amp;tag=michayingl-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=0836218620'),
('o', '1989-03-01', 'Yukon Ho!', 'http://www.amazon.com/gp/product/0836218353?ie=UTF8&amp;tag=michayingl-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=0836218353');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chbooks`
--
ALTER TABLE `chbooks`
  ADD PRIMARY KEY (`chb_id`), ADD KEY `chb_date` (`chb_date`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
