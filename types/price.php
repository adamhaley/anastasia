<?php

class price extends type
{
    public function price($key, $value, $bp)
    {
        //constructor
        return $this->type($key, $value, $bp);
    }
    public function prepare()
    {
        if($this->value) {
            $num = number_format($this->value, 2);
        } else {
            $num = '';
        }
        return $num;
    }
    public function form_field()
    {

        $value = stripslashes($this->value);
        $key = $this->key;
        $length = $this->length;
        $label = $this->get_label();
        return $label . ":(Leave off the $) <br><input type=\"text\" name=\"$key\" value=\"" . $value . "\" size=\"" . $length . "\"><br>\n";

    }
}
