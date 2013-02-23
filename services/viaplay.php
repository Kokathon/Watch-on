<?php

    class Viaplay {
        private $tvUrl = 'http://viaplay.se/tv/alphabetical';
        private $movieUrl = 'http://viaplay.se/film/samtliga/250/alphabetical';
        private $movies = array();
        private $shows = array();

        public function findAllMovies() {
            $data = file_get_contents( $this->movieUrl );
            preg_match_all( '/<ul>(.+?)<\/ul>/s', $data, $matches );
            $filterList = array_slice( $matches[ 0 ], 2, count( $matches ) - 8 );
            foreach ( $filterList as $letterList ) :
                preg_match_all( '/<a href=".+?">(.+?)<\/a>/s', $letterList, $movies );
                $this->movies = array_merge( $this->movies, $movies[ 1 ] );
            endforeach;
        }

        public function findAllTV() {
            $data = file_get_contents( $this->tvUrl );
            preg_match_all( '/<ul>(.+?)<\/ul>/s', $data, $matches );
            $filterList = array_slice( $matches[ 0 ], 1, count( $matches ) - 8 );
            foreach ( $filterList as $letterList ) :
                preg_match_all( '/<a href=".+?">(.+?)<\/a>/s', $letterList, $shows );
                $this->shows = array_merge( $this->shows, $shows[ 1 ] );
            endforeach;
        }

        public function getMovies() {
            return $this->movies;
        }

        public function getShows() {
            return $this->shows;
        }

        public function search( $param, $type = 'movies' ){
            $m = new MongoClient();

            // select a database
            $db = $m->watchon;

            // select a collection (analogous to a relational database's table)
            $collection = $db->$type;

            $condition = new MongoRegex( '/.*' . $param . '.*/i' );
            $movies = $collection->find( array( 'title' => $condition, 'service' => 'viaplay' ) );

            $results = array();

            foreach( $movies as $movie ) :
                $results[] = array(
                    'title' => $movie[ 'title' ],
                    'service' => $movie[ 'service' ]
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
            $collection = $db->movies;
            // add a record
            foreach ( $this->movies as $movie ) :
                $document = array(
                    "title" => $movie,
                    "service" => "viaplay"
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
                    "title" => $show,
                    "service" => "viaplay"
                );
                if ( !$collection->findOne( $document ) ) :
                    $collection->insert( $document );
                endif;
            endforeach;
        }
    }
?>