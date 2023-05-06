<?php

class bool extends type
{
    public function bool($key, $value, $bp)
    {
        //constructor
        return $this->type($key, $value, $bp);
    }

    public function db_update_string()
    {
        //for re-saving an element
        $key = $this->key;
        $value = $this->value;
        $string = $key . " = \"" . $value . "\",";
        return $string;
    }

    public function db_insert_string()
    {
        //for saving an element the first time around
        return $this->value;
    }

    public function form_field()
    {
        $value = $this->value;
        $key = $this->key;
        $label = $this->get_label();
        $form = $label . ":<br><input type=\"checkbox\" name=\"" . $key . "\"";
        $form .= $value ? " checked " : "";
        $form .= "><br>";
        return $form;
    }

    public function database_field()
    {
        return "tinyint(1)";
    }

    public function prepare()
    {
        $value = $this->value;
        if($value == 'on') {
            return 1;
        } else {
            return 0;
        }

    }


}
