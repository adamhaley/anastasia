<?
class links extends blueprint {
	function links(){
		$this->keywords = array(
			"type1" =>  new keywords(array(
                                                "All Elements",
                                                "Condition",
                                                "Id"
                                                ),"type1"
                                        ),
			"type2" =>  new keywords(array(
                                                "All Elements",
                                                "Condition",
                                                "Id"
                                                ),"type2"
                                        ),
			"bp1" => new bplist('bp1'),
			"bp2" => new bplist('bp2') 
		);
	 	$props = array(
			"local" => new local,
                        "table" => "links", //name the table the same as the class name
                        "manager_mask" => new mask(array("id","bp1","bp2","type1","type2","ids1","ids2","conditions1","conditions2","link")),
                        "fields" => array(
                                        "id" => array(
                                                "type" => "id",
                                                "length" => "11",
                                                "settable" => "no"
                                                ),
                                        "bp1" => array(
                                                "type" => "keyword_select"
                                                ),
					"bp2" => array(
						"type" => "keyword_select"
						),
                                        "type1" => array(
                                                "type" => "keyword_select"
                                                ),
					"type2" => array(
                                                "type" => "keyword_select"
                                                ),
					"conditions1" => array(
						"type" => "text",
						"length" => "50"
						),
					"conditions2" => array(
						"type" => "text",
						"length" => "50"
						),
					"ids1" => array(
                                                "type" => "text",
						"length" => "50"
                                                ),
					"ids2" => array(
						"type" => "text",
						"length" => "50"
						),
					"link" => array(
						"type" => "bool"
						)
					)
				);
				$this->blueprint($props);	
				return;
			}
}
?>
