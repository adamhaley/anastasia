<?
class articles extends blueprint{
	function articles(){
		$this->keywords = array(
                        "category" => new keywords(array(
                                                        "Bio",
                                                        "News",
                                                        "Press Release",
                                                        "Press Coverage",
							"Blurb",
							"Other"
                                                ),"category"
                                        )
                );
 		$props = array(
                  	"local" => new local,
		        "table" => "articles",
                        "manager_mask" => new mask(array("id","title","category","date","image1")),
			"fields" => array(
                                        "id" => array(
                                                "type" => "int",
                                                "length" => "11",
                                                "settable" => "no"
                                                ),
                                        "title" => array(
                                                "type" => "text",
                                                "length" => "20"
                                                ),
					"category" => array(	
						"type" => "keyword_select"
						),
					"date" => array(
						"type" => "date"
						),
					"lead_sentence" => array(
						"type" => "desc",
						"length" => "200"
						),
					"body" => array(
						"type" => "desc",
						"length" => "800"
						),
					"image1" => array(
						"type" => "file"
						)
					)
				);
		$this->blueprint($props);
		return;
	}
}
?>
