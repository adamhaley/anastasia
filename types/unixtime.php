<?php

class unixtime extends type
{
    public function unixtime($key, $value, $bp)
    {
        //constructor
        return $this->type($key, $value, $bp);
    }


}
