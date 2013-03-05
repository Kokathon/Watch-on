<?php

    require_once ( 'service.php' );

    class Viaplay extends Service implements Indexable{
        private $tvUrl = 'http://viaplay.se/tv/alphabetical';
        private $movieUrl = 'http://viaplay.se/film/samtliga/250/alphabetical';
        private static $movieBaseUrl = 'http://viaplay.se';
        private $movies = array();
        private $shows = array();

        public function indexMovies() {
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

            // connect to the db
            $m = new MongoClient();
            $db = $m->watchon;
            $collection = $db->viaplaymovie;

            // empty the collection
            $collection->remove();

            // add each record
            foreach( $this->movies as $movie ):
                $tv = array(
                    'title' => $movie[ 'title' ],
                    'url' => $movie[ 'url' ],
                    'type' => 'tv'
                );

                $collection->insert($tv);
            endforeach;
        }

        public function indexTv() {
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

            // connect to the db
            $m = new MongoClient();
            $db = $m->watchon;
            $collection = $db->viaplaytv;

            // empty the collection
            $collection->remove();

            // add each record
            foreach( $this->shows as $show ):
                $tv = array(
                    'title' => $show[ 'title' ],
                    'url' => $show[ 'url' ],
                    'type' => 'tv'
                );

                $collection->insert($tv);
            endforeach;
        }

        public function createIndex(){
            $this->indexMovies();
            $this->indexTv();
        }

        public function findMovies($term) {
            $m = new MongoClient();

            // select a database
            $db = $m->watchon;

            // select a collection (analogous to a relational database's table)
            $collectionName = 'viaplaymovie';
            $collection = $db->$collectionName;

            $condition = new MongoRegex( '/.*' . $term . '.*/i' );
            $results = $collection->find( array( 'title' => $condition ) );

            $movies = array();

            foreach( $results as $result ) :
                $movies[] = $result;
            endforeach;

            return $movies;
        }

        public function findTv($term) {
             $m = new MongoClient();

            // select a database
            $db = $m->watchon;

            // select a collection (analogous to a relational database's table)
            $collectionName = 'viaplaytv';
            $collection = $db->$collectionName;

            $condition = new MongoRegex( '/.*' . $term . '.*/i' );
            $results = $collection->find( array( 'title' => $condition ) );

            $tv = array();

            foreach( $results as $show ) :
                $tv[] = $show;
            endforeach;

            return $tv;
        }

    }

?>
