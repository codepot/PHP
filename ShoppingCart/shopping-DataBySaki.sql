SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `serial` int(11) NOT NULL auto_increment,
  `name` varchar(20) collate latin1_general_ci NOT NULL,
  `email` varchar(80) collate latin1_general_ci NOT NULL,
  `address` varchar(80) collate latin1_general_ci NOT NULL,
  `phone` varchar(20) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`serial`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `customers`
--


-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `serial` int(11) NOT NULL auto_increment,
  `date` date NOT NULL,
  `customerid` int(11) NOT NULL,
  PRIMARY KEY  (`serial`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `orders`
--


-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE IF NOT EXISTS `order_detail` (
  `orderid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `order_detail`
--


-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `serial` int(11) NOT NULL auto_increment,
  `name` varchar(20) collate latin1_general_ci NOT NULL,
  `description` varchar(255) collate latin1_general_ci NOT NULL,
  `price` float NOT NULL,
  `picture` varchar(80) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`serial`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`serial`,`name`,`description`,`price`,`picture`) VALUES (1,'Nike Braata LR Canva','Pieced navy canvas upper. Low profile styled shoe, brown lace front. White Nike logo on side. Padded insole. Man made rubber textured outsole. Size 10 shown.',65,'images/nike1.jpg');
INSERT INTO `products` (`serial`,`name`,`description`,`price`,`picture`) VALUES (2,'Riot Society - Me So','Online Exclusive! White tee with graphic on front, Crew neck. Short sleeves. Regular fit. Machine washable.100% cotton.',27.95,'images/thorn.jpg');
INSERT INTO `products` (`serial`,`name`,`description`,`price`,`picture`) VALUES (3,'Riot Society - Me So','Online Exclusive! White tee with graphic on front. Crew neck. Short sleeves. Regular fit. Machine washable.100% cotton.',25.95,'images/panda.jpg');
INSERT INTO `products` (`serial`,`name`,`description`,`price`,`picture`) VALUES (4,'Modern Amusement - F','Allover multi color fish print woven. Modern Amusement logo loop bottom. Chest pocket. Button front. Medium spread collar. Short sleeves. Straight yoke. Regular fit. Machine washable.100% cotton.',59.5,'images/shirt.jpg');
INSERT INTO `products` (`serial`,`name`,`description`,`price`,`picture`) VALUES (5,'Ray-Ban, Justin Red ','Inspired by Way Farer design. Ray Ban logo on temples and lens. Red gradient frames. Gray lenses. Gradient frame color. Rubberized finish. Imported. Sky blue case included.',125.95,'images/rayban.jpg');
INSERT INTO `products` (`serial`,`name`,`description`,`price`,`picture`) VALUES (6,'Vestal - Plexi Brown','Online Exclusive! Three unique material create a one of a kind watch. Large stainless steel case. Genuine brown leather and steel links. Two pusher stainless steel butterfly clasp 49mm wide case. 30mm wide band. Chronograph movement. Solid mineral crystal',210,'images/watch.jpg');
INSERT INTO `products` (`serial`,`name`,`description`,`price`,`picture`) VALUES (7,'On The Byas - Marvin','Allover two tone heather tee. Contrast chest pocket. Crew neck. 3/4 sleeves. Regular fit. Machine washable.Imported.55% cotton, 34% polyester, 11% rayon.',25.95,'images/bias.jpg');
