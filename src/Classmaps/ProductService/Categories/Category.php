<?php


namespace KgBot\SO24\Classmaps\ProductService\Categories;


use KgBot\SO24\Classmaps\SingleResource;

class Category extends SingleResource
{
	/** @var int */
	public $Id;
	/** @var string */
	public $Name;
	/** @var string */
	public $No;
	/** @var int */
	public $ParentId;
}