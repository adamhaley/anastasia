<?
class keyword_select extends type{
	function keyword_select($key,$value,$bp,$kobj){
		//constructor
		$this->kobj = $kobj;
		return $this->type($key,$value,$bp);
	}

	function form_field(){
		$value = stripslashes($this->value);
		$key = $this->key;
		
                $kobj = $this->kobj;
                $dropdown = $kobj->generate_dropdown($value);
                $label = $this->get_label();
		$form = "$label:<br>\n" . $dropdown;
		return $form;
	}

	function search_field(){
		$key = $this->key;
		$value = $this->value;  
                $key = str_replace('_',' ',$key);
                $key = ucwords($key);
		$kobj = $this->kobj;
		$dd = $kobj->generate_dropdown($value);
                $form = "<tr><td width=\"200\">" . $key . "</td><td width=\"100\"> is </td><td width=\"200\">$dd</td></tr>";
		return $form;

	}
	
}
?>
