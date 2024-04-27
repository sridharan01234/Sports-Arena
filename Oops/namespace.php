<?php
include 'monitor.php';
include 'cpu.php';

//use monitor\workprocess;
$object = new monitor\workprocess();
$object->dowork();

//use cpu\workprocess;
$object2 = new cpu\workprocess();
$object2->dowork();
?>