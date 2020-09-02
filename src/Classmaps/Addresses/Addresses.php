<?php


namespace KgBot\SO24\Classmaps\Addresses;

use KgBot\SO24\Classmaps\SingleResource;

class Addresses extends SingleResource
{
	/** @var Address */
	public $Post;
	/** @var Address */
	public $Delivery;
	/** @var Address */
	public $Visit;
	/** @var Address */
	public $Invoice;
}