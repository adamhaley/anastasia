<?
class templates extends blueprint {

	function templates(){

	 	$props = array(
			"local" => new local,
                        "table" => "templates", //name the table the same as the class name
                        "manager_mask" => new mask(array("id","name","description")),
                        "fields" => array(
                                        "id" => array(
                                                "type" => "id",
                                                "length" => "11",
                                                "settable" => "no"
                                                ),
                                        "name" => array(
                                                "type" => "text",
                                                "length" => "30"
                                                ),
                                        "description" => array(
                                                "type" => "desc",
                                                "length" => "200"
                                                ),
					"html_source" => array(
						"type" => "desc",
						"length" => "1000"
						)
					)
				);
				$this->blueprint($props);	
				return;
			}
}
?>
