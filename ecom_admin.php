<?
class ecom_admin extends admin {
        function ecom_admin($props){

                $this->admin($props);
        }

	function archive($id){
                $bp = $this->bps[orders];
                $e = new element($bp,$id);
                $e->set_prop("archive",1);
                $e->save();

                return "Order $id has been archived.";
        }
	
	function body() {
                //Overriding body to add functionality for archive
                //This method takes the http vars and decides what to do.
                //It acts sort of as a gateway
                //get the action

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
                //get archive
                if($get_vars[archive]){
                        $archive = $get_vars[archive];
                }else{
                        $archive = $post_vars[archive];
                }

                //get bp
                if($this->bps[$what]){
                        $bp = $this->bps[$what];
                }else{
                        $bp = $this->bp;
                }
                if($action == 'manage'){
                        return $this->manage($what,$archive);
                }else if($action == 'edit'){
                        return $this->edit_element($what,$id);
                }else if($action == 'delete'){
                        $e = new element($bp,$id);
                        return $e->delete();
                }else if($action == 'view'){
                        return $this->view_element($what,$id);
                }else if($action == 'archive'){
                        return $this->archive($id);
                }else if($action == 'announce'){
                        return $this->announce($id,$post_vars);
                }
        }

	function manage($what) {

                $h = $this->html;
                $d = $this->display;
                $action = $this->get_vars[action];
                if($this->bps[$what]){
                        $bp = $this->bps[$what];
                }
		$archive = ($this->get_vars[archive]) ? $this->get_vars[archive] : '0';		

		if($what == 'orders'){
			 $params =     array(
                                        "order_by" => "id desc",
                                        "archive" => "$archive"
                             );
		}else{
			$params = array();
		}
                $total = $bp->count_elements($params);

                $p = new pager($total,20,$this->get_vars[p]);
                $elements = $p->get_elements($bp,$params);
                $mask = $bp->props[manager_mask];

                $nav = $p->nav($h->sfile . "?action=manage&what=$what");

                $manager = "<table align = \"center\" border=\"" . $d->tableborder ."\"><tr>";
                $fields = $mask->filter_keys($bp->props[fields]);

                $numcols = ($what == 'products')? 3 : 4;
		$numext = $numcols;
                while(list($key,$value) = each($fields)){
                        $numcols++;
                        $key = preg_replace("/_/"," ",$key);
                        $key = ucwords($key);

                        $manager .= "<td bgcolor=\"" . $d->darkcolor . "\"><b>" . $d->startfont() . $key . "</font></b></td>\n";
                }
		for($i=0;$i<$numext;$i++){
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
                $manager .= "\t<tr>\n\t\t<td colspan=\"$numcols\" bgcolor=\"" . $d->darkcolor . "\"><b>" . $d->startfont() . "<a href=\"" . $h->sfile . "?action=edit&what=$what\">Add $ana $stitle </a></font></td>\n\t</tr>";

                //Pager(instansiated above)
                $manager .= $nav ? "\t<tr>\n\t\t<td colspan=\"$numcols\" bgcolor=\"" . $d->darkcolor . "\">" . $d->startfont() . "Pages:&nbsp;" .  $nav . "</font></td>\n\t</tr>" : "";
                for($i = 0; $i<count($elements);$i++){
                        $switch = $switch ? 0 : 1;
                        $bgcolor = $switch ? $d->lightcolor : "#ffffff";

                        $e = $elements[$i];
                        $fields = $mask->filter_keys($e->props);
                        $manager .= "<tr>";

                        while(list($key,$value) = each($fields)){
                                $value = stripslashes($value);
                                $manager .= "<td bgcolor=\"$bgcolor\">" . $d->startfont() . $value . "</font></td>";
                        }
                        $manager .= "<td bgcolor=\"$bgcolor\">" . $d->startfont();
                        if($bp->props[preview_page]){
                                $manager .=  "<a href=\"" . $bp->props[url] . $bp->props[preview_page] . "?id=" . $e->id . "\" target=\"_new\"\">View</a></font>";
                        }else{
                                $manager .= "<a href=\"" . $h->sfile . "?action=view&what=$what&id=" . $e->id . "\">View</a></font>";
                        }
                        $manager .= "</td>";
			$manager .= ($what == 'orders') ? "<td bgcolor=\"$bgcolor\">" . $d->startfont() . "<a href=\"" . $h->sfile . "?action=archive&what=$what&id=" . $e->id . "\">Archive</a></font></td>" : "";

			$manager .= "<td bgcolor=\"$bgcolor\">" . $d->startfont() . "<a href=\"" . $h->sfile . "?action=edit&what=$what&id=" . $e->id . "\">Modify</a></font></td>\n";

			$manager .= "<td bgcolor=\"$bgcolor\">" . $d->startfont() . "<a href=\"javascript:deleteConfirm(" . $e->id . ")\">Delete</a></font></td>\n";
                        $manager .= "</tr>";
                }
                 $manager .= $nav ? "\t<tr>\n\t\t<td colspan=\"$numcols\" bgcolor=\"" . $d->darkcolor . "\">" . $d->startfont() . "Pages:&nbsp;" . $nav . "</font></td>\n\t</tr>" : "";
                $manager .= "</table>";
                return $manager;
        }

	function nav() {

                $bps = $this->bps;
                $bp = $this->bp;
                $local = $this->local;
                $h = $this->html;
                $d = $this->display;

                $nav = "<center>" . $d->startfont();

                if($bps){
                        while(list($key,$value) = each($bps)){
                                $nav .= $this->_navlet($value->props[table]);

                        }
                }else{
                        $nav .= "No Blueprint Objects!\n";
                }
                $nav .= "<a href=\"" . $local->props[url] . "\" target = _new>View Site</a> | ";
		$nav .= "<a href=\"" . $h->sfile . "?action=manage&what=orders&archive=1\">View Order Archive</a>";
                $nav = preg_replace("/\| $/","",$nav);
                $nav .= "</font></center>\n<hr>";
                return $nav;
        }
}


?>
