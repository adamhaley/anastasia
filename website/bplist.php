<?
class bplist extends keywords{
	function bplist($name = ''){
		$db = new db;

		$st = "select * from bps";
		while($row = $db->dbh->query($st)){
			$tmp[] = stripslashes($row[name]);
		}
		$this->keywords($tmp,$name);
	}
}
?>
