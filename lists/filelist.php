<?
class filelist extends keywords{
	function filelist($name,$dir){
		$d = dir($dir);
                $i=0;
                while($entry = $d->read()){
                        if($entry != "." && $entry != ".." && $entry != "CVS"){
                                $files[$i] = $entry;
                                $i++;
                        }
                }
                $this->keywords($files,$name);

	}
}
?>
