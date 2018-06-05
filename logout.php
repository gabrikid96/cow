<?php
include 'config.php';

if(isSet($_SESSION['email_login'])){

    unset($_SESSION['email_login']);

    if(isSet($_COOKIE[$cookie_name])){
        setcookie($cookie_name, '', time() - $cookie_time);
    }

header("Location: index.php");
exit;
}
?>