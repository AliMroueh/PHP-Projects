<?php

    include 'connect.php';

    // Routes 
    $tpl = 'includes/templates/'; // Template Directory
    $lang = 'includes/languages/'; // Language directory
    $func = 'includes/functions/'; // Functions directory
    $css = 'layout/css/'; // Css Directory
    $js = 'layout/js/'; // Js Directory
   


    // Include the important files

    include $func . "functions.php";
    include $lang . "english.php";
    include $tpl . "header.php";

    // include navbar in all pages except the one with noNavbar
    if(!isset($noNavbar)){
    include $tpl . "navbar.php";
    }