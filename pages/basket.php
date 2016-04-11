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
				else
					redirect('vitrine', 0);
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
	else if (isset($_GET["validate"]))
	{
		echo '<DIV id="middle_frame_error">
			<DIV>
				<BR/><center>Validation de Panier</center>
			</DIV>
			<DIV class="message"><center>';
			if (!empty($_SESSION["basket"]))
			{
				if (!empty($_SESSION["account"]))
				{
					$error = false;
					$explode = explode(";", $_SESSION["basket"]);
					foreach ($explode as $value)
					{
						$ex2 = explode(":", $value);
						$id = $ex2[0];
						$quantity = $ex2[1];
						$database = new Database();
						$database->StartConnection(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
						$database->Query('SELECT * FROM products WHERE id = "'.$id.'"');
						$database->Get_Rows();
						if ($database->c_rows)
						{
							$database->Fetch_Assoc();
							if (intval($quantity) <= intval($database->c_assoc["stock"]))
							{
								if (!$error)
									$database->Query('UPDATE products SET stock = "'.($database->c_assoc["stock"] - $quantity).'" WHERE id = "'.$id.'"');
							}
							else
							{
								$error = true;
								print_message('Stock insuffisant pour l\'article <b><i>'.$database->c_assoc["name"].'</i></b><br/>', "error");
							}
						}
					}
					if (!$error)
					{
						print_message("Votre commande a bien été validé et sera pris en compte dans les plus brefs délais !", "success");
						$database->Query('INSERT INTO orders(all_product,by_who) VALUES("'.$_SESSION["basket"].'", "'.$_SESSION["account"].'")');
						$_SESSION["basket"] = "";
					}
				}
				else
				{
					print_message("Vous devez être connecté pour valider votre panier !", "error");
				}
			}
			else
				print_message("Votre panier est vide", "error");
		echo '</center>
			</DIV>
		</DIV>';
	}
?>