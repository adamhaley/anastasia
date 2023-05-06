<?php

class bplist extends keywords
{
    public function bplist($name = '')
    {
        global $db;

        $qh = mysql_query("select * from bps");
        while($row = mysql_fetch_array($qh)) {
            $tmp[] = stripslashes($row[name]);
        }
        $this->keywords($tmp, $name);
    }
}
