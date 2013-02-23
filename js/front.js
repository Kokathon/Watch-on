$(document).ready(function() {
  $( ".js-search-input" ).autocomplete({
    minLength: 2,
    source: "search.php",
    focus: function( event, ui ) {
      $( "#project" ).val( ui.item.label );
      return false;
    },
    select: function( event, ui ) {
      $( "#project" ).val( ui.item.label );
      $( "#project-id" ).val( ui.item.value );
      $( "#project-description" ).html( ui.item.desc );
      $( "#project-icon" ).attr( "src", "images/" + ui.item.icon );

      return false;
    }
  })
  .data( "ui-autocomplete" )._renderItem = function( ul, item ) {
    return $( "<li>" )
      .append( "<a>" + item.label + "<br>" + item.desc + "</a>" )
      .appendTo( ul );
  };
});