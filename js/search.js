var seats = $('seats');
var departure = $('departure');
var destination = $('destination');
var departureDate = $('departureDate');
var arrivalDate = $('arrivalDate');

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

// if ($("input[type=date]")){
//     $$("input[type=date]").forEach(function(element) {
//         element.observe('click', function(){
//             check_date(element);
//         });
//         element.observe('change', function(){
//             check_date(element);
//         });
//     });
// }

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
       /* var elements =  $('search-form').getElements();
        var hasError = elements.find(function(element) {
            return element.classList.contains("error-input");
        });
        if (hasError) e.stop();**/
        return true;
    };
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
        }
        
        return true;
    };
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

function removeFormError(form){
    if ($("errorForm")) $("errorForm").style.visibility = 'hidden';
}


// function showError(element, message){
    
//     var found = element.getElementsByClassName("tooltiptext");
//     if (found.length == 0){
//         var span = document.createElement("span");
//         //element.classList.add("tooltip");
//         span.classList.add("tooltiptext");
//         span.innerText = message;
//         element.insert(span);
//     }
    
/* <div class="tooltip">Hover over me
  <span class="tooltiptext">Tooltip text</span>
</div> */