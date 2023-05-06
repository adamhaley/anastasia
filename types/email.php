<?php

class email extends type
{
    public function email($key, $value, $bp)
    {
        //constructor
        return $this->type($key, $value, $bp);
    }
    public function get_value_for_web()
    {
        $value = $this->value;
        $value =  "<a href=\"mailto:" . $value . "\">" . $value . "</a>";
        $value = $this->_wrap($value);
        return $value;
    }


}
