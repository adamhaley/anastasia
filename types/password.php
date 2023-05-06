<?php

class password extends type
{
    public function password($key, $value, $bp)
    {
        //constructor
        return $this->type($key, $value, $bp);
    }

    public function prepare()
    {
        if($this->new==1) {
            return MD5($this->value);
        } else {
            return $this->value;
        }
    }

         public function form_field()
         {
             $value = stripslashes($this->value);
             $key = $this->key;
             $length = $this->length;
             $label = $this->get_label();
             return $label . ": <br><input type=\"password\" name=\"$key\" value=\"" . $value . "\" size=\"" . $length . "\"><br>\n";

         }

}
