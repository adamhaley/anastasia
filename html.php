<?php

class html
{
    public $header;
    public $footer;
    public $template;
    public $sfile;
    public $formaction;
    public $e;
    public $bp;
    public $display;

    public function html($sfile, $e = '')
    {
        $this->sfile=$sfile;
        $this->formaction = $sfile;
        $this->e = $e;
        $this->bp = $e->bp;
        $bp = $this->bp;
    }

    public function set_header($header)
    {
        $source = $this->read($header);
        $this->header = $source;
    }

    public function set_footer($footer)
    {
        $source = $this->read($footer);
        $this->footer = $source;
    }

    public function set_template($template)
    {
        $source = $this->read($template);
        $this->template = $source;
    }

    public function set_keywordobjs($keywordobjs)
    {
        //pass this an array of any keyword objects needed with classnames as keys
        $this->keywordobjs = $keywordobjs;
    }

    public function set_template_object($obj)
    {
        $this->template_object = $obj;
        $this->template = $obj->get_prop('html_source');
    }

    public function set_element($e)
    {
        $this->e = $e;
        $this->bp = $e->bp;
    }
    public function read($file)
    {
        $fh = fopen($file, "r")
            or die("could not open file e bp:" . $this->bp->props[table] . "\n<br>");
        $contents = fread($fh, filesize($file))
            or die("could not read file e bp:". $this->bp . "\n<br>");
        return $contents;
    }

    public function parse_template($which = '', $e = '')
    {
        //which is either header or footer
        //if nothing is passed template is used

        //e is an optional element object if none is passed, this->e is used

        if(!$e) {
            $e = $this->e;
        }

        $bp = $e->bp;
        if($which == 'header') {
            $source = $this->header;
        } elseif($which == 'footer') {
            $source = $this->footer;
        } else {
            $source = $this->template;
        }

        if(!$source) {
            return "No template source in html::parse_template!";
        }
        $fields = $e->props;
        while(list($key, $value) = each($fields)) {
            $value = stripslashes($value);
            $type = $bp->props[fields][$key][type];
            $http_vars = $this->http_vars;

            if($type == 'keyword_list' || $type == 'keyword_select') {
                //echo "key is $key value is $value \n<br>";
                $kobj = $keywords[$key];
                eval("\$typeobj = new $type (\$key,\$value,\$bp,\$kobj,\$http_vars);");
            } elseif(class_exists($type)) {
                eval("\$typeobj = new $type (\$key,\$value,\$bp,\$http_vars,\$post_files);");

            } else {
                $typeobj = new type($key, $value, $bp, $http_vars);
            }

            $value = $typeobj->get_value_for_web();

            $source = str_replace("<%$key%>", $value, $source);
        }
        return $source;
    }

    public function generate_form()
    {
        $bp = $this->bp;
        $e = $this->e;
        $d = $this->display;

        $fields = $bp->props[fields];

        while(list($key, $value) = each($fields)) {


            if(!$label = $value[label]) {
                $label = preg_replace("/_/", " ", $key);
                $label = ucwords($label);
            }


            $type = $value[type];
            $data = $e->get_prop($key);
            $length = $value['length'] ? $value['length'] : '';
            $http_vars = $this->http_vars;
            //if($key == 'id' || $type == 'hidden'){
            //	$form .= "<input type=\"hidden\" name=\"$key\" value=\"$data\">\n";
            if(preg_match("/^label/", $key)) {
                $form .= "<br>\n<b>$value</b>\n<br><br>";
            } else {
                if($type == 'keyword_list' || $type == 'keyword_select') {
                    $kobj = $bp->keywords[$key];
                    eval("\$typeobj  = new $type(\$key,\$data,\$bp,\$kobj,\$http_vars);");
                } elseif(class_exists($type)) {
                    eval("\$typeobj = new $type(\$key,\$data,\$bp,\$http_vars,\$post_files);");

                } else {
                    $typeobj = new type($key, $data, $bp, $http_vars);
                }
                if($e->get_prop('id')) {
                    //this is a modify
                    $form .= $typeobj->form_field_modify();
                } else {
                    $form  .= $typeobj->form_field();
                }
                $form .= "<br />";

            }
        }
        return $form;
    }

    public function start($title='', $headstuff='')
    {
        $d = $this->display;
        return "<html>
		<head>
		<title>$title</title>
		$headstuff
		</head>
		<body bgcolor=\"" . $d->bgcolor . "\" text=\"" . $d->fontcolor . "\" alink=\"" . $d->alink . "\" vlink=\"" . $d->vlink . "\" link=\"" . $d->link . "\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">";
    }

    public function end()
    {
        return "</body>\n</html>";
    }
}
