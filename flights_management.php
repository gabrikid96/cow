<?php
include('header.php');
$dbWorld = new PDO("mysql:dbname=world;host=localhost", "root","");
$dbSimpsons = new PDO("mysql:dbname=simpsons;host=localhost", "root","");
$cities = null;
$cities_query = "select ci.name, co.name as country, ci.country_code, co.continent FROM `cities` ci INNER JOIN `countries` co on ci.country_code = co.code LIMIT 10";

$student_name_error = false;
$course_name_error = false;
$course_teacher_error = false;
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (isset($_POST["world_search"])){
        $cities_query = get_world_filter_query($dbWorld, $_POST["continent"],$_POST["city"],$_POST["country"],$_POST["country-code"]);
    }   
    if (isset($_POST["create_student"])){
        if (empty($_POST["name-student"])){
            $student_name_error = true;
        }
    }    
    if (isset($_POST["create_course"])){
        if (empty($_POST["name"])){
            $course_name_error = true;
        }
        if (empty($_POST["teachers"])){
            $course_teacher_error = true;
        }
    }   
}

function get_world_filter_query($db, $continent, $city_name, $country, $country_code){
    $query = "select ci.name, co.name as country, ci.country_code, co.continent 
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


$cities = $dbWorld->query($cities_query);
$continents = $dbWorld->query("select continent from `countries` GROUP BY continent");
$grades = $dbSimpsons->query("SELECT s.name as student, g.grade, c.name as course FROM `grades` g 
                                inner join `students` s on s.id = g.student_id
                                inner join `courses` c on c.id = g.course_id");
$students = $dbSimpsons->query("SELECT id, name as student from `students`");
$teachers = $dbSimpsons->query("SELECT id, name from `teachers`");
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
        <div id="cities" class="panel-collapse collapse in">
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
        <div id="simpsons" class="panel-collapse collapse in">
            <div class="container">
            <form method="POST">
                <div class="row" style="margin: 5px;">
                    <div class="col-md-3">
                        <input class=<?php echo ($student_name_error ? "'form-control error-input'" : "'form-control'");  ?> type="text" id="name-student" name="name-student" placeholder="Student's name">
                    </div>
                    <div class="col-md-3">
                        <input class="form-control" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9]+\.[a-z]{2,4}$" id="email-student" name="email-student" placeholder="Email">
                    </div>
                    <div class="col-md-3">
                        <input class="form-control" type="text" id="password-student" name="password-student" placeholder="Password">
                    </div>
                    <input class="btn btn-success" type="submit" value="Create Student" name="create_student">
                </div>
            </form>
            <form method="POST">
                <div class="row" style="margin: 5px;">
                    <div class="col-md-3">
                        <input class=<?php echo ($course_name_error ? "'form-control error-input'" : "'form-control'");  ?>  type="text" id="name" name="name" placeholder="Course name">
                    </div>
                    <div class="col-md-3">
                        <select class=<?php echo ($course_teacher_error ? "'form-control error-input'" : "'form-control'");  ?>  id="teachers" name="teachers">
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
            <form method="POST">
                <div class="row" style="margin: 5px;">
                    <div class="col-md-3">
                    <select class="form-control" id="students" name="students">
                            <option value="" selected>Student</option>
                            <?php
                            foreach ($students as $student) {
                                $value = $student['student'];
                                $id = $student['id'];
                                echo "<option value='$id'> $value </option>";
                            }?>
                    </select>
                    </div>
                    <div class="col-md-2">
                        <input class="form-control" type="text" id="grade" name="grade" placeholder="Grade">
                    </div>
                    <input class="btn btn-success" type="submit" value="Insert Grade" name="simpsons">
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
    <!-- End World Accordion -->
</div>

<?php
include('footer.html');
?>