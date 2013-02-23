$(document).ready(function() {
  function log( message ) {
      $( "<div>" ).text( message ).prependTo( "#log" );
      $( "#log" ).scrollTop( 0 );
    }
 
    $( ".js-search-input" ).autocomplete({
      source: function( request, response ) {
        $.ajax({
          url: "http://kokarn.com/kokathon/repos/Watch-on/search.php",
          dataType: "jsonp",
          data: {
            featureClass: "P",
            style: "full",
            maxRows: 12,
            term: request.term
          },
          success: function( data ) {
            response( $.map( data, function( item ) {
              return {
                label: item.title + " (" + item.service + ")" + ", " + item.type,
                value: item.title
              }
            }));
          }
        });

        // n = new NetFlix();

        // console.log(n.findMovies(request.term));

      },
      minLength: 2,
      select: function( event, ui ) {
        log( ui.item ?
          "Selected: " + ui.item.label :
          "Nothing selected, input was " + this.value);
      },
      open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      },
      close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      }
    });
});