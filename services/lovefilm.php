<?php

    class Lovefilm {
        private $searchMovieBase = 'http://www.lovefilm.se/browse/film/film/?rows=50&query=';
        private $searchTvBase = 'http://www.lovefilm.se/browse/film/tv/?rows=50&query=';
        private $movies = array();
        private $shows = array();

        public function searchMovie( $param ){
            $param = urlencode( $param );
            $data = @file_get_contents( $this->searchMovieBase . $param );
            preg_match_all( '/<h2><a href="(.+?)"title="(.+?)".+?<\/h2>/s', $data, $matches );
            foreach( $matches[ 2 ] as $key => $movie ) :
                if( $movie == 'null' ) :
                    continue;
                endif;
                $this->movies[] = array(
                    'title' => utf8_encode( $movie ),
                    'type' => 'movie',
                    'service' => 'lovefilm',
                    'url' => utf8_encode( $matches[ 1 ][ $key ] )
                );
            endforeach;

            return $this->movies;
        }

        public function searchTv( $param ) {
            $param = urlencode( $param );
            $data = @file_get_contents( $this->searchTvBase . $param );
            preg_match_all( '/<h2><a href="(.+?)"title="(.+?)".+?<\/h2>/s', $data, $matches );
            foreach( $matches[ 2 ] as $key => $show ) :
                if( $show == 'null' ) :
                    continue;
                endif;
                $this->shows[] = array(
                    'title' => utf8_encode( $show ),
                    'type' => 'tv',
                    'service' => 'lovefilm',
                    'url' => utf8_encode( $matches[ 1 ][ $key ] )
                );
            endforeach;

            return $this->shows;
        }
    }