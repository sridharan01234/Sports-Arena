<?php
abstract class Shape
{
    protected $color;
    public function __construct($color)
    {
        $this->color = $color;
    }
    abstract public function calculateArea();
    abstract public function calculatePerimeter();

    public function getColor()
    {
        return $this->color;
    }
}

class Circle extends Shape {
    protected $radius;
    public function __construct($radius,$color)
    {
        parent::__construct($color);
        $this->radius = $radius;
    }
    public function calculateArea()
    {
        return pi() * pow($this->radius, 2);
    }
    public function calculatePerimeter() 
    {
        return 2 * pi() * $this->radius;
    }
}

class Rectangle extends Shape {
    protected $width;
    protected $height;
    public function __construct($width,$height,$color)
    {
        parent::__construct($color);
        $this->width = $width;
        $this->height = $height;
    }
    public function calculateArea()
    {
        return $this->width * $this->height;
    }
    public function calculatePerimeter()
    {
        return 2 * ($this->width + $this->height);
    }
}

$circle=new Circle(4,'red');
$rectangle=new Rectangle(4,6,'blue');

echo "circle color: ".$circle->getColor()."<br>";
echo "circle area: ".$circle->calculateArea()."<br>";
echo "circle perimeter: ".$circle->calculatePerimeter()."<br>";
echo "rectangle color: ".$rectangle->getColor()."<br>";
echo "rectangle color: ".$rectangle->calculateArea()."<br>";
echo "rectangle color: ".$rectangle->calculatePerimeter()."<br>";
