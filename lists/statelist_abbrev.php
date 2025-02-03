<?
class statelist_abbrev extends keywords{
	function statelist_abbrev($name){
		$db = new db;
		
		foreach($db->dbh->query("select * from states") as $row){
			$array[] = stripslashes($row['abbrev']);
		}
		$this->keywords($array,$name);
	}
}
?>
