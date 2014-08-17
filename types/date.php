<?
class date extends type{
	function date($key,$value,$bp,$http_vars=''){
		//constructor
		return $this->type($key,$value,$bp,$http_vars);

	}

	function db_update_string(){
		//for re-saving an element
		$key = $this->key;
		$value = $this->value;
		$string = $key . " = \"" . $value . "\",";
		return $string;
	}

	function db_insert_string(){
		//for saving an element the first time around
		return $this->value;
	}

	function form_field(){
		$value = stripslashes($this->value);
		if($value=='0000-00-00'){
			$value = date('Y-m-d');
		}
		$key = $this->key;
		$label = $this->get_label();
		$darray = explode("-",$value);                   
                                if(!$darray[0]){
                                        $darray[0] = date("Y");
                                }
				if(!$darray[1]){
                                        $darray[1] = date("n");
                                }
				if(!$darray[2]){
                                        $darray[2] = date("d");
                                }

                                $m = 01;
                                $f = "<select name=\"$key" . "_month\">\n";
                                while($m <= 12){
                                        $f .= "<option ";
                                        if($m == $darray[1]){
                                                $f .= "selected";
                                        }
                                        $f .= " value=\"$m\">$m \n";
                                        $m++;
                                }
                                $f .= "</select> - \n";
				$f .= "<select name=\"$key" . "_day\">\n";
                                $d = 1;
				while($d <= 31){
                                        $f .= "<option ";
                                        if($d == $darray[2]){
                                                $f .= "selected";
                                        }
                                        $f .= " value=\"$d\">$d \n";
                                        $d++;
                                }
                                $f .= "</select> - \n";

                                $yf = 1970;
                                $yc = 2010;
                                $f .= "<select name=\"$key" . "_year\">\n";
                                while($yf<=$yc){
				 	$f .= "<option ";
                                        if($yf == $darray[0]){
                                                $f.= " selected ";
                                        }
                                        $f .= " value=\"$yf\">$yf \n";
                                        $yf++;
                                }
                                $f .= "</select>\n";
                                $form = $label . ": <br>$f<br>";  
				return $form;
	}

	function database_field(){
		return "date";
	}

	function prepare(){
		$key = $this->key;
		$http_vars = $this->http_vars;
		$value = $this->value;
		if(!$value){
			$y = $http_vars[$key . "_year"];
                	$m = $http_vars[$key . "_month"];
                	$d = $http_vars[$key . "_day"];
                	$value = $y . "-" . $m . "-" . $d;
		}
		return $value;

	}

	function get_value_for_web(){
		$value = $this->value;      
		$darray = explode("-",$value);
                         	$y = $darray[0];
                                $m = $darray[1];
                                $d = $darray[2];
                                $timestamp = mktime(0,0,0,$m,$d,$y);
                                $date = date("F j, Y",$timestamp);
				return $this->_wrap($date);
	}
	
	
        function search_field(){
                $key = $this->key;
                $key = str_replace('_',' ',$key);
                $key = ucwords($key);
                $kobj = $this->kobj;
		$dd = $this->form_field();
		$dd = str_replace("$key:",'',$dd);
		$dd = str_replace("<br>","",$dd);
		
                $form = "<tr><td width=\"200\">" . $key . " is </td><td width=\"100\"><select name=\"" . $this->key . "_modifier\"><option value=\"\">Blank<option value=\"current day\">Current Day<option value=\"day in week ending\">day in week ending<option value=\"the date\">the date</select><option value=\"not the date\">not the date</select></td><td width=\"200\">$dd</td></tr>";
                return $form;

        }


}
?>
