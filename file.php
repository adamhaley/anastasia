<?
class file {
	var $path;
	var $name;
	var $fullpath;

	function file($props = ''){
		if($props[path]){
			$this->path = $props[path];
		}
		if($props[name]){
			$this->name = $props[name];
		}
		if($props[fullpath]){
			$this->fullpath = $props[fullpath];
		}
	}
}
?>
