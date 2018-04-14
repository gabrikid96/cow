// function autocomplete(input, cities) {
var currentFocus;
var departureInput = $('departure');
var destinationInput = $('destination');
var city = $('city');

function closeAutocomplete(input, elmnt) {
    $$(".autocomplete-items").forEach(function(element){
    if (elmnt != element && elmnt != input) {
        element.parentNode.removeChild(element);
        }
    });
}

if (departureInput){
    departureInput.onkeyup = (e) =>{
    new Ajax.Request("get_data.php", {
        method: "get",
        parameters: {city: $F(departureInput)},
        onSuccess: function(ajax){
            showAutocomplete(ajax, departureInput)
        }
        });
    }
    document.observe('click', function(e){
        closeAutocomplete(departureInput, e.target);
    });
}

if (destinationInput){
    destinationInput.onkeyup = (e) =>{
    new Ajax.Request("get_data.php", {
        method: "get",
        parameters: {city: $F(destinationInput)},
        onSuccess: function(ajax){
            showAutocomplete(ajax, destinationInput)
        }
        });
    }
    document.observe('click', function(e){
        closeAutocomplete(destinationInput, e.target);
    });
}

if (city){
    city.onkeyup = (e) =>{
    new Ajax.Request("get_data.php", {
        method: "get",
        parameters: {city: $F(city)},
        onSuccess: function(ajax){
            showAutocomplete(ajax, city)
        }
        });
    }
    document.observe('click', function(e){
        closeAutocomplete(city, e.target);
    });
}

function showAutocomplete(ajax, input){
    if (ajax.readyState == 4 && ajax.status == 200) {
        var cities = ajax.responseText;
        if (cities){
            departures = cities.split(",");
            autocomplete(input, departures.slice(0,5));
        }else{
            closeAutocomplete(input);
        }
    }        
}

function autocomplete(input, cities){
    var occurrences, city, index, val = $F(input);
    closeAutocomplete(input);
    currentFocus = -1;
    occurrences = document.createElement("DIV");
    occurrences.id = "autocomplete-list";
    occurrences.classList.add("autocomplete-items");
    input.parentNode.appendChild(occurrences);
    if (cities){
        cities.forEach(function(element) {
            if (element.substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                city = document.createElement("DIV");
                city.innerHTML = "<strong>" + element.substr(0, val.length) + "</strong>";
                city.innerHTML += element.substr(val.length);
                city.innerHTML += "<input type='hidden' value='" + element + "'>";
                city.observe('click', function(e){
                    input.value = $F(this.select("input")[0]);
                    closeAutocomplete(input);
                });
                occurrences.appendChild(city);
            }
        });
    }
}

    


    