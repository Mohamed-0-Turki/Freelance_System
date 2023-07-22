<?php
session_start();
require ('initialize.php');
if (isset($_COOKIE)) {
	$userID = $_COOKIE['ID'];
	$stmt = $CONDB->prepare("SELECT * FROM `users` WHERE `ID` = ? LIMIT 1");
	$stmt->execute([$userID]);
	$row = $stmt->fetch();
	$count = $stmt->rowCount();
	$stmt = null;
	print_r($row);
	if ($count === 1) {
		$_SESSION['ID']     = $row['ID'];
		$_SESSION['NAME']   = $row['Name'];
		$_SESSION['EMAIL']  = $row['Email'];
		$_SESSION['ACCESS'] = $row['UserRole'];
		if ($_SESSION['ACCESS'] === 'Admin') {
			header('Location: ./users/dashboard.php');
			exit();
		}
		else {
			if ($_SESSION['ACCESS'] === 'Client') {
				header('Location: ./main.php');
				exit();
				}
				else {
					header('Location: ./main.php');
					exit();
				}
		}
	}
	else {
		header('Location: ./main.php');
		exit();
	}
}
else {
	header('Location:./main.php');
	exit();
}
?>