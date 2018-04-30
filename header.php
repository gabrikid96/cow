<!DOCTYPE html, php>
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
                <ul class="nav navbar-nav navbar-right">
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["action"]) && $_POST["action"] == "login"){
                    ?>  
                    <li>
                        <a>Welcome  <?php echo $_POST["email_login"]; ?></a>
                    </li>
                    <li>
                        <a href="./">Logout</a>
                    </li>
                    <?php 
                    }else{ 
                    ?>
                    <li class="<?php echo active('register.php');?>">
                            <a href="./register.php">Already don't have an account?</a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <b>Login</b>
                            <span class="caret"></span>
                        </a>
                        
                        <ul id="login-dp" class="dropdown-menu">
                            <li>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="text-center">Login via</label>   
                                        <div class="social-buttons">
                                            <a href="#" class="btn btn-fb">
                                                <i class="fa fa-facebook"></i> Facebook</a>
                                            <a href="#" class="btn btn-tw">
                                                <i class="fa fa-twitter"></i> Twitter</a>
                                        </div>
                                        <p class="text-center">or</label>   
                                        <form class="form" action="<?php echo $pageName?>" method="POST">
                                            <input type="hidden" name="action" value="login">
                                            <div class="form-group">
                                                <label class="sr-only" for="email_login">Email address</label>
                                                <input type="email" class="form-control" id="email_login" name="email_login" placeholder="Email address" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="sr-only" for="password">Password</label>
                                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                                <div class="help-block text-right">
                                                    <a href="">Forget the password ?</a>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary btn-block">Sign in</button>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox"> Remember me
                                                </label>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <?php
                    } 
                    ?>  
                </ul>
            </div>
        </div>
    </nav>