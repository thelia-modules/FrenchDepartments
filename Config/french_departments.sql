SET FOREIGN_KEY_CHECKS = 0;

SELECT @max := MAX(`id`) FROM `state`;
SET @max := @max+1;

SET FOREIGN_KEY_CHECKS = 1;
