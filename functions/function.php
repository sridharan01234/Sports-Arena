<?php
//user-defined function

function foo()
{
    function bar()
    {
        echo "this is user defined function";
    }
}
foo();
bar();

echo "<br>";

//function arguments

function take_array($array1,$array2,$array3)
{
    echo "$array1,$array2,$array3";
}
take_array('gomathi','thiru','abi');
echo "<br>";

function make_coffee($type='cappuccino')
{
    return "making a cup of $type";
}
echo make_coffee() . "<br>";
echo make_coffee(null) . "<br>";
echo make_coffee('blackcoffee') . "<br>";

//returning values
function square($num)
{
    return $num * $num;
}
echo square(2);

//variable function

class Foo
{
    function variable()
    {
        $name = "gomathi";
        $this->$name;
    }
    function bar()
    {
        echo "this is bar";
    }
}
$foo = new Foo();
$funcName = "variable";
$foo->$funcName;
echo "<br>";

//arrow function

$a=10;
$function = fn($b) => fn($c) => $a * $b + $c;
print_r($function(20)(10));

//anonymous function

$greet = function($name)
{
    printf("hello %s",$name) . "/n";
};
$greet('gomathi');
$greet('thiru');
?>