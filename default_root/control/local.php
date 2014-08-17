<?
class local{
	var $props;
	function local(){
		$this->props = array(
			"url" => "http://localhost/testsite",
                        "website" => "Test Site ",
                        "doc_root" => "/www/testsite/",
                        "db" => new db_local,
			"display" => new display,
			"cart_display" => new cdisplay,
			"email" => "adam@adamhaley.com"
		);
	}
}
?>
