<?
class frontend{

	function frontend($sfile,$request_uri,$get_vars,$post_vars,$session_vars = '',$dynstatic=''){
		$this->sfile = $sfile;
		$this->request_uri = $request_uri;
		$this->get_vars = $get_vars;
		$this->post_vars = $post_vars;
		$this->session_vars = $session_vars;
		$this->html = new html($sfile);
		$this->dynstatic=$dynstatic? $dynstatic : 'dynamic';
	}
	
	function read_file($file){
                $fp = fopen($file,'r');
                $content = fread($fp,filesize($file));
                fclose($fp);
                return $content;
        }

	/*
	function display_page_xsl($page){
		$bp = $page->bp;

                $l = $bp->props[local];
                $sitename = $l->props[website];

                //turn page into the linked element that it is
                $pid = $page->props[id];
                //echo "page id is $pid \n<br>";

                $le = new linkedelement($bp,$pid);

	}
	*/

	function display_page($page){
		$bp = $page->bp;

                $l = $bp->props[local];
                $sitename = $l->props[website];
		
		//turn page into the linked element that it is
		$pid = $page->props[id];
		//echo "page id is $pid \n<br>";

		$le = new linkedelement($bp,$pid);
		//get the template that's linked to it(note - templates can only be linked to pages and links)
		
		if($tlink = $le->get_template_link()){
			$tid = $tlink->get_prop('ids2');
			$tid = preg_replace("/[\[\]]/","",$tid);
			//echo "tid is $tid \n<br>";
			$tobj = new template($tid);

			//echo "tname is $tname\n<br>";

			//get html object, set& parse its template
			$h = $this->html;
			$h->bp = new pages;
			//get the source & set template
                        if($source = $tobj->get_prop('html_source')){
				//here i'm going to eliminate the parsing of 
				//<%name%> if the page is 'home';
				//so headings wont appearon home.
				//this is not a great way to do it
				//but it will work for now
				if($page->get_prop('name') =='Home' ){
					$source = str_replace('<%name%>','',$source);
				}
				$h->template = $source;
                        }elseif($tname = $tobj->get_prop('html')){
				$h->set_template("templates/$tname");
			}else{
				return "empty template\n";
			}

			$code = $h->parse_template('',$page); 
			//recursively parse all elinks
			$code = $this->parse_and_replace($code,$page);		

			//NAV
			if(preg_match("/<%nav%>/",$code)){
                		$nav = $this->nav();
                		$code = str_replace("<%nav%>",$nav,$code);
			}else if(preg_match("/<%nav0%>/",$code)){
				$parray = $this->_get_page_array();
				for($i=0;$i<count($parray);$i++){
					$navlet = $this->navlet($i);
					$ss = "<%nav$i%>";
					//echo "s is " . $ss;
					$code = str_replace($ss,$navlet,$code);
				}
			}
			$code = str_replace("<%sitename%>",$sitename,$code);
			return $code;
		}else{
			return "No templates have been linked to this page\n";
		}
	}

	function parse_and_replace($code,$eobj){
		//recursively parse all elinks
                $linkarray = $this->parse_elinks($eobj);
			
		$le = new linkedelement($eobj->bp,$eobj->get_prop('id'));
		$numlinks = count($le->get_elinks());
                
		if($eobj->bp->props[table] == 'films'){
			//echo "<br>numlinks is $numlinks \n";
		}


		for($i =0;$i<count($linkarray);$i++){
                        $parsedlinks = $linkarray[$i];
			//echo "parsed links is " . count($parsedlinks) . " long \n<br>";
        		$lcode = '';
	                for($x = 0;$x<count($parsedlinks);$x++){
				$lcode .= $parsedlinks[$x];
                        }
			if(preg_match("/<%elink$i%>/",$code)){
				$code = str_replace("<%elink$i%>",$lcode,$code);
			}else{
				$extra .= $lcode;
			}
                }
		$code = preg_replace("/<%elink[0-9]{1,}%>/","",$code);
                $code = str_replace("<%elinks%>",$extra,$code);
		//check for slideshow tag
		if(preg_match("/<%slideshow%>/",$code)){
			$larray = $le->get_elinks();

			//assume first elink
			if(!$el = $larray[0]){
				$code = str_replace("<%slideshow%>","No element for slideshow...",$code);
				return $code;
			}
			$earray = $el->get_element_objects();
			$e = $earray[0];
			$slideshow = $this->slideshow($this->request_uri,$this->get_vars,$e);
			$code = str_replace("<%slideshow%>",$slideshow,$code);
		}else if(strstr($code,"<%bp_slideshow%>")){
			//how to know what bp??? hmmm for now lets default to images
			//send it to bp_slideshow(), which can be overridden if its a bp other than images	
			$code = str_replace("<%bp_slideshow%>",$this->bp_slideshow(),$code);
		}
		//check for cart tag
		//note - this doesnt work yet...this is a wishlist feature for now
		// need to figure out how to get certain cart configurations from the control area first
		if(strstr($code,"<%cart%>")){
			$_SESSION['products'] = array();
			$_SESSION['co'] = array();

			//$bp = new products;
			$c = new cart($this->sfile,$bp,$products,$co,$this->post_vars,$this->get_vars,$this->session_vars);

			if($c->step){
        			$co = $c->checkout();
			}else{
        			$products = $c->ction();
			}
			//echo $c->display;
			$code = str_replace("<%cart%>",$c->display,$code);
		}
		
		return $code;
	}

	function parse_elinks($e){
		$bp = $e->bp;

		$l = $bp->props[local];
		
		//turn this element into a linked element
		$el = new linkedelement($bp,$e->props[id]);
		//get all elinks to that element
		$elinks = $el->get_elinks();
	
		//echo "number of elinks is " . count($elinks);
		for($i=0;$i<count($elinks);$i++){
			$elink = $elinks[$i];
			//echo "elink $i is an object of class " . get_class($elink) . " conditions are:" . $elink->get_prop('conditions2') . "\n<br>";
			//echo "bp2 is " . $elink->get_prop('bp2') . "<br><br>";
			//$tmplt = $elink->get_html_name();
			$tobj = $elink->get_html_object();
			$tmplt = $tobj->get_prop('html');			
			$htmlsource = $tobj->get_prop('html_source');
			$elements = $elink->get_element_objects();
			//echo "template is $tmplt";			
			//echo " number of elements for elink $i is " . count($elements) . "\n<br>";
			$ecode = array();
			for($x=0;$x<count($elements);$x++){
				$eh = new html($this->sfile,$elements[$x]);
				$bpp = $elements[$x]->bp->props[table];
				//echo "element $x's blueprint is $bpp \n<br>";				
				//echo "the template is $tmplt\n<br>";
				if($tmplt || $htmlsource){
					if($tmplt){
						$eh->set_template("templates/$tmplt");
					}else if($htmlsource){
						$eh->template = $htmlsource;
					}
					$ecode[$x] = $eh->parse_template();
				}else{
					$ecode[$x] = $elements[$x]->view();
				}
				if(preg_match("/<%elink/",$ecode[$x])){
					//echo "element $i is a " . $elements[$x]->bp->props[table] . "<br>";
					$ecode[$x] = $this->parse_and_replace($ecode[$x],$elements[$x]);
				}
			}
			//echo "ecode is " . count($ecode) . " long \n<br>";
			$elinkcode[$i] = $ecode;		
		}
		//echo "elinkcode is " . count($elinkcode);
		return $elinkcode;
	}

	function action($n=''){
		$get_vars = $this->get_vars;

		//get bp
		if(!$bp = $get_vars[bp]){
			$bp = $post_vars[bp];
		}
		//get id
		if(!$id = $get_vars[id]){
			$id = $post_vars[id];
		}
		//get cat
		if(!$cat = $get_vars[cat]){
			$cat = $post_vars[cat];
		}	
		if(!$byid = $get_vars[byid]){
			$byid = $post_vars[byid];
		}
		if(!$p = $get_vars[p]){
			$p = $post_vars[p];
		}
		if($id && $p!='Cart'){
			//temporary fix...return to byid flag to test
			$code = $this->byid($cat,$id);
			$code = $this->wrap_in_main($code);
		}else if($cat){
			$code = $this->display_by_cat($cat,$byid);
			$code = $this->wrap_in_main($code);
		}else if($p){
			$bpobj = new pages;
			$pobj = new linkedelement($bpobj);
			$p = str_replace("-"," ",$p);
			$pobj->populate_from_profile(array('name' => "$p"));
			
			$code = $this->display_page($pobj);
			$code = $this->wrap_in_main($code);
		}
		$code = ($n=='nolinks')? $code : $this->replace_links($code);
		return $code;
	}

	function replace_links($code){
		$p = new publisher($this);	
		if($this->dynstatic == 'dynamic'){
			$code = $p->replace_links_dynamic($code,$this->sfile);
		}else if($this->dynstatic == 'static'){
			$code = $p->replace_links_static($code);
		}
		return $code;
	}

	function get_main_template(){
		$bp = new templates;
		$ta = $bp->get_all_elements(array('name' => 'main'));
		if($ta[0]){
			return $ta[0];
		}else{
			
			//echo "returning false";
			return false;
		}
	}
	function wrap_in_main($pagecode,$tid=''){
		//optional template id
		$h = $this->html;
		if($tid){
			$maintemplate = new template($tid);
		}else{
			$maintemplate = $this->get_main_template();
		}
		if($maintemplate){
			$file = "templates/" . $maintemplate->get_prop('html');
			if(!$css = "style/" . $maintemplate->get_prop('stylesheet')){
				 $css = $maintemplate->get_prop('stylesheet_source');  
			}
			if(!$code = $maintemplate->get_prop('html_source')){
				$code = $h->read($file);
			}
			$code = str_replace("<%css%>",$css,$code);
			$code = str_replace("<%pages%>",$pagecode,$code);
			$local = new local;
			$code = str_replace("<%sitename%>",$local->props[website],$code);
			if(preg_match("/<%nav%>/",$code)){
                                $nav = $this->nav();
                                $code = str_replace("<%nav%>",$nav,$code);
                        }else if(preg_match("/<%nav0%>/",$code)){
                                $parray = $this->_get_page_array();
                                for($i=0;$i<count($parray);$i++){
                                        $navlet = $this->navlet($i);
                                        $ss = "<%nav$i%>";
                                        $code = str_replace($ss,$navlet,$code);
                                }
                        }
			$code = str_replace("<%nav_js%>",$this->nav_js(),$code);
		}else{
			$code = $pagecode;
		}
		$code = $this->frontend_final_stuff($code);

	        //call any frontend functions that may need calling
		$code = $this->call_frontend_functions($code);
		return $code;
	}	

	function call_frontend_functions($code){
		 if(preg_match_all("/<%(.*?)\((.*?)\)%>/",$code,$matches)){
                                for($i=0;$i<count($matches[1]);$i++){
                                        $function = $matches[1][$i];
                                        $args = $matches[2][$i];
                                        if(!$args){$args='';}
                                        if(method_exists($this,$function)){
                                                eval("\$stuff = \$this->" . $function . "(" . $args . ");");
                                                $code = str_replace("<%$function($args)%>",$stuff,$code);
                                        }
                                }
                }else{
			return $code;
		}
		//call any new ones put there by the first iteration
		$code = $this->call_frontend_functions($code);
		return $code;
	}

	function navlet($i){
                    $parray = $this->_get_page_array();
			 $id = $this->get_vars[id] ? $this->get_vars[id] : $this->post_vars[id];
			if(!$id){
				$pagename = $this->get_vars[p]? $this->get_vars[p] : $this->post_vars[p];
			} 
			$thispage = $parray[$i];

	         	$linkid = $thispage->get_prop('id');
                    $lname = $thispage->get_prop('name');
 			$lclname = strtolower($lname);
			$lcpagename = strtolower($pagename);			

                    if(preg_match("/^mailto/",$thispage->get_prop('content'))){
                          $nav .= "<a href=\"" . $thispage->get_prop('content') . "\"";
                    }else if(preg_match("/linkto:/",$thispage->get_prop('content'))){
			$content = $thispage->get_prop('content');
			$content = str_replace("linkto:","",$content);
			$nav .= "<a href=\"" . $content . "\" target=\"new\" ";
		    }else{
			//echo "proceed as normal";
                          $nav .= "<a href=\"<%link_p_$lname%>\"";
                    }
                    //if theres a button with the page
                    if($button = $thispage->get_prop('button')){
                           //if its a rollover
                           $rollover = $thispage->get_prop('rollover');
                           if($rollover == 1){
                                   $nav .= ($linkid == $id || $lcpagename == $lclname)? "" : " onMouseOver=r.imageChange($i,'on') onMouseOut=r.imageChange($i,'off')";
                           }
                           $nav .= ">";
                           $name = strtolower(str_replace(" ","_",$lname));
                           //use on state if current page
                           if(($linkid == $id && $rollover == 1) || ($lcpagename == $lclname && $rollover == 1)){
				if(!$button = $thispage->get_prop('button_active')){
					$button = $thispage->get_prop('button_on');
				    }
                           }
                           $nav .= "<img src=\"images/$button\" border=\"0\" name = \"$name\">";
                    }else{
                           $nav .= ">$lname";
                    }
                    $nav .= "</a>";
                    //$nav .= "<br>\n";
		return $nav;
	}

	function nav(){
                $parray = $this->_get_page_array();
		$id = $this->get_vars[id] ? $this->get_vars[id] : $this->post_vars[id];
		
		for($i=0;$i<count($parray);$i++){
                	$nav .= $this->navlet($i);  
		}
		return $nav;
	}
	function nav_js(){
		$p = new pages;
                $parray = $this->_get_page_array();
		
		$js .= "<script language=\"javascript\" src=\"http://www.codeandcontent.com/javascript/rollover.js\"></script>
		<script language=\"javascript\">\n
		//image rollovers object
                baseurl = \"images/\";
		labels = [";

		for($i=0;$i<count($parray);$i++){
			$label = strtolower($parray[$i]->get_prop('name'));
			$label = str_replace(" ","_",$label);
			$js .= "'$label',";	
		}

		$js = preg_replace("/,$/","",$js);
		$js .= "];\n";

		$js .= "\n
                r = new rollovers(baseurl,labels);
		</script>
		";
		return $js;
	}
	function _get_page_array(){
		$p = new pages;
                $parray = $p->get_all_elements(array('post' => '1','nav' => '1','order_by'=>'seq'));
		return $parray;
	}
	function get_bp_obj(){
		$bp = $this->get_vars[bp]? $this->get_vars[bp] : $this->post_vars[bp];
		  	$str = "\$bpobj = new $bp;";
                        eval($str);
                        return $bpobj;
	}
	function display_by_cat($cat){
		return "override me!\n<br>";
	}
	
	function slideshow($e){
                $get_vars = $this->get_vars;
		$href = new href('');
		$base = $href->get_coded_string_from_array($get_vars);

                if(preg_match("/image/",$base)){
                        $base = preg_replace("/_image_./","",$base);
                }

                if(!$image = $this->get_vars['image']){
                        $image = '0';
                }
                $images = array();

                while(list($key,$value) = each($e->props)){
                        if(preg_match("/^image/",$key) && $value){
                                $images[] = $value;
                        }
                }
                if(!count($images)){
                        return false;
                }
                $numimage = count($images) -1;
                $nextimage = ($image < $numimage)? $image +1 : 0;
                $previmage = ($image) ? $image - 1 : $numimage;
                if(!$previmage){$previmage='0';}
                $code .= "";
		if(strstr($base,"?")){
			$mark = "&";
		}else{
			$mark = "?";
		}

		$code .= "<center>";
		if($numimage){
                	$code .= "<a href=\"<%link_$base" . "_image_$previmage%>\">&lt;&lt;</a>";
		}
	  	$curr = $image +1;
                $total = $numimage +1;

		$code .= " image $curr of $total ";
        	if($numimage){
	        	$code .= "<a href=\"<%link_$base" . "_image_$nextimage%>\">&gt;&gt;</a><br>";
        	}
		if(!$images[$image]){
			$images[$image] = 'blank.gif';
		}
		$code .= "\n\n<img src=\"images/" . $images[$image] . "\" border=\"1\" name=\"slideshow\"><br>";
 		
	       	$code .= "</center>";
		if($this->dynstatic=='dynamic'){
			$code = $this->replace_links($code);
		}
                return $code;
        }
	
	function bp_slideshow($params=''){
                $base = $this->request_uri;
                if(preg_match("/image/",$base)){
                        $base = preg_replace("/&image=./","",$base);
                }
		$base = preg_replace("/^(.*)\?/","",$base);
                if(!$image = $this->get_vars['image']){
                        $image = '0';
                }
                $images = array();
		if(class_exists('images')){ 
			$bp = new images;
		}else{
			return 'override bp_slideshow & tell it what blueprint to use, or create an images blueprint';
		}

		
		$array = $bp->get_all_elements($params);
		for($i=0;$i<count($array);$i++){
			$e = $array[$i];
			//this will add all props with the name image in the key
			while(list($key,$value) = each($e->props)){
				if(strstr($key,'image')){
					$images[] = $value;
				}
			}
		}
                if(!count($images)){
                        return false;
                }
                $numimage = count($images) -1;
                $nextimage = ($image < $numimage)? $image +1 : 0;
                $previmage = ($image) ? $image - 1 : $numimage;
                if(!$previmage){$previmage='0';}
		$code .= "<center>";
		$base = str_replace("?","_",$base);
		$base = str_replace("=","_",$base);
		$base = str_replace("&","_",$base);
		if($numimage){
                        $code .= "<a href=\"<%link_$base" . "_image_$previmage%>\">&lt;&lt;</a>";
                }
                $curr = $image +1;
                $total = $numimage +1;

                $code .= " image $curr of $total ";
                if($numimage){
                        $code .= "<a href=\"<%link_$base" . "_image_$nextimage%>\">&gt;&gt;</a><br>";
                }
                if(!$images[$image]){
                        $images[$image] = 'blank.gif';
                }
                $code .= "\n\n<img src=\"images/" . $images[$image] . "\" name=\"slideshow\"><br>";

                $code .= "</center>";
                if($this->dynstatic=='dynamic'){
                       $code = $this->replace_links($code);
                }


                $code .= "</center>";
                return $code;
	}
	
	function bp_slideshow_byid($params=''){
                if(!$id = $this->get_vars['id']){
                        $id = '1';
                }
                $images = array();
                if(class_exists('images')){
                        $bp = new images;
                }else{
                        return 'override bp_slideshow_byid & tell it what blueprint to use, or create an images blueprint';
                }


                $array = $bp->get_all_elements($params);
		for($i=0;$i<count($array);$i++){
			if($array[$i]->get_prop('id')==$id){
				$idindex = $i;
				$image = $array[$i]->get_prop('image');
			}
		}                

		if(!count($array)){
                        return false;
                }
                $numimage = count($array) -1;
                $nextindex = ($idindex >= $numimage)? 0 : $idindex + 1;
		$previndex = $idindex? ($idindex-1) : ($numimage-1);
		$nextid = $array[$nextindex]->get_prop('id');
		$previd = $array[$previndex]->get_prop('id');

		if(!$previmage){$previmage='0';}
                $code .= "<center>";
                if($numimage){
                        $code .= "<b><a href=\"<%link_" . "id_$previd%>\">&lt;&lt;</a>";
                }
                $curr = $idindex +1;
                $total = count($array);

                $code .= " image $curr of $total ";
                if($numimage){
                        $code .= "<a href=\"<%link_" . "id_$nextid%>\">&gt;&gt;</a></b><br><br>";
                }
                $code .= "\n\n<img src=\"images/" . $image . "\" name=\"slideshow\" border=\"2\"><br>";

                $code .= "</center>";
                if($this->dynstatic=='dynamic'){
                       $code = $this->replace_links($code);
                }

                $code .= "</center>";
                return $code;
        }

	function frontend_final_stuff($code){
		//override this for any final string replacing or whatever
		//this is the LAST STOP before the code is output
		$code = str_replace('<%copyrightyear%>','2025',$code);

		return $code;
	}

	function byid($cat="",$id){
                return "Override Me";
        }

	function menu_nav(){
		                $nav = "
<script language=\"JavaScript1.2\" src=\"http://www.monk.com/js/menus.js\"></script>
<script language=\"javascript\">
var hBar = new ItemStyle(40, 10, '>', -15, 2, '#ffffff','#cccccc', 'boldText', 'boldText'
, 'itemBorder', 'itemBorder',
 90, 90, 'hand', 'default');

var subM = new ItemStyle(22, 0, '>', -15, 3, '#ffffff', '#cccccc', 'lowText', 'lowText',
 'itemBorder', 'itemBorder', 90, 90, 'hand', 'default');

var subBlank = new ItemStyle(22, 1, '>', -15, 3, '#CCCCDD', '#6699CC', 'lowText', 'highText',
 'itemBorderBlank', 'itemBorder', null, null, 'hand', 'default');

var button = new ItemStyle(22, 1, '>', -15, 2, '#006633', '#CC6600', 'buttonText', 'buttonHover'
,
 'buttonBorder', 'buttonBorderOver', 80, 95, 'crosshair', 'default');

var pMenu = new PopupMenu('pMenu');
with (pMenu)
{
";

   $p = new pages;
        $parray = $p->get_all_elements(array('order_by' => 'seq','post' => '1','nav' => '1'));
        $parentarray = array();
        for($i=0;$i<count($parray);$i++){
                $page = $parray[$i];
                if($parent = $page->get_prop('parent')){
                        $parentarray[$parent][] = $page->get_prop('name');
                }
        }


$nav .= "

startMenu('root', true, 10, 110, 150, hBar);

";
//build the main menu
        for($i=0;$i<count($parray);$i++){
                $page = $parray[$i];
                $name = $page->get_prop('name');
                $mname = strtolower($name);
                $mname = str_replace(" ","_",$mname);
                if(!$page->get_prop('parent')){
                             $imagename = str_replace(' ','_',$name);
                                $imagename = strtolower($imagename);
                                $imagesrc = strtolower($imagename) . ".gif";

                        if(array_key_exists($name,$parentarray)){
                                //its a page w/ submenusa
                                $scode = 'sm:';
                                $nav .= "addItem('  <b>$name</b>','$mname','$scode',null,20);\n";

                        }else{
                                $scode = '';

                                $nav .= "addItem('  <b>$name</b>','<%link_p_$name%>','$scode',null,20);\n";
                        }
                }
        }
 	//now build the submenus
        while(list($key,$value) = each($parentarray)){
                $array = $parentarray[$key];
                $mname = strtolower($key);
                $mname = str_replace(" ","_",$mname);

                 $nav .= "startMenu('$mname', true, 154, 0, 150, subM);\n";

                for($i=0;$i<count($array);$i++){
                        $name = $array[$i];

                        $pagelet = new element($p);
 			$pagelet->populate_from_profile(array('name' => $name));

                        $content = $pagelet->get_prop('content');

                        if(preg_match("/linkto:/",$pagelet->get_prop('content'))){
                                $content = $pagelet->get_prop('content');
                                $content = str_replace("linkto:","",$content);
                                $link = 'window.open("' . $content . '","new","width=700,height=400,scrollbars=yes,menubar=yes,toolbar=yes,location=yes")';
                                $type = 'js:';
                        }else{
                                $mname = strtolower($name);
                                $mname = str_replace(' ','_',$mname);
                                $link = (array_key_exists($name,$parentarray))? "$mname" : '<%link_p_' . $name . '%>';
                                $type =  (array_key_exists($name,$parentarray))? 'sm:' : '';
                        }
                        $nav .= "addItem('$name', '$link', '$type');\n";
                }
        }

$nav .= "
}
addMenuBorder(pMenu, window.subBlank,null, '#666666', 1, '#CCCCDD', 2);

addDropShadow(pMenu, window.subM,[40,'#333333',6,6,-4,-4], [40,'#666666',4,4,0,0]);
addDropShadow(pMenu, window.subBlank,[40,'#333333',6,6,-4,-4], [40,'#666666',4,4,0,0]);

if (!isOp && navigator.userAgent.indexOf('rv:0.')==-1)
{
 pMenu.showMenu = new Function('mN','menuAnim(this, mN, 10)');
 pMenu.hideMenu = new Function('mN','menuAnim(this, mN, -10)');

}

if (!isNS4)
{
 pMenu.update(true);
}
else
{
 var popOldOL = window.onload;
 window.onload = function()
 {
  if (popOldOL) popOldOL();
  pMenu.update();
 }
}

var nsWinW = window.innerWidth, nsWinH = window.innerHeight, popOldOR = window.onresize;
window.onresize = function()
{
 if (popOldOR) popOldOR();
 if (isNS4 && (nsWinW!=innerWidth || nsWinH!=innerHeight)) history.go(0);
 pMenu.position();
}

window.onscroll = function()
{
 pMenu.position();
}

if (isNS4)
{
 document.captureEvents(Event.CLICK);
 document.onclick = function(evt)
 {
  with (pMenu) if (overI) click(overM, overI);
  return document.routeEvent(evt);
 }
}

if (!isIE || isOp)
{
 var nsPX=pageXOffset, nsPY=pageYOffset;
 setInterval('if (nsPX!=pageXOffset || nsPY!=pageYOffset) ' +
 '{ nsPX=pageXOffset; nsPY=pageYOffset; window.onscroll() }', 50);
}

</script>
";

                return $nav;
        }
}
?>
