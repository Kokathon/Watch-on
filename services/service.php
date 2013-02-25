<?php

abstract class Service {

	abstract public function findMovies($term);
	abstract public function findTv($term);
	abstract public function findAll($term);
		
}


?>