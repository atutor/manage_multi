CREATE TABLE `subsites` (
	`site_id` mediumint(10) NOT NULL AUTO_INCREMENT,
	`site_name` mediumint(10) NOT NULL DEFAULT '0',
	`site_URL` varchar(255) NOT NULL,
	`site_type` ENUM('domain', 'directory'),
	`directory` varchar(255) NOT NULL, 
	`enabled` tinyint(1) NOT NULL DEFAULT '1',
	PRIMARY KEY ( `site_id` )
) ENGINE = MyISAM;