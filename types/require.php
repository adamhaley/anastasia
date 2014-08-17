<?
$dir = "/home/secrets/public_html/anastasia/types/";
require $dir . "type.php";
if ($handle = opendir($dir)) {
  while ($fentry = readdir($handle)) {
   if (is_file("$dir/$fentry") && $fentry != 'require.php' && $fentry != 'type.php') {
     if (preg_match("/\.php$/i",$fentry))
      require("$dir/$fentry"); 
     }       
   }
}

?>
