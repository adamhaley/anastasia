<?
class pages extends blueprint {

	function pages(){
		$this->keywords = array(
			"parent" => new pagelist("parent")
		);
	 	$props = array(
			//"local" => new local,
                        "table" => "pages", //name the table the same as the class name
                        "manager_mask" => new mask(array("id","name","parent","seq","post","nav")),
                        "fields" => array(
                                        "id" => array(
                                                "type" => "id",
                                                "length" => "11",
                                                "settable" => "no"
                                                ),
					"seq" => array(
						"type" => "text",
						"length" => "2"
						),
                                        "name" => array(
                                                "type" => "text",
                                                "length" => "30",
                                                ),
                                        "content" => array(
                                                "type" => "desc",
						"length" => "1000"
                                                ),
					"nav" => array(
						"type" => "bool"
						),
					"button" => array(
						"type" => "image"
						),
					"button_on" => array(
						"type" => "image"
						),
					"button_active" => array(
						"type" => "image"
						),
					"rollover" => array(
						"type" => "bool"
						),
					"post" => array(
						"type" => "bool"
						),
					"parent" => array(
						"type" => "keyword_select"
						)
					)
				);
				$this->blueprint($props);	
				return;
			}
}
?>
