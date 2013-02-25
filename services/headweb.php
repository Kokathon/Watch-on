<?php

    class Headweb {
        private STATIC $apiBase = 'http://www.headweb.com/v4/searchhint/filter(-adult)?apikey=d91b8d77fe2f4c3dbbebbad9ea5dd201&c=0&fields=name&limit=20&offset=0&query=';
        private static $linkBase = 'http://www.headweb.com/sv/';
        private $movies = array();

        public function searchMovies( $param ) {
            $param = urlencode( $param );
            $data = @simplexml_load_file( self::$apiBase . $param );
            foreach ( $data->searchresult->list->content as $result ) :
                if ( isset( $result->originalname ) ) :
                    $name = (string)$result->originalname; else :
                    $name = (string)$result->name;
                endif;
                $this->movies[ ] = array(
                    'title' => $name,
                    'type' => 'movie',
                    'service' => 'headweb',
                    'url' => self::$linkBase . (string)$result->attributes()->id
                );
            endforeach;

            return $this->movies;
        }
    }