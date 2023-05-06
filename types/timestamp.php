<?php

class timestamp extends type
{
    public function timestamp($key, $value, $bp)
    {
        //constructor
        return $this->type($key, $value, $bp);
    }
    public function prepare()
    {
        $date = time();
        return $date;
    }

    public function form_field()
    {
        $value = stripslashes($this->value);
        $key = $this->key;
        $length = $this->length;
        $label = $this->get_label();
        return $label . ": $value \n";

    }

        public function form_field_modify()
        {
            return $this->form_field();
        }


}
