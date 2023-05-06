<?php

class allbps
{
    public $props;
    public function __construct()
    {
        include "bparray.php";

        $this->props = $bpnames; //from bparray
    }

    public function get_elements($cond)
    {
        $allelements = [];
        foreach ($this->props as $key => $value) {
            $a = $value->get_all_elements($cond);
            for($i=0;$i<(is_countable($a) ? count($a) : 0);$i++) {
                $allelements[] = $a[$i];
            }
        }
        return $allelements;
    }

    public function count_elements($params)
    {
        $count = 0;
        foreach ($this->props as $key => $value) {
            $count += $value->count_elements($params);
        }
        return $count;
    }
}
