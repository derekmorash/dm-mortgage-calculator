/* containers */
var dmForm = document.getElementById('dm-form-container');
var dmChart = document.getElementById('dm-chart-container');

/* buttons */
var dmSubmit = document.getElementById('dm-submit');

/* regex */
var dmAmountRegex = /^\d+(\.\d{2})?$/;
var dmRateRegex = /^\d+(\.\d{2})?$/;
var dmTermRegex = /^\d{1,2}$/;

/* animation event listener array */
var endAnimation = ['webkitAnimationEnd', 'mozAnimationEnd', 'MSAnimationEnd', 'oanimationend', 'animationend'];

/* chart values */
var dmPrinciple;
var dmInterest;

/* Empty boxes error message */
var dmEmptyError = document.getElementById('dm-empty'); //made this global because it is used multiple times

/* when the form submit button is clicked */
function submitForm() {
    dmSubmit.onclick = function() {
        /* get values from input boxes */
        var dmAmount = document.getElementById('dm-amount').value;
        var dmRate = document.getElementById('dm-rate').value;
        var dmTerm = document.getElementById('dm-term').value;
        var dmPaymentFrequency = document.getElementById('dm-payment-frequency').value;

        var dmAmountValidate = validateAmount(dmAmount); //validated the amount input
        var dmRateValidate = validateRate(dmRate); //validate the rate input
        var dmTermValidate = validateTerm(dmTerm); //validate the term input

        if(dmAmountValidate === true && dmRateValidate === true && dmTermValidate === true) {

            /* Set chart values */
            dmPrinciple = Number(dmAmount); //Set the global principle variable for the chart

            /* Calculate Payment */
            dmCalculatePayment(dmAmount, dmRate, dmTerm, dmPaymentFrequency);

            /* Animations */
            dmForm.className = "dm-form-container animated fadeOutDown"; // adds the animation classes to remove container

            /*
            * Uses the 'one' function to check if animation has happened.
            * If the animation has happened then the function
            * removes the animation classes and adds the hidden class.
            * Need to do one for each vendor prefix for full browser support.
            */
            for (var i = 0; i < endAnimation.length; i++) { //loop through each vendor prefix
                one(dmForm, endAnimation[i], function(event) {
                    dmForm.className = "dm-form-container dm-hidden"; //hide the form container
                    dmChart.className = "dm-chart-container animated fadeInDown"; //animate the chart container to come into view
                    drawChart(); //draw the chart
                });
            } //end for loop
        } //end validate if
    } //end on click
} //end submit form function

/* checks if event has happened or not */
function one(element, eventName, callback) {
  element.addEventListener(eventName, function handler(event) {
    element.removeEventListener(eventName, handler);
    callback(event);
  });
}

/* calculate monthly payment */
function dmCalculatePayment (amount, rate, term, frequency) {
    var monthlyPayment;
    var yearlyPayment;
    var monthlyRate = (Number(rate) / 100) / 12; //calculate monthly interest rate
    var numMonths = Number(term) * 12; //calculate the number of months
    amount = Number(amount);

    // calculate monthly payment
    monthlyPayment = amount * ((monthlyRate * (Math.pow((1 + monthlyRate), numMonths))) / (Math.pow((1 + monthlyRate), numMonths) - 1));

    //calculation depending on frequency eg. monthly, weekly, etc
    if(frequency === 'monthly') {
        //display monthly payment
        document.getElementById('dm-monthly-payment').innerHTML = '<span class="dm-bold">$'+monthlyPayment.toFixed(2)+'</span> per month';

        //multiply the monthly payment by the number of months
        yearlyPayment = monthlyPayment * (numMonths);
        //display overall payment
        document.getElementById('dm-overall-payment').innerHTML = '$'+yearlyPayment.toFixed(2);

        //get the amount of interest to be payed
        dmInterest = yearlyPayment - amount;
        dmInterest = Number(dmInterest.toFixed(2));
    } else if(frequency === 'semimonthly') {
        var semiMonthlyPayment = monthlyPayment/2;

        //display semi-monthly payment
        document.getElementById('dm-monthly-payment').innerHTML = '<span class="dm-bold">$'+semiMonthlyPayment.toFixed(2)+'</span> twice per month';

        //multiply the monthly payment by the number of months
        yearlyPayment = monthlyPayment * (numMonths);
        //display overall payment
        document.getElementById('dm-overall-payment').innerHTML = '$'+yearlyPayment.toFixed(2);

        //get the amount of interest to be payed
        dmInterest = yearlyPayment - amount;
        dmInterest = Number(dmInterest.toFixed(2));
    } else if(frequency === 'weekly') {
        var weeklyPayment = (monthlyPayment * 12) / 52;

        //display weekly payment
        document.getElementById('dm-monthly-payment').innerHTML = '<span class="dm-bold">$'+weeklyPayment.toFixed(2)+'</span> per week';

        //multiply the monthly payment by the number of months
        yearlyPayment = monthlyPayment * (numMonths);
        //display overall payment
        document.getElementById('dm-overall-payment').innerHTML = '$'+yearlyPayment.toFixed(2);

        //get the amount of interest to be payed
        dmInterest = yearlyPayment - amount;
        dmInterest = Number(dmInterest.toFixed(2));
    } else if(frequency === 'biweekly') {
        var biweeklyPayment = (monthlyPayment * 12) / 26;

        //display biweekly payment
        document.getElementById('dm-monthly-payment').innerHTML = '<span class="dm-bold">$'+biweeklyPayment.toFixed(2)+'</span> twice per week';

        //multiply the monthly payment by the number of months
        yearlyPayment = monthlyPayment * (numMonths);
        //display overall payment
        document.getElementById('dm-overall-payment').innerHTML = '$'+yearlyPayment.toFixed(2);

        //get the amount of interest to be payed
        dmInterest = yearlyPayment - amount;
        dmInterest = Number(dmInterest.toFixed(2));
    } else if(frequency === 'weeklyAccel') {
        var weeklyAccelPayment = (monthlyPayment / 4);
        var yearlyAccelPayment;

        //display weekly payment
        document.getElementById('dm-monthly-payment').innerHTML = '<span class="dm-bold">$'+weeklyAccelPayment.toFixed(2)+'</span> per week';

        //multiply the monthly payment by the number of months
        yearlyPayment = monthlyPayment * (numMonths);

        //multiply the payment by the number of payments per year (52)
        yearlyAccelPayment = (Number(weeklyAccelPayment) * 52) * term;

        //display overall payment
        document.getElementById('dm-overall-payment').innerHTML = '$'+yearlyAccelPayment.toFixed(2);

        //get the amount of interest to be payed
        dmInterest = yearlyAccelPayment - amount;
        dmInterest = Number(dmInterest.toFixed(2));
    } else if(frequency === 'biweeklyAccel') {

    }
} //end calculate payment function

/* validation */
function validateAmount(amount) { //function takes the input value
    var dmAmountBox = document.getElementById('dm-amount'); //get input box element
    var dmAmountError = document.getElementById('dm-amount-error');
    var validation = false; //initial return value

    dmAmountError.className = "dm-error dm-hidden"; //ensure the error message is hidden by default

    if(amount === '') { //check if empty
        dmAmountBox.className = 'dm-form-input dm-highlight-box'; //highlight box
        dmEmptyError.className = 'dm-error'; //display the empty error
    } else { //if not empty check if regex match
        if(dmAmountRegex.test(amount)) { //if regex returns true
            dmAmountBox.className = 'dm-form-input'; //remove highlight
            validation = true; //set return value to true
        } else { //if regex returns false
            dmAmountBox.className = 'dm-form-input dm-highlight-box'; //highlight box
            dmAmountError.className = 'dm-error'; //display the error message
            validation = false;
        }
    }

    return validation;
} //end validateAmount function

function validateRate(rate) { //function takes the input value
    var dmRateBox = document.getElementById('dm-rate'); //get input box element
    var dmRateError = document.getElementById('dm-rate-error');
    var validation = false; //initial return value

    dmRateError.className = "dm-error dm-hidden"; //ensure the error message is hidden by default

    if(rate === '') { //check if empty
        dmRateBox.className = 'dm-form-input dm-highlight-box'; //highlight box
        dmEmptyError.className = 'dm-error'; //display the empty error
    } else { //if not empty check if regex match
        if(dmRateRegex.test(rate)) { //if regex returns true
            dmRateBox.className = 'dm-form-input'; //remove highlight
            validation = true; //set return value to true
        } else { //if regex returns false
            dmRateBox.className = 'dm-form-input dm-highlight-box'; //highlight box
            dmRateError.className = 'dm-error'; //display the error message
            validation = false;
        }
    }

    return validation;
} //end validateRate function

function validateTerm(term) { //function takes the input value
    var dmTermBox = document.getElementById('dm-term'); //get input box element
    var dmTermError = document.getElementById('dm-term-error');
    var validation = false; //initial return value

    dmTermError.className = "dm-error dm-hidden"; //ensure the error message is hidden by default

    if(term === '') { //check if empty
        dmTermBox.className = 'dm-form-input dm-highlight-box'; //highlight box
        dmEmptyError.className = 'dm-error'; //display the empty error
    } else { //if not empty check if regex match
        if(dmTermRegex.test(term)) { //if regex returns true
            dmTermBox.className = 'dm-form-input'; //remove highlight
            validation = true; //set return value to true
        } else { //if regex returns false
            dmTermBox.className = 'dm-form-input dm-highlight-box'; //highlight box
            dmTermError.className = 'dm-error'; //display the error message
            validation = false;
        }
    }

    return validation;
} //end validateTerm function

/*
 * GOOGLE CHARTS
 */

// Load the Visualization API and the chart package.
google.load('visualization', '1.0', {
  'packages': ['corechart']
});

// Set a callback to run when the Google Visualization API is loaded.
/*google.setOnLoadCallback(drawChart);*/
google.setOnLoadCallback(submitForm);

// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function drawChart() {

  // Create the data table.        
  var data = google.visualization.arrayToDataTable([
    ['Payment', 'Principle ($)', 'Interest ($)', {
      role: 'annotation'
    }],
    ['Mortgage Term', dmPrinciple, dmInterest, '']
  ]);

  // Set chart options
  var options = {
    'width': 250,
    'height': 300,
    legend: {
      position: 'top',
      maxLines: 2
    },
    bar: {
      groupWidth: '75%'
    },
    isStacked: true
  };

  // Instantiate and draw our chart, passing in some options.
  var chart = new google.visualization.ColumnChart(document.getElementById('dm-chart'));
  chart.draw(data, options);
}