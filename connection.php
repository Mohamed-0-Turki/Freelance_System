<?php
	$dsn = 'mysql:host=localhost;dbname=freelance';
	$user = 'root';
	$pass = '';
	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',);
	try {
		$CONDB = new PDO($dsn, $user, $pass, $options);
		$CONDB -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
			echo 'Faild ' . $e -> getMessage();
	}