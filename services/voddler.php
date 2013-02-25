<?php

include ( 'service.php' );

class Voddler extends Service {
    private static $BASE_URL = 'http://api.voddler.com/metaapi/';

    public function findMovies( $q ) {
        $url = self::$BASE_URL . 'search/1?offset=0&count=50&q=' . urlencode( $q );
        $data = @file_get_contents( $url );
        $obj = json_decode( $data );

        $movies = array();

        foreach ( $obj->data->videos as $video ) {
            if ( $video->videoType == 'movie' ) {
                $movies[ ] = array(
                    'title' => $video->originalTitle,
                    'service' => 'voddler',
                    'type' => 'movie',
                    'url' => $video->url
                );
            }
        }

        return $movies;
    }


    public function findTv($term) {
        $url = self::$BASE_URL . 'search/1?offset=0&count=50&q=' . urlencode( $term );
        $data = @file_get_contents( $url );
        $obj = json_decode( $data );

        $found = array();

        $seasons = array();

        foreach ( $obj->data->videos as $video ) {
            if ( $video->videoType == 'episode' ) {

                preg_match("/(.+?S\d{2}) EP\d{2}/i", $video->originalTitle, $matches);

                if (!isset($found[$matches[1]])) {
                    $found[$matches[1]] = true;

                    $seasons[ ] = array(
                    'title' => $matches[1],
                    'service' => 'voddler',
                    'type' => 'tv',
                    'url' => $video->url
                );
                }                
            }
        }

        return $seasons;
    }

    public function findAll($term) {
        $url = self::$BASE_URL . 'search/1?offset=0&count=50&q=' . urlencode( $term );
        $data = @file_get_contents( $url );
        $obj = json_decode( $data );

        $found = array();

        $items = array();

        foreach ( $obj->data->videos as $video ) {
            if ( $video->videoType == 'episode' ) {

                preg_match("/(.+?S\d{2}) EP\d{2}/i", $video->originalTitle, $matches);

                if (!isset($found[$matches[1]])) {
                    $found[$matches[1]] = true;

                    $items[ ] = array(
                        'title' => $matches[1],
                        'service' => 'voddler',
                        'type' => 'tv',
                        'url' => $video->url
                    );
                }                
            } else if ($video->videoType == 'movie') {
                $items[ ] = array(
                    'title' => $video->originalTitle,
                    'service' => 'voddler',
                    'type' => 'movie',
                    'url' => $video->url
                );
            }
        }

        return $items;
    }

}

?>