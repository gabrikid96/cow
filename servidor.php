<?php


function Get_Request(){
    $MAX_SEATS = 10;
    $MIN_SEATS = 1;
    foreach ($_GET as $key => $value){
        /*Checking departure*/
        if ($key == "departure"){
            print "Departure: ";
            print (!empty($value) ? $value : GetError("not specified")) . "<br>";
        }

        /*Checking destination*/
        if ($key == "destination"){
            print "Destination: ";        
            print (!empty($value) ? $value : GetError("not specified")) . "<br>";
        }

        /*Checking seats number*/
        if ($key == "seats"){
            print "Seats number: ";        
            if (!empty($value)){
                    $seats = intval($value);
                    print $seats;
                    if ($seats > $MAX_SEATS || $seats < $MIN_SEATS)
                        print GetError(" (ERROR: Seats number must be between $MIN_SEATS and $MAX_SEATS)");                
            }else{
                print GetError("not specified");
            }
            print "<br>";
        }
        $now = new DateTime();
        /*Checking departure date*/
        if ($key == "departureDate"){ 
            print "Departure date: ";   
            if (!empty($value)){
                $departureDate = date_create($value);
                print $departureDate->format('d-m-Y');
                if (strtotime($value) < strtotime('now'))
                    print GetError(" (ERROR: Departure date must be after today)") ;     
                
            }else{
                print GetError("not specified");
            }
            print "<br>";
        }

        /*Checking return date*/
        if ($key == "returnDate"){ 
            print "Return date: ";   
            if (!empty($value)){
                $returnDate = date_create($value);
                print $returnDate->format('d-m-Y');
                if (strtotime($value) < strtotime('now'))
                    print GetError(" (ERROR: Return date must be after today)");

                if (strtotime($value) < strtotime($_GET['departureDate']))
                    print GetError(" (ERROR: Return date must be after departure date)") ;               
            }else{
                print GetError("not specified");
            }
            print "<br>";
        }

        if ($key == "email"){
            print "Email: ";
            if (!empty($value)){
                print $value;
                if (!check_email($value))
                    print GetError(" (ERROR: Invalid email format)");
            }else{
                print GetError("not specified");
            }
            print "<br>";            
        }
    }
}

function check_email($email) {
    
    return preg_match("^[a-z0-9._%+-]+@[a-z0-9]+\.[a-z]{2,4}$^",$email);
}

function GetError($text)
{
    return "<label style='color: #F00'>$text</label>";
}

function Post_Request(){

}

if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    Get_Request();
}else if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    Post_Request();
} 



?>