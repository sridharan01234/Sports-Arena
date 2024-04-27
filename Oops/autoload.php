<?php
function __autoloader($classname)
{
    require $classname.".php";
}

$friend = new Friend;
$friend->setName("Gomathi");
$friend->setMessage("Hai gomathi");

$friend->getName();
$friend->speak();
?>