<?php
    require_once ( 'service.php' );

    class Hbo extends Service implements Indexable {
        private $tvUrl = 'http://hbonordic.com/rest-services-hook/series';
        private $movieUrl = 'http://hbonordic.com/rest-services-hook/movies?startIndex=0&count=5000';
        private static $baseUrl = 'http://hbonordic.com';
        private $movies = array();
        private $shows = array();

        public function indexMovies(){
            $data = @file_get_contents( $this->movieUrl );
            $moviesResult = json_decode( $data );

            $movies = array();
            foreach( $moviesResult->entry as $movie ) :
                $movies[] = $movie;
            endforeach;

            // connect
            $m = new MongoClient();

            // select a database
            $db = $m->watchon;

            // select a collection (analogous to a relational database's table)
            $collection = $db->hbomovie;

            $collection->remove();

            // add a record
            foreach ( $movies as $movie ) :
                $document = array(
                    'title' => $movie->title,
                    'type' => 'movie',
                    'service' => 'hbo',
                    'url' => self::$baseUrl . $movie->url
                );
                if ( !$collection->findOne( $document ) ) :
                    $collection->insert( $document );
                endif;
            endforeach;
        }

        public function indexTv(){
            $data = @file_get_contents( $this->tvUrl );
            $showsResult = json_decode( $data );

            $shows = array();
            foreach( $showsResult as $show ) :
                $shows[] = $show;
            endforeach;

            // connect
            $m = new MongoClient();

            // select a database
            $db = $m->watchon;

            // select a collection (analogous to a relational database's table)
            $collection = $db->hbotv;

            $collection->remove();

            // add a record
            foreach ( $shows as $show ) :
                $document = array(
                    'title' => $show->title,
                    'type' => 'tv',
                    'service' => 'hbo',
                    'url' => self::$baseUrl . $show->url
                );
                if ( !$collection->findOne( $document ) ) :
                    $collection->insert( $document );
                endif;
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
            $collectionName = 'hbomovies';
            $collection = $db->$collectionName;

            $condition = new MongoRegex( '/.*' . $term . '.*/i' );
            $resultsCursor = $collection->find( array( 'title' => $condition, 'service' => 'hbo' ) );

            $results = array();

            foreach( $resultsCursor as $result ) :
                $results[] = $result;
            endforeach;

            return $results;
        }

        public function findTv($term) {
            $m = new MongoClient();

            // select a database
            $db = $m->watchon;

            // select a collection (analogous to a relational database's table)
            $collectionName = 'hbotv';
            $collection = $db->$collectionName;

            $condition = new MongoRegex( '/.*' . $term . '.*/i' );
            $resultsCursor = $collection->find( array( 'title' => $condition, 'service' => 'hbo' ) );

            $results = array();

            foreach( $resultsCursor as $result ) :
                $results[] = $result;
            endforeach;

            return $results;
        }
    }
?>

