function drop(ev){
    ev.preventDefault();
    var data=ev.dataTransfer.getData("src");
    ev.target.src = data;
}

function drag(ev){
    ev.dataTransfer.setData("src",ev.target.src);
}

function allowDrop(ev){
    ev.preventDefault();
}

var x=document.getElementById("demo");
getLocation();
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition,showError);
    }
    else{
        x.innerHTML="Geolocation is not supported by this browser.";
    }
}
function showPosition(position){
    var map = document.getElementById("map");
    //https://maps.google.com/maps?q=Universitat de Barcelona&t=&z=13&ie=UTF8&iwloc=&output=embed
    map.src = "https://maps.google.com/maps?q=" + position.coords.latitude + "," + position.coords.longitude + "&t=&z=13&ie=UTF8&iwloc=&output=embed"
    x.innerHTML="Latitude: " + position.coords.latitude +
    "<br>Longitude: " + position.coords.longitude;
}

function showError(error)
{
    switch(error.code)
    {
        case error.PERMISSION_DENIED:
        x.innerHTML="User denied the request for Geolocation."
        break;
        case error.POSITION_UNAVAILABLE:
        x.innerHTML="Location information is unavailable."
        break;
        case error.TIMEOUT:
        x.innerHTML="The request to get user location timed out."
        break;
        case error.UNKNOWN_ERROR:
        x.innerHTML="An unknown error occurred."
        break; 
    } 
}

function imageClick(event){
    var target = event.target;
    var fullPath = target.src;
    var filename = fullPath.replace(/^.*[\\\/]/, '').split('.')[0];
    console.log(filename);
    play(filename);
}

function play(src){
    var audio = document.getElementById("audio_source");
    audio.src = "./audio/" + src + ".mp3";
    audio.play(); 
}

