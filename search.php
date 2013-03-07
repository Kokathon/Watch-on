<?php
require_once('services/require_services.php');

    $term = '';
    $callback = '';
    $find = '';
    $results = array();


    if ( isset( $_GET[ 'term' ] ) ) {
        $term = urldecode( $_GET[ 'term' ] );
    }

    if ( isset( $_GET[ 'callback' ] ) ) {
        $callback = $_GET[ 'callback' ];
    } else {
        $callback = 'callback';
    }

    if( isset( $_GET[ 'service' ] ) ) :
        $service = $_GET[ 'service' ];
    else :
        $service = 'viaplay';
    endif;

    if (isset($_GET['find'])) {
        $find = $_GET['find'];
    } else {
        $find = 'all';
    }

    switch ($find) {
        case 'movie':
            $findFunction = 'findMovies';
            break;
        case 'tv':
            $findFunction = 'findTv';
            break;
        default:
            $findFunction = 'findAll';
            break;
    }

    $apcKey = $service . '_' . $find . '_' . $term;

    if( !apc_exists( $apcKey ) ) :
        $service = new $service();
        $results = $service->$findFunction($term);
        $jsonResults = json_encode( $results );
        apc_store( $apcKey, $jsonResults, 3600 );
    else :
        $jsonResults = apc_fetch( $apcKey );
    endif;

    header( 'Content-type: application/json' );
    echo $callback . "(";
    echo $jsonResults;
    echo ");";