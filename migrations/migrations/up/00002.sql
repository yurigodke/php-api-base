-- --------------------------------------------------------
-- This is the script for migrate up
-- from version '1' to version '2'
-- --------------------------------------------------------

BEGIN;

CREATE TABLE `tokens` (
	`tokenId` VARCHAR(32) NOT NULL,
	`userId` VARCHAR(80) NOT NULL,
	`datetime` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`type` INT(2) NOT NULL,
	PRIMARY KEY (`tokenId`),
	UNIQUE INDEX `datetime_UNIQUE` (`datetime` ASC),
	INDEX `userId_INDEX` (`userId` ASC)
);

COMMIT;
