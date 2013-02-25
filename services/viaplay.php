<?php

    class Viaplay {
        private $tvUrl = 'http://viaplay.se/tv/alphabetical';
        private $movieUrl = 'http://viaplay.se/film/samtliga/250/alphabetical';
        private static $movieBaseUrl = 'http://viaplay.se';
        private $movies = array();
        private $shows = array();

        public function findAllMovies() {
            $data = file_get_contents( $this->movieUrl );
            preg_match_all( '/<ul>(.+?)<\/ul>/s', $data, $matches );
            $filterList = array_slice( $matches[ 0 ], 2, count( $matches ) - 8 );
            foreach ( $filterList as $letterList ) :
                preg_match_all( '/<a href="(.+?)">(.+?)<\/a>/s', $letterList, $movies );
                foreach( $movies[ 2 ] as $key => $movie ) :
                    $this->movies[] = array(
                        'title' => $movie,
                        'url' => self::$movieBaseUrl . $movies[ 1 ][ $key ]
                    );
                endforeach;
            endforeach;
        }

        public function findAllTV() {
            $data = file_get_contents( $this->tvUrl );
            preg_match_all( '/<ul>(.+?)<\/ul>/s', $data, $matches );
            $filterList = array_slice( $matches[ 0 ], 1, count( $matches ) - 8 );
            foreach ( $filterList as $letterList ) :
                preg_match_all( '/<a href="(.+?)">(.+?)<\/a>/s', $letterList, $shows );
                foreach( $shows[ 2 ] as $key => $show ) :
                    $this->shows[] = array(
                        'title' => $show,
                        'url' => self::$movieBaseUrl . $shows[ 1 ][ $key ]
                    );
                endforeach;
            endforeach;
        }

        public function getMovies() {
            return $this->movies;
        }

        public function getShows() {
            return $this->shows;
        }

        public function search( $param, $type = 'movie' ) {
            $m = new MongoClient();

            // select a database
            $db = $m->watchon;

            // select a collection (analogous to a relational database's table)
            $collectionName = 'viaplay' . $type;
            $collection = $db->$collectionName;

            $condition = new MongoRegex( '/.*' . $param . '.*/i' );
            $findResults = $collection->find( array( 'title' => $condition ) );

            $results = array();

            foreach ( $findResults as $result ) :
                $results[ ] = array(
                    'title' => $result[ 'title' ],
                    'service' => $result[ 'service' ],
                    'type' => $result[ 'type' ],
                    'url' => $result[ 'url' ]
                );
            endforeach;

            return $results;
        }

        public function index() {
            $this->findAllMovies();
            // connect
            $m = new MongoClient();

            // select a database
            $db = $m->watchon;

            // select a collection (analogous to a relational database's table)
            $collection = $db->viaplaymovie;

            // Empty collection
            $collection->remove();

            // add a record
            foreach ( $this->movies as $movie ) :
                $document = array(
                    'title' => $movie[ 'title' ],
                    'service' => 'viaplay',
                    'type' => 'movie',
                    'url' => $movie[ 'url' ]
                );
                $collection->insert( $document );
            endforeach;

            $this->findAllTV();

            $collection = $db->viaplaytv;

            // Empty collection
            $collection->remove();

            // add a record
            foreach ( $this->shows as $show ) :
                $document = array(
                    'title' => $show[ 'title' ],
                    'service' => "viaplay",
                    'type' => 'tv',
                    'url' => $show[ 'url' ]
                );
                $collection->insert( $document );
            endforeach;
        }
    }

?>