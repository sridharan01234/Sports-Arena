<?php
trait CarFeatures
{
    public function make()
    {
        return "Car is made.";
    }
    
    public function model()
    {
        return "Car model is XYZ.";
    }
     
    public function wheel()
    {
        return "Car has 4 wheels.";
    }
    
    public function musicSystem()
    {
        return "Car has a premium music system.";
    }
}

trait VehicleBattery
{
    public function fuelType()
    {
        return "Car uses electricity.";
    }

    public function start()
    {
        return "Battery started.";
    }
    
    public function stop()
    {
        return "Battery stopped.";
    }
}

trait VehicleEngine
{
    public function fuelType()
    {
        return "Car uses diesel.";
    }

    public function start()
    {
        return "Engine started.";
    }
    
    public function stop()
    {
        return "Engine stopped.";
    }
}

class ElectricCar
{
    use CarFeatures, VehicleBattery;
}

class DieselCar
{
    use CarFeatures, VehicleEngine;
}

$electriccar = new ElectricCar();
echo $electriccar->make() . "<br>";
echo $electriccar->model() . "<br>";
echo $electriccar->fuelType() . "<br>";
echo $electriccar->wheel() . "<br>";
echo $electriccar->musicSystem() . "<br>";
echo $electriccar->start() . "<br>";
echo $electriccar->stop() . "<br>";

echo "<br>";

$dieselcar = new DieselCar();
echo $dieselcar->make() . "<br>";
echo $dieselcar->model() . "<br>";
echo $dieselcar->fuelType() . "<br>";
echo $dieselcar->wheel() . "<br>";
echo $dieselcar->musicSystem() . "<br>";
echo $dieselcar->start() . "<br>";
echo $dieselcar->stop() . "<br>";
?>
