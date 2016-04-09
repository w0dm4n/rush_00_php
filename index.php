<?php
	session_start();
	require_once("includes/config.php");
	if (check_installation())
	{
		require_once("includes/header.php");
		require_once("includes/menuLeft.php");
		GetPage();
		require_once("includes/basket.php");
	}
?>