<?
class htmlarea extends type{
	function htmlarea($key,$value,$bp,$http_vars = ''){
		//constructor
		return $this->type($key,$value,$bp,$http_vars);
	}

	function form_field(){
		 $value = stripslashes($this->value); 
		$key = $this->key;
                                $length = $this->length;
                                $cols = 100;
                                $rows = ceil($length / 40);
				$label = $this->get_label();
                                 $form = $label . "<br>    <script type=\"text/javascript\" src=\"http://ahservers.net/js/htmlarea/htmlarea.js\"></script>               
                <script type=\"text/javascript\" src=\"http://ahservers.net/js/htmlarea/dialog.js\"></script>
                <script type=\"text/javascript\" src=\"http://ahservers.net/js/htmlarea/lang/en.js\"></script>
<textarea id=\"htmlarea\" name = \"" . $key . "\" cols=\"" . $cols . "\" rows = \"" . $rows . "\" wrap=\"physical\">" . $value . "</textarea><br>
  <script language=\"javascript\">HTMLArea.replace('htmlarea')</script>";


		return $form;
	}
}
?>
