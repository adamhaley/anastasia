<?
class linkedelement extends element{
	function linkedelement($bp,$id = ''){
		$this->element($bp,$id);
	}
	
	function get_elinks($params = array()){
		$bpname = $this->bp->props[table];
	
		//echo "in elinks bpname is $bpname \n<br>";

                $l = new links;
		$id = $this->id;
		
		$params[bp1] = $bpname;
		$params[type1] = 'Id';
		$params["ids1%like%"] = "\[$id\]";
		$params[order_by] = "Id";
		/*
		if($ob = $this->bp->props[order_by]){
			//echo "ob is $ob \n<br>";
			$params[order_by] = $ob;
		}
		*/

               	$earray = $l->get_all_elements($params);

                //replace element array with array of elink objects (element subclass)
                $x = 0;
		for($i = 0; $i<count($earray);$i++){
                	//exclude templates
			if($earray[$i]->get_prop('bp2') != 'templates' && $earray[$i]->get_prop('link') == 1){
				$id = $earray[$i]->get_prop('id');
                        	$el = new elink($id);
			
				$newarray[$x]= $el;
				$x++;
			}
                }
                return $newarray;
        }
	/*
	function get_elinks_xml($params = array()){
		$earray = $this->get_elinks($params);
		foreach($earray as $e){
			$xml .= $e->get_as_xml();
		}
		return $xml;
	}
	*/
	function get_template_link(){
		//echo "in get_template_link \n<br>";
		$l = new links;
		$id = $this->id;
		$bpname = $this->bp->props[table];
		//get type1
		//so you can know what conditions to look for

		$toallbp1 = $l->get_all_elements(array('bp1' => $bpname,'bp2' => 'templates','type1' => 'All Elements','link'=>1));
		$toid = $l->get_all_elements(array('bp1' => $bpname,'bp2' => 'templates','type1' => 'Id','ids1%like%' => "\[$id\]",'link' => 1));
		       if(count($toid)){
                                //echo "toid  returned " . count($toid) . "template_link\n<br> with an id of " . $toallbp1[0]->get_prop('id');
                                return $toid[0];
                        }

			if(count($toallbp1)){
				//echo "toallbp1 returned " . count($toallbp1) . "template_link\n<br> with an id of " . $toallbp1[0]->get_prop('id');
				return $toallbp1[0];
			}
		//echo " I found " . count($earray) . " template link with an id of " . $earray[0]->get_prop('id');
	}
	function get_template_obj(){
		$tl = $this->get_template_link();
		$tid = $tl->get_prop('ids2');
		$tid = preg_replace("/[\[\]]/","",$tid);
		return new template($tid);
	}
}
?>
