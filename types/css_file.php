<?
class css_file extends type{
	function css_file($key,$value,$bp,$http_vars='',$post_files){
		
		//constructor
		//pass post_file as http_vars
		return $this->type($key,$value,$bp,$http_vars,$post_files);
	}

	function db_update_string(){
		//for re-saving an element
		$key = $this->key;
		$value = $this->value;
		return $key . " = \"" . addslashes($value) . "\",";

	}

	function form_field(){
		$key = $this->key;
		$label = $this->get_label();
		$form = "Upload " . $label . ":<br><input type=\"file\" name=\"" . $key . "\"><br>\n";
		 $l = new local();
                $dir = $l->props['doc_root'] . 'style/';
                $list = new filelist('css_file',$dir);
                $form .= $list->generate_dropdown();


		return $form;
	}
	
	function form_field_modify(){
                //put special image stuff here
                $key = $this->key;
                $label = $this->get_label();

                if($value = $this->value){
                        $form = $label . ": Currently style/" . $value;
                }
                $form .= "Upload New " . $label . ":<br><input type=\"file\" name=\"" . $key . "\"> or <br>\n";
                $l = new local();
                $dir = $l->props['doc_root'] . 'style/';
                $list = new filelist("uploaded_$key",$dir);
                $form .= $list->generate_dropdown();
                $form .= "<br>\n";

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
                                $path = $doc_root . "style/$filename";
                                if(file_exists($path)){
                                        echo "Error: $path already exists. please re-name your file, then try uploading again, or delete this element to re-upload your file. \n<br>";
                                }else if($tmp != 'none' && $tmp != ''){
  					copy($tmp,$path);
                                        return $filename;
                                }
		}else if($value = $this->http_vars['uploaded_' . $key]){
		       return $value;
                }else{
                        return $this->value;
                }

	}

	function clean_up($doc_root){
                                $filename = $this->value;
                                        $path = $doc_root . "style/$filename";
                                        if(file_exists($path)){
                                                if(unlink($path)){

                                                        return "$path deleted\n<br>";
                                                }
                                        }
	}
}
?>
