<?php

class info_db extends db
{
    public function info_db()
    {
        $this->host = 'localhost';

        //Connect and select the database
        $this->db('root', 'flam7ingo', 'info');
    }
}
