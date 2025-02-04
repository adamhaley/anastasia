<?
class countrylist extends keywords{
	function countrylist($name){
		$db = new db;

		$st = "select * from country";
		while($row = $db->dbh->query($st)){
			$array[] = stripslashes($row['name']);
		}
		$this->keywords($array,$name);
	}
}
?>
