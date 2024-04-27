<?php
function add()
{
    echo "GOMATHI";
}
add();
echo "<br>";


function addd($a)
{
    return $a+2;
}
echo addd(1);
echo "<br>";

function add1($a,$b=3)
{
    return $a+$b;
}
echo add1(1);
echo "<br>";


//annonymous
$ten = 10;
$result = array_map(function($arg)use($ten){return $arg*$ten;},[1,2,3,4,5,6]);
print_r($result);
echo "<br>";

//arrow
$ten = 10;
$result = array_map(fn($arg) => $arg*$ten,[1,2,3,4,5,6]);
print_r($result);
?>