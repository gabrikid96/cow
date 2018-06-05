<?php
include('header.php');
?>

<div class="container text-center">
    <h2>Profile image</h2>
    <div class="col-md-3 text-center" >
        <img class="img-responsive" ondragover="allowDrop(event)" ondrop="drop(event)" class="icon" border="0" src="img/profile-image.png">

    </div>
    <div class="row">
        <div class="col-sm-1 text-center">
            <img class="img-responsive" class="icon" border="0" onclick="imageClick(event)" src="img/minion.jpg" draggable="true" ondragstart="drag(event)">
        </div>
        <div class="col-sm-1 text-center">
            <img class="img-responsive" class="icon" border="0" onclick="imageClick(event)" src="img/vader.jpg" draggable="true" ondragstart="drag(event)">
        </div>
        <div class="col-sm-1 text-center">
            <img class="img-responsive" class="icon" border="0" onclick="imageClick(event)" src="img/gustavo.jpeg" draggable="true" ondragstart="drag(event)">
        </div>
        <div class="col-sm-1 text-center">
            <img class="img-responsive" class="icon" border="0" onclick="imageClick(event)" src="img/hulk.jpg" draggable="true" ondragstart="drag(event)">
        </div>
        <div class="col-sm-1 text-center">
            <img class="img-responsive" class="icon" border="0" onclick="imageClick(event)" src="img/ironman.jpg" draggable="true" ondragstart="drag(event)">
        </div>
        <div class="col-sm-1 text-center">
            <img class="img-responsive" class="icon" border="0" onclick="imageClick(event)" src="img/american.jpg" draggable="true" ondragstart="drag(event)">
        </div>
        <div class="col-sm-1 text-center">
            <img class="img-responsive" class="icon" border="0" onclick="imageClick(event)" src="img/minecraft.jfif" draggable="true" ondragstart="drag(event)">
        </div>
        <div class="col-sm-1 text-center">
            <img class="img-responsive" class="icon" border="0" onclick="imageClick(event)" src="img/pikachu.png" draggable="true" ondragstart="drag(event)">
        </div>
        
    </div>
    <div class="row">
        <audio id="audio_source" controls>
        </audio> 
    </div>
    <br>
    <div class="row">
        <div id="geolocation" class="col-md-6 text-center">
            <h2>Location</h2>
            <div id="demo"></div>
            <iframe width="400" height="400" id="map" src="https://maps.google.com/maps?q=Universitat de Barcelona&t=&z=13&ie=UTF8&iwloc=&output=embed" 
                frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
        </div>
        <div class="col-md-6">
        <h2>Video</h2>
        <video width="400" height="400" controls>
            <source src="./img/movie.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

</div>

<script src="js/html5.js"></script>
<?php
include('footer.html');
?>
        