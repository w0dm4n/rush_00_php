<?php
	echo '<HTML>
		<HTML>
 	<HEAD>
		<META charset="utf-8">
		<LINK rel="stylesheet" type="text/css" href="includes/templates/style.css" />
		<TITLE>Dig me shop</TITLE>
  	</HEAD>
  	<BODY>
		<DIV id="top_frame">
			<IMG class="logo" width="100" height="95" src="http://m.memegen.com/0i7bm8.jpg" alt="Tu veux voir ma...?" title="Tu veux voir ma...?">
			<DIV id="title">SexToys for everyone...</DIV>';
			if (empty($_SESSION["account"]))
			{
				if (isset($_POST["connection"]))
				{
					echo '<div class="connexion"/><br/>';
					$account = new Account();
					$account->Login();
					echo '</div>';
				}	
				else
				{			
					echo '<FORM class="connexion" method="POST">
						Login: <br/><INPUT type="text" name="login"></INPUT><BR/>
						Password: <br/><INPUT type="password" name="password"></INPUT><BR/>
						<INPUT style="margin-left: 45px;margin-top: 5px;" type="submit" name="connection" value="Validate"></INPUT>
						</FORM>';
				}
			}
			else
			{
				echo '<div class="connexion"/>
				<br/> <a href="?page=profile">Mon compte</a><br/>
				<a href="?page=logout">Déconnexion</a></div>';
			}
		echo '</DIV>';
?>