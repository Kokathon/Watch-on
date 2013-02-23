<?php
    $param = $_GET[ 'term' ];
    $callback = $_GET[ 'callback' ];
    /*
    // Lovefilm search
    include ('services/lovefilm.php' );
    $lovefilm = new Lovefilm();
    $lovefilm->searchMovie( $param );
    */

    // Viaplay search
    include( 'services/viaplay.php' );
    $viaplay = new Viaplay();

    $movies = $viaplay->search( $param, 'movie' );
    $tv = $viaplay->search( $param, 'tv' );

    $viaplay_results = array_merge( $movies, $tv );

    // Hbo search
    include( 'services/hbo.php' );
    $hbo = new Hbo();

    $movies = $hbo->search( $param, 'movie' );
    $tv = $hbo->search( $param, 'tv' );

    $hbo_results = array_merge( $movies, $tv );

    $results = array_merge( $viaplay_results, $hbo_results );

    header( 'Content-type: application/json' );
    echo $callback . "(";
    echo json_encode( $results );
    echo ");";