<?php
	echo '<DIV id="middle_frame">
				<DIV>
					<BR/><center>Inscription</center>
				</DIV>
				<DIV class="message"><center>';
		if (isset($_POST["register"]))
		{
			$account = new Account();
			$account->CreateAccount();
		}
		else
		{
			echo '<form method="post"/>
					Nom de compte<br/>
					<input type="text" style="min-width: 200px;min-height: 25px;" name="account"/></br>
					Mot de passe<br/>
					<input type="password" style="min-width: 200px;min-height: 25px;" name="password"/><br/>
					Répéter mot de passe<br/>
					<input type="password" style="min-width: 200px;min-height: 25px;" name="password_conf"/>
					<br/><br/>
					<input type="submit" width="200" name="register" value="S\'inscrire !"/>
					</form>';
		}
		echo '</center></DIV>
			</DIV>';
