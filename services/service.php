<?php

    abstract class Service {

        abstract public function findMovies( $term );

        abstract public function findTv( $term );

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
                return false;
            endif;
        }
    }

?>