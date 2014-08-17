<?
class textarea extends type{
	function textarea($key,$value,$bp,$http_vars = ''){
		//constructor
		return $this->type($key,$value,$bp,$http_vars);
	}

	function db_update_string(){
		//for re-saving an element
		$key = $this->key;
		$value = $this->value;
		$string =  $key . " = \"" . addslashes($value) . "\",";
		return $string;
	}

	function form_field(){
		 $value = stripslashes($this->value); 
		$key = $this->key;
                                $length = $this->length;
                                $cols = 90;
                                $rows = ceil($length / 40);
				$label = $this->get_label();
                                 $form = $label . ":<br /><textarea name = \"" . $key . "\" cols=\"" . $cols . "\" rows = \"" . $rows . "\" wrap=\"physical\">" . $value . "</textarea><br />";


		return $form;
	}
}
?>
