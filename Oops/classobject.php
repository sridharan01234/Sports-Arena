<?php
class Fruits
{
    private $name;
    private $color;
    private $vitamin;
    private $city;
    function __construct($name,$color,$vitamin,$city)
    {
        $this->name = $name;
        $this->color = $color;
        $this->vitamin = $vitamin;
        $this->city = $city;
    }
    function getdetails()
    {
        echo "The fruit name is {$this->name} and the color is {$this->color}. It contains {$this->vitamin}. Grown in {$this->city}";
    }
}
$apple = new Fruits('Apple','Red','Vitamin C','Kashmiri');
$apple->getdetails();
echo "<br>";
$mango = new Fruits('Mango','yellow','Vitamin B','Salem');
$mango->getdetails();
echo "<br>";
$grape = new Fruits('Grape','Green','Vitamin A','Maharashtra');
$grape->getdetails();
echo "<br>";
echo "<br>";
echo "<br>";


class Mobile
{
    private $brand;
    private $model;
    private $price;
    private $ram;
    private $rom;
    private $camera;
    function __construct($brand,$model,$price,$ram,$rom,$camera)
    {
        $this->brand = $brand;
        $this->model = $model;
        $this->price = $price;
        $this->ram = $ram;
        $this->rom = $rom;
        $this->camera = $camera;
    }
    function get()
    {
        echo "The mobile name is {$this->brand}, model is {$this->model}, price is {$this->price}, RAM is {$this->ram}GB, ROM is {$this->rom}GB, and camera is {$this->camera}MP.";
    }
}

$iqoo = new Mobile('iqoo','z7 pro',27000,8,250,15);
$iqoo->get();
echo "<br>";
$redmi = new Mobile('Redmi','12 Pro', 20000,8,256,12);
$redmi->get();
echo "<br>";
$samsung = new Mobile('Samsung Galaxy','S21 Ultra',15000,12,512,108);
$samsung->get();
echo "<br>";
$oppo = new Mobile('Oppo','pro 13',10000,8,200,7);
$oppo->get();
echo "<br>";
$oneplus = new Mobile('Oneplus','5T',23000,6,250,15);
$oneplus->get();
echo "<br>";
echo "<br>";
echo "<br>";



class Dress
{
    private $name;
    private $color;
    private $price;
    private $brand;
    private $size;
    private $material;
    function __construct($name,$color,$price,$brand,$size,$material)
    {
        $this->name = $name;
        $this->color = $color;
        $this->price = $price;
        $this->brand = $brand;
        $this->size = $size;
        $this->material = $material;
    }
    function get()
    {
        echo "The dress name is {$this->name},"."<br>";
        echo "The dress color {$this->color},"."<br>";
        echo "The dress price Rs.{$this->price},"."<br>";
        echo "The dress brand is {$this->brand},"."<br>";
        echo "The dress size {$this->size},"."<br>";
        echo "The dress material is {$this->material}."."<br>";
    }
}
$shirt = new dress('Shirt','White',550,'Max','XL','Cotton');
$shirt->get();
echo "<br>";
$pant = new dress('Pant','Black',1000,'Max','XL','Cotton');
$pant->get();
echo "<br>";
$tshirt = new dress('T-shirt','red',500,'Nike','M','Cotton');
$tshirt->get();
echo "<br>";
$top = new dress('Top','Yellow',400,'levis','S','Cotton');
$top->get();
echo "<br>";
$skirt = new dress('Skirt','pink',600,'Max','S','Crape');
$skirt->get();
echo "<br>";


class Vehicle
{
    private $name;
    private $brand;
    private $year;
    private $price;
    private $wheel;
    private $color;
    function __construct($name,$brand,$year,$price,$wheel,$color)
    {
        $this->name = $name;
        $this->brand = $brand;
        $this->year = $year;
        $this->price = $price;
        $this->wheel = $wheel;
        $this->color = $color;
    }
    function getDetails()
    {
        echo "{$this->name},{$this->brand},{$this->year},{$this->price},{$this->wheel},{$this->color}";
    }
}
$car = new Vehicle('Car','Audi',2020,'10Lks',4,'Black');
$car->getDetails();
echo "<br>";
$bike = new Vehicle('Bike','R15',2022,'4Lks',2,'White');
$bike->getDetails();
echo "<br>";
$cycle = new Vehicle('Cycle','Hero',2021,'10K',2,'black');
$cycle->getDetails();
echo "<br>";
$bus = new Vehicle('Bus','Govt',2023,'6Lks',4,'Yellow');
$bus->getDetails();
echo "<br>";
$auto = new Vehicle('Auto','Ford',2020,'10k',4,'black');
$auto->getDetails();
echo "<br>";
?>