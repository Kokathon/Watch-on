$(document).ready(function() {

  var searchTimeout;
  var span = ["span12", "span6", "span4", "span3", "span2", "span1"];
  var currentSpan = 0;

  var services = {};

  function populateArray(objects, service) {
    var serviceName;

    if (service !== undefined) {
      serviceName = service;
    } else {
      serviceName = objects[0];
    }

    //Only create if does not exist
    if (services[serviceName] == undefined) {
      services[serviceName] = {
        name:serviceName,
        results:[]
      }
    }

    //First empty corresponding array
    services[serviceName].results = [];

    console.log(services[serviceName]);

    $.each(objects, function(index, element){
      services[serviceName].results.push(element);
    });

    populateTable(services[serviceName].results, services[serviceName].name);
  }

  function populateTable(objects, serviceName) {
    if (currentSpan > 0) {
      $("." + span[currentSpan - 1]).removeClass(span[currentSpan - 1]).addClass(span[currentSpan]); 
    }
    $(".service-" + serviceName).remove();
    var html = "<div class='" + span[currentSpan] + " service-" + serviceName + "'><table class='table table-condensed table-hover table-striped js-table-viaplay'><tr><th>" + capitaliseFirstLetter(serviceName) + "</th></tr>";

    $.each(objects, function(index, element){
      html += "<tr><td>" + element.title + "</td></tr>";
    });

    html += "</table></div>";

    $(".js-results").append(html);
    currentSpan += 1;
  }

  $("body").on("keyup", "input", function(e) {

    var term = encodeURI($.trim($(".js-search-input").val()));

    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(function(){

        currentSpan = 0;

        //Search using search.php
        $.ajax({
          url: "http://kokarn.com/kokathon/repos/Watch-on/search.php",
          dataType: "jsonp",
          data: {
            featureClass: "P",
            style: "full",
            maxRows: 12,
            term: term
          },
          success: function( data ) {
            var service;

            for (service in data) {
              // if (data.hasOwnProperty(service)) {
                populateArray(data[service], service);
              // }
            }
          }
        });

        //Search using js/netflix.js
        n = new NetFlix();
        // populateArray()
        n.findMovies(term, function(data) {
          populateArray(data, 'netflix')
        });

    },200);
  });

  function capitaliseFirstLetter(string)
  {
      return string.charAt(0).toUpperCase() + string.slice(1);
  }

  function log( message ) {
      $( "<div>" ).text( message ).prependTo( "#log" );
      $( "#log" ).scrollTop( 0 );
    }


});