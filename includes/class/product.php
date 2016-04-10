<?php
class Product
{
	public function AddNewProduct()
	{
		if (isset($_POST["add_product"]))
		{
			if (!empty($_POST["product_name"]) && !empty($_POST["product_category"]) && !empty($_POST["product_img"]) && !empty($_POST["product_price"]) && !empty($_POST["product_stock"]))
			{
				$database = new Database();
				$database->StartConnection(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
				$image = base64_encode(file_get_contents($_POST["product_img"]));
				$database->Query("SELECT * FROM category");
				while ($database->Fetch_Assoc())
				{
					if ($database->c_assoc["cat_name"] == $_POST["product_category"])
						$category = $database->c_assoc["id"];
				}
				if (!empty($image))
				{
					if ($category)
					{
						$database->Query('INSERT INTO products(name,category,image,price,color,stock) VALUES("'.$_POST["product_name"].'", "'.$category.'", "'.$image.'", "'.$_POST["product_price"].'", "'.$_POST["product_color"].'", "'.$_POST["product_stock"].'")');
						print_message("Votre produit a été ajouté avec succès :)", "success");
					}
					else
					{
						print_message("Impossible de trouver cette catégorie", "error");
						redirect("admin&action=add_product", 3);
					}
				}
				else
				{
					print_message("Le lien de l'image est incorrect", "error");
					redirect("admin&action=add_product", 3);
				}
			}
			else
			{
				print_message("Un champ est manquant !", "error");
				redirect("admin&action=add_product", 3);
			}
		}
		else
		{
			echo '<form method="post"/>
					Nom du produit *<br/>
					<input type="text" style="min-width: 200px;min-height: 25px;" name="product_name"/>
					<br/>Catégorie du produit * <br/><select style="min-width: 200px;min-height: 25px;" name="product_category">';
			$database = new Database();
			$database->StartConnection(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
			$database->Query("SELECT * FROM category");
			while ($database->Fetch_Assoc())
			{
				echo '<option>'.$database->c_assoc["cat_name"].'</option>';
			}
			echo '</select>
			<br/>Image du produit *<br/><small>(l\'image sera téléchargé automatiquement)</small>
			<br/><input type="text" style="min-width: 200px;min-height: 25px;" name="product_img"/>
			<br/>Prix en euros *<br/><small>(EX : 10.23)</small>
			<br/><input type="text" style="max-width: 65px;min-height: 25px;" name="product_price"/>
			<br/>Stock du produit
			<br/><input type="text" style="max-width: 50px;min-height: 25px;" name="product_stock"/>
			<br/>Couleur du produit <br/><small>(EX : noir,blanc,rose)</small>
			<br/><input type="text" style="min-width: 200px;min-height: 25px;" name="product_color"/>
			<br/><br/>
			<input type="submit" name="add_product" value="Envoyer le produit"/>
					</form>';
		}
	}

	public function DeleteProduct()
	{
		$database = new Database();
		$database->StartConnection(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
		$database->Query("SELECT * FROM products");
		$database->Get_Rows();
		if ($database->c_rows)
		{
			if (isset($_POST["delete_product"]))
			{
				if (!empty($_POST["product_id"]))
				{
					$database->Query('SELECT * FROM products WHERE id = "'.intval($_POST["product_id"]).'"');
					$database->Get_Rows();
					if ($database->c_rows)
					{
						$database->Query('DELETE FROM products WHERE id = "'.intval($_POST["product_id"]).'"');
						print_message('Le produit a été supprimé !', "success");
					}
					else
					{
						print_message("Ce produit n'existe pas !", "error");
						redirect("admin&action=delete");
					}
				}
				else
				{
					print_message("Le produit est manquant !", "errro");
					redirect("admin&action=delete");
				}
			}
			else
			{
				echo '<form method="post">
						Séléctionner un produit a supprimer<br/>
						<select style="min-width: 200px;min-height: 25px;" name="product_id">';
				while ($database->Fetch_Assoc())
				{
					echo '<option value="'.$database->c_assoc["id"].'">'.$database->c_assoc["name"].'</option>';
				}
				echo '</select></br>
					<input type="submit" name="delete_product" value="Supprimer le produit"/>
				</form>';
			}
		}
		else
		{
			print_message("Bah alors ? on veux supprimer un produit alors qu'il n'y en as pas ? tu as quoi dans la tête ?", "error");
			redirect("admin&action=delete_product");
		}
	}

	public function EditProduct()
	{
		$database = new Database();
		$database->StartConnection(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
		$database->Query("SELECT * FROM products");
		$database->Get_Rows();
		if ($database->c_rows)
		{
				if (isset($_GET["edit_product"]))
				{
					if (!empty($_GET["product_id"]))
					{
						$id = intval($_GET["product_id"]);
						$query = $database->Query('SELECT * FROM products WHERE id = "'.$id.'"');
						$rows = $database->Get_Rows($query);
						$assoc = $database->Fetch_Assoc($query);
						if ($rows)
						{
							if (isset($_POST["edit_product"]))
							{
								if (!empty($_POST["product_name"]) && !empty($_POST["product_category"]) && !empty($_POST["product_price"]) && !empty($_POST["product_stock"]))
								{
									if (!empty($_POST["image"]))
										$image = base64_encode(file_get_contents($_POST["product_img"]));
									else
										$image = $assoc["image"];
									$database->Query("SELECT * FROM category");
									while ($database->Fetch_Assoc())
									{
										if ($database->c_assoc["id"] == $_POST["product_category"])
											$category = $database->c_assoc["id"];
									}
									if ($category)
									{
										if (!empty($_POST["image"]) && empty($image))
										{
											print_message("Le lien de l'image est incorrect", "error");
											redirect("admin&action=edit_product", 3);
										}
										else
										{
											$database->Query('UPDATE products SET name = "'.$_POST["product_name"].'", category = "'.$category.'", image = "'.$image.'", price = "'.$_POST["product_price"].'", color = "'.$_POST["product_color"].'", stock = "'.$_POST["product_stock"].'" WHERE id = "'.$assoc["id"].'"');
											//$database->Query('INSERT INTO products(name,category,image,price,color,stock) VALUES("'.$_POST["product_name"].'", "'.$category.'", "'.$image.'", "'.$_POST["product_price"].'", "'.$_POST["product_color"].'", "'.$_POST["product_stock"].'")');
											print_message("Votre produit a été modifié avec succès :)", "success");
										}
									}
									else
									{
										print_message("Impossible de trouver cette catégorie", "error");
										redirect("admin&action=add_product", 3);
									}
								}
								else
								{
									print_message("Un champ est manquant !", "error");
									redirect("admin&action=edit_product", 3);
								}
							}
							else
							{
								$c_q = $database->Query('SELECT * FROM category WHERE id = "'.$assoc["category"].'"');
								$assoc_category = $database->Fetch_Assoc($c_q);
								echo '<form method="post"/>
										Nom du produit *<br/>
										<input type="text" style="min-width: 200px;min-height: 25px;" name="product_name" value="'.$assoc["name"].'"/>
										<br/>Catégorie du produit * <br/><select style="min-width: 200px;min-height: 25px;" name="product_category">';
									echo '<option value="'.$assoc_category["id"].'">'.$assoc_category["cat_name"].'</option>';
									$q_all = $database->Query('SELECT * FROM category WHERE cat_name != "'.$assoc_category["cat_name"].'"');
									while ($assoc_all = $database->Fetch_Assoc($q_all))
									{
										echo '<option value="'.$assoc_all["id"].'">'.$assoc_all["cat_name"].'</option>';
									}
								echo '</select>
								<br/>Image du produit *<br/><small>(laissez vide pour ne pas changer)</small>
								<br/><input type="text" style="min-width: 200px;min-height: 25px;" name="product_img"/>
								<br/>Prix en euros *<br/><small>(EX : 10.23)</small>
								<br/><input type="text" style="max-width: 65px;min-height: 25px;" name="product_price" value="'.$assoc["price"].'"/>
								<br/>Stock du produit
								<br/><input type="text" style="max-width: 50px;min-height: 25px;" name="product_stock" value="'.$assoc["stock"].'"/>
								<br/>Couleur du produit <br/><small>(EX : noir,blanc,rose)</small>
								<br/><input type="text" style="min-width: 200px;min-height: 25px;" name="product_color" value="'.$assoc["color"].'"/>
								<br/><br/>
								<input type="submit" name="edit_product" value="Modifier le produit"/>
										</form>';
								}
						}
						else
						{
							print_message("Ce produit n'existe pas !", "error");
							redirect("admin&action=edit_product", 3);
						}
					}
					else
						redirect("admin&action=edit_product", 0);
				}
				else
				{
					echo '<form method="GET">
							Séléctionner un produit a éditer<br/>
							<input type="hidden" name="page" value="admin"/>
							<input type="hidden" name="action" value="edit_product"/>
							<select style="min-width: 200px;min-height: 25px;" name="product_id">';
					while ($database->Fetch_Assoc())
					{
						echo '<option value="'.$database->c_assoc["id"].'">'.$database->c_assoc["name"].'</option>';
					}
					echo '</select></br>
						<input type="submit" name="edit_product" value="Editer le produit"/>
					</form>';
				}
		}
		else
		{
			print_message("Bah alors ? on veux editer un produit alors qu'il n'y en as pas ? tu as quoi dans la tête ?", "error");
			redirect("admin&action=delete_product");
		}
	}
}
?>