<?
class pagelist extends keywords{
	function pagelist($name){
		$db = new db;
		while($row = $db->dbh->query("select * from pages")){
			$pages[] = stripslashes($row[name]);
		}
		$this->keywords($pages,$name);
	}
}
?>
