SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema olpc_volentarios
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `olpc_volentarios` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `olpc_volentarios` ;

-- -----------------------------------------------------
-- Table `olpc_volentarios`.`Persona`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `olpc_volentarios`.`Persona` ;

CREATE TABLE IF NOT EXISTS `olpc_volentarios`.`Persona` (
  `idPersona` INT NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(255) NULL,
  `NoDeCedula` VARCHAR(45) NULL,
  `DireccionDeResidencia` VARCHAR(1024) NULL,
  `Telefono` VARCHAR(45) NULL,
  `CorreoElectronico` VARCHAR(255) NULL,
  `InstitucionAcademica` VARCHAR(255) NULL,
  `CarreraCurso` VARCHAR(255) NULL,
  `Nivel` VARCHAR(45) NULL,
  `DiaInicio` DATE NULL,
  `DiaFinal` DATE NULL,
  PRIMARY KEY (`idPersona`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `olpc_volentarios`.`Disponibilidad`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `olpc_volentarios`.`Disponibilidad` ;

CREATE TABLE IF NOT EXISTS `olpc_volentarios`.`Disponibilidad` (
  `idDisponibilidad` INT NOT NULL AUTO_INCREMENT,
  `Persona_idPersona` INT NOT NULL,
  `horaInicio` TIME NULL,
  `horaFinal` TIME NULL,
  `dia` VARCHAR(45) NULL,
  PRIMARY KEY (`idDisponibilidad`),
  INDEX `fk_Disponibilidad_Persona_idx` (`Persona_idPersona` ASC),
  UNIQUE INDEX `idDisponibilidad_UNIQUE` (`idDisponibilidad` ASC),
  CONSTRAINT `fk_Disponibilidad_Persona`
    FOREIGN KEY (`Persona_idPersona`)
    REFERENCES `olpc_volentarios`.`Persona` (`idPersona`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `olpc_volentarios`.`Trabajar`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `olpc_volentarios`.`Trabajar` ;

CREATE TABLE IF NOT EXISTS `olpc_volentarios`.`Trabajar` (
  `idTrabajar` INT NOT NULL AUTO_INCREMENT,
  `Persona_idPersona` INT NOT NULL,
  `tiempo` DOUBLE NULL,
  `horaInicio` TIME NULL,
  `horaFinal` TIME NULL,
  `dia` DATE NULL,
  PRIMARY KEY (`idTrabajar`),
  INDEX `fk_Disponibilidad_Persona_idx` (`Persona_idPersona` ASC),
  UNIQUE INDEX `idDisponibilidad_UNIQUE` (`idTrabajar` ASC),
  CONSTRAINT `fk_Disponibilidad_Persona0`
    FOREIGN KEY (`Persona_idPersona`)
    REFERENCES `olpc_volentarios`.`Persona` (`idPersona`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `olpc_volentarios`.`Admins`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `olpc_volentarios`.`Admins` ;

CREATE TABLE IF NOT EXISTS `olpc_volentarios`.`Admins` (
  `idAdmins` INT NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(45) NOT NULL,
  `contraseña` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`idAdmins`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
