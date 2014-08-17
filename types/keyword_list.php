<?
class keyword_list extends type{
	function keyword_list($key,$value,$bp,$kobj){
		//constructor
		$this->kobj = $kobj;
		$this->http_vars = $http_vars;
		return $this->type($key,$value,$bp);
	}
	
	function get_value_for_web(){
			        $kobj = $this->kobj;
				$list =  $this->value;
				$list = explode(",",$list);
				foreach($list as $word){
					$word = str_replace("_"," ",$word);
					$word = ucwords($word);
					$out .= $word . ",";
				}
				
				
				/*
				foreach($kobj->props as $k=>$v){
					if($k != 'name'){
						$v = str_replace("_"," ",$v);
						$v = ucwords($v);
						$list .= $v . ",";
					}
				}
				*/
				$out = preg_replace("/,$/","",$out);
                                return $this->_wrap($out);

	}

	function form_field(){
		$value = stripslashes($this->value);
		$key = $this->key;
		$userwords = explode(",",$this->value);
                $kobj = $this->kobj;

                $checkboxes = $kobj->generate_checkboxes($userwords);
		$label = $this->get_label();
                $form = "$label:<br>\n" . $checkboxes;
		return $form;
	}

	function prepare(){
	  	$http_vars = $this->http_vars;
		$kobj = $this->kobj;
                $array = $kobj->checked2array($http_vars);
                if($array){
                 //join it into a string and set it
                 	$value = join(",",$array);
                }
		return $value;
	}

}
?>
