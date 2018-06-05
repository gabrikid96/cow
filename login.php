<?php
//require_once 'config.php';
// Is the user already logged in? Redirect him/her to the private page
if(isSet($_POST['submit'])){
    //echo "Submit";
    $do_login = true;
    include_once 'do_login.php';
}
?>
<ul class="nav navbar-nav navbar-right">
    <?php
        if(isSet($_SESSION['email_login'])){
    ?>  
    <li>
        <a href="./profile.php">Welcome  <?php echo $_SESSION['email_login']; ?></a>
    </li>
    <li>
        <a href="./logout.php">Logout</a>
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
                        <form class="form" method="POST">
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
                                <button type="submit" name ="submit" class="btn btn-primary btn-block">Sign in</button>
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