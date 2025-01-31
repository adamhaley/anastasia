<?

class cdisplay {

	var $lightcolor = "#cfe0ff";
        var $darkcolor = "#99ccff";
        var $bgcolor = "#99ccff";
        var $fontcolor = "#000000";
        var $link = "#009999";
        var $alink = "#00ffff";
	var $vlink = "#005555";
	var $fontface = "Arial, Helvetica, Sans-Serif";
	var $fontsize = "2";
	var $tableborder = "0";

	function startfont(){
		return "<font face=\"" . $this->fontface . "\" size=\"". $this->fontsize . "\">";
	}
	
}


?>
