<?
class type{
	function type($key,$value,$bp,$http_vars='',$post_files=''){
		//constructor
		$this->key = $key;
		$this->value = $value;
		$this->bp = $bp;
		
		$this->length = $bp->props['fields'][$key]['length']? $bp->props['fields'][$key]['length'] : '';
		$this->label = $bp->props['fields'][$key]['label']? $bp->props['fields'][$key]['label'] : '';
		$this->ifstart = $bp->props['fields'][$key]['ifstart']? $bp->props['fields'][$key]['ifstart'] : '';
		 $this->ifend = $bp->props['fields'][$key]['ifend']? $bp->props['fields'][$key]['ifend'] : '';

		$this->http_vars = $http_vars? $http_vars : '';
		$this->post_files = $post_files;
		$this->new;
		return;
	}
	
	function db_update_string(){
		//for re-saving an element
		$key = $this->key;
		$value = $this->value;
		return $key . " = \"" . addslashes($value) . "\",";

	}

	function db_insert_string(){
		//for saving an element the first time around
		$value = $this->value;
		return addslashes($value);
	}

	function form_field(){
		$value = stripslashes($this->value);
		$key = $this->key;
		$length = $this->length;
		$label = $this->get_label();
		return $label . ": <br><input type=\"text\" name=\"$key\" value=\"" . $value . "\" size=\"" . $length . "\"><br>\n";
		
	}
	
	function form_field_modify(){
		return $this->form_field();
	}

	function get_value_for_web(){
		return stripslashes($this->_wrap($this->value));
	}
	function database_field(){
		return "text";
	}

	function prepare(){
		return $this->value;
	}

	function clean_up(){

	}
	function get_label(){
		if($this->label){
			return $this->label;
		}
		$label = $this->key;
		$label = str_replace('_',' ',$label);
		$label = ucwords($label);
		return $label;
	}
	function _wrap($value){
		$ifstart = ($this->ifstart == 'key')? ucfirst($this->key) . ": " : $this->ifstart;
		$ifend = $this->ifend;
			$value = stripslashes($value);
		$value = ($value) ? $ifstart . $value . $ifend : '';
		return $value;
	}

	function search_field(){
		$key = $this->key;
		$key = str_replace('_',' ',$key);
		$key = ucwords($key);
		$value = $this->value;
		$form = "<tr><td width=\"200\">" . $key . "</td><td width=\"100\"> contains </td><td width=\"200\"><input type=\"text\" name=\"" . $this->key . "\" value=\"$value\" length=\"30\"></td></tr>";
		return $form;	
	}
}
?>
