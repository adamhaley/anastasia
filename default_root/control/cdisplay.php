<?php

class cdisplay
{
    public $lightcolor = "#cfe0ff";
    public $darkcolor = "#99ccff";
    public $bgcolor = "#99ccff";
    public $fontcolor = "#000000";
    public $link = "#009999";
    public $alink = "#00ffff";
    public $vlink = "#005555";
    public $fontface = "Arial, Helvetica, Sans-Serif";
    public $fontsize = "2";
    public $tableborder = "0";

    public function startfont()
    {
        return "<font face=\"" . $this->fontface . "\" size=\"". $this->fontsize . "\">";
    }

}
