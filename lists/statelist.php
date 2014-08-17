<?
class statelist extends keywords{
	function statelist($name){
		$db = new info_db;
		
		$qh = mysql_query("select * from states");
		while($row = mysql_fetch_array($qh)){
			$array[] = stripslashes($row['name']);
		}
		$this->keywords($array,$name);
	}
}
?>
