<?
//ELEMENT
//This class takes a blueprint object as its first parameter
//It takes an element id as its second parameter


class element {
	
	var $name;
	var $props;
	var $bp;
	var $new;	
	var $id;

	function element($bp,$id = ''){
		$this->bp = $bp;
		$this->props = array();
		reset($bp->props['fields']);
		while(list($key,$value) = each($bp->props['fields'])){
			$this->props[$key] = '';
		}
		if($id){
		//If an id was passed, populate the object from the database
			$this->id = $id;
			
			$table = $bp->props['table'];
			$st = "select * from $table where id = '$id'";
		//	echo "st is $st \n<br>";
			$sth = mysql_query($st);
			if($sth){
				while($row = mysql_fetch_array($sth)){
					while(list($key,$value) = each($this->props)){
						$this->props[$key] = stripslashes($row[$key]);
					}
				}
			}else{
				echo "Database Error in element:" . mysql_error();
			}
		}else{
		//This is a brand new element. Set the new flag to 1
			$this->new = 1;
		}
		
		//Get the table name from the blueprint
		$n = $bp->props['table'];
		
		//stip trailing "s" if present
		$n = preg_replace("/s$/","",$n);
		
		//set it as element name
		$this->name = $n;
	}

	function set_prop($prop,$value,$http_vars=array(),$keywords='',$post_files=array()){
		//strip trailing whitespace

		$value = preg_replace("/[\s]{1,}$/","",$value);
		$type = $this->bp->props['fields'][$prop]['type'];
		if($type != 'id'){
                                if($type == 'keyword_list' || $type == 'keyword_select'){
                                        $kobj = $keywords[$prop];
                                        eval("\$typeobj  = new $type(\$prop,\$value,\$bp,\$kobj);");
                                        $typeobj->http_vars = $http_vars;
                                }else if(class_exists($type)){
                                        eval("\$typeobj = new $type(\$prop,\$value,\$bp,\$http_vars,\$post_files);");
                                }
				$typeobj->new = $this->new;
                                
				//echo "in set_all key is $prop value is $value type is $type\n<br>";
                                if(is_object($typeobj)){
		
					$value = $typeobj->prepare();
				}
                }
		$this->props[$prop] = $value;
	}
	
	function get_prop($prop){
		return stripslashes($this->props[$prop]);
	}
	
	function get_prop_for_web($prop){
			$value = $this->get_prop($prop);
		   $type = $this->bp->props['fields'][$prop]['type'];
                                if($type == 'keyword_list' || $type == 'keyword_select'){
                                        $kobj = $bp->keywords[$prop];
                                        eval("\$typeobj  = new $type(\$prop,\$value,\$bp,\$kobj);");
                                        $typeobj->http_vars = $http_vars;
                                }else if(class_exists($type)){
                                        eval("\$typeobj = new $type(\$prop,\$value,\$bp,\$http_vars,\$post_files);");
                                }
                                //echo "in set_all key is $key value is $value type is $type\n<br>";
                                if(is_object($typeobj)){
                                        //echo "yes, is object $type \n<br>";
                                        return $typeobj->get_value_for_web();
                                        //echo "AAANNDD value is $value \n<br>";
                                }
	}

	function set_all($post_vars,$post_files = array()){
		$http_vars = $post_vars;
		$bp = $this->bp;
		$keywords = $bp->keywords;
		reset($this->props);
		while(list($key,$value) = each($this->props)){
			$this->set_prop($key,$http_vars[$key],$http_vars,$keywords,$post_files);
		}
	}

	function save() {
		$bp = $this->bp;
		//$db = bp->props[db];
		//global $db;
		$table = $bp->props['table'];
		$fields = $bp->props['fields'];
		$id = $this->props['id'];	
		//if this is a new element
		if($this->new){
			//build insert query
			//echo "this is a new element \n<br>";
			$st = "insert into $table (";
			

			$http_vars = array();
			while(list($key,$value) = each($fields)){
				//echo "type is $type <br>";
                                $type = $fields[$key]['type'] ?  $fields[$key]['type'] : 'type';

				$st .= "$key,";
                               	$data = $this->get_prop($key);
                               	if($type == 'keyword_list' || $type == 'keyword_select'){
                                       	$kobj = $keywords[$key];
					
                                       	eval("\$typeobj  = new $type(\$key,\$data,\$bp,\$kobj,\$http_vars);");
                                }else if(class_exists($type)){
				      	eval("\$typeobj = new $type(\$key,\$data,\$bp,\$http_vars,\$post_files);");

                                $data = $typeobj->db_insert_string();

                        	}
				$data = $typeobj->db_insert_string();

				$stvalues .=  "\"$data\",";
			}
			$stvalues = preg_replace("/,$/","",$stvalues);
			$st = preg_replace("/,$/","",$st);			

			$st .= ") values ($stvalues)";
			//echo "insert query is $st \n<br>";
		}else{
			//build update query
			$st = "update $table set ";
			while(list($key,$value) = each($fields)){
				   	$type = $fields[$key]['type'] ?  $fields[$key]['type'] : 'type';

					$value = addslashes($this->props[$key]);
					if($type == 'keyword_list' || $type == 'keyword_select'){
                                  	      $kobj = $keywords[$key];

                                        	eval("\$typeobj  = new $type(\$key,\$value,\$bp,\$kobj,\$http_vars);");
                                	}else if(class_exists($type)){

						eval("\$typeobj = new $type(\$key,\$value,\$bp,\$http_vars,\$post_files);");

                                	}
					$st .= $typeobj->db_update_string();
			}
			$st = preg_replace("/,$/","",$st);
			$st .= " where id = $id";
			//echo "update query is $st \n<br>";
		}
		if(!mysql_query($st)){
			 return "Error: Could not update Database \n<br>" . mysql_error();
		}else{
			$name = $this->name;
			$name = preg_replace("/_/"," ",$name);
			$name = ucwords($name);

			if($this->new){
				$this->props['id'] = mysql_insert_id();
				return "New $name Saved!\n<br>";
			}else{
				return "$name " . $this->id . " Saved!\n<br>";
			}
		}	
	}
	
	function delete() {
		$bp = $this->bp;
		$table = $bp->props['table'];
		$name = $this->name;	
		$id = $this->id;	
		
		$st = "delete from $table where id = $id";
		
		//delete any files from hard disk
		$doc_root = $bp->props['local']->props['doc_root'];
		while(list($key,$value) = each($this->props)){
			$type = $bp->props['fields'][$key]['type'];
			$data = $this->props[$key];
                        if(class_exists($type)){
                                eval("\$typeobj = new $type(\$key,\$data,\$bp);");
                        }else{
				$typeobj = new type($key,$value,$bp);
			}
                        $msg .= $typeobj->clean_up();

		}	
	
		if(!mysql_query($st)){
			$msg .= "Database error, $name $id could not be deleted \n<br>";
		}else{	
			$msg .= "$name  $id successfully deleted from database \n<br>";
		}
		return $msg;
	}
	function delete_file(){
		$bp = $this->bp;
		while(list($key,$value) = each($bp->props)){
			$type = $value['type'];
			if($type == 'file'){
				1;
			}
		}
	}
	function populate_from_profile($profile){
		//this function takes an array of properties as an argument,
		//and queries the database for any elements matching that profile
		//if none are found, returns false
		//if it finds one it populates the element from it.
	
		$bp = $this->bp;
		$db = $bp->props['db'];
                $table = $bp->props['table'];
                $fields = $bp->props['fields'];

		$q = "select * from $table where ";
		while(list($key,$value) = each($profile)){
			$q .= "$key = \"$value\" &&";
		}
		$q = preg_replace("/&&$/","",$q);
		$qh = mysql_query($q)
			or die ("mysql error in element::populate_from_profile" . mysql_error());
		
		if($r = mysql_fetch_array($qh)){
			//if it finds a matching record, populate the element from it.
			while(list($k,$v) = each($this->props)){
				if($r[$k]){
					$this->props[$k] = $r[$k];
				}
			}
			$this->new = 0;
			return 1;
		}else{
			//else return false
			return 0;
		}
	}

	function view(){
                $bp = $this->bp;

                if($t = $bp->props['template']){
                        $h = new html($PHP_SELF,$this);
                        $h->set_template($t);
                        return $h->parse_template();
                }else{
			reset($this->props);
                        while(list($key,$value) = each($this->props)){
                                $key = preg_replace("/_/"," ",$key);
                                $key = ucwords($key);
                                $value = stripslashes($value);
                                //$value = preg_replace("/\\n/","\n<br>",$value);
                                if(preg_match("/.gif$/",$value) || preg_match("/.jpeg$/",$value) || preg_match("/.jpg/",$value)){
                                        $view .= "$key:$value<br><img src=\"../images/$value\">\n<br>";
                                }else{
                                        $view .= "<b>$key:</b><br>$value<br>";
                                }
                        }
                        return $view;
                }
	}
	
	function prop_exists($key){
		//checks to see if a given key exists in the props array
		$array = array_keys($this->props);
		for($i=0;$i<count($array);$i++){
			if($array[$i] == $key){
				return true;
			}
		}
		return false;
	}

	function get_as_xml(){
		$bpname = get_class($this->bp);
		$out = "\t<el bp=\"$bpname\">\n";
		while(list($key,$value)=each($this->props)){
			$out .="\t\t<$key>";
			$value = $this->get_prop_for_web($key);
			$value = str_replace('\\','',$value);
			$value = htmlentities($value);
			$out .= $value;
			$out .= "</$key>\n";
		}
	
		$out .= "\t</el>\n";
		return $out;
	}
}

?>
