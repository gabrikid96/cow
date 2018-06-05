<!DOCTYPE html, php>
<?php
require_once 'config.php';
?>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" type="text/css" href="./index.css" </head>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>
<?php
        $pageName = substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'],"/") + 1);                                        
		function active($url){
            $pageName = substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'],"/") + 1);                            
            return (($pageName == "" && strpos($url, 'index') !== false) || strpos($url, $pageName) !== false) ? "active" : "";
        }
	?>
    <nav class="navbar navbar-default navbar-inverse" style="margin-bottom:0px;" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="<?php echo active('index.php');?>">
                        <a href="./">Home</a>
                    </li>
                    <li class="<?php echo active('search.php');?>">
                        <a href="./search.php">Search</a>
                    </li>
                    <li class="<?php echo active('flights_management.php');?>">
                        <a href="./flights_management.php">Flights Management</a>
                    </li>
                </ul>
                <?php include('login.php'); ?>
            </div>
        </div>
    </nav>