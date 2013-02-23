<?php

    class Lovefilm {
        private $searchBase = 'http://www.lovefilm.se/browse/film/?rows=50&query=';

        public function searchMovie( $param ){
            $data = file_get_contents( $this->searchBase . $param );
            preg_match_all( '/<h2><a.+?title="(.+?)".+?<\/h2>/s', $data, $matches );
            print_r( $matches );
            //echo $data;
        }
    }