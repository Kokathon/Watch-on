<?php

require_once ( 'service.php' );
require_once ( 'indexable.php' );

class tv4play extends Service implements Indexable {
	
	private static $BASE_URL = "http://www.tv4play.se/program?per_page=999&per_row=4&page=1&content-type=a-o";

	public function indexTv() {
		$page = file_get_contents(self::$BASE_URL);

		preg_match('/<section class=\"row video\-collection program\_format\_panel\">(.+?)<\/section>/s', $page, $aolist);
		preg_match_all('/<li><a href=\"(\/.+?)\">(.+?)<\/a><\/li>/s', $aolist[1], $matches);

		$num = count($matches[0]);

		// connect to the db
		$m = new MongoClient();
		$db = $m->watchon;
		$collection = $db->tv4playtv;

		// empty the collection
		$collection->remove();

		// add each record
		for($i = 0; $i < $num; ++$i) {
			$tv = array(
				'title' => $matches[2][$i],
				'url' => 'http://www.tv4play.se' . $matches[1][$i],
				'type' => 'tv'
				);

			$collection->insert($tv);
		}

	}


	public function createIndex() {
		$this->indexTv();
	}

    public function findTv($term) {
    	$m = new MongoClient();
    	$db = $m->watchon;
    	$collection = $db->tv4playtv;

    	$condition = new MongoRegex('/.*' . $term . '.*/i');
    	$result = $collection->find(array(
    		'title' => $condition
    		));

    	$tv = array();

    	foreach ($result as $show) {
    		$tv[] = $show;
    	}

    	return $tv;
    }

}

?>