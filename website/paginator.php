<?
class paginator{
        function paginator($total,$perpage,$currentpage){
		$this->total = $total;
                $this->perpage = $perpage;
                $this->currentpage = $currentpage ? $currentpage : 1;
        	$this->numpages = ceil($total / $perpage);
		
	}

        function limit_clause(){
                $pp = $this->perpage;
		
                $cp = $this->currentpage - 1;
                $start = $cp * $pp;
                $clause = "$start,$pp";
		return $clause;
        }

        function get_elements($elementarray){
		$page = $this->currentpage-1;
		$perpage = $this->perpage;
		$offset = $perpage * $page;
		//echo "offset is $offset \n<br>";
		//TEST CODE
		/*	
		for($i=0;$i<count($elementarray);$i++){
			$e = $elementarray[$i];
			if($i == $offset){
				echo "\t\t>>>>offset \n<br>";
			}
			echo $e->get_prop('name') . "<br>\n";
			echo $e->get_prop('ethnicity') . "<br>\n";
			echo $e->get_prop('gender') . "<br>\n";
			echo $e->get_prop('age_range') . "\n<br>";
			
		}
		*/
		$returnarray = array_splice($elementarray,$offset,$perpage);
		return $returnarray;
        }

        function nav($baseurl=''){
		//note - to work w/ publishing system, 
		//im setting this up to parse through the coded string system
		//send this method a coded string without the <% %> tags
                $cp = $this->currentpage;
        	$np = $this->numpages;
		
		$baseurl = $baseurl . "_page_";
		if($np > 1){
			$prev = $cp - 1;
			$next = $cp + 1;
			$nav .= ($cp == 1)? "" : "<a href=\"<%$baseurl" . 1 . "%>\">&lt;&lt;</a>  \n";

			$nav .= $prev ? "  <a href=\"<%$baseurl" . $prev . "%>\">&lt;</a>\n  " : "";
			
			$nav .= "Page $cp of $np ";
			$nav .= ($cp >= $np)? "" : "<a href=\"<%$baseurl" . $next . "%>\">&gt;</a>\n";
			$nav .= ($cp == $np)? "" : "<a href=\"<%$baseurl" . $np . "%>\">&gt;&gt;</a>\n"; 
			return $nav;
		}
	}
	
        function dynnav($baseurl){
                $cp = $this->currentpage;
                $np = $this->numpages;
		
                $baseurl = strstr($baseurl,'?')? $baseurl . "&p=" : $baseurl . "?p=";
                if($np > 1){
                        $prev = $cp - 1;
                        $next = $cp + 1;

                       $nav .=  "<a href=\"$baseurl" . 1 . "\">&lt;&lt;</a>&nbsp;&nbsp;  \n";

	                $nav .= $prev ? "<a href=\"$baseurl" . $prev . "\">&lt;</a>&nbsp;&nbsp\n" : "";

                        $nav .= "Page <b>$cp</b> of <b>$np</b> ";
                        /*for($i = 1; $i <= $np; $i++){
                                $nav .= ($i == $cp)? "$i&nbsp;&nbsp;" :  "<a href=\"$baseurl" . $i . "\">$i</a>\n&nbsp&nbsp;";
                        }
                        */
                        $nav .= ($next > $np) ? "" : "<a href=\"$baseurl" . $next . "\">&gt;</a>&nbsp;&nbsp;\n";

                        $nav .= ($cp == $np)? "" : "<a href=\"$baseurl" . $np . "\">&gt;&gt;</a>\n";
                        $lc = $this->limit_clause();
                        $a = explode(',',$lc);
                        $st = $a[0];
                        $pp = $a[1];

                        $end = $st + $pp;
                        $end = ($end > $this->total)? $this->total : $end;
                        $st += 1;
                        //$nav .= "&nbsp;&nbsp;Currently viewing records <b>$st</b> to <b>$end</b> of <b>" . $this->total . '</b>';
                        return $nav;
                }
        }

}
?>
