$(document).ready(function() {

  var searchTimeout;
  var span = ["span12", "span6", "span4", "span3", "span2", "span1"];
  var currentSpan = 0;

  var services = {};
  //Search using search.php
  $.ajax({
    url: "services.php",
    dataType: "jsonp",
    success: function( data ) {
      for (var i = 0; i < data.length; i++) {
        services[data[i]] = {
          id: data[i],
          name: capitaliseFirstLetter(data[i]),
          results: []
        }
      };      
    }
  });

  //Add netflix manually
  services['netflix'] = {
    id: "netflix",
    name: "Netflix",
    results: []
  }


  function populateArray(objects, service) {

    //First empty corresponding array
    services[service].results = [];

    $.each(objects, function(index, element){
      services[service].results.push(element);
    });

    populateTable(services[service].results, services[service].name);
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

    if (objects.length == 0) {
      html += "<tr class='warning'><td>No results!</td></tr>";
    };

    html += "</table></div>";

    $(".js-results").append(html);
    currentSpan += 1;
  }

  $("body").on("keyup", "input", function(e) {

    var term = encodeURI($.trim($(".js-search-input").val()));

    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(function(){

        currentSpan = 0;

        $.each(services, function (service) {
          //Search using search.php
          $.ajax({
            url: "http://kokarn.com/kokathon/repos/Watch-on/search.php",
            dataType: "jsonp",
            data: {
              service: service,
              term: term
            },
            success: function( data ) {
              console.log(service);
              console.log(data);
              if (data.length > 0) {
                populateArray(data, service);                
              }
            }
          });
        });
        for (service in services) {
          
          
        };

        

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