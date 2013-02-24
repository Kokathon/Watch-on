<?php



Class Voddler {
	private static $BASE_URL = 'http://api.voddler.com/metaapi/';

	public function findMovies($q) {
		$url = self::$BASE_URL . 'search/1?offset=0&count=50&q=' . $q;
		$data = file_get_contents($url);
		$obj = json_decode($data);

		$movies = array();

		foreach ($obj->data->videos as $video) {
			if ($video->videoType == 'movie') {
				$movies[] = array(
						'title' => $video->originalTitle,
						'service' => 'voddler',
						'type' => 'movie'
					);
			}
		}

		return $movies;
	}
}

?>