<!DOCTYPE html, php>

<?php
include('header.php');
$dbWorld = new PDO("mysql:dbname=world;host=localhost", "root","");
$dbWorld->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try{
    $sql = "CREATE TABLE flights (
        ID int NOT NULL AUTO_INCREMENT,
        departure_id INT NOT NULL,
        arrival_id INT NOT NULL,
        departure_date DATETIME  NOT NULL,
        arrival_date DATETIME NOT NULL,
        seats_available INT NOT NULL,
        PRIMARY KEY (ID)
    );";
    $dbWorld->exec($sql);
}catch(PDOException $ex){
}

try {
    $departures = $dbWorld->query("SELECT id, name from `cities` order by name ASC");
    $arrivals = $dbWorld->query("SELECT id, name from `cities` order by name ASC");
} catch (PDOException $ex) {
    echo getAlertError("Error on get Citiees in World database.". $ex->getMessage());
}
?>
    <h1 class="text-center">Search Flight</h1>

    <div class="container text-center">
        <form action="servidor.php" method="GET">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <label for="departure">Departure</label>
                        <select class="form-control" id="departure" name="departure">
                            <option value="" selected>Departure</option>
                            <?php
                            foreach ($departures as $departure) {
                                $value = $departure['name'];
                                $id = $departure['id'];
                                echo "<option value='$id'> $value </option>";
                            }?>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <label for="destination">Destination</label>
                        <select class="form-control" id="destination" name="destination">
                            <option value="" selected>Destination</option>
                            <?php
                            foreach ($arrivals as $arrival) {
                                $value = $arrival['name'];
                                $id = $arrival['id'];
                                echo "<option value='$id'> $value </option>";
                            }?>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <label for="seats">Seats number</label>
                        <input class="form-control" type="number" id="seats" name="seats" placeholder="Please select the seats you want">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-3 col-md-offset-3">
                        <label for="departureDate">Departure Date</label>
                        <input class="form-control" type="date" id="departureDate" name="departureDate" placeholder="Please select the departure date">
                    </div>
                    <div class="col-md-3">
                        <label for="arrivalDate">Return Date</label>
                        <input class="form-control" type="date" id="arrivalDate" name="arrivalDate" placeholder="Please select the arrival date">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <label for="email">Email</label>
                        <input class="form-control" type="text" id="email" name="email" 
                            placeholder="Enter your email">
                    </div>
                </div>
                <br>
                <div class="text-center">
                    <button class="btn btn-success">Search flight</button>
                </div>
            </div>
        </form>
    </div>
    <?php
include('footer.html');
?>