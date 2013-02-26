#####################################################
# Database setup SQL for managing ATutor multisite
#####################################################

# --------------------------------------------------------
# Table structure for table `subsites`
# since 2.1

CREATE TABLE IF NOT EXISTS `subsites` (
  `site_url` varchar(255) NOT NULL,
  `version` varchar(25) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`site_url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
