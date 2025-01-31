<?
class keywords {

	var $props;
	function keywords($k = '',$name = ''){
		for($i = 0; $i < count($k); $i++){
					
			$word = $k[$i];
			
			//$word = str_replace(" ","_",$word);
			//$lword = strtolower($lword);
			
			$this->props[$word] = $word;
		}
		$this->props[name] = $name;
	}

	function generate_checkboxes($userwords = '') {
		$d = new display;
		//Pass userwords as an array
	
        	$checkboxes;
        	$textarea;

        	$checked = array();
        	$leftovers;
	
		//if userwords were passed
        	if($userwords != ''){

		//seperate userwords that exist in this object from those that don't
		//existing keywords will go into the @checked array, nonexisting into @leftovers

                	for($i=0; $i< count($userwords); $i++){
                        	if($this->exists($userwords[$i])){
                                	array_push ($checked,$userwords[$i]);
                        	}
                	}
        	}

        	$checkboxes .= "<table border=\"0\">\n";
        	$count = count($this->props);
        	$switch = 0;
        	$i = 1;
		$p = $this->props;
		$name = $p[name];
        	while(list($key,$value) = each($p)){
			if($key != 'name'){
				$key = str_replace(' ','_',$key);
                		$checkboxes .= $switch ? "" : "<tr>\n";
                		$checkboxes .= "<td><input type=\"checkbox\" name=\"$key\"";
                		//$svalue = str_replace(" ","_
				if(in_array($key,$checked)){
					
                        		$checkboxes .= " checked ";
				}
                		$checkboxes .= " >" . $d->startfont() . "$value</font> </td>\n";
                		$checkboxes .= $switch ? "</tr>\n" : "";
                		$checkboxes .= ($i == $count && !$switch)? "<td></td>\n</tr>\n" : "";
                		$switch = $switch ? 0 : 1;
                		$i++;
        		}
		}
        	$checkboxes .= "</table>\n";
        	return $checkboxes;
	}
	
	function checked2array($http_vars){

		$checked = array();
		while(list($key,$value) = each($this->props)){
			//echo "in checked2array http_vars[" . $key . "] is " . $http_vars[$key] . "\n<br>";
			$key = str_replace(" ","_",$key);
			if($http_vars[$key] == 'on'){
				$checked[] = $key;
			}
		}
		return $checked;
	}
	
	function exists($keyword){
		while(list($key,$value) = each($this->props)){
			if($key == $keyword || $value == $keyword){
				return 1;
			}
		}
		return 0;
	}

	function generate_dropdown($selected = '',$caption='',$onchange=''){

       		$dropdown = "<select name=\"" . $this->props[name];
		if($onchange){
			$dropdown .= "\" onChange=\"$onchange\">";
		}else{
			$dropdown .= "\">";
		}
		if($caption){
			$dropdown .= "\t<option value=\"\">$caption</option> \n";
		}else{
			$dropdown .= "\t<option value=\"\">Choose " . $this->props[name] . "</option>\n";
        	}
		reset($this->props);
		while(list($key,$value) = each($this->props)){
                	if($key != 'name' && $key != ''){
				$dropdown .= "\t<option ";
				
				if($key == $selected){
                        		$dropdown .= ' selected ';
                		}
                		$dropdown .= " value=\"$key\">$value \n";
        		}
		}
		
		$dropdown .="</select>\n";

        	return $dropdown;
	}
	
	function generate_radios($selected){
		while(list($key,$value) = each($this->props)){
			if(($key != 'name') && $key != ''){
				$radios .= "<input type=\"radio\" name=\"" . $this->props[name] . "\" value = \"$key\" ";
				if($key == $selected){
					$radios .= " checked";
				}
				$radios .= "><font color=\"white\">...</font>$value\n<br>";
			}
		}
		return $radios;
	}
	
	function get_as_list() {
		$list = "";
		while(list($key,$value) = each($this->props)){
			if($key != 'name'){
				$value = ucwords($value);
				$list .= $value . ", ";
			}
		}
		$list = preg_replace("/, $/","",$list);
		return $list;
	}
	
	function get_key($value){
		$key = '';
		while(list($k,$v) = each($this->props)){
			if($v == $value){
				return $k;
			}
		}
		return false;
	}
	function keys_as_array(){
		while(list($k,$v) = each($this->props)){
			if($k != 'name'){
				$tmp[] = $k;
			}
		}
		return $tmp;
	}
	function values_as_array(){
                while(list($k,$v) = each($this->props)){
                        if($k != 'name'){
                                $tmp[] = $v;
                        }
                }
                return $tmp;
        }
}
?>
