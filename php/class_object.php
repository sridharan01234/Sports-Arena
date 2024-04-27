<?php

class Employee
{
    public $name;
    public $age;
    public $domain;
    function set($n,$a,$d)
    {
        $this->name=$n;
        $this->age=$a;
        $this->domain=$d;
    }
    function get()
    {
        return "<b>Name :</b> " .$this->name . ", <b>Age :</b> " .$this->age . ", <b>Domain :</b> " .$this->domain;
    }
}
$employee1=new Employee();
$employee1->set('Gomathi', 23, 'LAMP');
echo "The first employee details = ".$employee1->get()."<br>";

$employee2=new Employee();
$employee2->set('Priya', 30, 'Mobility');
echo "The second employee details = ".$employee2->get()."<br>";

$employee3=new Employee();
$employee3->set('vinith', 23, 'LAMP');
echo "The third employee details = ".$employee3->get()."<br>";
echo "<br>";

//no this key used directly pass the values
class Car{
    public $name;
    function get()
    {
        echo "The car name = ". $this->name."<br>";
    }
}
$audi = new Car();
$audi->name="AUDI";
$audi->get(); 
$BMW = new Car();
$BMW->name="BMW";
$BMW->get();
$Cyclone = new Car();
$Cyclone->name="CYCLONE";
$Cyclone->get();

echo "<br>";
echo var_dump($audi instanceof car);

echo "<br>";
//construct destruct
//construct parameter ,default constructor
class Tv{
    public $name;
    public $color;
    function __construct($name,$color)
    {
        $this->name=$name;
        $this->color=$color;
    }
    function get()
    {
        echo "The Tv name is ".$this->name . " and the color is ".$this->color."<br>";
       
    }
    // function __destruct()
    // {
    //     echo "{$this->name} {$this->color} Object is destroy"."<br>";
    // }
}
$obj=new Tv('LG','BLACK');
$obj->get();
$obj1=new Tv('REDMI','WHITE');
$obj1->get();
$obj2=new Tv('SONY','YELLO');
$obj2->get();
$obj3=new Tv('SAMSUNG','BLACK');
$obj3->get();
$obj4=new Tv('ONEPLUS','WHITE');
$obj4->get();
echo "<br>";

//inheritance

class Fruits{
    public $name;
    public $color;
    function __construct($name,$color)
    {
        $this->name=$name;
        $this->color=$color;
    }
    function get()
    {
        echo "<b>Parent :</b>The fruit name is " .$this->name. " and the color is " .$this->color."<br>"; 
    }
}    
class Strawberry extends Fruits{
    function child(){
        echo "<b>Child :</b>Am i a fruit or berry";
    }
}
$object=new Strawberry("Strawberry","Red");
$object->get();
$object->child();
echo "<br>";
echo "<br>";


//method overriding
class Fruit{
    public $name;
    public $color;
    public $weight;
    function __construct($name,$color)
    {
        $this->name=$name;
        $this->color=$color;
    }
    function get()
    {
        echo "The fruit name is " .$this->name. " and the color is " .$this->color. "<br>";
    }
}    
class Apple extends Fruit{
    function __construct($name,$color,$weight)
    {
    parent::__construct($name,$color);
        $this->weight=$weight;
    }
    function get()
    {
    echo parent:: get(). "The fruit kg is " .$this->weight ."<br>"; 
    }
}
$object=new Apple("Apple","Red","4KG");
$object->get();
$object1=new Apple("Mango","Yellow","2KG");
$object1->get();

echo "<br>";
echo "<br>";

//const
class Person{
    const a = "gomathi";
    function display()
    {
    echo self::a;
    }
}
echo Person::a;

$obj = new Person();
$obj->display();

echo "<br>";
echo "<br>";

//abstract

// abstract class Parent{
//     abstract function display($s);
// }
// class ChildA extends Parent{
//     function display($s)
//     {
//     echo $s;
//     }
// }    
// class ChildB extends Parent{
//     function display($s)
//     {
//     echo $s;
//     }
// }

// $obj1=new ChildA();
// $obj1->display( "CHILD A");
// $obj2=new ChildB();
// $obj2->display( "CHILD B");

//traits
trait Tamil_teacher{
    function message1(){
        echo "Coming monday tamil exam"."<br>";
    }
}
trait English_teacher{
    function message2(){
        echo "Coming tuesday english exam"."<br>";
    }
}
trait Maths_teacher{
    function message3(){
        echo "Coming wednesday maths exam"."<br>";
    }
}
trait science_teacher{
    function message4(){
        echo "Coming thursday science exam"."<br>";
    }
}
trait social_teacher{
    function message5(){
        echo "Coming friday social exam"."<br>";
    }
}
class student1{
    use Tamil_teacher;
    use English_teacher;
    use Maths_teacher;
    use science_teacher;
    use social_teacher;
}
class student2{
    use Tamil_teacher;
    use English_teacher;
    use Maths_teacher;
    use science_teacher;
    use social_teacher;
}
$obj=new student1();
$obj->message1();
$obj->message2();
$obj->message3();
$obj->message4();
$obj->message5();
echo "<br>";
$obj1=new student2();
$obj1->message1();
$obj1->message2();
$obj1->message3();
$obj1->message4();
$obj1->message5();

echo "<br>";
echo "<br>";


//static
class Cars{
    static function getname()
    {
        return "AUDI";
    }
}
class Audi extends Cars{
    function __construct()
    {
        echo "CAR NAME =".parent::getname()."<br>";
    }
}
$obj = new Audi();

class Bus{
    static $name="SETC";
    function __construct(){
        echo "The bus name is " .self::$name;
    }
}
$obj=new Bus();
echo "<br>";

//interface

interface Method1{
    public function page1();
    public function page2($arg);
    public function page3() : string;
}
interface Method2{
    public function page4();
    public function page5($arg);
    public function page6() : string;
}
class Parentclass implements Method1,Method2{
    public function page1(){
        echo "First method"."<br>";
    }
    public function page2($arg){
        echo $arg."<br>";
    }
    public function page3() : string{
        return "Third method"."<br>";
    }
    public function page4(){
        echo "Fourth method"."<br>";
    }
    public function page5($arg){
        echo $arg."<br>";
    }
    public function page6() : string{
        return "Sixth method"."<br>";
    }
}
$obj = new Parentclass();
$obj->page1();
$obj->page2("second method");
echo $obj->page3();
$obj->page4();
$obj->page5("Fiveth method");
echo $obj->page6();

interface Method1{
    public function page1();
    public function page2($arg);
    public function page3() : string;
}
interface Method2 extends Method1{
    public function page4();
    public function page5($arg);
    public function page6() : string;
}
class Parentclass implements Method2{
    public function page1(){
        echo "First method"."<br>";
    }
    public function page2($arg){
        echo $arg."<br>";
    }
    public function page3() : string{
        return "Third method"."<br>";
    }
    public function page4(){
        echo "Fourth method"."<br>";
    }
    public function page5($arg){
        echo $arg."<br>";
    }
    public function page6() : string{
        return "Sixth method"."<br>";
    }
}
$obj = new Parentclass();
$obj->page1();
$obj->page2("second method");
echo $obj->page3();
$obj->page4();
$obj->page5("Fiveth method");
echo $obj->page6();


<?php
// Parent class Vehicle
class Vehicle {
    protected $brand;
    protected $model;
    protected $year;

    public function __construct($brand, $model, $year) {
        $this->brand = $brand;
        $this->model = $model;
        $this->year = $year;
    }

    public function getInfo() {
        return "Brand: {$this->brand}, Model: {$this->model}, Year: {$this->year}";
    }

    public function drive() {
        return "The vehicle is driving.";
    }
}

// Child class Car inheriting from Vehicle
class Car extends Vehicle {
    private $numDoors;

    public function __construct($brand, $model, $year, $numDoors) {
        parent::__construct($brand, $model, $year);
        $this->numDoors = $numDoors;
    }

    public function getDetails() {
        return parent::getInfo() . ", Doors: {$this->numDoors}";
    }

    public function drive() {
        return "The car is driving.";
    }
}

// Child class Truck inheriting from Vehicle
class Truck extends Vehicle {
    private $loadCapacity;

    public function __construct($brand, $model, $year, $loadCapacity) {
        parent::__construct($brand, $model, $year);
        $this->loadCapacity = $loadCapacity;
    }

    public function getDetails() {
        return parent::getInfo() . ", Load Capacity: {$this->loadCapacity} tons";
    }

    public function drive() {
        return "The truck is driving.";
    }
}

// Usage
$car = new Car("Toyota", "Camry", 2022, 4);
echo $car->getDetails() . "<br>";
echo $car->drive() . "<br>";

$truck = new Truck("Ford", "F150", 2020, 5);
echo $truck->getDetails() . "<br>";
echo $truck->drive() . "<br>";


class Person {
    public $name;
    public $age;

    public function __construct($name, $age) {
        $this->name = $name;
        $this->age = $age;
    }
}

$person = new Person("John", 30);

foreach ($person as $key => $value) {
    echo "$key: $value<br>";
}

?>



?>
