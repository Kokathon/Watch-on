<?php
    require_once ( 'service.php' );

    class Hbo extends Service {
        private $tvUrl = 'http://hbonordic.com/rest-services-hook/series';
        private $movieUrl = 'http://hbonordic.com/rest-services-hook/movies?startIndex=0&count=5000';
        private static $movieBaseUrl = 'http://hbonordic.com';
        private $movies = array();
        private $shows = array();

        public function findAllMovies(){
            $data = @file_get_contents( $this->movieUrl );
            $moviesResult = json_decode( $data );

            $movies = array();
            foreach( $moviesResult->entry as $movie ) :
                $movies[] = $movie;
            endforeach;

            $this->movies = $movies;
        }

        public function findAllTV(){
            $data = @file_get_contents( $this->tvUrl );
            $showsResult = json_decode( $data );

            $shows = array();
            foreach( $showsResult as $show ) :
                $shows[] = $show;
            endforeach;

            $this->shows = $shows;
        }

        public function getMovies() {
            return $this->movies;
        }

        public function getShows() {
            return $this->shows;
        }

        public function search( $term, $type = 'movie' ){
            $m = new MongoClient();

            // select a database
            $db = $m->watchon;

            // select a collection (analogous to a relational database's table)
            $collectionName = 'hbo' . $type;
            $collection = $db->$collectionName;

            $condition = new MongoRegex( '/.*' . $term . '.*/i' );
            $results = $collection->find( array( 'title' => $condition, 'service' => 'hbo' ) );

            return $results;
        }

        public function index(){
            $this->findAllMovies();
            // connect
            $m = new MongoClient();

            // select a database
            $db = $m->watchon;

            // select a collection (analogous to a relational database's table)
            $collection = $db->hbomovie;

            $collection->remove();

            // add a record
            foreach ( $this->movies as $movie ) :
                $document = array(
                    'title' => $movie->title,
                    'type' => 'movie',
                    'service' => 'hbo',
                    'url' => self::$movieBaseUrl . $movie->url
                );
                if ( !$collection->findOne( $document ) ) :
                    $collection->insert( $document );
                endif;
            endforeach;

            $this->findAllTV();

            $collection = $db->hbotv;

            $collection->remove();

            // add a record
            foreach ( $this->shows as $show ) :
                $document = array(
                    'title' => $show->title,
                    'type' => 'tv',
                    'service' => 'hbo',
                    'url' => self::$movieBaseUrl . $show->url
                );
                if ( !$collection->findOne( $document ) ) :
                    $collection->insert( $document );
                endif;
            endforeach;
        }


        public function findMovies($term) {
            $m = new MongoClient();

            // select a database
            $db = $m->watchon;

            // select a collection (analogous to a relational database's table)
            $collectionName = 'hbomovies';
            $collection = $db->$collectionName;

            $condition = new MongoRegex( '/.*' . $term . '.*/i' );
            $results = $collection->find( array( 'title' => $condition, 'service' => 'hbo' ) );

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
            $results = $collection->find( array( 'title' => $condition, 'service' => 'hbo' ) );

            return $results;
        }
    }
?>

