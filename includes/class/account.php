<?php
class Account
{
	public function CreateAccount()
	{
		if (!empty($_POST["account"]) && !empty($_POST["password"]) && !empty($_POST["password_conf"]))
		{
			$account = secure($_POST["account"], 1);
			$password = secure($_POST["password"], 1);
			if ($_POST["password"] != $_POST["password_conf"])
			{
				print_message("Les deux mot de passe ne sont pas identiques !", "error");
				redirect("register", 2);
			}
			else
			{
				if (strlen($account) < 6)
				{
					print_message("Votre nom de compte doit faire au moin 6 caract&egrave;re !", "error");
					redirect("register", 2);
				}
				else if (strlen($password) < 6)
				{
					print_message("Votre mot de passe doit faire au moin 6 caract&egrave;re !", "error");
					redirect("register", 2);
				}
				else
				{
					$database = new Database();
					$database->StartConnection(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
					$database->Query('SELECT * FROM accounts WHERE account = "'.$account.'"');
					$database->Get_Rows();
					if (!$database->c_rows)
					{
						$database->Query('INSERT INTO accounts(account,password,admin,banned,user_ip) VALUES("'.$account.'", "'.hash("whirlpool", $password).'", "0", "0", "'.$_SERVER["REMOTE_ADDR"].'")');
						print_message("Inscription r&eacute;ussi, redirection automatique sur l'index du site...", "success");
						redirect("home", 5);
						$_SESSION["account"] = $account;
					}
					else
					{
						print_message("Ce nom de compte existe déjà dans notre base de donnée !", "error");
						redirect("register", 2);
					}
				}
			}
		}
		else
		{
			print_message("Un champ est manquant !", "error");
			redirect("register", 2);
		}
	}

	public function Login()
	{
		if (!empty($_POST["login"]) && !empty($_POST["password"]))
		{
			$account = secure($_POST["login"], 1);
			$password = secure($_POST["password"], 1);

			$database = new Database();
			$database->StartConnection(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
			$database->Query('SELECT * FROM accounts WHERE account = "'.$account.'" AND password = "'.hash("whirlpool", $password).'"');
			$database->Get_Rows();
			if ($database->c_rows)
			{
				$database->Fetch_Assoc();
				if ($database->c_assoc["banned"] != "1")
				{
					$_SESSION["account"] = $account;
					redirect($_GET["page"], 0);
				}
				else
				{
					print_message("Votre compte a été banni !", "error");
					redirect($_GET["page"], 2);
				}
			}
			else
			{
				print_message("Nom de compte/mot de passe incorrect !", "error");
				redirect($_GET["page"], 2);
			}
		}
		else
		{
			print_message("Un champ est manquant !", "error");
			redirect($_GET["page"], 2);
		}
	}

	public function ChangePassword()
	{
		echo 'Tu as raison, la sécurité avant tout !
		<br/><br/>';
		if (isset($_POST["change"]))
		{
			if (!empty($_POST["old_password"]) && !empty($_POST["new_password"]) && !empty($_POST["new_password_conf"]))
			{
				$old = hash("whirlpool", secure($_POST["old_password"], 1));
				$new = secure($_POST["new_password"], 1);
				if ($_POST["new_password"] != $_POST["new_password_conf"])
				{
					print_message("Les deux nouveau mot de passe ne sont pas identique !", "error");
					redirect("profile&action=change_password", 3);
				}
				else
				{
					if (strlen($new) >= 6)
					{
						$database = new Database();
						$database->StartConnection(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
						$database->Query('SELECT * FROM accounts WHERE account = "'.$_SESSION["account"].'"');
						$database->Fetch_Assoc();
						if ($old == $database->c_assoc["password"])
						{
							$database->Query('UPDATE accounts SET password = "'.hash("whirlpool", $new).'" WHERE account = "'.$_SESSION["account"].'"');
							print_message("Votre mot de passe a été changé avec succès !", "success");
						}
						else
						{
							print_message("Votre ancien mot de passe est incorrect !", "error");
							redirect("profile&action=change_password", 3);
						}
					}
					else
					{
						print_message("Votre nouveau mot de passe doit faire au moin 6 caractère !", "error");
						redirect("profile&action=change_password", 3);
					}
				}
			}
			else
			{
				print_message("Un champ est manquant !", "error");
				redirect("profile&action=change_password", 3);
			}
		}
		else
		{
			echo '<form method="post">
				Ancien mot de passe <br/>
				<input type="password" style="min-width: 200px;min-height: 25px;" name="old_password"/><br/>
				Nouveau mot de passe <br/>
				<input type="password" style="min-width: 200px;min-height: 25px;" name="new_password"/></br>
				Répéter nouveau mot de passe <br/>
				<input type="password" style="min-width: 200px;min-height: 25px;" name="new_password_conf"/></br><br/>
				<input type="submit" name="change" value="Changer son mot de passe !"/>
			</form>';
		}
	}

	public function DeleteAccount()
	{
		if (isset($_POST["delete_account"]))
		{
			$database = new Database();
			$database->StartConnection(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
			$database->Query('DELETE FROM accounts WHERE account = "'.$_SESSION["account"].'"');
			print_message("Votre compte a bien été supprimé, au revoir ... :'(", "success");
			session_unset();
			session_destroy();
			redirect("home", 4);
		}
		else
		{
			echo '<form method="POST">
				  <input type="submit" name="delete_account" value="Supprimer mon compte"
				    onclick="return confirm(\'Voulez vous vraiment supprimer votre compte ?\')" />
				</form>';
		}
	}
}
?>