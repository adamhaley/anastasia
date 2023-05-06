<?php

class display
{
    public $lightcolor = "#eeeeee";
    public $darkcolor = "#cccccc";
    public $bgcolor = "#ffffff";
    public $fontcolor = "#000000";
    public $link = "#009999";
    public $alink = "#00ffff";
    public $vlink = "#005555";
    public $fontface = "Arial, Helvetica, Sans-Serif";
    public $fontsize = "2";
    public $tableborder = "0";

    public function startfont($color = '')
    {
        $code = "<font face=\"" . $this->fontface . "\" size=\"". $this->fontsize . "\" color=\"";
        $code .= $color ? $this->$color : $this->fontcolor;

        $code .= "\">";
        return $code;
    }

}
