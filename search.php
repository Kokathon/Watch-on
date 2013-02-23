<?php
    $param = $_GET[ 'term' ];
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

    $results = array_merge( $movies, $tv );

    header( 'Content-type: application/json' );
    echo json_encode( $results );