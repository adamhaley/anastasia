<?php

$path = getcwd();
require "../../require.php";
require "../../website/require.php";
require "../../website/publisher/require.php";

require $path . "bparray.php";
require $path . "local.php";
require $path ."db_local.php";
require $path . "cdisplay.php";

for($i=0;$i<count($bpnames);$i++) {
    //Three paths to try

    $localpath =  $path . $bpnames[$i] . ".php";
    $elementpath = "/php/element/" . $bpnames[$i] . ".php";
    $websitepath = "/php/element/website/" . $bpnames[$i] . ".php";

    if(file_exists($localpath)) {
        require($localpath);
    } elseif(file_exists($elementpath)) {
        require($elementpath);
    } elseif(file_exists($websitepath)) {
        require($websitepath);
    } else {
        echo "Could not require " . $bpnames[$i] . ".php\n<br>";
    }
}
$bparray = array();

for($i=0;$i<count($bpnames);$i++) {
    $name = $bpnames[$i];
    $str = "\$bparray[\"$name\"] = new $name;";
    eval($str);
}
