<?php
    include( 'services/viaplay.php' );
    $param = $_GET[ 'search' ];
    $viaplay = new Viaplay();

    $movies = $viaplay->search( $param, 'movies' );
    $tv = $viaplay->search( $param, 'tv' );

    $results = array_merge( $movies, $tv );

    echo json_encode( $results );