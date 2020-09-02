<?php


namespace KgBot\SO24\Classmaps;


use Illuminate\Support\Collection;

interface ResponseInterface
{
	/**
	 * @return Collection|object
	 */
	public function getResults();
}