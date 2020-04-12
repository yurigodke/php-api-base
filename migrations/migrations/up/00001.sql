-- --------------------------------------------------------
-- This is the script for migrate up
-- from version '0' to version '1'
-- --------------------------------------------------------

BEGIN;

CREATE TABLE `users` (
	`userId` INT NOT NULL AUTO_INCREMENT,
	`email` VARCHAR(80) NOT NULL,
	`pass` VARCHAR(32) NOT NULL,
	`level` INT(3) NOT NULL,
	PRIMARY KEY (`userId`),
	UNIQUE INDEX `email_UNIQUE` (`email` ASC),
	INDEX `pass_INDEX` (`pass` ASC)
);

COMMIT;
