<?php

	//! Connection File.
	require ('connection.php');

	//! Routes
	$temp = 'includes/templates/';
	$func = 'includes/functions/';
	$pages = 'includes/pages/';
	$css  = 'layout/css/';
	$js   = 'layout/js/';
	$img   = 'layout/images/';

	//! Include Important Files
	require $func . ('functions.php');
	require $temp . ('header.php');