#####################################################
# Database setup SQL for managing ATutor multisite
#####################################################

# --------------------------------------------------------
# Table structure for table `subsites`
# since 2.1

CREATE TABLE `subsites` (
   `site_url` varchar(255) NOT NULL,
   `enabled` tinyint(1) NOT NULL DEFAULT '1',
   PRIMARY KEY ( `site_url` )
) ENGINE = MyISAM;
