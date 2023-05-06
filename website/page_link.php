<?php

class page_link extends element
{
    public function page_link($id='')
    {
        $bp = new links();
        $this->element($bp, $id);
    }

}
