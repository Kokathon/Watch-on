(function ( $ ) {
    "use strict";
    $( document ).ready( function () {
        var searchTimeout,
            currentSpan = 0,
            services = {},
            spanBase = 12,
            populating = false,
            internalServices = {},
            servicesRequested = 0,
            $body = $( 'body' );

        //Search using search.php
        $.ajax( {
            url : "http://kokarn.com/kokathon/repos/Watch-on/services.php",
            dataType : "jsonp",
            success : function ( data ) {
                for ( var i = 0; i < data.length; i++ ) {
                    services[data[i]] = {
                        id : data[i],
                        name : capitaliseFirstLetter( data[i] ),
                        results : []
                    };
                }
            }
        } );

        function populateArray( objects, service ) {

            servicesRequested++;

            var $progressbar = $( '.js-progress' ),
                newWidth = ( 100 / Object.keys(services).length ) * servicesRequested;

            $progressbar.find( '.bar' ).css({
                width : newWidth + '%'
            });

            if( newWidth === 100 ){
                $progressbar.removeClass( 'active' );
            }

            if( objects.length > 0 ){
                //First empty corresponding array
                services[service].results = [];

                $.each( objects, function ( index, element ) {
                    services[service].results.push( element );
                } );

                populateTable( services[service].results, services[service].id );
            }
        }

        function populateTable( objects, serviceName ) {
            // Make sure it's only run once at a time
            if ( !populating ) {
                populating = true;
                currentSpan += 1;
                var newSpan,
                    prevSpan = 12;

                if ( currentSpan > 0 ) {
                    newSpan = Math.floor( spanBase / currentSpan );
                    if ( currentSpan > 1 ) {
                        prevSpan = Math.floor( spanBase / ( currentSpan - 1 ) );
                    }
                    $( 'div:hasClassStartingWith("span")' ).removeClass( 'span' + prevSpan ).addClass( 'span' + newSpan );
                } else {
                    newSpan = spanBase;
                }

                var html = "<div class='span" + newSpan + " service-" + serviceName + "'><div class='service-logo'><!-- --></div><table class='table table-condensed table-hover table-striped'>",
                    icon = '',
                    elementText = '';

                $.each( objects, function ( index, element ) {
                    if ( element !== null ) {
                        if( element.type === 'movie' ){
                            icon = '<i class="icon-film pull-right"></i>';
                        }else if( element.type === 'tv' ){
                            icon = '<i class="icon-picture pull-right"></i>';
                        }

                        if( element.url ) {
                            elementText = '<a href="' + element.url + '" title="' + element.title + ' on ' + element.service + '">' + element.title + '</a>';
                        } else {
                            elementText = element.title;
                        }
                        html += '<tr><td>' + elementText + icon + '</td></tr>';
                    }
                } );

                if ( objects.length === 0 ) {
                    html += "<tr class='warning'><td>No results!</td></tr>";
                }

                html += "</table></div>";

                $( ".js-results" ).append( html );
                populating = false;
            } else {
                var myObjects = objects,
                    myServiceName = serviceName;

                setTimeout( function () {
                    populateTable( myObjects, myServiceName );
                }, 10 );
            }
        }

        function doSearch() {
            var term = encodeURI( $.trim( $( ".js-search-input" ).val() ) ),
                $progressbar = $( '.js-progress' ),
                type = $( '.js-current-searchtype' ).data( 'searchtype' );

            if( term.length <= 1 ){
                return;
            }

            clearTimeout( searchTimeout );

            searchTimeout = setTimeout( function () {

                $progressbar.addClass('no-transition');
                $progressbar.find( '.bar' ).css({
                    width : '0'
                });

                $progressbar.show();

                currentSpan = 0;
                servicesRequested = 0;

                $( '.js-results' ).children().remove();
                $progressbar.addClass( 'active' );

                $.each(services, function (service) {

                    var callback = function (data) {
                        $progressbar.removeClass('no-transition');
                        populateArray(data, service);
                    };

                    var s = new window[service]();

                    switch (type) {
                        case 'tv':
                            s.findTv(term, callback);
                            break;
                        case 'movie':
                            s.findMovies(term, callback);
                            break;
                        default:
                            s.findAll(term, callback);
                            break;
                    }

                });

            }, 500 );
        }

        if ('oninput' in document.documentElement) {
            $body.on( 'input', 'input', doSearch);
        } else {
            $body.on( 'keyup', 'input', doSearch);
        }

        $body.on( 'submit', 'form', function( event ){
            event.preventDefault();
        });

        $body.on( 'click', '.dropdown-menu a', function( event ){
            var $dropdownWrapper = $body.find( '.dropdown-menu' ),
                $this = $( this ),
                $oldSearchType = $dropdownWrapper.find( '.js-current-searchtype' );

            event.preventDefault();

            if( !$oldSearchType.is( $this ) ){
                $dropdownWrapper.find( '.js-current-searchtype' ).removeClass( 'js-current-searchtype' );
                $this.addClass( 'js-current-searchtype' ).append( $dropdownWrapper.find( '.js-icon-selected' ) );

                doSearch();
            }
        });

        function capitaliseFirstLetter( string ) {
            return string.charAt( 0 ).toUpperCase() + string.slice( 1 );
        }

        function log( message ) {
            $( "<div>" ).text( message ).prependTo( "#log" );
            $( "#log" ).scrollTop( 0 );
        }

        $.expr[':'].hasClassStartingWith = function ( el, i, selector ) {
            var re = new RegExp( "\\b" + selector[3] );
            return re.test( el.className );
        };

    } );
}( jQuery ));