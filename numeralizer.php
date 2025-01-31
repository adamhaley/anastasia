<?
class numeralizer{
	function numeralizer($bp,$keyfield,$number){
		$this->number = $number ? $number : "1";
		$this->keyfield = $keyfield;
		$this->bp = $bp;
		$this->numbers = $this->return_numbers();
	}

	function return_numbers(){
		$bp = $this->bp;
		$keyfield = $this->keyfield;
		$earray = $bp->get_all_elements(array("order_by" => $keyfield));
	
		$rawnumbers = array();	
		$numbers = array();
		
		for($i=0;$i<count($earray);$i++){
			$e = $earray[$i];
			 $rawnumbers[] = $e->get_prop($keyfield);
		}
	
		for($i=0;$i<count($rawnumbers);$i++){
			if(in_array($i,$rawnumbers) && $i!=0){
				$numbers[] = $i;
			}
		}

		return $numbers;
	}

	function nav($baseurl){
		//base url is coded string
		$baseurl .= "_n_";
		$numbers = $this->numbers;
		for($i=0;$i<count($numbers);$i++){
			$num = $numbers[$i];
			$nav .= ($num==$this->number)?  " $num -" : " <a href=\"<%$baseurl" . $num . "%>\">$num</a> -";
		}
		$nav = preg_replace("/-$/","",$nav);
		return $nav;
	}

	function get_elements($bp,$params){
		$num = $this->number;
		$keyfield = $this->keyfield;
		
		$params[$keyfield] = $num;
		return $bp->get_all_elements($params);
	}
}
?>
