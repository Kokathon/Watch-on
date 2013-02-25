<?php

abstract class Service {

	abstract public function findMovies($term);
	abstract public function findTv($term);

	public function findAll($term) {
		return array_merge($this->findMovies($term), $this->findTv($term));
	}
		
}


?>