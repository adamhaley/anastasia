<?
//THIS IS THE SAME AS DESC BUT IT INSERTS LINE BREAKS IN GET_VALUE_FOR_WEB
class article_desc extends type{
	function article_desc($key,$value,$bp,$http_vars = ''){
		//constructor
		return $this->type($key,$value,$bp,$http_vars);
	}

	function form_field(){
		 $value = stripslashes($this->value); 
		$key = $this->key;
                                $length = $this->length;
                                $cols = 90;
                                $rows = ceil($length / 40);
				$label = $this->get_label();
                                $form = $label . ": (&lt;b&gt;<b>Bold</b>&lt;/b&gt;  &lt;i&gt;<i>Italics</i>&lt;/i&gt; &lt;a href=\"URL\"&gt;<a href=\"#\">Link</a>&lt;/a&gt;)<br><textarea name = \"" . $key . "\" cols=\"" . $cols . "\" rows = \"" . $rows . "\" wrap=\"physical\">" . $value . "</textarea><br>";
		return $form;
	}
	
	function get_value_for_web(){
		$value = stripslashes($this->value);
		$value = str_replace("\n","<br>",$value);
		$value = preg_replace("/\s\s\s/","&nbsp;<img src=\"images/blank.gif\" width=\"10px\">",$value);
		
		if(strstr($value,'---')){
			$array = explode('---',$value);
			$value = '';
			for($i=0;$i<count($array);$i++){
				$imgno = $i+1;
				$tag = '<%image' . $imgno . '%>';
				$value .= $array[$i];
				$value .= ($i == (count($array)-1)) ? '' : $tag;
			}	
		}
		return $this->_wrap($value);
	}

	
	
}
?>
