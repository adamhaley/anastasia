<?php

class file
{
    public $path;
    public $name;
    public $fullpath;

    public function file($props = '')
    {
        if($props[path]) {
            $this->path = $props[path];
        }
        if($props[name]) {
            $this->name = $props[name];
        }
        if($props[fullpath]) {
            $this->fullpath = $props[fullpath];
        }
    }
}
