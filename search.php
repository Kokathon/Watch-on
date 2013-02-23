<?php
    include( 'services/viaplay.php' );
    $param = $_GET[ 'search' ];
    $viaplay = new Viaplay();

    $movies = $viaplay->searchMovie( $param );

    echo json_encode( $movies );