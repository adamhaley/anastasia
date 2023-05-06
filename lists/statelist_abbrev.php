<?php

class statelist_abbrev extends keywords
{
    public function statelist_abbrev($name)
    {
        $db = new info_db();

        $qh = mysql_query("select * from states");
        while($row = mysql_fetch_array($qh)) {
            $array[] = stripslashes($row['abbrev']);
        }
        $this->keywords($array, $name);
    }
}
