<?
class allbps{
	
	var $props;
	function allbps(){
		include "bparray.php";

		$this->props = $bpnames; //from bparray
	}

	function get_elements($cond){
		while(list($key,$value) = each($this->props)){
			$a = $value->get_all_elements($cond);
			for($i=0;$i<count($a);$i++){
				$allelements[] = $a[$i];
			}
		}
		return $allelements;
	}
	
	function count_elements($params){
		$count = 0;
		while(list($key,$value) = each($this->props)){
			$count += $value->count_elements($params);
		}
		return $count;
	}
}
?>
