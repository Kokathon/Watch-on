<?php
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

    require_once('services/lovefilm.php');
    require_once('services/hbo.php');
    require_once('services/viaplay.php');
    require_once('services/voddler.php');
    require_once('services/headweb.php');
    require_once('services/svtplay.php');
    require_once('services/tv4play.php');


    $service = new $service();
    $results = $service->$findFunction($term);

    header( 'Content-type: application/json' );
    echo $callback . "(";
    echo json_encode( $results );
    echo ");";