/* containers */
var dmForm = document.getElementById('dm-form-container');
var dmChart = document.getElementById('dm-chart-container');

/* buttons */
var dmSubmit = document.getElementById('dm-submit');
var dmClear = document.getElementById('dm-clear');

/* currency regex */
var dmAmountRegex = '(?=.)^\$?(([1-9][0-9]{0,2}(,[0-9]{3})*)|[0-9]+)?(\.[0-9]{1,2})?$';

/* animation event listener array */
var endAnimation = ['webkitAnimationEnd', 'mozAnimationEnd', 'MSAnimationEnd', 'oanimationend', 'animationend'];

/* when the form submit button is clicked */
function submitForm() {
    dmSubmit.onclick = function() {

        /* input boxes */
        var dmAmount = document.getElementById('dm-amount').value;
        var dmRate = document.getElementById('dm-rate').value;
        var dmTerm = document.getElementById('dm-term').value;

        alert(dmAmount);

        var amount = validateAmount(dmAmount);

        if(amount === true) {

          /* adds the animation classes to remove container */
          dmForm.className = "dm-form-container animated fadeOutDown";

          /*
           * Uses the 'one' function to check if animation has happened.
           * If the animation has happened then the function
           * removes the animation classes and adds the hidden class.
           * Need to do one for each vendor prefix for full browser support.
           */
          for (i = 0; i < endAnimation.length; i++) { //loop through each vendor prefix
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

/* validation */

function validateAmount(amount) {
  var dmAmountBox = document.getElementById('dm-amount');
  var validation = false;
  if(amount === '') { //check if empty
  dmAmountBox.style.border = '1px red solid';//highlight box show error message
  } else { //if not empty check if regex match
    if(!!amount.match(dmAmountRegex)) { //if regex returns true
      validation = true;
    } else { //if regex returns false
      validation = false;
      //highlight box show error message
    }
  }
  
  return validation;
}

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
    ['Payment', 'Principle Amount', 'Interest Amount', {
      role: 'annotation'
    }],
    ['Mortgage Term', 100000, 0, '']
  ]);

  // Set chart options
  var options = {
    'width': 300,
    'height': 400,
    legend: {
      position: 'top',
      maxLines: 1
    },
    bar: {
      groupWidth: '75%'
    },
    isStacked: true,
  };

  // Instantiate and draw our chart, passing in some options.
  var chart = new google.visualization.ColumnChart(document.getElementById('dm-chart'));
  chart.draw(data, options);
}