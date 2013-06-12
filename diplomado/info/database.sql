-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 03-03-2013 a las 10:04:18
-- Versión del servidor: 5.5.16
-- Versión de PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `fullmoondrivein2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contenidos`
--

CREATE TABLE IF NOT EXISTS `contenidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `titulo` varchar(200) DEFAULT NULL,
  `imagen1` varchar(300) DEFAULT NULL,
  `imagen2` varchar(300) DEFAULT NULL,
  `imagen3` varchar(300) DEFAULT NULL,
  `contenido` text,
  `publicado` tinyint(1) DEFAULT NULL,
  `primero_titulo` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `contenidos`
--

INSERT INTO `contenidos` (`id`, `fecha`, `titulo`, `imagen1`, `imagen2`, `imagen3`, `contenido`, `publicado`, `primero_titulo`) VALUES
(5, '2012-09-13', 'Contenido del Autocinema', 'September132012934pm_nos-mudamos.jpg', '', '', '<div align="center"><i>Esto lo saque del <a target="_blank" title="Autocinema" href="http://www.autocinemacoyote.com">sitio actual</a></i><br></div>', 1, 1),
(6, '2012-09-30', 'aaaaa', '', '', '', '<font color="#FF0000">hola</font><br>', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) NOT NULL,
  `email` varchar(180) NOT NULL,
  `phone` varchar(180) DEFAULT NULL,
  `message` text,
  `created` date DEFAULT NULL,
  `edad` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `phone`, `message`, `created`, `edad`) VALUES
(1, 'asf;ljsaf', 'ronald_salazar19@hotmail.com', '7894564', 'asflakjsfd', '2012-05-24', NULL),
(2, 'ggg', 'ggg', '', 'ggg', '2012-09-09', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movies`
--

CREATE TABLE IF NOT EXISTS `movies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) DEFAULT NULL,
  `description` text,
  `date` date NOT NULL,
  `start_time` varchar(50) NOT NULL,
  `end_time` varchar(50) NOT NULL,
  `image` varchar(2000) DEFAULT 'noimage.png',
  `cost` varchar(50) NOT NULL DEFAULT '25',
  `duration` varchar(15) DEFAULT NULL,
  `sold_out` tinyint(1) NOT NULL DEFAULT '0',
  `imagen1` varchar(180) DEFAULT NULL,
  `imagen2` varchar(180) DEFAULT NULL,
  `imagen3` varchar(180) DEFAULT NULL,
  `imagen4` varchar(180) DEFAULT NULL,
  `imagen5` varchar(180) DEFAULT NULL,
  `video_url` varchar(180) DEFAULT '#',
  `rating` varchar(15) DEFAULT NULL,
  `year` varchar(15) DEFAULT NULL,
  `genero` varchar(200) DEFAULT NULL,
  `directores` text,
  `actores` text,
  `aclaracion` varchar(250) DEFAULT NULL,
  `porque` text,
  `aclaracion2` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `movies`
--

INSERT INTO `movies` (`id`, `name`, `description`, `date`, `start_time`, `end_time`, `image`, `cost`, `duration`, `sold_out`, `imagen1`, `imagen2`, `imagen3`, `imagen4`, `imagen5`, `video_url`, `rating`, `year`, `genero`, `directores`, `actores`, `aclaracion`, `porque`, `aclaracion2`) VALUES
(1, 'Start War', 'Descripcion de la pelicula. Descripcion de la pelicula Descripcion de la pelicula Descripcion de la pelicula Descripcion de la pelicula', '2013-03-09', '9:00pm', '8:00pm', 'March32013957am_starwar.jpg', '$20', '120 min.', 1, 'March32013957am_starwar.jpg', 'March32013957am_starwar.jpg', 'March32013957am_starwar.jpg', NULL, NULL, '', '+G', '1980', 'Accion', 'director1,director2', 'actor1,actor2', 'Start War', 'Es una muy buena pelicula. Es una muy buena pelicula. Es una muy buena pelicula. Es una muy buena pelicula. Es una muy buena pelicula. Es una muy buena pelicula.', ''),
(2, 'Superamigos', 'Descripcion de la pelicula Descripcion de la pelicula Descripcion de la pelicula Descripcion de la pelicula Descripcion de la pelicula Descripcion de la pelicula', '2013-03-09', '9:00pm', '8:00pm', 'March32013958am_superamigos.jpg', '$20', '120 min.', 0, 'March32013958am_superamigos.jpg', 'March32013958am_superamigos.jpg', 'March32013958am_superamigos.jpg', NULL, NULL, '', '+G', '1980', 'Accion', 'director1,director2', 'actor1,actor2', 'Superamigos', 'Es una muy buena pelicula Es una muy buena pelicula Es una muy buena pelicula Es una muy buena pelicula Es una muy buena pelicula Es una muy buena pelicula Es una muy buena pelicula ', ''),
(3, 'Batman', 'Descripcion de la pelicula Descripcion de la pelicula Descripcion de la pelicula Descripcion de la pelicula Descripcion de la pelicula Descripcion de la pelicula Descripcion de la pelicula ', '2013-03-09', '9:00pm', '8:00pm', 'March32013959am_batman.jpg', '$20', '120 min.', 0, 'March32013959am_batman.jpg', 'March32013959am_batman.jpg', 'March32013959am_batman.jpg', NULL, NULL, '', '+G', '1980', 'Accion', 'director1,director2', 'actor1,actor2', 'Batman', 'Es un buena pelicula Es un buena pelicula Es un buena pelicula Es un buena pelicula Es un buena pelicula Es un buena pelicula Es un buena pelicula Es un buena pelicula Es un buena pelicula ', ''),
(4, 'ET', 'Descripcion de la pelicula Descripcion de la pelicula Descripcion de la pelicula Descripcion de la pelicula Descripcion de la pelicula Descripcion de la pelicula Descripcion de la pelicula ', '2013-03-09', '9:00pm', '8:00pm', 'March320131000am_et.jpg', '$20', '120 min.', 0, 'March320131000am_et.jpg', 'March320131000am_et.jpg', 'March320131000am_et.jpg', NULL, NULL, '', '+G', '1980', 'Accion', 'director1,director2', 'actor1,actor2', 'ET', 'Es una buena pelicula Es una buena pelicula Es una buena pelicula Es una buena pelicula Es una buena pelicula Es una buena pelicula Es una buena pelicula Es una buena pelicula Es una buena pelicula ', ''),
(5, 'Superman', 'Descripcion de la pelicula Descripcion de la pelicula Descripcion de la pelicula Descripcion de la pelicula Descripcion de la pelicula Descripcion de la pelicula Descripcion de la pelicula ', '2013-03-09', '9:00pm', '8:00pm', 'March320131001am_superman.jpg', '$20', '120 min.', 0, 'March320131001am_superman.jpg', 'March320131001am_superman.jpg', 'March320131001am_superman.jpg', NULL, NULL, '', '+G', '1980', 'Accion', 'director1,director2', 'actor1,actor2', 'Superman', 'Es una buena pelicula Es una buena pelicula Es una buena pelicula Es una buena pelicula Es una buena pelicula Es una buena pelicula Es una buena pelicula Es una buena pelicula ', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recommendeds`
--

CREATE TABLE IF NOT EXISTS `recommendeds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) DEFAULT NULL,
  `created` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `recommendeds`
--

INSERT INTO `recommendeds` (`id`, `name`, `created`) VALUES
(1, 'sugerencia', '2012-05-24'),
(2, '112', '2012-08-30'),
(3, 'PELI', '2012-09-07'),
(4, 'sugerencia 1', '2012-09-07'),
(5, 'sugerencia 1', '2012-09-07'),
(6, 'sugerencia 1', '2012-09-07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registers`
--

CREATE TABLE IF NOT EXISTS `registers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) NOT NULL,
  `created` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `registers`
--

INSERT INTO `registers` (`id`, `email`, `created`) VALUES
(1, 'ronald_salazar19@hotmail.com', '2012-05-24'),
(2, 'ronald_salazar19@hotmail.com', '2012-05-24'),
(3, 'ronald_salazar19@hotmail.com', '2012-05-24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(50) DEFAULT NULL,
  `password` char(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(7, 'ronald_salazar19@hotmail.com', '20eaccdd2e3766e53906a723202b7e44'),
(8, 'juanito', '671b7fa6fb0c818ad06b7e8596857740');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
