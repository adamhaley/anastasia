<?
class cart {
	
	var $bp;
	var $mask;

	function cart($sfile,$bp,$products,$co,$post_vars,$get_vars,$session_vars = '') {
		$_SESSION['products'] = $products;
		$this->bp = $bp; //for this cart to work correctly, the bp must have a 'price' field
		$this->mask = $bp->props[cart_mask];
		$this->products = $products;
		$this->co = $co;
		$this->sfile = $sfile;
		$this->post_vars = $post_vars;
		$this->get_vars = $get_vars;
		$this->session_vars = $session_vars;		

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
	
	function action(){
		if($this->get_vars[id]){
			return $this->add($this->get_vars[id]);
		}else if($this->post_vars[update]){
			return $this->update();
		}else{
			return $this->products;
		}

	}

	function add($id,$p = ''){
		$products = $this->products;
		$index = count($products);
		$products[$index][id] = $id;
		$products[$index][qty] = 1;

		if($p){
			$products[$index][p] = $p;
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

		$qty = $http_vars[qty];
		$delete = $http_vars[delete];		

        	//loop
        	while($i < count($products)){
                	//assign values from post
			$qt = $qty[$i]? $qty[$i] : 1;
                	$products[$i][qty] = $qt;

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

	function calculate_total(){
                $products = $this->products;
		$bp = $this->bp;

		for($i=0;$i<count($products);$i++){
			$id = $products[$i][id];
			$e = $bp->get_element($id);
			
			$price = $e->get_prop('price');
			settype($price,'double');
			$qty = $products[$i][qty];
			$adjprice = $qty * $price;
			$total += $adjprice;
			
		}
		$total += $this->shipping;
		$total = number_format($total,2);
		return $total;
	}

	function display(){
		if($this->step){
			return	$this->display_checkout();
		}else{
			return $this->display_cart();
		}
	}

	function display_cart(){
		$products = $this->products;
		$mask = $this->mask;
		$bp = $this->bp;
		$props = $mask->filter_keys($bp->props);
		
			
		$d = $bp->props[local]->props[cart_display];		
		
		if(count($products)){
			$out = "<form method=\"post\" action=\"" . $this->sfile . "\"><table border=\"0\" align=\"center\">\n";
                	$dark = $d->darkcolor;
                	$med = $d->lightcolor;
                	$light = $d->bgcolor;
		
			while(list($k,$v) = each($props)){
                		$uck = ucfirst($k);
                        	$out .= "\t\t<td bgcolor=\"$dark\"><b>" . $d->startfont() . "$uck</font></b></td>\n";
                	}
			$out .= "\t\t<td bgcolor=\"$dark\"><b>" . $d->startfont() . "Qty</font></b></td>\n\t\t<td bgcolor=\"$dark\"><b>" . $d->startfont() . "Delete</font></b></td>\n";
			reset($props);
                	$switch = 0;
			$i=0;
			while($i<count($products)){
				$id = $products[$i][id];
				$qty = $products[$i][qty];
			
				if(!$qty){
					$qty = 1;
				}			

				$color = $switch? $light : $med;
			
				$out .= "\t<tr>\n";

				$e = new element($bp,$id);
				$cols = 0;
				while(list($k,$v) = each($props)){
					$out .= "\t\t<td bgcolor=\"$color\">" . $d->startfont();
					if($k == 'price'){
						$out .= "$";
					}
					$out .=  $e->get_prop($k) . "</font></td>\n";
					$cols ++;
				}

				$out .= "\t\t<td bgcolor=\"$color\"><input type=\"text\" size=\"2\" name=\"qty[]\" value=\"$qty\"></td>\n\t\t<td bgcolor=\"$color\"><input type=\"checkbox\" name=\"delete[$i]\"></td>\n";
			

				$cols +=2;
				$switch = $switch ? 0 : 1;
				$i++;
			
				$out .= "\t</tr>\n";
				reset($props);
			}
			$sh = $this->shipping;		
			$out .= "\t<tr>\n";
			$out .= "\t\t<td colspan=\"$cols\" bgcolor=\"$med\"><b>" . $d->startfont() . "Total";
			$out .= $sh? " (Including \$$sh Shipping)" :"";
			$out .= ": $" . $this->calculate_total() . "</font></b></td>\n";
			$out .= "\t</tr>\n";
			$out .= "\t<tr>\n";
			$out .= "\t\t<td colspan=\"$cols\" bgcolor=\"$dark\"><input type=\"submit\" name=\"update\" value=\"Update Cart\"><font color=\"$dark\">...</font>&nbsp;
<input type=\"button\" value=\"Continue Shopping\" onClick=\"javascript:location='" . $this->session_vars['shopuri'] . "'\"><font color=\"$dark\">...</font>&nbsp;<input type=\"submit\" name=\"checkout\" value=\"Checkout\"></td>\n";
			$out .= "\t</tr>\n";
			$out .= "</table></form>\n";
		}else{
			$out = "<center>Your cart is empty.<br></center><br>\n";
			if($shopuri = $this->session_vars['shopuri']){
				$out .= "<a href=\"$shopuri\">Click Here</a> to add items to your Cart.";
			}
		}

		return $out;
	
	}
	
	function display_checkout(){
		$step = $this->step;
		if($step == 1){
			$code = $this->view_full_cart() . $this->view_checkout_step_1();
		}else if($step == 2){
			$code = $this->view_full_cart() . $this->view_checkout_step_2();
		}else if($step == 3){
			$code = $this->view_checkout_step_3();
			session_unset();
		}
		return $code;
	}	

	function view_full_cart(){
		$products = $this->products;
		$mask = $this->mask;
		$bp = $this->bp;
		$props = $mask->filter_keys($bp->props);
		$d = $bp->props[local]->props[cart_display];
		
		$dark = $d->darkcolor;
                $med = $d->lightcolor;
                $light = $d->bgcolor;
		
		$out = "<table border=\"0\" width=\"430\" align=\"center\"><tr>";
		while(list($k,$v) = each($props)){
                        $uck = ucfirst($k);
                        $out .= "\t\t<td bgcolor=\"$dark\"><b>" . $d->startfont() . "$uck</font></b></td>\n";
                }
                $out .= "\t\t<td bgcolor=\"$dark\"><b>" . $d->startfont() . "Qty</font></b></td>\n";
                reset($props);
                $switch = 0;
                $i=0;
                while($i<count($products)){
                        $id = $products[$i][id];
                        $qty = $products[$i][qty];

                        if(!$qty){
                                $qty = 1;
                        }

                        $color = $switch? $light : $med;

                        $out .= "\t<tr>\n";

                        $e = new element($bp,$id);
                        $cols = 0;
                        while(list($k,$v) = each($props)){
                                $out .= "\t\t<td bgcolor=\"$color\">" . $d->startfont();
                                if($k == 'price'){
                                        $out .= "$";
                                }
                                $out .=  $e->get_prop($k) . "</font></td>\n";
                                $cols ++;
                        }

                        $out .= "\t\t<td bgcolor=\"$color\">$qty</td>\n\t\t";


                        $switch = $switch ? 0 : 1;
                        $i++;

                 	$out .= "\t</tr>\n";
                 	reset($props);
		}
		
		$cols += 1;

		$out .= "\t<tr>\n";
                $out .= "\t\t<td colspan=\"$cols\" bgcolor=\"$med\"><b>" . $d->startfont() . "Total: $" . $this->calculate_total();
		$out .= $this->shipping? " (\$" . $this->shipping . " Added for Shipping)" : "";
		$out .= "</font></b></td>\n";
                $out .= "\t</tr>\n";
                $out .= "</table>\n";
                return $out;
	}
	
	function checkout(){
		$s = $this->step;
		if($sh = $this->co['shipping']){
			$this->shipping = floatval($sh);
			$this->shipping_method = $sh;
		}
                if(!$s){
                        $s = 1;
                }
                if($s == 1){
                        return $this->checkout_step_1();
                }else if($s == 2){
                        return $this->checkout_step_2();
                }else if($s == 3){
                        return $this->checkout_step_3();
                }
	}

	function checkout_step_1(){
		return $this->co;
	}

	function checkout_step_2(){
		$co = $this->co;
                $post_vars = $this->post_vars;
                while(list($key,$value) = each($post_vars)){
                        $co[$key] = $value;
                }
                $this->co = $co;
		$this->shipping = floatval($postvars['shipping']);
                return $this->co;
	}

	function checkout_step_3(){
                $num = $this->generate_order_number();

                $results = $this->process_credit_card($num);
                $flag = $results[0];
                $this->messages = $results[1];

                if($flag){
                        //mail order & erase cart & customer info & log order to dbase
                        $this->mail_order($num);
                        $this->log_order($num);
                        $this->products = array();
                        $this->messages = "<center>" . $this->messages . "<br>Your order number is $num. Please save this number in case of future correspondence.\n</center>";
                }else{
                        return $this->co;
                }
        }


	function view_checkout_step_1(){
		$co = $this->co;
		$prod = $co[prod] ? $co[prod] : $post_vars[prod];
        	
		$d = $this->bp->props[local]->props[cart_display];
		$color1 = $d->darkcolor;
		$color2 = $d->lightcolor;
		 return "
<script language=\"javascript\">
	required = new Array(
				\"cardnumber\",
				\"expmonth\",
				\"expyear\",
				\"name\",
				\"email\",
				\"billing_address\",
				\"billing_city\",
				\"billing_state\",
				\"billing_country\",
				\"billing_zip\"
			);		
	dropdowns = new Array(
				\"card\"
			);
	radioarray = new Array();
	
</script>
<form method=\"post\" action=\"" . $this->sfile . "\" name=\"checkout\" onSubmit=\"return validate(this,required,radioarray,dropdowns)\">
<input type=\"hidden\" name=\"s\" value=\"2\">
<table  border=\"0\" align=\"center\" width=\"430\">
        <tr>
                <td colspan = \"2\" bgcolor=\"$color1\">
<input type=\"hidden\" name=\"checked_out\" value=\"2\">
<FONT face=\"Arial, Helvetica, sans-serif\" size=2><b>Please fill out the following information to complete your order:</b></font>
                </td>
        </tr>
        <tr>
                <td colspan = \"2\" bgcolor=\"$color2\">
                <FONT face=\"Arial, Helvetica, sans-serif\" size=2>
                        Your Name:<br><input type=text name=\"name\" value=\"" . $co[name] . "\" size=\"40\">
                        </font>
                                </td>
        </tr>
        <tr>
                <td colspan = \"2\" bgcolor=\"$color1\">
                <FONT face=\"Arial, Helvetica, sans-serif\" size=2>
                        Your e-mail address:<br><input type=\"text\" name=\"email\"
value=\"" . $co[email] . "\" size=\"40\">                        </font>
                </td>
        </tr>
           <tr>
                <td colspan = \"2\" bgcolor=\"$color2\">
                <FONT face=\"Arial, Helvetica, sans-serif\" size=2>
                        Your phone number:<br><input type=\"text\" name=\"phone\" value=\"" . $co[phone] . "\" size=\"40\">
                </font>
                </td>
        </tr>
        <tr>
                <td colspan = \"2\" bgcolor=\"$color1\">
                <FONT face=\"Arial, Helvetica, sans-serif\" size=2>
        Billing Address:<br>
                        <input type=\"text\" name=\"billing_address\" value=\"" . $co[billing_address] . "\" size=\"40\">
                </font>
                </td>
        </tr>
        <tr>
                <td bgcolor=\"$color2\">
<FONT face=\"Arial, Helvetica, sans-serif\" size=2>
City:<br><input type=text name=\"billing_city\" value=\"" . $co[billing_city] . "\" size = \"30\">
                </td>
                <td bgcolor=\"$color2\">
                        <FONT face=\"Arial, Helvetica, sans-serif\" size=2>
State/Providence:<br><input type=text name=\"billing_state\" value=\"" . $co[billing_state] . "\" size=\"2\" maxlength = \"2\">
                      </font>
                </td>
         </tr>
         <tr>
                <td colspan = \"2\" bgcolor=\"" . $color1 . "\">
                <FONT face=\"Arial, Helvetica, sans-serif\" size=2>
                Country:<br><select name=\"billing_country\">
  <option value=\"\" selected>Choose A Country:
		<option value=\"US\">United States
                <option value=\"CA\" >Canada
                </select><br>
                Zip/Postal Code:<br><input type=text name=\"billing_zip\" value=\"" . $co[billing_zip] . "\">
                </font>
                </td>
        </tr>
        <tr>
                <td colspan = \"2\" bgcolor=\"$color2\">
                <FONT face=\"Arial, Helvetica, sans-serif\" size=2>
Shipping Address:(if different from billing address)<br>
                        <input type=\"text\" name=\"shipping_address\" value=\"" . $co[shipping_address]  . "\" size=\"40\">
                </font>
                </td>
        </tr>
        <tr>
                <td bgcolor=\"$color1\">
<FONT face=\"Arial, Helvetica, sans-serif\" size=2>
City:<br><input type=text name=\"shipping_city\" value=\"" . $co[shipping_city] . "\" size = \"30\">
                </td>
                <td bgcolor=\"$color1\">
                <FONT face=\"arial, Helvetica, sans-serif\" size=2>
State/Providence:<br><input type=text name=\"shipping_state\" size=\"2\" value=\"" . $co[shipping_state] . "\" maxlength = \"2\">
                </font>
                </td>
         </tr>
         <tr>
                <td colspan = \"2\" bgcolor=\"$color2\">
                <FONT face=\"Arial, Helvetica, sans-serif\" size=2>
                Country:<br><select name=\"shipping_country\">
                <option selected value=\"\">Choose A Country:
		<option value=\"US\">United States
                <option value=\"CA\">Canada
                </select><br>

                <FONT face=\"Arial, Helvetica, sans-serif\" size=2>
                Zip/Postal Code:<br><input type=text name=\"shipping_zip\" value=\"" . $co[shipping_zip] . "\">
                </font>
                </td>
        </tr>
        <tr>
    <td colspan='2' bgcolor=\"$color1\">
                        <FONT face=\" Arial, Helvetica, sans-serif\" size=2>If you are not
the recipient then name of person receiving this shipment:<br>
                        <input type=\"text\" name= \"recipient_name\" value=\"" . $co[recipient_name] . "\" size=\"40\">
                        </font>
                </td>
        </tr>
         <tr>
                <td colspan = \"2\" bgcolor=\"$color2\">
                <FONT face=\"Arial, Helvetica, sans-serif\" size=2>
Credit Card:
<select name=\"card\">
<option selected value=\"American Express\">American Express
<option value=\"Visa\">Visa
<option value=\"Master Card\">Master Card
</select>
<br>
Credit Card Number:
<input type = \"text\" name=\"cardnumber\" size=20 value=\"" . $co[cardnumber] . "\" maxlength = \"16\">
<br><br>
Exp. Date: (mm-yy)
<input type= \"text\" name=\"expmonth\" size=3 value=\"" . $co[expmonth] . "\" maxlength = \"2\">/<input type= \"text\" name=\"expyear\" value=\"" . $co[expyear] . "\" size=3 maxlength = \"2\"><br>
</font>
                </td>
        </tr>
        <tr>
            <td colspan = \"2\" bgcolor=\"$color1\">
<input type=\"submit\" value=\"Submit Order\"> <input type=\"reset\" value=\"Clear Form\" >
                </td>
        </tr>
</table>
</form>";
	}

	function view_checkout_step_2(){
		$post_vars = $this->post_vars;
		$get_vars = $this->get_vars;
		$co = $this->co;
		$d = $this->bp->props[local]->props[cart_display];
		$sitename = $this->bp->props[local]->props[website];		

		$color1 = $d->darkcolor;
		$color2 = $d->lightcolor;

		return "<table cellpadding=\"2\" align=\"center\" cellspacing=\"2\" width=\"430\">
                <tr>
			<td bgcolor=\"$color2\">
			<font face=\"Arial,Helvetica,Sans-Serif\" size=2>
			Please review your order. If everything is correct then you may submit your order for processing.
			</font>
			</td>
		</tr> 
		<tr>
                        <td align=\"left\" bgcolor=\"$color1\">
                        <FONT face=\"Arial, Helvetica, sans-serif\" size=2>
                         <b>Name:</b> " . $post_vars[name] . "<br>
                        </font>
                        </td>
                </tr>
                <tr>
                        <td align=\"left\" bgcolor=\"$color2\">
                        <FONT face=\"Arial, Helvetica, sans-serif\" size=2>
                                 <b>E-mail Address:</b> " . $post_vars[email] . " <br>
                        </font>
                        </td>
                </tr>
                 <tr>
                        <td align=\"left\" bgcolor=\"$color2\">
                        <FONT face=\"Arial, Helvetica, sans-serif\" size=2>
                                 <b>Phone:</b> " . $post_vars[phone] . " <br>
                        </font>
                        </td>
                </tr>
                <tr>
                        <td align=\"left\" bgcolor=\"$color1\">
                        <FONT face=\"Arial, Helvetica, sans-serif\" size=2>
                        <b>Billing Address:</b> " . $post_vars[billing_address] . "<br>
                        </font>
                        </td>
                </tr>
                <tr>
                        <td align=\"left\" bgcolor=\"$color2\">
                        <FONT face=\"Arial, Helvetica, sans-serif\" size=2>
                                <b>City:</b> " . $post_vars[billing_city]  . "<br>
                        </font>
                        </td>
                </tr>
                <tr>
                        <td align=\"left\" bgcolor=\"$color1\">
                        <FONT face=\"Arial, Helvetica, sans-serif\" size=2>
                                <b>State:</b> " . $post_vars[billing_state] . "<br>
                        </font>
                        </td>
                </tr>
                <tr>
                        <td bgcolor=\"$color2\">
                            <FONT face=\"Arial, Helvetica, sans-serif\" size=2>
                                <b>Zip:</b> " . $post_vars[billing_zip] . " <br><br>
                                </font>
                        </td>
                </tr>
                   <tr>
                        <td bgcolor=\"$color1\">
                            <FONT face=\"Arial, Helvetica, sans-serif\" size=2>
                                <b>Shipping Address (optional):</b> " . $post_vars[shipping_address] . "<br>
                                </font>
                        </td>
                </tr>
                <tr>
                        <td bgcolor=\"$color2\">
                            <FONT face=\"Arial, Helvetica, sans-serif\" size=2>
                                <b>City (optional):</b> " . $post_vars[shipping_city] . "<br>
                                </font>
                        </td>
                </tr>
                <tr>
                        <td bgcolor=\"$color1\">
                                <FONT face=\"Arial, Helvetica, sans-serif\" size=2>
                            <b>State (optional):</b> " . $post_vars[shipping_state] . "<br>
                                </font>
                        </td>
                </tr>
                <tr>
                        <td bgcolor=\"$color2\">
                                <FONT face=\"Arial, Helvetica, sans-serif\" size=2>
                            <b>Zip (optional):</b> " . $post_vars[shipping_zip] . "<br>
                                </font>
                         </td>
                <tr>
                        <td bgcolor=\"$color1\">
                                <FONT face=\"Arial, Helvetica, sans-serif\" size=2>
                    <b>Recipient Name (optional):</b> " . $post_vars[recipient_name] . "<br>
                                </font>
                        </td>
                </tr>
                <tr>
                        <td bgcolor=\"$color2\">
                                <FONT face=\"Arial, Helvetica, sans-serif\" size=2>
                                <b>Credit Card:</b> " . $post_vars[card] . "<br>
                                <b>Credit Card Exp. Date:</b> " . $post_vars[expmonth] . "-" . $post_vars[expyear] . "<br>

                        </font>
                        </td>
                </tr>
                <tr>
                        <td bgcolor=\"$color1\"><br>
                        <FONT face=\"Verdana, Arial, Helvetica, sans-serif\" size=2>
                        *I authorize $sitename  to charge the amount shown for 
			this order to my credit card. I am the signer of this
			credit card and am authorized to make purchases.
 </font>
                        <form method=\"post\" action=\"" . $this->sfile . "\">
                                <input type=\"hidden\" name=\"s\" value=\"3\">
                                <input type=\"submit\" name = \"complete\" value =\"Complete Order\">
                              <input type=\"submit\" name = \"modify\" value = \"Make Changes\">
                        </form>
                        </td>
                </tr>
        </table>";
	}

	function view_checkout_step_3(){
		return $this->messages;
	}
	
	function mail_order($ordernum){
		$co = $this->co;
		$products = $this->products;

		$email = $this->bp->props[local]->props[email];
		
		$custemail = $co[email];
		
		$bp = $this->bp;
		$mask = $this->mask;
		$props = $mask->filter_keys($bp->props);
		
		$sm = $this->shipping_method ? $this->shipping_method : '';

		//Build vendor email
		$sitename = $bp->props[local]->props[website];
		$body = "The following order was just placed at $sitename:\n\n"; 
		$order = "Order: \n\n";
		for($i=0;$i<count($products);$i++){
			$id = $products[$i][id];
			$qty = $products[$i][qty];
			$e = new element($bp,$id);
			reset($props);
			while(list($key,$value) = each($props)){
				$ukey = ucfirst($key);
				$order .= "$ukey : " . $e->get_prop($key) . " \n";
			}
			$order .= "quantity: $qty \n";		
			$order .= "\n";
		}
		if($sm){
			$order .= "Order will be sent \$$sm \n\n";
		}
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

		//Send vendor email
		$l = new local;
		$vbody  = "View/Manage this order at " . $l->props[url] . "/control/index.php?action=manage&what=orders\n\n" . $body;
		mail($email,"Order #$ordernum Recieved",$vbody,"From: $sitename<$email>");
		
		//Send Customer email
		$body = "You have just placed the following order at $sitename:\n
		Your order will be processed immediately. Thanks for shopping at $sitename!\n\n";
		$body .= $order . $total;
		mail($custemail,"Your order #$ordernum",$body,"From: $sitename<$email>");

	}
	
	function process_credit_card($num){
		//override this with your particular credit card processing stuff
		//return an array with a flag for success or failure, then any messages

		return array(1,"Thank You!");
	}
	
	function log_order($num){
		$co = $this->co;
		$products = $this->products;
		
		$bp = new orders;
		$e = new element($bp);
		while(list($key,$value) = each($co)){
			if($e->prop_exists($key)){
				$e->props[$key] = $value;
			}
		}
		$e->props['order_num'] = $num;
		
		$bp = $this->bp;
                $mask = $this->mask;
		$props = $mask->filter_keys($bp->props);

		for($i=0;$i<count($products);$i++){
			$id = $products[$i][id];
			$qty = $products[$i][qty];
			
			$prod = new element($bp,$id);
			while(list($key,$value) = each($props)){
                                $ukey = ucfirst($key);
                                $details .= "$ukey : " . $prod->get_prop($key) . "\n";
                        }
			$details .= "\n";
		}
		$sh = $this->shipping;
		$details .= "Total";
		$details .= $sh? " (Including \$$sh Shipping)" : "";
		$details .= ": "; $this->calculate_total() . "\n\n";
		$e->props['details'] = $details;
		$e->props['card_number'] = $co[cardnumber];
		$e->props['card_exp'] = $co[expmonth] . $co[expyear];
		$e->props['date_placed'] = date("F j, Y");
		
		$e->save();
	}
	
	function generate_order_number(){
		mt_srand ((double) microtime() * 1000000);
        	$randval = mt_rand();
        	$num = round($randval,10);
        	return $num;
	}
}

?>
