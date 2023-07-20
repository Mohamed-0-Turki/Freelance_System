<?php
	$dsn = 'mysql:host=sql305.epizy.com;dbname=epiz_34160046_freelance';
	$user = 'epiz_34160046';
	$pass = '6vYvQ927uwdnFtO';
	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');
	try {
		$CONDB = new PDO($dsn, $user, $pass, $options);
		$CONDB -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
			echo 'Faild ' . $e -> getMessage();
	}