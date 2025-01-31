<? 
//This is an element with a bp of links & follows the following rules:
//bp1 can be pages or elinks or page_links
//bp2 is always a template(single element)
//type1 can be condition, all, or ids
//type2 is always id


class template_link extends element{
	function template_link($id=''){
		
		$bp = new links;
		//echo "id is $id";
		$this->element($bp,$id);

		//get template obj & assign it as a prop
		$tbp = new templates;
                $tid = $this->props[ids2];
		$tid = preg_replace("/[\[\]]/","",$tid);
                
		//echo 'tid is ' . $this->props[ids2];
		//echo "tid is $tid\n<br>";
		$tmpltobj = new template($tbp,$tid);
		$this->template_obj = $tmpltobj;
		$this->template_html_path = $tmpltobj->html;
		$this->template_stylesheet_path = $tmpltobj->stylesheet;
	}
	
}
?>
