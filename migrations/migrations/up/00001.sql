-- --------------------------------------------------------
-- This is the script for migrate up
-- from version '0' to version '1'
-- --------------------------------------------------------

BEGIN;

CREATE TABLE `example` (
	`exampleId` INT NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(60) NOT NULL,
	`email` VARCHAR(80) NOT NULL,
	`pass` VARCHAR(32) NOT NULL,
	PRIMARY KEY (`exampleId`),
	UNIQUE INDEX `email_UNIQUE` (`email` ASC),
	INDEX `pass_INDEX` (`pass` ASC)
);

COMMIT;
