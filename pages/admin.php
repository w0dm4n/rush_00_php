<?php
	if (isset($_SESSION["account"]))
	{
		$database = new Database();
		$database->StartConnection(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
		$database->Query('SELECT * FROM accounts WHERE account = "'.$_SESSION["account"].'"');
		$database->Fetch_Assoc();
		if ($database->c_assoc["admin"] == 1)
		{
			echo '<DIV id="middle_frame_error">
			<DIV>
				<BR/><center>Administration</center>
			</DIV>
			<DIV class="message">
				<center>';
			echo 'Salut master of sextoys, tu veux faire quoi ?<hr>';
			if (isset($_GET["action"]))
			{
				$action = secure($_GET["action"], 1);
				switch ($action)
				{
					case "set_admin":
						if (isset($_POST["set_admin"]))
						{
							if (!empty($_POST["user"]))
							{
								$user = secure($_POST["user"], 1);
								$database->Query('SELECT * FROM accounts WHERE account = "'.$user.'"');
								$database->Get_Rows();
								if ($database->c_rows)
								{
									$database->Query('UPDATE accounts SET admin = "1" WHERE account = "'.$user.'"');
									print_message('<b><i>'.$user.'</b></i> est maintenant un adminstrateur !', "success");
								}
								else
								{
									print_message("Cet utilisateur n'existe pas !", "error");
									redirect("admin", 3);
								}
							}
							else
							{
								print_message("Il manque l'utilisateur, essaye a nouveau !", "error");
								redirect("admin", 3);
							}
						}
						else
						{
							echo '<form method="post">
							<select style="min-width: 200px;min-height: 25px;" name="user">';
							$database->Query("SELECT * FROM accounts");
							while ($database->Fetch_Assoc())
							{
								echo '<option>'.$database->c_assoc["account"].'</option>';
							}
							echo '</select><br/><input type="submit" name="set_admin" value="Mettre le compte admin"/></form>';
						}
					break ;

					case "ban":
						if (isset($_POST["ban"]))
						{
							if (!empty($_POST["user"]))
							{
								$user = secure($_POST["user"], 1);
								$database->Query('SELECT * FROM accounts WHERE account = "'.$user.'"');
								$database->Get_Rows();
								if ($database->c_rows)
								{
									$database->Query('UPDATE accounts SET banned = "1" WHERE account = "'.$user.'"');
									print_message('<b><i>'.$user.'</b></i> est maintenant banni, au revoir :\'(', "success");
								}
								else
								{
									print_message("Cet utilisateur n'existe pas !", "error");
									redirect("admin", 3);
								}
							}
							else
							{
								print_message("Il manque l'utilisateur, essaye a nouveau !", "error");
								redirect("admin", 3);
							}
						}
						else
						{
							echo '<form method="post">
							<select style="min-width: 200px;min-height: 25px;" name="user">';
							$database->Query("SELECT * FROM accounts");
							while ($database->Fetch_Assoc())
							{
								echo '<option>'.$database->c_assoc["account"].'</option>';
							}
							echo '</select><br/><input type="submit" name="ban" value="Bannir l\'utilisateur"/></form>';
						}
					break ;

					case "unban":
						if (isset($_POST["unban"]))
						{
							if (!empty($_POST["user"]))
							{
								$user = secure($_POST["user"], 1);
								$database->Query('SELECT * FROM accounts WHERE account = "'.$user.'"');
								$database->Get_Rows();
								if ($database->c_rows)
								{
									$database->Query('UPDATE accounts SET banned = "0" WHERE account = "'.$user.'"');
									print_message('<b><i>'.$user.'</b></i> est maintenant débanni, youhou :D', "success");
								}
								else
								{
									print_message("Cet utilisateur n'existe pas !", "error");
									redirect("admin", 3);
								}
							}
							else
							{
								print_message("Il manque l'utilisateur, essaye a nouveau !", "error");
								redirect("admin", 3);
							}
						}
						else
						{
							$database->Query('SELECT * FROM accounts WHERE banned = "1"');
							$database->Get_Rows();
							if ($database->c_rows)
							{
								echo '<form method="post">
								<select style="min-width: 200px;min-height: 25px;" name="user">';
								while ($database->Fetch_Assoc())
								{
									echo '<option>'.$database->c_assoc["account"].'</option>';
								}
								echo '</select><br/><input type="submit" name="unban" value="Débannir l\'utilisateur"/></form>';
							}
							else
								print_message("Il n'y a aucun utilisateur banni !", "error");
						}
					break ;

					case "add_product":
						$product = new Product();
						$product->AddNewProduct();
					break ;

					case "delete_product":
						$product = new Product();
						$product->DeleteProduct();
					break ;

					case "edit_product":
						$product = new Product();
						$product->EditProduct();
					break ;

					default:
						redirect("admin", 0);
					break ;
				}
			}
			else
			{
				echo '<a href="?page=admin&action=set_admin">Mettre un compte admin</a>
					<br/><a href="?page=admin&action=ban">Bannir un utilisateur</a>
					<br/><a href="?page=admin&action=unban">Débannir un utilisateur</a>
					<hr>
					<a href="?page=admin&action=add_product">Ajouter un produit</a>
					<br/><a href="?page=admin&action=delete_product">Supprimer un produit</a>
					<br/><a href="?page=admin&action=edit_product">Modifier un produit</a>';
			}
			echo '</center>
				</DIV>
				</DIV>';
		}
		else
		{
			echo '<DIV id="middle_frame_error">
			<DIV>
				<BR/><center>Administration</center>
			</DIV>
			<DIV class="message">
				<center>';
			print_message("L'accès a cette page est restreint, désolé :(", "error");
			echo '</center>
				</DIV>
				</DIV>';

		}
	}
	else
		redirect("home", 0);
?>