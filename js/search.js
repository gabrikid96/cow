var seats = $('seats');
var departure = $('departure');
var destination = $('destination');
var departureDate = $('departureDate');
var arrivalDate = $('arrivalDate');

document.observe("dom:loaded", function() {
    if ($(continent)){
        new Ajax.Request("get_data.php", {
            method: "get",
            parameters: {get_continents: "true"},
            onSuccess: function(ajax){
                if (ajax.readyState == 4 && ajax.status == 200) {
                    var continents = ajax.responseText;
                    var select = $(continent);
                    select.innerHTML += continents;
                }
            }
            });
    }

    if ($('flights-form')){
        searchFlights($('flights-form'));
    }

    if (departure && departure.nodeName == "SELECT" && destination && destination.nodeName == "SELECT"){
        new Ajax.Request("get_data.php", {
            method: "get",
            parameters: {get_cities: "true"},
            onSuccess: function(ajax){
                if (ajax.readyState == 4 && ajax.status == 200) {
                    var cities = ajax.responseText;
                    departure.innerHTML += cities;
                    destination.innerHTML += cities;
                }
            }
        });
    }
});

if (seats){
    seats.onkeyup = () =>{
        check_seats();
    }
}

$$("input[type=email]").forEach(function(element){
    element.onkeyup = () => {
        check_email(element);
    }
    element.observe('click', function(){
        check_email(element);
    });
});

$$("input[type=text]").forEach(function(element){
    element.onkeyup = () => {
        check_select(element);
    }
    element.observe('click', function(){
        check_select(element);
    });
});

if ($$("select")){
    $$("select").forEach(function(element) {
        element.observe('click', function(){
            check_select(element);
        });
    });
}

if (departureDate){
    departureDate.observe('click', function(){
        check_date(departureDate);
        if (new Date($F(arrivalDate)) != "Invalid Date"){
            check_dates(departureDate, arrivalDate);
        }
    });

    departureDate.observe('change', function(){
        check_date(departureDate);
        if (new Date($F(arrivalDate)) != "Invalid Date"){
            check_dates(departureDate, arrivalDate);
        }
    });
}

if (arrivalDate){
    arrivalDate.observe('click', function(){
        check_date(arrivalDate);
        if (new Date($F(departureDate)) != "Invalid Date"){
            check_dates(departureDate, arrivalDate);
        }
    });

    arrivalDate.observe('change', function(){
        check_date(arrivalDate);
        if (new Date($F(departureDate)) != "Invalid Date"){
            check_dates(departureDate, arrivalDate);
        }
    });
}

if ($('search-form')){
    $('search-form').onsubmit =  function(e) { 
        check_select(departure);
        check_select(destination);
        check_seats();
        check_email($('email'));
        check_date(departureDate);
        check_date(arrivalDate);
        if (new Date($F(departureDate)) != "Invalid Date" && new Date($F(arrivalDate)) != "Invalid Date")
            check_dates(departureDate, arrivalDate);
        searchFlights(this);
        return false;
    };   
}
function searchFlights(form){
    new Ajax.Request("get_data.php", {
        method: "get",
        parameters: {search_flights: "true", departure: $F(departure), destination: $F(destination), departureDate: $F(departureDate), arrivalDate: $F(arrivalDate), seats: $F(seats)   },
        onSuccess: function(ajax){
            if (ajax.readyState == 4 && ajax.status == 200) {
                var flights = ajax.responseText;
                if ($('flights')){
                    $('flights').innerHTML = flights;
                }else{
                    var flightsDiv = document.createElement("DIV");
                    flightsDiv.id = "flights";
                    flightsDiv.innerHTML = flights;
                    form.parentNode.appendChild(flightsDiv);
                }                
            }else{
                createFormError(form, "Error on search");
            }
        }
        });
        return false;
}     



if ($('cities-form')){
    $('cities-form').onsubmit =  function(e) { 
        searchCities(this);
        return false;
    };    
    function searchCities(form){
        var city = $("city");
        var country = $("country");
        var country_code = $("country-code");
        var continent = $("continent");
        new Ajax.Request("get_data.php", {
            method: "get",
            parameters: {search_cities: "true", city_search: $F(city), country: $F(country), country_code: $F(country_code), continent: $F(continent)},
            onSuccess: function(ajax){
                if (ajax.readyState == 4 && ajax.status == 200) {
                    var cities = ajax.responseText;
                    if ($('cities_result')){
                        $('cities_result').innerHTML = cities;
                    }else{
                        var citiesDiv = document.createElement("DIV");
                        citiesDiv.id = "cities_result";
                        citiesDiv.innerHTML = cities;
                        form.parentNode.appendChild(citiesDiv);
                    }                
                }else{
                    createFormError(form, "Error on search");
                }
            }
            });
            return false;
    }
       
}


if ($('flights-form')){
    $('flights-form').onsubmit =  function(e) { 
        check_select(departure);
        check_select(destination);
        check_seats();
        check_date(departureDate);
        check_date(arrivalDate);
        if (new Date($F(departureDate)) != "Invalid Date" && new Date($F(arrivalDate)) != "Invalid Date")
            check_dates(departureDate, arrivalDate);
        var elements =  $('flights-form').getElements();
        var hasError = elements.find(function(element) {
            return element.classList.contains("error-input");
        });
        if (hasError){
            e.stop();
            createFormError($('flights-form'), "Departure date must be before return date.");
        }else{
            createFlight(this);
            
        }
        
        return false;
    };
    function createFlight(form){
        new Ajax.Request("get_data.php", {
            method: "post",
            parameters: {create_flight: "true", departure: $F(departure), destination: $F(destination), departureDate: $F(departureDate), arrivalDate: $F(arrivalDate), seats: $F(seats)   },
            onSuccess: function(ajax){
                if (ajax.readyState == 4 && ajax.status == 200) {
                    var result = ajax.responseText;
                    if (result == "1"){
                        createFormSuccess(form, "Flight added succesfully.");
                        form.reset();
                        check_select(departure);
                        check_select(destination);
                        check_seats();
                        check_date(departureDate);
                        check_date(arrivalDate);
                        searchFlights(form);
                    }else{
                        createFormError(form, "Error on insert.");
                    }
                    
                }else{
                    createFormError(form, "Error on insert.");
                }
            }
            });
            return false;
    }  
}


function hasFormError(form){
    var hasError = form.getElements().find(function(element) {
        return element.classList.contains("error-input");
    });
    return hasError === undefined;
}


function check_select(element){
    var value = $F(element);
    if (value === ""){ setError(element);return;}
    success(element);
}

function check_date(element){
    var value = new Date($F(element));
    if (value == "Invalid Date"){ setError(element);return;}
    success(element);
}

function check_dates(elementDate1, elementDate2){
    date1 = new Date($F(elementDate1));
    date2 = new Date($F(elementDate2));
    if (date1 > date2){
        setError(elementDate1);
        setError(elementDate2);
        showFormError($("search-form"), "Departure date must be before return date.");        
    }else{
        success(elementDate1);
        success(elementDate2);
        removeFormError($("search-form"));
    }
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

function showFormError(form, message){
    var errorForm = $("errorForm");
    if (errorForm){ 
        errorForm.innerText = message;
        errorForm.style.visibility = 'visible';
    }
}

function createFormError(form, message){
    var error = document.createElement("DIV");
    error.classList.add("alert", "alert-danger", "text-center", "col-md-6", "col-md-offset-3");
    error.style = "margin-top: 10px";
    error.setAttribute("role","alert");
    error.innerText = message;
    form.appendChild(error);
}

function createFormSuccess(form, message){
    var error = document.createElement("DIV");
    error.classList.add("alert", "alert-success", "text-center", "col-md-6", "col-md-offset-3");
    error.style = "margin-top: 10px";
    error.setAttribute("role","alert");
    error.innerText = message;
    form.appendChild(error);
}

function removeFormError(form){
    if ($("errorForm")) $("errorForm").style.visibility = 'hidden';
}
