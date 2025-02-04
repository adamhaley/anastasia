<?
class statelist extends keywords{
	function statelist($name){
		$db = new db;

		$st = "select * from states";
		while($row = $db->dbh->query($qh)){
			$array[] = stripslashes($row['name']);
		}
		$this->keywords($array,$name);
	}
}
?>
