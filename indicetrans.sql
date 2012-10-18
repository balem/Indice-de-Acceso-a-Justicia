-- phpMyAdmin SQL Dump
-- version 3.4.7
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 18-10-2012 a las 13:13:38
-- Versión del servidor: 5.0.77
-- Versión de PHP: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `indicetrans`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contexto`
--

CREATE TABLE IF NOT EXISTS `contexto` (
  `cont_id` int(11) NOT NULL auto_increment,
  `cont_descripcion` varchar(200) NOT NULL,
  PRIMARY KEY  (`cont_id`),
  UNIQUE KEY `descripcion_UNIQUE` (`cont_descripcion`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuestas`
--

CREATE TABLE IF NOT EXISTS `encuestas` (
  `enc_id` int(11) NOT NULL auto_increment,
  `enc_titulo` varchar(300) NOT NULL,
  `enc_usuario` int(11) NOT NULL,
  `enc_fecha` date NOT NULL,
  `enc_tipo_usuario` varchar(25) NOT NULL default 'usuarios',
  PRIMARY KEY  (`enc_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuestas_carga`
--

CREATE TABLE IF NOT EXISTS `encuestas_carga` (
  `carga_id` int(11) NOT NULL auto_increment,
  `carga_enc_id` int(11) NOT NULL,
  `carga_var_id` int(11) NOT NULL,
  `carga_alias` text NOT NULL,
  PRIMARY KEY  (`carga_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=272 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuestas_resultado`
--

CREATE TABLE IF NOT EXISTS `encuestas_resultado` (
  `enc_res_id` int(11) NOT NULL auto_increment,
  `enc_res_nro_enc` int(11) NOT NULL,
  `enc_res_enc_id` int(11) NOT NULL,
  `enc_res_enc_val` int(11) NOT NULL,
  `enc_res_valor` varchar(5) NOT NULL,
  `enc_res_comentario` text,
  `enc_res_usr_votante` int(11) NOT NULL,
  PRIMARY KEY  (`enc_res_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=79 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factor`
--

CREATE TABLE IF NOT EXISTS `factor` (
  `fac_id` int(11) NOT NULL auto_increment,
  `fac_nombre` varchar(350) character set latin1 collate latin1_bin NOT NULL,
  PRIMARY KEY  (`fac_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo`
--

CREATE TABLE IF NOT EXISTS `tipo` (
  `tipo_id` int(11) NOT NULL auto_increment,
  `tipo_nombre` varchar(65) character set latin1 collate latin1_bin NOT NULL,
  PRIMARY KEY  (`tipo_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `usr_id` int(11) NOT NULL auto_increment,
  `usr_name` varchar(45) default NULL,
  `usr_passwrd` varchar(100) NOT NULL,
  `usr_mail` varchar(100) default NULL,
  `usr_fecha` date default NULL,
  `usr_level_acces` varchar(25) NOT NULL default 'usuario',
  PRIMARY KEY  (`usr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `variable`
--

CREATE TABLE IF NOT EXISTS `variable` (
  `var_id` int(11) NOT NULL auto_increment,
  `var_nombre` text NOT NULL,
  `var_contexto` int(11) NOT NULL,
  `var_factor` int(11) NOT NULL,
  `var_tipo` int(11) NOT NULL,
  `var_puntaje` int(5) NOT NULL default '0',
  `var_normativa` text NOT NULL,
  PRIMARY KEY  (`var_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=148 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vvariable`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`indicetrans`@`%` SQL SECURITY DEFINER VIEW `indicetrans`.`vvariable` AS select `var`.`id` AS `id`,`var`.`nombre` AS `nombre`,`con`.`descripcion` AS `contexto`,`fa`.`nombre` AS `factor`,`tip`.`nombre` AS `tipo` from (((`indicetrans`.`variable` `var` join `indicetrans`.`contexto` `con`) join `indicetrans`.`factor` `fa`) join `indicetrans`.`tipo` `tip`) where ((`tip`.`id` = `var`.`tipo`) and (`fa`.`id` = `var`.`factor`) and (`con`.`id` = `var`.`contexto`));

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
