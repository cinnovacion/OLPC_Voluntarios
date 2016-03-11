-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Machine: 127.0.0.1
-- Gegenereerd op: 03 mrt 2016 om 20:37
-- Serverversie: 5.6.21
-- PHP-versie: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `olpc_voluntarios`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `admins`
--

CREATE TABLE IF NOT EXISTS `admins` (
`idAdmins` int(11) NOT NULL,
  `Nombre` varchar(45) NOT NULL,
  `contraseña` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `admins`
--

INSERT INTO `admins` (`idAdmins`, `Nombre`, `contraseña`) VALUES
(1, 'vince', 'wA2EsihGov1456950288fcf3c4a6e987332090176b4270680bf14aecea1e68cf4cdb37af5ad5afeab634'),
(3, 'rein', 'sV4kuPq3QO1456762846b2814d431cdb1ff2b5021b64fd97d1e709ade5a51e28aa32d2ff7ec4a7a068bf'),
(4, 'sofana', 'RQlsUN0jyl1456952344101c44d5c55191db46c6bcbaeb21104db0bfed9fa53324887618ae633664553c');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `disponibilidad`
--

CREATE TABLE IF NOT EXISTS `disponibilidad` (
`idDisponibilidad` int(11) NOT NULL,
  `Persona_idPersona` int(11) NOT NULL,
  `horaInicio` varchar(45) DEFAULT NULL,
  `horaFinal` varchar(45) DEFAULT NULL,
  `dia` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `disponibilidad`
--

INSERT INTO `disponibilidad` (`idDisponibilidad`, `Persona_idPersona`, `horaInicio`, `horaFinal`, `dia`) VALUES
(57, 6, '8:30 AM', '5:00 PM', 'lunes'),
(58, 7, '8:30 AM', '4:00 PM', 'lunes'),
(59, 7, '9:30 AM', '5:00 PM', 'martes'),
(60, 7, '8:00 AM', '5:00 PM', 'viernes'),
(65, 1, '8:50 AM', '5:00 PM', 'lunes'),
(66, 1, '8:50 AM', '5:00 PM', 'martes'),
(67, 1, '8:30 AM', '5:00 AM', 'miercoles'),
(68, 1, '8:30 AM', '5:00 PM', 'jueves');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `persona`
--

CREATE TABLE IF NOT EXISTS `persona` (
`idPersona` int(11) NOT NULL,
  `Nombre` varchar(255) DEFAULT NULL,
  `NoDeCedula` varchar(45) DEFAULT NULL,
  `DireccionDeResidencia` varchar(1024) DEFAULT NULL,
  `Telefono` varchar(45) DEFAULT NULL,
  `CorreoElectronico` varchar(255) DEFAULT NULL,
  `InstitucionAcademica` varchar(255) DEFAULT NULL,
  `CarreraCurso` varchar(255) DEFAULT NULL,
  `Nivel` varchar(45) DEFAULT NULL,
  `DiaInicio` varchar(45) DEFAULT NULL,
  `DiaFinal` varchar(45) DEFAULT NULL,
  `Area` int(11) NOT NULL,
  `Sexo` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `persona`
--

INSERT INTO `persona` (`idPersona`, `Nombre`, `NoDeCedula`, `DireccionDeResidencia`, `Telefono`, `CorreoElectronico`, `InstitucionAcademica`, `CarreraCurso`, `Nivel`, `DiaInicio`, `DiaFinal`, `Area`, `Sexo`) VALUES
(1, 'Rein Bauwens', '98764321', 'BElgie', '456789', 'rein.bauwens@mail.be', 'odisee', 'ict', '3', '31/01/2016', '15/04/2016', 1, 1),
(2, 'vince dobbelaere', '123456', 'belgie', '456316853', 'vince.dobbelaere@mail.be', 'odisee', 'ict', '3', '2016-02-01', '2016-04-22', 1, 1),
(3, 'Brian Membreno Martinez', '001-240695-0022R', 'Americas 3, CASA B-2183', '96780610', 'mm.arg95@gmail.com', 'VAM', 'Arquitectura', '5', '2016-02-01', '2016-02-29', 1, 1),
(4, 'Herman Ruiz', '456789', 'managua nicaragua', '123456', 'herman.ruiz@mail.nica', 'olpc', 'voluntario', '1', '2016-02-01', '2016-02-29', 1, 1),
(5, 'gert jan piet', '789456', 'Schoonaarde', '4567816', 'gert@mail.be', 'odisee', 'ict', '2', NULL, NULL, 1, 1),
(6, 'Carlos', '003-250695-0055X', 'managua', '15648496', 'carlos@mail.nica', 'olpc', 'olpc', 'olpc', '28/02/2016', '09/04/2016', 1, 1),
(7, 'Sofana Barreto', '001-030693-0003L', 'Colonia del Periodista', '86678054', 'sbarreto@fundacionzt.org', 'UAM', 'Diplomacia', 'Egresada', '02/03/2016', '31/03/2016', 1, 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `trabajar`
--

CREATE TABLE IF NOT EXISTS `trabajar` (
`idTrabajar` int(11) NOT NULL,
  `Persona_idPersona` int(11) NOT NULL,
  `tiempo` double DEFAULT NULL,
  `horaInicio` time DEFAULT NULL,
  `horaFinal` time DEFAULT NULL,
  `dia` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `trabajar`
--

INSERT INTO `trabajar` (`idTrabajar`, `Persona_idPersona`, `tiempo`, `horaInicio`, `horaFinal`, `dia`) VALUES
(1, 1, 8, '08:00:00', '16:00:00', '2016-02-23'),
(2, 1, 8, '08:00:00', '16:00:00', '2016-02-23'),
(3, 1, 8, '08:00:00', '16:00:00', '2016-02-24'),
(4, 1, 8, '08:00:00', '16:00:00', '2016-02-25'),
(5, 1, 8, '08:00:00', '16:00:00', '2016-02-26'),
(6, 1, 8, '08:00:00', '16:00:00', '2016-02-28'),
(7, 2, 8, '08:00:00', '16:00:00', '2016-02-27'),
(8, 4, 8, '08:00:00', '16:00:00', '2016-02-27'),
(30, 1, 0, '09:00:00', '09:50:00', '2016-02-29'),
(31, 6, 2, '03:48:00', '05:48:00', '2016-03-01'),
(32, 7, 4, '00:24:00', '04:25:00', '2016-03-02'),
(33, 4, 0, '06:30:00', '06:31:00', '2016-03-02'),
(34, 2, 8, '02:17:00', '10:17:00', '2016-03-02'),
(49, 7, NULL, '08:21:00', NULL, '2016-03-03'),
(50, 6, NULL, '08:21:00', NULL, '2016-03-03'),
(51, 2, 54000, '05:26:00', '08:26:00', '2016-03-03'),
(52, 2, 900, '05:28:00', '08:28:00', '2016-03-03'),
(53, 2, NULL, '00:30:00', NULL, '2016-03-03'),
(54, 2, 43200, '08:30:00', '08:30:00', '2016-03-03'),
(55, 2, NULL, '07:59:00', NULL, '2016-03-03');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `admins`
--
ALTER TABLE `admins`
 ADD PRIMARY KEY (`idAdmins`);

--
-- Indexen voor tabel `disponibilidad`
--
ALTER TABLE `disponibilidad`
 ADD PRIMARY KEY (`idDisponibilidad`), ADD UNIQUE KEY `idDisponibilidad_UNIQUE` (`idDisponibilidad`), ADD KEY `fk_Disponibilidad_Persona_idx` (`Persona_idPersona`);

--
-- Indexen voor tabel `persona`
--
ALTER TABLE `persona`
 ADD PRIMARY KEY (`idPersona`);

--
-- Indexen voor tabel `trabajar`
--
ALTER TABLE `trabajar`
 ADD PRIMARY KEY (`idTrabajar`), ADD UNIQUE KEY `idDisponibilidad_UNIQUE` (`idTrabajar`), ADD KEY `fk_Disponibilidad_Persona_idx` (`Persona_idPersona`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `admins`
--
ALTER TABLE `admins`
MODIFY `idAdmins` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT voor een tabel `disponibilidad`
--
ALTER TABLE `disponibilidad`
MODIFY `idDisponibilidad` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=69;
--
-- AUTO_INCREMENT voor een tabel `persona`
--
ALTER TABLE `persona`
MODIFY `idPersona` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT voor een tabel `trabajar`
--
ALTER TABLE `trabajar`
MODIFY `idTrabajar` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=57;
--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `disponibilidad`
--
ALTER TABLE `disponibilidad`
ADD CONSTRAINT `fk_Disponibilidad_Persona` FOREIGN KEY (`Persona_idPersona`) REFERENCES `persona` (`idPersona`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `trabajar`
--
ALTER TABLE `trabajar`
ADD CONSTRAINT `fk_Disponibilidad_Persona0` FOREIGN KEY (`Persona_idPersona`) REFERENCES `persona` (`idPersona`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
