<?
class pagelist extends keywords{
	function pagelist($name){
		$db = new db_local;
		//global $db;		

		$qh = mysql_query("select * from pages");
		while($row = mysql_fetch_array($qh)){
			$pages[] = stripslashes($row[name]);
		}
		$this->keywords($pages,$name);
	}
}
?>
