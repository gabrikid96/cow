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

if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    try{
        $sql = get_world_filter_query($dbWorld, $_GET['departure'], $_GET['destination'], $_GET['departureDate'], $_GET['arrivalDate'], $_GET['seats']);
        $flights = $dbWorld->query($sql);
        echo getFlightsTable($flights);
        
    }catch(PDOException $ex){
        
    }
    
}
function get_world_filter_query($db, $departure_id, $arrival_id, $departure_date, $arrival_date, $seats){
    $query = "SELECT (SELECT name FROM `cities` WHERE id = f.departure_id) as departure,
    (SELECT name FROM `cities` WHERE id = f.arrival_id)  as arrival,
    departure_date, arrival_date, seats_available
    FROM `flights` f ";
    $departure_id = !empty($departure_id) ? intval($departure_id) : 0;
    $arrival_id = !empty($arrival_id) ? intval($arrival_id) : 0;
    $seats = !empty($seats) ? intval($seats) : 0;
    $departure_date = !empty($departure_date) ? $db->quote(date('Y-m-d', strtotime($departure_date))) : "";
    $arrival_date = !empty($arrival_date) ? $db->quote(date('Y-m-d', strtotime($arrival_date))) : "";

    $filters = array( $departure_id != 0 ? " f.departure_id = $departure_id " : "",
    $arrival_id != 0 ? " f.arrival_id = $arrival_id " : "",
    !empty($departure_date) ? " departure_date >= $departure_date " : "",
    !empty($arrival_date) ? " arrival_date <= $arrival_date " : "",
    $seats != 0 ? " f.seats_available >= $seats " : "");
    

    $query .= ($arrival_id != 0  || $departure_id != 0 || !empty($arrival_date) || !empty($departure_date) || $seats != 0) ? "WHERE " : "";
    foreach ($filters as $filter) {
        $query .= !empty($filter) ? $filter . "AND" : "";
    }
    $query = (substr("$query", -3) == "AND" ? substr_replace($query,"",-3,3) : $query);
    return $query;
}


function getFlightsTable($flights){
    $num = $flights->rowCount();
    $title = "<h3>$num Occurrences</h3>";
    $table = "<div class='container text-center'> <table class='table table-striped'>".$title."
    <thead>
        <tr>
            <th>Departure city</th>
            <th>Arrival city</th>
            <th>Departure date</th>
            <th>Return date</th>
            <th>Seats available</th>
        </tr>
    </thead>
    <tbody>";
    
    if ($flights != null && $num > 0){
        
        foreach ($flights as $flights) {
            $table .= "<tr>";
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
include('footer.html');
?>