-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 23, 2022 at 08:11 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `infobdn`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrador`
--

CREATE TABLE `administrador` (
  `nomUsuari` varchar(60) NOT NULL,
  `Contrasenya` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `administrador`
--

INSERT INTO `administrador` (`nomUsuari`, `Contrasenya`) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Table structure for table `alumnes`
--

CREATE TABLE `alumnes` (
  `dni` varchar(9) NOT NULL,
  `nom` varchar(60) NOT NULL,
  `cognom` varchar(60) NOT NULL,
  `fotografia` varchar(60) NOT NULL,
  `edat` varchar(11) NOT NULL,
  `correuElectronic` varchar(60) NOT NULL,
  `contrasenya` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cursos`
--

CREATE TABLE `cursos` (
  `codi` int(11) NOT NULL,
  `nom` varchar(40) NOT NULL,
  `descripcio` varchar(80) NOT NULL,
  `hores` int(11) NOT NULL,
  `dataInici` date NOT NULL,
  `dataFinal` date NOT NULL,
  `CursProfessorFK` varchar(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cursos`
--

INSERT INTO `cursos` (`codi`, `nom`, `descripcio`, `hores`, `dataInici`, `dataFinal`, `CursProfessorFK`) VALUES
(4, 'ert', 'ert', 3, '2022-09-13', '2022-09-30', 'ghjl'),
(67, '76', '76', 0, '2022-08-30', '2022-10-06', 'asdasdaas'),
(456, '456', '456', 456, '2022-10-01', '2022-09-08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `matricules`
--

CREATE TABLE `matricules` (
  `FK.Alumnes.DNI` varchar(9) NOT NULL,
  `FK.Cursos.CODI` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `professors`
--

CREATE TABLE `professors` (
  `dni` varchar(9) NOT NULL,
  `contrasenya` varchar(60) NOT NULL,
  `nom` varchar(60) NOT NULL,
  `cognom` varchar(60) NOT NULL,
  `fotografia` varchar(60) NOT NULL,
  `titolAcademic` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `professors`
--

INSERT INTO `professors` (`dni`, `contrasenya`, `nom`, `cognom`, `fotografia`, `titolAcademic`) VALUES
('38875608L', 'a79393a3a323dc874b680f03167cbb7c', 'maiol', 'pons', '38875608L.jpg', 'tech priest'),
('asdasdaas', 'adbf5a778175ee757c34d0eba4e932bc', 'asd', 'dasd', 'asdasdaasd.jpg', 'asdasd'),
('eloi', '3d43405a2720862f368745afa50793b4', 'eloi', 'pons', 'eloi.jpg', 'sneak eater'),
('ghjl', 'ed265bc903a5a097f61d3ec064d96d2e', '456456456456456456456', '4545', 'ghjl.jpg', '45445'),
('hfgh', 'b2f5ff47436671b6e533d8dc3614845d', 'hgj', 'ghjg', 'hfgh.jpg', 'hj'),
('iop', '9fbfb220e03aa76d424088e43314b0d0', 'iop', 'iop', 'iop.jpg', 'iop'),
('maiol', '5f0ad77b699eac25ce0d04e3a4ae4271', 'maiol', 'maiol', 'maiol.jpg', 'maiol'),
('sdfsdfsdf', 'd58e3582afa99040e27b92b13c8f2280', 'sdfs', 'dfsdf', 'sdfsdfsdf.jpg', 'sdf'),
('wa', 'c68c559d956d4ca20f435ed74a6e71e6', 'wa', 'wa', 'wa.jpg', 'wa'),
('why', '79339ebe03c2b89180090ce4569506d2', 'this', 'always', 'why.jpg', 'happens'),
('yui', '385d04e7683a033fcc6c6654529eb7e9', 'io', 'oy', 'yui.jpg', 'y');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`nomUsuari`);

--
-- Indexes for table `alumnes`
--
ALTER TABLE `alumnes`
  ADD PRIMARY KEY (`dni`),
  ADD UNIQUE KEY `correuElectronic` (`correuElectronic`);

--
-- Indexes for table `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`codi`),
  ADD KEY `CursProfessor.FK` (`CursProfessorFK`);

--
-- Indexes for table `matricules`
--
ALTER TABLE `matricules`
  ADD PRIMARY KEY (`FK.Alumnes.DNI`,`FK.Cursos.CODI`),
  ADD KEY `FK.Cursos.CODI` (`FK.Cursos.CODI`);

--
-- Indexes for table `professors`
--
ALTER TABLE `professors`
  ADD PRIMARY KEY (`dni`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`CursProfessorFK`) REFERENCES `professors` (`dni`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `matricules`
--
ALTER TABLE `matricules`
  ADD CONSTRAINT `matricules_ibfk_1` FOREIGN KEY (`FK.Alumnes.DNI`) REFERENCES `alumnes` (`dni`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `matricules_ibfk_2` FOREIGN KEY (`FK.Cursos.CODI`) REFERENCES `cursos` (`codi`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
