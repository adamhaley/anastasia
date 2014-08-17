<?
class pager{
        function pager($total,$perpage,$currentpage){
		$this->total = $total;
                $this->perpage = $perpage;

                $this->currentpage = $currentpage ? $currentpage : 1;
		$this->numpages = ceil($total / $perpage);
		
	}

        function limit_clause(){
                $pp = $this->perpage;
		
                $cp = $this->currentpage;
		$cp--;
                $start = $cp * $pp;
                $clause = "$start,$pp";
		return $clause;
        }

        function get_elements($bp,$params){
	
                $limit = $this->limit_clause();
		$params['limit'] = $limit;
                return $bp->get_all_elements($params);
        }

        function nav($baseurl){
                $cp = $this->currentpage;
        	$np = $this->numpages;
		
		$baseurl = $baseurl . "&p=";
		if($np > 1){
			$prev = $cp - 1;
			$next = $cp + 1;
		

		       $nav .=  "<a href=\"$baseurl" . 1 . "\">&lt;&lt;</a>&nbsp;&nbsp;  \n";


			$nav .= $prev ? "<a href=\"$baseurl" . $prev . "\">&lt;</a>&nbsp;&nbsp;\n" : "";
		
			$nav .= "Page <b>$cp</b> of <b>$np</b> ";
			$nav .= ($next > $np) ? "" : "<a href=\"$baseurl" . $next . "\">&gt;</a>&nbsp;&nbsp;\n";
	
		   	$nav .= ($cp == $np)? "" : "<a href=\"$baseurl" . $np . "\">&gt;&gt;</a>\n";
			$lc = $this->limit_clause();
			$a = explode(',',$lc);
			$st = $a[0];
			$pp = $a[1];
			
			$end = $st + $pp;
			$end = ($end > $this->total)? $this->total : $end;
			$st += 1;
			return $nav;
		}
	}
}
?>
