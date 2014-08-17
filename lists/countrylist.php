<?
class countrylist extends keywords{
	function countrylist($name){
		$db = new db_local;
		$db->select('info');		

		$qh = mysql_query("select * from country");
		while($row = mysql_fetch_array($qh)){
			$array[] = stripslashes($row['name']);
		}
		$this->keywords($array,$name);
	}
}
?>
