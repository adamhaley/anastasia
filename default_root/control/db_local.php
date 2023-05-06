<?php

class db_local extends db
{
    public function db_local()
    {
        $this->host = 'localhost';
        $this->user = 'root';
        $this->password = 'flam7ingo';
        $this->database = 'testsite';

        //Connect and select the database
        $this->db();
    }
}
