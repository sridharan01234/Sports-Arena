<?php
class Person
{
    public $name;
    function setName($namedata)
    {
        $this->name=$namedata;
    }
    function getName()
    {
        return $this->name;
    }
}
?>