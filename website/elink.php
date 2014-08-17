<?
//always link one element to multiple blueprints (or 1) but type1 is always id 
//& theres 1 id for ids1.Do not link to links. Link to blueprint elements which can then link again

class elink extends element{
	function elink($id = ''){
		$bp = new links;
		$this->bp = $bp;
		$this->element($bp,$id);
		$this->template_link = $this->get_template_link();
	}
	function display(){
		$tobj = $this->get_html_object();
		$elements = $this->get_element_objects();
		for($i=0;$i<count($elements);$i++){	
			$e = $elements[$i];
			$id = $e->get_prop('id');
			$bpname = $e->bp->props[table];
			$h = new html('',$e);
			if($source = $tobj->get_prop('html_source')){
				$h->template = $source;
			}else if($template=$tobj->get_prop('html')){
				$h->set_template("templates/$template");
			}
			$code .= $h->parse_template();
		}
		return $code;
	}
	function ids_as_array(){
		$ids = $this->get_prop('ids2');	
		//echo "ids is $ids \n";
		$ids = preg_replace("/[\[]/","",$ids);	
		return explode(']',$ids);
	}
	function get_bp2_obj(){
		$bp = $this->get_prop('bp2');
		if(class_exists($bp)){
			$str = "\$bpobj = new $bp;";
        		eval($str);
			return $bpobj;
		}else{
			return false;
		}
	}
	function get_element_objects($cond = array()){
		$type = $this->get_prop('type2');
		//echo "type is $type\n<br>";
		if(!$bp = $this->get_bp2_obj()){
			echo "Woooah!!get_bp2_obj returned false in elink:get_element_objects\n<br>";	
		}
		if(count($cond)){
                	while(list($key,$value) = each($cond)){
        	                $params[$key] = $value;
                        }
                }
		if($ob = $bp->props[order_by]){
			$params[order_by] = $ob;
		}
		if($type == 'Condition'){
                        $cond = $this->get_prop('conditions2');
                        if(strstr($cond,'postpull')){
                                $date = date('Ymd');
                                $params['post_date%<=%'] = "'$date'";
                                $params['pull_date%>=%'] = "'$date'";
                        }
                        if(preg_match("/top/",$cond)){
                                //get top certain number of elements
                                $a0 = explode(",",$cond);
                                for($ct=0;$ct<count($a0);$ct++){
                                        //if this is the parameter that specifies top
                                        if(strstr($a0[$ct],"top")){
                                                $topparam = $a0[$ct];
                                        }else if($a0[$ct] != "postpull"){
                                                $a1 = explode("=",$a0[$ct]);

  						$params[$a1[0]] = $a1[1];
                                        }
                                }

                                $a = explode(":",$topparam);
                                $numbertoreturn = $a[1];
                                $array = $bp->get_all_elements($params);
                                for($c=0;$c<count($array);$c++){
					if($c>=$numbertoreturn){
                                                break;
                                        }
                                        $returnarray[$c] = $array[$c];
                                }
                                return $returnarray;
				
			}else if(preg_match("/\bottom/",$cond)){
				//get bottom certain num of elements
				//get top certain number of elements
                                echo "found top";
                                $a1 = explode("=",$cond);
                                $param = $a1[0];
                                $a2 = explode(":",$cond);
                                $numbertoreturn = $a2[1];
                                $array = $bp->get_all_elements(array("order_by" => $param));
                                for($c=0;$c<count($array);$c++){
                                        if($c>=$numbertoreturn){
                                                break;
                                        }
                                        $returnarray[$c] = $val;
                                        $c++;
                                }
                                return $returnarray;
			}else{
				$condarray = explode(',',$cond);			
				for($i=0;$i<count($condarray);$i++){
					if(preg_match("/%like%/",$condarray[$i])){
						list($key,$value) = explode("%like%",$condarray[$i]);
						$key .= "%like%";
					}else if(preg_match("/!=/",$condarray[$i])){ 
						 list($key,$value) = explode("!=",$condarray[$i]);
						$key .= "%!=%";
					}else{
						list($key,$value) = explode("=",$condarray[$i]);
					}
		                        $params[$key] = $value;
				}
			
				//echo "bp is $bp of class " . get_class($bp);
				return $bp->get_all_elements($params);
			}
		}else if($type == 'All Elements'){
                        
			return $bp->get_all_elements($params);
			
		}else if($type == 'Id'){
			$ids = $this->ids_as_array();
			for($i = 0;$i<count($ids);$i++){
				if($ids[$i] != ''){
					$eobjs[$i] = new element($bp,$ids[$i]);
				}
			}
			return $eobjs;
		}
		return $eobjs;
	}
	function get_template_link(){
		$id = $this->props[id];
		$cond = array(
				'ids1%like%' => "\[$id\]",
				'bp1' => 'links',
				'bp2' => 'templates'
				);
		$bp = new links;
		$array = $bp->get_all_elements($cond);
		$tmplinkid = $array[0]->props[id];
		$tmpbp = new links;
		return new element($tmpbp,$tmplinkid);
	}
	function get_html_name(){
		$t = $this->get_html_object();
		return $t->get_prop('html');
	}
	function get_html_object(){
		$tl = $this->template_link;
                $id = $tl->get_prop('ids2');
		$id = preg_replace("/[\[\]]/","",$id);
		$t = new template($id);
		return $t;
	}
	function get_stylesheet_name(){
		$tmpltlink = $this->template_link;
		return $tmpltlink->get_prop('stylesheet');
	}	
}
?>
