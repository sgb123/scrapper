SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `address` (
  `id`                     INT(11)      NOT NULL AUTO_INCREMENT,
  `address_url`            VARCHAR(255) NOT NULL,
  `line_1`                 VARCHAR(255) NOT NULL,
  `line_2`                 VARCHAR(255) NOT NULL,
  `address_owner_id`       INT(11) DEFAULT NULL,
  `address_details_id`     INT(11) DEFAULT NULL,
  `address_census_data_id` INT(11) DEFAULT NULL,
  `address_education_id`   INT(11) DEFAULT NULL,
  `address_income_id`      INT(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `address_owner_id` (`address_owner_id`),
  KEY `address_details_id` (`address_details_id`),
  KEY `address_census_data_id` (`address_census_data_id`),
  KEY `address_education_id` (`address_education_id`),
  KEY `address_income_id` (`address_income_id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `address_census_data` (
  `id`         INT(11) NOT NULL AUTO_INCREMENT,
  `households` INT(11) NOT NULL,
  `families`   FLOAT   NOT NULL,
  `male`       FLOAT   NOT NULL,
  `female`     FLOAT   NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `address_details` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `acres`       FLOAT DEFAULT NULL,
  `bedrooms`    INT(11) DEFAULT NULL,
  `bathrooms`   INT(11) DEFAULT NULL,
  `built_year`  INT(11) DEFAULT NULL,
  `land_area`   INT(11) DEFAULT NULL,
  `living_area` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `address_education` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `high_school` FLOAT   NOT NULL,
  `college`     FLOAT   NOT NULL,
  `graduate`    FLOAT   NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `address_income` (
  `id`            INT(11) NOT NULL AUTO_INCREMENT,
  `average`       INT(11) NOT NULL,
  `less_10`       FLOAT   NOT NULL,
  `slice_10_15`   FLOAT   NOT NULL,
  `slice_15_25`   FLOAT   NOT NULL,
  `slice_25_35`   FLOAT   NOT NULL,
  `slice_35_50`   FLOAT   NOT NULL,
  `slice_50_75`   FLOAT   NOT NULL,
  `slice_75_100`  FLOAT   NOT NULL,
  `slice_100_150` FLOAT   NOT NULL,
  `slice_150_200` FLOAT   NOT NULL,
  `more_200`      FLOAT   NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `address_neighbor` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `full_name`  VARCHAR(255) NOT NULL,
  `address_id` INT(11)      NOT NULL,
  PRIMARY KEY (`id`),
  KEY `address_id` (`address_id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `address_owner` (
  `id`               INT(11)      NOT NULL AUTO_INCREMENT,
  `full_name`        VARCHAR(255) NOT NULL,
  `est_market_value` INT(11)      NOT NULL,
  `tax_amount`       INT(11)      NOT NULL,
  `tax_year`         INT(11)      NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `email` (
  `id`    INT(11)      NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `person` (
  `id`          INT(11)      NOT NULL AUTO_INCREMENT,
  `profile_url` TEXT         NOT NULL,
  `first_name`  VARCHAR(255) NOT NULL,
  `last_name`   VARCHAR(255) NOT NULL,
  `age`         INT(11) DEFAULT NULL,
  `processed`   TIMESTAMP    NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `person_address` (
  `id`         INT(11) NOT NULL AUTO_INCREMENT,
  `person_id`  INT(11) NOT NULL,
  `address_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `person_id_address_id` (`person_id`, `address_id`),
  KEY `person_id` (`person_id`),
  KEY `address_id` (`address_id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `person_email` (
  `id`        INT(11) NOT NULL AUTO_INCREMENT,
  `person_id` INT(11) NOT NULL,
  `email_id`  INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `person_id_email_id` (`person_id`, `email_id`),
  KEY `person_id` (`person_id`),
  KEY `email_id` (`email_id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `person_person` (
  `id`               INT(11) NOT NULL AUTO_INCREMENT,
  `parent_person_id` INT(11) NOT NULL,
  `child_person_id`  INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parent_person_id_child_person_id` (`parent_person_id`, `child_person_id`),
  KEY `parent_person_id` (`parent_person_id`),
  KEY `child_person_id` (`child_person_id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `person_search_form_history` (
  `id`              INT(11)   NOT NULL AUTO_INCREMENT,
  `first_name`      VARCHAR(255) DEFAULT NULL,
  `last_name`       VARCHAR(255) DEFAULT NULL,
  `city_state`      VARCHAR(255) DEFAULT NULL,
  `phone_area_code` INT(11) DEFAULT NULL,
  `phone_prefix`    INT(11) DEFAULT NULL,
  `phone_exchange`  INT(11) DEFAULT NULL,
  `email`           VARCHAR(255) DEFAULT NULL,
  `updated`         TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `phone` (
  `id`         INT(11) NOT NULL AUTO_INCREMENT,
  `area_code`  INT(11) NOT NULL,
  `prefix`     INT(11) NOT NULL,
  `exchange`   INT(11) NOT NULL,
  `address_id` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `area_code_prefix_exchange` (`area_code`, `prefix`, `exchange`),
  KEY `address_id` (`address_id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `user` (
  `id`       INT(11)      NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;


ALTER TABLE `address`
ADD CONSTRAINT `address_ibfk_5` FOREIGN KEY (`address_income_id`) REFERENCES `address_income` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`address_owner_id`) REFERENCES `address_owner` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `address_ibfk_2` FOREIGN KEY (`address_details_id`) REFERENCES `address_details` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `address_ibfk_3` FOREIGN KEY (`address_census_data_id`) REFERENCES `address_census_data` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `address_ibfk_4` FOREIGN KEY (`address_education_id`) REFERENCES `address_education` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `address_neighbor`
ADD CONSTRAINT `address_neighbor_ibfk_1` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `person_address`
ADD CONSTRAINT `person_address_ibfk_2` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `person_address_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `person_email`
ADD CONSTRAINT `person_email_ibfk_2` FOREIGN KEY (`email_id`) REFERENCES `email` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `person_email_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `person_person`
ADD CONSTRAINT `person_person_ibfk_2` FOREIGN KEY (`child_person_id`) REFERENCES `person` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `person_person_ibfk_1` FOREIGN KEY (`parent_person_id`) REFERENCES `person` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `phone`
ADD CONSTRAINT `phone_ibfk_1` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;