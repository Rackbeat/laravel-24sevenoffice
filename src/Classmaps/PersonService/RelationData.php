<?php


namespace KgBot\SO24\Classmaps\PersonService;


use KgBot\SO24\Classmaps\SingleResource;

class RelationData extends SingleResource
{
	/** @var int */
	public $ContactId;
	/** @var int */
	public $CustomerId;
	/** @var string */
	public $Title;
	/** @var string */
	public $Email;
	/** @var string */
	public $Phone;
	/** @var string */
	public $Mobile;
}