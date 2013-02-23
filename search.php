<?php
    $param = '';
    $callback = '';
    $results = array();

    if ( isset( $_GET[ 'term' ] ) ) {
        $param = $_GET[ 'term' ];
    }

    if ( isset( $_GET[ 'callback' ] ) ) {
        $callback = $_GET[ 'callback' ];
    }

    // Lovefilm search
    include ( 'services/lovefilm.php' );
    $lovefilm = new Lovefilm();
    $movies = $lovefilm->searchMovie( $param );
    $tv = $lovefilm->searchTv( $param );

    $lovefilm_results = array_merge( $movies, $tv );

    $results[ 'lovefilm' ] = $lovefilm_results;

    // Viaplay search
    include( 'services/viaplay.php' );
    $viaplay = new Viaplay();

    $movies = $viaplay->search( $param, 'movie' );
    $tv = $viaplay->search( $param, 'tv' );

    $viaplay_results = array_merge( $movies, $tv );

    $results[ 'viaplay' ] = $viaplay_results;

    // Hbo search
    include( 'services/hbo.php' );
    $hbo = new Hbo();

    $movies = $hbo->search( $param, 'movie' );
    $tv = $hbo->search( $param, 'tv' );

    $hbo_results = array_merge( $movies, $tv );

    $results[ 'hbo' ] = $hbo_results;

    // Voddler search
    include('services/voddler.php');
    $voddler = new Voddler();
    $movies = $voddler->findMovies($param);

    $results['voddler'] = $movies;

    //$results = array_merge( $viaplay_results, $hbo_results );

    header( 'Content-type: application/json' );
    echo $callback . "(";
    echo json_encode( $results );
    echo ");";