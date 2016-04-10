<?php
	echo '<DIV id="right_frame">
			Panier :
			<DIV style="overflow-y: scroll; overflow-x: hidden;"class="panier">';
			if (!empty($_SESSION["basket"]))
			{
				$database = new Database();
				$database->StartConnection(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
				$basket = explode(";", $_SESSION["basket"]);
				$price = 0;
				foreach ($basket as $value)
				{
					$quantity = explode(":", $value);
					$id = $quantity[0];
					if (intval($id) > 0)
					{
						$quantity = $quantity[1];
						$database->Query('SELECT * FROM products WHERE id = "'.$id.'"');
						$database->Fetch_Assoc();
						echo '<TABLE><TD>
							<a href="?page=basket&delete&product_id='.$id.'"><P style="text-align: right;">X</P></a>
							<IMG class="TE" width="60" height="40" src="data:image/png;base64,'.$database->c_assoc["image"].'"/><BR/>
							<P>Prix: '.$database->c_assoc["price"].' €</P><P>x'.$quantity.'</P>
						</TD>
					</TABLE>';
						$price += ($database->c_assoc["price"] * $quantity);
					}
				}
			}
			else
			{
				echo "Votre panier est vide";
			}
	echo '</DIV>';
	if (!empty($_SESSION["basket"]))
	{		
		echo '<DIV class="total">
					<FORM style="display: block;">
							<FONT size=3>Prix total:</FONT>
						<P>
							<FONT size=3>'.$price.' €</FONT>
						</P>
						<INPUT style="box-shadow: 0px 0px 0px;" type="submit" name="submit" value="Valider mon panier"></INPUT>
					</FORM>
				</DIV>';

	}
	echo '</BODY>
		</HTML>';
?>