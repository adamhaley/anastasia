<?
class mask {
	var $props = array();
	function mask($array) {
		$this->props = $array;
	}
	
	function filter_keys($a){
		//pass this an assosiative array to filter via the keys
		$newa = array();
		$props = $this->props;
		for($i=0;$i<count($this->props);$i++){
			$key = $this->props[$i];	
			$newa[$key] = $a[$key];
		}
		return $newa;
	}
}
?>
