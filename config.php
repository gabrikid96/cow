<?php
session_start(); // Start Session
header('Cache-control: private'); // IE 6 FIX
// ---------- LOGIN INFO ---------- //
$cookie_name = 'siteAuth';
$cookie_time = (3600 * 24 * 30); // 30 days
if(isSet($_SESSION['email_login'])){
    include_once 'autologin.php';
}
?>