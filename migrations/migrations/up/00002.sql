-- --------------------------------------------------------
-- This is the script for migrate up
-- from version '1' to version '2'
-- --------------------------------------------------------

BEGIN;

CREATE TABLE `tokens` (
	`tokenId` INT NOT NULL AUTO_INCREMENT,
	`userId` VARCHAR(80) NOT NULL,
	`datetime` VARCHAR(32) NOT NULL,
	`type` INT(2) NOT NULL,
	PRIMARY KEY (`tokenId`),
	UNIQUE INDEX `datetime_UNIQUE` (`datetime` ASC),
	INDEX `userId_INDEX` (`userId` ASC)
);

COMMIT;
