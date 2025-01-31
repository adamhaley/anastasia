<?
class tagged_image extends image{
	
	function tagged_image($key,$value,$bp,$http_vars='',$post_files){
		//constructor
		//pass post_file as http_vars
		return $this->type($key,$value,$bp,$http_vars,$post_files);
	}

	function get_value_for_web(){
		
		$value = $this->value;   

		$bp = $this->bp;

		$align = $bp->props['fields'][$this->key]['align'];
		$border = $bp->props['fields'][$this->key]['border'];
		$img = $value?$value : 'blank.gif';
	
		$value = $align == 'center' ? '<center>' : '';
		$value .= '<img src="images/' . $img . '" ';
		$value .= $align? ' align="' . $align . '" ' : '';
		$value .= $border? ' border="' . $border . '" ' : '';
		$value .= '>';
		$value .= $align == 'center'? '</center>' : ''; 
		return $this->_wrap($value);
	}
}
?>
