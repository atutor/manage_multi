#####################################################
# Database setup SQL for managing ATutor multisite
#####################################################

# --------------------------------------------------------
# Table structure for table `subsites`
# since 2.1

CREATE TABLE `subsites` (
   `site_url` varchar(255) NOT NULL,
   `enabled` tinyint(1) NOT NULL DEFAULT '1',
   `version` DOUBLE NOT NULL,
   `created_date` DATETIME NOT NULL,
   PRIMARY KEY ( `site_url` )
) ENGINE = MyISAM;

# Alter in v1.1
# ALTER TABLE `subsites` ADD `version` DOUBLE NOT NULL AFTER `site_url` 
# ALTER TABLE `subsites` ADD `created_date` DATETIME NOT NULL AFTER `version`  