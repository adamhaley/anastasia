<?
class padmin extends admin{
	function padmin($props){
		if(!$props[bps][pages]){
			$props[bps][pages] = new pages;
		}
		if(!$props[bps][links]){
			$props[bps][links] = new links;
		}
		if(!$props[bps][templates]){
			$props[bps][templates] = new templates;
		}
		if(!$props[bps][orders]){
			$props[bps][orders] = new orders;
		}
		$this->admin($props);
	}
	
	function generate_db(){
                while(list($key,$value) = each($this->bps)){
		      $l = $this->local;
                        $db = $l->props[db];
                        $db->generate_from_blueprint($value);
                }
		$out .= $db->generate_bps();
                $out .= $db->populate_bps($this->bps);
        	return $out;
	}

	//overriding edit_element for template and stylesheet editing
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

                if($post_vars['submitted']){
                       $e->set_all($post_vars,$post_files);
                        //if its a template
                        if($what == 'templates'){
                                $doc_root = $this->local->props[doc_root];
                                //check to see if theres a template file
                                if($file = $e->get_prop('html')){
                                        $file = $doc_root . "templates/" . $file;
                                        //read it
                                        $source = $h->read($file);

                                        //if theres no input from the html form, insert the contents to the html_source prop
                                        if(!$e->get_prop('html_source')){
                                                $e->props[html_source] = $source;
                                        }
                                }
                                //do the same for the style sheet
                                if($cssfile = $e->get_prop('stylesheet')){
                                        $cssfile = $doc_root . 'style/' . $cssfile;
                                        if(!$e->get_prop('stylesheet_source')){
                                                $e->props[stylesheet_source] = $h->read($cssfile);
                                        }
                                }
                        }
                        $r .= $e->save();

                        $h->set_element($e);
                        $r .= $this->manage($what);

                }else{
                        //if this is a template
                        if($what == 'templates'){
                                //if the html_source field is empty && theres a file
                                if(!$e->get_prop('html_source') && $file = $e->get_prop('html')){
                                        //read the file into the html_source field
                                        $file = $this->local->props[doc_root] . 'templates/' . $file;
                                        $e->props[html_source] = $h->read($file);
                                }
                                //same for stylesheet
                                if(!$e->get_prop('stylesheet_source') && $cssfile = $e->get_prop('stylesheet')){
                                        $cssfile = $this->local->props[doc_root] . 'style/' . $cssfile;
                                        $e->props[stylesheet_source] = $h->read($cssfile);
                                }
                        }
                        $h->set_element($e);
                        $r .= $h->generate_form();
			
                               $r .= '<br /><input type="submit" name="submitted" value="Save">&nbsp;';
                }
		$r .= "</form>";
		return $r;
        }	

}
?>
