<?php
abstract class MakeCar{
    abstract public function make();
}
interface CarInterface 
{
    public function model();
    public function fuelType();
    public function musicSystem();
    public function wheel();
}
interface Battery
{
    public function start();
    public function stop();
}
interface Engine
{
    public function start();
    public function stop();
}

class ElectricCar extends MakeCar implements CarInterface,Battery 
{
    private $model;
    private $fuelType;
    private $musicSystem;
    private $wheel;
    
    public function __construct($model,$fuelType,$musicSystem,$wheel)
    {
        $this->model=$model;
        $this->fuelType=$fuelType;
        $this->musicSystem=$musicSystem;
        $this->wheel=$wheel;
    }
    public function make() {
        return "Manufacturer: Electric Car Company.";
    }
    public function model()
    {
        return $this->model;
    }
    public function fuelType()
    {
        return $this->fuelType;
    }
    public function musicSystem()
    {
        return $this->musicSystem;
    }
    public function wheel()
    {
        return $this->wheel;
    }
    public function start()
    {
        return "Electric motor started.";
    }
    public function stop()
    {
        return "Electric motor stoped.";
    }
}

class DieselCar extends MakeCar implements CarInterface,Engine
{
    private $model;
    private $fuelType;
    private $musicSystem;
    private $wheel;

    public function __construct($model,$fuelType,$musicSystem,$wheel)
    {
        $this->model=$model;
        $this->fuelType=$fuelType;
        $this->musicSystem=$musicSystem;
        $this->wheel=$wheel;
    }
    public function make() {
        return "Manufacturer: Diesel Car Company.";
    }
    public function model()
    {
        return $this->model;
    }
    public function fuelType()
    {
        return $this->fuelType;
    }
    public function musicSystem()
    {
        return $this->musicSystem;
    }
    public function wheel()
    {
        return $this->wheel;
    }
    public function start()
    {
        return "Diesel engine started.";
    }
    public function stop()
    {
        return "Diesel engine stoped.";
    }
}
$electricCar = new ElectricCar('Model S','Electric','FIRE NEX',4);
$dieselCar = new DieselCar('A4','Diesel','Bang',4);

echo $electricCar->make() . "<br>";
echo $electricCar->model() . "<br>";
echo $electricCar->fuelType() . "<br>";
echo $electricCar->musicSystem() . "<br>";
echo $electricCar->wheel() . "<br>";
echo $electricCar->start() . "<br>";
echo $electricCar->stop() . "<br>";

echo "<br>";

echo $dieselCar->make() . "<br>";
echo $dieselCar->model() . "<br>";
echo $dieselCar->fuelType() . "<br>";
echo $dieselCar->musicSystem() . "<br>";
echo $dieselCar->wheel() . "<br>";
echo $dieselCar->start() . "<br>";
echo $dieselCar->stop() . "<br>";
?>