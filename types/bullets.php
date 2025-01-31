<?
class bullets extends type{
	function bullets($key,$value,$bp){
		//constructor
		return $this->type($key,$value,$bp);
	}

	function get_value_for_web(){
		$value = stripslashes($this->value);
		if($value){                               
			$array = explode("\n",$value);
                	for($i = 0; $i<count($array);$i++){
                		$bullets .= "&#149; &nbsp;" . $array[$i] . "\n<br /><br />";
                	}

			return $this->_wrap($bullets);
		}
		return "";
	}
	function form_field(){
		$value = stripslashes($this->value);
                $key = $this->key;
                                $length = $this->length;
                                $cols = 70;
                                $rows = ceil($length / 40);
                                $label = $this->get_label();
                                $form = $label . ":<br><textarea name = \"" . $key . "\" cols=\"" . $cols . "\" rows = \"" . $rows . "\" wrap=\"physical\">" . $value . "</textarea><br>";
                return $form;

	}
}
?>
