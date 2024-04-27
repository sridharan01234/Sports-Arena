<?php

// Autoloader function
function my_autoloader($class) {
    include 'classes/' . $class . '.php';
}

// Register the autoloader
spl_autoload_register('my_autoloader');

// Now you can create an instance of MyClass without including its file explicitly
$myObj = new MyClass();
?>