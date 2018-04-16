<?php
include('header.php');
?>

<h1 class="text-center">Flights Management</h1>
<div class="panel-group container" id="accordion">
    <!-- World Accordion -->
    <div class="panel panel-default">
        <!-- Head -->
        <div class="panel-heading">
            <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#cities">
            Cities</a>
            </h4>
        </div>
        <!-- Body -->
        <div id="cities" class="panel-collapse collapse">
            <div class="container">
            <form id="cities-form" method="GET">
                <div class="row" style="margin: 5px;">
                    <div class="col-md-2">
                        <input autocomplete="off" class="form-control" type="text" id="city" name="city" placeholder="City">
                    </div>
                    <div class="col-md-2">
                        <input class="form-control" type="text" id="country" name="country" placeholder="Country">
                    </div>
                    <div class="col-md-2">
                        <input class="form-control" type="text" id="country-code" name="country-code" placeholder="Country code">
                    </div>
                    <div class="col-md-2">
                    <select class="form-control" id="continent" name="continent">
                            <option value="" disabled selected>Continent</option>
                        </select>
                    </div>
                    <input class="btn btn-success" type="submit" value="Search flight" name="world_search">
                </div>
            </form>
                
            </div>
        </div>
    </div>
    <!-- End World Accordion -->

    <!-- Flights Accordion -->
    <div class="panel panel-default">
        <!-- Head -->
        <div class="panel-heading">
            <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#flights-div">
            Flights</a>
            </h4>
        </div>
        <!-- Body -->
        <div id="flights-div" class="panel-collapse collapse">
            <div class="container">
            <form method="POST" id="flights-form">
                <div class="row" style="margin: 5px;">
                    <div class="col-md-2">
                        <select required class="form-control" id="departure" name="departure">
                                <option value="" disabled selected>Departure</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select required class="form-control" id="destination" name="destination">
                                <option value="" disabled selected>Arrival</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input required class="form-control" type="date" id="departureDate" name="departureDate" placeholder="Departure date">
                    </div>

                    <div class="col-md-2">
                        <input required class="form-control" type="date" id="arrivalDate" name="arrivalDate" placeholder="Arrival date">
                    </div>

                    <div class="col-md-2">
                        <input required max="200" min="0" step="1" class="form-control" min="0" type="number" id="seats" name="seats" placeholder="Seats availables">
                    </div>
                    <input class="btn btn-success" type="submit" value="Create Flight" name="create_flight">
                </div>
            </form>
            </div>
        </div>
    </div>
    <!-- End Simpsons Accordion -->
</div>

<?php
include('footer.html');
?>