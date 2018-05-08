// function autocomplete(input, cities) {
var currentFocus;
var departureInput = $('#departure');
var destinationInput = $('#destination');
var city = $('#city');

function closeAutocomplete(input, elmnt) {
    $(".autocomplete-items").each(function(element){
    if (elmnt != $(this)[0] && elmnt != input) {
        $(this)[0].parentNode.removeChild($(this)[0]);
        }
    });
}

if (departureInput.length){
    departureInput.bind('keyup',function(event){
        $.get("get_data.php", 
        {city: departureInput.val()},
        function(data,status){
            showAutocomplete_json(data,status, departureInput);
        });

        
    });
    $(document).bind('click', function(event){
        closeAutocomplete(departureInput, event.target);
    });
}

if (destinationInput.length){
    destinationInput.bind('keyup',function(event){
        $.get("get_data.php", 
        {city: destinationInput.val()},
        function(data,status){
            showAutocomplete_json(data,status, destinationInput);
        });
    });
    $(document).bind('click', function(event){
        closeAutocomplete(destinationInput, event.target);
    });
}

if (city.length){
    city.bind('keyup',function(event){
        $.get("get_data.php", 
        {city: city.val()},
        function(data,status){
            showAutocomplete_json(data,status, city);
        });
    });
    
    $(document).bind('click', function(event){
        closeAutocomplete(city, event.target);
    });
}

function showAutocomplete(data,status, input){
    if (status == "success") {
        var cities = data;
        if (cities){
            departures = cities.split(",");
            autocomplete(input, departures.slice(0,5));
        }else{
            closeAutocomplete(input[0]);
        }
    }        
}

function autocomplete(input, cities){
    var occurrences, city, index, val = input.val();
    closeAutocomplete(input[0]);
    currentFocus = -1;
    occurrences = document.createElement("DIV");
    occurrences.id = "autocomplete-list";
    occurrences.classList.add("autocomplete-items");
    input[0].parentNode.appendChild(occurrences);
    if (cities){
        cities.forEach(function(element) {
            if (element.substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                city = document.createElement("DIV");
                city.innerHTML = "<strong>" + element.substr(0, val.length) + "</strong>";
                city.innerHTML += element.substr(val.length);
                city.innerHTML += "<input type='hidden' value='" + element + "'>";
                city.onclick = function(e){
                    input.val(this.lastChild.value); //= $F(this.select("input")[0]);
                    closeAutocomplete(input[0]);
                };
                occurrences.appendChild(city);
            }
        });
    }
}

function showAutocomplete_json(data,status, input){
    if (status == "success") {
        var cities = data;
        if (cities){
            autocomplete_json(input, data);
        }else{
            closeAutocomplete(input[0]);
        }
    }        
}

function autocomplete_json(input, cities){
    var occurrences, city, text, val = input.val();
    closeAutocomplete(input[0]);
    currentFocus = -1;
    occurrences = $("<div>");
    occurrences.attr("id","autocomplete-list");
    occurrences.addClass("autocomplete-items");
    occurrences.appendTo(input.parent());
    if (cities){
        $.each(JSON.parse(cities).slice(0,5), function(i, item) {
            if (item["name"].substr(0, val.length).toUpperCase() == val.toUpperCase()){
                city = $("<div>");
                city.append($("<strong>" + item["name"].substr(0, val.length) + "</strong>"));
                city.text(city.text() + item["name"].substr(val.length));
                city.append($("<input type='hidden' value='" + item["name"] + "'>"));
                city.click(function() {
                    input.val($(this).find("input").val());
                    closeAutocomplete(input[0]);
                });
                occurrences.append(city);
            }
        });
    }
}

    


    