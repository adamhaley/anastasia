<?
class blueprint {
	var $keywords;
	var $props;
	function blueprint($args = ''){
		if($args){
			while(list($key,$value) = each($args)){
				$this->props[$key] = $value;
			}
		}
	}
	
	function display($array = ''){
		while(list($key,$value) = each($array)){
			echo  $key . " ::: " . $value . "\n<br>";
			if(is_array($value)){
				echo " ------------------------- \n  <br>";
				$this->display($value);
			}
		}
	}

	function get_all_elements($params = array()) {
                //pass this guy an array of conditions for returning elements

                //$db = $this->props[local]->props[db];
                
		global $db;
		$table = $this->props[table];

		//echo " in get_all_elements db is $db->database ";

                $st = "select * from $table ";
		if(count($params)){
                	$st .= $this->sql_from_params($params);
		}
		//echo $st ."<br>";
		mysql_select_db($db->database);
		$sth = mysql_query($st);

                $elements = array();
		if($sth){
                	while($row = mysql_fetch_array($sth)){
		        	$elements[] = new element($this,$row['id']);
			}
		}else{
			echo "Error in blueprint::get_all_elements() : " . mysql_error();
			return false;
		}
                return $elements;
        }

	function get_all_elements_xml($params = array()){
		$earray = $this->get_all_elements($params);
		$bpname = get_class($this);
		$out .= "<bp name=\"$bpname\">\n";
		for($i=0;$i<count($earray);$i++){
			$e = $earray[$i];
			$out .= $e->get_as_xml();
		}
		$out .= "</bp>\n";
		return $out;
	}

	function count_elements($params){
		$db = $this->props[local]->props[db];
		$table = $this->props[table];

		$st = "select count(*) from $table ";
		$st .= $this->sql_from_params($params);

		$sth = mysql_query($st);
		$row = mysql_fetch_array($sth);
		$count = $row["count(*)"];
		return $count;
	}
	
	function sql_from_params($params){
		if(!is_array($params)){
			$params = array();
		}
		$cnt = 0;
		while(list($key,$value) = each($params)){
			if(preg_match("/%like%/",$key)){
				$key = preg_replace("/%like%/","",$key);
				$s .= " $key like \"%$value%\" and";
			}else if(strstr('%not like%',$key)){
				$key = str_replace('%not like%','',$key);
				$s .= " $key not like \"%$value%\" and";
			}else if(preg_match("/%regexp%/",$key)){
				$key = preg_replace("/%regexp%/","",$key);
				$s .= " $key regexp \"$value\" and";
			}else if(preg_match("/%starts_with%/",$key)){
				$key = preg_replace("/%starts_with%/","",$key);
				$s .= " $key like \"$value%\" and";
                        }else if(preg_match("/%between%/",$key)){
			
				$key = preg_replace("/%between%/","",$key);
				$varray = explode(",",$value);
				
				$s .= " $key between " . $varray[0] . " and " . $varray[1] . " and";
                	}else if(strstr($value,"%or%")){
				$varray = explode("%or%",$value);
				for($cnt = 0;$cnt<count($varray);$cnt++){
					$s .= " $key = \"" . $varray[$cnt] . "\" or";
				}
				$s = preg_replace("/or$/","",$s);
				$s .= " and";
			}else if(preg_match("/%>%/",$key)){
				$key = preg_replace("/%>%/","",$key);
				$s .= " $key > $value and";
			}else if(preg_match("/%<%/",$key)){
				$key = preg_replace("/%<%/","",$key);
                                $s .= " $key < $value and";
			}else if(preg_match("/%>=%/",$key)){
				$key = preg_replace("/%>=%/","",$key);
                                $s .= " $key >= $value and";
			}else if(preg_match("/%<=%/",$key)){
				$key = preg_replace("/%<=%/","",$key);
                                $s .= " $key <= $value and";
			}else if(strstr($key,"%!=%")){
				$key = str_replace("%!=%","",$key);
				$s .= " $key != $value and";
			}else if($key != "order_by" && $key != "limit"){
				if(preg_match("/^!/",$value)){
                                        $value = preg_replace("/!/","",$value);
                                        $s .= " $key != $value and";
                                }else{
                                        $s .= " $key = \"$value\" and";
                                }
			}
			if($key != "order_by" && $key != "limit"){
				$cnt++;
			}
		}
               	$s = preg_replace("/and$/","",$s);
               	$s = preg_replace("/where$/","",$s);
               	$st = $cnt? " where $s" : "";

		if($o = $params[order_by]){
                       	$st .= " order by $o";
               	}
               	if($l = $params[limit]){
                       	$st .= " limit $l";
               	}
		//echo "st is $st\n<br>";
		return $st;
	}	
	
	function get_element($id){
		return new element($this,$id);
	}
}
?>
