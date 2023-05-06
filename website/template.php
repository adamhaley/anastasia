<?php

class template extends element
{
    public function template($id = '')
    {
        $bp = new templates();
        //echo "id is $id";
        $this->element($bp, $id);
    }

}
