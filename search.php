<!DOCTYPE html, php>

<?php
include('header.php');
?>
    <h1 class="text-center">Search Flight</h1>

    <div class="container text-center">
        <form action="servidor.php" method="GET">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <label for="departure">Departure</label>
                        <select class="form-control" id="departure" name="departure">
                            <option value="" selected>Please select departure</option>
                            <option value="Barcelona">Barcelona</option>
                            <option value="London">London</option>
                            <option value="New_york">New York</option>
                            <option value="Sidney">Sidney</option>
                            <option value="Tokio">Tokio</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <label for="destination">Destination</label>
                        <select class="form-control" id="destination" name="destination">
                            <option value="" selected>Please select destination</option>
                            <option value="Rome">Rome</option>
                            <option value="Berlin">Berlin</option>
                            <option value="Paris">Paris</option>
                            <option value="Napoli">Napoli</option>
                            <option value="Tokio">Madrid</option>
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
                        <label for="returnDate">Return Date</label>
                        <input class="form-control" type="date" id="returnDate" name="returnDate" placeholder="Please select the return date">
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