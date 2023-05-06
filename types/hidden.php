<?php

class hidden extends type
{
    public function hidden($key, $value, $bp)
    {
        //constructor
        return $this->type($key, $value, $bp);
    }

    public function form_field()
    {
        $value = stripslashes($this->value);
        $key = $this->key;
        $length = $this->length;
        $label = $this->get_label();
        return "<input type=\"hidden\" name=\"$key\" value=\"" . $value . "\" size=\"" . $length . "\">\n";
    }

}
