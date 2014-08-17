<?

class display {

	var $lightcolor = "#eeeeee";
        var $darkcolor = "#cccccc";
        var $bgcolor = "#ffffff";
        var $fontcolor = "#000000";
        var $link = "#009999";
        var $alink = "#00ffff";
	var $vlink = "#005555";
	var $fontface = "Arial, Helvetica, Sans-Serif";
	var $fontsize = "2";
	var $tableborder = "0";

	function startfont($color = ''){
		$code = "<font face=\"" . $this->fontface . "\" size=\"". $this->fontsize . "\" color=\"";
		$code .= $color? $this->$color : $this->fontcolor;
	
		$code .= "\">";
		return $code;
	}
	
}


?>
