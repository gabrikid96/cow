<?php
if(isSet($cookie_name)){
    // Check if the cookie exists
    if(isSet($_COOKIE[$cookie_name])){
        parse_str($_COOKIE[$cookie_name]);
        $_SESSION['email_login'] = $usr;
    }
}
?>