<?php
session_start();
if (isset($_COOKIE)) {
	$_SESSION['ID']     = $_COOKIE['ID'];
	$_SESSION['NAME']   = $_COOKIE['NAME'];
	$_SESSION['ACCESS'] = $_COOKIE['ACCESS'];
	$_SESSION['EMAIL'] 	= $_COOKIE['EMAIL'];
	if ($_SESSION['ACCESS'] == 'Admin') {
		header('Location: users/dashboard.php');
		exit();
	}
	else {
		if ($_SESSION['ACCESS'] == 'Client') {
			header('Location: main.php');
			exit();
			}
			else {
				header('Location: main.php');
				exit();
			}
	}
}
else {
	header('Location: main.php');
	exit();
}
?>