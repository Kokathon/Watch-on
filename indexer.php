<?php
    include( 'services/viaplay.php' );
    $viaplay = new Viaplay();

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
        $collection->insert( $document );
    endforeach;

    // find everything in the collection
    $cursor = $collection->find();

    // iterate through the results
    foreach ( $cursor as $document ) :
        echo $document[ "title" ] . "\n";
    endforeach;