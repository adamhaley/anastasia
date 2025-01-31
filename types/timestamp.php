<?
class timestamp extends type{
	function timestamp($key,$value,$bp){
		//constructor
		return $this->type($key,$value,$bp);
	}
	function prepare(){
		  $date = time();
		return $date;
	}
	
	function form_field(){
                $value = stripslashes($this->value);
                $key = $this->key;
                $length = $this->length;
                $label = $this->get_label();
                return $label . ": $value \n";

        }

        function form_field_modify(){
                return $this->form_field();
        }


}
?>
