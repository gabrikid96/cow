var seats = $('#seats');
var departure = $('#departure');
var destination = $('#destination');
var departureDate = $('#departureDate');
var arrivalDate = $('#arrivalDate');

$(document).ready(function() {
    if ($('#continent').length){
        $.get("get_data.php", {get_continents: "true"}, function(data,status){
            if(status=="success"){
                var continents = data;
                var select = $(continent)[0];
                select.innerHTML += continents;
            }
        });
    }

    if ($('#flights-form').length){
        searchFlights($('#flights-form'));
    }

    if (departure.length && departure[0].nodeName == "SELECT" && destination.length && destination[0].nodeName == "SELECT"){
        $.get("get_data.php", {get_cities: "true"}, function(data,status){
            if(status=="success"){
                var cities = data;
                jsonToOptions(data, departure);
                jsonToOptions(data, destination);
               /* departure[0].innerHTML += cities;
                destination[0].innerHTML += cities;*/
            }
        });
    }
});

if ($('#seats').length){
    $('#seats').bind('keyup', function(){
        check_seats();
    });
}

$("input[type=email]").each(function(element){
    $(this).bind('keyup', function(){
        check_email($(this));
    });
    $(this).bind('click', function(){
        check_email($(this));
    });
});

$("input[type=text]").each(function(element){
    $(this).bind('keyup', function(){
        check_select($(this));
    });
    $(this).bind('click', function(){
        check_select($(this));
    });
});

if ($("select").length){
    $("select").each(function(element) {
        $(this).bind('click', function(){
            check_select( $(this));
        });
    });
}

if ($('#departureDate').length){
    $('#departureDate').bind('click', function(){
        check_date($(this));
        if (new Date($('#arrivalDate').val()) != "Invalid Date"){
            check_dates($(this), $('#arrivalDate'));
        }
    });

    $('#departureDate').bind('change', function(){
        check_date($(this));
        if (new Date($('#arrivalDate').val()) != "Invalid Date"){
            check_dates($(this), $('#arrivalDate'));
        }
    });
}

if ($('#arrivalDate').length){
    $('#arrivalDate').bind('click', function(){
        check_date($(this));
        if (new Date($('#departureDate').val()) != "Invalid Date"){
            check_dates($('#departureDate'), $(this));
        }
    });

    $('#arrivalDate').bind('change', function(){
        check_date($(this));
        if (new Date($('#departureDate').val()) != "Invalid Date"){
            check_dates($('#departureDate'), $(this));
        }
    });
}
c = {};
if ($('#search-form').length){
    $('#search-form').submit(function (){
        check_select($('#departure'));
        check_select($('#destination'));
        check_seats();
        check_email($('#email'));
        check_date($('#departureDate'));
        check_date($('#arrivalDate'));
        if (new Date(departureDate.val()) != "Invalid Date" && new Date(arrivalDate.val()) != "Invalid Date")
            check_dates(departureDate, arrivalDate);
        searchFlights($(this), true);
        
        //addShoppingCart();
        return false;
    }); 
}
function searchFlights(form, shoppingCart){
    var type = $("#type").length ? $("#type").val() : "json";
    $.get("get_data.php", 
    {search_flights: "true", departure: $('#departure').val(), destination: $('#destination').val(), departureDate: $('#departureDate').val(), arrivalDate: $('#arrivalDate').val(), seats: $('#seats').val(), type: type},
    function(data,status){
        if(status=="success"){
            var table = type.indexOf("json") > -1 ? jsonToTable(data, ['Departure city', 'Arrival city', 'Departure date ', 'Return date', 'Seats available']) : xmlToTable(data, ['Departure city', 'Arrival city', 'Departure date ', 'Return date', 'Seats available']);
            table.attr("id","flights");
            
            if ($('#flights').length){
                $('#flights').replaceWith(table);
            }else{
                table.appendTo(form.parent());
            }       
            if (shoppingCart) addShoppingCart(table);
        }else{
            createFormError(form[0], "Error on search");
        }
    });
    return false;
}     



if ($('#cities-form').length){
    $('#cities-form').submit(function (){
        searchCities($(this));
        return false;
    });  
    function searchCities(form){
        var city = $("#city");
        var country = $("#country");
        var country_code = $("#country-code");
        var continent = $("#continent");
        $.get("get_data.php", 
        {search_cities: "true", city_search: city.val(), country: country.val(), country_code: country_code.val(), continent: continent.val()},
        function(data,status){
            if(status=="success"){
                var cities = data;
                var table = jsonToTable(data, ['City', 'Country', 'Country Code ', 'Continent']);
                table.attr("id","cities_result");
                if ($('#cities_result').length){
                    $('#cities_result').replaceWith(table);
                }else{
                    table.appendTo(form.parent());
                }                
            }else{
                createFormError(form[0], "Error on search");
            }
        });
            return false;
    }       
}


if ($('#flights-form').length){
    $('#flights-form').submit(function (){
        check_select(departure);
        check_select(destination);
        check_seats();
        check_date(departureDate);
        check_date(arrivalDate);
        if (new Date(departureDate.val()) != "Invalid Date" && new Date(arrivalDate.val()) != "Invalid Date")
            check_dates(departureDate, arrivalDate);

        var hasError = $('#flights-form').find('.error-input').length > 0;
        if (hasError){
            createFormError($('#flights-form')[0], "Departure date must be before return date.");
        }else{
            createFlight($(this));  
        }
        
        return false;
    });
    function createFlight(form){
        $.post("get_data.php", {create_flight: "true", departure: departure.val(), destination: destination.val(), departureDate: departureDate.val(), arrivalDate: arrivalDate.val(), seats: seats.val()   },
        function(data,status){
            if(status=="success"){
                var result = data;
                if (result == "1"){
                    createFormSuccess(form[0], "Flight added succesfully.");
                    form[0].reset();
                    check_select(departure);
                    check_select(destination);
                    check_seats();
                    check_date(departureDate);
                    check_date(arrivalDate);
                    searchFlights(form);
                }else{
                    createFormError(form[0], "Error on insert.");
                }
                
            }else{
                createFormError(form[0], "Error on insert.");
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
    var value = element.val();
    if (value === ""){ setError(element);return;}
    success(element);
}

function check_date(element){
    var value = new Date(element.val());
    if (value == "Invalid Date"){ setError(element);return;}
    success(element);
}

function check_dates(elementDate1, elementDate2){
    date1 = new Date(elementDate1.val());
    date2 = new Date(elementDate2.val());
    if (date1 > date2){
        setError(elementDate1);
        setError(elementDate2);
        showFormError($("#search-form"), "Departure date must be before return date.");        
    }else{
        success(elementDate1);
        success(elementDate2);
        removeFormError($("#search-form"));
    }
}

function check_seats(){
    var num_seats = +$('#seats').val();
    var max = +$('#seats').attr("max");
    var min = +$('#seats').attr("min")
    if (isNaN(num_seats) || num_seats > max || num_seats < min){
        setError($('#seats'));
        return;
    }
    success($('#seats'));
}

function check_email(element){
    var regex = /[a-z0-9._%+-]+@[a-z0-9]+\.[a-z]{2,4}$/;
    var isValid = regex.test(element.val());
    if (isValid) success(element);
    else setError(element);
}

function setError(element){
    if(!element.hasClass("error-input")){
        element.addClass("error-input");
    }

    if(element.hasClass("success-input")){
        element.removeClass("success-input");
    }
}

function removeError(element){
    if(element.hasClass("error-input")){
        element.removeClass("error-input");
    }
}

function success(element){
    removeError(element);
    if(!element.hasClass("success-input")){
        element.addClass("success-input");
    }
}

function showFormError(form, message){
    var errorForm = $("#errorForm");
    if (errorForm.length){ 
        errorForm[0].innerText = message;
        errorForm[0].style.visibility = 'visible';
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
    if ($("#errorForm").length) $("#errorForm")[0].style.visibility = 'hidden';
}

function jsonToTable(json, header){
    var container = $("<div class='container text-center'></div>");
    var table = $("<table id='flights_table' class='table table-striped'>");
    var trh = $('<tr>');
    $.each(header, function(i, item) {
        var td = $('<td>').text(item);
        td.appendTo(trh);
    });
    var thead = $('<thead>');
    trh.appendTo(thead);
    thead.appendTo(table);
    var tbody = $('<tbody>');
    $.each(JSON.parse(json), function(i, item) {
        var tr = $('<tr>');
            $.each(item, function(i2, item2){
                $('<td>').text(item2).appendTo(tr);
            });
        tr.appendTo(tbody);
    });
    tbody.appendTo(table);
    table.appendTo(container);
    return container;
}

function xmlToTable(xml, header){
    /*var container = $("<div class='container text-center'></div>");
	var tabla = $("<table id='flights_table' class='table table-striped'>");
	var tbody = $(document.createElement("tbody"));
	var tr = $(document.createElement("tr"));

	$.each(xml.firstChild.attributes,function(key,value){
		var th = crearTagTh(value.localName);
	    tr.append(th);
	});
	tbody.append(tr);
	$.each(xml, function(key,objeto){
		var tr = $(document.createElement("tr"));
		$.each(objeto.attributes,function(key,atributo){
			var td = crearTagTd(objeto.getAttribute(atributo.localName));
		    tr.append(td);
			
		});
		tbody.append(tr);
	});
    tabla.append(tbody);
    container.append(tabla);
	return container;*/
}
function jsonToOptions(json, select){
    $.each(JSON.parse(json), function(i, item) {
        var option = $("<option>");
        option.text(item["name"]);
        option.attr("value", item["id"]);
        option.appendTo(select);
    });
}

function addShoppingCart(table){ 
    table.find('thead tr').prepend("<td>Add to cart</td>");
    table.find('tbody tr').each(function(){
        $(this).prepend("<td><div class='ui-widget-content text-center'><i class='fas fa-plane'></i></div></td>");
    });
   $("#flights_table").find("tbody").find("div").draggable({ revert: true, 
    start: function( event, ui ) {
        if (!$("#shopCart").length){
            var button = "<div id='shopCart' class='btn btn-info btn-lg text-center'><span class='glyphicon glyphicon-shopping-cart'></span>Shopping Cart (0)</div>";
            var test = $(button);
            test.data("num", 0);
            $("#flights_table").before(test);
            $( "#shopCart" ).droppable({
                activeClass: "ui-state-default",
                hoverClass: "btn-success",
                accept: ":not(.ui-sortable-helper)",
                drop: function( event, ui ) {
                    runEffect($(this), "bounce");
                    $(this).data("num", test.data("num") + 1);
                    $(this).text("Shopping Cart (" + test.data("num") + ")");
                    $("<span class='glyphicon glyphicon-shopping-cart'></span>").prependTo($(this));
                }
              });
        }                        
    } });      
}

function runEffect(element, effect) {
    // Most effect types need no options passed by default
    var options = {};
    // some effects have required parameters
    if ( effect === "scale" ) {
      options = { percent: 50 };
    } else if ( effect === "transfer" ) {
      options = { to: "#button", className: "ui-effects-transfer" };
    } else if ( effect === "size" ) {
      options = { to: { width: 200, height: 60 } };
    }

    // Run the effect
    element.effect( effect, options, 500 );
  }