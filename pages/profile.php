<?php
	if (!empty($_SESSION["account"]))
	{
		echo '<DIV id="middle_frame_error">
			<DIV>
				<BR/><center>Bienvenue sur ton profil <i><b>'.secure($_SESSION["account"], 1).'</b></i></center>
			</DIV>
			<DIV class="message">
				<center>
				Quoi de neuf ?
				<hr>';
			if (isset($_GET["action"]))
			{
				$action = secure($_GET["action"], 1);
				switch ($action)
				{
					case "change_password":
						$account = new Account();
						$account->ChangePassword();
					break ;

					case "delete_account":
						$account = new Account();
						$account->DeleteAccount();
					break ;

					default:
						redirect("profile", 0);
					break ;
				}
			}
			else
			{
				echo '<a href="?page=profile&action=change_password"/>Je souhaite changer mon mot de passe </a><br/>
					  <a href="?page=profile&action=delete_account"/>Je souhaite supprimer mon compte </a>';
			}
		echo '</center>
			</DIV>
		</DIV>';
	}
	else
		redirect("home", 0);
?>