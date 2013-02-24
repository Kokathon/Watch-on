<?php

    class Headweb {
        private STATIC $apiBase = 'http://www.headweb.com/v4/searchhint/filter(-adult)?apikey=d91b8d77fe2f4c3dbbebbad9ea5dd201&c=0&fields=name&limit=20&offset=0&query=';
        private $movies = array();

        public function searchMovies( $param ){
            $param = urlencode( $param );
            $data = simplexml_load_file( self::$apiBase . $param );
            foreach( $data->searchresult->list->content as $result ) :
                $this->movies[] = array(
                    'title' => $result->originalname,
                    'type' => 'movie',
                    'service' => 'headweb'
                );
            endforeach;

            return $this->movies;
        }

    }