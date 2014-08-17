<?
class orders extends blueprint{
	function orders(){
		$this->blueprint(array("local" => new local));
	 	$this->props = array(
                        "table" => "orders",
                        "manager_mask" => new mask(array("id","name","email","card","card_exp","date_placed")),
                        "fields" => array(
                                        "id" => array(
                                                "type" => "id"
                                                ),
                                        "details" => array(
                                                "type" => "desc",
                                                "length" => "400"
                                                ),
                                        "card" => array(
                                                "type" => "text",
                                                "length" => "30"
                                                ),
                                        "card_number" => array(
                                                "type" => "text",
                                                "length" =>"40"
                                                ),
					"card_exp" => array(
                                                "type" => "text",
                                                "length" =>"20"
                                                ),
					"name" => array(
                                                "type" => "text",
                                                "length" => "20"
                                                ),
				 	"email" => array(
                                                "type" => "text",
                                                "length" => "20"
                                                ),
					"billing_address" => array(
                                                "type" => "text",
                                                "length" => "20"
                                                ),
                                        "billing_city" => array(
                                                "type" => "text",
                                                "length" => "20"
                                                ),
                                        "billing_state" => array(
                                                "type" => "text",
                                                "length" => "20"
                                                ),
                                        "billing_zip" => array(
                                                "type" => "text",
                                                "length" =>"20"
                                                ),
                                        "mailing_address" => array(
                                                "type" => "text",
                                                "length" => "20"
                                                ),
                                        "mailing_city" => array(
                                                "type" => "text",
                                                "length" => "20"
                                                ),
                                        "mailing_state" => array(
                                                "type" => "text",
                                                "length" => "20"
                                                ),
                                        "mailing_zip" => array(
                                                "type" => "text",
                                                "length" =>"20"
                                                ),
					"recipient_name" => array(
                                                "type" => "text",
                                                "length" =>"20"
                                                ),
					"date_placed" => array(
						"type" => "text",
						"length" => "20"
						),
					"archive" => array(
						"type" => "bool"
						)
				)
			);
	}
}
?>
