<?
class desc extends type{
	function desc($key,$value,$bp,$http_vars = ''){
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
                                $cols = 100;
                                $rows = ceil($length / 40);
				$label = $this->get_label();
                                 $form = $label . ": (Formatting: &lt;br&gt;=Line Break &lt;b&gt;<b>Bold</b>&lt;/b&gt;  &lt;i&gt;<i>Italics</i>&lt;/i&gt; &lt;a href=\"URL\"&gt;<a href=\"#\">Link</a>&lt;/a&gt;)<br><textarea name = \"" . $key . "\" cols=\"" . $cols . "\" rows = \"" . $rows . "\" wrap=\"physical\">" . $value . "</textarea><br>";

				$form .= "Auto-Insert HTML Line breaks (One time only!)<input type=\"checkbox\" name=\"" . $key  . "_breaks\"><br><br><br>\n";

		return $form;
	}

	function prepare(){
		$value = stripslashes($this->value);
		$key = $this->key;
		$http_vars = $this->http_vars;
		$thiskey = $key . "_breaks";

		if($http_vars[$thiskey]){
                	$value = str_replace("\n","<br>",$value);
                }
		return $value;
	}	
	
}
?>
