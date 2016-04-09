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
						$database->Query('INSERT INTO accounts(account,password,admin) VALUES("'.$account.'", "'.hash("whirlpool", $password).'", "0")');
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
				$_SESSION["account"] = $account;
				redirect($_GET["page"], 0);
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
}
?>