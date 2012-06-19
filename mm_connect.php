<?php

if(!isset($db_mm)){
		$db_mm = mysql_connect($_config['mm_mysql_server.'].':'.$_config['mm_mysql_port'], $_config['mm_mysql_user'], $_config['mm_mysql_password'], true);
		$selected_db = mysql_select_db($_config['mm_mysql_db_name'], $db_mm);
		
}
?>