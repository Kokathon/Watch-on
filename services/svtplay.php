<?php

require_once ( 'service.php' );
require_once ( 'indexable.php' );

class svtplay extends Service implements Indexable {
	
	private static $BASE_URL = "http://www.svtplay.se/program";

	public function indexTv() {
		$page = file_get_contents(self::$BASE_URL);

		preg_match_all('/<li class=\"playListItem.+?<a href=\"(.+?)\".+?>(.+?)<\/a>/s', $page, $matches);

		$num = count($matches[0]);

		// connect to the db
		$m = new MongoClient();
		$db = $m->watchon;
		$collection = $db->svtplaytv;

		// empty the collection
		$collection->remove();

		// add each record
		for($i = 0; $i < $num; ++$i) {
			$tv = array(
				'title' => $matches[2][$i],
				'url' => 'http://www.svtplay.se' . $matches[1][$i],
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
    	$collection = $db->svtplaytv;

    	$condition = new MongoRegex('/.*' . $tem . '.*/i');
    	$result = $collection->find(array(
    		'title' => $condition
    		));

    	$tv = array();

    	foreach ($results as $show) {
    		$tv[] = $show;
    	}

    	return $tv;
    }

}

?>