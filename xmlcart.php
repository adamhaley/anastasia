<?
class xmlcart {
	//this is a shopping cart that can handle elements from multiple blueprints, not just one	
	function xmlcart($sfile,$products,$co,$post_vars,$get_vars,$session_vars = '') {
		session_register('products');
		//for this cart to work correctly, the bp must have a 'price' field
		$this->products = $products;
		$this->co = $co;
		$this->sfile = $sfile;
		$this->post_vars = $post_vars;
		$this->get_vars = $get_vars;
		$this->session_vars = $session_vars;		
		$this->order_number = 0;
		$this->xslpath = '/php/anastasia/xsl/';
		$this->shipping = $co['shipping_option'] ? floatval($co['shipping_option']) : '';
		//order number will be set when log_order() is called	
	
		//figure out & record what step this is
		if($post_vars[checkout] || $post_vars[modify]){
			$this->step = 1;
		}else if($post_vars[s]){
			$this->step = $post_vars[s];	
		}else if($get_vars[s]){
			$this->step = $get_vars[s];
		}else{
			$this->step = 0;
		}
	}
	
	function update_products(){
		if($this->get_vars['bp'] && $this->get_vars['id']){
			return $this->add($this->get_vars['bp'],$this->get_vars['id']);
		}else if($this->post_vars['update']){
			return $this->update();
		}else{
			return $this->products;
		}
	}

	function add($bp,$id,$p = ''){
		echo "in add\n";
		//bp is the string name of the bp
		$products = $this->products;
		$index = count($products);
		//echo "bp is $bp";
		//echo "number of products is " . count($products);
		$products[$index]['bp'] = $bp;
		$products[$index]['id'] = $id;
		$products[$index]['qty'] = 1;

		if($p){
			$products[$index]['p'] = $p;
		}
		$this->products = $products;
		return $products;
	}

	function update(){
		//handles deletes and quantity changes
		//todo - add support for property changes
		
		$http_vars = $this->post_vars;
		$products = $this->products;
 		$i = 0;

		$qty = $http_vars['qty'];
		$delete = $http_vars['delete'];		

        	//loop
        	while($i < count($products)){
                	//assign values from post
			$qt = $qty[$i]? $qty[$i] : 1;
                	$products[$i]['qty'] = $qt;

                	//if the item is not deleted, add it to the tmp array
                	//& increment it's counter
                	if(!$delete[$i]){
                        	$tmp[] = $products[$i];
                	}

                	//increment $products counter
                	$i++;
        	}
		$products = $tmp;
		$this->products = $products;
		return $this->products;
	}

	function get_unique_bps($products){
		//gets unique blueprint names & returns as arrays
		$i=0;
		$bps = array();
		while($i<count($products)){
			$bpname = $products[$i]['bp'];
			if(!in_array($bpname,$bps)){
				$bps[] = $bpname;
			}
			
			$i++;
		}
		return array_unique($bps);
	}
	
	function get_products_by_bpname($products,$bpname){
		$retarray = array();
		for($i=0;$i<count($products);$i++){
			if($bpname==$products[$i]['bp']){
				$retarray[] = $products[$i];
			}
		}
		return $retarray;
	}
	
        function cart_data(){
		$products = $this->products;
		$bps = $this->get_unique_bps($products);
		
		$out = "<cart>\n";
		foreach($bps as $bpname){
			$out .= "\t<bp name=\"$bpname\">\n";			
			
			$bp = $this->instantiate_bp($bpname);
			$mask = $bp->props['cart_mask'];

			$products = $this->get_products_by_bpname($products,$bpname);
			foreach($products as $prod){
				$out .= "\t\t<el>\n";
				
				$e = new element($bp,$prod['id']);				

				$props = $e->props;
				if(is_object($mask)){
					$props = $mask->filter_keys($props);
				}else{
					echo "no cart mask found\n";
				}				

				foreach($props as $key=>$value){
					$out .= "\t\t\t<$key>$value</$key>\n";
				}
				$out .= "\t\t\t<qty>" . $prod['qty'] . "</qty>\n";	
	
				$out .= "\t\t</el>\n";
			}
			$out .= "\t</bp>\n";
		}
		$out .= "</cart>";
		return $out;
        }

	function customer_data(){
		$co = $this->co;
		$out .= "<customer>\n";
		$out .= "\t<name>" . $co['name'] . "</name>\n";
		$out .= "\t<email>" . $co['email'] . "</email>\n";
		$out .= "\t<shipping_address>" . $co['shipping_address'] . "</shipping_address>\n";
		$out .= "\t<shipping_city>" . $co['shipping_city'] . "</shipping_city>\n";
		$out .= "\t<shipping_state>" . $co['shipping_state'] . "</shipping_state>\n";
		$out .= "\t<shipping_country>" . $co['shipping_country'] . "</shipping_country>\n";
		$out .= "\t<shipping_zip>" . $co['shipping_zip'] . "</shipping_zip>\n";
		$out .= "\t<billing_address>" . $co['billing_address'] . "</billing_address>\n";
                $out .= "\t<billing_city>" . $co['billing_city'] . "</billing_city>\n";
                $out .= "\t<billing_state>" . $co['billing_state'] . "</billing_state>\n";
	 	$out .= "\t<billing_country>" . $co['billing_country'] . "</billing_country>\n";

                $out .= "\t<billing_zip>" . $co['billing_zip'] . "</billing_zip>\n";
		$out .= "\t<recipient_name>" . $co['recipient_name'] . "</recipient_name>\n";
		$out .= "\t<shipping_option>" . $co['shipping_option'] . "</shipping_option>\n";
		$out .= "\t<card>" . $co['card'] . "</card>\n";
		$out .= "\t<card_number>" . $co['card_number'] . "</credit_card_number>\n";
		$out .= "\t<expiration>" . $co['expiration'] . "</expiration>\n";
		$out .= "</customer>\n";
		return $out;
	}

	function display(){
		$step = $this->step;
		
		$xml = $this->cart_data();
		if($step){
			$xml .= $this->customer_data();
		}

		//choose an xsl file
		switch($step){
			case '':
				$xslfile = 'cart.xsl';
				break;
			case 1:
				$xslfile = 'checkout_step1.xsl';
				break;
			case 2:
				$xslfile = 'checkout_step2.xsl';
				break;
			case 3: 
				$xslfile = 'checkout_step3.xsl';
				break;
		}
		$xslpath = $this->xslpath;

		$xh = xslt_create();

		echo "xsl is $xslfile";

		$xml = "<? xml version=\"1.0\" ?>";

		$args = array('/_xml' => $xml);

		$xsl = $xslpath . "/" . $xslfile;

		$out = xslt_process($xh,'arg:/_xml', "$xslpath/$xslfile",NULL,$args);
		if (!$out) {
			$out = xslt_error($xh);
		}
		xslt_free($xh);

		//clear session
		if($step == 3){
			session_unset();
		}
		return $out;
	}	

	
	function mail_order(){
		$co = $this->co;
		$products = $this->products;

		$l = new local;
		$email = $l->props['email'];
		
		$custemail = $co['email'];
		
		$bps = $this->get_unique_bps($products);

                for($c=0;$c<count($bps);$c++){
                        $bpname = $bps[$c];
                        $prodarray = $this->get_products_by_bpname($products,$bpname);
                        $bp = $this->instantiate_bp($bpname);
                        $mask = $bp->props['cart_mask'];
                        $props = $mask->filter_keys($bp->props);
                        $cols = count($props);

                        $props = $mask->filter_keys($bp->props);

			$sm = $this->shipping_method ? $this->shipping_method : '';
		}
		//Build vendor email
		$sitename = $bp->props[local]->props[website];
		$body = "The following order was just placed at $sitename:\n\n"; 
		$order = "Order: \n\n";

		$bps = $this->get_unique_bps($products);

		
                $sm = $this->shipping_method ? $this->shipping_method : '';


                for($c=0;$c<count($bps);$c++){
                        $bpname = $bps[$c];
                        $prodarray = $this->get_products_by_bpname($products,$bpname);
                        $bp = $this->instantiate_bp($bpname);
                        $mask = $bp->props['cart_mask'];
                        $props = $mask->filter_keys($bp->props);
                        $cols = count($props);

                        $props = $mask->filter_keys($bp->props);
			
			
			$order .= strtoupper($bpname) . " :\n";
	
			for($i=0;$i<count($prodarray);$i++){
                        	$id = $prodarray[$i]['id'];
                        	$qty = $prodarray[$i]['qty'];
                        	$e = new element($bp,$id);
                        	reset($props);
                        	while(list($key,$value) = each($props)){
                                	$ukey = ucfirst($key);
                                	$order .= "$ukey : " . $e->get_prop($key) . " \n";
                        	}
                        	$order .= "quantity: $qty \n";
                        	$order .= "\n";
                	}
                }

		/*if($sm){
			UNCOMMENT THIS IF YOU CAN GET THE SHIPPING METHOD TO PASSTHROUGH RIGHT
			$order .= "Order will be sent \$$sm \n\n";
		}
		*/
		$sh = $this->shipping;
		$total = "Total";
		$total .= $sh? "(\$$sh Shipping Included)" : "";
		$total .= ": \n$";
		$total .= number_format($this->calculate_total(),2) . "\n\n";
		$custinfo .= "\n";
		reset($this->co);
		while(list($key,$value) = each($this->co)){
			if($key != 's' && $key != 'checked_out' && $key != 'cardnumber'){
				$key = strtoupper($key);
				$body .= "$key:\n$value\n\n";
			}
		}
		$body .= $order . $total . $custinfo;

		//Log Order to DB
		$ordernum = $this->log_order($order);	

		//Send vendor email
		$l = new local;
		$vbody  = "View/Manage this order at " . $l->props['url'] . "/control/index.php?action=edit&what=orders&id=$ordernum\n\n" . $body;
		mail($email,"Order #$ordernum Recieved",$vbody,"From: $sitename<$email>");
		
		//Send Customer email
		$body = "You have just placed the following order at $sitename:\nYour order will be processed immediately. Thanks for shopping at $sitename!\n\n";
		$body .= $order . $total;
		mail($custemail,"Your order #$ordernum",$body,"From: $sitename<$email>");

	}
	
	function process_credit_card($num){
		//override this with your particular credit card processing stuff
		//return an array with a flag for success or failure, then any messages

		return array(1,"");
	}
	
	function log_order($details){
		$co = $this->co;
		
		$bp = new orders;
		$e = new element($bp);
		while(list($key,$value) = each($co)){
			if($e->prop_exists($key)){
				$e->props[$key] = $value;
			}
		}

		$sh = $this->shipping;
		$details .= "Total";
		$details .= $sh? " (Including \$$sh Shipping)" : "";
		$details .= ": " . $this->calculate_total() . "\n\n";
		$e->props['details'] = $details;
		$e->props['card_number'] = $co[cardnumber];
		$e->props['card_exp'] = $co[expmonth] . $co[expyear];
		$e->props['date_placed'] = date("F j, Y");
		
		//save order
		$e->save();
		
		//set and return order number
		$num = $e->get_prop('id');
		$this->order_number = $num;
		return $num;
	}
	
	function instantiate_bp($bpname){
		//creates an blueprint object of class $bpname & returns
		if(class_exists($bpname)){
			eval("\$bp  = new $bpname;");
			return $bp;
		}else{
			die("Error in Multicart::instantiate_bp() - Blueprint Class $bpname does not exist.");
		}
	}
}

?>
