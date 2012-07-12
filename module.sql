# sql file for Manage Multisite module


INSERT INTO `language_text` VALUES ('en', '_module','manage_multi','Manage Subsites',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','manage_multi_text','This is a description of managing ATutor subsites.',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','config_multi_desc','Use the form below to setup a publicly accessible database, independent of this ATutor installation, that can be accessed read-only by all subsites created. Note that these preparations are essential:<br /> 1. Create a writable <span style="font-weight: bold;">include/config_manage_multisite.php</span> in ATutor root directory;<br />2. Provide a MySQL ID that has privileges to create database, create mysql account and grant privilege.',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_EMPTY_ADMIN_USER','Administrator username cannot be empty.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_INVALID_ADMIN_USER','Administrator username is not valid.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_EMPTY_ADMIN_PWD','Administrator password cannot be empty.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_EMPTY_ADMIN_EMAIL','Administrator email cannot be empty.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_INVALID_ADMIN_EMAIL','Administrator email is not valid.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_EMPTY_SITE_NAME','Site name cannot be empty.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_EMPTY_CONTACT_EMAIL','Contact email cannot be empty.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_INVALID_CONTACT_EMAIL','Contact email is not valid.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_EMPTY_PERSONAL_ACCT','Personal Account Username cannot be empty.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_INVALID_PERSONAL_ACCT','Personal Account Username is not valid.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_SAME_PERSONAL_ADMIN_ACCT','That Personal Account Username is already being used for the Administrator account, choose another.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_EMPTY_PERSONAL_PWD','Personal Account Password cannot be empty.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_EMPTY_PERSONAL_EMAIL','Personal Account email cannot be empty.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_INVALID_PERSONAL_EMAIL','Invalid Personal Account email is not valid.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_EMPTY_FIRST_NAME','Personal Account First Name cannot be empty.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_EMPTY_LAST_NAME','Personal Account Last Name cannot be empty.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_UNABLE_CONNECT_DB','Unable to connect to database server.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_LOW_MYSQL_VERSION','MySQL version %s was detected. ATutor requires version 4.1.10 or later.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_UNABLE_SELECT_DB','Unable to select or create database %s.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_FEEDBACK_DB_CREATED','Database %s created successfully.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_DB_NOT_UTF8','Database %s is not in UTF8.  Please set the database character set to UTF8 before continuing by using the following query: <br /> ALTER DATABASE `%s` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci.  <br />To use ALTER DATABASE, you need the ALTER privilege on the database.  You can also check the MySQL manual <a href="http://dev.mysql.com/doc/refman/4.1/en/alter-database.html" target="mysql_window">here</a>.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_WARNING_MANAGE_MULTISITE_DB_EXISTS','The database for managing subsites already exists and is named "%s". Be cautious that recreating the database with another name will cause the loss of the existing subsites information.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_FILE_NOT_WRITABLE','%s is not writable.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_FEEDBACK_FILE_CREATED','%s is writable and created.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_CONFIG_FILE_NOT_EXIST','%s does not exist.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_FEEDBACK_TABLE_CREATED','Table %s created successfully.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_FEEDBACK_TABLE_EXIST','Table %s already exists. Skipping.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_CREATE_TABLE_FAIL','Table %s creation failed.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_FEEDBACK_TABLE_ALTERED','Table %s altered successfully.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_FEEDBACK_TABLE_FIELD_EXIST','Table %s fields already exists. Skipping.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_FEEDBACK_TABLE_FIELD_DROPPED','Table %s fields already dropped. Skipping.',now(),'');
INSERT INTO `language_text` VALUES ('en', '_msgs','AT_ERROR_ALTER_TABLE_FAIL','Table %s alteration failed.',now(),'');


#MM_CREATE_DB_SUCCESSFUL
#MM_CREATE_TABLE_FAILED
#MM_CREATE_DB_FAILED
#AT_CONFIRM_DELETE_SUBSITE