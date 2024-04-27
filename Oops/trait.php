<?php
trait MathOperations {
    public function add($a,$b)
    {
      return $a+$b;
    }
    public function subtract($a,$b)
    {
      return $a-$b;
    }
    public function multiply($a,$b)
    {
      return $a*$b;
    }
    public function divide($a,$b)
    {
        if($b != 0)
        {
          return $a/$b;
        } else {
            return "Error: Division by zero";
        }
    }
}
class Calculator
{
    use MathOperations;
}
$calculator = new Calculator();
echo "Addition :".$calculator->add(10,5)."<br>";
echo "Subtraction :".$calculator->subtract(10,5)."<br>";
echo "Multiplication :".$calculator->multiply(10,5)."<br>";
echo "Division :".$calculator->divide(10,5)."<br>";
echo "Divide by zero :".$calculator->divide(10,0)."<br>";
?>