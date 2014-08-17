<?
class mp3 extends type{
	function mp3($key,$value,$bp,$http_vars='',$post_file){
		
		//constructor
		//pass post_file as http_vars
		return $this->type($key,$value,$bp,$http_vars,$post_files);
	}

	function form_field(){
		$key = $this->key;
		$label = $this->get_label();
		$form = "Upload " . $label . ":<br><input type=\"file\" name=\"" . $key . "\"><br>\n";
		return $form;

	}
	function form_field_modify(){
		//put special image stuff here
		$form = "Currently mp3/" . $value . "<br>";
		$form .= $this->form_field();
		return $form; 
	}

	function prepare(){
		$post_files=$this->post_files;
		$key = $this->key; 
		if($filename = $post_files[$key][name]){
                             	$tmp = $post_files[$key][tmp_name];
                                $type = $post_files[$key][type];
                                //echo "tmp :$tmp - type : $type filename - $filename";
                                $l = new local;
				$doc_root = $l->props['doc_root'];
                                //echo "doc_root is $doc_root \n<br>";
                                $path = $doc_root . "mp3/$filename";
                                if(file_exists($path)){
                                        echo "Error: $path already exists. please re-name your file, then try uploading again, or delete this element to re-upload your file. \n<br>";
                                }else if($tmp != 'none' && $tmp != ''){
  					copy($tmp,$path);
                                        return $filename;
                                }
		}else{
			return $this->value;
		}

	}


	
}
?>
