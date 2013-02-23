<?php

    class Lovefilm {
        private $searchMovieBase = 'http://www.lovefilm.se/browse/film/film/?rows=50&query=';
        private $searchTvBase = 'http://www.lovefilm.se/browse/film/tv/?rows=50&query=';
        private $movies = array();
        private $shows = array();

        public function searchMovie( $param ){
            $data = file_get_contents( $this->searchMovieBase . $param );
            preg_match_all( '/<h2><a.+?title="(.+?)".+?<\/h2>/s', $data, $matches );
            foreach( $matches[ 1 ] as $movie ) :
                $this->movies[] = array(
                    'title' => $movie,
                    'type' => 'movie',
                    'service' => 'lovefilm'
                );
            endforeach;
            //$this->movies = array_merge( $this->movies, $matches[ 1 ] );

            return $this->movies;
        }

        public function searchTv( $param ) {
            $data = file_get_contents( $this->searchTvBase . $param );
            preg_match_all( '/<h2><a.+?title="(.+?)".+?<\/h2>/s', $data, $matches );
            $this->shows = array_merge( $this->shows, $matches[ 1 ] );
            foreach( $matches[ 1 ] as $show ) :
                $this->shows[] = array(
                    'title' => $show,
                    'type' => 'tv',
                    'service' => 'lovefilm'
                );
            endforeach;

            return $this->shows;
        }
    }