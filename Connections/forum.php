<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_forum = "localhost";
$database_forum = "forum";
$username_forum = "admin";
$password_forum = "123456";
$forum = mysql_pconnect($hostname_forum, $username_forum, $password_forum) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_query("set names uf8");
?>