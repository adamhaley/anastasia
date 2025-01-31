<?
class numberlist extends keywords{
	function numberlist($name){
		
		for($i=00;$i<100;$i++){
			if($i<10){
				$i = '0' . $i; 
			}
			$array[] = $i;
		}
		$this->keywords($array,$name);
	}
}
?>
