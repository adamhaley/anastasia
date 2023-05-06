<?php
class pages extends blueprint{
	function pages(){
 		$props = array(
                  	"local" => new local,
		        "table" => "pages",
                        "manager_mask" => new mask(array("id","name","category","date","image1")),
			"fields" => array(
                                        "id" => array(
                                                "type" => "int",
                                                "length" => "11",
                                                "settable" => "no"
                                                ),
                                        "name" => array(
                                                "type" => "text",
                                                "length" => "20"
                                                ),
					"body" => array(
						"type" => "desc",
						"length" => "800"
						),
					)
				);
		$this->blueprint($props);
		return;
	}
}
?>
