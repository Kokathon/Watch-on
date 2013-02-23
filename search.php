<?php
    $param = $_GET[ 'search' ];
    $m = new MongoClient();

    // select a database
    $db = $m->watchon;

    // select a collection (analogous to a relational database's table)
    $collection = $db->movies;

    $condition = new MongoRegex( '/.*' . $param . '.*/i' );
    $movies = $collection->find( array( 'title' => $condition ) );

    foreach( $movies as $movie ) :
           echo $movie[ 'service' ] , ' - ', $movie[ 'title' ] , '<br>';
    endforeach;