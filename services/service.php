<?php

    abstract class Service {

        public function findMovies( $term ) {
            return array();
        }

        public function findTv( $term ) {
            return array();
        }

        public function findAll( $term ) {
            $movies = $this->findMovies( $term );
            $tv = $this->findTv( $term );

            if ( is_array( $movies ) && is_array( $tv ) ) :
                return array_merge( $movies, $tv );
            elseif ( is_array( $movies ) ) :
                return $movies;
            elseif ( is_array( $tv ) ) :
                return $tv;
            else :
                return array();
            endif;
        }
    }

?>