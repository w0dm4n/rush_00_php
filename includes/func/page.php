<?php
	function GetPage()
	{
		$page = $_GET["page"];
		if (empty($page))
			include('pages/home.php');
		else
		{
			if (file_exists('pages/'.$page.'.php'))
				include('pages/'.$page.'.php');
			else
				include('pages/error.php');
		}
	}
?>