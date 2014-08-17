<?
class image extends type{
	
	function image($key,$value,$bp,$http_vars='',$post_files){
		//constructor
		//pass post_file as http_vars
		$this->type($key,$value,$bp,$http_vars,$post_files);
	}


	function form_field(){
		$key = $this->key;
		$label = $this->get_label();
		$form = "Upload " . $label . ":<br><input type=\"file\" name=\"" . $key . "\"><br>
			or <br>\n";
		$l = new local();
		$dir = $l->props['doc_root'] . 'images/';
		$list = new filelist('image',$dir);
		$form .= $list->generate_dropdown();
		$form .= "<br>\n";

		return $form;

	}
	function form_field_modify(){
		//put special image stuff here
		$key = $this->key;
                $label = $this->get_label();
	
		if($value = $this->value){
			$form = $label . ": Currently images/" . $value . "<br><img src=\"../images/" . $this->value . "\"><br>";
		}
		$form .= "Upload New " . $label . ":<br><input type=\"file\" name=\"" . $key . "\"> or <br>\n";
		$l = new local();
                $dir = $l->props['doc_root'] . 'images/';
                $list = new filelist("uploaded_$key",$dir);
                $form .= $list->generate_dropdown();
                $form .= "<br>\n";

		return $form;
	}
	function db_update_string(){
		$key = $this->key;
		if($value = $this->value){
			return $key . " = \"" . addslashes($value) . "\",";
		}else{
			return '';
		}
	}
	function prepare(){
		$post_files=$this->post_files;
		$http_vars = $this->http_vars;
		$key = $this->key; 
			
		//echo "post_files is $post_files <br />";

		foreach ($post_files as $k => $v){
			//echo "$k ---- $v <br />";
			if(is_array($v)){
				//echo "array count is " . count($v);
			}
		}

		if($filename = $post_files[$key]['name']){
		       	$tmp = $post_files[$key]['tmp_name'];
                                $type = $post_files[$key]['type'];
                                //echo "tmp :$tmp - type : $type filename - $filename";
                        	$l = new local;
				$doc_root = $l->props['doc_root']; 
                                //echo "doc_root is $doc_root \n<br>";
                                $path = $doc_root . "images/$filename";
                                if(file_exists($path)){
                                        echo "Error: $path already exists. please re-name your file, then try uploading again, or delete this element to re-upload your file. \n<br>";
                                }else if($tmp != 'none' && $tmp != ''){
  					copy($tmp,$path);
					echo "Copying.\n";
                                        return $filename;
                                }else{
					echo 'Image upload error #' . $post_files[$key]['error'];
				}
		}else if($value = $http_vars['uploaded_' . $key]){
			return $value;
		}else{
			return $this->value;
		}
	}

	function clean_up(){
        	$filename = $this->value;
                $path = $doc_root . "images/$filename";
                	if(file_exists($path)){
	                        if(unlink($path)){
                                	return  "$path deleted\n<br>";
                                }
                        }

	}

	function get_value_for_web(){
		//echo "in getvforweb";
		$value = $this->value;   
		
		$value = ($value == '')? 'blank.gif' : $value;
	
		//echo "v is $value \n<br>";
		return $this->_wrap($value);
	}
}
?>
