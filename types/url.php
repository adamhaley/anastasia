<?php

class url extends type
{
    public function url($key, $value, $bp)
    {
        //constructor
        return $this->type($key, $value, $bp);
    }
    public function get_value_for_web()
    {
        $value = $this->value;
        $value =  "<a href=\"http://" . $value . "\" target=\"new\">" . $value . "</a>";
        $value = $this->_wrap($value);
        return $value;
    }


}
