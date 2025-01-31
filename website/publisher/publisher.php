<?

class publisher{
	function publisher($frontend=''){
		$this->previewfile = 'display.php';
		$this->files=array();
		$l = new local;
		$this->local = $l;
		$this->frontend = $frontend? $frontend : $l->props['frontend'];

	}
	function publish(){
		set_time_limit(600);
		//set time limit to a generous 10 minutes in case things are taking forever

		$pbp = new pages;
		$parray = $pbp->get_all_elements(array());
		//for each page
		$filesarray = array();	
	
		for($i=0;$i<count($parray);$i++){
			$p = $parray[$i];
			$pname = $p->get_prop('name');
			$pname = strtolower($pname);
			$pname = str_replace(' ','-',$pname);
			//get the flat filename
			$codedstring = "p_$pname";
			$href = new href($codedstring);//can put dynfile as second parameter		
			$filename = $href->get_static_href();
			//simulate a frontend request to get page content
			$get_vars = array();
			$get_vars['p'] = $pname;
			$content=$this->frontend_request($get_vars);
			//replace links static
			preg_match_all("|<%link_(.*?)%>|i", $content,$arrayoflinks);
			while(list(,$link) = each($arrayoflinks[1])){
        	                $fname = $link . ".html";
                	        $fname = strtolower($fname);
				$fname = str_replace(" ","-",$fname);

                        	//replace the link
                        	$content = str_replace("<%link_$link%>",$fname,$content);
                        	//slate the file for creation, if it hasn't already been
                       		if(!array_key_exists($fname,$filesarray)){
                                	$filesarray["$fname"] = 'neg';
                        	}
                        	//echo "replaced $link with $fname<Br>";
                	}
			
			//publish the file
			 $l = new local();
                        $path = $l->props['doc_root'];
			$filepath = $path . $filename;
                        $fh = fopen($filepath,"w");

                        if(fwrite($fh,$content)){
                                $msgs .= "Published $path" . "$filename\n<br>";
                                //add filename to files array
                                $filesarray["$filename"] = 'pos';
                        }else{
                                $msgs .= "Write error could not write to $path" . "$filename\n<br>";
                        }
                        fclose($fh);
		}

		$this->files = $filesarray;
		//now the files array should be built up with every file that is linked to
		  while(list($key,$value) = each($filesarray)){
                        
			if($value == 'neg'){
                                //if value is set to neg, the file has not already been generated
                                //key is the filename(coded string)
                                $h = new href($key);
                                $array = $h->get_array_from_coded_string();
                                $content = $this->frontend_request($array);
                                 //replace links
                                $content = $this->replace_links_static($content);

                                //publish the file
                                $msgs .= $this->create_file($key,$content);
                        }
                }

		$l = $this->local;
		$uri = $l->props[url] . $l->props[livepath];
		$msgs = "<b>Publishing Complete.</b><br>Your changes are now live at <a href=\"$uri\" target=\"new\">$uri</a><br><br>" . $msgs; 
		
		$msgs .= $this->publish_additional_files();
		return $msgs;	
	}
	function replace_links_static($content){
		preg_match_all("|<%link_(.*?)%>|i", $content,$arrayoflinks);
		while(list(,$link) = each($arrayoflinks[1])){
			$filename = $link . ".html";
			$filename = strtolower($filename);
			$filename = str_replace(' ','-',$filename);
			$content = str_replace("<%link_$link%>",$filename,$content);
				
			if(!array_key_exists($filename,$this->files)){
				$this->files["$filename"] = 'neg';
			}
		}

		return $content;
	}
	function replace_links_dynamic($content,$file=''){
		$l = new local;
		$file = $file? $file : $l->props['stagingpath'];
		preg_match_all("|<%link_(.*?)%>|i", $content,$arrayoflinks);
		  while(list(,$link) = each($arrayoflinks[1])){
			$h = new href($link);
			$querystring = $h->querystring_from_coded_string();
			$content = str_replace("<%link_$link%>",$file . "?$querystring",$content);
		}
		return $content;
	}

	function publish_additional_files(){
		//generate any files in the files array that have not already been taken care of by publish()
		if(!count($this->files)){
			echo 'Baaa, Im getting out of here<br>';
			return;
		}else{
			while(list($key,$value) = each($this->files)){
				//echo $key . "<br>";
				if($value == 'neg'){
					//if value is set to false, the file has not already been generated
					//key is the filename(coded string)
					$h = new href($key);
					$array = $h->get_array_from_coded_string();
					$content = $this->frontend_request($array);
				 	//replace links
	                        	$content = $this->replace_links_static($content);

        	                	//publish the file
                		        $msgs .= $this->create_file($key,$content);
				}
			}
			//$msgs .= $this->publish_additional_files();
		}
		return $msgs;
	}

	function create_file($filename,$content){
		  //open flat file for writing
                        $l = new local();

			$path = $l->props['doc_root'];

			$filepath = $path . $filename;
                        $fh = fopen($filepath,"w");
                        if(fwrite($fh,$content)){
                                $msgs .= "Published $path" . "$filename\n<br>";
                                //add filename to files array
                                $filesarray["$filename"] = 'pos';
                        }else{
                                $msgs .= "Write error could not write to $path" . "$filename\n<br>";
                        }
                        fclose($fh);
			return $msgs;
	}	
	function frontend_request($get_vars_array){
        	$post_vars = array();
                $f=$this->frontend;
		$f->dynstatic = 'static';
		$f->get_vars = $get_vars_array;
		return $f->action('nolinks');
	}
}
?>
