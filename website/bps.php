<?
class bps extends blueprint {

	function bps(){

	 	$props = array(
			"local" => new local,
                        "table" => "bps", //name the table the same as the class name
                        "manager_mask" => new mask(array("id","name")),
                        "fields" => array(
                                        "id" => array(
                                                "type" => "iD",
                                                "length" => "11",
                                                "settable" => "no"
                                                ),
                                        "name" => array(
                                                "type" => "text",
                                                "length" => "30"
                                                )
					)
				);
				$this->blueprint($props);	
				return;
			}
			function get_elinks($pageid){
				$page = $this->get_element($pageid);
				$lpage = new linkedelement($this,$page->get_prop('id'));
				return $lpage->get_elinks();
			}

}
?>
