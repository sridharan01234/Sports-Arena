<?php
class Friend extends Person
{
    public $message;
    function setMessage($data)
    {
        $this->message=$data;
    }
    function speak()
    {
        return $this->message;
    }
}
?>