<?php
include('header.php');
$dbWorld = new PDO("mysql:dbname=world;host=localhost", "root","");
$dbWorld->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbWorld->exec("SET NAMES 'utf8'");

$dbSimpsons = new PDO("mysql:dbname=simpsons;host=localhost", "root","");
$dbSimpsons->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try{
    $dbSimpsons->exec("ALTER TABLE `students` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;");
    $dbSimpsons->exec("ALTER TABLE `courses` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;");
}catch(PDOException $ex){
    echo $ex->getMessage();
}
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


$cities = null;
$cities_query = "select ci.name, co.name as country, ci.country_code, co.continent FROM `cities` ci INNER JOIN `countries` co on ci.country_code = co.code LIMIT 10";

$student_name_error = false;
$student_email_error = false;
$course_name_error = false;
$course_teacher_error = false;
$student_error = false;
$grade_error = false;
$course_error=false;

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (isset($_POST["world_search"])){
        $cities_query = get_world_filter_query($dbWorld, $_POST["continent"],$_POST["city"],$_POST["country"],$_POST["country-code"]);
    }   
    if (isset($_POST["create_student"])){
        insert_student($dbSimpsons);
    }    
    if (isset($_POST["create_course"])){
        insert_course($dbSimpsons);
    }  
    if (isset($_POST["create_grade"])){
        insert_grade($dbSimpsons);
    }  
    if (isset($_POST["create_flight"])){
        insert_flight($dbWorld);
    }    
}

function insert_student($db){
    global $student_name_error, $student_email_error;
    if (empty($_POST["name-student"])){
        $student_name_error = true;
    }
    if (!empty(($_POST["email-student"])) && !check_email($_POST["email-student"])){
        $student_email_error = true;
    }
    if(!$student_name_error && !$student_email_error){
        try{
            $name = $db->quote($_POST['name-student']);
            $password = $db->quote($_POST['password-student']);
            $email = $db->quote($_POST['email-student']);
            $sql = "INSERT INTO `students` (name, email, password) VALUES ($name, $email, $password)";
            $db->exec($sql);
            echo getSuccessAlert("Student $name added successfully.");
        }catch(PDOException $ex){
            echo getAlertError("Error on insert Student.".$ex->getMessage());
        }
    }
}

function insert_course($db){
    global $course_name_error, $course_teacher_error;
    if (empty($_POST["name"])){
        $course_name_error = true;
    }
    if (empty($_POST["teachers"])){
        $course_teacher_error = true;
    }
    if(!$course_name_error && !$course_teacher_error){
        try{
            $name = $db->quote($_POST["name"]);
            $teacher_id = $_POST["teachers"];
            $sql = "INSERT INTO `courses` (name, teacher_id) VALUES ($name, $teacher_id)";
            $db->exec($sql);
            echo getSuccessAlert("Course $name added successfully.");
        }catch(PDOException $ex){
            echo getAlertError("Error on insert Course.".$ex->getMessage());
        }
    }
}

function insert_grade($db){
    global $student_error, $grade_error, $course_error;
    if (empty($_POST["student"])){
        $student_error = true;
    }
    if (empty($_POST["grade"])){
        $grade_error = true;
    }else if(strlen($_POST["grade"]) > 2){
        $grade_error = true;
        echo getAlertError("Grade exceeds the allowed size (2)");
    }
    
    if (empty($_POST["course"])){
        $course_error = true;
    }
    if(!$course_error && !$grade_error && !$student_error){
        try{
            $grade = $db->quote($_POST["grade"]);
            $course_id = $_POST["course"];
            $student_id = $_POST["student"];
            $sql = "INSERT INTO `grades` (grade, student_id, course_id) VALUES ($grade, $student_id, $course_id)";
            $db->exec($sql);
            echo getSuccessAlert("Grade added successfully.");
        }catch(PDOException $ex){
            echo getAlertError("Error on insert Course.".$ex->getMessage());
        }
    }
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
        echo getSuccessAlert("Flight added successfully.");
    }catch(PDOException $ex){
        echo getAlertError("Error on insert flight.".$ex->getMessage());
    }
    
}

function check_email($email) { 
    return preg_match("^[a-z0-9._%+-]+@[a-z0-9]+\.[a-z]{2,4}$^",$email);
}

function get_world_filter_query($db, $continent, $city_name, $country, $country_code){
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

function getAlert($type, $content){
    return "<div class='alert alert-$type text-center  container' role='alert' style='margin-top: 10px;'>$content</div>";
    
}

function getAlertError($string){
    return getAlert("danger",$string);
}

function getSuccessAlert($string){
    return getAlert("success",$string);
}

try {
    $cities = $dbWorld->query($cities_query);
} catch (PDOException $ex) {
    echo getAlertError("Error on execute query in World database". $ex->getMessage());
}
try {
    $continents = $dbWorld->query("SELECT continent from `countries` GROUP BY continent");
} catch (PDOException $ex) {
    echo getAlertError("Error on get continents in World database.". $ex->getMessage());
}
try {
    $grades = $dbSimpsons->query("SELECT s.name as student, g.grade, c.name as course FROM `grades` g 
    right join `students` s on s.id = g.student_id
    inner join `courses` c on c.id = g.course_id");
} catch (PDOException $ex) {    
    echo getAlertError("Error on get Grades in Simpsons database". $ex->getMessage());
}
try {
    $courses = $dbSimpsons->query("SELECT id, name FROM `courses`");
} catch (PDOException $ex) {    
    echo getAlertError("Error on get Grades in Simpsons database". $ex->getMessage());
}
try {
    $students = $dbSimpsons->query("SELECT id, name as student from `students`");
} catch (PDOException $ex) {
    echo getAlertError("Error on get Students in Simpsons database". $ex->getMessage());
}
try {
    $teachers = $dbSimpsons->query("SELECT id, name from `teachers`");
} catch (PDOException $ex) {
    echo getAlertError("Error on get Teachers in Simpsons database.". $ex->getMessage());
}

try {
    $departures = $dbWorld->query("SELECT id, name from `cities` order by name ASC");
    $arrivals = $dbWorld->query("SELECT id, name from `cities` order by name ASC");
} catch (PDOException $ex) {
    echo getAlertError("Error on get Cities in World database.". $ex->getMessage());
}
try {
    $flights = $dbWorld->query("SELECT (SELECT name FROM `cities` WHERE id = f.departure_id) as departure,
    (SELECT name FROM `cities` WHERE id = f.arrival_id)  as arrival,
    departure_date, arrival_date, seats_available
    FROM `flights` f");
} catch (PDOException $ex) {
    echo getAlertError("Error on get Citiees in World database.". $ex->getMessage());
}

function getFlightsTable($flights){
    $table = "<table class='table table-striped'>
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
    if ($flights != null && $flights->rowCount() > 0){
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
    return $table .= "</tbody></table>";
}
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
            <form method="POST">
                <div class="row" style="margin: 5px;">
                    <div class="col-md-2">
                        <input class="form-control" type="text" id="city" name="city" placeholder="City">
                    </div>
                    <div class="col-md-2">
                        <input class="form-control" type="text" id="country" name="country" placeholder="Country">
                    </div>
                    <div class="col-md-2">
                        <input class="form-control" type="text" id="country-code" name="country-code" placeholder="Country code">
                    </div>
                    <div class="col-md-2">
                    <select class="form-control" id="continent" name="continent">
                            <option value="" selected>Continent</option>
                            <?php
                            foreach ($continents as $continent) {
                                $value = $continent['continent'];
                                echo "<option value='$value'> $value </option>";
                            }?>
                        </select>
                    </div>
                    <input class="btn btn-success" type="submit" value="Search flight" name="world_search">
                </div>
            </form>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>City</th>
                            <th>Country</th>
                            <th>Country Code</th>
                            <th>Continent</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($cities != null && $cities->rowCount() > 0){
                        foreach ($cities as $city) {
                            echo "<tr>";
                            echo "<td>".$city['name']."</td>";
                            echo "<td>".$city['country']."</td>";
                            echo "<td>".$city['country_code']."</td>";
                            echo "<td>".$city['continent']."</td>";
                            echo "</tr>";
                        }
                    }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- End World Accordion -->

    <!-- Simpsons Accordion -->
    <div class="panel panel-default">
        <!-- Head -->
        <div class="panel-heading">
            <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#simpsons">
            Simpsons Grades</a>
            </h4>
        </div>
        <!-- Body -->
        <div id="simpsons" class="panel-collapse collapse">
            <div class="container">
            <form method="POST" id="student-form">
                <div class="row" style="margin: 5px;">
                    <div class="col-md-3">
                        <input class="form-control" required type="text" id="name-student" name="name-student" placeholder="Student's name">
                    </div>
                    <div class="col-md-3">
                        <input class="form-control" type="email" id="email-student" name="email-student" placeholder="Email">
                    </div>
                    <div class="col-md-3">
                        <input class="form-control" type="text" id="password-student" name="password-student" placeholder="Password">
                    </div>
                    <input class="btn btn-success" type="submit" value="Create Student" name="create_student">
                </div>
            </form>
            <form method="POST" id="course-form">
                <div class="row" style="margin: 5px;">
                    <div class="col-md-3">
                        <input class="form-control"  type="text" id="name" name="name" placeholder="Course name" required>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control"  id="teachers" name="teachers" required>
                                <option value="" disabled selected>Teacher</option>
                                <?php
                                foreach ($teachers as $teacher) {
                                    $value = $teacher['name'];
                                    $id = $teacher['id'];
                                    echo "<option value='$id'> $value </option>";
                                }?>
                        </select>
                    </div>
                    <input class="btn btn-success" type="submit" value="Create Course" name="create_course">
                </div>
            </form>
            <form method="POST" id="grade-form">
                <div class="row" style="margin: 5px;">
                    <div class="col-md-3">
                    <select class="form-control" id="student" name="student" required>
                            <option value="" disabled selected>Student</option>
                            <?php
                            foreach ($students as $student) {
                                $value = $student['student'];
                                $id = $student['id'];
                                echo "<option value='$id'> $value </option>";
                            }?>
                    </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control"  id="course" name="course" required>
                                <option value="" disabled selected>Course</option>
                                <?php
                                foreach ($courses as $course) {
                                    $value = $course['name'];
                                    $id = $course['id'];
                                    echo "<option value='$id'> $value </option>";
                                }?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input class="form-control" type="text" id="grade" name="grade" placeholder="Grade" required>
                    </div>
                    <input class="btn btn-success" type="submit" value="Insert Grade" name="create_grade">
                </div>
            </form>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($grades != null && $grades->rowCount() > 0){
                        foreach ($grades as $grade) {
                            echo "<tr>";
                            echo "<td>".$grade['student']."</td>";
                            echo "<td>".$grade['course']."</td>";
                            echo "<td>".$grade['grade']."</td>";
                            echo "</tr>";
                        }
                    }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- End Simpsons Accordion -->

    <!-- Flights Accordion -->
    <div class="panel panel-default">
        <!-- Head -->
        <div class="panel-heading">
            <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#flights">
            Flights</a>
            </h4>
        </div>
        <!-- Body -->
        <div id="flights" class="panel-collapse collapse">
            <div class="container">
            <form method="POST" id="flights-form">
                <div class="row" style="margin: 5px;">
                    <div class="col-md-2">
                        <select required class="form-control" id="departure" name="departure">
                                <option value="" disabled selected>Departure</option>
                                <?php
                                foreach ($departures as $departure) {
                                    $value = $departure['name'];
                                    $id = $departure['id'];
                                    echo "<option value='$id'> $value </option>";
                                }?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select required class="form-control" id="destination" name="destination">
                                <option value="" disabled selected>Arrival</option>
                                <?php
                                foreach ($arrivals as $arrival) {
                                    $value = $arrival['name'];
                                    $id = $arrival['id'];
                                    echo "<option value='$id'> $value </option>";
                                }?>
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
            <?php echo getFlightsTable($flights);?>
            </div>
        </div>
    </div>
    <!-- End Simpsons Accordion -->
</div>

<?php
include('footer.html');
?>