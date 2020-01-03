<?php

$host = '127.0.0.1'; // 고정 IP 바꾸기 전에 127.0.0.1
$user = 'root';
$pw = 'root';
$dbName = 'web_db';
return $db = mysqli_connect($host, $user, $pw, $dbName);

?>