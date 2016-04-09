<?php
	function secure($var, $type)
	{
		switch($type)
		{
			case 0:
				return (intval($var));
			break ;

			case 1:
				return (htmlentities(htmlspecialchars($var)));
			break ;
		}
	}
?>