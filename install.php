<?php
error_reporting(0);

function install_database($host, $user, $password, $db_name)
{
	$database = new Database();
	$database->StartConnection($host, $user, $password, $db_name);
	$database->Query(file_get_contents("includes/sql_tables/accounts.sql"));
	$database->Query(file_get_contents("includes/sql_tables/products.sql"));
	$database->Query(file_get_contents("includes/sql_tables/products_data.sql"));
	$database->Query(file_get_contents("includes/sql_tables/products_data_1.sql"));
	$database->Query(file_get_contents("includes/sql_tables/products_data_2.sql"));
	$database->Query(file_get_contents("includes/sql_tables/products_data_3.sql"));
	$database->Query(file_get_contents("includes/sql_tables/products_data_4.sql"));
	$database->Query(file_get_contents("includes/sql_tables/products_data_5.sql"));
	$database->Query(file_get_contents("includes/sql_tables/products_data_6.sql"));
	$database->Query(file_get_contents("includes/sql_tables/category.sql"));
	$database->Query(file_get_contents("includes/sql_tables/category_data.sql"));
	$database->Query(file_get_contents("includes/sql_tables/orders.sql"));
}

function update_configuration($host, $user, $password, $db_name)
{
	$content = file_get_contents("includes/config.php");
	$i = 0;
	$line = NULL;
	while ($content[$i] != NULL)
	{
		if ($content[$i] == "\n")
		{
			if (strstr($line, "DATABASE_HOST"))
			{
				$new = 'const DATABASE_HOST = "'.$host.'";';
				$content = str_replace(trim($line), $new, $content);
			}
			else if (strstr($line, "DATABASE_USER"))
			{
				$new = 'const DATABASE_USER = "'.$user.'";';
				$content = str_replace(trim($line), $new, $content);
			}
			else if (strstr($line, "DATABASE_PASSWORD"))
			{
				$new = 'const DATABASE_PASSWORD = "'.$password.'";';
				$content = str_replace(trim($line), $new, $content);
			}
			else if (strstr($line, "DATABASE_NAME"))
			{
				$new = 'const DATABASE_NAME = "'.$db_name.'";';
				$content = str_replace(trim($line), $new, $content);
			}
			$line = NULL;
		}
		$line = ''.$line.''.$content[$i].'';
		$i++;
	}
	file_put_contents("includes/config.php", $content);
}

function installation()
{
	if (!empty($_POST["host"]) && !empty($_POST["user"]) && !empty($_POST["password"]) && !empty($_POST["db_name"]))
	{
		install_database($_POST["host"], $_POST["user"], $_POST["password"], $_POST["db_name"]);
		update_configuration($_POST["host"], $_POST["user"], $_POST["password"], $_POST["db_name"]);
		echo '<center><span style="color:green"><b><h2>The website was sucessfully installed, you can now reload the page and visit it !</h2></b></span></center>';
		fopen("includes/locked", "w");
	}
	else
		echo '<center><span style="color:red"><b><h2>An error occured, please try again.</h2></b></span></center>';
}

function check_installation()
{
	if (!file_exists("includes/locked"))
	{
		if (isset($_POST["send"]))
			installation();
		else
		{
			echo '<center>
				<h2>Hello there, you need to install the website before using it !<br/></h2>
				<form method="post"/>
					<fieldset>
						<h3>Database informations</h3>
						Database host :
						<br/>
						<input type="text" name="host">
						<br/>
						Database User :
						<br/>
						<input type="text" name="user"><br/>
						Database Password :
						<br/>
						<input type="password" name="password"><br/>
						Database name :
						<br/>
						<input type="text" name="db_name">
					</fieldset>
					<br/>
					<input style="font-size:20px;" type="submit" name="send" value="Install it !"/>
				</form>
				</center>';
		}
		return (false);
	}
	else
		return (true);
}
?>