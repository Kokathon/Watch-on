<?php
    include( 'services/viaplay.php' );
    $viaplay = new Viaplay();
    $viaplay->index();

    /*
    $viaplay->findAllMovies();
    $movies = $viaplay->getMovies();
    // connect
    $m = new MongoClient();

    // select a database
    $db = $m->watchon;

    // select a collection (analogous to a relational database's table)
    $collection = $db->movies;
    // add a record
    foreach ( $movies as $movie ) :
        $document = array(
            "title" => $movie,
            "service" => "viaplay"
        );
        if ( !$collection->findOne( $document ) ) :
            $collection->insert( $document );
        endif;
    endforeach;
    */