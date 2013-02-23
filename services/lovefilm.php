<?php

    class Lovefilm {
        private $searchMovieBase = 'http://www.lovefilm.se/browse/film/film/?rows=50&query=';
        private $movies = array();

        public function searchMovie( $param ){
            $data = file_get_contents( $this->searchMovieBase . $param );
            //preg_match_all( '/<h2><a.+?title="(.+?)".+?<\/h2>/s', $data, $matches );
            preg_match( '/<div class="pagination(.+?)<\/div>/s', $data, $pagination );
            print_r( $pagination );
            //$this->movies = array_merge( $this->movies, $matches[ 1 ] );
            //print_r( $this->movies );
            //echo $data;
        }
    }