<?php
class Database
{
	static $db;
	static $c_query;
	static $c_assoc;
	static $c_rows;
	public function StartConnection($host, $user, $password, $db_name)
	{
		$this->db = mysqli_connect($host, $user, $password, $db_name) or die("Can't connect to the database :(");
		mysqli_query($this->db, "SET NAMES UTF8");
	}
	public function Query($query)
	{
		return (($this->c_query = mysqli_query($this->db, $query)));
	}
	public function Fetch_Assoc($query)
	{
		return ((!empty($query)) ? $this->c_assoc = mysqli_fetch_assoc($query) : $this->c_assoc = mysqli_fetch_assoc($this->c_query));
	}
	public function Get_Rows($query)
	{
		return ((!empty($query)) ? $this->c_rows = mysqli_num_rows($query) : $this->c_rows = mysqli_num_rows($this->c_query));
	}
	public function CloseConnection()
	{
		mysqli_close($this->db);
	}
}