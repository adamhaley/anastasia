<?
class id extends type{
	function id($key,$value,$bp){
		//constructor
		return $this->type($key,$value,$bp);
	}
	
	 function db_update_string(){
                //for re-saving an element
                return '';

        }

        function db_insert_string(){
                //for saving an element the first time around
                return "null";
        }

        function form_field(){
                $value = $this->value;
                return "<input type=\"hidden\" name=\"id\" value=\"" . $value . "\">\n <!--<b>ID: $value</b><br><br>-->";
        }

	function form_field_modify(){
                return $this->form_field();
        }

        function database_field(){
                return " int not null primary key default 0 auto_increment";
        }
	
	function search_field(){
		 //$form = "<table border=\"0\" width=\"400\"><tr><td width=\"150\">ID</td><td width=\"150\"> <select name=\"id_modifier\"> <option value=\"is\">is<option value=\"is less than\">is less than<option value=\"is greater than\">is greater than</select></td><td width=\"100\"><input type=\"text\" name=\"" . $this->key . "\"_ss\" length=\"4\"></td></tr></table><br>";
                $form = '';
		return $form;
	
	}
}	
?>
