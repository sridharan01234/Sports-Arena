<?php
interface MobileInterface {
    public function makecall($phonenumber);
    public function sendMessage($phonenumber,$sendmessage);
}
interface CameraInterface {
    public function takePhoto();
}
interface FlashInterface {
    public function flash();
}

class BasicMobile implements MobileInterface {
    public function makecall($phonenumber)
    {
        echo "Dailing $phonenumber"."<br>";
    }
    public function sendMessage($phonenumber,$sendmessage)
    {
        echo "Sending message to $phonenumber:$sendmessage\n"."<br>";
    }
}

class SmartPhone implements MobileInterface,CameraInterface,FlashInterface {
    public function makecall($phonenumber)
    {
        echo "Initiating call to $phonenumber"."<br>";
    }
    public function sendMessage($phonenumber, $sendmessage)
    {
        echo "Sending SMS to $phonenumber: $sendmessage"."<br>";
    }
    public function takephoto()
    {
        echo "Taking a photo.."."<br>";
    }
    public function flash()
    {
        echo "flash on/off"."<br>";
    }
}

$basicmobile=new BasicMobile();
$basicmobile->makecall('9629639909');
$basicmobile->sendMessage('9629639909','hi from basic mobile');
echo "<br>";
$smartphone=new SmartPhone();
$smartphone->makecall('8754947125');
$smartphone->sendMessage('8754947125','hi from smart phone');
$smartphone->takePhoto();
$smartphone->flash();
?>