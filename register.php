<!DOCTYPE html, php>
<?php
include('header.php');
?>
    <h1 class="text-center">Register</h1>
    <div class="box container text-center" style="margin-top: 50px;">
        <form>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <label for="name">Name</label>
                        <input class="form-control" type="text" id="name" name="name" required placeholder="Your name">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <label for="email">Email</label>
                        <input class="form-control" type="text" id="email" name="email" pattern="^[a-z0-9._%+-]+@[a-z0-9]+\.[a-z]{2,4}$" required
                            placeholder="Your email">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <label for="password">Password</label>
                        <input class="form-control" type="password" id="password" name="password" required placeholder="Your password">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <label for="cpassword">Confirm Password</label>
                        <input class="form-control" type="cpassword" id="cpassword" name="cpassword" required placeholder="Confirm password">
                    </div>
                </div>
                <br>

                <br>
                <div class="text-center">
                    <button class="btn btn-success">Register</button>
                </div>
            </div>
        </form>
    </div>

<?php
include('footer.html');
?>