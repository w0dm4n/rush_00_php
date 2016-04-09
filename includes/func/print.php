<?php
function print_message($message, $type)
{
	switch ($type)
	{
		case "success":
			echo '<span style="color:green">'.$message.'</span>';
		break ;

		case "error":
			echo '<span style="color:red">'.$message.'</span>';
		break ;
	}
}
?>