<?php
    require_once ( 'service.php' );

    class Headweb extends Service {
        private STATIC $apiBase = 'http://www.headweb.com/v4/searchhint/filter(-adult)?apikey=d91b8d77fe2f4c3dbbebbad9ea5dd201&c=0&fields=name&limit=20&offset=0&query=';
        private static $linkBase = 'http://www.headweb.com/sv/';
        private $movies = array();

        public function findMovies( $term ) {
            $term = urlencode( $term );
            $data = @simplexml_load_file( self::$apiBase . $term );
            foreach ( $data->searchresult->list->content as $result ) :
                if ( isset( $result->originalname ) ) :
                    $name = (string)$result->originalname; else :
                    $name = (string)$result->name;
                endif;
                $this->movies[ ] = array(
                    'title' => $name,
                    'type' => 'movie',
                    'url' => self::$linkBase . (string)$result->attributes()->id
                );
            endforeach;

            return $this->movies;
        }

        public function findTv($term) {
            return array();
        }
?>
