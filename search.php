<!DOCTYPE html, php>

<?php
include('header.php');
$dbWorld = new PDO("mysql:dbname=world;host=localhost", "root","");
$dbWorld->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbWorld->exec("SET NAMES 'utf8'");

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

        <!--Make sure the form has the autocomplete function switched off:-->
        

        <form id="search-form"action="servidor.php" method="GET">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <label for="departure">Departure</label>
                        <div class="autocomplete">
                            <input autocomplete="off" id="departure" type="text" name="departure" class="form-control" placeholder="Departure city">
                        </div>
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <label for="destination">Destination</label>
                        <div class="autocomplete">
                            <input autocomplete="off" id="destination" type="text" name="destination" class="form-control" placeholder="Destination city">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <label for="seats">Seats number</label>
                        <input max="10" min="0" step="1" class="form-control" type="number" id="seats" name="seats" placeholder="Please select the seats you want">
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
                        <input class="form-control" type="email" id="email" name="email" 
                            placeholder="Enter your email">
                    </div>
                </div>
                <br>
                <div class="text-center">
                    <button class="btn btn-success">Search flight</button>
                </div>
            </div>
            <div id='errorForm' class='alert alert-danger text-center col-md-6 col-md-offset-3' role='alert' style='margin-top: 10px; visibility: hidden;'>
            </div>
        </form>
    </div>
    
    <?php
include('footer.html');
?>