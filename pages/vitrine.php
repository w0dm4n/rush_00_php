<?php
$database = new Database();
$database->StartConnection(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
echo '<DIV id="middle_frame" style="overflow-x: hidden; overflow-y: scroll;">';
	if (isset($_GET["category"]))
	{
		$database->Query('SELECT * FROM products WHERE category = "'.intval($_GET["category"]).'"');
		$database->Get_Rows();
		if ($database->c_rows)
		{
			echo '<TABLE class="coucou">';
			while ($database->Fetch_Assoc())
			{
				echo '<DIV class="vitrine">
					<P class="MEP"><FONT size=3>'.$database->c_assoc["name"].'</FONT></P>
					<P class="MEPIMG"><IMG class="TE" border="1" style="border-color: pink; border-radius: 10px" width="100" height="75" src="data:image/png;base64,'.$database->c_assoc["image"].'" alt="tour et fion" title="tour et fion"/><BR/><P>
					';
					if ($database->c_assoc["stock"] == "0")
						echo '<P style="margin-top: -15px;"><FONT class="MEP" size=3>Stock: Vide</FONT></P>';
					else
						echo '<P style="margin-top: -15px;"><FONT class="MEP" size=3>Stock: '.$database->c_assoc["stock"].'</FONT></P>';
					
					echo '<P style="margin-top: -25px;"><FONT class="MEP" size=3>'.$database->c_assoc["price"].'€</FONT><a href="?page=basket&add&product_id='.$database->c_assoc["id"].'"><INPUT style="margin-left: 30px" type="submit" name="submit" value="Ajouter"></INPUT></P></a>
				</DIV>';
			}
			echo '</table>';
		}
		else
			redirect("category", 0);
	}
	else
	{
		$database->Query('SELECT * FROM products');
		$database->Get_Rows();
		if ($database->c_rows)
		{
			echo '<TABLE class="coucou">';
			while ($database->Fetch_Assoc())
			{
				echo '<DIV class="vitrine">
					<P class="MEP"><FONT size=3>'.$database->c_assoc["name"].'</FONT></P>
					<P class="MEPIMG"><IMG class="TE" border="1" style="border-color: pink; border-radius: 10px" width="100" height="75" src="data:image/png;base64,'.$database->c_assoc["image"].'" alt="tour et fion" title="tour et fion"/><BR/><P>
					';
					if ($database->c_assoc["stock"] == "0")
						echo '<P style="margin-top: -15px;"><FONT class="MEP" size=3>Stock: Vide</FONT></P>';
					else
						echo '<P style="margin-top: -15px;"><FONT class="MEP" size=3>Stock: '.$database->c_assoc["stock"].'</FONT></P>';
					
					echo '<P style="margin-top: -25px;"><FONT class="MEP" size=3>'.$database->c_assoc["price"].'€</FONT><a href="?page=basket&add&product_id='.$database->c_assoc["id"].'"><INPUT style="margin-left: 30px" type="submit" name="submit" value="Ajouter"></INPUT></P></a>
				</DIV>';
			}
			echo '</table>';
		}
		else
			print_message('<DIV class="message">Il n\'y a aucun produit :(</div>', "error");
	}
echo '</DIV>';
?>