<?
class href{
	function href($codedstring,$dynfile=''){
		$this->codedstring = $codedstring;
		$this->dynfile = $dynfile? $dynfile : '';
		$this->local = new local;
	}

	function get_dynamic_href($relabsolute='',$codedstring=''){
		$relabsolute = $relabsolute ? $relabsolute : 'relative';
		if($relabsolute == 'absolute'){
			$l = $this->local;
			$url = $l->props[url];
			$href = $url . "/";
		}
		$href .= $this->dynfile;
		$string = $codedstring? $codedstring : $this->codedstring;
		$querystring = $this->querystring_from_coded_string($string);
		$href .= "?" . $querystring;
		return $href;
	}
	
	function querystring_from_coded_string($string=''){
		$string = $string? $string : $this->codedstring;
		$array = $this->get_array_from_coded_string($string);
		while(list($key,$value) = each($array)){
			$query .= "$key=$value&";
		}
		$query = preg_replace("/&$/","",$query);
		return $query;
	}

	function codedstring_from_querystring($querystring){
		
	}	

	function get_static_href($relabsolute='',$codedstring=''){
		$relabsolute = $relabsolute ? $relabsolute : 'relative';
		if($relabsolute == 'absolute'){
                        $l = $this->local;
                        $url = $l->props[url];
                        $href = $url . "/";
                }
		$href .= $codedstring ? $codedstring : $this->codedstring;
		$href = str_replace(" ","-",$href);
		$href .= ".html";
		return $href;
	}
	
	function get_static_from_dynamic($uri,$relabsolute=''){
		$ua = explode("?",$uri);
		$uri = (count($ua)>1)? $ua[1] : $ua[0];
		$pairsarray = explode("&",$uri);
	     	$relabsolute = $relabsolute ? $relabsolute : 'relative';

		for($i=0;$i<count($pairsarray);$i++){
			list($key,$value) = explode("=",$pairsarray[$i]);
			$value = strtolower($value);
			$value = str_replace(' ','-',$value);
			$str .= "_$key" . "_$value";
			
		}
		$str = preg_replace("/^_/","",$str);
		if($relabsolute == 'absolute'){
                        $l = $this->local;
                        $url = $l->props[url];
                        $href = $url . "/";
                }
		$href .=  $str . ".html";
		return $href;
	}

	function get_array_from_coded_string($string=''){
		$string = $string? $string : $this->codedstring;
		if(strstr($string,".html")){
			$string = str_replace(".html","",$string);
		}
		$array = explode("_",$string);
		
		$returnarray = array();
		$switch = 1;
		for($i=0;$i<count($array);$i++){
			if($switch){
				//set key as current val, with val of ''
				$returnarray[$array[$i]] = '';
			}else{
				//set the key we just set last time around with the value of current val
				$returnarray[$array[$i-1]] = $array[$i];
			}
			$switch = $switch? 0 : 1;
			//fun!
		}
		return $returnarray;
	}
	function get_coded_string_from_array($array){
		reset($array);
		
		while(list($key,$value) = each($array)){
			$string .= $key . "_" . $value . "_";
			$value = strtolower($value);
			$value = str_replace(' ','-',$value);
		}
		//remove trailing "_"
		$string = preg_replace("/_$/","",$string);
		return $string;
	}
}
?>
