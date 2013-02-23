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
    }
?>