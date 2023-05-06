<?php

class pages extends blueprint
{
    public function __construct()
    {
        $props = ["local" => new local(), "table" => "pages", "manager_mask" => new mask(["id", "name", "category", "date", "image1"]), "fields" => ["id" => ["type" => "int", "length" => "11", "settable" => "no"], "name" => ["type" => "text", "length" => "20"], "body" => ["type" => "desc", "length" => "800"]]];
        $this->blueprint($props);
        return;
    }
}
