<?php
    $param = '';
    $callback = '';
    $results = array();

    if ( isset( $_GET[ 'term' ] ) ) {
        $param = urldecode( $_GET[ 'term' ] );
    }

    if ( isset( $_GET[ 'callback' ] ) ) {
        $callback = $_GET[ 'callback' ];
    }

    if( isset( $_GET[ 'service' ] ) ) :
        $service = $_GET[ 'service' ];
    else :
        $service = 'viaplay';
    endif;

    switch( $service ) :
        case 'lovefilm':
            // Lovefilm search
            include ( 'services/lovefilm.php' );
            $lovefilm = new Lovefilm();
            $movies = $lovefilm->searchMovie( $param );
            $tv = $lovefilm->searchTv( $param );

            $lovefilm_results = array_merge( $movies, $tv );

            $results[ 'lovefilm' ] = $lovefilm_results;
            break;
        case 'hbo':
            // Hbo search
            include( 'services/hbo.php' );
            $hbo = new Hbo();

            $movies = $hbo->search( $param, 'movie' );
            $tv = $hbo->search( $param, 'tv' );

            $hbo_results = array_merge( $movies, $tv );

            $results[ 'hbo' ] = $hbo_results;
            break;
        case 'voddler':
            // Voddler search
            include('services/voddler.php');
            $voddler = new Voddler();
            $movies = $voddler->findMovies($param);

            $results['voddler'] = $movies;
            break;
        default:
            // Viaplay search
            include( 'services/viaplay.php' );
            $viaplay = new Viaplay();

            $movies = $viaplay->search( $param, 'movie' );
            $tv = $viaplay->search( $param, 'tv' );

            $viaplay_results = array_merge( $movies, $tv );

            $results[ 'viaplay' ] = $viaplay_results;
            break;
    endswitch;

    header( 'Content-type: application/json' );
    echo $callback . "(";
    echo json_encode( $results );
    echo ");";