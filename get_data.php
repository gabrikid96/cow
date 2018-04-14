<?php
$dbWorld = new PDO("mysql:dbname=world;host=localhost", "root","");
$dbWorld->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbWorld->exec("SET NAMES 'utf8'");

if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    if(!empty($_GET["city"])) {
        //LIKE '%age of empires III%');"
        $string = $_GET["city"];
        $cities = $dbWorld->query("SELECT id, name from `cities` where name LIKE '$string%' order by name ASC");
        foreach ($cities as $city) {
            $value = $city['name'];
            $id = $city['id'];
            echo $value . ",";
        }
    }

}
?>