<?php
include 'upperstring.php';
include 'lowerstring.php';
include 'reversestring.php';

use upperstring\StringCase as Upper;
use lowerstring\StringCase as Lower;
use reversestring\StringReverse as Reverse;

$string = "Gomathi Thiru";

echo "Original string: $string<br>";

echo "Uppercase string: " .Upper::uppercase($string) . "<br>";
echo "Lowercase string: " .Lower::lowercase($string) . "<br>";
echo "Reverse string: " .Reverse::reverse($string) . "<br>";
?>