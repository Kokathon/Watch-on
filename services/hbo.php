<?php

    class Hbo {
        private $tvUrl = 'http://hbonordic.com/rest-services-hook/series';
        private $movieUrl = 'http://hbonordic.com/rest-services-hook/movies?startIndex=0&count=5000';
        private $movies = array();
        private $shows = array();

        public function findAllMovies(){
            $data = file_get_contents( $this->movieUrl );
            $moviesResult = json_decode( $data );

            $movies = array();
            foreach( $moviesResult->entry as $movie ) :
                $movies[] = array(
                    'title' => $movie->title
                );
            endforeach;

            $this->movies = $movies;
        }

        public function findAllTV(){
            $data = file_get_contents( $this->tvUrl );
            $showsResult = json_decode( $data );

            $shows = array();
            foreach( $showsResult as $show ) :
                $shows[] = array(
                    'title' => $show->title
                );
            endforeach;

            $this->shows = $shows;
        }

        public function getMovies() {
            return $this->movies;
        }

        public function getShows() {
            return $this->shows;
        }

        public function search( $param, $type = 'movie' ){
            $m = new MongoClient();

            // select a database
            $db = $m->watchon;

            // select a collection (analogous to a relational database's table)
            $collection = $db->$type;

            $condition = new MongoRegex( '/.*' . $param . '.*/i' );
            $findResults = $collection->find( array( 'title' => $condition, 'service' => 'hbo' ) );

            $results = array();

            foreach( $findResults as $result ) :
                $results[] = array(
                    'title' => $result[ 'title' ],
                    'service' => $result[ 'service' ],
                    'type' => $type
                );
            endforeach;

            return $results;
        }

        public function index(){
            $this->findAllMovies();
            // connect
            $m = new MongoClient();

            // select a database
            $db = $m->watchon;

            // select a collection (analogous to a relational database's table)
            $collection = $db->movie;
            // add a record
            foreach ( $this->movies as $movie ) :
                $document = array(
                    'title' => $movie,
                    'service' => 'hbo'
                );
                if ( !$collection->findOne( $document ) ) :
                    $collection->insert( $document );
                endif;
            endforeach;

            $this->findAllTV();

            $collection = $db->tv;
            // add a record
            foreach ( $this->shows as $show ) :
                $document = array(
                    'title' => $show,
                    'service' => 'hbo'
                );
                if ( !$collection->findOne( $document ) ) :
                    $collection->insert( $document );
                endif;
            endforeach;
        }
    }