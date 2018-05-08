<?php
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

if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    if(!empty($_GET["city"])) {
        $string = $_GET["city"];
        $cities = $dbWorld->query("SELECT id, name from `cities` where name LIKE '$string%' order by name ASC");
        echo json_encode($cities->fetchAll(PDO::FETCH_ASSOC));
    }

    if(!empty($_GET["city_json"])) {
        $string = $_GET["city_json"];
        $cities = $dbWorld->query("SELECT id, name from `cities` where name LIKE '$string%' order by name ASC");
        echo json_encode($cities->fetchAll(PDO::FETCH_ASSOC));
    }

    if (!empty($_GET["search_flights"])){
        try{
            $sql = get_flights_filter_query($dbWorld, $_GET['departure'], $_GET['destination'], $_GET['departureDate'], $_GET['arrivalDate'], $_GET['seats']);
            //echo $sql;
            $flights = $dbWorld->query($sql);
            if (strcmp($_GET["type"], "json") === 0){
                echo json_encode($flights->fetchAll(PDO::FETCH_ASSOC));
            }else if (strcmp($_GET["type"], "xml") === 0){
                header('Content-Type: application/xml');
                $xmldoc = new DOMDocument('1.0', 'UTF-8');
                $flights_tag = $xmldoc->createElement("flights");
                
                 
                foreach ($flights as $flight) {
                    $flight_tag = $xmldoc->createElement("flight"); 
                    $flight_tag->setAttribute("departure", $flight["departure"]);
                    $flight_tag->setAttribute("arrival", $flight["arrival"]);
                    $flight_tag->setAttribute("departure_date", $flight["departure_date"]);
                    $flight_tag->setAttribute("arrival_date", $flight["arrival_date"]);
                    $flight_tag->setAttribute("seats_available", $flight["seats_available"]);
                    $flights_tag->appendChild($flight_tag);
                }
                $xmldoc->appendChild($flights_tag); 
                echo preg_replace( "/<\?xml.+?\?>/", "", $xmldoc->saveXML());
                //print $xmldoc->saveXML();
                 
            }
            
        }catch(PDOException $ex){
        }
    }

    if (!empty($_GET["search_cities"])){
        try{
            $sql = get_cities_filter_query($dbWorld, $_GET['continent'], $_GET['city_search'], $_GET['country'], $_GET['country_code']);
            $cities = $dbWorld->query($sql);
            echo json_encode($cities->fetchAll(PDO::FETCH_ASSOC));
        }catch(PDOException $ex){
        }
    }

    if (!empty($_GET["get_continents"])){
        try{
            $continents = $dbWorld->query("SELECT continent from `countries` GROUP BY continent");
            echo getContinentsOptions($continents);
        }catch(PDOException $ex){
        }
    }

    if (!empty($_GET["get_cities"])){
        try{
            $cities = $dbWorld->query("SELECT id, name from `cities` order by name ASC");
            echo json_encode($cities->fetchAll(PDO::FETCH_ASSOC));
        }catch(PDOException $ex){
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if(!empty($_POST["create_flight"])) {
        echo insert_flight($dbWorld);
    }
}

function get_flights_filter_query($db, $departure_id, $arrival_id, $departure_date, $arrival_date, $seats){
    $query = "SELECT * FROM(
    SELECT (SELECT name FROM `cities` WHERE id = f.departure_id) as departure,
    (SELECT name FROM `cities` WHERE id = f.arrival_id)  as arrival,
    departure_date, arrival_date, seats_available
    FROM `flights` f) as customFlights " ;
    //$departure_id = !empty($departure_id) ? intval($departure_id) : 0;
    //$arrival_id = !empty($arrival_id) ? intval($arrival_id) : 0;
    $seats = !empty($seats) ? intval($seats) : 0;
    $departure_date = !empty($departure_date) ? $db->quote(date('Y-m-d', strtotime($departure_date))) : "";
    $arrival_date = !empty($arrival_date) ? $db->quote(date('Y-m-d', strtotime($arrival_date))) : "";

    //!empty($departure_id) ? " departure LIKE '%$departure_id%' " : "",
    $filters = array(!empty($departure_id) ? " departure LIKE '%$departure_id%' " : "",
    !empty($arrival_id) ? " arrival LIKE '%$arrival_id%' " : "",
    !empty($departure_date) ? " departure_date >= $departure_date " : "",
    !empty($arrival_date) ? " arrival_date <= $arrival_date " : "",
    $seats != 0 ? " seats_available >= $seats " : "");
    

    $query .= (!empty($departure_id)  || !empty($arrival_id) || !empty($arrival_date) || !empty($departure_date) || $seats != 0) ? "WHERE " : "";
    foreach ($filters as $filter) {
        $query .= !empty($filter) ? $filter . "AND" : "";
    }
    $query = (substr("$query", -3) == "AND" ? substr_replace($query,"",-3,3) : $query);
    return $query;
}

function get_cities_filter_query($db, $continent, $city_name, $country, $country_code){
    $query = "SELECT ci.name, co.name as country, ci.country_code, co.continent 
    FROM `cities` ci INNER JOIN `countries` co on ci.country_code = co.code ";

    $continent = $continent != null ? $db->quote($continent) : "";
    $country_code = $country_code != null ? $db->quote($country_code) : "";

    $filters = array( !empty($continent) ? " co.continent = $continent " : "",
    !empty($city_name) ? " ci.name LIKE '%$city_name%' " : "",
    !empty($country) ? " co.name LIKE '%$country%' " : "",
    !empty($country_code) ? " ci.country_code = $country_code " : "");

    $query .= (!empty($continent) || !empty($city_name) || !empty($country) || !empty($country_code)) ? "WHERE " : "";
    foreach ($filters as $filter) {
        $query .= !empty($filter) ? $filter . "AND" : "";
    }
    $query = (substr("$query", -3) == "AND" ? substr_replace($query,"",-3,3) : $query) . " LIMIT 10";
    return $query;
}


function getFlightsTable($flights){
    $table = "<div class='container text-center'> <table id='flights_table' class='table table-striped'>"."
    <thead>
        <tr>
            <th>Drag to cart</th>
            <th>Departure city</th>
            <th>Arrival city</th>
            <th>Departure date</th>
            <th>Return date</th>
            <th>Seats available</th>
        </tr>
    </thead>
    <tbody>";
    
    if ($flights != null && $flights->rowCount() > 0){
        
        foreach ($flights as $flights) {
            $table .= "<tr>";
            $table .= "<td><div class='ui-widget-content'><i class='fas fa-plane'></i></div></td>";
            $table .= "<td>".$flights['departure']."</td>";
            $table .= "<td>".$flights['arrival']."</td>";
            $table .= "<td>".$flights['departure_date']."</td>";
            $table .= "<td>".$flights['arrival_date']."</td>";
            $table .= "<td>".$flights['seats_available']."</td>";
            $table .= "</tr>";
        }
    }
    $table .= "</tbody></table></div>";
    return $table;
}

function getCitiesTable($cities){
    $table = "<div class='container text-center'> <table class='table table-striped'>"."
    <thead>
        <tr>
            <th>City</th>
            <th>Country</th>
            <th>Country Code</th>
            <th>Continent</th>
        </tr>
    </thead>
    <tbody>";
    if ($cities != null && $cities->rowCount() > 0){
        foreach ($cities as $city) {
            $table .= "<tr>";
            $table .= "<td>".$city['name']."</td>";
            $table .= "<td>".$city['country']."</td>";
            $table .= "<td>".$city['country_code']."</td>";
            $table .= "<td>".$city['continent']."</td>";
            $table .= "</tr>";
        }
    }
    $table .= "</tbody></table></div>";
    return $table;
}

function getContinentsOptions($continents){
    $options = "";
    foreach ($continents as $continent) {
        $value = $continent['continent'];
        $options .= "<option value='$value'> $value </option>";
    }
    return $options;
}

function getCitiesOptions($cities){
    $options = "";
    foreach ($cities as $city) {
        $value = $city['name'];
        $id = $city['id'];
        echo "<option value='$id'> $value </option>";
    }
    return $options;
}

function insert_flight($db){
    try{
        $departure_id = $_POST["departure"];
        $arrival_id = $_POST["destination"];
        $departure_date = $db->quote(date('Y-m-d', strtotime(htmlentities($_POST['departureDate']))));
        $arrival_date = $db->quote(date('Y-m-d', strtotime(htmlentities($_POST['arrivalDate']))));
        $seats = $_POST["seats"];

        $sql = "INSERT INTO `flights` (departure_id, arrival_id, departure_date, arrival_date, seats_available) VALUES  ($departure_id, $arrival_id, $departure_date, $arrival_date, $seats)";
        $db->exec($sql);
        return true;
    }catch(PDOException $ex){
        return false;
    } 
}
?>