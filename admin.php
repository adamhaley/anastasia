<?
class admin {
	var $bps = array();
	var $html;
	var $bp;
	var $post_vars;
	var $get_vars;	
	var $post_files;	
	var $display;
	var $session_vars;

	function admin($props=array()){
		//Pass this class the main blueprint and any child  blueprints you would like it to be the admin of
		$this->bps = $props[bps]; 
		$this->local = $props[local];
		$this->bp = new blueprint(array("local" => $this->local));
		$this->html = $props[html];
		$this->post_vars = $props[post_vars];
		$this->get_vars = $props[get_vars];
		$this->post_files = $props[post_files];
		$this->session_vars = $props[session_vars];

		if($props[display]){
			$this->display = $props[display];
		}else{
			$this->display = new display;
		}
		$this->html->display = $this->display;
	}
	
	function set_mask($m){
		$this->mask = $m;
	}

	function get_mask(){
		return $this->mask;
	}

	function start() {
	 	if($this->post_vars[what]){
                        $what = $this->post_vars[what];
                }else{
                        $what = $this->get_vars[what];
                }
		
		$twhat = str_replace("_"," ",$what);
		$twhat = ucwords($twhat);
		$website = $this->local->props[website];
		$title = ucfirst($website) . " Control Panel :: Manage $twhat";
		$stwhat = preg_replace("/s$/","",$twhat);

		if($this->get_vars[action] == 'edit'){
			if($id = $this->get_vars[id]){
				$title .= " :: Edit $stwhat $id";
			}else{
				$title .= " :: New $stwhat";
			}
		}		

		if($this->get_vars[p]){
                        $p = $this->get_vars[p];
                }else if($this->post_vars[p]){
                        $p = $this->post_vars[p];
                }else if($what != $this->session_vars[what]){
                        $p = 1;
                }else if($this->session_vars[p]){
                        $p = $this->session_vars[p];
                }

		if($what != $this->session_vars[what]){
                        $sortby = 'id';
                }else if($this->get_vars[sortby]){
                        $sortby = $this->get_vars[sortby];
                }else if($this->post_vars[sortby]){
                        $sortby = $this->post_vars[sortby];
                }
		
                $p = $p? $p : 1;

		$h = $this->html;
		$d = $this->display;
		
		$swhat = preg_replace("/s$/","",$what);
		
		$start = "<html>
                <head>
                <title>$title</title>" .
		$this->get_head() .
                "</head>
                <body  bgcolor=\"" . $d->bgcolor . "\" text=\"" . $d->fontcolor . "\" alink=\"" . $d->alink . "\" vlink=\"" . $d->vlink . "\" link=\"" . $d->link . "\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">";

		$start .= "<center><b>$title</b></center>\n<hr>";
		
		return header("Pragma: no-cache") . $start;
	}
	function get_head(){
		$l = new local;
		$out .= $this->get_javascript();
		$out .= "<link rel='stylesheet' type='text/css' href='" . $l->props['url'] . "/style/main.css' />";
		return $out;
	}

	function get_title(){
	   	if($this->post_vars[what]){
                        $what = $this->post_vars[what];
                }else{
                        $what = $this->get_vars[what];
                }
		$twhat = str_replace("_"," ",$what);
                $twhat = ucwords($twhat);
                $website = $this->local->props[website];
                $title = ucfirst($website) . " Control Panel :: Manage $twhat";
                $stwhat = preg_replace("/s$/","",$twhat);

                if($this->get_vars[action] == 'edit'){
                        if($id = $this->get_vars[id]){
                                $title .= " :: Edit $stwhat $id";
                        }else{
                                $title .= " :: New $stwhat";
                        }
                }	
		return $title;
	}
	function get_javascript(){
		 if($this->post_vars[what]){
                        $what = $this->post_vars[what];
                }else{
                        $what = $this->get_vars[what];
                }


	  	if($this->get_vars[p]){
                        $p = $this->get_vars[p];
                }else if($this->post_vars[p]){
                        $p = $this->post_vars[p];
                }else if($what != $this->session_vars[what]){
                        $p = 1;
                }else if($this->session_vars[p]){
                        $p = $this->session_vars[p];
                }

                if($this->get_vars[sortby]){
                        $sortby = $this->get_vars[sortby];
                }else if($this->post_vars[sortby]){
                        $sortby = $this->post_vars[sortby];
                }else if($what != $this->session_vars[what]){
                        $sortby = '';
                }

                $p = $p? $p : 1;

                $h = $this->html;
                $d = $this->display;

                $swhat = preg_replace("/s$/","",$what);

                //Passing javascript to start method for insertion in <head>
                $js =  "<script language = \"javascript\">
                function deleteConfirm(id){
                        var url = \"" . $h->sfile . "?action=delete&what=$what&id=\" + id;

                        if(confirm(\"Are you sure you want to delete this " . $swhat . " from the database?\")){
                                window.location=url;
                        }
                }
                function dumpConfirm(id){
                        var url = \"" . $h->sfile . "?action=dump&what=dbdumps&id=\" + id;
                        if(confirm(\"You are about to dump all the contents of this Export File into the Talent Table. Are you sure you want to continue?\")){
                                window.location=url;
                        }
                }
		function sortBy(){
                        url = 'index.php?action=manage&what=$what&p=$p&sortby=';
                        formfield = eval('document.forms.sort.elements[0]');

                        i = formfield.selectedIndex;
                        url = url + formfield.options[i].value;
                        location = url;
                }
                function viewBy(field,ddnum){
                        url = 'index.php?action=manage&what=$what&p=$p&viewby=';
                        formfield = eval('document.forms.sort.elements[' + ddnum + ']');

                        i = formfield.selectedIndex;
                        url = url + formfield.options[i].value;
                        url += '&field=' + field;

                        location = url;
                }
		function goLoc(){

                        url = \"" . $h->sfile . "?action=manage&what=$what&p=$p&sortby=\";
                        i = document.forms.dd.sortby.selectedIndex;
                        url = url + document.forms.dd.sortby.options[i].value;
                        location = url;
                }

		function publishConfirm(){
			if(confirm('Are you ready to make your changes Live?')){
				location='?action=publish';
			}
		}
                 //set cookie, storing current location
                //var urlString = document.location;
                //document.cookie = \"lastUrl=\" + urlString;
                </script>
		";

		return $js;
	}
	function nav() {
		
		$bps = $this->bps;
		$bp = $this->bp;
		$local = $this->local;
		$h = $this->html;
		$d = $this->display;

		$nav .= "<center>";
		if($bps){
			while(list($key,$value) = each($bps)){
				$nav .= $this->_navlet($value->props[table]);
				
			}
		}else{
			$nav .= "No Blueprint Objects!\n";
		}

		  $nav .= "<br><br><a href=\"" . $this->local->props[url] . $this->local->props['stagingpath'] . "\" target = _new class=\"adminnav\">Preview Changes</a>  |  <a href=\"javascript:publishConfirm()\" class=\"adminnav\">Publish Changes</a>\n";
		$nav .= "</center><br><br>";
		return $nav;
	}

	function _navlet($what){
		$bp = $this->bps[$what];
		$h = $this->html;
	
                $title = ucfirst($what);
                $nav .= "<a href=\"" . $h->sfile . "?action=manage&what=$what\" class=\"adminnav\">$title</a>";
		if($bp->props[archive] == 1){
			$nav .= "<a href=\"" . $h->sfile . "?action=manage&what=$what&archive=1\" class=\"adminnav\">Archived $title</a>";
		}

		return $nav;
	}
	
	function end() {

		$h = $this->html;
		return "</font>" . $h->end();
	}

	function body() {
		//This method takes the http vars and decides what to do. 
		//It acts sort of as a gateway
		//get the action
		 $lasturl = $this->cookie_vars[lastUrl];
	
		$get_vars = $this->get_vars;
		$post_vars = $this->post_vars;
		$post_files = $this->post_files;
	
		//Get action
		if($get_vars[action]){
			$action = $get_vars[action];
		}else if($post_vars[action]){
			$action = $post_vars[action];
		}
		//get what
		if($get_vars[what]){
			$what = $get_vars[what];
		}else if($post_vars[what]){
			$what = $post_vars[what];
		}
		//get id
		if($get_vars[id]){
			$id = $get_vars[id];
		}else if($post_vars[id]){
			$id = $post_vars[id];
		}
		
		//get bp
		if($this->bps[$what]){
			$bp = $this->bps[$what];
		}else{
			$bp = $this->bp;
		}	
		

		if($action == 'manage'){
			return $this->manage($what);
		}else if($action == 'edit'){
			return $this->edit_element($what,$id);
		}else if($action == 'delete'){
			$e = new element($bp,$id);
			$code = $e->delete();
			$code  .= $this->manage($what);
			return $code;
		}else if($action == 'view'){
			return $this->view_element($what,$id);
		}else if($action == 'duplicate'){
			return $this->duplicate_element($what,$id);
		}else if($action == 'archive'){
			return $this->archive_element($what,$id);
		}else if($action == 'init'){
			return $this->generate_db();
		}else if($action == 'publish'){
			return $this->publish_site();
		}else if($action=='search'){
			return $this->search($what);
		}
	}

	function edit_element($what,$id=''){
		$h = $this->html;
		$post_vars = $this->post_vars;		
		$post_files = $this->post_files;		

		$r = '<form method="post" action="" enctype ="multipart/form-data">

<input type="hidden" name="submitted" value="1">
<input type="hidden" name="action" value="edit">
<input type="hidden" name="what" value="' . $what . '">
<input type="hidden" name="id" value="' . $id . '">';

		if($this->bps[$what]){
			$bp = $this->bps[$what];
		}else{
			return "sorry, I need a blueprint object to do that \n<br>";
		}
		$d = $this->display;

		$e = new element($bp,$id);
		if($post_vars[submitted]){
			$e->set_all($post_vars,$post_files);
			$r .= $e->save();
			
			$h->set_element($e);
			$r .= $this->manage($what);
			
			return $r;
		}else{
			$h->set_element($e);
			$r .= $h->generate_form();
		}
		$r .= '<br /><input type="submit" name="submitted" value="Save">&nbsp;</form>';
		return $r;
	}
	function manage($what){

                $h = $this->html;
                $d = $this->display;
                $action = $this->get_vars[action];
                if($this->bps[$what]){
                        $bp = $this->bps[$what];
                }

                if($this->get_vars[p]){
                        $page = $this->get_vars[p];
                }else if($this->post_vars[p]){
                        $page = $this->post_vars[p];
                }else if($what != $this->session_vars[what]){
                        $page = 1;
                }else if($this->session_vars[p]){
                        $page = $this->session_vars[p];
                }

                if($this->get_vars[sortby]){
                        $sortby = $this->get_vars[sortby];
                }else if($this->post_vars[sortby]){
                        $sortby = $this->post_vars[sortby];
		}else if($this->session_vars[sortby]){
			$sortby = $this->session_vars[sortby];
                }
		
                if($what != $this->session_vars['what']){
                        $sortby = '';
			
                }
                if($this->get_vars[archive]){
                        $archive = $this->get_vars[archive];
                }else if($this->post_vars[archive]){
                        $archive = $this->post_vars[archive];
                }
                $params[order_by] = $sortby;

                $total = $bp->count_elements($params);

                $page = $page ? $page : 1;

                $p = new pager($total,20,$page);

                $elements = $p->get_elements($bp,$params);
                $mask = $bp->props[manager_mask];

                $nav = $p->nav($h->sfile . "?action=manage&what=$what&sortby=$sortby");

                $manager = "<table align = \"center\" border=\"" . $d->tableborder . "\" width=\"90%\"><tr>";
                $fields = $mask->filter_keys($bp->props[fields]);

                $orgnumcols = $bp->props[archive]? 5 : 4;
                $numcols = $orgnumcols;
                while(list($key,$value) = each($fields)){
                        $numcols++;
                        $ukey = preg_replace("/_/"," ",$key);
                        $ukey = ucwords($ukey);

                        $clr = ($key == $sortby)? "bgcolor" : "";
                        $manager .= "<td bgcolor=\"" . $d->darkcolor . "\" class=\"adminmain\"><b><a href=\"index.php?action=manage&what=$what&p=$page&sortby=$key\"><font color=\"black\">$ukey</font></a></b></td>\n";
                }
		
                reset($fields);
                for($i=0;$i<$orgnumcols;$i++){
                        $manager .= "<td bgcolor=\"" . $d->darkcolor . "\">&nbsp;</td>\n";
                }
                //Add Button
                $stitle = preg_replace("/s$/","",$what);
                $stitle = ucwords($stitle);

                $manager .= "\t<tr>\n\t\t<td colspan=\"$numcols\" bgcolor=\"" . $d->darkcolor . "\"><b>" . $d->startfont() . "<a href=\"" . $h->sfile . "?action=edit&what=$what\">Add</a></font></td>\n\t</tr>";

                //Pager(instansiated above)
                $manager .= $nav ? "\t<tr>\n\t\t<td colspan=\"$numcols\" bgcolor=\"" . $d->darkcolor . "\" class=\"adminmain\">Pages:&nbsp;" .  $nav . "</td>\n\t</tr>" : "";
                for($i = 0; $i<count($elements);$i++){
                        $switch = $switch ? 0 : 1;
                        $bgcolor = $switch ? $d->lightcolor : "";

                        $e = $elements[$i];
                        $fields = $mask->filter_keys($e->props);
                        $manager .= "<tr>";

                        while(list($key,$value) = each($fields)){
                                $value = stripslashes($value);
                                $manager .= "<td bgcolor=\"$bgcolor\" class=\"adminmain\">&nbsp;$value</td>";
                        }
                        $manager .= "<td bgcolor=\"$bgcolor\" class=\"adminmain\">";
                        if($bp->props[preview_page]){
                                $manager .=  "<a href=\"" . $bp->props[url] . $bp->props[preview_page] . "?id=" . $e->id . "\" target=\"_new\"\">View</a>";
                        }else{
                                $manager .= "<a href=\"" . $h->sfile . "?action=view&what=$what&id=" . $e->id . "\">View</a></font>";
                        }
                        $manager .= "</td>
<td bgcolor=\"$bgcolor\" class=\"adminmain\"><a href=\"" . $h->sfile . "?action=edit&what=$what&id=" . $e->id . "\">Modify</a></td><td bgcolor=\"$bgcolor\" class=\"adminmain\"><a href=\"?action=duplicate&what=$what&id=" . $e->id . "\">Duplicate</a></td>
";
                        if($bp->props[archive]){
                                $manager .= "<td bgcolor=\"$bgcolor\" class=\"adminmain\"><a href=\"" . $h->sfile . "?action=archive&id=" . $e->id . "\">Archive</a></td>";
                        }
                        $manager .= "<td bgcolor=\"$bgcolor\" class=\"adminmain\"><a href=\"javascript:deleteConfirm(" . $e->id . ")\">Delete</a></td>\n";
                        $manager .= "</tr>";
                }
                 $manager .= $nav ? "\t<tr>\n\t\t<td colspan=\"$numcols\" bgcolor=\"" . $d->darkcolor . "\" class=\"adminmain\">Pages:&nbsp;$nav</td>\n\t</tr>" : "";
                $manager .= "</table>";
                return $manager;
        }
	
	function search($what){
		$uwhat = str_replace('_',' ' ,$what);
                $uwhat = ucwords($uwhat);

		$content .= "<b>Search $uwhat Table:</b><br><br>
		<form name=\"search\" method=\"post\" action=\"" . $this->sfile . "\">";
		$bp = $this->bps[$what];
		while(list($key,$value) = each($bp->props['fields'])){
			$kw = ($kobj=$bp->keywords[$key])? 1 : 0;
			$ukey = //str_replace('_',' ' ,$key);
			$ukey = ucwords($key);
			$type = $value['type'];
			
			if($kw){
				$content .= "$ukey:<br>" . $kobj->generate_dropdown() . "<br><br>";
			}else if($type=='date'){
                                $typeobj  = new date($key,'',$bp,'');
				//$content .= $typeobj->form_field() . "<br>";
				$content .= "$ukey:<br>";
				//$content .= $typeobj->form_field();
				
			}
		}
		$content .= "<input type=\"submit\"> <input type=\"reset\">
				</form>";
		return  $content;
	}
/*
	function manage($what){
		
		$h = $this->html;
		$d = $this->display;
		$action = $this->get_vars[action];
		if($this->bps[$what]){
			$bp = $this->bps[$what];
		}
		if(is_numeric($this->get_vars[p])){
			if($this->get_vars[p]){
                        	$page = $this->get_vars[p];
                	}else if($this->post_vars[p]){
                        	$page = $this->post_vars[p];
                	}else if($what != $this->session_vars[what]){
                        	$page = 1;
                	}else if($this->session_vars[p]){
                        	$page = $this->session_vars[p];
                	}
		}else{
			$page = 1;
		}

                if($this->get_vars[sortby]){
                        $sortby = $this->get_vars[sortby];
                }else if($this->post_vars[sortby]){
                        $sortby = $this->post_vars[sortby];
                }else if($what != $this->session_vars[what]){
                        $sortby = '';
		}
		if($this->get_vars[archive]){
                        $archive = $this->get_vars[archive];
                }else if($this->post_vars[archive]){
                        $archive = $this->post_vars[archive];
                }
		while(list($k,$v) = each($this->get_vars)){
			if($k != 'PHPSESSID' && $k != 'action' && $k != 'p' && $k != 'what' && $k != 'sortby'){
				$params[$k] = $v;
			}
		}
		$params[order_by] = $sortby;

		$total = $bp->count_elements($params);
		//echo "total is $total \n<br>";		


		$page = $page ? $page : 1;		
		$p = new pager($total,20,$page);
		
		$elements = $p->get_elements($bp,$params);
		$mask = $bp->props[manager_mask];	
		

		$nav = $p->nav($h->sfile . "?action=manage&what=$what&sortby=$sortby");
		
		$manager = "<table align = \"center\" border=\"" . $d->tableborder . "\" width=\"90%\"><tr>";	
                $fields = $mask->filter_keys($bp->props[fields]);
		
		$orgnumcols = $bp->props[archive]? 5 : 4;
		$numcols = $orgnumcols;
		while(list($key,$value) = each($fields)){
			$numcols++;	
			$ukey = preg_replace("/_/"," ",$key);
			$ukey = ucwords($ukey);

			$clr = ($key == $sortby)? "bgcolor" : "";	
                	$manager .= "<td bgcolor=\"" . $d->darkcolor . "\" class=\"adminmain\"><b>$ukey</font></b></td>\n";
                }
		reset($fields);
		for($i=0;$i<$orgnumcols;$i++){
               		$manager .= "<td bgcolor=\"" . $d->darkcolor . "\">&nbsp;</td>\n";
		}
		//Add Button
                $stitle = preg_replace("/s$/","",$what);
		$stitle = ucwords($stitle);

                if(preg_match("/^[AEIO]/",$stitle)){
                        $ana = "an";
                }else{
                        $ana = "a";
                }
		$manager .= "\t<tr>\n\t\t<td colspan=\"$numcols\" bgcolor=\"" . $d->darkcolor . "\"><b>" . $d->startfont() . "<a href=\"" . $h->sfile . "?action=edit&what=$what\">Add</a></font></td>\n\t</tr>";
		if($what == 'users'){
			$manager .= "<a href=\"?action=dumpemails\">Dump Emails</a>";
		}
		//sortby drop down
		$manager .= "\n\t<tr>\n\t\t<td colspan=\"$numcols\" bgcolor=\"" . $d->darkcolor . "\"><form name=\"dd\"><select name=\"sortby\" onChange=\"goLoc()\"><option value = \"\">Sort By:";
		reset($fields);
		while(list($key,$value) = each($fields)){
			$manager .= "<option value=\"" . $key . "\"";
			if($key == $sortby){
				$manager .= " selected ";
			}
			$manager .= ">" . ucwords(preg_replace("/_/"," ",$key)) . "\n";
		}
		$manager .= "</select>\n</form>\n\t\t</td>\n\t</tr>";

		//Pager(instansiated above)
		$manager .= $nav ? "\t<tr>\n\t\t<td colspan=\"$numcols\" bgcolor=\"" . $d->darkcolor . "\" class=\"adminmain\">Pages:&nbsp;" .  $nav . "</td>\n\t</tr>" : "";
		for($i = 0; $i<count($elements);$i++){
			$switch = $switch ? 0 : 1;
			$bgcolor = $switch ? $d->lightcolor : "";

			$e = $elements[$i];
			$fields = $mask->filter_keys($e->props);
			$manager .= "<tr>";
				
			while(list($key,$value) = each($fields)){
				$value = stripslashes($value);
				$manager .= "<td bgcolor=\"$bgcolor\" class=\"adminmain\">&nbsp;$value</td>";
			}
			$manager .= "<td bgcolor=\"$bgcolor\" class=\"adminmain\">";
			if($bp->props[preview_page]){
				$manager .=  "<a href=\"" . $bp->props[url] . $bp->props[preview_page] . "?id=" . $e->id . "\" target=\"_new\"\">View</a>";
			}else{
				$manager .= "<a href=\"" . $h->sfile . "?action=view&what=$what&id=" . $e->id . "\">View</a></font>";
			}
			$manager .= "</td>
<td bgcolor=\"$bgcolor\" class=\"adminmain\"><a href=\"" . $h->sfile . "?action=edit&what=$what&id=" . $e->id . "\">Modify</a></td><td bgcolor=\"$bgcolor\" class=\"adminmain\"><a href=\"?action=duplicate&what=$what&id=" . $e->id . "\">Duplicate</a></td>
";
			if($bp->props[archive]){
				$manager .= "<td bgcolor=\"$bgcolor\" class=\"adminmain\"><a href=\"" . $h->sfile . "?action=archive&id=" . $e->id . "\">Archive</a></td>";
			}
			$manager .= "<td bgcolor=\"$bgcolor\" class=\"adminmain\"><a href=\"javascript:deleteConfirm(" . $e->id . ")\">Delete</a></td>\n";
			$manager .= "</tr>";
		}
		 $manager .= $nav ? "\t<tr>\n\t\t<td colspan=\"$numcols\" bgcolor=\"" . $d->darkcolor . "\" class=\"adminmain\">Pages:&nbsp;$nav</td>\n\t</tr>" : "";
		$manager .= "</table>";
		return $manager;
	}
*/
	function view_element($what,$id){
	 	if($this->bps[$what]){
                        $bp = $this->bps[$what];
                }else{
                        return "sorry, I need a blueprint object to do that \n<b
r>";
                }
		
		$e = new element($bp,$id);
		return $e->view();
	}

	function duplicate_element($what,$id){
		$bp = $this->bps[$what];
		$e = new element($bp,$id);
		$newe = new element($bp);
		reset($e->props);
		while(list($key,$value) = each($e->props)){
			$newe->set_prop($key,$value,array(),$bp->keywords);
		}	
		$swhat = preg_replace("/s$/","",$what);	
		 $newe->save();
		return "$swhat $id duplicated successfully<br>" .  $this->manage($what);
	}

	function archive_element($what,$id){
		$bp = $this->bps[$what];
		$e = new element($bp,$id);
		$e->set_prop('archive',1);
		return $e->save() . "<br>" . $this->manage($what);
	}

	function generate_db(){
		//echo "hello";
		$db = new db;
		echo get_class( $db);
		die;
		while(list($key,$value) = each($this->bps)){
			echo "$key : $value \n<br>";
			$l = $this->local;
			$db = $l->props['db'];
			echo "db is $db ";
			$db->generate_from_blueprint($value);
		}
	}
	function publish_site(){
		$l = new local;
		$f= $l->props[frontend] ? $l->props[frontend] : new frontend('','',array(),array());
		$f->dynstatic = 'static';
		$p = new publisher($f);
		return $p->publish();
	}
	function wrap_in_template($admin,$tid){
		$tobj = new template($tid);
		$source = $tobj->get_prop('html_source');
		$javascript = $this->get_javascript();
		$source = str_replace("<%javascript%>",$javascript,$source);
		$title = $this->get_title();
		$source = str_replace("<%nav%>",$this->nav(),$source);
		$source = str_replace("<%title%>",$title,$source);
		$source = str_replace("<%admin%>",$admin,$source);

		return $source;
	}

	function login(){
                if($this->session_vars['logged_in'] == 1){
                        return;
                }else{
                        if($this->post_vars['submit']){
                                $username = $this->post_vars['username'];
                                $password = $this->post_vars['password'];
                                $l = new local;
                                if($username==$l->props['username']&&$password==$l->props['password']){
                                        $this->session_vars['logged_in'] = 1;
                                        header("Location:" . $this->html->sfile);
                                }else{
                                        $code = 'Error:The username/password you submitted was incorrect. Perhaps you made a typo. Go <a href="javascript:history.go(-1)">Back</a> to try again.';
                                        die($code);
                                }
                        }else{
                                           $form = '<form method="post" action = "' . $this->html->sfile . '">
                                        <input type="hidden" name="submit" value="1">
                                        Username:<input type="text" name="username"><br>
                                        Password:<input type="password" name="password"><br>
                                        <input type="submit" value="Submit">';

                                        return $form;
                        }
                }

        }


}
?>
