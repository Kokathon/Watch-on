$(document).ready(function() {

  var searchTimeout;
  var span = 12;

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
      // html += "<tr><td>" + element.title + "</td></tr>";
    });

    populateTable(services[serviceName].results, services[serviceName].name);
  }

  function populateTable(objects, serviceName) {
    $(".span" + span * 2).removeClass("span" + span * 2).addClass("span" + span);
    $(".service-" + serviceName).remove();
    var html = "<div class='span" + span + " service-" + serviceName + "'><table class='table table-condensed table-hover table-striped js-table-viaplay'><tr><th>" + capitaliseFirstLetter(serviceName) + "</th></tr>";

    $.each(objects, function(index, element){
      html += "<tr><td>" + element.title + "</td></tr>";
    });

    html += "</table></div>";

    $(".js-results").append(html);
    span = span / 2;
  }

  $("body").on("keyup", "input", function(e) {

    var term = $.trim($(".js-search-input").val());

    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(function(){

        //Search using search.php
        $.ajax({
          url: "http://kokarn.com/kokathon/repos/Watch-on/search.php",
          dataType: "jsonp",
          data: {
            featureClass: "P",
            style: "full",
            maxRows: 12,
            term: $.trim($(".js-search-input").val())
          },
          success: function( data ) {
            var service;

            for (service in data) {
              if (data.hasOwnProperty(data)) {
                populateArray(data[service], service);
              }
            }
            /*populateArray(data.viaplay, "viaplay");
            populateArray(data.hbo, "hbo");*/
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