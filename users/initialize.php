<?php

    //! Connection File.
    require ('../connection.php');

    //! Routes
    $func = 'includes/functions/';
    $temp = 'includes/templates/';
    $pages = 'includes/pages/';
    $img = 'layout/images/';
    $css = 'layout/css/';
    $js = 'layout/js/';


	//! Include Important Files
	require $func . ('functions.php');
	require $temp . ('header.php');