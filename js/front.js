(function ( $ ) {
    "use strict";
    $( document ).ready( function () {
        var searchTimeout,
            currentSpan = 0,
            services = {},
            populating = false,
            servicesRequested = 0,
            $body = $( 'body' ),
            searchCount = 1,
            scrollbarWidth = getScrollBarWidth();

        //Search using search.php
        $.ajax( {
            url: "services.php",
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
                var newWidth = 100 / currentSpan,
                    html = "<div class='result-wrapper service-" + serviceName + "'><div class='service-logo'><img src='img/" + serviceName + "-logo.png'></div><div class='table-wrapper'><table class='table table-condensed table-hover table-striped'>",
                    icon = '',
                    elementText = '';

                $.each( objects, function ( index, element ) {
                    if ( element !== null ) {
                        if( element.type === 'movie' ){
                            icon = '<i class="icon-film pull-right type-icon"></i>';
                        }else if( element.type === 'tv' ){
                            icon = '<i class="icon-picture pull-right type-icon"></i>';
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

                html += "</div></table></div>";

                $( ".js-results" ).append( html );

                if( !window.matchMedia || window.matchMedia("(min-width: 768px)").matches ) {
                    var $resultWrapper = $( '.result-wrapper' ),
                        $tableWrapper = $resultWrapper.find( '.table-wrapper' );


                    $resultWrapper.css({
                        width: newWidth + '%',
                        paddingLeft: scrollbarWidth
                    });

                    $tableWrapper.width( $resultWrapper.width() + scrollbarWidth );
                }

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

            searchCount++;
            clearTimeout( searchTimeout );

            searchTimeout = setTimeout( function () {
                var requestedOn = searchCount;

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
                        if (requestedOn === searchCount) {
                            $progressbar.removeClass('no-transition');
                            populateArray(data, service);
                        }
                    };

                    var s = new Service( service );

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

        $body.on( 'click', '.share-popup', function( event ){
            event.preventDefault();
            var url = $( this ).attr( 'href' );
            sharePopup( url );
        });

        function capitaliseFirstLetter( string ) {
            return string.charAt( 0 ).toUpperCase() + string.slice( 1 );
        }

        function sharePopup( url ) {
            var height = 400,
                width = 600,
                left = ( screen.width / 2 )-( width / 2 ),
                top = ( screen.height / 2 )-( height / 2 ),
                windowFeatures = "status=no,height=" + height + ",width=" + width + ",resizable=yes,toolbar=no,menubar=no,scrollbars=no,location=no,directories=no,left=" + left + ",top=" + top;

            window.open( url, 'sharer', windowFeatures );
        }

        $body.on( 'click', '.share-on-facebook', function( event ){
            event.preventDefault();
            postToFeed( $( this ).attr( 'href' ) );
        } );

        function postToFeed( url ) {
            var obj = {
                method: 'feed',
                redirect_uri: 'http://varkanjag.se/facebook.html',
                link: url,
                //picture: 'http://fbrell.com/f8.jpg',
                name: 'Var kan jag se...',
                caption: 'Hitta filmen!... eller tv-serien',
                description: 'Hitta vilken tjänst som erbjuder det just du vill se.'
            };

            FB.ui(obj, function( response ){

            });
        }

        function getScrollBarWidth () {
            var inner = document.createElement('p');
            inner.style.width = "100%";
            inner.style.height = "200px";

            var outer = document.createElement('div');
            outer.style.position = "absolute";
            outer.style.top = "0px";
            outer.style.left = "0px";
            outer.style.visibility = "hidden";
            outer.style.width = "200px";
            outer.style.height = "150px";
            outer.style.overflow = "hidden";
            outer.appendChild (inner);

            document.body.appendChild (outer);
            var w1 = inner.offsetWidth;
            outer.style.overflow = 'scroll';
            var w2 = inner.offsetWidth;
            if (w1 == w2) w2 = outer.clientWidth;

            document.body.removeChild (outer);

            return (w1 - w2);
        }

    } );
}( jQuery ));

if (!Object.keys) {
    Object.keys = (function () {
        var hasOwnProperty = Object.prototype.hasOwnProperty,
            hasDontEnumBug = !({toString: null}).propertyIsEnumerable('toString'),
            dontEnums = [
                'toString',
                'toLocaleString',
                'valueOf',
                'hasOwnProperty',
                'isPrototypeOf',
                'propertyIsEnumerable',
                'constructor'
            ],
            dontEnumsLength = dontEnums.length;

        return function (obj) {
            if (typeof obj !== 'object' && typeof obj !== 'function' || obj === null) throw new TypeError('Object.keys called on non-object');

            var result = [];

            for (var prop in obj) {
                if (hasOwnProperty.call(obj, prop)) result.push(prop);
            }

            if (hasDontEnumBug) {
                for (var i=0; i < dontEnumsLength; i++) {
                    if (hasOwnProperty.call(obj, dontEnums[i])){
                        result.push(dontEnums[i]);
                    }
                }
            }
            return result;
        };
    })();
}
