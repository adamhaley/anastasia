<?
class alphabetizer{
	function alphabetizer($keyfield,$letter){
		$this->letter = $letter ? $letter : "a";
		$this->keyfield = $keyfield;
		$this->alphabet = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
	}

	function nav($baseurl){
		//base url is coded string
		$baseurl .= "_l_";
		$alpha = $this->alphabet;
		foreach($alpha as $letter){
			$nav .= ($letter==$this->letter)?  " $letter -" : " <a href=\"<%$baseurl" . $letter . "%>\">$letter</a> -";
		}
		$nav = preg_replace("/-$/","",$nav);
		return $nav;
	}

	function get_elements($bp,$cond=array()){
		//$cond is an associative array that should be filled with
		//any other conditions for the sql query 
		//ie: ('fieldname' => 'value','fieldname' => 'value')		

		$letter = $this->letter;
		$keyfield = $this->keyfield;
		
		//the next 2 lines use my blueprint/element object model
                //of database abstraction
		//if you dont have access to the element library, replace this code 
		//with an sql query asking for all rows where $keyfield starts 
		//with $letter. also be sure to include the conditions from the 
		//$cond array in the sql query

		$cond["$keyfield%starts_with%"] = $letter;
		
		return $bp->get_all_elements($cond);
	}
}
?>
