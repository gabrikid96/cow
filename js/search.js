var seats = $('seats');
var departure = $('departure');
var destination = $('destination');

seats.onkeyup = () =>{
    check_seats();
}

$$("input[type=email]").forEach(function(element){
    element.onkeyup = () => {
        check_email(element);
    }
});

//departure.addEventListener("change", check_select;
$$("select").forEach(function(element) {
    element.observe('click', function(){
        check_select(element);
    });
});


function check_select(element){
    var value = parseInt($F(element));
    if (isNaN(value)){ setError(element);return;}
    success(element);
    
}

function check_seats(){
    var num_seats = parseInt($F(seats));
    var max = parseInt(seats.max);
    var min = parseInt(seats.min);
    if (isNaN(num_seats) || num_seats > max || num_seats < min){
        setError(seats);
        return;
    }
    success(seats);
}

function check_email(element){
    var regex = /[a-z0-9._%+-]+@[a-z0-9]+\.[a-z]{2,4}$/;
    var isValid = regex.test($F(element));
    if (isValid) success(element);
    else setError(element);
}

$('search-form').observe('submit', function(e) { 
    alert('submitted!');
    // e.stop();
});

function setError(element){
    if (!element.classList.contains("error-input"))element.classList.add("error-input");
    if (element.classList.contains("success-input"))element.classList.remove("success-input");
}

function removeError(element){
    if (element.classList.contains("error-input"))element.classList.remove("error-input");
}

function success(element){
    removeError(element);
    if (!element.classList.contains("success-input"))element.classList.add("success-input");
}