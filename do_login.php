<?php
$db = new PDO("mysql:dbname=simpsons;host=localhost", "root", "");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec("SET NAMES 'utf8'");
if(!$do_login) exit;
// declare post fields
$post_username = trim($_POST['email_login']);
$post_password = trim($_POST['password']);
$post_autologin = isSet($_POST['autologin']);

$result = $db->query("SELECT * FROM students WHERE email = '$post_username' AND password = '$post_password'");
if($result->rowCount() > 0){
    $login_ok = true;
    $_SESSION['email_login'] = $post_username;

    if($post_autologin == 1){
        $password_hash = md5($post_password); // will result in a 32 characters hash
        setcookie ($cookie_name, 'usr='.$post_username.'&hash='.$password_hash, time() + $cookie_time);
    }

    header("Location: index.php");
    exit;
}else{
    echo "<div id='snackbar'>Email or password incorrect</div>";
    ?>
    <script type="text/javascript">
        snackbar();
        function snackbar() {
            var x = document.getElementById("snackbar");
            x.className = "show";
            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
        }
    </script>
    <?php
}
?>