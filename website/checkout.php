<?
class checkout{
	function checkout($sfile,$post_vars,$co,$products){
		$this->sfile = $sfile;
		$this->post_vars = $post_vars;
		$this->co = $co;
		$this->products = $products;
		$this->step = ($post_vars[s])? $post_vars[s] : 1;
		$this->cart = new lcart($sfile,new books,$products);		
		if($post_vars[s] == 'Complete Order'){
			$this->step = 3;
		}else if($post_vars[s] == 'Make Changes'){
			$this->step = 1;
		}

		$this->error = array();
	}

	function action(){
		$o = new orders;
                $e = new element($o);
                $s = $this->step;

                if(!$s){
                        $s = 1;
                }
                if($s == 1){
                        return $this->step1();
                }else if($s == 2){
                        return $this->step2();
                }else if($s == 3){
			return $this->step3();
		}
	}
	
	function step1(){
        	return $this->co;
	}
	function step2(){
		$co = $this->co;
		$post_vars = $this->post_vars;
		while(list($key,$value) = each($post_vars)){
			$co[$key] = $value;
		}
		$this->co = $co;
		return $this->co;
	}
	function step3(){
		$co = $this->co;
		$p = $this->products;
		$o = new orders;
		$e = new element($o);
		$total = $this->total;

		if(count($p)){
			for($i=0;$i<count($p);$i++){
				$id = $p[$i][id];
				$qty = $p[$i][qty];
				$b = new books;
				$be = $b->get_element($id);
				$ocode .= "Book#: " . $be->get_prop('book') . "\n";
				$ocode .= "Title: " . $be->get_prop('tnam') . "\n";
				$ocode .= "Author: " . $be->get_prop('anam') . "\n";
				$ocode .= "Qty: $qty \n";
				$ocode .= "Price: $" . $be->get_prop('price') . "\n\n";
				/*
				while(list($key,$value) = each($p[$i])){
					echo " $key : $value<br>\n";
				}
				*/
			}
		}

		
		//Log order to DataBase	
		if(count($co)){
			while(list($key,$value) = each($co)){
				if($value && $key != 'checkout_x' && $key != 'submitted' && $key != 's' && $key != 'id'){
					$e->set_prop($key,$value);
				}
			}
		}
		$e->set_prop('cart',$ocode);
		$e->set_prop('sub_total',"$$total");
		$e->save();
		$this->order= $e;
		
		//$this->error[] = 'Order was not logged to Database';
		
		//Email Order
		$this->email_client();
		//$co = array();
		//return $co;
	}
	function display(){	
		 	$code = "<table border=\"0\" cellpadding=\"20\" cellspacing=\"20\" width=\"700\">
                                <tr>
                                        <td width=\"350\">";
		if($this->step == 1){
			$co = $this->co;
			$code .= "<span class=\"heading\">Checkout</span><br><br><span class=\"main\">Please fill out the required information and hit submit. If there are 
			any changes to be made to the shopping cart you may click \"View Cart\" 
			and edit changes there.  Once you have hit submit we will receive the 
			information and quickly respond confirming availability and total 
			price.  When you receive a confirmation by e-mail, the terms and 
			payment options will be fully outlined.  For more information on 
			payment options and shipping, go to Terms-Conditions.  Thank you.<br><br>
                	Fields marked * are required.</td><td valign=\"top\" width=\"350\">
			<center><a href=\"cart.php\"><img src=\"graphics/view_cart.gif\" border=\"0\"></a></center>
			</td>
			</tr>
			<tr>
			<td colspan=\"2\"><span class=\"main\">
			<form method=\"post\" action=\"" . $this->sfile . "\">
			<input type=\"hidden\" name=\"submitted\" value=\"1\">
			<input type=\"hidden\" name=\"s\" value=\"2\">
			<input type=\"hidden\" name=\"id\" value=\"\">
			<input type=\"hidden\" name=\"checkout_x\" value=\"1\">
			*First Name: <br><input type=\"text\" name=\"first_name\" value=\"" . $co[first_name] . "\" size=\"20\"><br>
			*Last Name: <br><input type=\"text\" name=\"last_name\" value=\"" . $co[last_name] . "\" size=\"20\"><br>
			Phone: <br><input type=\"text\" name=\"phone\" value=\"" . $co[phone] . "\" size=\"20\"><br>
			*Email: <br><input type=\"text\" name=\"email\" value=\"" . $co[email] . "\" size=\"20\"><br>
			*Billing Address: <br><input type=\"text\" name=\"billing_address\" value=\"" . $co[billing_address] . "\" size=\"20\"><br>
			*Billing City: <br><input type=\"text\" name=\"billing_city\" value=\"" . $co[billing_city] . "\" size=\"20\"><br>
			*Billing State/Province: <br><input type=\"text\" name=\"billing_state\" value=\"" . $co[billing_state] . "\" size=\"2\"><br>
			*Billing Zip/Postal Code: <br><input type=\"text\" name=\"billing_zip\" value=\"" . $co[billing_zip] . "\" size=\"20\"><br>
			*Billing Country: <br><input type=\"text\" name=\"billing_country\" value=\"" . $co[billing_country] . "\" size=\"20\"><br>
			Shipping Address(If different from Billing Address): <br><input type=\"text\" name=\"shipping_address\" value=\"" . $co[shipping_address] . "\" size=\"20\"><br>
			Shipping City: <br><input type=\"text\" name=\"shipping_city\" value=\"" . $co[shipping_city] . "\" size=\"20\"><br>
			Shipping State/Province: <br><input type=\"text\" name=\"shipping_state\" value=\"" . $co[shipping_state] . "\" size=\"2\"><br>
			Shipping Zip/Postal Code: <br><input type=\"text\" name=\"shipping_zip\" value=\"" . $co[shipping_zip] . "\" size=\"20\"><br>
			Shipping Country: <br><input type=\"text\" name=\"shipping_country\" value=\"" . $co[shipping_country] . "\" size=\"20\"><br>
			Comments: <br><textarea name=\"comments\" rows=\"7\" cols=\"50\">" . $co[comments] . "</textarea>
			<br><br><input type=\"submit\" name=\"submitted\" value=\"Submit Order\"><font color=\"#ffffff\">...</font><input type=\"reset\">
			</span></form>";
		}else if($this->step == 2){
			$co = $this->co;
			/*
			$products = $this->products;
			
			$bp = new books;
			$mask = $bp->props[cart_mask];
			
			for($i = 0;$i<count($products);$i++){
				$id = $products[$i][id];
				$e = $bp->get_element($id);
						
			}	
			*/
			while(list($key,$value) = each($co)){
				if($value && $key != 'checkout_x' && $key != 'submitted' && $key != 's' && $key != 'id'){
						if(preg_match("/billing/",$key) && $key != 'billing_address'){
							$key = preg_replace("/billing_/","",$key);
						}
						if(preg_match("/shipping/",$key) && $key != 'shipping_address'){
                                                        $key = preg_replace("/shipping_/","",$key);
                                                }
						$key = preg_replace("/_/"," ",$key);
						$key = ucwords($key);
						$list .= "<span class=\"main\"><b>$key:</b><font color=\"#ffffff\">....</font>$value<br><br></span>";
				}else  if(!$value && $key != 'phone' && $key != 'id' && $key != 'comments' && !preg_match("/^shipping/",$key)){
					$key = preg_replace("/_/"," ",$key);
                                        $key = ucwords($key);
					$missing[] = $key;
				}
			}
			reset($co);
			if(count($missing)){
				$code .= "<span class=\"main\">Oops! You did not fill the following required fields. Press \"Make Changes\" to go back, fill them out and continue with the checkout process.<br><br>";
				
				for($i = 0;$i < count($missing); $i++){
					$code .= $missing[$i] . "<br>";
				}
				$code .= "</span>";
				$code .= "<form method=\"post\" action =\"" . $this->sfile . "\"><input type=\"submit\" name=\"s\" value=\"Make Changes\"></form>";
			}else{
				
				$code .= "<span class=\"heading\">Checkout</span><br><br><span class=\"main\">You have entered the following information. If it is correct, press \"Complete Order\".<br> Otherwise, press \"Make Changes\" to return to the form.<br><br></span>";
				$code .= $this->cart->view_full_cart() . "<br><br>";
				$code .= $list;
				$code .= "<br><form method=\"post\" action=\"" . $this->sfile . "\"><input type=\"submit\" name=\"s\" value=\"Complete Order\"><font color=\"#ffffff\">...</font><input type=\"submit\" name=\"s\" value=\"Make Changes\"></form>";
			}
		}else if($this->step == 3){
			if(!count($this->error)){
				$code .= "<span class=\"main\"><b>Thank You!</b><br>Thank you for your order. Someone will be contacting you shortly.</span>";
			}else{
				$code .= "<b>Error:</b><br>";
				for($i=0;$i<count($this->error);$i++){
					$code .= "<span class=\"main\"><font color=\"red\">" . $this->error[$i] . "</font></span><br>";
				}
			}
		}
		$code .= "</td></tr></table>";
                return $code;
	}
	function email_client(){
		$current_date = date("l F j, Y");
        	$current_time = date("g:i a");
		$e = $this->order;
		$id = $e->get_prop('id');
		$email = "The following order was placed at longaeva.com on $current_date at $current_time : \n\n";
		while(list($key,$value) = each($e->props)){
			if($key != 'id' && $key != 'date' && $value){
				$key = preg_replace("/_/"," ",$key);
				$key = ucwords($key);
				$email .= "$key :\n $value\n\n";
			}
		}
		mail("dds@longaeva.com","Order num Received!",$email);
	}
}
?>
