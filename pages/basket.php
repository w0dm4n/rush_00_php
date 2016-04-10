<?php
	if (isset($_GET["add"]))
	{
		if (!empty($_GET["product_id"]))
		{
			$id = intval($_GET["product_id"]);
			$database = new Database();
			$database->StartConnection(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
			$database->Query('SELECT * FROM products WHERE id = "'.$id.'"');
			$database->Get_Rows();
			if ($database->c_rows)
			{
				$database->Fetch_Assoc();
				if (intval($database->c_assoc["stock"]) > 0)
				{
					if (empty($_SESSION["basket"]))
						$_SESSION["basket"] = ''.$id.':1';
					else
					{
						$array = explode(";", $_SESSION["basket"]);
						$found = false;
						foreach ($array as $value)
						{
							$a2 = explode(':', $value);
							$value = intval($a2[1]);
							if (intval($a2[0]) == $id)
							{
								$found = true;
								$_SESSION["basket"] = str_replace(''.$id.':'.$value.'', ''.$id.':'.($value + 1).'', $_SESSION["basket"]);
							}
						}
						if (!$found)
							$_SESSION["basket"] = ''.$_SESSION["basket"].';'.$id.':1';
					}
					redirect('vitrine', 0);
				}
			}
			else
				redirect('vitrine', 0);
		}	
	}
	else if (isset($_GET["delete"]))
	{
		if (!empty($_GET["product_id"]))
		{
			$id = intval($_GET["product_id"]);
			$array = explode(";", $_SESSION["basket"]);
			$i = 1;
			foreach ($array as $value)
			{
				$in = explode(":", $value);
				$id_a = $in[0];
				$quantity = $in[1];
				if ($id == $id_a)
				{
					if ($i != sizeof($array))
						$_SESSION["basket"] = str_replace(''.$id.':'.$quantity.';', '', $_SESSION["basket"]);
					else
						$_SESSION["basket"] = str_replace(''.$id.':'.$quantity.'', '', $_SESSION["basket"]);
				}
				$i++;
			}
			$result = preg_match("/[^;]/", $_SESSION["basket"]);
			if (!$result)
				$_SESSION["basket"] = '';
			redirect('vitrine', 0);
		}
	}
?>